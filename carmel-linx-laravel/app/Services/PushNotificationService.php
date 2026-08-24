<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Store or update push subscription for a user device.
     */
    public static function subscribe(string $userId, string $role, string $endpoint, ?string $p256dhKey, ?string $authKey, string $deviceType = 'mobile')
    {
        return PushSubscription::updateOrCreate(
            [
                'endpoint' => $endpoint,
            ],
            [
                'user_id' => $userId,
                'role' => strtolower($role),
                'p256dh_key' => $p256dhKey,
                'auth_key' => $authKey,
                'device_type' => $deviceType,
            ]
        );
    }

    /**
     * Dispatch notification payload to subscribers.
     */
    public static function dispatchPayload($subscriptions, string $title, string $body, string $url = '/', string $tag = 'carmel-alert')
    {
        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'url' => $url,
            'tag' => $tag,
            'timestamp' => time(),
        ]);

        $sentCount = 0;

        foreach ($subscriptions as $sub) {
            try {
                // If it's a standard Web Push endpoint (FCM / Mozilla / Apple), dispatch via cURL
                if (!empty($sub->endpoint)) {
                    $ch = curl_init($sub->endpoint);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($payload),
                        'TTL: 86400'
                    ]);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    $sentCount++;
                }
            } catch (\Exception $e) {
                Log::error('Push Notification Dispatch Failed: ' . $e->getMessage());
            }
        }

        return $sentCount;
    }

    /**
     * Send notification to a specific user by ID.
     */
    public static function notifyUser(string $userId, string $title, string $body, string $url = '/', string $tag = 'carmel-user-alert')
    {
        $subscriptions = PushSubscription::where('user_id', $userId)->get();
        return self::dispatchPayload($subscriptions, $title, $body, $url, $tag);
    }

    /**
     * Send notification to a role (e.g. 'staff' or 'student').
     */
    public static function notifyRole(string $role, string $title, string $body, string $url = '/', string $tag = 'carmel-role-alert')
    {
        $subscriptions = PushSubscription::where('role', strtolower($role))->get();
        return self::dispatchPayload($subscriptions, $title, $body, $url, $tag);
    }

    /**
     * Send notification to all registered mobile/desktop push subscribers.
     */
    public static function notifyAll(string $title, string $body, string $url = '/', string $tag = 'carmel-broadcast-alert')
    {
        $subscriptions = PushSubscription::all();
        return self::dispatchPayload($subscriptions, $title, $body, $url, $tag);
    }
}
