<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\OeCourse;
use App\Models\OeSetting;
use App\Models\OeRegistration;
use App\Models\OeVirtualClassroom;
use App\Models\ClassManagement;
use App\Models\BatchSubject;
use App\Models\SubjectStaffAssignment;
use App\Models\Student;
use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Carbon\Carbon;

class OpenElectiveAdminController extends Controller
{
    private const ALLOWED_ROLES = ['Super_Admin', 'Principal', 'Admin', 'Chairman'];
    private const ALL_BRANCHES = ['AU', 'CE', 'CT', 'EEE', 'EL', 'ME'];

    /**
     * Check if current user has executive authorization.
     */
    private function checkAccess()
    {
        $role = Session::get('userRole');
        return in_array($role, self::ALLOWED_ROLES);
    }

    /**
     * Main Open Elective Super Admin / Principal Desk.
     */
    public function index(Request $request)
    {
        if (!$this->checkAccess()) {
            return redirect('/')->with('error', 'Unauthorized access to Open Elective Management.');
        }

        $settings = OeSetting::getActiveSettings();
        $courses = OeCourse::orderBy('offering_branch')->get();

        // 5th Semester Student Analytics (Exclude suspended duplicate self-registration accounts)
        $s5StudentsQuery = Student::where('semester', 5)->where('status', 'Approved');
        $totalS5Students = (clone $s5StudentsQuery)->count();
        $branchBreakdown = (clone $s5StudentsQuery)
            ->select('branch', DB::raw('count(*) as count'))
            ->groupBy('branch')
            ->pluck('count', 'branch')
            ->toArray();

        // Registration stats
        $registeredCount = OeRegistration::count();
        $allottedCount = OeRegistration::whereNotNull('allotted_course_id')->count();
        $pendingCount = max(0, $totalS5Students - $registeredCount);

        // Complete list of registrations with all 5 preferences & student info
        $registrations = OeRegistration::with([
            'option1Course',
            'option2Course',
            'option3Course',
            'option4Course',
            'option5Course',
            'allottedCourse',
            'student'
        ])->orderBy('registered_at', 'ASC')->get();

        $completed5Count = $registrations->filter(function ($r) {
            return $r->hasAllFiveOptions();
        })->count();
        $incompleteCount = max(0, $registrations->count() - $completed5Count);

        // Course statistics & HOD readiness check
        $courseStats = [];
        $submittedHodsCount = 0;
        foreach ($courses as $c) {
            $opt1Count = OeRegistration::where('option_1_course_id', $c->id)->count();
            $allotted = OeRegistration::where('allotted_course_id', $c->id)->count();
            if ($c->is_hod_submitted) {
                $submittedHodsCount++;
            }
            $courseStats[$c->id] = [
                'course' => $c,
                'opt1_count' => $opt1Count,
                'allotted_count' => $allotted,
                'remaining_seats' => max(0, $c->max_capacity - $allotted),
                'fill_percentage' => $c->max_capacity > 0 ? round(($allotted / $c->max_capacity) * 100, 1) : 0,
            ];
        }

        return view('open_elective.admin_index', compact(
            'settings',
            'courses',
            'totalS5Students',
            'branchBreakdown',
            'registeredCount',
            'allottedCount',
            'pendingCount',
            'courseStats',
            'submittedHodsCount',
            'registrations',
            'completed5Count',
            'incompleteCount'
        ));
    }

    /**
     * Save / Update an Open Elective Course offering (Admin Override).
     */
    public function saveCourse(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'offering_branch' => 'required|string|max:10',
            'course_code' => 'required|string|max:20',
            'course_title' => 'required|string|max:150',
            'max_capacity' => 'nullable|integer|min:10|max:120',
        ]);

        $courseId = $request->input('id');
        $branch = strtoupper(trim($request->input('offering_branch')));
        $capacity = (int)($request->input('max_capacity', 60));

        $data = [
            'academic_year' => $request->input('academic_year', '2026-2027'),
            'semester' => 6,
            'offering_branch' => $branch,
            'course_code' => strtoupper(trim($request->input('course_code'))),
            'course_title' => trim($request->input('course_title')),
            'course_description' => $request->input('course_description'),
            'course_outcomes' => $request->input('course_outcomes'),
            'max_capacity' => $capacity > 0 ? $capacity : 60,
            'faculty_name' => $request->input('faculty_name'),
            'faculty_phone' => $request->input('faculty_phone'),
            'faculty_staff_id' => $request->input('faculty_staff_id'),
            'is_active' => $request->boolean('is_active', true),
            'is_hod_submitted' => true,
        ];

        if ($courseId) {
            $course = OeCourse::findOrFail($courseId);
            $course->update($data);
        } else {
            $exists = OeCourse::where('offering_branch', $branch)
                ->where('academic_year', $data['academic_year'])
                ->where('semester', 6)
                ->first();

            if ($exists) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => "Department {$branch} already has an Open Elective course registered for this term."
                ], 422);
            }

            $course = OeCourse::create($data);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Course saved successfully.',
            'course' => $course,
        ]);
    }

    /**
     * Delete an Open Elective Course offering.
     */
    public function deleteCourse($id)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $course = OeCourse::findOrFail($id);

        $regCount = OeRegistration::where('option_1_course_id', $course->id)
            ->orWhere('allotted_course_id', $course->id)
            ->count();

        if ($regCount > 0) {
            return response()->json([
                'status' => 'ERROR',
                'message' => "Cannot delete course '{$course->course_title}' because {$regCount} student registrations reference it."
            ], 422);
        }

        $course->delete();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Course deleted successfully.'
        ]);
    }

    /**
     * Save Registration Window, Timers and Status.
     */
    public function saveSettings(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $settings = OeSetting::getActiveSettings();

        $settings->reg_start_at = $request->filled('reg_start_at') ? Carbon::parse($request->input('reg_start_at')) : null;
        $settings->reg_end_at = $request->filled('reg_end_at') ? Carbon::parse($request->input('reg_end_at')) : null;
        $settings->is_registration_open = $request->boolean('is_registration_open');
        $settings->push_title = $request->input('push_title', 'S6 Open Elective Registration Open');
        $settings->push_body = $request->input('push_body', 'Please select your 6th semester Open Elective preferences on Carmel Linx.');
        $settings->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Settings and registration window updated successfully.',
            'settings' => $settings,
        ]);
    }

    /**
     * Broadcast Web Push Notification to all active Semester 5 students.
     */
    public function broadcastPush(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $settings = OeSetting::getActiveSettings();

        // Get all active/approved S5 student registration numbers
        $s5StudentRegNos = Student::where('semester', 5)
            ->where('status', 'Approved')
            ->whereNotNull('reg_no')
            ->pluck('reg_no')
            ->toArray();

        if (empty($s5StudentRegNos)) {
            return response()->json(['status' => 'ERROR', 'message' => 'No active Semester 5 students found in database.'], 404);
        }

        $subscriptions = PushSubscription::where('role', 'student')
            ->whereIn('user_id', $s5StudentRegNos)
            ->get();

        $title = $request->input('title', $settings->push_title);
        $body = $request->input('body', $settings->push_body);
        $url = '/student/open-elective';

        $dispatchCount = 0;
        if ($subscriptions->isNotEmpty()) {
            PushNotificationService::dispatchPayload(
                $subscriptions,
                $title,
                $body,
                $url,
                'open-elective-reg'
            );
            $dispatchCount = $subscriptions->count();
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Push notification broadcast dispatched to {$dispatchCount} active student devices (Total S5 cohort: " . count($s5StudentRegNos) . ").",
            'dispatched_count' => $dispatchCount,
            'cohort_count' => count($s5StudentRegNos),
        ]);
    }

    /**
     * Broadcast mandatory 5-option resubmission notification to S5 students.
     */
    public function notifyResubmit(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $targetType = $request->input('target_type', 'all_s5'); // 'all_s5' or 'incomplete_only'

        if ($targetType === 'incomplete_only') {
            $completeAdmNos = OeRegistration::whereNotNull('option_1_course_id')
                ->whereNotNull('option_2_course_id')
                ->whereNotNull('option_3_course_id')
                ->whereNotNull('option_4_course_id')
                ->whereNotNull('option_5_course_id')
                ->pluck('student_adm_no')
                ->toArray();

            $s5StudentRegNos = Student::where('semester', 5)
                ->where('status', 'Approved')
                ->whereNotIn('adm_no', $completeAdmNos)
                ->whereNotNull('reg_no')
                ->pluck('reg_no')
                ->toArray();
        } else {
            $s5StudentRegNos = Student::where('semester', 5)
                ->where('status', 'Approved')
                ->whereNotNull('reg_no')
                ->pluck('reg_no')
                ->toArray();
        }

        if (empty($s5StudentRegNos)) {
            return response()->json(['status' => 'ERROR', 'message' => 'No matching Semester 5 students found.'], 404);
        }

        $subscriptions = PushSubscription::where('role', 'student')
            ->whereIn('user_id', $s5StudentRegNos)
            ->get();

        $title = $request->input('title', 'Rev 2021 Open Elective: 5 Options Compulsory');
        $body = $request->input('body', 'All 5 options are mandatory for Revision 2021 Semester 6 Open Elective. If you have selected fewer options or need to re-register, please login to Carmel Linx and lock all 5 preferences.');
        $url = '/student/open-elective';

        $dispatchCount = 0;
        if ($subscriptions->isNotEmpty()) {
            PushNotificationService::dispatchPayload(
                $subscriptions,
                $title,
                $body,
                $url,
                'oe-resubmit-alert'
            );
            $dispatchCount = $subscriptions->count();
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Mandatory 5-option notice dispatched to {$dispatchCount} student devices (Cohort size: " . count($s5StudentRegNos) . " students).",
            'dispatched_count' => $dispatchCount,
            'cohort_count' => count($s5StudentRegNos),
        ]);
    }

    /**
     * Fetch real-time Allocation & Course-wise Pool data.
     */
    public function getAllocationData(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $courses = OeCourse::where('is_active', true)->orderBy('offering_branch')->get();
        $registrations = OeRegistration::with(['option1Course', 'option2Course', 'option3Course', 'option4Course', 'option5Course', 'allottedCourse'])
            ->orderBy('registered_at', 'ASC')
            ->get();

        $coursePools = [];
        foreach ($courses as $course) {
            $coursePools[$course->id] = [
                'course' => $course,
                'opt1_applicants' => [],
                'allotted_students' => [],
                'rollover_potential' => [],
            ];
        }

        $unallotted = [];

        foreach ($registrations as $reg) {
            // Option 1 pool
            if (isset($coursePools[$reg->option_1_course_id])) {
                $coursePools[$reg->option_1_course_id]['opt1_applicants'][] = $reg;
            }

            // Allotted pool
            if ($reg->allotted_course_id && isset($coursePools[$reg->allotted_course_id])) {
                $coursePools[$reg->allotted_course_id]['allotted_students'][] = $reg;
            } else {
                $unallotted[] = $reg;
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'is_released_to_hods' => (bool) OeSetting::getActiveSettings()->is_released_to_hods,
            'courses' => $courses,
            'coursePools' => $coursePools,
            'unallotted' => $unallotted,
            'all_registrations' => $registrations,
            'total_registered' => $registrations->count(),
            'total_allotted' => $registrations->whereNotNull('allotted_course_id')->count(),
        ]);
    }

    /**
     * Run Smart FCFS Auto-Allocation (Option 1 FCFS + Option 2..5 Rollover).
     */
    public function autoAllocate(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        DB::beginTransaction();
        try {
            $courses = OeCourse::where('is_active', true)->get()->keyBy('id');
            $capacities = [];
            $allottedCounts = [];
            foreach ($courses as $id => $c) {
                $capacities[$id] = (int)$c->max_capacity;
                $allottedCounts[$id] = 0;
            }

            // Keep admin manual overrides if requested, else reset
            $preserveOverrides = $request->boolean('preserve_overrides', false);

            if ($preserveOverrides) {
                $existingOverrides = OeRegistration::where('allocation_source', 'admin_override')
                    ->whereNotNull('allotted_course_id')
                    ->get();

                foreach ($existingOverrides as $ov) {
                    if (isset($allottedCounts[$ov->allotted_course_id])) {
                        $allottedCounts[$ov->allotted_course_id]++;
                    }
                }

                // Reset only non-overrides
                OeRegistration::where('allocation_source', '!=', 'admin_override')
                    ->orWhereNull('allocation_source')
                    ->update([
                        'allotted_course_id' => null,
                        'allocation_source' => null,
                        'status' => 'submitted',
                    ]);
            } else {
                OeRegistration::query()->update([
                    'allotted_course_id' => null,
                    'allocation_source' => null,
                    'status' => 'submitted',
                ]);
            }

            // Get unallotted students sorted by registered_at ASC (Strict FCFS timestamp)
            $pendingStudents = OeRegistration::whereNull('allotted_course_id')
                ->orderBy('registered_at', 'ASC')
                ->get();

            // Pass 1: Try Option 1
            $unplacedAfterPass1 = [];
            foreach ($pendingStudents as $student) {
                $opt1 = $student->option_1_course_id;
                if (isset($capacities[$opt1]) && $allottedCounts[$opt1] < $capacities[$opt1]) {
                    $student->allotted_course_id = $opt1;
                    $student->allocation_source = 'option_1_fcfs';
                    $student->status = 'allotted';
                    $student->save();
                    $allottedCounts[$opt1]++;
                } else {
                    $unplacedAfterPass1[] = $student;
                }
            }

            // Pass 2: Rollover through Option 2, Option 3, Option 4, Option 5
            $unplacedTotal = [];
            foreach ($unplacedAfterPass1 as $student) {
                $placed = false;
                $options = [
                    'option_2_rollover' => $student->option_2_course_id,
                    'option_3_rollover' => $student->option_3_course_id,
                    'option_4_rollover' => $student->option_4_course_id,
                    'option_5_rollover' => $student->option_5_course_id,
                ];

                foreach ($options as $source => $optCourseId) {
                    if ($optCourseId && isset($capacities[$optCourseId]) && $allottedCounts[$optCourseId] < $capacities[$optCourseId]) {
                        $student->allotted_course_id = $optCourseId;
                        $student->allocation_source = $source;
                        $student->status = 'allotted';
                        $student->save();
                        $allottedCounts[$optCourseId]++;
                        $placed = true;
                        break;
                    }
                }

                if (!$placed) {
                    $unplacedTotal[] = $student;
                }
            }

            DB::commit();

            $totalAllotted = OeRegistration::whereNotNull('allotted_course_id')->count();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Auto-allocation completed: {$totalAllotted} students placed successfully.",
                'allotted_counts' => $allottedCounts,
                'unplaced_count' => count($unplacedTotal),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('OpenElective AutoAllocate Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to allocate: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Manual Override / Fix for a single student.
     */
    public function manualOverride(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'registration_id' => 'required|integer|exists:oe_registrations,id',
            'target_course_id' => 'required|integer|exists:oe_courses,id',
        ]);

        $reg = OeRegistration::findOrFail($request->registration_id);
        $course = OeCourse::findOrFail($request->target_course_id);

        if ($course->offering_branch === $reg->parent_branch && !$request->boolean('force_override', false)) {
            return response()->json([
                'status' => 'WARNING',
                'message' => "Target course '{$course->course_title}' is offered by the student's own parent department ({$reg->parent_branch}). Are you sure you want to proceed?",
                'requires_confirmation' => true,
            ]);
        }

        $reg->allotted_course_id = $course->id;
        $reg->allocation_source = 'admin_override';
        $reg->status = 'allotted';
        $reg->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Student {$reg->student_name} ({$reg->sbte_reg_no}) manually allocated to {$course->course_title}.",
            'student' => $reg,
        ]);
    }

    /**
     * Final Approval & Handover to HODs.
     * Sets up Admission 2024 Open Elective Virtual Classrooms in class_management and batch_subjects.
     * Populates oe_virtual_classrooms with cross-department students.
     */
    public function releaseToHods(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $allottedCount = OeRegistration::whereNotNull('allotted_course_id')->count();
        if ($allottedCount === 0) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Cannot release to HODs: No students have been allocated to any course yet.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $settings = OeSetting::getActiveSettings();
            $now = Carbon::now();

            // Clear old virtual classroom records if re-releasing
            OeVirtualClassroom::where('academic_year', $settings->academic_year)
                ->where('semester', 6)
                ->delete();

            $courses = OeCourse::where('is_active', true)->get();

            // 1. Setup Admission 2024 Open Elective Classrooms & Batch Subjects
            $courseClassroomMap = [];
            foreach ($courses as $c) {
                $classroomId = "{$c->offering_branch}_2024_2027_OE";

                // Ensure classroom exists in class_management for Admission 2024
                $classroom = ClassManagement::firstOrCreate(
                    ['classroom_id' => $classroomId],
                    [
                        'branch' => $c->offering_branch,
                        'batch_year' => 2024,
                        'current_semester' => 6,
                        'tutor_mobile_no' => $c->faculty_staff_id,
                        'mentor_mobile_no' => null,
                    ]
                );

                // Create or find BatchSubject for Theory Virtual Class
                $batchSubject = BatchSubject::firstOrCreate(
                    [
                        'classroom_id' => $classroomId,
                        'semester' => 6,
                        'subject_code' => $c->course_code,
                    ],
                    [
                        'subject_name' => $c->course_title . ' (Open Elective)',
                        'subject_type' => 'Theory',
                        'syllabus_revision_code' => 'REV2021',
                        'lab_batch_mode' => 'full',
                    ]
                );

                // Link to course
                $c->classroom_id = $classroomId;
                $c->batch_subject_id = $batchSubject->id;
                $c->save();

                // If faculty is designated, assign to subject
                if (!empty($c->faculty_staff_id)) {
                    SubjectStaffAssignment::firstOrCreate([
                        'batch_subject_id' => $batchSubject->id,
                        'staff_mobile_no' => $c->faculty_staff_id,
                    ]);
                }

                $courseClassroomMap[$c->id] = [
                    'classroom_id' => $classroomId,
                    'batch_subject_id' => $batchSubject->id,
                ];
            }

            // 2. Populate oe_virtual_classrooms with cross-department students
            $allotted = OeRegistration::whereNotNull('allotted_course_id')->get();
            $records = [];

            foreach ($allotted as $st) {
                $studentMaster = Student::where('adm_no', $st->student_adm_no)->first();
                $rollNo = $studentMaster ? $studentMaster->roll_no : null;
                $cid = $courseClassroomMap[$st->allotted_course_id]['classroom_id'] ?? null;
                $bsid = $courseClassroomMap[$st->allotted_course_id]['batch_subject_id'] ?? null;

                $records[] = [
                    'oe_course_id' => $st->allotted_course_id,
                    'classroom_id' => $cid,
                    'batch_subject_id' => $bsid,
                    'student_adm_no' => $st->student_adm_no,
                    'sbte_reg_no' => $st->sbte_reg_no,
                    'student_name' => $st->student_name,
                    'parent_branch' => $st->parent_branch,
                    'roll_no' => $rollNo,
                    'academic_year' => $settings->academic_year,
                    'semester' => 6,
                    'assigned_faculty_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach (array_chunk($records, 100) as $chunk) {
                OeVirtualClassroom::insert($chunk);
            }

            // 3. Update settings gate & finalize registrations
            $settings->is_released_to_hods = true;
            $settings->released_at = $now;
            $settings->released_by_staff_id = (string)Session::get('userId');
            $settings->save();

            OeRegistration::whereNotNull('allotted_course_id')->update(['status' => 'finalized']);

            // 4. Notify Allotted Students via Web Push
            $allottedStudentRegNos = $allotted->pluck('sbte_reg_no')->filter()->toArray();
            if (!empty($allottedStudentRegNos)) {
                $subs = PushSubscription::where('role', 'student')
                    ->whereIn('user_id', $allottedStudentRegNos)
                    ->get();

                if ($subs->isNotEmpty()) {
                    PushNotificationService::dispatchPayload(
                        $subs,
                        'Open Elective Allotment Confirmed',
                        'Your Semester 6 Open Elective course has been confirmed by the Principal Office. View your classroom details on Carmel Linx.',
                        '/student/open-elective',
                        'oe-allotted-alert'
                    );
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Allocations officially approved! Admission 2024 Open Elective Classrooms setup and transferred to HODs.",
                'released_count' => $allottedCount,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('OpenElective ReleaseToHods Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to release to HODs: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reset all trial registrations and allocations.
     * Keeps HOD course offerings, syllabus PDFs, and COs intact.
     */
    public function resetTrialData(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        DB::beginTransaction();
        try {
            $regCount = OeRegistration::count();
            $classCount = OeVirtualClassroom::count();

            // 1. Wipe registrations and virtual classroom mappings
            OeRegistration::query()->delete();
            OeVirtualClassroom::query()->delete();

            // 2. Reset settings flags
            $settings = OeSetting::getActiveSettings();
            $settings->is_released_to_hods = false;
            $settings->released_at = null;
            $settings->released_by_staff_id = null;
            $settings->save();

            // 3. Clear classroom link references on courses (preserves titles, COs, PDFs)
            OeCourse::query()->update([
                'classroom_id' => null,
                'batch_subject_id' => null,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Trial reset complete! Cleared {$regCount} student registrations and {$classCount} classroom enrollments. System is at zero state.",
                'cleared_registrations' => $regCount,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('OpenElective ResetTrialData Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to reset trial data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Print / Export official Nominal Roll for an Open Elective course.
     */
    public function exportNominalRoll(Request $request, $courseId)
    {
        if (!$this->checkAccess()) {
            $userRole = Session::get('userRole');
            $settings = OeSetting::getActiveSettings();

            if (!($userRole === 'HOD' && $settings->is_released_to_hods)) {
                return redirect('/')->with('error', 'Unauthorized access.');
            }
        }

        $course = OeCourse::findOrFail($courseId);
        $settings = OeSetting::getActiveSettings();

        $students = OeVirtualClassroom::where('oe_course_id', $courseId)
            ->orderBy('parent_branch', 'ASC')
            ->orderBy('sbte_reg_no', 'ASC')
            ->get();

        $branchSummary = $students->groupBy('parent_branch')->map->count();

        return view('open_elective.nominal_roll_print', compact('course', 'students', 'branchSummary', 'settings'));
    }

    /**
     * Print / Export Pre-Allocation First-Option Applicants List for courses.
     */
    public function exportFirstOptionList(Request $request)
    {
        if (!$this->checkAccess()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $courseId = $request->query('course_id');
        $settings = OeSetting::getActiveSettings();

        if ($courseId) {
            $courses = OeCourse::where('id', $courseId)->get();
        } else {
            $courses = OeCourse::where('is_active', true)->orderBy('offering_branch')->get();
        }

        $coursePools = [];
        foreach ($courses as $c) {
            $applicants = OeRegistration::where('option_1_course_id', $c->id)
                ->with(['student', 'option1Course', 'option2Course', 'option3Course', 'option4Course', 'option5Course'])
                ->orderBy('registered_at', 'ASC')
                ->get();

            $coursePools[] = [
                'course' => $c,
                'applicants' => $applicants,
                'branchSummary' => $applicants->groupBy('parent_branch')->map->count()->toArray(),
                'total_count' => $applicants->count(),
            ];
        }

        return view('open_elective.first_option_list_print', compact('coursePools', 'settings', 'courseId'));
    }

    /**
     * Print / Export Pre-Approval Allocation Analysis List (after FCFS, before HOD handover).
     * Includes Branch, SBTE Number, Name, 1st Option, 2nd Option, and Allocation Source.
     */
    public function exportAllocationAnalysis(Request $request)
    {
        if (!$this->checkAccess()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $courseId = $request->query('course_id');
        $settings = OeSetting::getActiveSettings();

        if ($courseId) {
            $courses = OeCourse::where('id', $courseId)->get();
        } else {
            $courses = OeCourse::where('is_active', true)->orderBy('offering_branch')->get();
        }

        $coursePools = [];
        foreach ($courses as $c) {
            $students = OeRegistration::where('allotted_course_id', $c->id)
                ->with(['option1Course', 'option2Course', 'allottedCourse'])
                ->orderBy('parent_branch', 'ASC')
                ->orderBy('sbte_reg_no', 'ASC')
                ->get();

            $coursePools[] = [
                'course' => $c,
                'students' => $students,
                'branchSummary' => $students->groupBy('parent_branch')->map->count()->toArray(),
                'sourceSummary' => $students->groupBy('allocation_source')->map->count()->toArray(),
                'total_count' => $students->count(),
            ];
        }

        $unallottedStudents = collect();
        if (!$courseId) {
            $unallottedStudents = OeRegistration::whereNull('allotted_course_id')
                ->with(['option1Course', 'option2Course'])
                ->orderBy('parent_branch', 'ASC')
                ->orderBy('sbte_reg_no', 'ASC')
                ->get();
        }

        $totalRegistered = OeRegistration::count();
        $totalAllotted = OeRegistration::whereNotNull('allotted_course_id')->count();

        return view('open_elective.allocation_analysis_print', compact(
            'coursePools',
            'unallottedStudents',
            'settings',
            'courseId',
            'totalRegistered',
            'totalAllotted'
        ));
    }
}
