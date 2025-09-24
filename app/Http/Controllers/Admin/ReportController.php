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

        return view('admin.reports.index', compact('user', 'tasks', 'projects', 'statuses', 'priorities', 'users'));
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

        switch ($format) {
            case 'pdf':
                return Pdf::loadView('admin.reports.export_pdf', compact('tasks'))
                    ->download('tasks_report.pdf');

            case 'excel':
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Headings
                $headers = ['ID', 'Title', 'Project', 'Status', 'Priority', 'Tag', 'Assigned To', 'Due Date', 'Created At'];
                $sheet->fromArray($headers, null, 'A1');

                // Data rows
                $row = 2;
                foreach ($tasks as $t) {
                    $sheet->setCellValue("A$row", $t->task_id);
                    $sheet->setCellValue("B$row", $t->title);
                    $sheet->setCellValue("C$row", $t->project_name ?? '-');
                    $sheet->setCellValue("D$row", $t->status_name ?? '-');
                    $sheet->setCellValue("E$row", $t->priority_name ?? '-');
                    $sheet->setCellValue("F$row", $t->tag_name ?? '-');
                    $sheet->setCellValue("G$row", $t->assigned_user_name ?? '-');
                    $sheet->setCellValue("H$row", $t->due_date ?? '-');
                    $sheet->setCellValue("I$row", $t->created_at);
                    $row++;
                }

                // Auto size columns
                foreach (range('A', 'I') as $col) {
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
                $headers = ['ID', 'Title', 'Project', 'Status', 'Priority', 'Assigned To', 'Due Date'];
                $table->addRow();
                foreach ($headers as $h) {
                    $table->addCell(2000)->addText($h, ['bold' => true]);
                }

                foreach ($tasks as $index => $t) {
                    $table->addRow();
                    $bgColor = $index % 2 === 0 ? 'EEEEEE' : 'FFFFFF';
                    $table->addCell(2000, ['bgColor' => $bgColor])->addText($t->task_id);
                    $table->addCell(3000, ['bgColor' => $bgColor])->addText($t->title);
                    $table->addCell(2000, ['bgColor' => $bgColor])->addText($t->project_name ?? '-');
                    $table->addCell(2000, ['bgColor' => $bgColor])->addText($t->status_name ?? '-');
                    $table->addCell(2000, ['bgColor' => $bgColor])->addText($t->priority_name ?? '-');
                    $table->addCell(2000, ['bgColor' => $bgColor])->addText($t->assigned_user_name ?? '-');
                    $table->addCell(2000, ['bgColor' => $bgColor])->addText($t->due_date ?? '-');
                }

                $file = storage_path('tasks_report.docx');
                $phpWord->save($file, 'Word2007');
                return response()->download($file)->deleteFileAfterSend(true);

            case 'ppt':
                $ppt = new PhpPresentation();
                $slide = $ppt->getActiveSlide();

                $titleShape = $slide->createRichTextShape()
                    ->setHeight(50)->setWidth(700)->setOffsetX(50)->setOffsetY(20);
                $titleRun = $titleShape->createTextRun('Tasks Report');
                $titleRun->getFont()->setBold(true)->setSize(20)->setColor(new Color('0000FF'));

                $y = 100;
                foreach ($tasks as $index => $t) {
                    $shape = $slide->createRichTextShape()->setHeight(40)->setWidth(700)->setOffsetX(50)->setOffsetY($y);
                    $textRun = $shape->createTextRun(
                        "{$t->task_id} | {$t->title} | {$t->project_name} | {$t->status_name} | {$t->priority_name} | {$t->assigned_user_name}"
                    );
                    $textRun->getFont()->setSize(14)->setColor(new Color('333333'));
                    $shape->getFill()->setFillType(Fill::FILL_SOLID)
                        ->setStartColor(new Color($index % 2 === 0 ? 'DDDDDD' : 'FFFFFF'));
                    $y += 50;
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
