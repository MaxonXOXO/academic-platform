<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StaffBirthdayController extends Controller
{
    /**
     * Fetch staff members celebrating their birthday today, along with current wishes & reactions.
     */
    public function getTodayBirthdays(Request $request)
    {
        $todayStr = date('Y-m-d');
        $month = (int)date('m');
        $day   = (int)date('d');

        try {
            // Find active staff profiles with birthday today
            $celebrants = StaffProfile::where('account_status', 'Approved')
                ->whereNotNull('dob')
                ->whereRaw('MONTH(dob) = ?', [$month])
                ->whereRaw('DAY(dob) = ?', [$day])
                ->select('mobile_no', 'name', 'designation', 'branch', 'photo_url', 'dob')
                ->get()
                ->map(function ($staff) {
                    return [
                        'mobile_no'   => $staff->mobile_no,
                        'name'        => $staff->name,
                        'designation' => str_replace('_', ' ', $staff->designation),
                        'branch'      => $staff->branch,
                        'photo_url'   => $staff->photo_url ?: '/storage/avatars/default.png',
                        'dob'         => $staff->dob,
                    ];
                });

            if ($celebrants->isEmpty()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'has_birthdays' => false,
                    'celebrants' => [],
                ]);
            }

            $celebrantMobiles = $celebrants->pluck('mobile_no')->toArray();

            // Fetch wishes sent today for these celebrants
            $wishes = DB::table('staff_birthday_wishes')
                ->where('wish_date', $todayStr)
                ->whereIn('celebrant_mobile_no', $celebrantMobiles)
                ->orderBy('id', 'desc')
                ->get();

            // Aggregated reaction counts
            $reactions = [
                '🎉' => 0,
                '🎂' => 0,
                '🎈' => 0,
                '🎁' => 0,
                '❤️' => 0,
                '👏' => 0,
            ];

            foreach ($wishes as $w) {
                if (!empty($w->emoji) && isset($reactions[$w->emoji])) {
                    $reactions[$w->emoji]++;
                }
            }

            // Check if current user has already wished today
            $currentUserId = Session::get('userId');
            $hasWished = false;
            if ($currentUserId) {
                $hasWished = DB::table('staff_birthday_wishes')
                    ->where('wish_date', $todayStr)
                    ->where('sender_mobile_no', $currentUserId)
                    ->exists();
            }

            $dateLabel = strtoupper(date('F d')); // e.g. AUGUST 24

            return response()->json([
                'status'         => 'SUCCESS',
                'has_birthdays'  => true,
                'date_label'     => $dateLabel,
                'celebrants'     => $celebrants,
                'reactions'      => $reactions,
                'wishes'         => $wishes->map(function($w) {
                    return [
                        'id'          => $w->id,
                        'sender_name' => $w->sender_name,
                        'emoji'       => $w->emoji,
                        'message'     => $w->message,
                        'time'        => date('h:i A', strtotime($w->created_at)),
                    ];
                }),
                'has_wished'     => $hasWished,
                'current_user_id'=> $currentUserId
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch birthday notifications: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Send a birthday wish (emoji reaction or message) to celebrant(s).
     */
    public function sendWish(Request $request)
    {
        $senderMobile = Session::get('userId');
        $senderName   = Session::get('userName');

        if (!$senderMobile || !$senderName) {
            return response()->json(['status' => 'ERROR', 'message' => 'Active staff session required to send wishes.'], 401);
        }

        $request->validate([
            'celebrant_mobile_no' => 'required|string',
            'emoji'               => 'nullable|string|max:10',
            'message'             => 'nullable|string|max:500',
        ]);

        $celebrantMobile = $request->input('celebrant_mobile_no');
        $emoji           = $request->input('emoji');
        $message         = trim($request->input('message'));

        if (empty($emoji) && empty($message)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Please select an emoji or write a wish message.'], 422);
        }

        $todayStr = date('Y-m-d');

        try {
            DB::table('staff_birthday_wishes')->insert([
                'wish_date'           => $todayStr,
                'celebrant_mobile_no' => $celebrantMobile,
                'sender_mobile_no'    => $senderMobile,
                'sender_name'         => $senderName,
                'emoji'               => $emoji,
                'message'             => $message ?: null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            // Audit log
            AuditLog::create([
                'performed_by'      => $senderMobile,
                'performed_by_name' => $senderName,
                'target_id'         => $celebrantMobile,
                'target_name'       => 'Staff Birthday Wish',
                'action'            => 'Birthday Wish Sent',
                'details'           => "Sent birthday wish to {$celebrantMobile}" . ($emoji ? " with emoji {$emoji}" : ''),
                'ip_address'        => $request->ip(),
            ]);

            // Send real-time push notification to the birthday celebrant
            \App\Services\PushNotificationService::notifyUser(
                $celebrantMobile,
                "🎂 New Birthday Wish from {$senderName}!",
                $message ? "{$senderName}: \"{$message}\"" : "{$senderName} sent you a {$emoji} birthday wish!",
                '/dashboard/staff/mobile',
                'carmel-birthday-wish'
            );

            return $this->getTodayBirthdays($request);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to send wish: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update logged-in staff member's own Date of Birth.
     */
    public function updateSelfDob(Request $request)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Session expired. Please log in.'], 401);
        }

        $request->validate([
            'dob' => 'required|date',
        ]);

        $dob = $request->input('dob');

        try {
            $staff = StaffProfile::where('mobile_no', $userId)->first();
            if (!$staff) {
                return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.'], 404);
            }

            $staff->dob = $dob;
            $staff->save();

            AuditLog::create([
                'performed_by'      => $staff->mobile_no,
                'performed_by_name' => $staff->name,
                'target_id'         => $staff->mobile_no,
                'target_name'       => $staff->name,
                'action'            => 'DOB Updated',
                'details'           => "Staff updated Date of Birth to {$dob}",
                'ip_address'        => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Date of Birth updated successfully!', 'dob' => $dob]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to update DOB: ' . $e->getMessage()], 500);
        }
    }
}
