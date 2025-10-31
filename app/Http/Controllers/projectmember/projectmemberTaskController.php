<?php

namespace App\Http\Controllers\ProjectMember;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\NotificationService;


class projectmemberTaskController extends Controller
{
    // List tasks assigned to this project member
    public function index(Request $request)
    {
        $user = User::current();

        $tasks = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->leftJoin('users as assigner', 'tasks.created_by', '=', 'assigner.id')
            ->leftJoin('roles', 'assigner.role_id', '=', 'roles.id')
            ->select(
                'tasks.*',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'projects.name as project_name',
                'assigner.name as assigned_by_name',
                'roles.name as assigned_by_role'
            )
            ->where('tasks.assigned_to', $user->id)
            ->when($request->status_id, fn($q) => $q->where('tasks.status_id', $request->status_id))
            ->when($request->priority_id, function ($q) use ($request) {
                $q->where('priorities.name', $request->priority_id);
            })
            ->when($request->due_date, fn($q) => $q->whereDate('tasks.due_date', $request->due_date))
            ->when($request->project_id, fn($q) => $q->where('tasks.project_id', $request->project_id))
            ->get();

        $statuses   = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $projects   = DB::table('projects')->get();

        return view('projectmember.tasks.index', compact('tasks', 'user', 'statuses', 'priorities', 'projects', 'request'));
    }

    // Show task details
    public function show($task_id)
    {
        $user = User::current();

        $task = DB::table('tasks')
            ->leftJoin('users as assigner', 'tasks.created_by', '=', 'assigner.id')
            ->leftJoin('roles', 'assigner.role_id', '=', 'roles.id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->select(
                'tasks.*',
                'assigner.name as assigned_by_name',
                'roles.name as assigned_by_role',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'projects.name as project_name'
            )
            ->where('tasks.task_id', $task_id)
            ->where('tasks.assigned_to', $user->id)
            ->first();

        if (!$task) {
            return redirect()->route('projectmember.tasks.index')->with('error', 'Unauthorized to view this task.');
        }

        $comments = DB::table('comments')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('comments.task_id', $task->task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select('comments.*', 'users.name', 'roles.name as role_name')
            ->get();

        return view('projectmember.tasks.show', compact('task', 'user', 'comments'));
    }

    // Update status (AJAX)
    public function updateStatus(Request $request, $task_id)
    {
        $user = User::current();

        $task = DB::table('tasks')->where('task_id', $task_id)->first();

        if (!$task || $task->assigned_to != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::table('tasks')->where('task_id', $task_id)->update([
            'status_id' => $request->status_id,
            'updated_at' => now()
        ]);

        $task = DB::table('tasks')->where('task_id', $task_id)->first();

        // Send status changed notification to admin/project manager
        NotificationService::statusChanged($task, DB::table('statuses')->where('status_id', $request->status_id)->value('name'));


        return response()->json(['success' => true]);
    }
}
