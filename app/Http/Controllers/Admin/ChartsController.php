<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Status;
use App\Models\Priority;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChartsController extends Controller
{
    /**
     * Utility: Check if the logged-in user has permission for the Project module.
     */
    private function hasPermission($action)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        if (!$user || !$user->role_id) {
            return false;
        }

        $permission = DB::table('role_permissions')
            ->where('role_id', $user->role_id)
            ->where('module_name', 'project management')
            ->first();

        if (!$permission) {
            return false;
        }

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

        if (!$user || !$user->role_id) {
            return [
                'can_view' => false,
                'can_add' => false,
                'can_edit' => false,
                'can_delete' => false,
            ];
        }

        $perm = DB::table('role_permissions')
            ->where('role_id', $user->role_id)
            ->where('module_name', 'project management')
            ->first();

        return [
            'can_view' => $perm->can_view ?? false,
            'can_add' => $perm->can_add ?? false,
            'can_edit' => $perm->can_edit ?? false,
            'can_delete' => $perm->can_delete ?? false,
        ];
    }

    public function index()
    {
        $user = User::withTrashed()->with('role')->where('id', Auth::id())->first();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_view']) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to view charts.');
        }

        return view('admin.charts', compact('user', 'permissions'));
    }

    public function getTaskStatusData()
    {
        if (!$this->hasPermission('view')) return response()->json(['error' => 'Unauthorized'], 403);

        $statuses = Status::pluck('name', 'status_id');
        $taskCounts = Task::selectRaw('status_id, COUNT(*) as total')
            ->groupBy('status_id')
            ->pluck('total', 'status_id');

        $data = [];
        foreach ($statuses as $id => $name) {
            $data[$name] = $taskCounts[$id] ?? 0;
        }
        return response()->json($data);
    }

    // Chart 2 - Tasks per User
    public function getTasksPerUser()
    {
        if (!$this->hasPermission('view')) return response()->json(['error' => 'Unauthorized'], 403);

        $users = User::pluck('name', 'id');
        $taskCounts = Task::selectRaw('assigned_to, COUNT(*) as total')
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->pluck('total', 'assigned_to');

        $data = [];
        foreach ($users as $id => $name) {
            $data[$name] = $taskCounts[$id] ?? 0;
        }
        return response()->json($data);
    }

    // Chart 3 - Tasks by Priority
    public function getTasksByPriority()
    {
        if (!$this->hasPermission('view')) return response()->json(['error' => 'Unauthorized'], 403);

        $priorities = Priority::pluck('name', 'priority_id');
        $taskCounts = Task::selectRaw('priority_id, COUNT(*) as total')
            ->groupBy('priority_id')
            ->pluck('total', 'priority_id');

        $data = [];
        foreach ($priorities as $id => $name) {
            $data[$name] = $taskCounts[$id] ?? 0;
        }
        return response()->json($data);
    }

    // Chart 4 - Tasks per Project
    public function getTasksPerProject()
    {
        if (!$this->hasPermission('view')) return response()->json(['error' => 'Unauthorized'], 403);

        $projects = Project::pluck('name', 'project_id');
        $taskCounts = Task::selectRaw('project_id, COUNT(*) as total')
            ->whereNotNull('project_id')
            ->groupBy('project_id')
            ->pluck('total', 'project_id');

        $data = [];
        foreach ($projects as $id => $name) {
            $data[$name] = $taskCounts[$id] ?? 0;
        }
        return response()->json($data);
    }

    // Chart 5 - Tasks Completed Over Time
    public function getTasksCompletedOverTime()
    {
        if (!$this->hasPermission('view')) return response()->json(['error' => 'Unauthorized'], 403);

        $taskCounts = Task::selectRaw('DATE(completed_at) as date, COUNT(*) as total')
            ->whereNotNull('completed_at')
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->orderBy('date')
            ->pluck('total', 'date');

        return response()->json($taskCounts);
    }
}
