<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class TaskController extends Controller
{
    // List tasks assigned to this user
    public function index(Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        // Fetch all projects for dropdown filter
        $projects = DB::table('projects')->get();

        $tasks = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->leftJoin('users as assigner', 'tasks.created_by', '=', 'assigner.id') // assigner info
            ->leftJoin('roles', 'assigner.id', '=', 'roles.id')         // assigner role
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
            ->when($request->priority_id, fn($q) => $q->where('tasks.priority_id', $request->priority_id))
            ->when($request->due_date, fn($q) => $q->whereDate('tasks.due_date', $request->due_date))
            ->when($request->project_id, fn($q) => $q->where('tasks.project_id', $request->project_id))
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
            ->leftJoin('users as assigner', 'tasks.created_by', '=', 'assigner.id')
            ->leftJoin('roles', 'assigner.role_id', '=', 'roles.id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->select(
                'tasks.*',
                'assigner.name as assigned_by_name',
                'roles.name as assigned_by_role',    // <-- assigner role
                'statuses.name as status_name',
                'priorities.name as priority_name'
            )
            ->where('tasks.task_id', $task_id)
            ->where('tasks.assigned_to', $user->id)
            ->first();

        if (!$task) {
            return redirect()->route('user.tasks.index')->with('error', 'Unauthorized to view this task.');
        }

        $comments = DB::table('comments')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('comments.task_id', $task->task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select(
                'comments.*',
                'users.name as name',
                'roles.name as role'
            )
            ->get();



        return view('user.tasks.show', compact('task', 'user', 'comments'));
    }

    // Edit task
    public function edit($task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $task_id)->where('assigned_to', $user->id)->first();
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

        try {
            $request->validate([
                'title'       => 'required|max:255',
                'description' => 'nullable|string',
                'status_id'   => 'required|integer',
                'tag_id'      => 'nullable|integer',
                'project_id'  => 'required|integer|exists:projects,project_id',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $task = DB::table('tasks')->where('task_id', $task_id)->where('assigned_to', $user->id)->first();
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

}
