<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = User::current();

        // Fetch tasks with creators, assigned users, statuses, projects
        $tasksQuery = DB::table('tasks')
            ->leftJoin('users as created_by_user', 'tasks.created_by', '=', 'created_by_user.id')
            ->leftJoin('users as assigned_user', 'tasks.assigned_to', '=', 'assigned_user.id')
            ->leftJoin('roles as assigned_user_role', 'assigned_user.role_id', '=', 'assigned_user_role.id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->leftJoin('users as assigned_by_user', 'tasks.created_by', '=', 'assigned_by_user.id')
            ->select(
                'tasks.*',
                'tasks.description',
                'created_by_user.name as created_by_name',
                'assigned_user.name as assigned_user_name',
                'assigned_user_role.name as assigned_user_role_name',
                'statuses.name as status_name',
                'assigned_by_user.name as assigned_by_name',
                'projects.name as project_name'
            );

        // Filter by project if chosen
        if ($request->filled('project_id')) {
            $tasksQuery->where('tasks.project_id', $request->project_id);
        }

        // Show selected user's tasks (if chosen) otherwise show current user's tasks
        if ($request->filled('assigned_to')) {
            $tasksQuery->where('tasks.assigned_to', $request->assigned_to);
        } else {
            $tasksQuery->where('tasks.assigned_to', $user->id);
        }

        $tasks = $tasksQuery->get();

        // Group tasks by status
        $tasksByStatus = [
            'pending'     => $tasks->where('status_name', 'pending'),
            'in_progress' => $tasks->where('status_name', 'in_progress'),
            'completed'   => $tasks->where('status_name', 'completed'),
            'on_hold'     => $tasks->where('status_name', 'on_hold'),
        ];

        // Users and projects for filters
        $usersList = DB::table('users')->get();
        $projectsList = DB::table('projects')->get();

        return view('user.dashboard', compact('tasksByStatus', 'user', 'usersList', 'projectsList', 'request', 'tasks'));
    }
}
