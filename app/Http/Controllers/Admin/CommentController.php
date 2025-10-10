<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\log;


class CommentController extends Controller
{
    // Store a new comment or reply
    public function store(Request $request)
    {
        try {
            $request->validate([
                'task_id' => 'required|integer',
                'message' => [
                    'required',
                    'string',
                    'regex:/^[^<>]*$/',
                ],
                'parent_id' => 'nullable|integer',
            ]);

            $user = Auth::user();
            $task = DB::table('tasks')->where('task_id', $request->task_id)->first();

            if (!$task) {
                return redirect()->back()->with('error', 'Task not found.');
            }

            if ($user->role_id != 1 && $task->assigned_to != $user->id) {
                return redirect()->back()->with('error', 'You cannot comment on this task.');
            }
            // Strip HTML tags silently
            $message = strip_tags($request->message);

            DB::table('comments')->insert([
                'task_id'    => $request->task_id,
                'user_id'    => $user->id,
                'message'    => $message,
                'parent_id'  => $request->parent_id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Comment added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Fetch comments for a task
    public function getComments($task_id)
    {
        $comments = DB::table('comments')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('comments.task_id', $task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select('comments.*', 'users.name', 'roles.name as role_name')
            ->get();

        return $comments;
    }

    // Update comment
    public function update(Request $request, $comment_id)
    {
        $request->validate(['message' => 'required|string']);
        $user = Auth::user();

        $comment = DB::table('comments')->where('comment_id', $comment_id)->first();
        if (!$comment || ($user->role_id != 1 && $comment->user_id != $user->id)) {
            return back()->with('error', 'You cannot edit this comment.');
        }

        DB::table('comments')->where('comment_id', $comment_id)->update([
            'message' => $request->message,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Comment updated successfully.');
    }

    // Delete comment
    public function destroy($comment_id)
    {
        $user = Auth::user();

        $comment = DB::table('comments')->where('comment_id', $comment_id)->first();
        if (!$comment || ($user->role_id != 1 && $comment->user_id != $user->id)) {
            return back()->with('error', 'You cannot delete this comment.');
        }

        // Delete comment and all its replies
        DB::table('comments')
            ->where('comment_id', $comment_id)
            ->orWhere('parent_id', $comment_id)
            ->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
    // combined comment and status update
    public function storeWithStatus(Request $request)
    {
        $user = Auth::user();

        // --- VALIDATE INPUT ---
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer',
            'message' => ['nullable','string','regex:/^[^<>]*$/',],
            'parent_id' => 'nullable|integer',
            'status_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // --- FETCH TASK ---
        $task = DB::table('tasks')->where('task_id', $request->task_id)->first();
        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }

        // Permission check for commenting
        if ($request->message && $user->role_id != 1 && $task->assigned_to != $user->id) {
            return redirect()->back()->with('error', 'You cannot comment on this task.');
        }

        // Permission check for status change
        if ($request->status_id && $user->role_id != 1 && $task->assigned_to != $user->id) {
            return redirect()->back()->with('error', 'You cannot change status for this task.');
        }

        // --- UPDATE STATUS IF PROVIDED ---
        if ($request->status_id) {
            DB::table('tasks')->where('task_id', $task->task_id)->update([
                'status_id' => $request->status_id
            ]);
        }

        // --- INSERT COMMENT IF PROVIDED ---
        if ($request->message) {
            DB::table('comments')->insert([
                'task_id' => $task->task_id,
                'user_id' => $user->id,
                'message' => $request->message,
                'parent_id' => $request->parent_id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Action completed successfully.');
    }
}
