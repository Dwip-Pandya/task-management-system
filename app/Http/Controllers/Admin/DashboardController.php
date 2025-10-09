<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Get all projects for filter
        $projectsList = DB::table('projects')->get();

        // Base query for tasks
        $tasksQuery = DB::table('tasks')
            ->leftJoin('users', 'tasks.assigned_to', '=', 'users.id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id') // join projects
            ->select(
                'tasks.*',
                'users.name as assigned_user_name',
                'statuses.name as status_name',
                'projects.name as project_name'
            );

        // Filter by assigned user (optional)
        if ($request->assigned_to) {
            $tasksQuery->where('tasks.assigned_to', $request->assigned_to);
        }

        // Filter by project (optional)
        if ($request->project_id) {
            $tasksQuery->where('tasks.project_id', $request->project_id);
        }

        $tasks = $tasksQuery->get();

        // Group tasks by status
        $tasksByStatus = [
            'pending'     => $tasks->where('status_name', 'pending'),
            'in_progress' => $tasks->where('status_name', 'in_progress'),
            'completed'   => $tasks->where('status_name', 'completed'),
            'on_hold'     => $tasks->where('status_name', 'on_hold'),
        ];

        // Get all users for dropdown
        $usersList = DB::table('users')->get();

        return view('admin.dashboard', compact('tasksByStatus', 'user', 'usersList', 'projectsList', 'request'));
    }
}
