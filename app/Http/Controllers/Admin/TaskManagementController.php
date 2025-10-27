<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Services\NotificationService;

class TaskManagementController extends Controller
{
    /**
     * Utility: Check if the logged-in user has permission for the Task module.
     */
    private function hasPermission($action)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();
        if (!$user) return false;

        // Check user-specific permissions first
        $permission = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', 'task management')
            ->first();

        // Fallback to role default if no user override
        if (!$permission) {
            $permission = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', 'task management')
                ->first();
        }

        if (!$permission) return false;

        $field = 'can_' . $action;

        if (!property_exists($permission, $field)) {
            return true;
        }

        return $permission->$field == 1;
    }

    /**
     * Utility: Fetch all permissions for current user.
     */
    private function getAllPermissions()
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'can_view' => false,
                'can_add' => false,
                'can_edit' => false,
                'can_delete' => false,
            ];
        }

        // Fetch user-specific permissions first
        $perm = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', 'task management')
            ->first();

        // Fallback to role defaults
        if (!$perm) {
            $perm = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', 'task management')
                ->first();
        }

        return [
            'can_view' => $perm->can_view ?? false,
            'can_add' => $perm->can_add ?? false,
            'can_edit' => $perm->can_edit ?? false,
            'can_delete' => $perm->can_delete ?? false,
        ];
    }

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

        // Permissions
        $permissions = $this->getAllPermissions();

        return view('admin.tasks.index', compact('tasks', 'user', 'statuses', 'priorities', 'usersList', 'projects', 'request', 'tasksQuery', 'permissions'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();
        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags = DB::table('tags')->get();

        $users = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();

        $projects = DB::table('projects')->get();

        // Permissions
        $permissions = $this->getAllPermissions();
        if (!$permissions['can_add']) {
            if (!$permissions['can_add']) {
                return response()->view('errors.permission-denied', [], 403);
            }
        }

        return view('admin.tasks.create', compact(
            'user',
            'statuses',
            'priorities',
            'tags',
            'users',
            'projects',
            'permissions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->hasPermission('add')) {
            return redirect()->route('tasks.index')
                ->with('error', 'You do not have permission to add a task.');
        }

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

        $task = DB::table('tasks')
            ->where('created_by', $user->id)
            ->where('title', $request->title)
            ->latest()
            ->first();

        NotificationService::taskAssigned($task);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $task = DB::table('tasks')->where('task_id', $id)->first();
        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags = DB::table('tags')->get();

        $users = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();

        $projects = DB::table('projects')->get();

        // Permissions
        $permissions = $this->getAllPermissions();
        if (!$permissions['can_edit']) {
            return response()->view('errors.permission-denied', [], 403);
        }

        return view('admin.tasks.edit', compact(
            'task',
            'statuses',
            'priorities',
            'tags',
            'users',
            'projects',
            'user',
            'permissions'
        ));
    }

    /**
     * Display the specified resource.
     */
    // Show task details to admin
    public function show($task_id)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

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

        // Permissions
        $permissions = $this->getAllPermissions();
        if (!$permissions['can_view']) {
            return response()->view('errors.permission-denied', [], 403);
        }

        return view('admin.tasks.show', compact('task', 'user', 'comments', 'permissions'));
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

        $task = DB::table('tasks')->where('task_id', $id)->first();
        NotificationService::dataUpdated($task, 'task');

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

        // Send task assigned notification
        $task = DB::table('tasks')->where('task_id', $id)->first();
        NotificationService::taskAssigned($task);

        return response()->json(['success' => true]);
    }

    // Update Status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer|exists:statuses,status_id',
        ]);

        $statusId = $request->status_id;
        $completedStatus = DB::table('statuses')->where('name', 'Completed')->value('status_id');

        $updateData = [
            'status_id' => $statusId,
            'updated_at' => now(),
        ];

        // If task marked as completed → set timestamp
        if ($statusId == $completedStatus) {
            $updateData['completed_at'] = now();
        } else {
            $updateData['completed_at'] = null;
        }

        DB::table('tasks')->where('task_id', $id)->update($updateData);

        // Send status change notification
        $task = DB::table('tasks')->where('task_id', $id)->first();
        NotificationService::statusChanged($task, DB::table('statuses')->where('status_id', $request->status_id)->value('name'));

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
