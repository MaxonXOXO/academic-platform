<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use App\Models\BatchSubject;
use App\Models\Student;
use App\Models\StaffProfile;

class TutorController extends Controller
{
    /**
     * Map branch acronym to full department name.
     */
    public static function getBranchFullName($code)
    {
        $code = strtoupper(trim($code ?? ''));
        $branches = [
            'EL'  => 'Electronics Engineering',
            'CT'  => 'Computer Engineering',
            'CE'  => 'Civil Engineering',
            'ME'  => 'Mechanical Engineering',
            'AU'  => 'Automobile Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
        ];
        return $branches[$code] ?? ($code ?: 'Engineering');
    }

    /**
     * API: Get comprehensive student progress report data for a supervised classroom.
     * Includes Series Exam Marks (CO1, CO2, CO3, CO4) for all subjects, total attendance %, and class rank.
     */
    public function getProgressReportData(Request $request)
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        // Resolve staff mobile if available
        if ($staffMobile) {
            $staff = StaffProfile::where('mobile_no', $staffMobile)
                ->orWhere('email', $staffMobile)
                ->orWhere('id', $staffMobile)
                ->first();
            if ($staff && $staff->mobile_no) {
                $staffMobile = $staff->mobile_no;
            }
        }

        $cleanMobile = $staffMobile ? preg_replace('/[^0-9]/', '', $staffMobile) : null;

        // Resolve supervised classes
        $classes1 = collect();
        $classes2 = collect();
        if ($staffMobile) {
            $classes1 = DB::table('class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
                $q->where('tutor_mobile_no', $staffMobile)->orWhere('mentor_mobile_no', $staffMobile);
                if ($cleanMobile) {
                    $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
                }
            })->get();

            if (Schema::hasTable('r26_class_management')) {
                $classes2 = DB::table('r26_class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
                    $q->where('tutor_mobile_no', $staffMobile)->orWhere('mentor_mobile_no', $staffMobile);
                    if ($cleanMobile) {
                        $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
                    }
                })->get();
            }
        }

        $allClasses = $classes1->concat($classes2);

        // Requested classroom override or fallback to first supervised
        $requestedClassId = $request->input('classroom_id') ?? $request->input('classroom');
        $classroom = null;
        if ($requestedClassId) {
            $classroom = DB::table('class_management')->where('classroom_id', $requestedClassId)->first();
            if (!$classroom && Schema::hasTable('r26_class_management')) {
                $classroom = DB::table('r26_class_management')->where('classroom_id', $requestedClassId)->first();
            }
        }
        if (!$classroom) {
            $classroom = $allClasses->first();
        }

        // If still not resolved and user is admin/principal/HOD, pick active classroom
        if (!$classroom) {
            $userBranch = Session::get('userBranch');
            if ($userBranch) {
                $classroom = DB::table('class_management')->where('branch', $userBranch)->first();
            }
            if (!$classroom) {
                $classroom = DB::table('class_management')->first();
            }
        }

        if (!$classroom) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No classroom assigned as advisor/tutor/mentor to your profile.'
            ], 404);
        }

        $classroomId = $classroom->classroom_id;
        $branchCode = $classroom->branch ?? '';
        $branchName = self::getBranchFullName($branchCode);
        $semester = $classroom->current_semester ?? 1;
        $batchYear = $classroom->batch_year ?? null;

        // Calculate academic batch string (e.g. 2025 - 2028)
        $batchString = '';
        if ($batchYear) {
            $batchString = $batchYear . ' - ' . ($batchYear + 3);
        } else {
            $parts = explode('_', $classroomId);
            if (count($parts) >= 3 && is_numeric($parts[1]) && is_numeric($parts[2])) {
                $batchString = $parts[1] . ' - ' . $parts[2];
            } else {
                $batchString = $classroomId;
            }
        }

        // Fetch Tutor Profile details
        $tutorProfile = null;
        if (!empty($classroom->tutor_mobile_no)) {
            $tutorProfile = DB::table('staff_profiles')->where('mobile_no', $classroom->tutor_mobile_no)->first();
        }

        // 1. Fetch Subjects for this Classroom (strictly matching current semester)
        $rawSubjects = BatchSubject::where('classroom_id', $classroomId)
            ->where('semester', $semester)
            ->get(['id', 'subject_code', 'subject_name', 'subject_type', 'syllabus_revision_code']);

        // Determine revision & scheme
        $isRev2026 = (str_contains($classroomId, '2026') || ($classroom->batch_year ?? null) == 2026 || $rawSubjects->pluck('syllabus_revision_code')->contains('REV2026'));
        $revision = $isRev2026 ? 'REV2026' : 'REV2021';
        $schemeName = $isRev2026 ? 'Revision 2026 (SBTE Kerala)' : 'Revision 2021 (Diploma Engineering)';

        // Order subjects logically: Theory first, Practical second, Drawing, Seminar, Project (deduplicated by subject_code)
        $typePriority = [
            'Theory' => 1,
            'Theory / Lecture' => 1,
            'Theory Courses' => 1,
            'Practicum Course' => 2,
            'Practicum Courses' => 2,
            'Practical / Lab' => 2,
            'Practical' => 2,
            'Lab' => 2,
            'Laboratory/Workshop Courses' => 2,
            'Laboratory/Works' => 2,
            'Drawing' => 3,
            'Drawing Courses' => 3,
            'Seminar' => 4,
            'Project' => 5
        ];

        $subjects = $rawSubjects->unique('subject_code')->sortBy(function ($s) use ($typePriority) {
            $p = $typePriority[$s->subject_type] ?? 9;
            return sprintf('%02d_%s', $p, $s->subject_code);
        })->values();

        $subjectIds = $subjects->pluck('id')->toArray();
        $subjectCodes = $subjects->pluck('subject_code')->filter()->unique()->toArray();

        // 2. Fetch Students for this Classroom
        $students = Student::getClassroomStudentsQuery($classroomId)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no', 'phone', 'guardian_mobile', 'admission_type']);

        $studentRegNos = $students->pluck('reg_no')->toArray();

        // 3. Gather Series Exam / Written Test Marks for all subjects & students
        // Query A1: Rev 2026 Theory Evaluations (series_1_co1_25, series_1_co2_25, series_2_co3_25, series_2_co4_25)
        $r26TheoryEvals = collect();
        if (Schema::hasTable('r26_theory_evaluations') && !empty($subjectIds) && !empty($studentRegNos)) {
            $r26TheoryEvals = DB::table('r26_theory_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentRegNos)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // Query A2: Rev 2026 Practicum Series Theory (Series 1, Series 2, CO1, CO2, CO3, CO4)
        $r26PracticumTheoryEvals = collect();
        if (Schema::hasTable('r26_practicum_series_theory') && !empty($subjectIds) && !empty($studentRegNos)) {
            $r26PracticumTheoryEvals = DB::table('r26_practicum_series_theory')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentRegNos)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // Query A3: Rev 2026 Drawing Evaluations (series_1_co1_20, series_1_co2_20, series_2_co3_20, series_2_co4_20)
        $r26DrawingEvals = collect();
        if (Schema::hasTable('r26_drawing_evaluations') && !empty($subjectIds) && !empty($studentRegNos)) {
            $r26DrawingEvals = DB::table('r26_drawing_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentRegNos)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // Query A4: Rev 2026 English Evaluations (series1_50, series2_50)
        $r26EnglishEvals = collect();
        if (Schema::hasTable('r26_english_evaluations') && !empty($subjectIds) && !empty($studentRegNos)) {
            $r26EnglishEvals = DB::table('r26_english_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentRegNos)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // Query A5: Rev 2026 Practicum Practical Evaluations (r26_practicum_series_practical - max 40)
        $r26PracticumPracticalEvals = collect();
        if (Schema::hasTable('r26_practicum_series_practical') && !empty($subjectIds) && !empty($studentRegNos)) {
            $r26PracticumPracticalEvals = DB::table('r26_practicum_series_practical')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentRegNos)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // Query A6: Rev 2026 Practical Standalone Evaluations (r26_practical_series_evaluations - max 40)
        $r26PracticalSeriesEvals = collect();
        if (Schema::hasTable('r26_practical_series_evaluations') && !empty($subjectIds) && !empty($studentRegNos)) {
            $r26PracticalSeriesEvals = DB::table('r26_practical_series_evaluations')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentRegNos)
                ->get()
                ->groupBy('batch_subject_id');
        }

        // Query A7: academic_marks (Theory, Written Test, Series Test, Summative - strictly excluding Self Study / Assignment / MCQ)
        $academicMarks = collect();
        if (Schema::hasTable('academic_marks') && !empty($studentRegNos) && !empty($subjectCodes)) {
            $academicMarks = DB::table('academic_marks')
                ->whereIn('reg_no', $studentRegNos)
                ->where(function($q) use ($subjectCodes, $subjectIds) {
                    $q->whereIn('subject_code', $subjectCodes);
                    if (!empty($subjectIds) && Schema::hasColumn('academic_marks', 'batch_subject_id')) {
                        $q->orWhereIn('batch_subject_id', $subjectIds);
                    }
                })
                ->get();
        }

        // Query B: practical_test_marks (Practical / Lab subjects)
        $practicalMarks = collect();
        if (Schema::hasTable('practical_test_marks') && Schema::hasTable('practical_tests') && !empty($subjectIds) && !empty($studentRegNos)) {
            $practicalMarks = DB::table('practical_test_marks')
                ->join('practical_tests', 'practical_test_marks.practical_test_id', '=', 'practical_tests.id')
                ->whereIn('practical_tests.batch_subject_id', $subjectIds)
                ->whereIn('practical_test_marks.reg_no', $studentRegNos)
                ->select(
                    'practical_test_marks.reg_no',
                    'practical_tests.batch_subject_id',
                    'practical_test_marks.co_tag',
                    'practical_test_marks.marks_obtained'
                )
                ->get();
        }

        // Query C: r21_drawing_series_tests (Drawing subjects)
        $drawingTests = collect();
        if (Schema::hasTable('r21_drawing_series_tests') && !empty($subjectIds) && !empty($studentRegNos)) {
            $drawingTests = DB::table('r21_drawing_series_tests')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentRegNos)
                ->get();
        }

        // 4. Gather Authoritative Attendance Uploaded by Tutor (tutor_class_attendances in total)
        $tutorClassAttendances = collect();
        if (Schema::hasTable('tutor_class_attendances') && !empty($studentRegNos)) {
            $tutorClassAttendances = DB::table('tutor_class_attendances')
                ->where('classroom_id', $classroomId)
                ->whereIn('reg_no', $studentRegNos)
                ->get()
                ->keyBy('reg_no');
        }

        $studentAttGrouped = collect();
        if (Schema::hasTable('student_attendance') && !empty($subjectCodes)) {
            $studentAttQuery = DB::table('student_attendance')
                ->whereIn('subject_code', $subjectCodes)
                ->whereIn('reg_no', $studentRegNos)
                ->get();
            $studentAttGrouped = $studentAttQuery->groupBy('reg_no');
        }

        $classLogsBySubject = collect();
        if (Schema::hasTable('class_logs_attendance') && !empty($subjectIds)) {
            $classLogs = DB::table('class_logs_attendance')
                ->whereIn('batch_subject_id', $subjectIds)
                ->get();
            $classLogsBySubject = $classLogs->groupBy('batch_subject_id');
        }

        // 5. Gather Activity Points Cumulated across ALL Semesters
        $activityPointsMap = [];
        if (Schema::hasTable('activity_point_claims') && !empty($studentRegNos)) {
            $claims = DB::table('activity_point_claims')
                ->whereIn('reg_no', $studentRegNos)
                ->whereIn('status', ['Verified', 'Approved'])
                ->groupBy('reg_no')
                ->select('reg_no', DB::raw('SUM(points_awarded) as total'))
                ->get();
            foreach ($claims as $c) {
                $activityPointsMap[$c->reg_no] = (float)$c->total;
            }
        }
        if (Schema::hasTable('student_semester_summary') && !empty($studentRegNos)) {
            $summaries = DB::table('student_semester_summary')
                ->whereIn('reg_no', $studentRegNos)
                ->groupBy('reg_no')
                ->select('reg_no', DB::raw('SUM(activity_points) as total'))
                ->get();
            foreach ($summaries as $s) {
                $activityPointsMap[$s->reg_no] = max($activityPointsMap[$s->reg_no] ?? 0.0, (float)$s->total);
            }
        }
        if (Schema::hasTable('extracurricular_activities') && !empty($studentRegNos)) {
            $extras = DB::table('extracurricular_activities')
                ->whereIn('reg_no', $studentRegNos)
                ->whereIn('status', ['Verified', 'Approved'])
                ->groupBy('reg_no')
                ->select('reg_no', DB::raw('SUM(points_awarded) as total'))
                ->get();
            foreach ($extras as $e) {
                $activityPointsMap[$e->reg_no] = max($activityPointsMap[$e->reg_no] ?? 0.0, (float)$e->total);
            }
        }

        // 6. Gather Semester Assignments (Total Given vs Completed / Pending)
        $classroomAssignments = collect();
        if (Schema::hasTable('academic_marks') && !empty($studentRegNos) && !empty($subjectIds)) {
            $classroomAssignments = DB::table('academic_marks')
                ->whereIn('batch_subject_id', $subjectIds)
                ->where('category', 'like', '%assign%')
                ->get();
        }
        $distinctTasksCount = $classroomAssignments->unique(fn($r) => $r->subject_code . '_' . ($r->co_tag ?? 'CO1'))->count();

        // 6b. Pre-analyze Series Exam Conduction per Subject
        $subjMeta = [];
        foreach ($subjects as $s) {
            $sId = $s->id;
            $sCode = $s->subject_code;

            // Theory Series 2 Conduction Check
            $isTheoryS2Conducted = false;
            if ($r26TheoryEvals->has($sId)) {
                $teAll = $r26TheoryEvals->get($sId, collect());
                $isTheoryS2Conducted = $teAll->filter(function($row) {
                    return (float)($row->series_2_50 ?? 0) > 0 || !empty($row->series_2_absent) || (float)($row->series_2_co3_25 ?? 0) > 0 || (float)($row->series_2_co4_25 ?? 0) > 0;
                })->count() > 1;
            }

            // Practicum Series Mode & Conduction Check
            $practicumTheoryMode = 'TWO_PART_SERIES';
            $isPracticumS2Conducted = false;
            if ($r26PracticumTheoryEvals->has($sId)) {
                $pstAll = $r26PracticumTheoryEvals->get($sId, collect());
                $maxS1 = (float)$pstAll->where('series_no', 'Series 1')->max('total_score_50');
                $maxS2 = (float)$pstAll->where('series_no', 'Series 2')->max('total_score_50');
                $hasExplicitCo = $pstAll->whereIn('series_no', ['CO1', 'CO2', 'CO3', 'CO4', 'Series 3', 'Series 4'])->isNotEmpty();

                if (!$hasExplicitCo && $maxS1 > 25.0) {
                    $practicumTheoryMode = 'SINGLE_50_SERIES';
                    $isPracticumS2Conducted = $pstAll->where('series_no', 'Series 2')->filter(fn($r) => (float)$r->total_score_50 > 0 || !empty($r->is_absent))->count() > 1;
                } else {
                    $practicumTheoryMode = 'TWO_PART_SERIES';
                    $isPracticumS2Conducted = $pstAll->whereIn('series_no', ['Series 3', 'Series 4', 'CO3', 'CO4'])->filter(fn($r) => (float)$r->total_score_50 > 0 || !empty($r->is_absent))->count() > 1;
                }
            }

            // Practical Series 2 Conduction Check
            $isPracticalS2Conducted = false;
            if ($r26PracticumPracticalEvals->has($sId)) {
                $pspAll = $r26PracticumPracticalEvals->get($sId, collect());
                $isPracticalS2Conducted = $pspAll->whereIn('series_no', ['Series 2', 'Test 2', 'CA3', 'Test 2 (CO3+CO4)', 'CO3', 'CO4'])->filter(fn($r) => (float)$r->total_score_40 > 0 || !empty($r->is_absent))->count() > 1;
            }

            // Drawing Series 2 Conduction Check
            $isDrawingS2Conducted = false;
            if ($r26DrawingEvals->has($sId)) {
                $deAll = $r26DrawingEvals->get($sId, collect());
                $isDrawingS2Conducted = $deAll->filter(function($row) {
                    return (float)($row->series_2_40 ?? 0) > 0 || !empty($row->series_2_absent) || (float)($row->series_2_co3_20 ?? 0) > 0 || (float)($row->series_2_co4_20 ?? 0) > 0;
                })->count() > 1;
            }

            // Academic Marks Series 2 Conduction Check
            $isAcademicS2Conducted = false;
            if ($academicMarks->isNotEmpty()) {
                $isAcademicS2Conducted = $academicMarks->filter(function($m) use ($sId, $sCode) {
                    $matchSubj = (isset($m->batch_subject_id) && $m->batch_subject_id == $sId) || ($m->subject_code == $sCode);
                    if (!$matchSubj) return false;
                    $cat = $m->category ?? '';
                    if (stripos($cat, 'Self') !== false || stripos($cat, 'Assignment') !== false || stripos($cat, 'Mcq') !== false) return false;
                    return (stripos($cat, 'Series 2') !== false || stripos($cat, 'Series Exam 2') !== false || in_array($m->co_tag ?? '', ['CO3', 'CO4', 'CO3,CO4'])) && (float)$m->marks_obtained > 0;
                })->count() > 1;
            }

            $subjMeta[$sId] = [
                'theory_s2_conducted'    => $isTheoryS2Conducted,
                'practicum_theory_mode'  => $practicumTheoryMode,
                'practicum_s2_conducted' => $isPracticumS2Conducted,
                'practical_s2_conducted' => $isPracticalS2Conducted,
                'drawing_s2_conducted'   => $isDrawingS2Conducted,
                'academic_s2_conducted'  => $isAcademicS2Conducted,
            ];
        }

        // 7. Build Student Report Rows with Subject Series Marks & Attendance
        $studentsData = [];
        $totalConductedClasswide = 0;
        $totalAttendedClasswide = 0;

        foreach ($students as $stud) {
            $regNo = $stud->reg_no;
            $isLet = ($stud->admission_type === 'LET') || str_ends_with(strtoupper($regNo), 'L');
            $activityGoal = $isLet ? 40 : 60;

            // Attendance uploaded by tutor in total (authoritative)
            $tutorOfficial = $tutorClassAttendances->get($regNo);
            if ($tutorOfficial) {
                $overallAttPct = (float)($tutorOfficial->final_percentage ?? $tutorOfficial->attendance_percentage);
                $status = $tutorOfficial->eligibility_status ?: ($overallAttPct >= 75.0 ? 'Eligible' : ($overallAttPct >= 65.0 ? 'Condonation' : 'Detained'));
                $studentTotalAttended = (int)$tutorOfficial->attended_hours;
                $studentTotalConducted = (int)$tutorOfficial->total_hours;
            } else {
                // Fallback to calculated class logs / student attendance
                $stStudAtt = $studentAttGrouped->get($regNo, collect());
                $studentTotalAttended = 0;
                $studentTotalConducted = 0;

                foreach ($subjects as $subj) {
                    $sLogs = $classLogsBySubject->get($subj->id, collect());
                    $stSubjAttRecords = $stStudAtt->where('subject_code', $subj->subject_code);
                    if ($stSubjAttRecords->isNotEmpty()) {
                        $studentTotalConducted += $stSubjAttRecords->count();
                        $studentTotalAttended += $stSubjAttRecords->whereIn('status', ['Present', 'Late'])->count();
                    } elseif ($sLogs->isNotEmpty()) {
                        $studentTotalConducted += $sLogs->count();
                        $cAtt = 0;
                        foreach ($sLogs as $log) {
                            $pArr = json_decode($log->present_students ?? '[]', true) ?: [];
                            if (in_array($regNo, $pArr)) $cAtt++;
                        }
                        $studentTotalAttended += $cAtt;
                    }
                }

                $overallAttPct = $studentTotalConducted > 0 
                    ? round(($studentTotalAttended / $studentTotalConducted) * 100, 1) 
                    : 100.0;

                if ($overallAttPct >= 75.0) {
                    $status = 'Eligible';
                } elseif ($overallAttPct >= 65.0) {
                    $status = 'Condonation';
                } else {
                    $status = 'Detained';
                }
            }

            $totalConductedClasswide += $studentTotalConducted;
            $totalAttendedClasswide += $studentTotalAttended;

            $statusBadge = $status === 'Eligible' ? 'status-eligible' : ($status === 'Condonation' ? 'status-condonation' : 'status-detained');

            // Cumulative Activity Points
            $cumulatedActivityPoints = (float)($activityPointsMap[$regNo] ?? 0.0);

            // Assignment completion stats
            $stAssg = $classroomAssignments->where('reg_no', $regNo);
            $submittedCount = $stAssg->where('marks_obtained', '>', 0)->unique(fn($r) => $r->subject_code . '_' . ($r->co_tag ?? 'CO1'))->count();
            if ($submittedCount === 0 && $stAssg->count() > 0) {
                $submittedCount = $stAssg->unique(fn($r) => $r->subject_code . '_' . ($r->co_tag ?? 'CO1'))->count();
            }
            $pendingCount = max(0, $distinctTasksCount - $submittedCount);

            $subjectMarksData = [];
            $studentGrandTotalMarks = 0.0;
            $studentSubjectsWithMarksCount = 0;
            $hasAnySeriesMark = false;

            foreach ($subjects as $subj) {
                $subjId = $subj->id;
                $sCode = $subj->subject_code;

                $s1_100 = null;
                $s2_100 = null;

                // 1. Check Rev 2026 English Evaluations (r26_english_evaluations - max 50)
                if ($r26EnglishEvals->has($subjId) && $r26EnglishEvals->get($subjId)->isNotEmpty()) {
                    $ee = $r26EnglishEvals->get($subjId)->where('reg_no', $regNo)->first();
                    if ($ee) {
                        if ($ee->series1_50 !== null) {
                            $s1_100 = min(100.0, max(0.0, round(((float)$ee->series1_50 / 50.0) * 100.0, 1)));
                        }
                        if ($ee->series2_50 !== null) {
                            $s2_100 = min(100.0, max(0.0, round(((float)$ee->series2_50 / 50.0) * 100.0, 1)));
                        }
                    }
                }

                // 2. Check Rev 2026 Theory Evaluations (r26_theory_evaluations - max 50)
                if ($s1_100 === null && $r26TheoryEvals->has($subjId)) {
                    $te = $r26TheoryEvals->get($subjId)->where('reg_no', $regNo)->first();
                    if ($te) {
                        $rawS1 = null;
                        if ($te->series_1_50 !== null) {
                            $rawS1 = (float)$te->series_1_50;
                        } elseif ($te->series_1_co1_25 !== null || $te->series_1_co2_25 !== null) {
                            $rawS1 = (float)($te->series_1_co1_25 ?? 0) + (float)($te->series_1_co2_25 ?? 0);
                        }
                        if ($rawS1 !== null) {
                            $s1_100 = min(100.0, max(0.0, round(($rawS1 / 50.0) * 100.0, 1)));
                        }

                        if ($subjMeta[$subjId]['theory_s2_conducted'] ?? false) {
                            $rawS2 = null;
                            if ($te->series_2_50 !== null) {
                                $rawS2 = (float)$te->series_2_50;
                            } elseif ($te->series_2_co3_25 !== null || $te->series_2_co4_25 !== null) {
                                $rawS2 = (float)($te->series_2_co3_25 ?? 0) + (float)($te->series_2_co4_25 ?? 0);
                            }
                            if ($rawS2 !== null) {
                                $s2_100 = min(100.0, max(0.0, round(($rawS2 / 50.0) * 100.0, 1)));
                            }
                        }
                    }
                }

                // 3. Check Rev 2026 Practicum Theory + Practical Evaluations
                $thS1_100 = null;
                $thS2_100 = null;
                $prS1_100 = null;
                $prS2_100 = null;

                if ($s1_100 === null && $r26PracticumTheoryEvals->has($subjId)) {
                    $pstList = $r26PracticumTheoryEvals->get($subjId)->where('reg_no', $regNo);
                    if ($pstList->isNotEmpty()) {
                        $pMode = $subjMeta[$subjId]['practicum_theory_mode'] ?? 'TWO_PART_SERIES';
                        if ($pMode === 'TWO_PART_SERIES') {
                            // Revision 2026 Practicum: Series 1 = CO1 (max 25) + CO2 (max 25) -> Total max 50
                            $co1 = $pstList->whereIn('series_no', ['Series 1', 'CO1'])->first();
                            $co2 = $pstList->whereIn('series_no', ['Series 2', 'CO2'])->first();
                            if ($co1 || $co2) {
                                $totS1 = ($co1 ? (float)$co1->total_score_50 : 0.0) + ($co2 ? (float)$co2->total_score_50 : 0.0);
                                $thS1_100 = min(100.0, max(0.0, round(($totS1 / 50.0) * 100.0, 1)));
                            }

                            // Series 2 = CO3 (max 25) + CO4 (max 25) -> Total max 50 (only if conducted)
                            if ($subjMeta[$subjId]['practicum_s2_conducted'] ?? false) {
                                $co3 = $pstList->whereIn('series_no', ['Series 3', 'CO3'])->first();
                                $co4 = $pstList->whereIn('series_no', ['Series 4', 'CO4'])->first();
                                if ($co3 || $co4) {
                                    $totS2 = ($co3 ? (float)$co3->total_score_50 : 0.0) + ($co4 ? (float)$co4->total_score_50 : 0.0);
                                    $thS2_100 = min(100.0, max(0.0, round(($totS2 / 50.0) * 100.0, 1)));
                                }
                            }
                        } else {
                            // Standard single 50M series exam per session
                            $pS1 = $pstList->where('series_no', 'Series 1')->first();
                            if ($pS1 && $pS1->total_score_50 !== null) {
                                $thS1_100 = min(100.0, max(0.0, round(((float)$pS1->total_score_50 / 50.0) * 100.0, 1)));
                            }
                            if ($subjMeta[$subjId]['practicum_s2_conducted'] ?? false) {
                                $pS2 = $pstList->where('series_no', 'Series 2')->first();
                                if ($pS2 && $pS2->total_score_50 !== null) {
                                    $thS2_100 = min(100.0, max(0.0, round(((float)$pS2->total_score_50 / 50.0) * 100.0, 1)));
                                }
                            }
                        }
                    }
                }

                if ($s1_100 === null && $r26PracticumPracticalEvals->has($subjId)) {
                    $pspList = $r26PracticumPracticalEvals->get($subjId)->where('reg_no', $regNo);
                    if ($pspList->isNotEmpty()) {
                        $pr1 = $pspList->whereIn('series_no', ['Series 1', 'Test 1', 'CA2', 'Test 1 (CO1+CO2)', 'CO1', 'CO2'])->first();
                        if ($pr1 && $pr1->total_score_40 !== null) {
                            $prS1_100 = min(100.0, max(0.0, round(((float)$pr1->total_score_40 / 40.0) * 100.0, 1)));
                        }

                        if ($subjMeta[$subjId]['practical_s2_conducted'] ?? false) {
                            $pr2 = $pspList->whereIn('series_no', ['Series 2', 'Test 2', 'CA3', 'Test 2 (CO3+CO4)', 'CO3', 'CO4'])->first();
                            if ($pr2 && $pr2->total_score_40 !== null) {
                                $prS2_100 = min(100.0, max(0.0, round(((float)$pr2->total_score_40 / 40.0) * 100.0, 1)));
                            }
                        }
                    }
                }

                if ($thS1_100 !== null || $prS1_100 !== null) {
                    $s1_100 = ($thS1_100 !== null && $prS1_100 !== null) ? round(($thS1_100 + $prS1_100) / 2.0, 1) : ($thS1_100 !== null ? $thS1_100 : $prS1_100);
                }
                if ($thS2_100 !== null || $prS2_100 !== null) {
                    $s2_100 = ($thS2_100 !== null && $prS2_100 !== null) ? round(($thS2_100 + $prS2_100) / 2.0, 1) : ($thS2_100 !== null ? $thS2_100 : $prS2_100);
                }

                // 4. Check Rev 2026 Practical Standalone Evaluations (r26_practical_series_evaluations - max 40)
                if ($s1_100 === null && $r26PracticalSeriesEvals->has($subjId)) {
                    $pseList = $r26PracticalSeriesEvals->get($subjId)->where('reg_no', $regNo);
                    if ($pseList->isNotEmpty()) {
                        $ps1 = $pseList->whereIn('series_no', ['Series 1', 'Test 1', '1'])->first();
                        if ($ps1 && $ps1->total_score_40 !== null) {
                            $s1_100 = min(100.0, max(0.0, round(((float)$ps1->total_score_40 / 40.0) * 100.0, 1)));
                        }
                        if ($subjMeta[$subjId]['practical_s2_conducted'] ?? false) {
                            $ps2 = $pseList->whereIn('series_no', ['Series 2', 'Test 2', '2'])->first();
                            if ($ps2 && $ps2->total_score_40 !== null) {
                                $s2_100 = min(100.0, max(0.0, round(((float)$ps2->total_score_40 / 40.0) * 100.0, 1)));
                            }
                        }
                    }
                }

                // 5. Check Rev 2026 Drawing Evaluations (r26_drawing_evaluations - max 40)
                if ($s1_100 === null && $r26DrawingEvals->has($subjId)) {
                    $de = $r26DrawingEvals->get($subjId)->where('reg_no', $regNo)->first();
                    if ($de) {
                        $rawS1 = null;
                        if ($de->series_1_40 !== null) {
                            $rawS1 = (float)$de->series_1_40;
                        } elseif ($de->series_1_co1_20 !== null || $de->series_1_co2_20 !== null) {
                            $rawS1 = (float)($de->series_1_co1_20 ?? 0) + (float)($de->series_1_co2_20 ?? 0);
                        }
                        if ($rawS1 !== null) {
                            $s1_100 = min(100.0, max(0.0, round(($rawS1 / 40.0) * 100.0, 1)));
                        }

                        if ($subjMeta[$subjId]['drawing_s2_conducted'] ?? false) {
                            $rawS2 = null;
                            if ($de->series_2_40 !== null) {
                                $rawS2 = (float)$de->series_2_40;
                            } elseif ($de->series_2_co3_20 !== null || $de->series_2_co4_20 !== null) {
                                $rawS2 = (float)($de->series_2_co3_20 ?? 0) + (float)($de->series_2_co4_20 ?? 0);
                            }
                            if ($rawS2 !== null) {
                                $s2_100 = min(100.0, max(0.0, round(($rawS2 / 40.0) * 100.0, 1)));
                            }
                        }
                    }
                }

                // 6. Check Rev 2021 Drawing Tests (r21_drawing_series_tests - max 100)
                if ($s1_100 === null && $drawingTests->isNotEmpty()) {
                    $stDraw = $drawingTests->where('reg_no', $regNo)->where('batch_subject_id', $subjId);
                    if ($stDraw->isNotEmpty()) {
                        $t1 = $stDraw->where('test_no', 'Test 1')->first();
                        if ($t1 && is_numeric($t1->total_score_100)) {
                            $s1_100 = min(100.0, max(0.0, round((float)$t1->total_score_100, 1)));
                        }
                        $t2 = $stDraw->where('test_no', 'Test 2')->first();
                        if ($t2 && is_numeric($t2->total_score_100)) {
                            $s2_100 = min(100.0, max(0.0, round((float)$t2->total_score_100, 1)));
                        }
                    }
                }

                // 7. Check academic_marks (Theory, Series Exam 1 & 2 - max 50)
                if (($s1_100 === null || $s2_100 === null) && $academicMarks->isNotEmpty()) {
                    $stAcademic = $academicMarks->where('reg_no', $regNo)->filter(function($m) use ($subjId, $sCode) {
                        return (isset($m->batch_subject_id) && $m->batch_subject_id == $subjId) || ($m->subject_code == $sCode);
                    });

                    if ($s1_100 === null) {
                        $s1Rows = $stAcademic->filter(function($row) {
                            $cat = $row->category ?? '';
                            if (stripos($cat, 'Self') !== false || stripos($cat, 'Assignment') !== false || stripos($cat, 'Mcq') !== false) return false;
                            return (stripos($cat, 'Series 1') !== false || stripos($cat, 'Series Exam 1') !== false || str_contains($row->co_tag ?? '', 'CO1,CO2') || in_array($row->co_tag ?? '', ['CO1', 'CO2']));
                        });
                        if ($s1Rows->isNotEmpty()) {
                            $comb = $s1Rows->whereIn('co_tag', ['CO1,CO2', 'All', ''])->first();
                            if ($comb && is_numeric($comb->marks_obtained)) {
                                $maxM = (float)($comb->max_marks ?: 50);
                                $s1_100 = min(100.0, max(0.0, round(((float)$comb->marks_obtained / $maxM) * 100.0, 1)));
                            } else {
                                $c1 = $s1Rows->where('co_tag', 'CO1')->first();
                                $c2 = $s1Rows->where('co_tag', 'CO2')->first();
                                if ($c1 || $c2) {
                                    $tot = (float)($c1->marks_obtained ?? 0) + (float)($c2->marks_obtained ?? 0);
                                    $maxM = ((float)($c1->max_marks ?? 25)) + ((float)($c2->max_marks ?? 25)) ?: 50;
                                    $s1_100 = min(100.0, max(0.0, round(($tot / $maxM) * 100.0, 1)));
                                }
                            }
                        }
                    }

                    if ($s2_100 === null && ($subjMeta[$subjId]['academic_s2_conducted'] ?? false)) {
                        $s2Rows = $stAcademic->filter(function($row) {
                            $cat = $row->category ?? '';
                            if (stripos($cat, 'Self') !== false || stripos($cat, 'Assignment') !== false || stripos($cat, 'Mcq') !== false) return false;
                            return (stripos($cat, 'Series 2') !== false || stripos($cat, 'Series Exam 2') !== false || str_contains($row->co_tag ?? '', 'CO3,CO4') || in_array($row->co_tag ?? '', ['CO3', 'CO4']));
                        });
                        if ($s2Rows->isNotEmpty()) {
                            $comb = $s2Rows->whereIn('co_tag', ['CO3,CO4', 'All', ''])->first();
                            if ($comb && is_numeric($comb->marks_obtained)) {
                                $maxM = (float)($comb->max_marks ?: 50);
                                $s2_100 = min(100.0, max(0.0, round(((float)$comb->marks_obtained / $maxM) * 100.0, 1)));
                            } else {
                                $c3 = $s2Rows->where('co_tag', 'CO3')->first();
                                $c4 = $s2Rows->where('co_tag', 'CO4')->first();
                                if ($c3 || $c4) {
                                    $tot = (float)($c3->marks_obtained ?? 0) + (float)($c4->marks_obtained ?? 0);
                                    $maxM = ((float)($c3->max_marks ?? 25)) + ((float)($c4->max_marks ?? 25)) ?: 50;
                                    $s2_100 = min(100.0, max(0.0, round(($tot / $maxM) * 100.0, 1)));
                                }
                            }
                        }
                    }
                }

                // 8. Practical Test Marks (Fallback for R21 Practical - max 40)
                if (($s1_100 === null || $s2_100 === null) && $practicalMarks->isNotEmpty()) {
                    $stPrac = $practicalMarks->where('reg_no', $regNo)->where('batch_subject_id', $subjId);
                    if ($stPrac->isNotEmpty()) {
                        if ($s1_100 === null) {
                            $p1 = $stPrac->whereIn('co_tag', ['Test 1', 'CO1', 'CO2'])->first();
                            if ($p1 && is_numeric($p1->marks_obtained)) {
                                $s1_100 = min(100.0, max(0.0, round(((float)$p1->marks_obtained / 40.0) * 100.0, 1)));
                            }
                        }
                        if ($s2_100 === null) {
                            $p2 = $stPrac->whereIn('co_tag', ['Test 2', 'CO3', 'CO4'])->first();
                            if ($p2 && is_numeric($p2->marks_obtained)) {
                                $s2_100 = min(100.0, max(0.0, round(((float)$p2->marks_obtained / 40.0) * 100.0, 1)));
                            }
                        }
                    }
                }

                // Apply Redefined Series Rule:
                // If series 1 is only available, show that only as total.
                // If 1 and 2 are both available, take total and average as total.
                $subjSeriesMark = null;
                if ($s1_100 !== null && $s2_100 !== null) {
                    $subjSeriesMark = round(($s1_100 + $s2_100) / 2.0, 1);
                } elseif ($s1_100 !== null) {
                    $subjSeriesMark = $s1_100;
                } elseif ($s2_100 !== null) {
                    $subjSeriesMark = $s2_100;
                }

                if ($subjSeriesMark !== null) {
                    $studentGrandTotalMarks += $subjSeriesMark;
                    $studentSubjectsWithMarksCount++;
                    $hasAnySeriesMark = true;
                }

                $subjectMarksData[$subjId] = [
                    'subject_id'   => $subjId,
                    'subject_code' => $sCode,
                    'subject_name' => $subj->subject_name,
                    'subject_type' => $subj->subject_type,
                    'series_1'     => $s1_100 !== null ? round($s1_100, 1) : null,
                    'series_2'     => $s2_100 !== null ? round($s2_100, 1) : null,
                    'subject_total'=> $subjSeriesMark !== null ? round($subjSeriesMark, 1) : null,
                    'has_s1'       => $s1_100 !== null,
                    'has_s2'       => $s2_100 !== null,
                    'co_marks'     => [
                        'CO1' => $s1_100 !== null ? round($s1_100 / 2, 1) : null,
                        'CO2' => $s1_100 !== null ? round($s1_100 / 2, 1) : null,
                        'CO3' => $s2_100 !== null ? round($s2_100 / 2, 1) : null,
                        'CO4' => $s2_100 !== null ? round($s2_100 / 2, 1) : null,
                    ]
                ];
            }

            $studentsData[] = [
                'roll_no'             => $stud->roll_no,
                'reg_no'              => $regNo,
                'sbte_reg_no'         => $stud->sbte_reg_no ?: $regNo,
                'name'                => $stud->name,
                'phone'               => $stud->phone,
                'guardian_mobile'     => $stud->guardian_mobile,
                'has_marks'           => $hasAnySeriesMark,
                'grand_total_marks'   => round($studentGrandTotalMarks, 1),
                'average_series_pct'  => $studentSubjectsWithMarksCount > 0 ? round($studentGrandTotalMarks / $studentSubjectsWithMarksCount, 1) : null,
                'total_attended'      => $studentTotalAttended,
                'total_conducted'     => $studentTotalConducted,
                'overall_attendance'  => $overallAttPct,
                'status'              => $status,
                'status_badge'        => $statusBadge,
                'activity_points'     => $cumulatedActivityPoints,
                'activity_goal'       => $activityGoal,
                'assignments'         => [
                    'total'     => $distinctTasksCount,
                    'submitted' => $submittedCount,
                    'pending'   => $pendingCount
                ],
                'subjects'            => $subjectMarksData,
                'class_rank'          => null, // Computed below
            ];
        }

        // 6. Compute Class Rank
        // Sort: 1) has_marks desc, 2) grand_total_marks desc, 3) overall_attendance desc, 4) roll_no asc
        usort($studentsData, function($a, $b) {
            if ($a['has_marks'] !== $b['has_marks']) {
                return $b['has_marks'] ? 1 : -1;
            }
            if ($a['grand_total_marks'] != $b['grand_total_marks']) {
                return $b['grand_total_marks'] <=> $a['grand_total_marks'];
            }
            if ($a['overall_attendance'] != $b['overall_attendance']) {
                return $b['overall_attendance'] <=> $a['overall_attendance'];
            }
            $rA = is_numeric($a['roll_no']) ? (int)$a['roll_no'] : 9999;
            $rB = is_numeric($b['roll_no']) ? (int)$b['roll_no'] : 9999;
            return $rA <=> $rB;
        });

        // Assign standard competition ranks
        $currentRank = 1;
        $prevScore = null;
        $prevRank = 1;

        foreach ($studentsData as $index => &$st) {
            if ($st['has_marks'] && $st['grand_total_marks'] > 0) {
                if ($prevScore !== null && abs($st['grand_total_marks'] - $prevScore) < 0.01) {
                    $st['class_rank'] = $prevRank;
                } else {
                    $st['class_rank'] = $currentRank;
                    $prevRank = $currentRank;
                    $prevScore = $st['grand_total_marks'];
                }
            } else {
                $st['class_rank'] = null; // Unranked / No marks entered yet
            }
            $currentRank++;
        }
        unset($st);

        // Sort back to Roll Number order for default register viewing
        usort($studentsData, function($a, $b) {
            $rA = is_numeric($a['roll_no']) ? (int)$a['roll_no'] : 9999;
            $rB = is_numeric($b['roll_no']) ? (int)$b['roll_no'] : 9999;
            if ($rA === $rB) {
                return strcmp($a['name'], $b['name']);
            }
            return $rA <=> $rB;
        });

        // 7. Calculate Summary Statistics
        $totalStudents = count($studentsData);
        $avgAttendance = $totalStudents > 0 
            ? round(array_sum(array_column($studentsData, 'overall_attendance')) / $totalStudents, 1) 
            : 0.0;

        $rankedStudents = array_filter($studentsData, fn($s) => $s['class_rank'] !== null);
        $avgMarks = count($rankedStudents) > 0 
            ? round(array_sum(array_column($rankedStudents, 'grand_total_marks')) / count($rankedStudents), 1) 
            : 0.0;

        // Find Topper (Rank 1)
        $topper = null;
        foreach ($studentsData as $st) {
            if ($st['class_rank'] === 1) {
                $topper = [
                    'name'        => $st['name'],
                    'roll_no'     => $st['roll_no'],
                    'sbte_reg_no' => $st['sbte_reg_no'],
                    'marks'       => $st['grand_total_marks'],
                    'attendance'  => $st['overall_attendance']
                ];
                break;
            }
        }

        $eligibleCount = count(array_filter($studentsData, fn($s) => $s['status'] === 'Eligible'));
        $condonationCount = count(array_filter($studentsData, fn($s) => $s['status'] === 'Condonation'));
        $detainedCount = count(array_filter($studentsData, fn($s) => $s['status'] === 'Detained'));

        return response()->json([
            'status' => 'SUCCESS',
            'classroom' => [
                'id'            => $classroom->classroom_id,
                'batch'         => $batchString,
                'branch_code'   => $branchCode,
                'branch_name'   => $branchName,
                'semester'      => $semester,
                'revision'      => $revision,
                'scheme_name'   => $schemeName,
                'tutor_name'    => $tutorProfile->name ?? 'Class Tutor',
                'tutor_mobile'  => $classroom->tutor_mobile_no ?? '',
                'date'          => date('d-m-Y')
            ],
            'summary' => [
                'total_students'    => $totalStudents,
                'average_attendance'=> $avgAttendance,
                'average_marks'     => $avgMarks,
                'topper'            => $topper,
                'eligible_count'    => $eligibleCount,
                'condonation_count' => $condonationCount,
                'detained_count'    => $detainedCount,
                'subjects_count'    => count($subjects)
            ],
            'subjects' => $subjects,
            'students' => $studentsData
        ]);
    }

    /**
     * Printable Progress Report view:
     * - mode=consolidated (default): A4 Landscape broadsheet for all students with all subjects CO1-CO4, attendance %, and class rank.
     * - mode=card or student={reg_no}: Individual student progress report card slip(s) for PTM.
     */
    public function printProgressReport(Request $request)
    {
        $res = $this->getProgressReportData($request);
        $data = $res->getData(true);

        if (($data['status'] ?? '') !== 'SUCCESS') {
            abort(404, $data['message'] ?? 'Failed to load student progress report.');
        }

        $mode = $request->query('mode', 'consolidated');
        $targetStudent = $request->query('student');

        // If specific student requested, filter students list and switch to card view
        if ($targetStudent) {
            $data['students'] = array_values(array_filter($data['students'], function($s) use ($targetStudent) {
                return $s['reg_no'] == $targetStudent || $s['sbte_reg_no'] == $targetStudent;
            }));
            $mode = 'card';
        }

        if ($mode === 'card' || $mode === 'all_cards') {
            return view('tutor.progress_report_card_print', [
                'classroom' => $data['classroom'],
                'summary'   => $data['summary'],
                'subjects'  => collect($data['subjects'])->map(fn($s) => (object)$s),
                'students'  => $data['students'],
                'isSingle'  => !empty($targetStudent)
            ]);
        }

        return view('tutor.progress_report_consolidated_print', [
            'classroom' => $data['classroom'],
            'summary'   => $data['summary'],
            'subjects'  => collect($data['subjects'])->map(fn($s) => (object)$s),
            'students'  => $data['students']
        ]);
    }

    /**
     * Print single student progress card slip.
     */
    public function printStudentProgressCard(Request $request, $regNo = null)
    {
        $regNo = $regNo ?: $request->input('reg_no') ?: $request->input('student');
        if ($regNo) {
            $regNo = urldecode(trim((string)$regNo));
        }
        $request->merge(['student' => $regNo, 'mode' => 'card']);
        return $this->printProgressReport($request);
    }
}

