<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create notification for one user
     */
    private static function sendUserNotification($userId, $senderId, $type, $title, $message, $relatedId = null, $relatedType = null)
    {
        Notification::create([
            'user_id'      => $userId,
            'sender_id'    => $senderId,
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'related_id'   => $relatedId,
            'related_type' => $relatedType,
            'is_read'      => false,
            'roles'        => null,
        ]);
    }

    /**
     * Create notification for all users of given roles
     */
    private static function sendRoleNotification(array $roles, $senderId, $type, $title, $message, $relatedId = null, $relatedType = null)
    {
        // Fetch all users that match given roles
        $recipients = User::whereHas('role', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        })->get();

        foreach ($recipients as $user) {
            Notification::create([
                'user_id'      => $user->id,
                'sender_id'    => $senderId,
                'type'         => $type,
                'title'        => $title,
                'message'      => $message,
                'related_id'   => $relatedId,
                'related_type' => $relatedType,
                'is_read'      => false,
                'roles'        => json_encode($roles),
            ]);
        }
    }

    /**
     * Task Assigned
     */
    public static function taskAssigned($task)
    {
        $sender = auth()->user();

        // Get the assigned user details
        $assignedUser = User::find($task->assigned_to);

        // Assigned user notification
        if ($assignedUser) {
            self::sendUserNotification(
                $assignedUser->id,
                $sender->id,
                'task_assigned',
                'New Task Assigned',
                "Task '{$task->title}' has been assigned to you by {$sender->name}",
                $task->task_id,
                'task'
            );
        }

        // Notify all Admins and all Project Managers
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
        $managers = User::whereHas('role', fn($q) => $q->where('name', 'project manager'))->get();

        $recipients = $admins->merge($managers)->unique('id');

        foreach ($recipients as $recipient) {
            self::sendUserNotification(
                $recipient->id,
                $sender->id,
                'task_assigned',
                'Task Assigned',
                "Task '{$task->title}' has been assigned to {$assignedUser->name} by {$sender->name}",
                $task->task_id,
                'task'
            );
        }
    }


    /**
     * Project Created
     */
    public static function projectCreated($project)
    {
        $sender = auth()->user();

        // Notify admins + project managers
        self::sendRoleNotification(
            ['admin', 'project manager'],
            $sender->id,
            'project_created',
            'New Project Created',
            "Project '{$project->name}' has been created by {$sender->name}",
            $project->project_id ?? $project->id,
            'project'
        );
    }

    /**
     * Task Status Changed
     */
    public static function statusChanged($task, $newStatus)
    {
        $sender = auth()->user();

        // Notify the assignee
        if ($task->assigned_to && $task->assigned_to != $sender->id) {
            self::sendUserNotification(
                $task->assigned_to,
                $sender->id,
                'status_changed',
                'Task Status Updated',
                "The status of '{$task->title}' has been changed to '{$newStatus}' by {$sender->name}",
                $task->task_id ?? $task->id,
                'task'
            );
        }

        // Notify admins + PMs
        self::sendRoleNotification(
            ['admin', 'project manager'],
            $sender->id,
            'status_changed',
            'Task Status Updated',
            "{$sender->name} changed the status of '{$task->title}' to '{$newStatus}'",
            $task->task_id ?? $task->id,
            'task'
        );
    }

    /**
     * Comment Added
     */
    public static function commentAdded($comment)
    {
        $task = $comment->task;
        $sender = $comment->user;

        // Notify assigned user if not sender
        if ($task->assigned_to && $task->assigned_to != $sender->id) {
            self::sendUserNotification(
                $task->assigned_to,
                $sender->id,
                'comment_added',
                'New Comment',
                "{$sender->name} commented on task '{$task->title}'",
                $task->task_id ?? $task->id,
                'task'
            );
        }

        // Notify admins + PMs
        self::sendRoleNotification(
            ['admin', 'project manager'],
            $sender->id,
            'comment_added',
            'New Comment',
            "{$sender->name} commented on task '{$task->title}'",
            $task->task_id ?? $task->id,
            'task'
        );
    }

    /**
     * Data Updated (Project or Task)
     */
    public static function dataUpdated($entity, $entityType)
    {
        $sender = auth()->user();

        // Notify admins + project managers
        self::sendRoleNotification(
            ['admin', 'project manager'],
            $sender->id,
            'data_updated',
            ucfirst($entityType) . " Updated",
            "{$sender->name} updated the {$entityType} '{$entity->name}'",
            $entityType === 'task' ? ($entity->task_id ?? $entity->id) : ($entity->project_id ?? $entity->id),
            $entityType
        );
    }
}
