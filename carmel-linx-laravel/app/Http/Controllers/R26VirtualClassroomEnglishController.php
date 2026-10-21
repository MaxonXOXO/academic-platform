<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\Student;
use App\Models\R26EnglishCourseFile;
use App\Models\R26EnglishLessonPlan;
use App\Models\R26EnglishEvaluation;
use App\Models\StaffProfile;
use App\Services\AttainmentService;

class R26VirtualClassroomEnglishController extends Controller
{
    /**
     * Dedicated Virtual Classroom for Revision 2026 Subject Code 1001: English for Communication
     * Assessment Methodology - Practicum (40 Marks CIA + 60 Marks ESE = 100 Marks Total)
     *   - Attendance: 5 Marks (End of semester)
     *   - CA1 (Self Learning): M1, M2, M3, M4 each out of 10 -> Avg (10) -> Converted to 5 Marks (By 4th week)
     *   - CE (Continuous Evaluation): M1, M2, M3, M4 each out of 50 -> Avg (50) -> Converted to 10 Marks (By 7th week)
     *   - CA2 & CA3 (Practical Tests): Test 1 (40) & Test 2 (40) -> Converted to 10 each -> Average 10 Marks (By 11th & 14th week)
     *   - CA4 & CA5 (Series Exams): Series 1 (50) & Series 2 (50) -> Converted to 10 each -> Average 10 Marks (8th & 15th week)
     *   - Total CIA = 5 + 5 + 10 + 10 + 10 = 40 Marks
     *   - ESE (Lab Examination - Internal, 2.5 hours): Exam mark 60 -> Converted to 60 Marks (End of semester)
     */
    public function show($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Fetch classroom details (check R26 table first, then fallback to standard)
        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // All R26 1001 English subjects across branches for quick branch switcher (if coordinator/admin)
        $allEnglishSubjects = BatchSubject::where('syllabus_revision_code', 'REV2026')
            ->where(function ($q) {
                $q->where('subject_code', 'like', '%1001%')
                  ->orWhere('subject_name', 'like', '%English%');
            })
            ->orderBy('classroom_id', 'asc')
            ->get(['id', 'classroom_id', 'subject_code', 'subject_name']);

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Fetch or Create Dedicated English Course File Record
        $englishCourseFile = R26EnglishCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program' => $classroom->department ?? $classroom->branch ?? 'Engineering',
                'course_title' => $batchSubject->subject_name ?: 'English for Communication',
                'course_code' => $batchSubject->subject_code ?: '1001',
                'semester' => $classroom->current_semester ?? 'I',
                'type_of_course' => 'Basic Science & Humanities Practicum',
                'teaching_scheme' => '2:0:2:0',
                'contact_hours' => 60,
                'credits' => 3.00,
                'cie_marks' => 40,
                'ese_marks' => 60,
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Apply fundamental grammar, vocabulary, and phonetic skills for accurate oral and written communication.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO2', 'description' => 'Comprehend and analyze technical texts, passages, and listening inputs to extract key information.', 'cognitive_level' => 'Analyze'],
                    ['id' => 'CO3', 'description' => 'Demonstrate effective speaking skills in group discussions, presentations, and professional interactions.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Draft professional emails, formal letters, technical reports, and resumes with clarity and precision.', 'cognitive_level' => 'Create'],
                ],
                'parsed_modules' => [
                    [
                        'module_id' => 'I',
                        'title' => 'Foundations of Communication & Functional Grammar',
                        'hours' => 15.0,
                        'content' => 'Process of communication, barriers, verbal & non-verbal communication, parts of speech, tenses, subject-verb agreement, active/passive voice, sentence correction, and phonetic transcription basics.'
                    ],
                    [
                        'module_id' => 'II',
                        'title' => 'Reading Comprehension, Vocabulary & Listening Skills',
                        'hours' => 15.0,
                        'content' => 'Skimming, scanning, intensive reading of technical passages, word formation, prefixes/suffixes, synonyms, antonyms, technical vocabulary, active listening strategies, note-taking.'
                    ],
                    [
                        'module_id' => 'III',
                        'title' => 'Oral & Interpersonal Communication Practicum',
                        'hours' => 15.0,
                        'content' => 'Self-introduction, JAM (Just A Minute) sessions, role plays, situational conversations, group discussions, technical presentations with visual aids, interview etiquette.'
                    ],
                    [
                        'module_id' => 'IV',
                        'title' => 'Professional & Technical Writing Practicum',
                        'hours' => 15.0,
                        'content' => 'Formal correspondence, business letters, professional email etiquette, resume & cover letter preparation, technical report writing, agenda & minutes of meeting.'
                    ],
                ],
                'parsed_exercises' => [
                    ['exercise_no' => 'EXP-01', 'module' => 'Module I', 'title' => 'Phonetics & Pronunciation Practice: Sounds of English, Stress & Intonation', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXP-02', 'module' => 'Module I', 'title' => 'Functional Grammar Lab: Error Identification & Sentence Building', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXP-03', 'module' => 'Module II', 'title' => 'Listening Comprehension & Audio Summarization Lab', 'co_id' => 'CO2', 'hours' => 3.0],
                    ['exercise_no' => 'EXP-04', 'module' => 'Module II', 'title' => 'Reading Comprehension & Technical Vocabulary Building', 'co_id' => 'CO2', 'hours' => 3.0],
                    ['exercise_no' => 'EXP-05', 'module' => 'Module III', 'title' => 'Self-Introduction, JAM & Role Play in Workplace Scenarios', 'co_id' => 'CO3', 'hours' => 4.0],
                    ['exercise_no' => 'EXP-06', 'module' => 'Module III', 'title' => 'Group Discussion & Oral Presentation with Audio-Visual Aids', 'co_id' => 'CO3', 'hours' => 4.0],
                    ['exercise_no' => 'EXP-07', 'module' => 'Module IV', 'title' => 'Drafting Professional Emails, Formal Letters & Resume Preparation', 'co_id' => 'CO4', 'hours' => 4.0],
                    ['exercise_no' => 'EXP-08', 'module' => 'Module IV', 'title' => 'Technical Report Writing & Mock Interview Practicum', 'co_id' => 'CO4', 'hours' => 4.0],
                ],
                'parsed_copo' => [
                    'credit' => 3.0,
                    'l_t_p_r' => '2:0:2:0',
                    'cie_marks' => 40,
                    'ese_marks' => 60,
                    'total_hours' => 60,
                    'mappings' => [
                        'CO1' => ['PO1' => '2', 'PO2' => '-', 'PO3' => '-', 'PO4' => '-', 'PO5' => '2', 'PO6' => '-', 'PO7' => '2'],
                        'CO2' => ['PO1' => '2', 'PO2' => '2', 'PO3' => '-', 'PO4' => '-', 'PO5' => '2', 'PO6' => '-', 'PO7' => '2'],
                        'CO3' => ['PO1' => '-', 'PO2' => '-', 'PO3' => '-', 'PO4' => '-', 'PO5' => '3', 'PO6' => '2', 'PO7' => '3'],
                        'CO4' => ['PO1' => '-', 'PO2' => '-', 'PO3' => '-', 'PO4' => '-', 'PO5' => '3', 'PO6' => '2', 'PO7' => '3'],
                    ],
                ],
                'parsed_textbooks' => [
                    'English for Technical Communication - SITTTR / State Board of Technical Education (Rev 2026)',
                    'Technical Communication: Principles and Practice - Meenakshi Raman & Sangeeta Sharma (Oxford University Press)',
                    'Effective Technical Communication - M. Ashraf Rizvi (McGraw Hill Education)',
                    'English Grammar in Use - Raymond Murphy (Cambridge University Press)',
                ],
            ]
        );

        // Fetch or Auto-Generate Dedicated Lesson Plan
        $lessonPlans = R26EnglishLessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        if ($lessonPlans->count() === 0) {
            $this->generateDefaultLessonPlan($batchSubject, $englishCourseFile);
            $lessonPlans = R26EnglishLessonPlan::where('batch_subject_id', $subjectId)
                ->orderBy('day_no', 'asc')
                ->get();
        }

        // Fetch Official TEAMS Uploaded Subject Attendance (Strict Subject-Level Separation)
        $subjectOfficialAttendance = DB::table('subject_official_attendances')
            ->where(function ($query) use ($subjectId, $batchSubject) {
                $query->where('batch_subject_id', $subjectId)
                      ->orWhere(function ($q) use ($batchSubject) {
                          $q->where('subject_code', $batchSubject->subject_code)
                            ->where('classroom_id', $batchSubject->classroom_id);
                      });
            })
            ->get()
            ->keyBy('reg_no');

        // Fallback to manual subject attendance logs
        $attendanceData = DB::table('student_attendance')
            ->where(function ($q) use ($batchSubject) {
                $q->where('subject_code', $batchSubject->subject_code)
                  ->orWhere('subject_code', 'like', '%' . substr($batchSubject->subject_code, -4) . '%');
            })
            ->get()
            ->groupBy('reg_no');

        // Fetch Consolidated Evaluations for R26 English (1001)
        $evaluations = R26EnglishEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Map Students & Compute All Components per SITTTR Practicum Table
        $studentResults = $students->map(function ($student) use (
            $subjectOfficialAttendance,
            $attendanceData,
            $evaluations
        ) {
            $regNo = $student->reg_no;
            $eval = $evaluations->get($regNo);

            // 1. Attendance Percentage & Mark (Out of 5)
            $stOfficial = $subjectOfficialAttendance->get($regNo)
                ?: (!empty($student->sbte_reg_no) ? $subjectOfficialAttendance->get($student->sbte_reg_no) : null);

            $staffUploadedPct = null;
            if ($stOfficial) {
                if ($stOfficial->teams_percentage !== null) {
                    $staffUploadedPct = (float)$stOfficial->teams_percentage;
                } elseif ($stOfficial->final_percentage !== null) {
                    $staffUploadedPct = (float)$stOfficial->final_percentage;
                }
            }

            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $logAttPct = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : null;

            $rawAttPercentage = $staffUploadedPct !== null ? $staffUploadedPct : $logAttPct;

            $overridePct = null;
            if ($eval && $eval->attendance_override_pct !== null) {
                $overridePct = (float)$eval->attendance_override_pct;
            } elseif ($stOfficial && $stOfficial->override_percentage !== null) {
                $overridePct = (float)$stOfficial->override_percentage;
            }

            $effectivePct = $overridePct !== null ? $overridePct : $rawAttPercentage;
            $attMark5 = $effectivePct !== null ? R26EnglishEvaluation::calculateAttendanceMarks($effectivePct) : 0.0;

            // 2. CA1: Self Learning (M1, M2, M3, M4 each out of 10 -> Avg out of 10 -> Converted to 5)
            $slM1 = ($eval && $eval->sl_m1_10 !== null) ? (float)$eval->sl_m1_10 : null;
            $slM2 = ($eval && $eval->sl_m2_10 !== null) ? (float)$eval->sl_m2_10 : null;
            $slM3 = ($eval && $eval->sl_m3_10 !== null) ? (float)$eval->sl_m3_10 : null;
            $slM4 = ($eval && $eval->sl_m4_10 !== null) ? (float)$eval->sl_m4_10 : null;

            $slHasAny = ($slM1 !== null || $slM2 !== null || $slM3 !== null || $slM4 !== null);
            $slSum = ($slM1 ?? 0.0) + ($slM2 ?? 0.0) + ($slM3 ?? 0.0) + ($slM4 ?? 0.0);
            $slAvg10 = $slHasAny ? round($slSum / 4.0, 2) : 0.0;
            $slConv5 = $slHasAny ? min(5.0, round((($slAvg10 / 10.0) * 5.0) * 2) / 2) : 0.0;

            // 3. CE: Continuous Evaluation (M1, M2, M3, M4 each out of 50 -> Avg out of 50 -> Converted to 10)
            $ceM1 = ($eval && $eval->ce_m1_50 !== null) ? (float)$eval->ce_m1_50 : null;
            $ceM2 = ($eval && $eval->ce_m2_50 !== null) ? (float)$eval->ce_m2_50 : null;
            $ceM3 = ($eval && $eval->ce_m3_50 !== null) ? (float)$eval->ce_m3_50 : null;
            $ceM4 = ($eval && $eval->ce_m4_50 !== null) ? (float)$eval->ce_m4_50 : null;

            $ceHasAny = ($ceM1 !== null || $ceM2 !== null || $ceM3 !== null || $ceM4 !== null);
            $ceSum = ($ceM1 ?? 0.0) + ($ceM2 ?? 0.0) + ($ceM3 ?? 0.0) + ($ceM4 ?? 0.0);
            $ceAvg50 = $ceHasAny ? round($ceSum / 4.0, 2) : 0.0;
            $ceConv10 = $ceHasAny ? min(10.0, round((($ceAvg50 / 50.0) * 10.0) * 2) / 2) : 0.0;

            // 4. CA2 & CA3: Practical Tests (Test 1 & Test 2 each out of 40 -> Converted to 10 each -> Average 10)
            $pt1Abs = $eval ? (bool)$eval->prac_test1_absent : false;
            $pt2Abs = $eval ? (bool)$eval->prac_test2_absent : false;
            $pt1Raw40 = ($eval && $eval->prac_test1_40 !== null) ? (float)$eval->prac_test1_40 : null;
            $pt2Raw40 = ($eval && $eval->prac_test2_40 !== null) ? (float)$eval->prac_test2_40 : null;

            $pt1Eff40 = (!$pt1Abs && $pt1Raw40 !== null) ? min(40.0, max(0.0, $pt1Raw40)) : 0.0;
            $pt2Eff40 = (!$pt2Abs && $pt2Raw40 !== null) ? min(40.0, max(0.0, $pt2Raw40)) : 0.0;

            $pt1Conv10 = (!$pt1Abs && $pt1Raw40 !== null) ? round(($pt1Eff40 / 40.0) * 10.0, 2) : 0.0;
            $pt2Conv10 = (!$pt2Abs && $pt2Raw40 !== null) ? round(($pt2Eff40 / 40.0) * 10.0, 2) : 0.0;

            $ptHasAny = ($pt1Raw40 !== null || $pt2Raw40 !== null || $pt1Abs || $pt2Abs);
            $pracAvg40 = $ptHasAny ? round(($pt1Eff40 + $pt2Eff40) / 2.0, 2) : 0.0;
            $pracConv10 = $ptHasAny ? min(10.0, round((($pracAvg40 / 40.0) * 10.0) * 2) / 2) : 0.0;

            // 5. CA4 & CA5: Series Exams (Series 1 & Series 2 each out of 50 -> Converted to 10 each -> Average 10)
            $s1Abs = $eval ? (bool)$eval->series1_absent : false;
            $s2Abs = $eval ? (bool)$eval->series2_absent : false;
            $s1Raw50 = ($eval && $eval->series1_50 !== null) ? (float)$eval->series1_50 : null;
            $s2Raw50 = ($eval && $eval->series2_50 !== null) ? (float)$eval->series2_50 : null;

            $s1Eff50 = (!$s1Abs && $s1Raw50 !== null) ? min(50.0, max(0.0, $s1Raw50)) : 0.0;
            $s2Eff50 = (!$s2Abs && $s2Raw50 !== null) ? min(50.0, max(0.0, $s2Raw50)) : 0.0;

            $s1Conv10 = (!$s1Abs && $s1Raw50 !== null) ? round(($s1Eff50 / 50.0) * 10.0, 2) : 0.0;
            $s2Conv10 = (!$s2Abs && $s2Raw50 !== null) ? round(($s2Eff50 / 50.0) * 10.0, 2) : 0.0;

            $seriesHasAny = ($s1Raw50 !== null || $s2Raw50 !== null || $s1Abs || $s2Abs);
            $seriesAvg50 = $seriesHasAny ? round(($s1Eff50 + $s2Eff50) / 2.0, 2) : 0.0;
            $seriesConv10 = $seriesHasAny ? min(10.0, round((($seriesAvg50 / 50.0) * 10.0) * 2) / 2) : 0.0;

            // 6. Total CIA (Internal Mark out of 40 — Rounded off to nearest whole mark)
            // Attendance (5) + CA1 Self-Learning (5) + CE (10) + Practical Tests (10) + Series Exams (10) = 40
            $totalCia40 = min(40.0, round($attMark5 + $slConv5 + $ceConv10 + $pracConv10 + $seriesConv10));

            // 7. ESE (Lab Examination Internal - Max 60 Marks or Board Grade)
            $eseGrade = $eval ? $eval->ese_grade : null;
            $eseAbsent = $eval ? (bool)$eval->ese_absent : false;
            $eseMark60 = ($eval && $eval->ese_mark_60 !== null) ? (float)$eval->ese_mark_60 : null;

            if ($eseMark60 === null && $eseGrade && !$eseAbsent) {
                $eseMark60 = R26EnglishEvaluation::gradeToMarks60($eseGrade);
            }
            $totalEse60 = ($eseMark60 !== null && !$eseAbsent) ? min(60.0, max(0.0, $eseMark60)) : 0.0;

            $totalMarks100 = round(($totalCia40 + $totalEse60) * 2) / 2;

            // Pass Criteria: Min 40% in ESE (24/60) & Min 40% Combined (40/100)
            $isEsePass = ($totalEse60 >= 24.0 || in_array(strtoupper($eseGrade ?? ''), ['S', 'A', 'B', 'C', 'D', 'E', 'P']));
            $isTotalPass = ($totalMarks100 >= 40.0);
            $isPassed = ($isEsePass && $isTotalPass && !$eseAbsent);

            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                // Attendance
                'raw_att_percentage' => $rawAttPercentage,
                'attendance_override_pct' => $overridePct,
                'att_percentage' => $effectivePct,
                'att_mark_5' => $attMark5,
                // CA1: Self Learning (M1..M4 out of 10 -> Avg 10 -> Conv 5)
                'sl_m1_10' => $slM1,
                'sl_m2_10' => $slM2,
                'sl_m3_10' => $slM3,
                'sl_m4_10' => $slM4,
                'sl_avg_10' => $slAvg10,
                'sl_conv_5' => $slConv5,
                // CE: Continuous Evaluation (M1..M4 out of 50 -> Avg 50 -> Conv 10)
                'ce_m1_50' => $ceM1,
                'ce_m2_50' => $ceM2,
                'ce_m3_50' => $ceM3,
                'ce_m4_50' => $ceM4,
                'ce_avg_50' => $ceAvg50,
                'ce_conv_10' => $ceConv10,
                // CA2 & CA3: Practical Tests (Test 1 & Test 2 out of 40 -> Avg 40 -> Conv 10)
                'prac_test1_40' => $pt1Raw40,
                'prac_test1_absent' => $pt1Abs,
                'prac_test1_10' => $pt1Conv10,
                'prac_test2_40' => $pt2Raw40,
                'prac_test2_absent' => $pt2Abs,
                'prac_test2_10' => $pt2Conv10,
                'prac_avg_40' => $pracAvg40,
                'prac_conv_10' => $pracConv10,
                // CA4 & CA5: Series Tests (Series 1 & Series 2 out of 50 -> Avg 50 -> Conv 10)
                'series1_50' => $s1Raw50,
                'series1_absent' => $s1Abs,
                'series1_10' => $s1Conv10,
                'series2_50' => $s2Raw50,
                'series2_absent' => $s2Abs,
                'series2_10' => $s2Conv10,
                'series_avg_50' => $seriesAvg50,
                'series_conv_10' => $seriesConv10,
                // Totals & ESE
                'total_cia_40' => $totalCia40,
                'ese_mark_60' => ($eval && $eval->ese_mark_60 !== null) ? (float)$eval->ese_mark_60 : null,
                'ese_grade' => $eseGrade,
                'ese_absent' => $eseAbsent,
                'total_ese_60' => $totalEse60,
                'total_marks_100' => $totalMarks100,
                'is_passed' => $isPassed,
            ];
        });

        // Assigned Staff & HOD
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function ($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation', 'mobile_no')
            ->first();

        $copoPayload = $englishCourseFile->parsed_copo ?: [];
        $mappings = $copoPayload['mappings'] ?? [
            'CO1' => ['PO1' => '2', 'PO2' => '-', 'PO3' => '-', 'PO4' => '-', 'PO5' => '2', 'PO6' => '-', 'PO7' => '2'],
            'CO2' => ['PO1' => '2', 'PO2' => '2', 'PO3' => '-', 'PO4' => '-', 'PO5' => '2', 'PO6' => '-', 'PO7' => '2'],
            'CO3' => ['PO1' => '-', 'PO2' => '-', 'PO3' => '-', 'PO4' => '-', 'PO5' => '3', 'PO6' => '2', 'PO7' => '3'],
            'CO4' => ['PO1' => '-', 'PO2' => '-', 'PO3' => '-', 'PO4' => '-', 'PO5' => '3', 'PO6' => '2', 'PO7' => '3'],
        ];

        return view('r26_english.virtual_classroom_english', compact(
            'batchSubject',
            'classroom',
            'allEnglishSubjects',
            'students',
            'englishCourseFile',
            'lessonPlans',
            'studentResults',
            'evaluations',
            'assignedStaff',
            'hod',
            'mappings'
        ));
    }

    /**
     * Save Consolidated Evaluation Marks (Debounced Auto-Save & Manual Save from any Tab)
     */
    public function saveConsolidatedMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $request->validate([
            'marks_data' => 'required|array',
        ]);

        $marksData = $request->input('marks_data');

        // Fetch Official TEAMS Attendance & Manual Logs
        $subjectOfficialAttendance = DB::table('subject_official_attendances')
            ->where(function ($query) use ($subjectId, $batchSubject) {
                $query->where('batch_subject_id', $subjectId)
                      ->orWhere(function ($q) use ($batchSubject) {
                          $q->where('subject_code', $batchSubject->subject_code)
                            ->where('classroom_id', $batchSubject->classroom_id);
                      });
            })
            ->get()
            ->keyBy('reg_no');

        $attendanceData = DB::table('student_attendance')
            ->where(function ($q) use ($batchSubject) {
                $q->where('subject_code', $batchSubject->subject_code)
                  ->orWhere('subject_code', 'like', '%' . substr($batchSubject->subject_code, -4) . '%');
            })
            ->get()
            ->groupBy('reg_no');

        $updatedRows = [];

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'] ?? null;
            if (!$regNo) continue;

            // 1. Attendance
            $stOfficial = $subjectOfficialAttendance->get($regNo);
            $staffUploadedPct = null;
            if ($stOfficial) {
                if ($stOfficial->teams_percentage !== null) {
                    $staffUploadedPct = (float)$stOfficial->teams_percentage;
                } elseif ($stOfficial->final_percentage !== null) {
                    $staffUploadedPct = (float)$stOfficial->final_percentage;
                }
            }

            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $logAttPct = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : null;

            $rawAttPct = $staffUploadedPct !== null ? $staffUploadedPct : $logAttPct;

            $overridePct = (isset($row['attendance_override_pct']) && $row['attendance_override_pct'] !== '' && $row['attendance_override_pct'] !== null)
                ? min(100.0, max(0.0, (float)$row['attendance_override_pct']))
                : null;

            $effectivePct = $overridePct !== null ? $overridePct : $rawAttPct;
            $attMark5 = $effectivePct !== null ? R26EnglishEvaluation::calculateAttendanceMarks($effectivePct) : 0.0;

            // 2. CA1: Self Learning (M1..M4 each out of 10 -> Avg out of 10 -> Converted to 5)
            $slM1 = (isset($row['sl_m1_10']) && $row['sl_m1_10'] !== '' && $row['sl_m1_10'] !== null) ? min(10.0, max(0.0, (float)$row['sl_m1_10'])) : null;
            $slM2 = (isset($row['sl_m2_10']) && $row['sl_m2_10'] !== '' && $row['sl_m2_10'] !== null) ? min(10.0, max(0.0, (float)$row['sl_m2_10'])) : null;
            $slM3 = (isset($row['sl_m3_10']) && $row['sl_m3_10'] !== '' && $row['sl_m3_10'] !== null) ? min(10.0, max(0.0, (float)$row['sl_m3_10'])) : null;
            $slM4 = (isset($row['sl_m4_10']) && $row['sl_m4_10'] !== '' && $row['sl_m4_10'] !== null) ? min(10.0, max(0.0, (float)$row['sl_m4_10'])) : null;

            $slHasAny = ($slM1 !== null || $slM2 !== null || $slM3 !== null || $slM4 !== null);
            $slSum = ($slM1 ?? 0.0) + ($slM2 ?? 0.0) + ($slM3 ?? 0.0) + ($slM4 ?? 0.0);
            $slAvg10 = $slHasAny ? round($slSum / 4.0, 2) : 0.0;
            $slConv5 = $slHasAny ? min(5.0, round((($slAvg10 / 10.0) * 5.0) * 2) / 2) : 0.0;

            // 3. CE: Continuous Evaluation (M1..M4 each out of 50 -> Avg out of 50 -> Converted to 10)
            $ceM1 = (isset($row['ce_m1_50']) && $row['ce_m1_50'] !== '' && $row['ce_m1_50'] !== null) ? min(50.0, max(0.0, (float)$row['ce_m1_50'])) : null;
            $ceM2 = (isset($row['ce_m2_50']) && $row['ce_m2_50'] !== '' && $row['ce_m2_50'] !== null) ? min(50.0, max(0.0, (float)$row['ce_m2_50'])) : null;
            $ceM3 = (isset($row['ce_m3_50']) && $row['ce_m3_50'] !== '' && $row['ce_m3_50'] !== null) ? min(50.0, max(0.0, (float)$row['ce_m3_50'])) : null;
            $ceM4 = (isset($row['ce_m4_50']) && $row['ce_m4_50'] !== '' && $row['ce_m4_50'] !== null) ? min(50.0, max(0.0, (float)$row['ce_m4_50'])) : null;

            $ceHasAny = ($ceM1 !== null || $ceM2 !== null || $ceM3 !== null || $ceM4 !== null);
            $ceSum = ($ceM1 ?? 0.0) + ($ceM2 ?? 0.0) + ($ceM3 ?? 0.0) + ($ceM4 ?? 0.0);
            $ceAvg50 = $ceHasAny ? round($ceSum / 4.0, 2) : 0.0;
            $ceConv10 = $ceHasAny ? min(10.0, round((($ceAvg50 / 50.0) * 10.0) * 2) / 2) : 0.0;

            // 4. CA2 & CA3: Practical Tests (Test 1 & Test 2 each out of 40 -> Avg 40 -> Converted to 10)
            $pt1Abs = !empty($row['prac_test1_absent']);
            $pt2Abs = !empty($row['prac_test2_absent']);
            $pt1Raw40 = (isset($row['prac_test1_40']) && $row['prac_test1_40'] !== '' && $row['prac_test1_40'] !== null)
                ? min(40.0, max(0.0, (float)$row['prac_test1_40']))
                : null;
            $pt2Raw40 = (isset($row['prac_test2_40']) && $row['prac_test2_40'] !== '' && $row['prac_test2_40'] !== null)
                ? min(40.0, max(0.0, (float)$row['prac_test2_40']))
                : null;

            $pt1Eff40 = (!$pt1Abs && $pt1Raw40 !== null) ? $pt1Raw40 : 0.0;
            $pt2Eff40 = (!$pt2Abs && $pt2Raw40 !== null) ? $pt2Raw40 : 0.0;

            $pt1Conv10 = (!$pt1Abs && $pt1Raw40 !== null) ? round(($pt1Eff40 / 40.0) * 10.0, 2) : 0.0;
            $pt2Conv10 = (!$pt2Abs && $pt2Raw40 !== null) ? round(($pt2Eff40 / 40.0) * 10.0, 2) : 0.0;

            $ptHasAny = ($pt1Raw40 !== null || $pt2Raw40 !== null || $pt1Abs || $pt2Abs);
            $pracAvg40 = $ptHasAny ? round(($pt1Eff40 + $pt2Eff40) / 2.0, 2) : 0.0;
            $pracConv10 = $ptHasAny ? min(10.0, round((($pracAvg40 / 40.0) * 10.0) * 2) / 2) : 0.0;

            // 5. CA4 & CA5: Series Exams (Series 1 & Series 2 each out of 50 -> Avg 50 -> Converted to 10)
            $s1Abs = !empty($row['series1_absent']);
            $s2Abs = !empty($row['series2_absent']);
            $s1Raw50 = (isset($row['series1_50']) && $row['series1_50'] !== '' && $row['series1_50'] !== null)
                ? min(50.0, max(0.0, (float)$row['series1_50']))
                : null;
            $s2Raw50 = (isset($row['series2_50']) && $row['series2_50'] !== '' && $row['series2_50'] !== null)
                ? min(50.0, max(0.0, (float)$row['series2_50']))
                : null;

            $s1Eff50 = (!$s1Abs && $s1Raw50 !== null) ? $s1Raw50 : 0.0;
            $s2Eff50 = (!$s2Abs && $s2Raw50 !== null) ? $s2Raw50 : 0.0;

            $s1Conv10 = (!$s1Abs && $s1Raw50 !== null) ? round(($s1Eff50 / 50.0) * 10.0, 2) : 0.0;
            $s2Conv10 = (!$s2Abs && $s2Raw50 !== null) ? round(($s2Eff50 / 50.0) * 10.0, 2) : 0.0;

            $seriesHasAny = ($s1Raw50 !== null || $s2Raw50 !== null || $s1Abs || $s2Abs);
            $seriesAvg50 = $seriesHasAny ? round(($s1Eff50 + $s2Eff50) / 2.0, 2) : 0.0;
            $seriesConv10 = $seriesHasAny ? min(10.0, round((($seriesAvg50 / 50.0) * 10.0) * 2) / 2) : 0.0;

            // 6. Total CIA (Max 40 — Rounded off to nearest whole mark)
            $totalCia40 = min(40.0, round($attMark5 + $slConv5 + $ceConv10 + $pracConv10 + $seriesConv10));

            // 7. ESE (Max 60 + Grade)
            $eseGrade = isset($row['ese_grade']) && trim($row['ese_grade']) !== '' ? strtoupper(trim($row['ese_grade'])) : null;
            $eseAbsent = !empty($row['ese_absent']) || ($eseGrade === 'AB' || $eseGrade === 'ABSENT');
            $eseMark60 = (isset($row['ese_mark_60']) && $row['ese_mark_60'] !== '' && $row['ese_mark_60'] !== null)
                ? min(60.0, max(0.0, (float)$row['ese_mark_60']))
                : null;

            $effectiveEse60 = $eseMark60;
            if ($effectiveEse60 === null && $eseGrade && !$eseAbsent) {
                $effectiveEse60 = R26EnglishEvaluation::gradeToMarks60($eseGrade);
            }
            $totalEse60 = ($effectiveEse60 !== null && !$eseAbsent) ? $effectiveEse60 : 0.0;
            $totalMarks100 = round(($totalCia40 + $totalEse60) * 2) / 2;

            $isEsePass = ($totalEse60 >= 24.0 || in_array($eseGrade ?? '', ['S', 'A', 'B', 'C', 'D', 'E', 'P']));
            $isTotalPass = ($totalMarks100 >= 40.0);
            $isPassed = ($isEsePass && $isTotalPass && !$eseAbsent);

            R26EnglishEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo,
                ],
                [
                    'attendance_pct' => $rawAttPct,
                    'attendance_override_pct' => $overridePct,
                    'attendance_mark_5' => $attMark5,
                    'sl_m1_10' => $slM1,
                    'sl_m2_10' => $slM2,
                    'sl_m3_10' => $slM3,
                    'sl_m4_10' => $slM4,
                    'sl_avg_10' => $slAvg10,
                    'sl_conv_5' => $slConv5,
                    'ce_m1_50' => $ceM1,
                    'ce_m2_50' => $ceM2,
                    'ce_m3_50' => $ceM3,
                    'ce_m4_50' => $ceM4,
                    'ce_avg_50' => $ceAvg50,
                    'ce_conv_10' => $ceConv10,
                    'prac_test1_40' => $pt1Raw40,
                    'prac_test1_absent' => $pt1Abs,
                    'prac_test1_10' => $pt1Conv10,
                    'prac_test2_40' => $pt2Raw40,
                    'prac_test2_absent' => $pt2Abs,
                    'prac_test2_10' => $pt2Conv10,
                    'prac_avg_40' => $pracAvg40,
                    'prac_conv_10' => $pracConv10,
                    'series1_50' => $s1Raw50,
                    'series1_absent' => $s1Abs,
                    'series1_10' => $s1Conv10,
                    'series2_50' => $s2Raw50,
                    'series2_absent' => $s2Abs,
                    'series2_10' => $s2Conv10,
                    'series_avg_50' => $seriesAvg50,
                    'series_conv_10' => $seriesConv10,
                    'total_cia_40' => $totalCia40,
                    'ese_mark_60' => $eseMark60,
                    'ese_grade' => $eseGrade,
                    'ese_absent' => $eseAbsent,
                    'total_marks_100' => $totalMarks100,
                    'is_passed' => $isPassed,
                    'updated_by' => (string)$userId,
                ]
            );

            $updatedRows[$regNo] = [
                'effective_pct' => $effectivePct,
                'att_mark_5' => $attMark5,
                'sl_avg_10' => $slAvg10,
                'sl_conv_5' => $slConv5,
                'ce_avg_50' => $ceAvg50,
                'ce_conv_10' => $ceConv10,
                'prac_test1_10' => $pt1Conv10,
                'prac_test2_10' => $pt2Conv10,
                'prac_avg_40' => $pracAvg40,
                'prac_conv_10' => $pracConv10,
                'series1_10' => $s1Conv10,
                'series2_10' => $s2Conv10,
                'series_avg_50' => $seriesAvg50,
                'series_conv_10' => $seriesConv10,
                'total_cia_40' => $totalCia40,
                'total_marks_100' => $totalMarks100,
                'is_passed' => $isPassed,
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'English (1001) evaluation marks saved successfully!',
            'data' => $updatedRows,
        ]);
    }

    /**
     * Upload Syllabus PDF for R26 English (1001)
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $file = $request->file('syllabus_file');
            $path = $file->store('r26_english_syllabi', 'public');

            R26EnglishCourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'course_title' => $batchSubject->subject_name ?: 'English for Communication',
                    'course_code' => $batchSubject->subject_code ?: '1001',
                ]
            );

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'English (1001) Syllabus PDF uploaded successfully!',
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Save Course Outline & CO-PO Matrix for R26 English (1001)
     */
    public function saveCourseOutline(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $englishCourseFile = R26EnglishCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        if ($request->has('parsed_cos')) {
            $englishCourseFile->parsed_cos = $request->input('parsed_cos');
        }
        if ($request->has('parsed_modules')) {
            $englishCourseFile->parsed_modules = $request->input('parsed_modules');
        }
        if ($request->has('parsed_exercises')) {
            $englishCourseFile->parsed_exercises = $request->input('parsed_exercises');
        }
        if ($request->has('parsed_textbooks')) {
            $englishCourseFile->parsed_textbooks = $request->input('parsed_textbooks');
        }
        if ($request->has('mappings')) {
            $copo = $englishCourseFile->parsed_copo ?: [];
            $copo['mappings'] = $request->input('mappings');
            $englishCourseFile->parsed_copo = $copo;
        }

        $englishCourseFile->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Course Outline saved successfully!',
        ]);
    }

    /**
     * Bulk Save Lesson Planner Rows for R26 English (1001)
     */
    public function bulkUpdateLessonPlans(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $plans = $request->input('plans', []);
        $dayNoCounter = 1;

        foreach ($plans as $id => $data) {
            $topic = trim($data['topic_content'] ?? '');
            if (empty($topic)) {
                if (is_numeric($id) && intval($id) > 0) {
                    R26EnglishLessonPlan::where('id', $id)->where('batch_subject_id', $subjectId)->delete();
                }
                continue;
            }

            $actualDate = !empty($data['actual_date']) ? $data['actual_date'] : null;
            $status = $data['status'] ?? 'Pending';
            if ($actualDate && $status === 'Pending') {
                $status = 'Completed';
            }

            $payload = [
                'batch_subject_id' => $subjectId,
                'day_no' => intval($data['day_no'] ?? $dayNoCounter),
                'proposed_date' => !empty($data['proposed_date']) ? $data['proposed_date'] : null,
                'actual_date' => $actualDate,
                'topic_content' => $topic,
                'module_id' => $data['module_id'] ?? 'M1',
                'co_tag' => $data['co_tag'] ?? 'CO1',
                'allocated_hours' => intval($data['allocated_hours'] ?? 1),
                'mode' => $data['mode'] ?? 'L',
                'pedagogy' => $data['pedagogy'] ?? 'Interactive Lecture & Practicum',
                'status' => $status,
            ];

            if (is_numeric($id) && intval($id) > 0) {
                R26EnglishLessonPlan::where('id', $id)->where('batch_subject_id', $subjectId)->update($payload);
            } else {
                R26EnglishLessonPlan::create($payload);
            }
            $dayNoCounter++;
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'English (1001) Lesson Planner saved successfully!',
        ]);
    }

    /**
     * Generate / Reset Default 60-Hour Lesson Plan for R26 English (1001)
     */
    public function generateLessonPlanApi(Request $request, $subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $englishCourseFile = R26EnglishCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        $this->generateDefaultLessonPlan($batchSubject, $englishCourseFile);

        $totalRows = R26EnglishLessonPlan::where('batch_subject_id', $subjectId)->count();
        return response()->json([
            'status' => 'SUCCESS',
            'message' => "English (1001) Lesson Planner generated with {$totalRows} sessions!",
        ]);
    }

    /**
     * Print Official CIA Register for R26 English (1001)
     */
    public function printCiaRegister($subjectId)
    {
        return $this->printReport($subjectId, 'cia-register');
    }

    /**
     * Print Specific Report Type for R26 English (1001):
     *   - cia-register: CIA (Internal Mark Consolidated 40M)
     *   - consolidated-marks: Consolidated Marks (CIA 40M + ESE 60M = Total 100M)
     *   - attendance: Attendance Percentage & Marks Report (5M)
     *   - self-learning: CA1 Self-Learning Marks Report (M1-M4 out of 10 -> 5M)
     *   - ce-marks: CE Continuous Evaluation Report (M1-M4 out of 50 -> 10M)
     *   - practical-test: CA2 & CA3 Practical Test Marks Report (Test 1 & 2 out of 40 -> 10M)
     *   - series-test: CA4 & CA5 Series Exam Marks Report (Series 1 & 2 out of 50 -> 10M)
     */
    public function printReport($subjectId, $reportType = 'cia-register')
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?? ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $englishCourseFile = R26EnglishCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $evaluations = R26EnglishEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        // Also fetch official TEAMS attendance and log attendance as fallback if evaluation row not yet saved
        $subjectOfficialAttendance = DB::table('subject_official_attendances')
            ->where(function ($query) use ($subjectId, $batchSubject) {
                $query->where('batch_subject_id', $subjectId)
                      ->orWhere(function ($q) use ($batchSubject) {
                          $q->where('subject_code', $batchSubject->subject_code)
                            ->where('classroom_id', $batchSubject->classroom_id);
                      });
            })
            ->get()
            ->keyBy('reg_no');

        $attendanceData = DB::table('student_attendance')
            ->where(function ($q) use ($batchSubject) {
                $q->where('subject_code', $batchSubject->subject_code)
                  ->orWhere('subject_code', 'like', '%' . substr($batchSubject->subject_code, -4) . '%');
            })
            ->get()
            ->groupBy('reg_no');

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function ($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation')
            ->first();

        $academicCtx = $this->resolveDepartmentAndAcademicContext($classroom, $batchSubject);

        return view('r26_english.cia_register_print', array_merge(compact(
            'batchSubject',
            'classroom',
            'englishCourseFile',
            'students',
            'evaluations',
            'subjectOfficialAttendance',
            'attendanceData',
            'assignedStaff',
            'hod',
            'reportType'
        ), $academicCtx));
    }

    /**
     * Print Official Lesson Plan for R26 English (1001)
     */
    public function printLessonPlan($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?? ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $englishCourseFile = R26EnglishCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $lessonPlans = R26EnglishLessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no', 'asc')->get();

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function ($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation')
            ->first();

        $academicCtx = $this->resolveDepartmentAndAcademicContext($classroom, $batchSubject);

        return view('r26_english.lesson_plan_print', array_merge(compact(
            'batchSubject',
            'classroom',
            'englishCourseFile',
            'lessonPlans',
            'assignedStaff',
            'hod'
        ), $academicCtx));
    }

    /**
     * Default Lesson Plan Generator for English for Communication (1001)
     * Incorporates Module I–IV Theory + Language Lab Practicum + CA1/CE/CA2/CA3/CA4/CA5 Schedule
     */
    private function generateDefaultLessonPlan($batchSubject, $englishCourseFile)
    {
        R26EnglishLessonPlan::where('batch_subject_id', $batchSubject->id)->delete();

        $sessions = [
            // Module I (Weeks 1-4)
            ['mod' => 'M1', 'co' => 'CO1', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module I: Introduction to Communication Process, Channels & Barriers to Communication'],
            ['mod' => 'M1', 'co' => 'CO1', 'hrs' => 2, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-01: Organs of Speech, Vowel & Consonant Sounds (IPA Symbols)'],
            ['mod' => 'M1', 'co' => 'CO1', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module I: Functional Grammar — Parts of Speech, Tenses & Concord (Subject-Verb Agreement)'],
            ['mod' => 'M1', 'co' => 'CO1', 'hrs' => 2, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-01: Word Stress, Sentence Stress & Intonation Patterns in English'],
            ['mod' => 'M1', 'co' => 'CO1', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module I: Active & Passive Voice, Direct & Indirect Speech in Technical Contexts'],
            ['mod' => 'M1', 'co' => 'CO1', 'hrs' => 2, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-02: Functional Grammar Lab — Common Errors & Sentence Correction'],
            ['mod' => 'M1', 'co' => 'CO1', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Self-Learning Review (CA1)', 'topic' => 'CA1 Self-Learning Assessment (Module I) & Continuous Evaluation (CE - M1)'],

            // Module II (Weeks 5-8)
            ['mod' => 'M2', 'co' => 'CO2', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module II: Reading Strategies — Skimming, Scanning, Intensive & Extensive Reading'],
            ['mod' => 'M2', 'co' => 'CO2', 'hrs' => 2, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-03: Active Listening Lab — Listening to Technical Lectures & Note-Taking'],
            ['mod' => 'M2', 'co' => 'CO2', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module II: Vocabulary Enhancement — Word Formation, Prefixes, Suffixes, Synonyms & Antonyms'],
            ['mod' => 'M2', 'co' => 'CO2', 'hrs' => 2, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-04: Reading Comprehension of Technical Passages & Data Interpretation'],
            ['mod' => 'M2', 'co' => 'CO2', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Continuous Evaluation (CE)', 'topic' => 'CA1 Self-Learning Assessment (Module II) & Continuous Evaluation (CE - M2) [By 7th Week]'],
            ['mod' => 'M2', 'co' => 'CO2', 'hrs' => 2, 'mode' => 'ST', 'ped' => 'Series Exam 1 (CA4)', 'topic' => 'CA4: SERIES EXAM 1 (Modules I & II — 2 Hours, Max 50 Marks) [8th Week]'],

            // Module III (Weeks 9-11)
            ['mod' => 'M3', 'co' => 'CO3', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module III: Oral Communication — Principles of Effective Speaking, Non-Verbal Cues & Body Language'],
            ['mod' => 'M3', 'co' => 'CO3', 'hrs' => 3, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-05: Self-Introduction, JAM (Just A Minute) & Workplace Role Plays'],
            ['mod' => 'M3', 'co' => 'CO3', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module III: Group Discussion Dynamics, Etiquette & Technical Presentation Planning'],
            ['mod' => 'M3', 'co' => 'CO3', 'hrs' => 3, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-06: Group Discussion & Individual Oral Presentation with Visual Aids'],
            ['mod' => 'M3', 'co' => 'CO3', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Self-Learning & CE (M3)', 'topic' => 'CA1 Self-Learning Assessment (Module III) & Continuous Evaluation (CE - M3)'],
            ['mod' => 'M3', 'co' => 'CO3', 'hrs' => 3, 'mode' => 'PT', 'ped' => 'Practical Test 1 (CA2)', 'topic' => 'CA2: PRACTICAL TEST 1 (First Half of Experiments — 3 Hours, Max 40 Marks) [By 11th Week]'],

            // Module IV (Weeks 12-15)
            ['mod' => 'M4', 'co' => 'CO4', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module IV: Professional Writing — Formal Letters, Business Correspondence & Email Etiquette'],
            ['mod' => 'M4', 'co' => 'CO4', 'hrs' => 3, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-07: Drafting Formal Letters, Professional Emails, Job Application & Resume'],
            ['mod' => 'M4', 'co' => 'CO4', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Interactive Lecture (L)', 'topic' => 'Module IV: Technical Report Writing — Structure, Abstract, Agenda & Minutes of Meeting'],
            ['mod' => 'M4', 'co' => 'CO4', 'hrs' => 3, 'mode' => 'P', 'ped' => 'Language Lab Practicum (P)', 'topic' => 'Practicum EXP-08: Technical Report Preparation & Mock Interview Practicum'],
            ['mod' => 'M4', 'co' => 'CO4', 'hrs' => 2, 'mode' => 'L', 'ped' => 'Self-Learning & CE (M4)', 'topic' => 'CA1 Self-Learning Assessment (Module IV) & Continuous Evaluation (CE - M4)'],
            ['mod' => 'M4', 'co' => 'CO4', 'hrs' => 3, 'mode' => 'PT', 'ped' => 'Practical Test 2 (CA3)', 'topic' => 'CA3: PRACTICAL TEST 2 (Second Half of Experiments — 3 Hours, Max 40 Marks) [By 14th Week]'],
            ['mod' => 'M4', 'co' => 'CO4', 'hrs' => 2, 'mode' => 'ST', 'ped' => 'Series Exam 2 (CA5)', 'topic' => 'CA5: SERIES EXAM 2 (Modules III & IV — 2 Hours, Max 50 Marks) [15th Week]'],
        ];

        $day = 1;
        foreach ($sessions as $s) {
            R26EnglishLessonPlan::create([
                'batch_subject_id' => $batchSubject->id,
                'day_no' => $day,
                'proposed_date' => now()->addDays($day * 2)->toDateString(),
                'actual_date' => null,
                'topic_content' => $s['topic'],
                'module_id' => $s['mod'],
                'co_tag' => $s['co'],
                'allocated_hours' => $s['hrs'],
                'mode' => $s['mode'],
                'pedagogy' => $s['ped'],
                'status' => 'Pending',
            ]);
            $day++;
        }
    }

    /**
     * Resolve Department Name and Academic Context for Printable Headers
     */
    private function resolveDepartmentAndAcademicContext($classroom, $batchSubject): array
    {
        $deptCode = strtoupper($classroom->department ?? $classroom->branch ?? '');
        if (!$deptCode && !empty($batchSubject->classroom_id)) {
            $deptCode = strtoupper(explode('_', $batchSubject->classroom_id)[0]);
        }

        $branchMap = [
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'EL' => 'Electronics Engineering',
            'CT' => 'Computer Engineering',
            'CS' => 'Computer Engineering',
            'AU' => 'Automobile Engineering',
        ];

        $departmentName = $branchMap[$deptCode] ?? ($deptCode ?: 'General Engineering');
        $departmentFullName = 'DEPARTMENT OF ' . strtoupper($departmentName);

        return [
            'departmentName' => $departmentName,
            'departmentFullName' => $departmentFullName,
            'semester' => $batchSubject->semester ?? 1,
            'assessmentYear' => '2026 - 2027',
            'reportDate' => date('d-m-Y'),
        ];
    }
}
