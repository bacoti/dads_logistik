<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'notifications' => $notifications->items(),
                'has_more' => $notifications->hasMorePages(),
                'unread_count' => auth()->user()->unreadNotifications()->count()
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(): JsonResponse
    {
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return response()->json(['count' => $unreadCount]);
    }

    /**
     * Get recent notifications for dropdown
     */
    public function getRecent(): JsonResponse
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(string $id): JsonResponse
    {
        $notification = auth()->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete a specific notification
     */
    public function destroy(string $id): JsonResponse
    {
        $notification = auth()->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Clear all notifications for the user
     */
    public function clear(): JsonResponse
    {
        auth()->user()->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared'
        ]);
    }
}
