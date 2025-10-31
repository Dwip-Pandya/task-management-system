<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the logged-in user
     */
    public function index()
    {
        $user = User::current();

        // --- Step 1: Get all user-specific notifications ---
        $userNotifications = Notification::where('user_id', $user->id)->get();

        // --- Step 2: Get shared notifications for this user's role ---
        $sharedNotifications = Notification::whereNull('user_id')
            ->whereJsonContains('roles', [$user->role])
            ->where('is_read', false) // shared ones are always unread until each user marks them
            ->get();

        // --- Step 3: Filter shared to exclude ones user already has a copy of ---
        $filteredShared = $sharedNotifications->filter(function ($shared) use ($userNotifications) {
            return !$userNotifications->contains(function ($u) use ($shared) {
                return $u->title === $shared->title &&
                    $u->message === $shared->message &&
                    $u->type === $shared->type;
            });
        });

        // --- Step 4: Merge shared + personal notifications ---
        $allNotifications = $filteredShared->merge($userNotifications)
            ->sortByDesc('created_at')
            ->values();

        return response()->json($allNotifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = User::current();

        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found']);
        }

        // If it's shared (role-based)
        if ($notification->user_id === null) {
            // Check if this user already has a personal copy
            $existing = Notification::where('user_id', $user->id)
                ->where('title', $notification->title)
                ->where('message', $notification->message)
                ->where('type', $notification->type)
                ->first();

            if (!$existing) {
                $personalCopy = $notification->replicate();
                $personalCopy->user_id = $user->id;
                $personalCopy->is_read = true;
                $personalCopy->save();
            } else {
                $existing->is_read = true;
                $existing->save();
            }
        } else {
            // Personal notification → mark as read
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
