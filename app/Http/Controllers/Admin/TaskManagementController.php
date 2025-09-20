<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Start query
        $tasksQuery = DB::table('tasks')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->leftJoin('tags', 'tasks.tag_id', '=', 'tags.tag_id')
            ->leftJoin('tbl_user', 'tasks.assigned_to', '=', 'tbl_user.user_id')
            ->select(
                'tasks.*',
                'statuses.name as status_name',
                'priorities.name as priority_name',
                'tags.name as tag_name',
                'tbl_user.name as assigned_user_name'
            );

        // Filter by user
        if ($user->role === 'admin') {
            if ($request->assigned_to) {
                $tasksQuery->where('tasks.assigned_to', $request->assigned_to);
            }
        } else {
            // Regular user: show own tasks by default
            $tasksQuery->where('tasks.assigned_to', $request->assigned_to ?? $user->user_id);
        }

        // Filters
        if ($request->status_id) {
            $tasksQuery->where('tasks.status_id', $request->status_id);
        }
        if ($request->priority_id) {
            $tasksQuery->where('tasks.priority_id', $request->priority_id);
        }
        if ($request->due_date) {
            $tasksQuery->whereDate('tasks.due_date', $request->due_date);
        }

        $tasks = $tasksQuery->get();

        // Data for dropdowns
        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $usersList = DB::table('tbl_user')->get();

        return view('admin.tasks.index', compact('tasks', 'user', 'statuses', 'priorities', 'usersList', 'request'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags = DB::table('tags')->get();
        $users = DB::table('tbl_user')->get();

        return view('admin.tasks.create', compact('user', 'statuses', 'priorities', 'tags', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|integer',
            'status_id'   => 'required|integer',
            'priority_id' => 'required|integer',
            'tag_id'      => 'nullable|integer',
            'due_date'    => 'nullable|date',
        ]);

        $user = Auth::user();

        DB::table('tasks')->insert([
            'title'       => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'created_by'  => $user->user_id,
            'status_id'   => $request->status_id,
            'priority_id' => $request->priority_id,
            'tag_id'      => $request->tag_id,
            'due_date'    => $request->due_date,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    // Show task details to admin
    public function show($task_id)
    {
        $user = Auth::user();

        $task = DB::table('tasks')
            ->leftJoin('tbl_user as created_by_user', 'tasks.created_by', '=', 'created_by_user.user_id')
            ->leftJoin('tbl_user as assigned_user', 'tasks.assigned_to', '=', 'assigned_user.user_id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.status_id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.priority_id')
            ->select(
                'tasks.*',
                'created_by_user.name as created_by_name',
                'assigned_user.name as assigned_to_name',
                'statuses.name as status_name',
                'priorities.name as priority_name'
            )
            ->where('tasks.task_id', $task_id)
            ->first();

        if (!$task) {
            return redirect()->route('admin.dashboard')->with('error', 'Task not found.');
        }

        // Fetch all comments
        $comments = DB::table('comments')
            ->leftJoin('tbl_user', 'comments.user_id', '=', 'tbl_user.user_id')
            ->where('comments.task_id', $task->task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select('comments.*', 'tbl_user.name', 'tbl_user.role')
            ->get();

        return view('admin.tasks.show', compact('task', 'user', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $task = DB::table('tasks')->where('task_id', $id)->first();
        $statuses = DB::table('statuses')->get();
        $priorities = DB::table('priorities')->get();
        $tags = DB::table('tags')->get();
        $users = DB::table('tbl_user')->get();

        return view('admin.tasks.edit', compact('task', 'statuses', 'priorities', 'tags', 'users', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|integer',
            'status_id'   => 'required|integer',
            'priority_id' => 'required|integer',
            'tag_id'      => 'nullable|integer',
            'due_date'    => 'nullable|date',
        ]);

        DB::table('tasks')->where('task_id', $id)->update([
            'title'       => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status_id'   => $request->status_id,
            'priority_id' => $request->priority_id,
            'tag_id'      => $request->tag_id,
            'due_date'    => $request->due_date,
            'updated_at'  => now(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('tasks')->where('task_id', $id)->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        DB::table('tasks')->where('task_id', $id)->update([
            'status_id' => $request->status_id,
        ]);
        return response()->json(['success' => true]);
    }

    public function updatePriority(Request $request, $id)
    {
        DB::table('tasks')->where('task_id', $id)->update([
            'priority_id' => $request->priority_id,
        ]);
        return response()->json(['success' => true]);
    }

    public function updateAssigned(Request $request, $id)
    {
        DB::table('tasks')->where('task_id', $id)->update([
            'assigned_to' => $request->assigned_to,
        ]);
        return response()->json(['success' => true]);
    }
}
