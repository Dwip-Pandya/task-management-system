<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    // Show calendar view
    public function index()
    {
        $user = Auth::user();
        return view('calendar', compact('user'));
    }

    // Return tasks as events for FullCalendar
    public function events(Request $request)
    {
        $user = Auth::user();

        $query = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('users as assigned_user', 'tasks.assigned_to', '=', 'assigned_user.id')
            ->select(
                'tasks.task_id',
                'tasks.title',
                'tasks.due_date',
                'statuses.name as status_name',
                'assigned_user.name as assigned_to_name',
                'tasks.project_id'
            );

        // Role-based task visibility
        switch ($user->role_id) {
            case 1: // Admin → See all tasks
                // No restrictions
                break;

            case 2: // User → Only tasks assigned to them
                $query->where('tasks.assigned_to', $user->id);
                break;

            case 3: // Project Member → Only tasks in projects they belong to
                $query->whereIn('tasks.project_id', function ($subquery) use ($user) {
                    $subquery->select('project_id')
                        ->from('project_members')
                        ->where('user_id', $user->id);
                });
                break;

            // case 4: // Project Manager → Tasks in their managed projects OR assigned to them
            //     $query->where(function ($q) use ($user) {
            //         $q->whereIn('tasks.project_id', function ($subquery) use ($user) {
            //             $subquery->select('project_id')
            //                 ->from('projects')
            //                 ->where('created_by', $user->id); // Manager-created projects
            //         })
            //             ->orWhere('tasks.assigned_to', $user->id);
            //     });
            //     break;

            default:
                // No tasks if role is unknown
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
