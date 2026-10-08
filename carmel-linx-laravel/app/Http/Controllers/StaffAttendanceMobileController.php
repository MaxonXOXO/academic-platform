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
            $returnUrl = (Session::get('userRole') === 'HOD') ? '/dashboard/hod' : '/dashboard/staff/mobile';
            return redirect($returnUrl)->with('error', 'Biometric attendance is only applicable for EL, CT, AU, and General SF staff.');
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
                'campus_name' => 'Carmel Polytechnic College Campus',
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
                    'message' => "❌ Attendance Rejected: You are currently {$distLabel} outside Carmel Polytechnic College Campus. Biometric punch is restricted to campus premises."
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
                'campus_name' => 'Carmel Polytechnic College Campus',
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

        $geofence->campus_name = $request->input('campus_name', 'Carmel Polytechnic College Campus');
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
     * Supports:
     *  1. Registered Users List
     *  2. Daily Log date-wise using date selection
     *  3. Monthly Log of all staff
     *  4. User-wise Individual Report (Monthly & All-time)
     */
    public function showAttendanceReport(Request $request)
    {
        $userRole = Session::get('userRole');
        $allowedRoles = [
            'SUPER_ADMIN', 'Super_Admin',
            'PRINCIPAL', 'Principal',
            'ADMIN', 'Admin',
            'CHAIRMAN', 'Chairman',
            'ACADEMIC_COORDINATOR_SF', 'Academic_Coordinator_SF',
            'GEN_DEPT_COORDINATOR_SELF_FINANCE', 'Gen_Dept_Coordinator_Self_Finance'
        ];
        if ($userRole && !in_array($userRole, $allowedRoles)) {
            return redirect('/dashboard/staff/mobile')->with('error', 'Unauthorized access to SF Attendance Reports.');
        }

        $today = now()->format('Y-m-d');
        $currentMonth = now()->format('Y-m');

        // Determine active tab: 'daily', 'monthly', 'individual', 'registered'
        $activeTab = $request->input('tab');
        if (!$activeTab) {
            if ($request->has('staff_id')) {
                $activeTab = 'individual';
            } elseif ($request->has('month')) {
                $activeTab = 'monthly';
            } elseif ($request->has('registered')) {
                $activeTab = 'registered';
            } else {
                $activeTab = 'daily';
            }
        }

        // 1. Registered Staff List (with staff profiles)
        $registeredStaff = SfStaffFaceRegistration::orderBy('created_at', 'desc')->get();
        $staffMobileNos = $registeredStaff->pluck('mobile_no')->merge($registeredStaff->pluck('staff_id'))->unique()->filter();
        $profiles = StaffProfile::whereIn('mobile_no', $staffMobileNos)
            ->orWhereIn('id', $staffMobileNos)
            ->get();

        $profileMap = [];
        foreach ($profiles as $prof) {
            $profileMap[$prof->mobile_no] = $prof;
            $profileMap[$prof->id] = $prof;
        }

        $punchCounts = SfStaffTimePunch::select(
            'staff_id',
            DB::raw('count(*) as total_punches'),
            DB::raw('max(punch_date) as latest_punch'),
            DB::raw('min(punch_date) as first_punch')
        )->groupBy('staff_id')->get()->keyBy('staff_id');

        foreach ($registeredStaff as $staff) {
            $staffProf = $profileMap[$staff->mobile_no] ?? $profileMap[$staff->staff_id] ?? null;
            $staff->branch = $staffProf->branch ?? 'SF';
            $staff->designation = $staffProf->designation ?? 'Faculty';
            $staff->email = $staffProf->email ?? null;
            $punchMeta = $punchCounts->get($staff->staff_id);
            $staff->total_punches = $punchMeta ? $punchMeta->total_punches : 0;
            $staff->latest_punch = $punchMeta ? $punchMeta->latest_punch : null;
            $staff->first_punch = $punchMeta ? $punchMeta->first_punch : null;
        }

        // Geofence settings
        $geofence = SfCampusGeofenceSetting::first();
        if (!$geofence) {
            $geofence = (object)[
                'campus_name' => 'Carmel Polytechnic College Campus',
                'centroid_lat' => 10.23120000,
                'centroid_lng' => 76.20450000,
                'radius_meters' => 150,
                'max_accuracy_meters' => 30
            ];
        }

        // TAB 1: DAILY LOG
        $selectedDate = $request->input('date', $request->input('start_date', $today));
        $dailySearch = $request->input('daily_search', $request->input('search'));
        $premisesFilter = $request->input('premises_status');

        $dailyQuery = SfStaffTimePunch::where('punch_date', $selectedDate);
        if ($dailySearch) {
            $dailyQuery->where(function($q) use ($dailySearch) {
                $q->where('staff_id', 'like', "%{$dailySearch}%")
                  ->orWhere('staff_name', 'like', "%{$dailySearch}%");
            });
        }
        if ($premisesFilter) {
            $dailyQuery->where(function($q) use ($premisesFilter) {
                $q->where('in_premises_status', $premisesFilter)
                  ->orWhere('out_premises_status', $premisesFilter);
            });
        }
        $dailyPunches = $dailyQuery->orderBy('in_time', 'asc')->get();

        $dailyTotalPresent = $dailyPunches->whereNotNull('in_time')->count();
        $dailyInside = $dailyPunches->where('in_premises_status', 'INSIDE_PREMISES')->count();
        $dailyOutside = $dailyPunches->where('in_premises_status', 'OUTSIDE_PREMISES')->count();
        $dailyLate = $dailyPunches->filter(function($p) {
            return str_contains($p->punch_status ?? '', 'LATE_IN');
        })->count();
        $dailyEarlyIn = $dailyPunches->filter(function($p) {
            return str_contains($p->punch_status ?? '', 'EARLY_IN');
        })->count();
        $dailyCompleted = $dailyPunches->whereNotNull('out_time')->count();

        // TAB 2: MONTHLY LOG OF ALL
        $selectedMonth = $request->input('month', $currentMonth);
        $monthlyViewType = $request->input('monthly_view_type', 'summary'); // 'summary' or 'detailed'
        $startOfMonth = $selectedMonth . '-01';
        $endOfMonth = date('Y-m-t', strtotime($startOfMonth));

        $monthlyPunchesQuery = SfStaffTimePunch::whereBetween('punch_date', [$startOfMonth, $endOfMonth]);
        if ($request->filled('monthly_search')) {
            $mSearch = $request->input('monthly_search');
            $monthlyPunchesQuery->where(function($q) use ($mSearch) {
                $q->where('staff_id', 'like', "%{$mSearch}%")
                  ->orWhere('staff_name', 'like', "%{$mSearch}%");
            });
        }
        $allMonthlyPunches = $monthlyPunchesQuery->orderBy('punch_date', 'desc')->orderBy('in_time', 'asc')->get();

        $groupedMonthlyPunches = $allMonthlyPunches->groupBy('staff_id');
        $monthlyStaffSummary = [];

        // Iterate through registered staff first
        foreach ($registeredStaff as $rs) {
            $sId = $rs->staff_id;
            $staffPunches = $groupedMonthlyPunches->get($sId, collect());
            
            $daysPresent = $staffPunches->whereNotNull('in_time')->count();
            $insidePremisesDays = $staffPunches->where('in_premises_status', 'INSIDE_PREMISES')->count();
            $lateCount = $staffPunches->filter(function($p) {
                return str_contains($p->punch_status ?? '', 'LATE_IN');
            })->count();
            $earlyOutCount = $staffPunches->filter(function($p) {
                return str_contains($p->punch_status ?? '', 'EARLY_OUT');
            })->count();

            $totalMinutes = 0;
            foreach ($staffPunches as $sp) {
                if ($sp->in_time && $sp->out_time) {
                    $totalMinutes += round(abs(strtotime($sp->out_time) - strtotime($sp->in_time)) / 60);
                }
            }
            $totHrs = floor($totalMinutes / 60);
            $totMins = $totalMinutes % 60;

            $avgMinutes = $daysPresent > 0 ? round($totalMinutes / $daysPresent) : 0;
            $avgHrs = floor($avgMinutes / 60);
            $avgMins = $avgMinutes % 60;

            $monthlyStaffSummary[] = (object)[
                'staff_id' => $sId,
                'staff_name' => $rs->staff_name,
                'branch' => $rs->branch,
                'designation' => $rs->designation,
                'photo_url' => $rs->photo_url,
                'days_present' => $daysPresent,
                'inside_premises_days' => $insidePremisesDays,
                'late_count' => $lateCount,
                'early_out_count' => $earlyOutCount,
                'total_minutes' => $totalMinutes,
                'total_hours_formatted' => "{$totHrs}h {$totMins}m",
                'avg_hours_formatted' => "{$avgHrs}h {$avgMins}m",
                'punches' => $staffPunches,
            ];
        }

        // Also include any staff who have punches but no registration record
        foreach ($groupedMonthlyPunches as $sId => $staffPunches) {
            if (!$registeredStaff->contains('staff_id', $sId)) {
                $firstP = $staffPunches->first();
                $daysPresent = $staffPunches->whereNotNull('in_time')->count();
                $insidePremisesDays = $staffPunches->where('in_premises_status', 'INSIDE_PREMISES')->count();
                $lateCount = $staffPunches->filter(function($p) {
                    return str_contains($p->punch_status ?? '', 'LATE_IN');
                })->count();
                $earlyOutCount = $staffPunches->filter(function($p) {
                    return str_contains($p->punch_status ?? '', 'EARLY_OUT');
                })->count();

                $totalMinutes = 0;
                foreach ($staffPunches as $sp) {
                    if ($sp->in_time && $sp->out_time) {
                        $totalMinutes += round(abs(strtotime($sp->out_time) - strtotime($sp->in_time)) / 60);
                    }
                }
                $totHrs = floor($totalMinutes / 60);
                $totMins = $totalMinutes % 60;

                $avgMinutes = $daysPresent > 0 ? round($totalMinutes / $daysPresent) : 0;
                $avgHrs = floor($avgMinutes / 60);
                $avgMins = $avgMinutes % 60;

                $staffProf = $profileMap[$sId] ?? null;

                $monthlyStaffSummary[] = (object)[
                    'staff_id' => $sId,
                    'staff_name' => $firstP->staff_name ?? 'SF Staff',
                    'branch' => $staffProf->branch ?? 'SF',
                    'designation' => $staffProf->designation ?? 'Faculty',
                    'photo_url' => null,
                    'days_present' => $daysPresent,
                    'inside_premises_days' => $insidePremisesDays,
                    'late_count' => $lateCount,
                    'early_out_count' => $earlyOutCount,
                    'total_minutes' => $totalMinutes,
                    'total_hours_formatted' => "{$totHrs}h {$totMins}m",
                    'avg_hours_formatted' => "{$avgHrs}h {$avgMins}m",
                    'punches' => $staffPunches,
                ];
            }
        }

        usort($monthlyStaffSummary, function($a, $b) {
            return strcmp($a->staff_name, $b->staff_name);
        });

        $monthlyTotalHoursMins = 0;
        $monthlyTotalLateEntries = 0;
        foreach ($monthlyStaffSummary as $mss) {
            $monthlyTotalHoursMins += $mss->total_minutes;
            $monthlyTotalLateEntries += $mss->late_count;
        }
        $monthTotHrs = floor($monthlyTotalHoursMins / 60);
        $monthTotMins = $monthlyTotalHoursMins % 60;
        $monthlyTotalHoursFormatted = "{$monthTotHrs}h {$monthTotMins}m";
        $monthlyActiveStaffCount = collect($monthlyStaffSummary)->where('days_present', '>', 0)->count();

        // TAB 3: USERWISE INDIVIDUAL REPORT
        $selectedStaffId = $request->input('staff_id');
        if (!$selectedStaffId && $registeredStaff->isNotEmpty()) {
            $selectedStaffId = $registeredStaff->first()->staff_id;
        }
        $individualPeriod = $request->input('period', 'month'); // 'month' or 'all'
        $individualMonth = $request->input('individual_month', $selectedMonth);

        $individualStaff = null;
        $individualPunches = collect();
        $individualStats = (object)[
            'days_present' => 0,
            'total_hours_formatted' => '0h 0m',
            'avg_hours_formatted' => '0h 0m',
            'late_count' => 0,
            'early_out_count' => 0,
            'inside_percentage' => 100,
            'total_minutes' => 0,
        ];

        if ($selectedStaffId) {
            $individualStaff = $registeredStaff->firstWhere('staff_id', $selectedStaffId);
            if (!$individualStaff) {
                $regObj = SfStaffFaceRegistration::where('staff_id', $selectedStaffId)->orWhere('mobile_no', $selectedStaffId)->first();
                if ($regObj) {
                    $staffProf = $profileMap[$regObj->mobile_no] ?? $profileMap[$regObj->staff_id] ?? null;
                    $regObj->branch = $staffProf->branch ?? 'SF';
                    $regObj->designation = $staffProf->designation ?? 'Faculty';
                    $individualStaff = $regObj;
                } else {
                    $staffProf = $profileMap[$selectedStaffId] ?? null;
                    $firstP = SfStaffTimePunch::where('staff_id', $selectedStaffId)->first();
                    $individualStaff = (object)[
                        'staff_id' => $selectedStaffId,
                        'staff_name' => $firstP->staff_name ?? ($staffProf->name ?? 'Staff ' . $selectedStaffId),
                        'mobile_no' => $selectedStaffId,
                        'branch' => $staffProf->branch ?? 'SF',
                        'designation' => $staffProf->designation ?? 'Faculty',
                        'photo_url' => null,
                        'created_at' => null,
                    ];
                }
            }

            $indQuery = SfStaffTimePunch::where('staff_id', $selectedStaffId);
            if ($individualPeriod === 'month') {
                $indStart = $individualMonth . '-01';
                $indEnd = date('Y-m-t', strtotime($indStart));
                $indQuery->whereBetween('punch_date', [$indStart, $indEnd]);
            }
            $individualPunches = $indQuery->orderBy('punch_date', 'desc')->get();

            $daysPresent = $individualPunches->whereNotNull('in_time')->count();
            $insideCount = $individualPunches->where('in_premises_status', 'INSIDE_PREMISES')->count();
            $lateCount = $individualPunches->filter(function($p) {
                return str_contains($p->punch_status ?? '', 'LATE_IN');
            })->count();
            $earlyOutCount = $individualPunches->filter(function($p) {
                return str_contains($p->punch_status ?? '', 'EARLY_OUT');
            })->count();

            $totMins = 0;
            foreach ($individualPunches as $ip) {
                if ($ip->in_time && $ip->out_time) {
                    $totMins += round(abs(strtotime($ip->out_time) - strtotime($ip->in_time)) / 60);
                }
            }
            $h = floor($totMins / 60);
            $m = $totMins % 60;
            $avgM = $daysPresent > 0 ? round($totMins / $daysPresent) : 0;
            $avgH = floor($avgM / 60);
            $avgMin = $avgM % 60;
            $insidePct = $daysPresent > 0 ? round(($insideCount / $daysPresent) * 100) : 100;

            $individualStats = (object)[
                'days_present' => $daysPresent,
                'total_hours_formatted' => "{$h}h {$m}m",
                'avg_hours_formatted' => "{$avgH}h {$avgMin}m",
                'late_count' => $lateCount,
                'early_out_count' => $earlyOutCount,
                'inside_percentage' => $insidePct,
                'total_minutes' => $totMins,
            ];
        }

        return response()
            ->view('sf_staff_attendance_report', [
                'activeTab' => $activeTab,
                // Tab 1: Daily Log
                'selectedDate' => $selectedDate,
                'dailySearch' => $dailySearch,
                'premisesFilter' => $premisesFilter,
                'dailyPunches' => $dailyPunches,
                'dailyTotalPresent' => $dailyTotalPresent,
                'dailyInside' => $dailyInside,
                'dailyOutside' => $dailyOutside,
                'dailyLate' => $dailyLate,
                'dailyEarlyIn' => $dailyEarlyIn,
                'dailyCompleted' => $dailyCompleted,
                // Tab 2: Monthly Log
                'selectedMonth' => $selectedMonth,
                'monthlyViewType' => $monthlyViewType,
                'allMonthlyPunches' => $allMonthlyPunches,
                'monthlyStaffSummary' => $monthlyStaffSummary,
                'monthlyTotalHoursFormatted' => $monthlyTotalHoursFormatted,
                'monthlyActiveStaffCount' => $monthlyActiveStaffCount,
                'monthlyTotalLateEntries' => $monthlyTotalLateEntries,
                // Tab 3: Individual Report
                'selectedStaffId' => $selectedStaffId,
                'individualPeriod' => $individualPeriod,
                'individualMonth' => $individualMonth,
                'individualStaff' => $individualStaff,
                'individualPunches' => $individualPunches,
                'individualStats' => $individualStats,
                // Tab 4: Registered Staff
                'registeredStaff' => $registeredStaff,
                // Geofence & Meta
                'geofence' => $geofence,
                // Backwards compatibility
                'punches' => $dailyPunches,
                'startDate' => $selectedDate,
                'endDate' => $selectedDate,
                'search' => $dailySearch,
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
