<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Store a new comment or reply
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer',
            'message' => 'required|string',
            'parent_id' => 'nullable|integer',
        ]);

        $user = Auth::user();

        // Permission check: admin or assigned user
        $task = DB::table('tasks')->where('task_id', $request->task_id)->first();
        if (!$task || ($user->role !== 'admin' && $task->assigned_to != $user->user_id)) {
            return back()->with('error', 'You cannot comment on this task.');
        }

        DB::table('comments')->insert([
            'task_id' => $request->task_id,
            'user_id' => $user->user_id,
            'message' => $request->message,
            'parent_id' => $request->parent_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    // Fetch comments for a task
    public function getComments($task_id)
    {
        $comments = DB::table('comments')
            ->leftJoin('tbl_user', 'comments.user_id', '=', 'tbl_user.user_id')
            ->where('comments.task_id', $task_id)
            ->orderBy('comments.created_at', 'asc')
            ->select('comments.*', 'tbl_user.name', 'tbl_user.role')
            ->get();

        return $comments;
    }

    // Update comment
    public function update(Request $request, $comment_id)
    {
        $request->validate(['message' => 'required|string']);
        $user = Auth::user();

        $comment = DB::table('comments')->where('comment_id', $comment_id)->first();
        if (!$comment || ($user->role !== 'admin' && $comment->user_id != $user->user_id)) {
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
        if (!$comment || ($user->role !== 'admin' && $comment->user_id != $user->user_id)) {
            return back()->with('error', 'You cannot delete this comment.');
        }

        // Delete comment and all its replies
        DB::table('comments')
            ->where('comment_id', $comment_id)
            ->orWhere('parent_id', $comment_id)
            ->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}
