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
    public function index()
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        return view('admin.charts', compact('user'));
    }

    // Chart 1 - Task Status Distribution
    public function getTaskStatusData()
    {
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
        $taskCounts = Task::selectRaw('DATE(completed_at) as date, COUNT(*) as total')
            ->whereNotNull('completed_at')
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->orderBy('date')
            ->pluck('total', 'date');

        return response()->json($taskCounts);
    }
}
