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

        if ($user->role === 'admin') {
            $tasks = DB::table('tasks')
                ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
                ->select(
                    'tasks.task_id',
                    'tasks.title',
                    'tasks.due_date',
                    'statuses.name as status_name'
                )
                ->get();
        } else {
            $tasks = DB::table('tasks')
                ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
                ->select(
                    'tasks.task_id',
                    'tasks.title',
                    'tasks.due_date',
                    'statuses.name as status_name'
                )
                ->where('tasks.assigned_to', $user->user_id)
                ->get();
        }

        // Convert tasks to FullCalendar event format
        $events = $tasks->map(function ($task) {
            return [
                'id' => $task->task_id,
                'title' => $task->title,
                'start' => $task->due_date,
                'color' => match ($task->status_name) {
                    'pending' => 'orange',
                    'in_progress' => 'blue',
                    'completed' => 'green',
                    'on_hold' => 'gray',
                    default => 'purple',
                }
            ];
        });

        return response()->json($events);
    }
}
