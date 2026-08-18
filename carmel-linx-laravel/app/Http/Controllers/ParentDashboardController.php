<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\BatchSubject;

class ParentDashboardController extends Controller
{
    /**
     * Show Passwordless Parent Login Page.
     */
    public function showLoginPage()
    {
        return view('parent_login');
    }

    /**
     * Handle Passwordless Login verification using Student Reg No + Guardian Mobile No.
     */
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'regNo' => 'required|string',
            'guardianMobile' => 'required|string',
        ]);

        $regNo = strtoupper(trim($request->input('regNo')));
        $cleanMobile = preg_replace('/[^0-9]/', '', $request->input('guardianMobile'));

        // Match student record
        $student = Student::where('reg_no', $regNo)
            ->orWhere('adm_no', $regNo)
            ->orWhere('sbte_reg_no', $regNo)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No student profile found with the provided Register/Admission Number.'
            ], 404);
        }

        // Clean registered phone numbers
        $registeredGuardianMobile = preg_replace('/[^0-9]/', '', $student->guardian_mobile ?? '');
        $registeredStudentMobile = preg_replace('/[^0-9]/', '', $student->phone ?? '');

        $hasGuardianMobile = !empty($registeredGuardianMobile);
        $hasStudentMobile = !empty($registeredStudentMobile);

        $isMobileMatched = false;

        if ($hasGuardianMobile) {
            // Guardian mobile is set - match guardian mobile or student mobile
            $isMobileMatched = ($cleanMobile === $registeredGuardianMobile) || 
                               ($cleanMobile === $registeredStudentMobile) ||
                               (strlen($cleanMobile) >= 10 && (str_contains($registeredGuardianMobile, $cleanMobile) || str_contains($registeredStudentMobile, $cleanMobile)));
        } else {
            // Guardian mobile is NOT yet registered in database!
            // Allow login with ANY valid 10-digit mobile number AND auto-register it for future logins
            if (strlen($cleanMobile) >= 10) {
                $student->update(['guardian_mobile' => $cleanMobile]);
                $isMobileMatched = true;
            } elseif ($hasStudentMobile) {
                $isMobileMatched = ($cleanMobile === $registeredStudentMobile);
            } else {
                $isMobileMatched = true; // No mobile records exist at all, allow onboarding
            }
        }

        if (!$isMobileMatched) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'The mobile number provided does not match college records for this student. You can log in using your ward\'s mobile number, or contact the Class Tutor.'
            ], 401);
        }

        // Store Parent Session
        Session::put([
            'userRole' => 'Parent',
            'parentRegNo' => $student->reg_no,
            'studentName' => $student->name,
            'guardianMobile' => $cleanMobile
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'redirect' => '/parent/dashboard/' . $student->reg_no
        ]);
    }

    /**
     * Display the Parent Dashboard for a specific student.
     */
    public function showDashboard(Request $request, $regNo)
    {
        $regNo = strtoupper(trim($regNo));

        // Check if access is via token in query string or session
        $token = $request->query('token');
        $sessionRegNo = Session::get('parentRegNo');
        $sessionRole = Session::get('userRole');

        $student = Student::where('reg_no', $regNo)
            ->orWhere('adm_no', $regNo)
            ->firstOrFail();

        // Optional token security verification (MD5 hash of reg_no + guardian_mobile)
        $expectedToken = substr(md5($student->reg_no . ($student->guardian_mobile ?: $student->phone) . 'CarmelR26ParentSecret'), 0, 12);
        
        if ($token && $token === $expectedToken) {
            // Direct SMS link access - auto create parent session
            Session::put([
                'userRole' => 'Parent',
                'parentRegNo' => $student->reg_no,
                'studentName' => $student->name,
                'guardianMobile' => $student->guardian_mobile ?: $student->phone
            ]);
        } elseif ($sessionRole === 'Parent' && $sessionRegNo === $student->reg_no) {
            // Valid session
        } elseif (in_array($sessionRole, ['Tutor', 'HOD', 'Lecturer', 'Principal', 'Super_Admin'])) {
            // Staff previewing parent view
        } else {
            // Redirect to login if unauthorized
            return redirect('/parent')->with('error', 'Please verify student details with guardian mobile number to access Parent Portal.');
        }

        // 1. Classroom & Batch details
        $classroom = DB::table('class_management')
            ->where('classroom_id', $student->classroom_id)
            ->first();

        if (!$classroom) {
            $classroom = DB::table('r26_class_management')
                ->where('classroom_id', $student->classroom_id)
                ->first();
        }

        $tutor = null;
        if ($classroom && $classroom->tutor_mobile_no) {
            $tutor = DB::table('staff_profiles')
                ->where('mobile_no', $classroom->tutor_mobile_no)
                ->first();
        }

        // 2. Fetch all subjects for student's classroom
        $batchSubjects = BatchSubject::where('classroom_id', $student->classroom_id)
            ->orderBy('subject_code', 'asc')
            ->get();

        $subjectIds = $batchSubjects->pluck('id')->toArray();

        // 3. Today's Hour-Wise Attendance Grid
        $todayDate = now()->toDateString();
        $todayLogs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->where('date', $todayDate)
            ->orderBy('period', 'asc')
            ->get();

        $periodTimings = [
            1 => '9:00 AM – 10:00 AM',
            2 => '10:00 AM – 11:00 AM',
            3 => '11:10 AM – 12:10 PM',
            4 => '1:00 PM – 2:00 PM',
            5 => '2:00 PM – 3:00 PM',
            6 => '3:00 PM – 4:00 PM',
            7 => 'Special / Extra Class'
        ];

        $hourlyStatus = [];
        for ($p = 1; $p <= 6; $p++) {
            $hourlyStatus[$p] = [
                'period' => $p,
                'time_slot' => $periodTimings[$p],
                'status' => 'Not Marked',
                'subject_name' => 'Free Period',
                'subject_code' => '',
                'topic' => '',
                'badge_class' => 'bg-secondary text-light'
            ];
        }

        // Period 7 is reserved as a Special / Remedial / Extra Class Hour
        $hourlyStatus[7] = [
            'period' => 7,
            'time_slot' => $periodTimings[7],
            'status' => 'Not Scheduled',
            'subject_name' => 'Special Hour (Remedial / Extra Class)',
            'subject_code' => 'P7',
            'topic' => 'Special / Remedial Session',
            'badge_class' => 'bg-dark text-secondary border border-secondary'
        ];

        foreach ($todayLogs as $log) {
            $period = (int)$log->period;
            if ($period >= 1 && $period <= 7) {
                $subj = $batchSubjects->firstWhere('id', $log->batch_subject_id);
                $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                $aList = json_decode($log->absent_students ?? '[]', true) ?: [];

                $statusText = 'Absent';
                $badgeClass = 'bg-danger text-white';

                if (in_array($student->reg_no, $pList)) {
                    $statusText = 'Present';
                    $badgeClass = 'bg-success text-white';
                } elseif (in_array($student->reg_no, $aList)) {
                    $statusText = 'Absent';
                    $badgeClass = 'bg-danger text-white';
                }

                $hourlyStatus[$period] = [
                    'period' => $period,
                    'time_slot' => $periodTimings[$period],
                    'status' => $statusText,
                    'subject_name' => ($period === 7 ? '[Special 7th Hour] ' : '') . ($subj->subject_name ?? 'Class Session'),
                    'subject_code' => $subj->subject_code ?? '',
                    'topic' => $log->topics_covered ?? ($period === 7 ? 'Remedial / Extra Class' : 'Regular Session'),
                    'badge_class' => $badgeClass
                ];
            }
        }

        // 4. Calculate Overall Attendance Percentage (Standard 6-Hour Academic Schedule)
        // Period 7 (Special/Remedial) is evaluated for that class session only and excluded from standard daily totals
        $totalConductedClasses = 0;
        $totalAttendedClasses = 0;

        foreach ($subjectIds as $sId) {
            $logs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $sId)
                ->where('period', '<=', 6) // Standard 6-Hour Academic Day
                ->get(['present_students', 'absent_students']);

            foreach ($logs as $l) {
                $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                $aList = json_decode($l->absent_students ?? '[]', true) ?: [];

                if (in_array($student->reg_no, $pList) || in_array($student->reg_no, $aList)) {
                    $totalConductedClasses++;
                    if (in_array($student->reg_no, $pList)) {
                        $totalAttendedClasses++;
                    }
                }
            }
        }

        $overallAttendancePct = $totalConductedClasses > 0 
            ? round(($totalAttendedClasses / $totalConductedClasses) * 100, 1) 
            : 0.0;

        // 5. Today's Assignments & Pending Works
        $assignments = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('assignments')) {
            $assignments = DB::table('assignments')
                ->whereIn('batch_subject_id', $subjectIds)
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get();
        }

        // 6. Scheduled Tests & Evaluations
        $practicalTests = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('r26_drawing_practical_tests')) {
            $practicalTests = DB::table('r26_drawing_practical_tests')
                ->whereIn('batch_subject_id', $subjectIds)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    $item->test_name = 'Practical Test #' . ($item->test_no ?? $item->id);
                    $item->test_date = $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : now()->toDateString();
                    $item->max_marks = 40;
                    return $item;
                });
        } elseif (\Illuminate\Support\Facades\Schema::hasTable('practical_tests')) {
            $practicalTests = DB::table('practical_tests')
                ->whereIn('batch_subject_id', $subjectIds)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
        }

        // 7. Tutor Mentoring Comments & Staff Remarks
        $mentoringNotes = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_mentoring_diaries')) {
            $mentoringNotes = DB::table('student_mentoring_diaries')
                ->where('reg_no', $student->reg_no)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        } elseif (\Illuminate\Support\Facades\Schema::hasTable('student_mentoring_profiles')) {
            $mentoringNotes = DB::table('student_mentoring_profiles')
                ->where('reg_no', $student->reg_no)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }

        // 8. Ward Academic Status & Performance
        $academicStatus = !empty($student->academic_status) ? $student->academic_status : (!empty($student->status) ? $student->status : 'Regular (Active)');
        $statusNotes = !empty($student->status_notes) ? $student->status_notes : 'Student status is active and in good academic standing.';

        // Semester summaries & CGPA / SGPA
        $semesterSummaries = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_semester_summary')) {
            $semesterSummaries = DB::table('student_semester_summary')
                ->where('reg_no', $student->reg_no)
                ->orderBy('semester', 'desc')
                ->get();
        }

        $latestSummary = $semesterSummaries->first();
        $cgpa = $latestSummary->cgpa ?? null;
        $sgpa = $latestSummary->sgpa ?? null;

        // Activity Points
        $activityPoints = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('activity_point_claims')) {
            $activityPoints = DB::table('activity_point_claims')
                ->where('reg_no', $student->reg_no)
                ->where('status', 'Verified')
                ->sum('points_awarded');
        }
        if ($activityPoints == 0 && $latestSummary) {
            $activityPoints = $latestSummary->activity_points ?? 0;
        }

        // Academic Marks & Board Grades
        $academicMarks = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('academic_marks')) {
            $academicMarks = DB::table('academic_marks')
                ->where('reg_no', $student->reg_no)
                ->get()
                ->groupBy('subject_code');
        }

        $boardGrades = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_board_grades')) {
            $boardGrades = DB::table('student_board_grades')
                ->where('reg_no', $student->reg_no)
                ->get()
                ->keyBy('subject_code');
        }

        $subjectAcademicPerformance = $batchSubjects->map(function($subj) use ($academicMarks, $boardGrades) {
            $marks = $academicMarks->get($subj->subject_code, collect());
            
            $series1 = $marks->where('category', 'Written Test')->where('co_tag', 'CO1')->first()->marks_obtained ?? null;
            $series2 = $marks->where('category', 'Written Test')->where('co_tag', 'CO2')->first()->marks_obtained ?? null;
            $assignment1 = $marks->where('category', 'Assignment')->where('co_tag', 'CO1')->first()->marks_obtained ?? null;
            $assignment2 = $marks->where('category', 'Assignment')->where('co_tag', 'CO2')->first()->marks_obtained ?? null;
            
            $totalMarks = $marks->sum('marks_obtained');
            $bGrade = $boardGrades->get($subj->subject_code);
            
            return (object)[
                'subject_code' => $subj->subject_code,
                'subject_name' => $subj->subject_name,
                'subject_type' => $subj->subject_type ?? 'Theory',
                'credits' => $subj->credits ?? 3,
                'series1' => $series1,
                'series2' => $series2,
                'assignment1' => $assignment1,
                'assignment2' => $assignment2,
                'total_internal' => $totalMarks > 0 ? $totalMarks : null,
                'board_grade' => $bGrade ? $bGrade->grade : null
            ];
        });

        // 10. Student Leave Records / History
        $leaveRecords = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_leave_records')) {
            $leaveRecords = DB::table('student_leave_records')
                ->where('reg_no', $student->reg_no)
                ->orderBy('leave_date', 'desc')
                ->get();
        } elseif (class_exists('\App\Models\LeaveRecord')) {
            try {
                $leaveRecords = \App\Models\LeaveRecord::where('reg_no', $student->reg_no)
                    ->orderBy('leave_date', 'desc')
                    ->get();
            } catch (\Exception $e) {
                $leaveRecords = collect();
            }
        }

        // 11. Generate shareable SMS Link Token for parent
        $shareableToken = $expectedToken;
        $smsShareUrl = url('/parent/dashboard/' . $student->reg_no . '?token=' . $shareableToken);

        return view('parent_dashboard', compact(
            'student',
            'classroom',
            'tutor',
            'hourlyStatus',
            'totalConductedClasses',
            'totalAttendedClasses',
            'overallAttendancePct',
            'assignments',
            'practicalTests',
            'mentoringNotes',
            'smsShareUrl',
            'academicStatus',
            'statusNotes',
            'cgpa',
            'sgpa',
            'activityPoints',
            'subjectAcademicPerformance',
            'leaveRecords'
        ));
    }

    /**
     * Render Parent Dashboard with Rich Mock Demo Data for instant testing.
     */
    public function showDemoDashboard()
    {
        $student = (object)[
            'name' => 'Rahul K. Nair',
            'reg_no' => '24ME1045',
            'branch' => 'ME',
            'semester' => '4',
            'classroom_id' => 'ME_2024_2027',
            'photo_url' => null,
            'guardian_name' => 'K. Ramanathan Nair',
            'guardian_mobile' => '9876543210',
            'phone' => '9876543210'
        ];

        $classroom = (object)[
            'classroom_id' => 'ME_2024_2027',
            'branch' => 'ME',
            'semester' => '4'
        ];

        $tutor = (object)[
            'name' => 'Prof. Rajesh V.',
            'mobile_no' => '9447123456'
        ];

        $hourlyStatus = [
            1 => [
                'period' => 1,
                'status' => 'Present',
                'subject_name' => 'Machine Drawing',
                'subject_code' => 'ME204',
                'topic' => 'Orthographic Projections of Cylindrical Solids',
                'badge_class' => 'bg-success text-white'
            ],
            2 => [
                'period' => 2,
                'status' => 'Present',
                'subject_name' => 'Machine Drawing',
                'subject_code' => 'ME204',
                'topic' => 'Sectional Views & Standard Hatching Rules',
                'badge_class' => 'bg-success text-white'
            ],
            3 => [
                'period' => 3,
                'status' => 'Absent',
                'subject_name' => 'Thermal Engineering',
                'subject_code' => 'ME206',
                'topic' => 'Rankine Cycle Efficiency & Steam Tables',
                'badge_class' => 'bg-danger text-white'
            ],
            4 => [
                'period' => 4,
                'status' => 'Present',
                'subject_name' => 'Fluid Mechanics',
                'subject_code' => 'ME208',
                'topic' => 'Bernoulli Equation & Venturimeter Applications',
                'badge_class' => 'bg-success text-white'
            ],
            5 => [
                'period' => 5,
                'status' => 'Present',
                'subject_name' => 'Manufacturing Tech',
                'subject_code' => 'ME210',
                'topic' => 'CNC Lathe G-Code & M-Code Programming',
                'badge_class' => 'bg-success text-white'
            ],
            6 => [
                'period' => 6,
                'status' => 'Present',
                'subject_name' => 'General Engg Lab',
                'subject_code' => 'ME212',
                'topic' => 'UTM Tensile Stress-Strain Plotting',
                'badge_class' => 'bg-success text-white'
            ],
            7 => [
                'period' => 7,
                'status' => 'Present',
                'subject_name' => '[Special 7th Hour] Remedial Class',
                'subject_code' => 'P7',
                'topic' => 'Remedial Problem Solving: Rankine Cycle',
                'badge_class' => 'bg-info text-dark'
            ],
        ];

        $totalConductedClasses = 120;
        $totalAttendedClasses = 101;
        $overallAttendancePct = 84.2;

        $assignments = collect([
            (object)[
                'subject_code' => 'ME204',
                'title' => 'Machine Drawing Sheet #4: Assembly of Flanged Coupling',
                'due_date' => '2026-07-31'
            ],
            (object)[
                'subject_code' => 'ME206',
                'title' => 'Thermal Engg Assignment 2: Steam Boiler Calculations',
                'due_date' => '2026-08-02'
            ]
        ]);

        $practicalTests = collect([
            (object)[
                'test_name' => 'CAD Lab Series Test 1',
                'test_date' => '2026-08-01',
                'max_marks' => 40
            ],
            (object)[
                'test_name' => 'Machine Shop Slot Evaluation 2',
                'test_date' => '2026-08-05',
                'max_marks' => 40
            ]
        ]);

        $mentoringNotes = collect([
            (object)[
                'faculty_name' => 'Prof. Rajesh V. (Class Advisor)',
                'created_at' => '2026-07-28 10:30:00',
                'comments' => 'Contacted guardian regarding Period 3 absence today. Student submitted medical slip for morning appointment.'
            ],
            (object)[
                'faculty_name' => 'Prof. Rajesh V. (Class Advisor)',
                'created_at' => '2026-07-20 14:15:00',
                'comments' => 'Mid-semester performance review: Good progress in CAD drawing practicals and workshops.'
            ]
        ]);

        $smsShareUrl = url('/parent/demo');

        $academicStatus = 'Regular (Active)';
        $statusNotes = 'Good academic standing. Recommended for SBTE diploma honors.';
        $cgpa = 8.42;
        $sgpa = 8.65;
        $activityPoints = 45;

        $subjectAcademicPerformance = collect([
            (object)[
                'subject_code' => 'ME204',
                'subject_name' => 'Machine Drawing',
                'subject_type' => 'Drawing',
                'credits' => 4,
                'series1' => 18,
                'series2' => 17,
                'assignment1' => 9,
                'assignment2' => 10,
                'total_internal' => 54,
                'board_grade' => 'A+'
            ],
            (object)[
                'subject_code' => 'ME206',
                'subject_name' => 'Thermal Engineering',
                'subject_type' => 'Theory',
                'credits' => 4,
                'series1' => 15,
                'series2' => 16,
                'assignment1' => 8,
                'assignment2' => 9,
                'total_internal' => 48,
                'board_grade' => 'A'
            ],
            (object)[
                'subject_code' => 'ME208',
                'subject_name' => 'Fluid Mechanics',
                'subject_type' => 'Theory',
                'credits' => 3,
                'series1' => 16,
                'series2' => 18,
                'assignment1' => 10,
                'assignment2' => 9,
                'total_internal' => 53,
                'board_grade' => 'S'
            ],
            (object)[
                'subject_code' => 'ME210',
                'subject_name' => 'Manufacturing Tech',
                'subject_type' => 'Theory',
                'credits' => 3,
                'series1' => 14,
                'series2' => 15,
                'assignment1' => 8,
                'assignment2' => 8,
                'total_internal' => 45,
                'board_grade' => 'B+'
            ],
            (object)[
                'subject_code' => 'ME212',
                'subject_name' => 'General Engg Lab',
                'subject_type' => 'Practical',
                'credits' => 2,
                'series1' => 19,
                'series2' => 20,
                'assignment1' => 10,
                'assignment2' => 10,
                'total_internal' => 59,
                'board_grade' => 'S'
            ]
        ]);

        $leaveRecords = collect([
            (object)[
                'leave_date' => '2026-07-28',
                'no_of_days' => 1,
                'reason' => 'Medical appointment for dental treatment',
                'parent_informed' => 1,
                'status' => 'Approved'
            ],
            (object)[
                'leave_date' => '2026-07-14',
                'no_of_days' => 0.5,
                'reason' => 'Family emergency during afternoon hours',
                'parent_informed' => 1,
                'status' => 'Approved'
            ]
        ]);

        return view('parent_dashboard', compact(
            'student',
            'classroom',
            'tutor',
            'hourlyStatus',
            'totalConductedClasses',
            'totalAttendedClasses',
            'overallAttendancePct',
            'assignments',
            'practicalTests',
            'mentoringNotes',
            'smsShareUrl',
            'academicStatus',
            'statusNotes',
            'cgpa',
            'sgpa',
            'activityPoints',
            'subjectAcademicPerformance',
            'leaveRecords'
        ));
    }
}

