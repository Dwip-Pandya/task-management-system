<?php

namespace App\Http\Controllers\Admin;

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

class ReportController extends Controller
{
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

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = DB::table('tasks')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.tag_id')
            ->leftJoin('tbl_user', 'tasks.assigned_to', '=', 'tbl_user.user_id')
            ->select(
                'tasks.*',
                'projects.name as project_name',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'tags.name as tag_name',
                'tbl_user.name as assigned_user_name'
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
        $users = DB::table('tbl_user')->select('user_id', 'name')->get();

        // Columns selected by user (default: all)
        $selectedColumns = $request->input('columns', array_keys($this->allColumns));

        return view('admin.reports.index', [
            'user' => $user,
            'tasks' => $tasks,
            'projects' => $projects,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'users' => $users,
            'allColumns' => $this->allColumns,
            'selectedColumns' => $selectedColumns,
        ]);
    }

    public function export(Request $request, $format)
    {
        $query = DB::table('tasks')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.tag_id')
            ->leftJoin('tbl_user', 'tasks.assigned_to', '=', 'tbl_user.user_id')
            ->select(
                'tasks.*',
                'projects.name as project_name',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'tags.name as tag_name',
                'tbl_user.name as assigned_user_name'
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
                return Pdf::loadView('admin.reports.export_pdf', [
                    'tasks' => $tasks,
                    'selectedColumns' => $columns,
                    'allColumns' => $allColumns
                ])->download('tasks_report.pdf');


            case 'excel':
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Headers
                $headers = array_map(fn($c) => $allColumns[$c], $columns);
                $sheet->fromArray($headers, null, 'A1');

                // Rows
                $row = 2;
                foreach ($tasks as $t) {
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

                foreach (range('A', 'Z') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $file = storage_path('tasks_report.xlsx');
                $writer = new Xlsx($spreadsheet);
                $writer->save($file);

                return response()->download($file)->deleteFileAfterSend(true);

            case 'word':
                $phpWord = new PhpWord();
                $section = $phpWord->addSection();

                $tableStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 50];
                $firstRowStyle = ['bgColor' => 'CCCCCC'];
                $phpWord->addTableStyle('TaskTable', $tableStyle, $firstRowStyle);

                $table = $section->addTable('TaskTable');

                $table->addRow();
                foreach ($columns as $col) {
                    $table->addCell(2000)->addText($allColumns[$col], ['bold' => true]);
                }

                foreach ($tasks as $index => $t) {
                    $table->addRow();
                    foreach ($columns as $col) {
                        $value = $t->$col ?? '-';
                        if (in_array($col, ['due_date', 'created_at'])) {
                            $value = $t->$col ? date('d-m-Y', strtotime($t->$col)) : '-';
                        }
                        $table->addCell(2000)->addText($value);
                    }
                }

                $file = storage_path('tasks_report.docx');
                $phpWord->save($file, 'Word2007');
                return response()->download($file)->deleteFileAfterSend(true);

            case 'ppt':
                $ppt = new PhpPresentation();
                $slide = $ppt->getActiveSlide();

                // Title
                $titleShape = $slide->createRichTextShape()
                    ->setHeight(50)->setWidth(900)->setOffsetX(20)->setOffsetY(20);
                $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);
                $titleRun = $titleShape->createTextRun('Tasks Report');
                $titleRun->getFont()->setBold(true)->setSize(22)->setColor(new Color('0000FF'));

                // Header background rectangle with text
                $headerBox = $slide->createRichTextShape()
                    ->setHeight(30)->setWidth(1000)->setOffsetX(20)->setOffsetY(80);
                $headerBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('CCCCCC'));
                $headerBox->getActiveParagraph()->getAlignment()->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);

                $headerText = $headerBox->createTextRun(implode(' | ', array_map(fn($c) => $allColumns[$c], $columns)));
                $headerText->getFont()->setBold(true)->setSize(14)->setColor(new Color('000000'));

                // Data rows
                $y = 120;
                foreach ($tasks as $index => $t) {
                    $rowData = [];
                    foreach ($columns as $col) {
                        $value = $t->$col ?? '-';
                        if (in_array($col, ['due_date', 'created_at'])) {
                            $value = $t->$col ? date('d-m-Y', strtotime($t->$col)) : '-';
                        }
                        $rowData[] = $value;
                    }
                    $rowText = implode(' | ', $rowData);

                    $rowShape = $slide->createRichTextShape()
                        ->setHeight(25)->setWidth(1000)->setOffsetX(20)->setOffsetY($y);
                    $rowShape->createTextRun($rowText)->getFont()->setSize(12)->setColor(new Color('333333'));

                    // alternating background
                    $rowShape->getFill()->setFillType(Fill::FILL_SOLID)
                        ->setStartColor(new Color($index % 2 === 0 ? 'F5F5F5' : 'FFFFFF'));

                    $y += 30;
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
