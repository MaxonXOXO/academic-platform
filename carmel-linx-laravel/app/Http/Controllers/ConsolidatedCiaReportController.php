<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\ConsolidatedCiaApproval;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\StudentSemesterMarks;
use App\Services\AttainmentService;

class ConsolidatedCiaReportController extends Controller
{
    /**
     * Resolve classroom based on request and logged-in user context.
     */
    public static function resolveClassroom(Request $request)
    {
        $requestedClassId = $request->input('classroom_id') ?? $request->input('classroom');

        if ($requestedClassId) {
            $classroom = DB::table('class_management')->where('classroom_id', $requestedClassId)->first();
            if (!$classroom && Schema::hasTable('r26_class_management')) {
                $classroom = DB::table('r26_class_management')->where('classroom_id', $requestedClassId)->first();
            }
            if ($classroom) return $classroom;
        }

        $userId = Session::get('userId');
        $role = Session::get('userRole');
        $cleanMobile = $userId ? preg_replace('/[^0-9]/', '', $userId) : null;

        // If Tutor or Advisor
        if ($role === 'Tutor' || $userId) {
            $tutorClass = DB::table('class_management')->where(function($q) use ($userId, $cleanMobile) {
                $q->where('tutor_mobile_no', $userId)->orWhere('mentor_mobile_no', $userId);
                if ($cleanMobile) {
                    $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
                }
            })->first();

            if (!$tutorClass && Schema::hasTable('r26_class_management')) {
                $tutorClass = DB::table('r26_class_management')->where(function($q) use ($userId, $cleanMobile) {
                    $q->where('tutor_mobile_no', $userId)->orWhere('mentor_mobile_no', $userId);
                    if ($cleanMobile) {
                        $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
                    }
                })->first();
            }

            if ($tutorClass) return $tutorClass;
        }

        // If HOD, find first classroom of branch
        $userBranch = Session::get('userBranch');
        if ($userBranch) {
            $hodClass = DB::table('class_management')->where('branch', $userBranch)->orderBy('current_semester', 'desc')->first();
            if ($hodClass) return $hodClass;
            if (Schema::hasTable('r26_class_management')) {
                $hodClass2 = DB::table('r26_class_management')->where('branch', $userBranch)->first();
                if ($hodClass2) return $hodClass2;
            }
        }

        return DB::table('class_management')->first();
    }

    /**
     * Map branch code to full name.
     */
    public static function getBranchFullName(?string $code): string
    {
        $map = [
            'EL'  => 'Electronics & Communication Engineering',
            'AU'  => 'Automobile Engineering',
            'CE'  => 'Civil Engineering',
            'ME'  => 'Mechanical Engineering',
            'EE'  => 'Electrical & Electronics Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CS'  => 'Computer Engineering',
            'CT'  => 'Computer Engineering',
            'CH'  => 'Chemical Engineering',
        ];
        return $map[strtoupper($code ?? '')] ?? ($code ? "$code Engineering" : 'Polytechnic Engineering');
    }

    /**
     * API: Get complete Consolidated CIA report data for a classroom & semester.
     */
    public function getConsolidatedCiaData(Request $request)
    {
        $classroom = self::resolveClassroom($request);
        if (!$classroom) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No classroom found or assigned.'
            ], 404);
        }

        $classroomId = $classroom->classroom_id;
        $semester = (int)($request->input('semester') ?: ($classroom->current_semester ?? 1));
        $branchCode = $classroom->branch ?? '';
        $branchName = self::getBranchFullName($branchCode);

        // Academic batch string (e.g. 2025 - 2028)
        $batchYear = $classroom->batch_year ?? null;
        if ($batchYear && is_numeric($batchYear)) {
            $batchString = $batchYear . ' - ' . ($batchYear + 3);
        } else {
            $parts = explode('_', $classroomId);
            if (count($parts) >= 3 && is_numeric($parts[1]) && is_numeric($parts[2])) {
                $batchString = $parts[1] . ' - ' . $parts[2];
            } else {
                $batchString = $classroomId;
            }
        }

        // Detect Scheme / Regulation
        $isR26 = str_contains($classroomId, '2026') || str_contains($classroom->scheme_code ?? '', '2026');
        $schemeName = $isR26 ? 'Revision 2026 (SBTE Kerala)' : 'Revision 2021 (SBTE Kerala)';

        // Tutor Profile
        $tutorProfile = null;
        if (!empty($classroom->tutor_mobile_no)) {
            $tutorProfile = DB::table('staff_profiles')->where('mobile_no', $classroom->tutor_mobile_no)->first();
        }

        // HOD Profile for this branch
        $hodProfile = DB::table('staff_profiles')
            ->where('branch', $branchCode)
            ->where(function($q) {
                $q->where('designation', 'LIKE', '%Head of Department%')
                  ->orWhere('designation', 'LIKE', '%HOD%');
            })->first();

        // 1. Fetch Subjects for this Classroom & Semester
        $rawSubjects = BatchSubject::where('classroom_id', $classroomId)
            ->where('semester', $semester)
            ->get();

        $typePriority = [
            'Theory' => 1,
            'Theory Courses' => 1,
            'Theory / Lecture' => 1,
            'Practical / Lab' => 2,
            'Laboratory/Workshop Courses' => 2,
            'Practical' => 2,
            'Lab' => 2,
            'Practicum Courses' => 2,
            'Practicum Courses under Basic Science & Humanities category' => 2,
            'Drawing' => 3,
            'Drawing / CAD' => 3,
            'Seminar' => 4,
            'Project' => 5,
            'Major Project' => 5,
        ];

        $subjects = $rawSubjects->sortBy(function ($s) use ($typePriority) {
            $p = $typePriority[$s->subject_type] ?? 9;
            return sprintf('%02d_%s', $p, $s->subject_code);
        })->values();

        // 2. Fetch Students enrolled in this classroom
        $students = Student::getClassroomStudentsQuery($classroomId)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no', 'admission_type', 'date_of_joining']);

        $studentRegNos = $students->pluck('reg_no')->toArray();
        $studentSbteNos = $students->pluck('sbte_reg_no')->filter()->toArray();
        $allLookupRegs = array_unique(array_merge($studentRegNos, $studentSbteNos));

        // 3. Gather Pre-existing Semester Marks if any
        $semesterMarks = collect();
        if (Schema::hasTable('student_semester_marks') && !empty($studentRegNos)) {
            $semesterMarks = DB::table('student_semester_marks')
                ->whereIn('reg_no', $allLookupRegs)
                ->where('semester', $semester)
                ->get();
        }

        // 4. Gather Official Attendance from student_attendance (TEAMS)
        $officialAttendance = collect();
        if (Schema::hasTable('student_attendance') && !empty($allLookupRegs)) {
            $officialAttendance = DB::table('student_attendance')
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->groupBy('subject_code');
        }

        // Authoritative TEAMS Subject Attendance (subject_official_attendances)
        $teamsOfficialAttendances = collect();
        if (Schema::hasTable('subject_official_attendances') && $subjects->isNotEmpty()) {
            $teamsOfficialAttendances = DB::table('subject_official_attendances')
                ->whereIn('batch_subject_id', $subjects->pluck('id'))
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->groupBy('batch_subject_id')
                ->map(fn($group) => $group->keyBy('reg_no'));
        }

        // Authoritative TEAMS Class Attendance (tutor_class_attendances)
        $tutorClassAttendances = collect();
        if (Schema::hasTable('tutor_class_attendances')) {
            $tutorClassAttendances = DB::table('tutor_class_attendances')
                ->where('classroom_id', $classroomId)
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->keyBy('reg_no');
        }

        // Class logs attendance
        $classLogsGrouped = collect();
        if (Schema::hasTable('class_logs_attendance') && $subjects->isNotEmpty()) {
            $classLogsGrouped = DB::table('class_logs_attendance')
                ->whereIn('batch_subject_id', $subjects->pluck('id'))
                ->get()
                ->groupBy('batch_subject_id');
        }

        // 5. Gather Subject-specific Evaluation Data
        $subjectIds = $subjects->pluck('id')->toArray();
        $subjectCodes = $subjects->pluck('subject_code')->filter()->unique()->toArray();

        // A. Academic marks (Theory: Assignments & Summative)
        $academicMarks = collect();
        if (Schema::hasTable('academic_marks') && !empty($allLookupRegs)) {
            $academicMarks = DB::table('academic_marks')
                ->whereIn('reg_no', $allLookupRegs)
                ->where(function($q) use ($subjectCodes, $subjectIds) {
                    $q->whereIn('subject_code', $subjectCodes);
                    if (!empty($subjectIds) && Schema::hasColumn('academic_marks', 'batch_subject_id')) {
                        $q->orWhereIn('batch_subject_id', $subjectIds);
                    }
                })->get();
        }

        // B. Practical evaluations & test marks
        $practicalEvals = collect();
        $practicalTests = collect();
        $practicalExpMarks = collect();
        if (Schema::hasTable('practical_evaluations') && !empty($subjectIds)) {
            $practicalEvals = DB::table('practical_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->groupBy('batch_subject_id');
        }
        if (Schema::hasTable('practical_test_marks') && Schema::hasTable('practical_tests') && !empty($subjectIds)) {
            $practicalTests = DB::table('practical_test_marks')
                ->join('practical_tests', 'practical_test_marks.practical_test_id', '=', 'practical_tests.id')
                ->whereIn('practical_tests.batch_subject_id', $subjectIds)
                ->whereIn('practical_test_marks.reg_no', $allLookupRegs)
                ->select('practical_test_marks.*', 'practical_tests.batch_subject_id')
                ->get()
                ->groupBy('batch_subject_id');
        }

        // C. Drawing sheet evaluations & series tests
        $drawingSheets = collect();
        $drawingTests = collect();
        if (Schema::hasTable('r21_drawing_sheet_evaluations') && !empty($subjectIds)) {
            $drawingSheets = DB::table('r21_drawing_sheet_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->groupBy('batch_subject_id');
        }
        if (Schema::hasTable('r21_drawing_series_tests') && !empty($subjectIds)) {
            $drawingTests = DB::table('r21_drawing_series_tests')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // D. Seminar evaluations
        $seminarEvals = collect();
        if (Schema::hasTable('seminar_evaluations') && !empty($subjectIds)) {
            $seminarEvals = DB::table('seminar_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // E. Major Project evaluations
        $projectEvals = collect();
        if (Schema::hasTable('r21_major_project_evaluations') && !empty($subjectIds)) {
            $projectEvals = DB::table('r21_major_project_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $allLookupRegs)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // F. Tutor-Uploaded TEAMS Duty Hours & Special Attendance Credits (Exam Eligibility)
        $specialAttendanceRecords = collect();
        if (Schema::hasTable('tutor_special_attendances')) {
            $specialAttendanceRecords = DB::table('tutor_special_attendances')
                ->where('classroom_id', $classroomId)
                ->get()
                ->groupBy('reg_no');
        }
        $schemeCode = TutorSpecialAttendanceController::detectScheme($classroom);

        // G. Assigned Lab Batches for Practical Subjects
        $assignedLabBatches = collect();
        if (Schema::hasTable('r26_student_lab_batches') && !empty($subjectIds)) {
            $assignedLabBatches = DB::table('r26_student_lab_batches')
                ->whereIn('batch_subject_id', $subjectIds)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // 6. Process Subjects Metadata & Assigned Faculty
        $subjectsMeta = [];
        $allAssignedStaff = [];

        foreach ($subjects as $s) {
            $maxCia = self::determineMaxCia($s);
            $facList = $s->getAssignedFacultyList();
            $facNames = $s->getAssignedFacultyNames();

            foreach ($facList as $facName) {
                if (empty($facName)) continue;
                $staffKey = trim($facName);
                if (!isset($allAssignedStaff[$staffKey])) {
                    $staffProfile = DB::table('staff_profiles')->where('name', $staffKey)->first();
                    $allAssignedStaff[$staffKey] = [
                        'name' => $staffKey,
                        'designation' => $staffProfile->designation ?? 'Faculty Member',
                        'department' => $staffProfile && $staffProfile->branch ? self::getBranchFullName($staffProfile->branch) : $branchName,
                        'subjects' => []
                    ];
                }
                $allAssignedStaff[$staffKey]['subjects'][] = $s->subject_code . ' (' . $s->subject_name . ')';
            }

            $subjectsMeta[] = [
                'id' => $s->id,
                'subject_code' => $s->subject_code,
                'subject_name' => $s->subject_name,
                'subject_type' => $s->subject_type ?? 'Theory',
                'max_cia' => $maxCia,
                'faculty_names' => $facNames,
                'faculty_list' => $facList
            ];
        }

        // 7. Calculate Live CIA Marks and Overall Attendance for Each Student
        $studentsData = [];
        $totalPassedStudents = 0;
        $totalFailedStudents = 0;
        $totalGeneratedCiaEntries = 0;

        foreach ($students as $student) {
            $regNo = $student->reg_no;
            $sbteRegNo = $student->sbte_reg_no;
            $studentSubjectMarks = [];
            $totalConductedAll = 0;
            $totalAttendedAll = 0;
            $hasAnyFail = false;
            $allSubjectsHaveMarks = true;

            foreach ($subjects as $s) {
                $sId = $s->id;
                $sCode = $s->subject_code;
                $sType = $s->subject_type ?? 'Theory';
                $maxCia = self::determineMaxCia($s);
                $isPractical = stripos($sType, 'pract') !== false || stripos($sType, 'lab') !== false;

                // Resolve student's lab batch (1 or 2)
                $labBatch = null;
                $sBatches = $assignedLabBatches->get($sId, collect())->keyBy('reg_no');
                if ($sBatches->has($regNo)) {
                    $labBatch = (string)$sBatches->get($regNo)->lab_batch;
                } elseif (!empty($s->lab_batch_cutoff) && $student->roll_no !== null) {
                    $labBatch = ((int)$student->roll_no <= (int)$s->lab_batch_cutoff) ? '1' : '2';
                } else {
                    $labBatch = '1';
                }

                // Official TEAMS student_attendance for this student (used for Subject CIA mark)
                $subjLogs = $classLogsGrouped->get($sId, collect());
                $stAtt = $officialAttendance->get($sCode, collect());
                $stAttForStud = $stAtt->filter(function($att) use ($regNo, $sbteRegNo) {
                    return $att->reg_no === $regNo || ($sbteRegNo && $att->reg_no === $sbteRegNo);
                });

                // Pre-admission date filtering for LET / late-joining students
                $doj = $student->date_of_joining ?: (($student->admission_type === 'LET') ? '2026-07-15' : null);
                if (!empty($doj)) {
                    $stAttForStud = $stAttForStud->filter(fn($att) => $att->date >= $doj);
                }

                $conducted = 0;
                $attended = 0;

                if ($subjLogs->isNotEmpty()) {
                    $slots1 = [];
                    $slots2 = [];
                    $slotsWhole = [];
                    $studentSlots = [];

                    foreach ($subjLogs as $l) {
                        // Skip pre-admission dates
                        if (!empty($doj) && $l->date < $doj) {
                            continue;
                        }

                        $slotKey = $l->date . '_P' . $l->period;
                        $sb = (string)($l->sub_batch ?? 'Whole');
                        if ($sb === '1' || $sb === 1) {
                            $slots1[$slotKey] = true;
                        } elseif ($sb === '2' || $sb === 2) {
                            $slots2[$slotKey] = true;
                        } else {
                            $slotsWhole[$slotKey] = true;
                        }

                        $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                        if (in_array($regNo, $pList) || ($sbteRegNo && in_array($sbteRegNo, $pList))) {
                            $studentSlots[$slotKey] = true;
                        }
                    }

                    if ($isPractical) {
                        $b1Count = count($slots1) + count($slotsWhole);
                        $b2Count = count($slots2) + count($slotsWhole);
                        $conducted = ($labBatch === '2') ? ($b2Count ?: $subjLogs->count()) : ($b1Count ?: $subjLogs->count());
                    } else {
                        $conducted = (count($slots1) + count($slots2) + count($slotsWhole)) ?: $subjLogs->count();
                    }
                    $attended = min($conducted, count($studentSlots));
                } elseif ($stAttForStud->isNotEmpty()) {
                    $conducted = $stAttForStud->count();
                    $attended = $stAttForStud->whereIn('status', ['Present', 'Late'])->count();
                }

                // Authoritative attendance percentage for subject CIA:
                // Teams uploaded attendance in subject_official_attendances is official for subject attendance % and CIA mark
                $officialRecord = $teamsOfficialAttendances->get($sId, collect())->get($regNo)
                    ?: ($sbteRegNo ? $teamsOfficialAttendances->get($sId, collect())->get($sbteRegNo) : null);

                if ($officialRecord) {
                    $subjAttPct = (float)$officialRecord->final_percentage;
                    $conducted = (int)$officialRecord->total_hours;
                    $attended = (int)$officialRecord->attended_hours;
                } elseif ($stAttForStud->isNotEmpty()) {
                    if ($isPractical && !empty($labBatch)) {
                        $stAttForStudFiltered = $stAttForStud->filter(function($att) use ($labBatch) {
                            $sb = (string)($att->sub_batch ?? 'Whole');
                            if ($sb === $labBatch || $sb === 'Whole') return true;
                            return in_array($att->status, ['Present', 'Late']);
                        });
                        if ($stAttForStudFiltered->isNotEmpty()) {
                            $stAttForStud = $stAttForStudFiltered;
                        }
                    }
                    $offTot = $stAttForStud->count();
                    $offPres = $stAttForStud->whereIn('status', ['Present', 'Late'])->count();
                    $subjAttPct = ($offTot > 0) ? round(($offPres / $offTot) * 100, 1) : 0.0;
                    if ($conducted == 0) {
                        $conducted = $offTot;
                        $attended = $offPres;
                    }
                } else {
                    $subjAttPct = ($conducted > 0) ? round(($attended / $conducted) * 100, 1) : 0.0;
                }

                $totalConductedAll += $conducted;
                $totalAttendedAll += $attended;

                // Compute Live CIA Mark based on subject type
                $ciaResult = self::calculateSubjectCia(
                    $s,
                    $regNo,
                    $sbteRegNo,
                    $subjAttPct,
                    $maxCia,
                    $academicMarks,
                    $practicalEvals->get($sId, collect()),
                    $practicalTests->get($sId, collect()),
                    $drawingSheets->get($sId, collect()),
                    $drawingTests->get($sId, collect()),
                    $seminarEvals->get($sId, collect()),
                    $projectEvals->get($sId, collect()),
                    $semesterMarks
                );

                $markVal = $ciaResult['mark'];
                $isGenerated = $ciaResult['is_generated'];

                if ($isGenerated && $markVal !== null) {
                    $totalGeneratedCiaEntries++;
                    // Min pass mark is 40% of max CIA (e.g. 20 for 50M, 30 for 75M)
                    $passThreshold = round($maxCia * 0.40);
                    $isPass = ($markVal >= $passThreshold);
                    if (!$isPass) $hasAnyFail = true;

                    $studentSubjectMarks[$sId] = [
                        'subject_id' => $sId,
                        'subject_code' => $sCode,
                        'mark' => $markVal,
                        'max_cia' => $maxCia,
                        'is_pass' => $isPass,
                        'is_generated' => true,
                        'attendance_pct' => $subjAttPct
                    ];

                    // Proactively sync live calculated mark to student_semester_marks table
                    self::syncToSemesterMarksTable($regNo, $sCode, $s->subject_name, $semester, $markVal, $subjAttPct);
                } else {
                    $allSubjectsHaveMarks = false;
                    $studentSubjectMarks[$sId] = [
                        'subject_id' => $sId,
                        'subject_code' => $sCode,
                        'mark' => null,
                        'max_cia' => $maxCia,
                        'is_pass' => null,
                        'is_generated' => false,
                        'attendance_pct' => $subjAttPct
                    ];
                }
            }

            // Extract any sanctioned duty leave / special attendance credits
            $stSpecial = $specialAttendanceRecords->get($regNo, collect());
            if ($stSpecial->isEmpty() && $sbteRegNo) {
                $stSpecial = $specialAttendanceRecords->get($sbteRegNo, collect());
            }
            $specialDutyHours = (float)$stSpecial->sum('hours');
            $relaxations = $stSpecial->pluck('category')->filter(fn($c) => in_array($c, ['Menstrual Leave', 'PWD']))->unique()->toArray();

            // Check Authoritative TEAMS Class Attendance (Tutor Uploaded) first!
            $tutorClassAtt = $tutorClassAttendances->get($regNo) ?: ($sbteRegNo ? $tutorClassAttendances->get($sbteRegNo) : null);

            if ($tutorClassAtt) {
                $finalEligibilityAttPct = (float)$tutorClassAtt->final_percentage;
                $rawAttPct = (float)$tutorClassAtt->attendance_percentage;
                $eligibility = TutorSpecialAttendanceController::evaluateEligibility($finalEligibilityAttPct, $schemeCode, $relaxations);
                $attStatus = $tutorClassAtt->eligibility_status ?: $eligibility['status'];
                $displayConducted = ((int)$tutorClassAtt->total_hours > 0) ? (int)$tutorClassAtt->total_hours : $totalConductedAll;
                $displayAttended = ((int)$tutorClassAtt->total_hours > 0) ? (int)$tutorClassAtt->attended_hours : $totalAttendedAll;
            } else {
                // Raw unadjusted attendance across subjects
                $rawAttPct = ($totalConductedAll > 0)
                    ? round(($totalAttendedAll / $totalConductedAll) * 100, 1)
                    : 0.0;

                // Incorporate duty hours and special attendance credits
                $effectiveAttendedAll = min($totalConductedAll, $totalAttendedAll + $specialDutyHours);
                $finalEligibilityAttPct = ($totalConductedAll > 0)
                    ? round(($effectiveAttendedAll / $totalConductedAll) * 100, 1)
                    : 0.0;

                // SBTE Exam Eligibility Evaluation (Clause 10 for R21 / Rule 7 for R26)
                $eligibility = TutorSpecialAttendanceController::evaluateEligibility($finalEligibilityAttPct, $schemeCode, $relaxations);
                $attStatus = $eligibility['status'];
                $displayConducted = $totalConductedAll;
                $displayAttended = $totalAttendedAll;
            }

            // Overall Semester CIA Status
            $overallResult = '-';
            if ($allSubjectsHaveMarks && count($subjects) > 0) {
                if ($hasAnyFail) {
                    $overallResult = 'FAILED';
                    $totalFailedStudents++;
                } else {
                    $overallResult = 'PASSED';
                    $totalPassedStudents++;
                }
            }

            $studentsData[] = [
                'roll_no' => $student->roll_no,
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?: $student->reg_no,
                'name' => $student->name,
                'admission_type' => $student->admission_type,
                'total_conducted' => $displayConducted,
                'total_attended' => $displayAttended,
                'subject_marks' => $studentSubjectMarks,
                'overall_attendance' => $finalEligibilityAttPct,
                'raw_attendance' => $rawAttPct,
                'special_duty_hours' => $specialDutyHours,
                'attendance_status' => $attStatus,
                'attendance_badge' => $eligibility['badge'] ?? '',
                'attendance_rule' => $eligibility['rule'] ?? '',
                'attendance_decision' => $eligibility['decision'] ?? '',
                'overall_result' => $overallResult
            ];
        }

        // 8. Fetch HOD Approval & Lock Status
        $approvalRec = ConsolidatedCiaApproval::where('classroom_id', $classroomId)
            ->where('semester', $semester)
            ->first();

        $approval = [
            'is_locked' => (bool)($approvalRec && $approvalRec->is_locked),
            'approved_by_hod' => (bool)($approvalRec && $approvalRec->approved_by_hod),
            'hod_name' => $approvalRec->hod_name ?? ($hodProfile->name ?? 'Head of Department'),
            'approved_at' => $approvalRec && $approvalRec->approved_at ? $approvalRec->approved_at->format('d-m-Y h:i A') : null,
            'submitted_by_tutor' => (bool)($approvalRec && $approvalRec->submitted_by_tutor),
            'tutor_name' => $approvalRec->tutor_name ?? ($tutorProfile->name ?? 'Class Tutor'),
            'submitted_at' => $approvalRec && $approvalRec->submitted_at ? $approvalRec->submitted_at->format('d-m-Y h:i A') : null,
            'remarks' => $approvalRec->remarks ?? null,
        ];

        // 9. Available Semesters for this batch/classroom (e.g. S1 to S6)
        $availableSemesters = BatchSubject::where('classroom_id', $classroomId)
            ->pluck('semester')
            ->unique()
            ->sort()
            ->values()
            ->toArray();
        if (empty($availableSemesters)) {
            $availableSemesters = [1, 2, 3, 4, 5, 6];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'classroom' => [
                'id' => $classroomId,
                'batch' => $batchString,
                'branch_code' => $branchCode,
                'branch_name' => $branchName,
                'semester' => $semester,
                'current_semester' => $classroom->current_semester ?? 1,
                'scheme_name' => $schemeName,
                'is_r26' => $isR26,
                'tutor_name' => $tutorProfile->name ?? 'Class Tutor',
                'hod_name' => $hodProfile->name ?? 'Head of Department',
                'report_date' => date('d-m-Y')
            ],
            'available_semesters' => $availableSemesters,
            'subjects' => $subjectsMeta,
            'students' => $studentsData,
            'staff_list' => array_values($allAssignedStaff),
            'approval' => $approval,
            'stats' => [
                'total_students' => count($studentsData),
                'total_subjects' => count($subjectsMeta),
                'passed_students' => $totalPassedStudents,
                'failed_students' => $totalFailedStudents,
                'entries_generated' => $totalGeneratedCiaEntries
            ]
        ]);
    }

    /**
     * Determine maximum CIA mark for a subject based on type and syllabus.
     */
    private static function determineMaxCia(BatchSubject $subject): int
    {
        $type = strtolower($subject->subject_type ?? '');
        $name = strtolower($subject->subject_name ?? '');

        if (str_contains($type, 'practical') || str_contains($type, 'lab') || str_contains($name, 'lab') || str_contains($name, 'workshop')) {
            return 75;
        }
        if (str_contains($type, 'drawing') || str_contains($name, 'drawing')) {
            return 75;
        }
        if (str_contains($type, 'seminar') || str_contains($name, 'seminar')) {
            return 75;
        }
        if (str_contains($type, 'project') || str_contains($name, 'project')) {
            return 75;
        }

        // Theory default is 50 in R2021 (or check syllabus)
        return 50;
    }

    /**
     * Compute live CIA mark for a given student & subject using real evaluation tables.
     */
    private static function calculateSubjectCia(
        $subject,
        $regNo,
        $sbteRegNo,
        $attPct,
        $maxCia,
        $academicMarks,
        $pracEvals,
        $pracTests,
        $drawSheets,
        $drawTests,
        $seminarEvals,
        $projectEvals,
        $semesterMarks
    ): array {
        $type = strtolower($subject->subject_type ?? '');
        $name = strtolower($subject->subject_name ?? '');
        $sCode = $subject->subject_code;
        $sId = $subject->id;

        // 1. Practical / Lab Subjects
        if (str_contains($type, 'practical') || str_contains($type, 'lab') || str_contains($name, 'lab')) {
            $eval = $pracEvals->where('reg_no', $regNo)->first()
                ?: ($sbteRegNo ? $pracEvals->where('reg_no', $sbteRegNo)->first() : null);

            $hasDirect = ($eval && $eval->lab_work_marks !== null && $eval->lab_work_marks !== '');
            $micro = ($eval && $eval->micro_project !== null) ? (float)$eval->micro_project : 0.0;

            // Practical series tests (max 15)
            $stTests = $pracTests->filter(function($t) use ($regNo, $sbteRegNo) {
                return $t->reg_no === $regNo || ($sbteRegNo && $t->reg_no === $sbteRegNo);
            });
            $tScores = [];
            foreach ($stTests as $t) {
                if (is_numeric($t->marks_obtained)) {
                    $val = (float)$t->marks_obtained;
                    if ($val > 15.0) $val = round($val / 2, 2);
                    $tScores[] = $val;
                }
            }
            $testAvg = count($tScores) > 0 ? (array_sum($tScores) / count($tScores)) : 0.0;

            // Attendance marks (max 15)
            $attMark = AttainmentService::calculateR21AttendanceMark($attPct, 15.0);

            if ($hasDirect) {
                $labWork = (float)$eval->lab_work_marks;
                $tot = (int)round($labWork + $micro + $testAvg + $attMark);
                return ['mark' => min(75, max(0, $tot)), 'is_generated' => true];
            }

            // Check if fallback to student_semester_marks exists
            $preMark = $semesterMarks->filter(function($m) use ($regNo, $sbteRegNo, $sCode) {
                return ($m->reg_no === $regNo || ($sbteRegNo && $m->reg_no === $sbteRegNo)) && $m->subject_code == $sCode;
            })->first();
            if ($preMark && is_numeric($preMark->internal_marks)) {
                return ['mark' => (int)round($preMark->internal_marks), 'is_generated' => true];
            }

            if ($eval || count($tScores) > 0) {
                $tot = (int)round($micro + $testAvg + $attMark);
                return ['mark' => min(75, max(0, $tot)), 'is_generated' => true];
            }

            return ['mark' => null, 'is_generated' => false];
        }

        // 2. Drawing Subjects
        if (str_contains($type, 'drawing') || str_contains($name, 'drawing')) {
            $stSheets = $drawSheets->filter(function($s) use ($regNo, $sbteRegNo) {
                return $s->reg_no === $regNo || ($sbteRegNo && $s->reg_no === $sbteRegNo);
            });
            $validSheets = $stSheets->where('is_absent', false);
            $avgSheet = $validSheets->count() > 0 ? $validSheets->avg('total_score_100') : null;

            $stTests = $drawTests->filter(function($t) use ($regNo, $sbteRegNo) {
                return $t->reg_no === $regNo || ($sbteRegNo && $t->reg_no === $sbteRegNo);
            });
            $testScores = [];
            foreach ($stTests as $t) {
                if (!$t->is_absent && is_numeric($t->total_score_100)) {
                    $testScores[] = round(((float)$t->total_score_100 / 100.0) * 15.0, 2);
                }
            }
            $drawTestAvg = count($testScores) > 0 ? (array_sum($testScores) / count($testScores)) : 0.0;

            // Attendance (max 15)
            $drawAtt = AttainmentService::calculateR21AttendanceMark($attPct, 15.0);

            if ($avgSheet !== null || count($testScores) > 0) {
                $continuous = ($avgSheet !== null) ? round((($avgSheet / 100.0) * 37.5) * 2) / 2 : 0.0;
                $tot = (int)round($continuous + $drawTestAvg + $drawAtt);
                return ['mark' => min(75, max(0, $tot)), 'is_generated' => true];
            }

            $preMark = $semesterMarks->filter(function($m) use ($regNo, $sbteRegNo, $sCode) {
                return ($m->reg_no === $regNo || ($sbteRegNo && $m->reg_no === $sbteRegNo)) && $m->subject_code == $sCode;
            })->first();
            if ($preMark && is_numeric($preMark->internal_marks)) {
                return ['mark' => (int)round($preMark->internal_marks), 'is_generated' => true];
            }

            return ['mark' => null, 'is_generated' => false];
        }

        // 3. Seminar Subjects
        if (str_contains($type, 'seminar') || str_contains($name, 'seminar')) {
            $semEval = $seminarEvals->filter(function($e) use ($regNo, $sbteRegNo) {
                return $e->reg_no === $regNo || ($sbteRegNo && $e->reg_no === $sbteRegNo);
            })->first();

            if ($semEval) {
                $rep = (float)($semEval->report_score ?? 0);
                $pres = (float)($semEval->presentation_score ?? 0);
                $rel = (float)($semEval->relevance_score ?? 0);
                $lit = (float)($semEval->literature_score ?? 0);
                $int = (float)($semEval->interaction_score ?? 0);
                $seminarComp = round(($rep + $pres + $rel + $lit + $int) / 100.0 * 60.0, 1);
                $attComp = AttainmentService::calculateR21AttendanceMark($attPct, 15.0);
                $tot = min(75, (int)round($seminarComp + $attComp));
                return ['mark' => $tot, 'is_generated' => true];
            }

            $preMark = $semesterMarks->filter(function($m) use ($regNo, $sbteRegNo, $sCode) {
                return ($m->reg_no === $regNo || ($sbteRegNo && $m->reg_no === $sbteRegNo)) && $m->subject_code == $sCode;
            })->first();
            if ($preMark && is_numeric($preMark->internal_marks)) {
                return ['mark' => (int)round($preMark->internal_marks), 'is_generated' => true];
            }

            return ['mark' => null, 'is_generated' => false];
        }

        // 4. Major Project Subjects
        if (str_contains($type, 'project') || str_contains($name, 'project')) {
            $projEval = $projectEvals->filter(function($e) use ($regNo, $sbteRegNo) {
                return $e->reg_no === $regNo || ($sbteRegNo && $e->reg_no === $sbteRegNo);
            })->first();

            if ($projEval && ($projEval->formative_diary_marks > 0 || $projEval->summative_dept_marks > 0)) {
                $diary = (float)($projEval->formative_diary_marks ?? 0);
                $review = (float)($projEval->summative_dept_marks ?? 0);
                $projAtt = (float)($projEval->attendance_marks ?? AttainmentService::calculateR21AttendanceMark($attPct, 15.0));
                $tot = min(75, (int)round($diary + $review + $projAtt));
                return ['mark' => $tot, 'is_generated' => true];
            }

            $preMark = $semesterMarks->filter(function($m) use ($regNo, $sbteRegNo, $sCode) {
                return ($m->reg_no === $regNo || ($sbteRegNo && $m->reg_no === $sbteRegNo)) && $m->subject_code == $sCode;
            })->first();
            if ($preMark && is_numeric($preMark->internal_marks)) {
                return ['mark' => (int)round($preMark->internal_marks), 'is_generated' => true];
            }

            return ['mark' => null, 'is_generated' => false];
        }

        // 5. Theory Subjects (Default)
        $stAcademic = $academicMarks->filter(function($m) use ($regNo, $sbteRegNo, $sId, $sCode) {
            $matchReg = ($m->reg_no === $regNo || ($sbteRegNo && $m->reg_no === $sbteRegNo));
            $matchSubj = (isset($m->batch_subject_id) && $m->batch_subject_id == $sId) || ($m->subject_code == $sCode);
            return $matchReg && $matchSubj;
        });

        $assignScores = [];
        $summScores = [];

        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $co) {
            $a = $stAcademic->where('category', 'Assignment')->where('co_tag', $co)->sortByDesc('updated_at')->first();
            if ($a && is_numeric($a->marks_obtained)) {
                $assignScores[] = (float)$a->marks_obtained;
            }

            $s = $stAcademic->whereIn('category', ['Written Test', 'Summative', 'Series Test'])->where('co_tag', $co)->sortByDesc('updated_at')->first();
            if ($s && is_numeric($s->marks_obtained)) {
                $summScores[] = (float)$s->marks_obtained;
            }
        }

        if (count($assignScores) > 0 || count($summScores) > 0) {
            // Best 2 out of assignments (max 20)
            rsort($assignScores);
            if (count($assignScores) >= 2) {
                $assignAvg = ($assignScores[0] + $assignScores[1]) / 2.0;
            } elseif (count($assignScores) === 1) {
                $assignAvg = $assignScores[0];
            } else {
                $assignAvg = 0.0;
            }

            // Best 2 out of summatives (max 20)
            rsort($summScores);
            if (count($summScores) >= 2) {
                $summAvg = ($summScores[0] + $summScores[1]) / 2.0;
            } elseif (count($summScores) === 1) {
                $summAvg = $summScores[0];
            } else {
                $summAvg = 0.0;
            }

            // Attendance (max 10 for Theory)
            $attMark = AttainmentService::calculateR21AttendanceMark($attPct, 10.0);

            $tot = (int)round($assignAvg + $summAvg + $attMark);
            return ['mark' => min($maxCia, max(0, $tot)), 'is_generated' => true];
        }

        // Check fallback to student_semester_marks
        $preMark = $semesterMarks->filter(function($m) use ($regNo, $sbteRegNo, $sCode) {
            return ($m->reg_no === $regNo || ($sbteRegNo && $m->reg_no === $sbteRegNo)) && $m->subject_code == $sCode;
        })->first();

        if ($preMark && is_numeric($preMark->internal_marks)) {
            return ['mark' => (int)round($preMark->internal_marks), 'is_generated' => true];
        }

        return ['mark' => null, 'is_generated' => false];
    }

    /**
     * Helper to keep student_semester_marks synchronized.
     */
    private static function syncToSemesterMarksTable($regNo, $subjectCode, $subjectName, $semester, $ciaMark, $attPct)
    {
        if (!Schema::hasTable('student_semester_marks') || !$ciaMark) return;

        try {
            DB::table('student_semester_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'subject_code' => $subjectCode,
                    'semester' => $semester
                ],
                [
                    'subject_name' => $subjectName,
                    'internal_marks' => $ciaMark,
                    'attendance_percentage' => $attPct,
                    'updated_at' => now()
                ]
            );
        } catch (\Exception $e) {
            // Ignore constraint errors silently during live calculation
        }
    }

    /**
     * API: Approve & Lock (or Unlock) Consolidated CIA Report by HOD.
     */
    public function toggleLock(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        $userName = Session::get('userName') ?? 'Head of Department';

        if (!in_array($role, ['HOD', 'Principal', 'Admin', 'Super_Admin'])) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Unauthorized. Only Head of Department (HOD) or Principal can approve and lock CIA marks.'
            ], 403);
        }

        $classroomId = $request->input('classroom_id');
        $semester = (int)$request->input('semester');
        $action = $request->input('action', 'lock'); // 'lock' | 'unlock'
        $remarks = $request->input('remarks');

        if (!$classroomId || !$semester) {
            return response()->json(['status' => 'ERROR', 'message' => 'Classroom ID and Semester are required.'], 422);
        }

        $approval = ConsolidatedCiaApproval::firstOrNew([
            'classroom_id' => $classroomId,
            'semester' => $semester
        ]);

        if ($action === 'lock' || $action === 'approve') {
            $approval->is_locked = true;
            $approval->approved_by_hod = true;
            $approval->hod_user_id = $userId;
            $approval->hod_name = $userName;
            $approval->approved_at = now();
            if ($remarks) $approval->remarks = $remarks;
            $approval->save();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Consolidated CIA marks for Semester {$semester} successfully Approved and Locked. Subject teachers cannot edit marks until unlocked.",
                'approval' => $approval
            ]);
        } else {
            // Unlock
            $approval->is_locked = false;
            $approval->approved_by_hod = false;
            $approval->approved_at = null;
            if ($remarks) $approval->remarks = $remarks;
            $approval->save();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Consolidated CIA marks for Semester {$semester} Unlocked. Subject teachers can now update evaluations.",
                'approval' => $approval
            ]);
        }
    }

    /**
     * API: Submit Consolidated CIA report to HOD by Tutor.
     */
    public function submitToHod(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        $userName = Session::get('userName') ?? 'Class Tutor';

        $classroomId = $request->input('classroom_id');
        $semester = (int)$request->input('semester');

        if (!$classroomId || !$semester) {
            return response()->json(['status' => 'ERROR', 'message' => 'Classroom ID and Semester are required.'], 422);
        }

        $approval = ConsolidatedCiaApproval::firstOrNew([
            'classroom_id' => $classroomId,
            'semester' => $semester
        ]);

        if ($approval->is_locked) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'This report has already been approved and locked by the HOD.'
            ], 400);
        }

        $approval->submitted_by_tutor = true;
        $approval->tutor_user_id = $userId;
        $approval->tutor_name = $userName;
        $approval->submitted_at = now();
        $approval->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Consolidated CIA Report for Semester {$semester} submitted to HOD for final approval and locking.",
            'approval' => $approval
        ]);
    }

    /**
     * Print View: Consolidated CIA Mark Report on A4 Landscape.
     */
    public function printReport(Request $request)
    {
        $res = $this->getConsolidatedCiaData($request);
        $data = $res->getData(true);

        if (($data['status'] ?? '') !== 'SUCCESS') {
            abort(404, $data['message'] ?? 'Failed to load consolidated CIA report.');
        }

        return view('consolidated_cia_report_print', [
            'classroom'  => $data['classroom'],
            'available_semesters' => $data['available_semesters'],
            'subjects'   => collect($data['subjects'])->map(fn($s) => (object)$s),
            'students'   => $data['students'],
            'staff_list' => $data['staff_list'],
            'approval'   => $data['approval'],
            'stats'      => $data['stats'],
        ]);
    }
}
