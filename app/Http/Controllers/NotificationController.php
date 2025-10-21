<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the logged-in user
     */
    public function index()
    {
        $userId = Auth::id();

        $notifications = Notification::where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhere(function ($q) {
                    $q->whereNull('user_id')
                        ->whereJsonContains('roles', [auth()->user()->role]);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->is_read = true;
            $notification->save();
        }
        return response()->json(['success' => true]);
    }


    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        Notification::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
