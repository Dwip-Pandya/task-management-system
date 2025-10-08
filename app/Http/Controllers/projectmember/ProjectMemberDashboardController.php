<?php

namespace App\Http\Controllers\projectmember;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProjectMemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all projects for filter dropdown
        $projectsList = DB::table('projects')->get();

        // Base query: tasks assigned to the member
        $tasksQuery = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.project_id')
            ->select(
                'tasks.*',
                'statuses.name as status_name',
                'projects.name as project_name'
            )
            ->where('tasks.assigned_to', $user->id);

        // Optional project filter
        if ($request->project_id) {
            $tasksQuery->where('tasks.project_id', $request->project_id);
        }

        // Optional status filter
        if ($request->status) {
            $tasksQuery->where('statuses.name', $request->status);
        }

        $tasks = $tasksQuery->get();

        // Group tasks by status
        $tasksByStatus = [
            'pending'     => $tasks->where('status_name', 'pending'),
            'in_progress' => $tasks->where('status_name', 'in_progress'),
            'completed'   => $tasks->where('status_name', 'completed'),
            'on_hold'     => $tasks->where('status_name', 'on_hold'),
        ];

        return view('projectmember.dashboard', compact('tasksByStatus', 'user', 'projectsList', 'request'));
    }
}
