<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class TaskManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();
        // Start query
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
            );

        // Filter by user
        if ($user->role_id === 1) {
            if ($request->assigned_to) {
                $tasksQuery->where('tasks.assigned_to', $request->assigned_to);
            }
        } else {
            $tasksQuery->where('tasks.assigned_to', $request->assigned_to ?? $user->id);
        }

        // Filters
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

        // Users with role
        $usersList = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();

        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $projects = DB::table('projects')->get();

        return view('admin.tasks.index', compact('tasks', 'user', 'statuses', 'priorities', 'usersList', 'projects', 'request', 'tasksQuery'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags = DB::table('tags')->get();

        $users = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();

        $projects = DB::table('projects')->get();

        return view('admin.tasks.create', compact(
            'user',
            'statuses',
            'priorities',
            'tags',
            'users',
            'projects'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $user = Auth::user();

        DB::table('tasks')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'created_by' => $user->id,
            'status_id' => $request->status_id,
            'priority_id' => $request->priority_id,
            'tag_id' => $request->tag_id,
            'project_id' => $request->project_id,
            'due_date' => $request->due_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')->where('task_id', $id)->first();
        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags = DB::table('tags')->get();

        $users = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();

        $projects = DB::table('projects')->get();

        return view('admin.tasks.edit', compact(
            'task',
            'statuses',
            'priorities',
            'tags',
            'users',
            'projects',
            'user'
        ));
    }

    /**
     * Display the specified resource.
     */
    // Show task details to admin
    public function show($task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')
            ->leftJoin('users as created_by_user', 'tasks.created_by', '=', 'created_by_user.id')
            ->leftJoin('roles as created_role', 'created_by_user.role_id', '=', 'created_role.id')
            ->leftJoin('users as assigned_user', 'tasks.assigned_to', '=', 'assigned_user.id')
            ->leftJoin('roles as assigned_role', 'assigned_user.role_id', '=', 'assigned_role.id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->select(
                'tasks.*',
                'created_by_user.name as created_by_name',
                'created_role.name as created_by_role',
                'assigned_user.name as assigned_to_name',
                'assigned_role.name as assigned_to_role',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'projects.name as project_name'
            )
            ->where('tasks.task_id', $task_id)
            ->first();

        if (!$task) {
            return redirect()->route('admin.dashboard')->with('error', 'Task not found.');
        }

        // Fetch all comments
        $comments = DB::table('comments')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('comments.task_id', $task->task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select('comments.*', 'users.name', 'roles.name as role_name')
            ->get();

        return view('admin.tasks.show', compact('task', 'user', 'comments'));
    }

    /**
     * Update a task.
     */
    public function update(Request $request, $id)
    {
        try {
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
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::table('tasks')->where('task_id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status_id' => $request->status_id,
            'priority_id' => $request->priority_id,
            'tag_id' => $request->tag_id,
            'project_id' => $request->project_id,
            'due_date' => $request->due_date,
            'updated_at' => now(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Deletion disabled
     */
    public function destroy($id)
    {
        // Deletion disabled as per new requirements
        return redirect()->route('tasks.index')->with('error', 'Task deletion is disabled.');
    }

    // Update status, priority, assigned user (AJAX)
    public function updateAssigned(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        DB::table('tasks')->where('task_id', $id)->update([
            'assigned_to' => $request->assigned_to,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // Update Status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer|exists:statuses,status_id',
        ]);

        DB::table('tasks')->where('task_id', $id)->update([
            'status_id' => $request->status_id,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // Update Priority
    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority_id' => 'required|integer|exists:priorities,priority_id',
        ]);

        DB::table('tasks')->where('task_id', $id)->update([
            'priority_id' => $request->priority_id,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
