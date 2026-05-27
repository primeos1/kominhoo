<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /api/v1/notifications
     * Paginated notifications for the auth user.
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 20), 100);

        $notifs = UserNotification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $unreadCount = UserNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success'      => true,
            'data'         => $notifs,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * POST /api/v1/notifications/{id}/read
     * Mark a single notification as read.
     */
    public function markRead(Request $request, $id)
    {
        UserNotification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/v1/notifications/read-all
     * Mark all notifications as read for the auth user.
     */
    public function markAllRead(Request $request)
    {
        UserNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * DELETE /api/v1/notifications/{id}
     * Delete a notification.
     */
    public function destroy(Request $request, $id)
    {
        UserNotification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/v1/notifications/send (admin)
     * Send a notification to one or all users.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'user_id'  => 'nullable|exists:users,id',
            'type'     => 'required|string|max:64',
            'title'    => 'required|string|max:255',
            'message'  => 'required|string',
            'data'     => 'nullable|array',
        ]);

        if (!empty($data['user_id'])) {
            // Single user
            UserNotification::create([
                'user_id' => $data['user_id'],
                'type'    => $data['type'],
                'title'   => $data['title'],
                'message' => $data['message'],
                'data'    => $data['data'] ?? null,
            ]);
            $sent = 1;
        } else {
            // Broadcast to all active users
            $users = User::where('role', '!=', 'admin')->pluck('id');
            $now   = now();
            $rows  = $users->map(fn($uid) => [
                'user_id'    => $uid,
                'type'       => $data['type'],
                'title'      => $data['title'],
                'message'    => $data['message'],
                'data'       => json_encode($data['data'] ?? null),
                'is_read'    => false,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            foreach (array_chunk($rows, 500) as $chunk) {
                UserNotification::insert($chunk);
            }
            $sent = count($rows);
        }

        return response()->json(['success' => true, 'sent_to' => $sent]);
    }
}
