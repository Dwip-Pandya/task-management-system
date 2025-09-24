<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // List tasks assigned to this user
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch all projects for dropdown filter
        $projects = DB::table('projects')->get();

        $tasks = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id') // join projects
            ->select(
                'tasks.*',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'projects.name as project_name'
            )
            ->where('tasks.assigned_to', $user->user_id)
            ->when($request->status_id, fn($q) => $q->where('tasks.status_id', $request->status_id))
            ->when($request->priority_id, fn($q) => $q->where('tasks.priority_id', $request->priority_id))
            ->when($request->due_date, fn($q) => $q->whereDate('tasks.due_date', $request->due_date))
            ->when($request->project_id, fn($q) => $q->where('tasks.project_id', $request->project_id)) // <-- filter by project
            ->get();

        $statuses   = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();

        return view('user.tasks.index', compact('tasks', 'user', 'statuses', 'priorities', 'projects', 'request'));
    }

    // Show single task
    public function show($task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')
            ->leftJoin('tbl_user as created_by_user', 'tasks.created_by', '=', 'created_by_user.user_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->select(
                'tasks.*',
                'created_by_user.name as created_by_name',
                'statuses.name as status_name',
                'priorities.name as priority_name'
            )
            ->where('tasks.task_id', $task_id)
            ->where('tasks.assigned_to', $user->user_id)
            ->first();

        if (!$task) {
            return redirect()->route('user.tasks.index')->with('error', 'Unauthorized to view this task.');
        }

        $comments = DB::table('comments')
            ->leftJoin('tbl_user', 'comments.user_id', '=', 'tbl_user.user_id')
            ->where('comments.task_id', $task->task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select('comments.*', 'tbl_user.name', 'tbl_user.role')
            ->get();

        return view('user.tasks.show', compact('task', 'user', 'comments'));
    }

    // Edit task
    public function edit($task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $task_id)->where('assigned_to', $user->user_id)->first();
        if (!$task) {
            return redirect()->route('user.tasks.index')->with('error', 'Unauthorized to edit this task.');
        }

        $statuses   = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $projectsList = DB::table('projects')->get();

        return view('user.tasks.edit', compact('task', 'user', 'statuses', 'priorities', 'projectsList'));
    }

    // Update task
    public function update(Request $request, $task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $task_id)->where('assigned_to', $user->user_id)->first();
        if (!$task) {
            return redirect()->route('user.tasks.index')->with('error', 'Unauthorized update.');
        }

        DB::table('tasks')->where('task_id', $task_id)->update([
            'title'       => $request->title,
            'description' => $request->description,
            'status_id'   => $request->status_id,
            'project_id'  => $request->project_id,
            'due_date'    => $request->due_date,
        ]);

        return redirect()->route('user.tasks.index')->with('success', 'Task updated successfully.');
    }

    // Delete task
    public function destroy($task_id)
    {
        //
    }

    // Update task status (AJAX)
    public function updateStatus(Request $request, $task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $task_id)->first();

        if (!$task || $task->assigned_to != $user->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::table('tasks')->where('task_id', $task_id)->update([
            'status_id' => $request->status_id
        ]);

        return response()->json(['success' => true]);
    }
}
