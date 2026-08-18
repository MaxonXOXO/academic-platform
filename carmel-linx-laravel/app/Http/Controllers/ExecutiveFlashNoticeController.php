<?php

namespace App\Http\Controllers;

use App\Models\ExecutiveFlashNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ExecutiveFlashNoticeController extends Controller
{
    /**
     * Store and broadcast a new executive flash notice.
     */
    public function broadcast(Request $request)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Principal', 'Super_Admin', 'Chairman', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Only executive administrative desks can broadcast flash notices.'], 403);
        }

        $request->validate([
            'title'             => 'required|string|max:255',
            'content'           => 'required|string',
            'priority'          => 'required|string|in:Normal,Urgent,Circular',
            'target_audience'   => 'required|string|in:ALL_CAMPUS,STAFF_ALL,STAFF_DEPT,STUDENTS_ALL,STUDENTS_DEPT_SEM',
            'target_department' => 'required|string',
            'target_semester'   => 'required|string',
            'dispatch_type'     => 'required|string|in:immediate,scheduled',
            'scheduled_at'      => 'nullable|date',
            'attachment'        => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:10240', // max 10MB
        ]);

        $attachmentPath = null;
        $attachmentType = 'none';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'flash_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $attachmentPath = $file->storeAs('flash_notices', $filename, 'public');

            $mime = $file->getClientMimeType();
            if (str_contains($mime, 'pdf')) {
                $attachmentType = 'pdf';
            } elseif (str_contains($mime, 'image')) {
                $attachmentType = 'image';
            } else {
                $attachmentType = 'other';
            }
        }

        $dispatchType = $request->input('dispatch_type', 'immediate');
        $scheduledAt  = $dispatchType === 'scheduled' ? $request->input('scheduled_at') : null;
        $isPublished  = $dispatchType === 'immediate';

        $notice = ExecutiveFlashNotice::create([
            'sender_id'         => (string) Session::get('userId', '9999999999'),
            'sender_role'       => $role ?: 'Principal',
            'sender_name'       => Session::get('userName', 'Principal / Executive Desk'),
            'title'             => trim($request->input('title')),
            'content'           => trim($request->input('content')),
            'priority'          => $request->input('priority', 'Normal'),
            'target_audience'   => $request->input('target_audience', 'ALL_CAMPUS'),
            'target_department' => $request->input('target_department', 'ALL'),
            'target_semester'   => $request->input('target_semester', 'ALL'),
            'attachment_path'   => $attachmentPath,
            'attachment_type'   => $attachmentType,
            'dispatch_type'     => $dispatchType,
            'scheduled_at'      => $scheduledAt,
            'is_published'      => $isPublished,
        ]);

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => $dispatchType === 'scheduled' ? 'Flash notice scheduled successfully!' : 'Flash notice broadcasted to targeted audience with immediate effect!',
            'notice'  => $notice,
        ]);
    }

    /**
     * List all flash notices for executive audit.
     */
    public function getNotices()
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Principal', 'Super_Admin', 'Chairman', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $notices = ExecutiveFlashNotice::orderBy('created_at', 'desc')->take(30)->get();

        $stats = [
            'total_sent'      => ExecutiveFlashNotice::where('is_published', true)->count(),
            'scheduled_count' => ExecutiveFlashNotice::where('dispatch_type', 'scheduled')->where('is_published', false)->count(),
            'urgent_count'    => ExecutiveFlashNotice::where('priority', 'Urgent')->count(),
        ];

        return response()->json([
            'status'  => 'SUCCESS',
            'stats'   => $stats,
            'notices' => $notices,
        ]);
    }

    /**
     * Revoke / delete a flash notice.
     */
    public function revokeNotice($id)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Principal', 'Super_Admin', 'Chairman', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $notice = ExecutiveFlashNotice::find($id);
        if (!$notice) {
            return response()->json(['status' => 'ERROR', 'message' => 'Flash notice not found.'], 404);
        }

        if ($notice->attachment_path && Storage::disk('public')->exists($notice->attachment_path)) {
            Storage::disk('public')->delete($notice->attachment_path);
        }

        $notice->delete();

        return response()->json(['status' => 'SUCCESS', 'message' => 'Flash notice revoked successfully.']);
    }

    /**
     * Get active published flash notices targeted for the logged-in user.
     */
    public function getActiveNotices(Request $request)
    {
        $userId   = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId && !$userRole) {
            return response()->json(['status' => 'SUCCESS', 'notices' => []]);
        }

        $staff = \App\Models\StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff && $userRole && $userRole !== 'Student') {
            $staff = \App\Models\StaffProfile::where('designation', $userRole)->first();
        }

        $dept = $staff ? ($staff->department ?? 'ALL') : 'ALL';

        $query = ExecutiveFlashNotice::where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('dispatch_type')
                  ->orWhere('dispatch_type', 'immediate')
                  ->orWhere(function ($sq) {
                      $sq->where('dispatch_type', 'scheduled')
                         ->where('scheduled_at', '<=', now());
                  });
            });

        if ($userRole === 'Student') {
            $query->where(function ($q) {
                $q->whereIn('target_audience', ['ALL_CAMPUS', 'STUDENTS_ALL', 'STUDENTS_DEPT_SEM']);
            });
        } else {
            $query->where(function ($q) use ($dept) {
                $q->whereIn('target_audience', ['ALL_CAMPUS', 'STAFF_ALL'])
                  ->orWhere(function ($sq) use ($dept) {
                      $sq->where('target_audience', 'STAFF_DEPT')
                         ->where(function ($d) use ($dept) {
                             $d->where('target_department', 'ALL')
                               ->orWhere('target_department', $dept);
                         });
                  });
            });
        }

        $notices = $query->orderByRaw("CASE WHEN priority = 'Urgent' THEN 1 WHEN priority = 'Circular' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'status'  => 'SUCCESS',
            'notices' => $notices,
        ]);
    }
}
