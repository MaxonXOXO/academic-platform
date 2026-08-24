<?php

namespace App\Http\Controllers;

use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PushNotificationController extends Controller
{
    /**
     * Store Web Push Notification Subscription for current session user.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthenticated session.'], 401);
        }

        $roleType = (strtolower($userRole) === 'student') ? 'student' : 'staff';
        $endpoint = $request->input('endpoint');
        $p256dhKey = $request->input('p256dh_key');
        $authKey = $request->input('auth_key');
        $deviceType = $request->input('device_type', 'mobile');

        PushNotificationService::subscribe($userId, $roleType, $endpoint, $p256dhKey, $authKey, $deviceType);

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Push notification subscription registered successfully.'
        ]);
    }

    /**
     * Broadcast urgent push notice (Authorized Senders: Admin, Principal, HOD, Chairman, Super_Admin, Staff).
     */
    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:255',
            'target' => 'required|string|in:all,staff,students',
        ]);

        $userRole = Session::get('userRole');
        $userId = Session::get('userId');

        if (!$userId || !in_array($userRole, ['Super_Admin', 'Admin', 'Principal', 'Chairman', 'HOD', 'Tutor', 'Lecturer', 'Academic_Coordinator', 'Academic Coordinator'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized push sender.'], 403);
        }

        $title = trim($request->input('title'));
        $body = trim($request->input('body'));
        $target = $request->input('target');
        $targetUrl = $request->input('url', '/');

        $sentCount = 0;
        if ($target === 'all') {
            $sentCount = PushNotificationService::notifyAll($title, $body, $targetUrl, 'carmel-broadcast');
        } else {
            $sentCount = PushNotificationService::notifyRole($target, $title, $body, $targetUrl, 'carmel-' . $target);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Push notification dispatched to {$sentCount} device(s)."
        ]);
    }
}
