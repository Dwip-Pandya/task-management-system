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

        // Base query for tasks
        $tasksQuery = DB::table('tasks')
            ->leftJoin('tbl_user', 'tasks.assigned_to', '=', 'tbl_user.user_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->select(
                'tasks.*',
                'tbl_user.name as assigned_user_name',
                'statuses.name as status_name'
            );

        // Filter by assigned user (optional)
        if ($request->assigned_to) {
            $tasksQuery->where('tasks.assigned_to', $request->assigned_to);
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
        $usersList = DB::table('tbl_user')->get();

        return view('admin.dashboard', compact('tasksByStatus', 'user', 'usersList', 'request'));
    }
}
