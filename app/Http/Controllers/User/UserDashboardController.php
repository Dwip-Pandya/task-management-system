<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tasksQuery = DB::table('tasks')
            ->leftJoin('tbl_user as created_by_user', 'tasks.created_by', '=', 'created_by_user.user_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->select(
                'tasks.*',
                'created_by_user.name as created_by_name',
                'statuses.name as status_name',
                'projects.name as project_name'
            );

        // Filter by project if chosen
        if ($request->project_id) {
            $tasksQuery->where('tasks.project_id', $request->project_id);
        }

        // Show selected user's tasks (if chosen) otherwise show current user's tasks
        if ($request->assigned_to) {
            $tasksQuery->where('tasks.assigned_to', $request->assigned_to);
        } else {
            $tasksQuery->where('tasks.assigned_to', $user->user_id);
        }

        $tasks = $tasksQuery->get();

        $tasksByStatus = [
            'pending'     => $tasks->where('status_name', 'pending'),
            'in_progress' => $tasks->where('status_name', 'in_progress'),
            'completed'   => $tasks->where('status_name', 'completed'),
            'on_hold'     => $tasks->where('status_name', 'on_hold'),
        ];

        // Pass users list for the dropdown in the dashboard
        $usersList = DB::table('tbl_user')->get();
        $projectsList = DB::table('projects')->get();

        return view('user.dashboard', compact('tasksByStatus', 'user', 'usersList', 'projectsList', 'request'));
    }
}
