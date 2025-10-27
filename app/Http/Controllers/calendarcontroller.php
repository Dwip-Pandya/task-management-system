<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CalendarController extends Controller
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

        if (!$user) return false;

        // User-specific permissions first
        $permission = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', 'calendar viewing')
            ->first();

        // Fallback to role defaults
        if (!$permission) {
            $permission = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', 'calendar viewing')
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
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        if (!$user) {
            return [
                'can_view' => false,
                'can_add' => false,
                'can_edit' => false,
                'can_delete' => false,
            ];
        }

        $perm = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', 'calendar viewing')
            ->first();

        if (!$perm) {
            $perm = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', 'calendar viewing')
                ->first();
        }

        return [
            'can_view' => $perm->can_view ?? false,
            'can_add' => $perm->can_add ?? false,
            'can_edit' => $perm->can_edit ?? false,
            'can_delete' => $perm->can_delete ?? false,
        ];
    }
    // Show calendar view
    public function index()
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_view']) {
            return response()->view('errors.permission-denied', [], 403);
        }

        return view('calendar', compact('user', 'permissions'));
    }

    public function events(Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_view']) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->select('tasks.task_id', 'tasks.title', 'tasks.due_date', 'statuses.name as status_name');

        // Role-based task visibility
        switch ($user->role_id) {
            case 1: // Admin → See all tasks
                // No restrictions
                break;
            case 2: // User → Only tasks assigned to them
                $query->where('tasks.assigned_to', $user->id);
                break;
            case 3: // Project Member → Only tasks in projects they belong to
                $query->where('tasks.assigned_to', $user->id);
                break;

            case 4: // Project Manager → Tasks they created + tasks in projects they created
                $query->where(function ($q) use ($user) {
                    $q->where('tasks.created_by', $user->id) // Tasks they personally created
                        ->orWhereIn('tasks.project_id', function ($subquery) use ($user) {
                            $subquery->select('project_id')
                                ->from('projects')
                                ->where('created_by', $user->id);
                        });
                });
                break;
            default:
                $query->whereNull('tasks.task_id');
                break;
        }

        $tasks = $query->get();

        // Convert tasks to FullCalendar event format
        $events = $tasks->map(function ($task) {
            return [
                'id' => $task->task_id,
                'title' => $task->title,
                'start' => $task->due_date,
                'status' => $task->status_name,
                'color' => match (strtolower($task->status_name)) {
                    'pending' => 'orange',
                    'in_progress' => 'blue',
                    'completed' => 'green',
                    'on_hold' => 'gray',
                    default => 'purple',
                },
            ];
        });

        return response()->json($events);
    }
}
