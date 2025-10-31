<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use App\Services\PermissionService;

class ReportController extends Controller
{
    protected $permissions;
    protected $module = 'report generation';

    protected $allColumns = [
        'task_id' => 'Task ID',
        'title' => 'Title',
        'description' => 'Description',
        'project_name' => 'Project',
        'status_name' => 'Status',
        'priority_name' => 'Priority',
        'tag_name' => 'Tag',
        'assigned_user_name' => 'Assigned To',
        'created_at' => 'Created At',
        'due_date' => 'Due Date',
    ];

    public function __construct(PermissionService $permissions)
    {
        $this->permissions = $permissions;
    }

    public function index(Request $request)
    {
        $user = User::current();

        $permissions = $this->permissions->getAllPermissions($this->module);

        if (!$permissions['can_view']) {
            return response()->view('errors.permission-denied', [], 403);
        }

        $query = DB::table('tasks')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.tag_id')
            ->leftJoin('users', 'tasks.assigned_to', '=', 'users.id')
            ->select(
                'tasks.*',
                'projects.name as project_name',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'tags.name as tag_name',
                'users.name as assigned_user_name'
            );

        if ($request->filled('project_id')) $query->where('tasks.project_id', $request->project_id);
        if ($request->filled('status_id')) $query->where('tasks.status_id', $request->status_id);
        if ($request->filled('priority_id')) $query->where('tasks.priority_id', $request->priority_id);
        if ($request->filled('assigned_to')) $query->where('tasks.assigned_to', $request->assigned_to);
        if ($request->filled('from_date') && $request->filled('to_date'))
            $query->whereBetween('tasks.due_date', [$request->from_date, $request->to_date]);

        $tasks = $query->orderBy('tasks.task_id', 'asc')->paginate(5)->withQueryString();

        $projects = DB::table('projects')->select('project_id', 'name')->get();
        $statuses = DB::table('statuses')->select('status_id', 'name')->get();
        $priorities = DB::table('priorities')->select('priority_id', 'name')->get();
        $users = DB::table('users')->select('id', 'name')->get();

        // Columns selected by user (default: all)
        $selectedColumns = $request->input('columns', array_keys($this->allColumns));

        return view('reports.index', [
            'user' => $user,
            'tasks' => $tasks,
            'projects' => $projects,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'users' => $users,
            'allColumns' => $this->allColumns,
            'selectedColumns' => $selectedColumns,
            'permissions' => $permissions,
        ]);
    }

    public function export(Request $request, $format)
    {
        $query = DB::table('tasks')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.tag_id')
            ->leftJoin('users', 'tasks.assigned_to', '=', 'users.id')
            ->select(
                'tasks.*',
                'projects.name as project_name',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'tags.name as tag_name',
                'users.name as assigned_user_name'
            );

        if ($request->filled('project_id')) $query->where('tasks.project_id', $request->project_id);
        if ($request->filled('status_id')) $query->where('tasks.status_id', $request->status_id);
        if ($request->filled('priority_id')) $query->where('tasks.priority_id', $request->priority_id);
        if ($request->filled('assigned_to')) $query->where('tasks.assigned_to', $request->assigned_to);
        if ($request->filled('from_date') && $request->filled('to_date'))
            $query->whereBetween('tasks.due_date', [$request->from_date, $request->to_date]);

        $tasks = $query->get();

        $allColumns = $this->allColumns;
        $columns = $request->input('columns', array_keys($allColumns));

        switch ($format) {
            case 'pdf':
                $projects = $tasks->groupBy('project_name');

                // Get filter names - collect all active filters
                $filterDetails = [];

                if ($request->filled('project_id')) {
                    $filterDetails['Project'] = DB::table('projects')
                        ->where('project_id', $request->project_id)
                        ->value('name');
                }

                if ($request->filled('status_id')) {
                    $filterDetails['Status'] = DB::table('statuses')
                        ->where('status_id', $request->status_id)
                        ->value('name');
                }

                if ($request->filled('priority_id')) {
                    $filterDetails['Priority'] = DB::table('priorities')
                        ->where('priority_id', $request->priority_id)
                        ->value('name');
                }

                if ($request->filled('assigned_to')) {
                    $filterDetails['Assigned To'] = DB::table('users')
                        ->where('id', $request->assigned_to)
                        ->value('name');
                }

                if ($request->filled('from_date') && $request->filled('to_date')) {
                    $filterDetails['Date Range'] = $request->from_date . ' to ' . $request->to_date;
                }

                return Pdf::loadView('reports.export_pdf', [
                    'tasks' => $tasks,
                    'projects' => $projects,
                    'selectedColumns' => $columns,
                    'allColumns' => $allColumns,
                    'filters' => $filterDetails,
                ])->setPaper('a4', 'portrait')->download('tasks_report.pdf');


            case 'excel':
                $spreadsheet = new Spreadsheet();
                $spreadsheet->removeSheetByIndex(0);

                $projects = $tasks->groupBy('project_name');

                foreach ($projects as $projectName => $projectTasks) {
                    $sheet = $spreadsheet->createSheet();
                    $sheet->setTitle(substr($projectName ?: 'Unknown Project', 0, 31));

                    // Header
                    $headers = array_map(fn($c) => $allColumns[$c], $columns);
                    $sheet->fromArray($headers, null, 'A1');

                    // Apply header styling
                    $headerStyle = [
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '4A90E2'],
                        ],
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                            'size' => 12,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ];
                    $sheet->getStyle('A1:' . chr(64 + count($headers)) . '1')->applyFromArray($headerStyle);

                    // Rows
                    $row = 2;
                    foreach ($projectTasks as $t) {
                        $data = [];
                        foreach ($columns as $col) {
                            $value = $t->$col ?? '-';
                            if (in_array($col, ['due_date', 'created_at'])) {
                                $value = $t->$col ? date('d-m-Y', strtotime($t->$col)) : '-';
                            }
                            $data[] = $value;
                        }
                        $sheet->fromArray($data, null, "A$row");
                        $row++;
                    }

                    // Add alternating row background
                    for ($r = 2; $r < $row; $r++) {
                        if ($r % 2 === 0) {
                            $sheet->getStyle("A$r:" . chr(64 + count($headers)) . "$r")->applyFromArray([
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F2F2F2'],
                                ]
                            ]);
                        }
                    }

                    // Auto-size columns
                    foreach (range('A', chr(64 + count($headers))) as $col) {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                    }

                    // Add border to all cells
                    $sheet->getStyle("A1:" . chr(64 + count($headers)) . ($row - 1))->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '999999'],
                            ],
                        ],
                    ]);
                }

                $writer = new Xlsx($spreadsheet);
                $file = storage_path('tasks_report.xlsx');
                $writer->save($file);
                return response()->download($file)->deleteFileAfterSend(true);


            case 'word':
                $phpWord = new PhpWord();

                // Define styles
                $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 20, 'color' => '2c3e50']);
                $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14, 'color' => 'FFFFFF'], ['bgColor' => '667eea']);

                $tableStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 50];
                $firstRowStyle = ['bgColor' => 'f0f3f8'];
                $phpWord->addTableStyle('TaskTable', $tableStyle, $firstRowStyle);

                // Group tasks by project
                $projects = $tasks->groupBy('project_name');

                // Get filter details - collect all active filters
                $filterDetails = [];

                if ($request->filled('project_id')) {
                    $filterDetails['Project'] = DB::table('projects')
                        ->where('project_id', $request->project_id)
                        ->value('name');
                }

                if ($request->filled('status_id')) {
                    $filterDetails['Status'] = DB::table('statuses')
                        ->where('status_id', $request->status_id)
                        ->value('name');
                }

                if ($request->filled('priority_id')) {
                    $filterDetails['Priority'] = DB::table('priorities')
                        ->where('priority_id', $request->priority_id)
                        ->value('name');
                }

                if ($request->filled('assigned_to')) {
                    $filterDetails['Assigned To'] = DB::table('users')
                        ->where('id', $request->assigned_to)
                        ->value('name');
                }

                if ($request->filled('from_date') && $request->filled('to_date')) {
                    $filterDetails['Date Range'] = $request->from_date . ' to ' . $request->to_date;
                }

                $isFirstProject = true;

                foreach ($projects as $projectName => $projectTasks) {
                    // Add section for each project (new page for each project except first)
                    if ($isFirstProject) {
                        $section = $phpWord->addSection();

                        // Add Report Title (only on first page)
                        $section->addTitle('Tasks Report', 1);
                        $section->addTextBreak(1);

                        // Add Filters Section (only on first page)
                        $filterTable = $section->addTable([
                            'borderSize' => 6,
                            'borderColor' => '3498db',
                            'cellMargin' => 80,
                            'width' => 100 * 50
                        ]);

                        if (count($filterDetails) > 0) {
                            foreach ($filterDetails as $label => $value) {
                                $filterTable->addRow();
                                $filterTable->addCell(2000)->addText($label . ':', ['bold' => true, 'color' => '2c3e50']);
                                $filterTable->addCell(6000)->addText($value, ['color' => '555555']);
                            }
                        } else {
                            $filterTable->addRow();
                            $filterTable->addCell(2000)->addText('Filters:', ['bold' => true, 'color' => '2c3e50']);
                        }

                        $section->addTextBreak(1);
                        $isFirstProject = false;
                    } else {
                        // New page for each new project
                        $section = $phpWord->addSection(['breakType' => 'nextPage']);
                    }

                    // Add Project Header
                    $projectHeader = $section->addTextRun([
                        'bgColor' => '667eea',
                        'spacing' => 0
                    ]);
                    $section->addText(
                        'Project: ' . ($projectName ?? 'No Project Assigned'),
                        [
                            'bold' => true,
                            'size' => 14,
                            'color' => '#ffffffff'
                        ],
                        [
                            'bgColor' => '667eea',
                            'spaceBefore' => 100,
                            'spaceAfter' => 100,
                            'indentation' => ['left' => 200, 'right' => 200]
                        ]
                    );

                    $section->addTextBreak(1);

                    // Add Tasks Table for this project
                    $table = $section->addTable('TaskTable');

                    // Add Header Row
                    $table->addRow(400);
                    $table->addCell(800)->addText('#', ['bold' => true, 'color' => '2c3e50']);
                    foreach ($columns as $col) {
                        $table->addCell(2000)->addText($allColumns[$col], ['bold' => true, 'color' => '2c3e50']);
                    }

                    // Add Data Rows
                    foreach ($projectTasks as $index => $t) {
                        $table->addRow();
                        $table->addCell(800)->addText($index + 1);
                        foreach ($columns as $col) {
                            $value = $t->$col ?? '-';
                            if (in_array($col, ['due_date', 'created_at']) && $t->$col) {
                                $value = date('d-m-Y', strtotime($t->$col));
                            }
                            $table->addCell(2000)->addText($value);
                        }
                    }

                    $section->addTextBreak(1);
                }

                $file = storage_path('tasks_report.docx');
                $phpWord->save($file, 'Word2007');
                return response()->download($file)->deleteFileAfterSend(true);

            case 'ppt':
                $ppt = new PhpPresentation();

                // Group tasks by project
                $tasksByProject = [];
                foreach ($tasks as $t) {
                    $projectName = $t->project_name ?? 'No Project';
                    $tasksByProject[$projectName][] = $t;
                }

                foreach ($tasksByProject as $projectName => $projectTasks) {
                    $slide = $ppt->createSlide();

                    // Project title
                    $titleShape = $slide->createRichTextShape()
                        ->setHeight(50)->setWidth(900)->setOffsetX(20)->setOffsetY(20);
                    $titleShape->getActiveParagraph()->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);
                    $titleRun = $titleShape->createTextRun($projectName);
                    $titleRun->getFont()->setBold(true)->setSize(24)->setColor(new Color('1E90FF'));

                    // Optional filter info
                    $y = 100;
                    if (!empty($filters) && $projectName === array_key_first($tasksByProject)) {
                        $filterShape = $slide->createRichTextShape()
                            ->setHeight(30)->setWidth(900)->setOffsetX(20)->setOffsetY(80);
                        $filterShape->getActiveParagraph()->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);
                        $filterText = $filterShape->createTextRun('Filter applied: ' . implode(', ', $filters));
                        $filterText->getFont()->setItalic(true)->setSize(14)->setColor(new Color('FF8C00'));
                        $y = 120;
                    }

                    // Header
                    $headerShape = $slide->createRichTextShape()
                        ->setHeight(30)->setWidth(1000)->setOffsetX(20)->setOffsetY($y);
                    $headerShape->getFill()->setFillType(Fill::FILL_SOLID)
                        ->setStartColor(new Color('333333'));
                    $headerShape->getActiveParagraph()->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);
                    $headerText = $headerShape->createTextRun(implode(' | ', array_map(fn($c) => $allColumns[$c], $columns)));
                    $headerText->getFont()->setBold(true)->setSize(14)->setColor(new Color('FFFFFF'));
                    $y += 30;

                    // Data rows
                    foreach ($projectTasks as $index => $t) {
                        $rowData = [];
                        foreach ($columns as $col) {
                            $value = $t->$col ?? '-';
                            if (in_array($col, ['due_date', 'created_at'])) {
                                $value = $t->$col ? date('d-m-Y', strtotime($t->$col)) : '-';
                            }
                            $rowData[] = $value;
                        }

                        $rowShape = $slide->createRichTextShape()
                            ->setHeight(25)->setWidth(1000)->setOffsetX(20)->setOffsetY($y);
                        $rowShape->createTextRun(implode(' | ', $rowData))
                            ->getFont()->setSize(12)
                            ->setColor(new Color('000000'));
                        // alternating background
                        $rowShape->getFill()->setFillType(Fill::FILL_SOLID)
                            ->setStartColor(new Color($index % 2 === 0 ? 'F0F0F0' : 'FFFFFF'));
                        $y += 30;
                    }
                }

                $file = storage_path('tasks_report.pptx');
                $writer = IOFactory::createWriter($ppt, 'PowerPoint2007');
                $writer->save($file);

                return response()->download($file)->deleteFileAfterSend(true);

            default:
                return back()->with('error', 'Invalid format selected.');
        }
    }
}
