<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class projectmanagerTaskController extends Controller
{
    // List tasks in projects created by this project manager
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch tasks in projects managed by this PM
        $tasksQuery = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.tag_id')
            ->leftJoin('users as assigned_user', 'tasks.assigned_to', '=', 'assigned_user.id')
            ->leftJoin('roles as assigned_role', 'assigned_user.role_id', '=', 'assigned_role.id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->select(
                'tasks.*',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'tags.name as tag_name',
                'assigned_user.name as assigned_user_name',
                'assigned_role.name as assigned_role_name',
                'projects.name as project_name'
            )
            ->whereIn('tasks.project_id', function ($query) use ($user) {
                $query->select('project_id')
                    ->from('projects')
                    ->where('created_by', $user->id);
            });

        // Optional filters
        if ($request->status_id) {
            $tasksQuery->where('tasks.status_id', $request->status_id);
        }
        if ($request->priority_id) {
            $tasksQuery->where('tasks.priority_id', $request->priority_id);
        }
        if ($request->project_id) {
            $tasksQuery->where('tasks.project_id', $request->project_id);
        }
        if ($request->due_date) {
            $tasksQuery->whereDate('tasks.due_date', $request->due_date);
        }

        $tasks = $tasksQuery->get();

        $statuses   = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $projects   = DB::table('projects')->where('created_by', $user->id)->get();

        return view('projectmanager.tasks.index', compact('tasks', 'user', 'statuses', 'priorities', 'projects', 'request'));
    }

    // Show task details
    public function show($task_id)
    {
        $user = Auth::user();

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
            ->whereIn('tasks.project_id', function ($query) use ($user) {
                $query->select('project_id')->from('projects')->where('created_by', $user->id);
            })
            ->first();

        if (!$task) {
            return redirect()->route('projectmanager.tasks.index')->with('error', 'Task not found or unauthorized.');
        }

        $comments = DB::table('comments')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('comments.task_id', $task->task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select('comments.*', 'users.name', 'roles.name as role_name')
            ->get();

        return view('projectmanager.tasks.show', compact('task', 'user', 'comments'));
    }

    // Create task
    public function create()
    {
        $user = Auth::user();
        $statuses   = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags       = DB::table('tags')->get();
        $projects   = DB::table('projects')->where('created_by', $user->id)->get();
        $users      = DB::table('users')->get();

        return view('projectmanager.tasks.create', compact('user', 'statuses', 'priorities', 'tags', 'projects', 'users'));
    }

    // Store task
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|integer',
            'status_id'   => 'required|integer',
            'priority_id' => 'required|integer',
            'tag_id'      => 'nullable|integer',
            'project_id'  => 'required|integer|exists:projects,project_id',
            'due_date'    => 'nullable|date',
        ]);

        // Ensure task belongs to PM's project
        $project = DB::table('projects')->where('project_id', $request->project_id)->where('created_by', $user->id)->first();
        if (!$project) {
            return redirect()->route('projectmanager.tasks.index')->with('error', 'Unauthorized project selection.');
        }

        DB::table('tasks')->insert([
            'title'       => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status_id'   => $request->status_id,
            'priority_id' => $request->priority_id,
            'tag_id'      => $request->tag_id,
            'project_id'  => $request->project_id,
            'created_by'  => $user->id,
            'due_date'    => $request->due_date,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('projectmanager.tasks.index')->with('success', 'Task created successfully.');
    }

    // Edit task
    public function edit($task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $task_id)
            ->whereIn('project_id', function ($q) use ($user) {
                $q->select('project_id')->from('projects')->where('created_by', $user->id);
            })->first();

        if (!$task) return redirect()->route('projectmanager.tasks.index')->with('error', 'Unauthorized edit.');

        $statuses   = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags       = DB::table('tags')->get();
        $projects   = DB::table('projects')->where('created_by', $user->id)->get();
        $users      = DB::table('users')->get();

        return view('projectmanager.tasks.edit', compact('task', 'user', 'statuses', 'priorities', 'tags', 'projects', 'users'));
    }

    // Update task
    public function update(Request $request, $task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $task_id)
            ->whereIn('project_id', function ($q) use ($user) {
                $q->select('project_id')->from('projects')->where('created_by', $user->id);
            })->first();

        if (!$task) return redirect()->route('projectmanager.tasks.index')->with('error', 'Unauthorized update.');

        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|integer',
            'status_id'   => 'required|integer',
            'priority_id' => 'required|integer',
            'tag_id'      => 'nullable|integer',
            'project_id'  => 'required|integer|exists:projects,project_id',
            'due_date'    => 'nullable|date',
        ]);

        DB::table('tasks')->where('task_id', $task_id)->update([
            'title'       => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status_id'   => $request->status_id,
            'priority_id' => $request->priority_id,
            'tag_id'      => $request->tag_id,
            'project_id'  => $request->project_id,
            'due_date'    => $request->due_date,
            'updated_at'  => now(),
        ]);

        return redirect()->route('projectmanager.tasks.index')->with('success', 'Task updated successfully.');
    }

    // Deletion disabled
    public function destroy($task_id)
    {
        return redirect()->route('projectmanager.tasks.index')->with('error', 'Task deletion is disabled.');
    }

    // AJAX: Update Status
    public function updateStatus(Request $request, $task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $task_id)
            ->whereIn('project_id', function ($q) use ($user) {
                $q->select('project_id')->from('projects')->where('created_by', $user->id);
            })->first();

        if (!$task) return response()->json(['error' => 'Unauthorized'], 403);

        DB::table('tasks')->where('task_id', $task_id)->update([
            'status_id' => $request->status_id,
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
}
