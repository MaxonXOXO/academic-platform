<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SfCampusGeofenceSetting;
use App\Models\SfStaffFaceRegistration;
use App\Models\SfStaffTimePunch;
use App\Models\StaffProfile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StaffAttendanceMobileController extends Controller
{
    /**
     * Helper: Compute Haversine distance in meters between 2 Lat/Lng coordinates.
     */
    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth radius in meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius);
    }

    /**
     * SF Staff Mobile Face Punch Page
     */
    public function showFacePunch(Request $request)
    {
        $staffId = Session::get('userStaffId') ?? Session::get('mobileNo') ?? Session::get('userId') ?? 'SF-STAFF-DEMO';
        $staffName = Session::get('userName') ?? Session::get('userRole') ?? 'Self-Financing Staff';

        // Authorization Guard: Restrict to EL, CT, AU, and General SF staff categories
        $staffBranch = strtoupper(Session::get('userBranch') ?? '');
        $staffRole   = strtoupper(Session::get('userRole') ?? '');

        $staff = StaffProfile::where('mobile_no', $staffId)->first();
        if ($staff) {
            if ($staff->branch) $staffBranch = strtoupper($staff->branch);
            if ($staff->designation) $staffRole = strtoupper($staff->designation);
        }

        $sfAllowedBranches = ['EL', 'CT', 'AU', 'GEN_SF', 'SF'];
        $sfAllowedRoles    = ['GEN_DEPT_COORDINATOR_SELF_FINANCE', 'ACADEMIC_COORDINATOR_SF'];

        $isSfStaff = in_array($staffBranch, $sfAllowedBranches)
            || in_array($staffRole, $sfAllowedRoles)
            || str_contains($staffRole, 'SELF_FINANCE')
            || str_contains($staffRole, 'SELF FINANCE')
            || str_contains($staffRole, '_SF')
            || str_contains($staffBranch, 'SF')
            || in_array($staffRole, ['SUPER_ADMIN', 'PRINCIPAL', 'ADMIN', 'CHAIRMAN']);

        if (!$isSfStaff) {
            return redirect('/dashboard/staff/mobile')->with('error', 'Biometric attendance is only applicable for EL, CT, AU, and General SF staff.');
        }

        $registration = SfStaffFaceRegistration::where('staff_id', $staffId)
            ->orWhere('mobile_no', $staffId)
            ->first();
        $todayPunch = SfStaffTimePunch::where('staff_id', $staffId)
            ->where('punch_date', now()->format('Y-m-d'))
            ->first();

        $geofence = SfCampusGeofenceSetting::where('is_active', true)->first();
        if (!$geofence) {
            $geofence = (object)[
                'campus_name' => 'Carmel College Campus',
                'centroid_lat' => 10.23120000,
                'centroid_lng' => 76.20450000,
                'radius_meters' => 150,
                'max_accuracy_meters' => 30
            ];
        }

        return view('sf_staff_face_punch', [
            'staffId' => $staffId,
            'staffName' => $staffName,
            'registration' => $registration,
            'todayPunch' => $todayPunch,
            'geofence' => $geofence,
        ]);
    }

    /**
     * API: Save Face Registration Descriptor & Photo
     */
    public function saveFaceRegistration(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|string',
            'face_descriptor' => 'required',
        ]);

        $staffId = $request->input('staff_id');
        $mobileNo = Session::get('mobileNo') ?? $staffId;
        $staffName = Session::get('userName') ?? 'Self-Financing Staff';

        $photoUrl = null;
        if ($request->has('photo_base64') && !empty($request->input('photo_base64'))) {
            try {
                $imageData = $request->input('photo_base64');
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    $imageData = base64_decode($imageData);
                    $fileName = 'sf_faces/' . $staffId . '_' . time() . '.' . $type;
                    Storage::disk('public')->put($fileName, $imageData);
                    $photoUrl = '/storage/' . $fileName;
                }
            } catch (\Exception $e) {
                // Ignore snapshot error if any
            }
        }

        $descriptor = is_array($request->input('face_descriptor')) 
            ? json_encode($request->input('face_descriptor')) 
            : $request->input('face_descriptor');

        $registration = SfStaffFaceRegistration::updateOrCreate(
            ['staff_id' => $staffId],
            [
                'mobile_no' => $mobileNo,
                'staff_name' => $staffName,
                'face_descriptor' => $descriptor,
                'photo_url' => $photoUrl ?? DB::raw('photo_url'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Face registered successfully!',
            'registration' => $registration
        ]);
    }

    /**
     * API: Process Face & Smile Verified Time Punch (IN / OUT)
     */
    public function verifyAndPunch(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|string',
            'punch_type' => 'required|in:IN,OUT',
            'gps_lat' => 'required|numeric',
            'gps_lng' => 'required|numeric',
        ]);

        try {
            $staffId = $request->input('staff_id');
            $staffName = Session::get('userName') ?? 'Self-Financing Staff';
            $punchType = $request->input('punch_type');
            $lat = (float) $request->input('gps_lat');
            $lng = (float) $request->input('gps_lng');
            $livenessScore = (float) ($request->input('liveness_score', 0.85));

            // Authorization Guard: Restrict API punching to EL, CT, AU, and General SF staff
            $staffBranch = strtoupper(Session::get('userBranch') ?? '');
            $staffRole   = strtoupper(Session::get('userRole') ?? '');

            $staffProfile = StaffProfile::where('mobile_no', $staffId)->first();
            if ($staffProfile) {
                if ($staffProfile->branch) $staffBranch = strtoupper($staffProfile->branch);
                if ($staffProfile->designation) $staffRole = strtoupper($staffProfile->designation);
            }

            $sfAllowedBranches = ['EL', 'CT', 'AU', 'GEN_SF', 'SF'];
            $sfAllowedRoles    = ['GEN_DEPT_COORDINATOR_SELF_FINANCE', 'ACADEMIC_COORDINATOR_SF'];

            $isSfStaff = in_array($staffBranch, $sfAllowedBranches)
                || in_array($staffRole, $sfAllowedRoles)
                || str_contains($staffRole, 'SELF_FINANCE')
                || str_contains($staffRole, 'SELF FINANCE')
                || str_contains($staffRole, '_SF')
                || str_contains($staffBranch, 'SF')
                || in_array($staffRole, ['SUPER_ADMIN', 'PRINCIPAL', 'ADMIN', 'CHAIRMAN']);

            if (!$isSfStaff) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Biometric attendance punching is restricted to EL, CT, AU, and General SF staff.'
                ], 403);
            }

            // Verify staff biometric face registration exists
            $registration = SfStaffFaceRegistration::where('staff_id', $staffId)
                                ->orWhere('mobile_no', $staffId)
                                ->orWhere('staff_id', 'like', "%{$staffId}%")
                                ->orWhere('mobile_no', 'like', "%{$staffId}%")
                                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Biometric face profile not registered for Staff ID ' . $staffId . '. Please register face first.'
                ], 422);
            }

            // Perform Biometric Facial Feature Vector Comparison
            $punchDesc = $request->input('face_descriptor');
            if (!empty($registration->face_descriptor) && is_array($punchDesc) && count($punchDesc) > 0) {
                $regDesc = json_decode($registration->face_descriptor, true);
                if (is_array($regDesc) && count($regDesc) > 0) {
                    $similarity = $this->calculateCosineSimilarity($regDesc, $punchDesc);
                    
                    // Match threshold: Cosine Similarity >= 0.42 (42% match)
                    // Same person under normal variations: 0.60 to 0.98
                    // Different person: < 0.35
                    if ($similarity < 0.42) {
                        return response()->json([
                            'success' => false,
                            'message' => '❌ Biometric Mismatch! Captured face does not match registered profile for ' . $staffName . ' (' . round($similarity * 100) . '% match). Attendance rejected.'
                        ], 422);
                    }
                }
            }

            // Fetch Campus Geofence Config
            $geofence = SfCampusGeofenceSetting::where('is_active', true)->first();
            $centroidLat = $geofence ? (float)$geofence->centroid_lat : 10.23120000;
            $centroidLng = $geofence ? (float)$geofence->centroid_lng : 76.20450000;
            $allowedRadius = $geofence ? (int)$geofence->radius_meters : 150;

            $distance = $this->calculateHaversineDistance($lat, $lng, $centroidLat, $centroidLng);
            $premisesStatus = ($distance <= $allowedRadius) ? 'INSIDE_PREMISES' : 'OUTSIDE_PREMISES';

            // Strict Geofence Enforcement: Reject punches outside campus premises
            if ($distance > $allowedRadius) {
                $distLabel = $distance >= 1000 ? number_format($distance / 1000, 2) . ' km' : $distance . ' meters';
                return response()->json([
                    'success' => false,
                    'message' => "❌ Attendance Rejected: You are currently {$distLabel} outside Carmel College Campus. Biometric punch is restricted to campus premises."
                ], 422);
            }

            // Save Snapshot if provided
            $snapshotUrl = null;
            if ($request->has('snapshot_base64') && !empty($request->input('snapshot_base64'))) {
                try {
                    $imageData = $request->input('snapshot_base64');
                    if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                        $imageData = substr($imageData, strpos($imageData, ',') + 1);
                        $type = strtolower($type[1]);
                        $imageData = base64_decode($imageData);
                        $fileName = 'sf_punches/' . $staffId . '_' . strtolower($punchType) . '_' . time() . '.' . $type;
                        Storage::disk('public')->put($fileName, $imageData);
                        $snapshotUrl = '/storage/' . $fileName;
                    }
                } catch (\Exception $e) {
                    // Ignore snapshot save error
                }
            }

            $today = now()->format('Y-m-d');
            $currentTime = now()->format('H:i:s');

            $punch = SfStaffTimePunch::firstOrNew([
                'staff_id' => $staffId,
                'punch_date' => $today,
            ]);

            $punch->staff_name = $staffName;
            $punch->liveness_type = 'SMILE';
            $punch->liveness_score = $livenessScore;

            if ($punchType === 'IN') {
                $punch->in_time = $currentTime;
                $punch->in_gps_lat = $lat;
                $punch->in_gps_lng = $lng;
                $punch->in_gps_distance_meters = $distance;
                $punch->in_premises_status = $premisesStatus;
                if ($snapshotUrl) $punch->in_snapshot_url = $snapshotUrl;

                $nowInTime = now()->format('H:i');
                if ($nowInTime < '08:45') {
                    $punch->punch_status = 'EARLY_IN';
                } elseif ($nowInTime > '09:15') {
                    $punch->punch_status = 'LATE_IN';
                } else {
                    $punch->punch_status = 'PRESENT';
                }
            } else {
                // Ensure Morning IN exists before recording Evening OUT
                if (!$punch->exists || !$punch->in_time) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Morning IN punch must be logged before punching OUT.'
                    ], 422);
                }

                $inTimestamp = strtotime($punch->punch_date . ' ' . $punch->in_time);
                $elapsedSeconds = time() - $inTimestamp;
                if ($elapsedSeconds < 30) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Morning IN was logged just now. Please re-open attendance from dashboard when ready for Evening OUT.'
                    ], 422);
                }

                $punch->out_time = $currentTime;
                $punch->out_gps_lat = $lat;
                $punch->out_gps_lng = $lng;
                $punch->out_gps_distance_meters = $distance;
                $punch->out_premises_status = $premisesStatus;
                if ($snapshotUrl) $punch->out_snapshot_url = $snapshotUrl;

                $nowHi = now()->format('H:i');
                $inPrefix = 'PRESENT';
                $existingStatus = (string) ($punch->punch_status ?? '');
                if (str_contains($existingStatus, 'EARLY_IN')) {
                    $inPrefix = 'EARLY_IN';
                } elseif (str_contains($existingStatus, 'LATE_IN')) {
                    $inPrefix = 'LATE_IN';
                }

                if ($nowHi < '16:00') {
                    $punch->punch_status = $inPrefix . ' & EARLY_OUT';
                } elseif ($nowHi > '16:30') {
                    $punch->punch_status = $inPrefix . ' & LATE_OUT';
                } else {
                    $punch->punch_status = $inPrefix . ' & COMPLETED';
                }
            }

            $punch->save();

            return response()->json([
                'success' => true,
                'message' => 'Successfully recorded ' . ($punchType === 'IN' ? 'Morning IN-Time' : 'Evening OUT-Time') . ' punch!',
                'punch' => $punch,
                'distance_meters' => $distance,
                'premises_status' => $premisesStatus,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing attendance punch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GPS Location Setup Page for Super Admin & Principal Mobile Views
     */
    public function showGeofenceSetup(Request $request)
    {
        $geofence = SfCampusGeofenceSetting::first();
        if (!$geofence) {
            $geofence = SfCampusGeofenceSetting::create([
                'campus_name' => 'Carmel College Campus',
                'centroid_lat' => 10.23120000,
                'centroid_lng' => 76.20450000,
                'radius_meters' => 150,
                'max_accuracy_meters' => 30,
                'is_active' => true,
            ]);
        }

        return view('sf_campus_geofence_setup', [
            'geofence' => $geofence
        ]);
    }

    /**
     * API: Save GPS Location Core Setup
     */
    public function saveGeofenceSetup(Request $request)
    {
        $request->validate([
            'centroid_lat' => 'required|numeric',
            'centroid_lng' => 'required|numeric',
            'radius_meters' => 'required|integer|min:10|max:5000',
            'max_accuracy_meters' => 'required|integer|min:5|max:200',
        ]);

        $geofence = SfCampusGeofenceSetting::first();
        if (!$geofence) {
            $geofence = new SfCampusGeofenceSetting();
        }

        $geofence->campus_name = $request->input('campus_name', 'Carmel College Campus');
        $geofence->centroid_lat = $request->input('centroid_lat');
        $geofence->centroid_lng = $request->input('centroid_lng');
        $geofence->radius_meters = $request->input('radius_meters');
        $geofence->max_accuracy_meters = $request->input('max_accuracy_meters');
        $geofence->is_active = true;
        $geofence->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Campus GPS Location Setup updated successfully!',
                'geofence' => $geofence
            ]);
        }

        return redirect()->back()->with('success', 'Campus GPS Location Setup updated successfully!');
    }

    /**
     * Master Attendance Report View (Super Admin, Admin, Principal, SF Academic Coordinator)
     */
    public function showAttendanceReport(Request $request)
    {
        $today = now()->format('Y-m-d');
        $startDate = $request->input('start_date', $today);
        $endDate = $request->input('end_date', $today);
        $search = $request->input('search');
        $premisesFilter = $request->input('premises_status');

        $query = SfStaffTimePunch::whereBetween('punch_date', [$startDate, $endDate]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('staff_id', 'like', "%{$search}%")
                  ->orWhere('staff_name', 'like', "%{$search}%");
            });
        }

        if ($premisesFilter) {
            $query->where(function($q) use ($premisesFilter) {
                $q->where('in_premises_status', $premisesFilter)
                  ->orWhere('out_premises_status', $premisesFilter);
            });
        }

        $punches = $query->orderBy('punch_date', 'desc')->orderBy('created_at', 'desc')->get();

        $geofence = SfCampusGeofenceSetting::first();
        if (!$geofence) {
            $geofence = (object)[
                'campus_name' => 'Carmel College Campus',
                'centroid_lat' => 10.23120000,
                'centroid_lng' => 76.20450000,
                'radius_meters' => 150,
                'max_accuracy_meters' => 30
            ];
        }

        $registeredStaff = SfStaffFaceRegistration::all();

        return response()
            ->view('sf_staff_attendance_report', [
                'punches' => $punches,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'search' => $search,
                'premisesFilter' => $premisesFilter,
                'geofence' => $geofence,
                'registeredStaff' => $registeredStaff,
            ])
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1900 00:00:00 GMT');
    }

    /**
     * Delete an accidental/test attendance punch entry (Admin / Management only).
     */
    public function deletePunch(Request $request, $id)
    {
        $punch = SfStaffTimePunch::find($id);
        if (!$punch) {
            return response()->json(['success' => false, 'message' => 'Attendance record not found.'], 404);
        }

        $punch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance punch record deleted successfully!'
        ]);
    }

    /**
     * Reset / Delete biometric face registration and all associated attendance logs for a staff member (Admin / Management only).
     */
    public function resetFaceRegistration(Request $request, $staffId)
    {
        $targetId = trim($staffId);

        // 1. Delete biometric face registration
        $deletedReg = SfStaffFaceRegistration::where('staff_id', $targetId)
                    ->orWhere('mobile_no', $targetId)
                    ->orWhere('staff_id', 'like', "%{$targetId}%")
                    ->orWhere('mobile_no', 'like', "%{$targetId}%")
                    ->delete();

        // 2. Delete all attendance punch logs for this staff member
        $deletedPunches = SfStaffTimePunch::where('staff_id', $targetId)
                    ->orWhere('staff_id', 'like', "%{$targetId}%")
                    ->delete();

        return response()->json([
            'success' => true,
            'message' => "Biometric face registration and all attendance logs for Staff ID '{$staffId}' deregistered and cleared successfully!"
        ]);
    }

    /**
     * Calculate Cosine Similarity between two 128-float facial descriptors.
     * Returns value between -1.0 and 1.0 (Higher = more similar).
     */
    private function calculateCosineSimilarity(array $desc1, array $desc2): float
    {
        $count = min(count($desc1), count($desc2));
        if ($count === 0) return 0.0;
        
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;
        
        for ($i = 0; $i < $count; $i++) {
            $valA = (float)$desc1[$i];
            $valB = (float)$desc2[$i];
            $dotProduct += $valA * $valB;
            $normA += $valA * $valA;
            $normB += $valB * $valB;
        }
        
        $denom = sqrt($normA) * sqrt($normB);
        if ($denom < 1e-6) return 0.0;
        
        return $dotProduct / $denom;
    }
}
