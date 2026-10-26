<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\Student;
use App\Models\CourseFile;
use App\Models\LessonPlan;
use App\Models\R26CourseFile;
use App\Models\R26CourseFileDocument;
use App\Models\R26TheoryEvaluation;

class R26ClassroomController extends Controller
{
    /**
     * Show / View Virtual Classroom (Theory) for Revision 2026.
     */
    public function show($subjectId)
    {
        return $this->viewTheoryClassroom($subjectId);
    }

    /**
     * View Virtual Classroom (Theory) for Revision 2026.
     */
    public function viewTheoryClassroom($subjectId)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Dedicated redirection for Revision 2026 English (1001)
        if ($batchSubject->syllabus_revision_code === 'REV2026' && (str_contains($batchSubject->subject_code ?? '', '1001') || stripos($batchSubject->subject_name ?? '', 'English') !== false)) {
            return redirect('/r26/classroom/english-1001/' . $subjectId);
        }

        // Fetch classroom (check R26 table first, then fallback to standard)
        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Get enrolled students
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Course file data
        $courseFile = CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'parsed_cos' => json_encode([
                    ['id' => 'CO1', 'description' => 'Understand and explain the core concepts of the subject.', 'duration' => 15, 'cognitive_level' => 'Understanding'],
                    ['id' => 'CO2', 'description' => 'Apply theoretical methodologies to solve problems.', 'duration' => 15, 'cognitive_level' => 'Applying'],
                    ['id' => 'CO3', 'description' => 'Analyze systems and evaluate outcomes.', 'duration' => 15, 'cognitive_level' => 'Analyzing'],
                    ['id' => 'CO4', 'description' => 'Formulate, design, or optimize solutions.', 'duration' => 15, 'cognitive_level' => 'Creating']
                ]),
                'parsed_modules' => json_encode([
                    ['module_id' => 'I', 'content' => 'Module 1 Course Contents'],
                    ['module_id' => 'II', 'content' => 'Module 2 Course Contents'],
                    ['module_id' => 'III', 'content' => 'Module 3 Course Contents'],
                    ['module_id' => 'IV', 'content' => 'Module 4 Course Contents']
                ]),
                'parsed_textbooks' => json_encode([
                    'Textbook Reference 1',
                    'Textbook Reference 2'
                ])
            ]
        );

        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        // Compute student attendance & marks
        $attendanceData = \DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Fetch authoritative TEAMS uploaded attendance records
        $subjectOfficialAttendance = \DB::table('subject_official_attendances')
            ->where(function ($query) use ($subjectId, $batchSubject) {
                $query->where('batch_subject_id', $subjectId)
                      ->orWhere(function ($q) use ($batchSubject) {
                          $q->where('subject_code', $batchSubject->subject_code)
                            ->where('classroom_id', $batchSubject->classroom_id);
                      });
            })
            ->get()
            ->keyBy('reg_no');

        $hasStaffUpload = $subjectOfficialAttendance->isNotEmpty();
        $hasManualLog = $attendanceData->isNotEmpty();
        $hasSubjectAttendance = $hasStaffUpload || $hasManualLog;

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Check if Series 1 or Series 2 marks exist in academic_marks
        $allSubjectAcademicMarks = $academicMarks->flatten();
        $hasS1InAcademicMarks = $allSubjectAcademicMarks->filter(function($m) {
            return in_array($m->category, ['Series Exam: CO1 Test', 'Series Exam: CO2 Test', 'Series Exam: Series Exam 1 (CO1+CO2)', 'Series Exam 1']) && (float)$m->marks_obtained > 0;
        })->isNotEmpty();

        $hasS2InAcademicMarks = $allSubjectAcademicMarks->filter(function($m) {
            return in_array($m->category, ['Series Exam: CO3 Test', 'Series Exam: CO4 Test', 'Series Exam: Series Exam 2 (CO3+CO4)', 'Series Exam 2']) && (float)$m->marks_obtained > 0;
        })->isNotEmpty();

        // Self-Learning Configurations
        $selfLearningConfigs = $courseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO2' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO3' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO4' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
        ];

        // Fetch MCQ Test Configurations & Attempts for Automation
        $testConfigs = \DB::table('test_configs')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('classroom_id', $batchSubject->classroom_id)
            ->get();
            
        $testIds = $testConfigs->pluck('test_id')->toArray();
        
        $testAttempts = \DB::table('test_attempts')
            ->whereIn('test_id', $testIds)
            ->where('status', 'Completed')
            ->get()
            ->groupBy('reg_no');

        $submissions = \DB::table('student_task_submissions')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('category', 'Assignment')
            ->get()
            ->groupBy('reg_no');

        $seriesExams = \App\Models\SeriesExam::where('batch_subject_id', $subjectId)->get();

        $boardGrades = \DB::table('student_board_grades')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->keyBy('reg_no');

        // Fetch Consolidated Evaluations for Revision 2026 Theory Course
        $evaluations = R26TheoryEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        $studentCiaData = $students->map(function ($student) use ($attendanceData, $subjectOfficialAttendance, $academicMarks, $hasS1InAcademicMarks, $hasS2InAcademicMarks, $subjectId, $batchSubject, $selfLearningConfigs, $testConfigs, $testAttempts, $submissions, $seriesExams, $boardGrades, $evaluations) {
            $regNo = $student->reg_no;
            $eval = $evaluations->get($regNo);

            $studentSubmissions = $submissions->get($regNo, collect());
            $studentAttendance = $attendanceData->get($regNo, collect());
            $totalAttendance = $studentAttendance->count();
            $present = $studentAttendance->whereIn('status', ['Present', 'Late'])->count();
            $logAttPct = $totalAttendance > 0 ? round(($present / $totalAttendance) * 100, 2) : null;

            // STRICT RULE: Only use subject attendance uploaded by concerned staff (subject_official_attendances)
            // or manual attendance logs for this subject.
            // Tutor class attendance must NEVER be used for subjects or attendance mark calculations.
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

            // Hierarchy: 1. Staff uploaded Teams subject % -> 2. Manual attendance log % -> 3. null (prompt missing)
            $rawAttPercentage = $staffUploadedPct !== null ? $staffUploadedPct : $logAttPct;
            
            // 1. Attendance Marks (Max 5) with Override support
            $overridePct = null;
            if ($eval && $eval->attendance_override_pct !== null) {
                $overridePct = (float)$eval->attendance_override_pct;
            } elseif ($stOfficial && $stOfficial->override_percentage !== null) {
                $overridePct = (float)$stOfficial->override_percentage;
            }

            $effectivePct = $overridePct !== null ? $overridePct : $rawAttPercentage;
            $attMarks = $effectivePct !== null ? R26TheoryEvaluation::calculateAttendanceMarks($effectivePct) : 0.0;

            // Attendance eligibility & condonation possibilities
            $attStatus = 'Not Entered';
            $attColor = 'slate-400';
            if ($effectivePct !== null) {
                if ($effectivePct >= 75) {
                    $attStatus = 'Eligible';
                    $attColor = 'emerald-450';
                } elseif ($effectivePct >= 60) {
                    $attStatus = 'Condonation Possible';
                    $attColor = 'amber-500';
                } elseif ($effectivePct >= 50) {
                    $attStatus = 'Special Condonation';
                    $attColor = 'purple-400';
                } else {
                    $attStatus = 'Shortage (Detained)';
                    $attColor = 'rose-500';
                }
            }
            
            $studentMarks = $academicMarks->get($regNo, collect());
            $studentAttempts = $testAttempts->get($regNo, collect());
            
            $coDetails = [];
            $totalAvgSum = 0.0;
            
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $coMarks = $studentMarks->where('co_tag', $coTag);
                
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;

                // MCQ Automated Mark logic
                if ($mcqMark) {
                    $valMcq = (float)$mcqMark->marks_obtained;
                } else {
                    $valMcq = 0.0;
                    $coTest = $testConfigs->filter(function($tc) use ($coTag) {
                        $selected = json_decode($tc->selected_cos, true) ?: [];
                        return in_array($coTag, $selected);
                    })->first();
                    if ($coTest) {
                        $attemptsForTest = $studentAttempts->where('test_id', $coTest->test_id);
                        $maxScore = $attemptsForTest->max('total_score');
                        $mcqMax = (float)($coTest->mcq_count ?: 10);
                        $configMaxMCQ = (float)($selfLearningConfigs[$coTag]['mcq'] ?? 5.0);
                        if ($mcqMax > 0 && $maxScore !== null) {
                            $valMcq = round(($maxScore / $mcqMax) * $configMaxMCQ, 2);
                        }
                    }
                }
                
                $coTotal = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                $totalAvgSum += $coTotal;
                
                $subRecord = $studentSubmissions->where('co_tag', $coTag)->first();
                $subStatus = $subRecord ? $subRecord->status : 'Not Assigned';

                $coDetails[$coTag] = [
                    'assignment' => $valAssignment,
                    'mcq' => $valMcq,
                    'act3' => $valAct3,
                    'act4' => $valAct4,
                    'act5' => $valAct5,
                    'total' => $coTotal,
                    'submission_status' => $subStatus
                ];
            }
            
            // 2. Self Learning CA1 - CA4 (Max 15 each)
            $ca1 = ($eval && $eval->ca1_15 !== null) ? (float)$eval->ca1_15 : ($coDetails['CO1']['total'] > 0 ? (float)$coDetails['CO1']['total'] : null);
            $ca2 = ($eval && $eval->ca2_15 !== null) ? (float)$eval->ca2_15 : ($coDetails['CO2']['total'] > 0 ? (float)$coDetails['CO2']['total'] : null);
            $ca3 = ($eval && $eval->ca3_15 !== null) ? (float)$eval->ca3_15 : ($coDetails['CO3']['total'] > 0 ? (float)$coDetails['CO3']['total'] : null);
            $ca4 = ($eval && $eval->ca4_15 !== null) ? (float)$eval->ca4_15 : ($coDetails['CO4']['total'] > 0 ? (float)$coDetails['CO4']['total'] : null);

            $caEntered = [];
            if ($ca1 !== null) $caEntered[] = $ca1;
            if ($ca2 !== null) $caEntered[] = $ca2;
            if ($ca3 !== null) $caEntered[] = $ca3;
            if ($ca4 !== null) $caEntered[] = $ca4;

            if ($eval && $eval->self_learning_15 !== null) {
                $selfLearning15 = (float)$eval->self_learning_15;
            } elseif (count($caEntered) > 0) {
                $selfLearning15 = round((array_sum($caEntered) / 4.0) * 2) / 2;
            } else {
                $selfLearning15 = round($totalAvgSum / 4, 2);
            }
            if ($selfLearning15 > 15.0) $selfLearning15 = 15.0;

            // 3. Series Exams (Series 1: CO1 [25] + CO2 [25] = 50 -> 20; Series 2: CO3 [25] + CO4 [25] = 50 -> 20)
            $s1Absent = $eval ? (bool)$eval->series_1_absent : false;
            $s2Absent = $eval ? (bool)$eval->series_2_absent : false;

            $s1_co1 = $eval && $eval->series_1_co1_25 !== null ? (float)$eval->series_1_co1_25 : null;
            $s1_co2 = $eval && $eval->series_1_co2_25 !== null ? (float)$eval->series_1_co2_25 : null;
            $s2_co3 = $eval && $eval->series_2_co3_25 !== null ? (float)$eval->series_2_co3_25 : null;
            $s2_co4 = $eval && $eval->series_2_co4_25 !== null ? (float)$eval->series_2_co4_25 : null;
            $s1_50 = $eval && $eval->series_1_50 !== null ? (float)$eval->series_1_50 : null;
            $s2_50 = $eval && $eval->series_2_50 !== null ? (float)$eval->series_2_50 : null;

            // Fallback to academic_marks if r26_theory_evaluations does not have Series 1 values
            if ($s1_co1 === null && $s1_co2 === null && $s1_50 === null) {
                $mCo1 = $studentMarks->filter(function($m) {
                    return $m->category === 'Series Exam: CO1 Test' 
                        || $m->category === 'Series Exam: CO1' 
                        || ($m->co_tag === 'CO1' && str_starts_with($m->category, 'Series'));
                })->first();

                $mCo2 = $studentMarks->filter(function($m) {
                    return $m->category === 'Series Exam: CO2 Test' 
                        || $m->category === 'Series Exam: CO2' 
                        || ($m->co_tag === 'CO2' && str_starts_with($m->category, 'Series'));
                })->first();

                if ($mCo1 || $mCo2) {
                    $s1_co1 = $mCo1 ? (float)$mCo1->marks_obtained : null;
                    $s1_co2 = $mCo2 ? (float)$mCo2->marks_obtained : null;
                    if ($s1_co1 !== null || $s1_co2 !== null) {
                        $s1_50 = min(50.0, ($s1_co1 ?? 0) + ($s1_co2 ?? 0));
                    }
                } else {
                    $mS1 = $studentMarks->filter(function($m) {
                        return str_contains($m->category, 'Series Exam 1') 
                            || str_contains($m->category, 'Series 1')
                            || ($m->max_marks == 50 && (str_contains($m->category, 'Series') || str_contains($m->category, 'Test 1')));
                    })->first();
                    if ($mS1) {
                        $s1_50 = min(50.0, (float)$mS1->marks_obtained);
                        $s1_co1 = round(($s1_50 / 2.0) * 2) / 2;
                        $s1_co2 = round(($s1_50 / 2.0) * 2) / 2;
                    }
                }
            } elseif ($s1_50 !== null && ($s1_co1 === null || $s1_co2 === null)) {
                $s1_co1 = $s1_co1 ?? round(($s1_50 / 2.0) * 2) / 2;
                $s1_co2 = $s1_co2 ?? round(($s1_50 / 2.0) * 2) / 2;
            } elseif (($s1_co1 !== null || $s1_co2 !== null) && $s1_50 === null) {
                $s1_50 = min(50.0, ($s1_co1 ?? 0) + ($s1_co2 ?? 0));
            }

            // Fallback to academic_marks if r26_theory_evaluations does not have Series 2 values
            if ($s2_co3 === null && $s2_co4 === null && $s2_50 === null) {
                if ($hasS2InAcademicMarks) {
                    $mCo3 = $studentMarks->filter(function($m) {
                        return $m->category === 'Series Exam: CO3 Test' 
                            || $m->category === 'Series Exam: CO3' 
                            || ($m->co_tag === 'CO3' && str_starts_with($m->category, 'Series'));
                    })->first();

                    $mCo4 = $studentMarks->filter(function($m) {
                        return $m->category === 'Series Exam: CO4 Test' 
                            || $m->category === 'Series Exam: CO4' 
                            || ($m->co_tag === 'CO4' && str_starts_with($m->category, 'Series'));
                    })->first();

                    if ($mCo3 || $mCo4) {
                        $s2_co3 = $mCo3 ? (float)$mCo3->marks_obtained : null;
                        $s2_co4 = $mCo4 ? (float)$mCo4->marks_obtained : null;
                        if ($s2_co3 !== null || $s2_co4 !== null) {
                            $s2_50 = min(50.0, ($s2_co3 ?? 0) + ($s2_co4 ?? 0));
                        }
                    } else {
                        $mS2 = $studentMarks->filter(function($m) {
                            return str_contains($m->category, 'Series Exam 2') 
                                || str_contains($m->category, 'Series 2')
                                || ($m->max_marks == 50 && (str_contains($m->category, 'Series') && str_contains($m->category, '2')));
                        })->first();
                        if ($mS2) {
                            $s2_50 = min(50.0, (float)$mS2->marks_obtained);
                            $s2_co3 = round(($s2_50 / 2.0) * 2) / 2;
                            $s2_co4 = round(($s2_50 / 2.0) * 2) / 2;
                        }
                    }
                }
            } elseif ($s2_50 !== null && ($s2_co3 === null || $s2_co4 === null)) {
                $s2_co3 = $s2_co3 ?? round(($s2_50 / 2.0) * 2) / 2;
                $s2_co4 = $s2_co4 ?? round(($s2_50 / 2.0) * 2) / 2;
            } elseif (($s2_co3 !== null || $s2_co4 !== null) && $s2_50 === null) {
                $s2_50 = min(50.0, ($s2_co3 ?? 0) + ($s2_co4 ?? 0));
            }

            $s1_20 = ($s1_50 !== null && !$s1Absent) ? round((($s1_50 / 50.0) * 20.0) * 2) / 2 : 0.0;
            $s2_20 = ($s2_50 !== null && !$s2Absent) ? round((($s2_50 / 50.0) * 20.0) * 2) / 2 : 0.0;

            if ($eval && $eval->series_20 !== null) {
                $series20 = (float)$eval->series_20;
            } elseif ($s1_50 !== null || $s2_50 !== null) {
                if ($s1_50 !== null && $s2_50 === null) {
                    $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
                    $series20 = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : round(($s1_20 / 2.0) * 2) / 2;
                } else {
                    $series20 = round((($s1_20 + $s2_20) / 2.0) * 2) / 2;
                }
            } else {
                $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
                $series20 = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;
            }
            if ($series20 > 20.0) $series20 = 20.0;

            // 4. Total CIE Marks (Max 40)
            if ($eval && $eval->total_cia_40 !== null) {
                $totalCiaMarks = (float)round($eval->total_cia_40);
            } else {
                $totalCiaMarks = (float)round($attMarks + $selfLearning15 + $series20);
            }
            if ($totalCiaMarks > 40.0) $totalCiaMarks = 40.0;

            // Legacy exam_marks mapping for individual series exams if present
            $examMarks = [];
            foreach ($seriesExams as $ex) {
                $eMark = $studentMarks->where('category', 'Series Exam: ' . $ex->exam_name)->first();
                $examMarks[$ex->id] = $eMark ? (float)$eMark->marks_obtained : 0.0;
            }

            // 5. ESE Marks (60) & Grade
            $eseRecord = $studentMarks->where('category', 'ESE')->first();
            $gradeRecord = $boardGrades->get($student->reg_no) ?: $boardGrades->get($student->sbte_reg_no);
            
            $eseMarks = $eval && $eval->ese_mark_60 !== null ? (float)$eval->ese_mark_60 : ($eseRecord ? (float)$eseRecord->marks_obtained : null);
            $eseGrade = $eval && $eval->ese_grade ? $eval->ese_grade : ($gradeRecord ? trim($gradeRecord->grade) : null);
            $eseAbsent = $eval ? (bool)$eval->ese_absent : false;

            $gradeToMarkScale = [
                'S' => 57.0, 'A' => 51.0, 'B' => 45.0, 'C' => 39.0, 'D' => 33.0, 'E' => 27.0, 'F' => 0.0, 'FE' => 0.0,
            ];

            if (empty($eseGrade) && $eseMarks !== null && $eseMarks > 0) {
                $pct = ($eseMarks / 60.0) * 100.0;
                if ($pct >= 90) $eseGrade = 'S';
                elseif ($pct >= 80) $eseGrade = 'A';
                elseif ($pct >= 70) $eseGrade = 'B';
                elseif ($pct >= 60) $eseGrade = 'C';
                elseif ($pct >= 50) $eseGrade = 'D';
                elseif ($pct >= 40) $eseGrade = 'E';
                else $eseGrade = 'F';
            } elseif (!empty($eseGrade) && ($eseMarks === null || $eseMarks == 0)) {
                $eseMarks = $gradeToMarkScale[strtoupper($eseGrade)] ?? 0.0;
            }

            $finalEseMarks = ($eseMarks !== null && !$eseAbsent) ? (float)$eseMarks : 0.0;
            $totalCourseMarks = (float)round($totalCiaMarks + $finalEseMarks);
            if ($totalCourseMarks > 100.0) $totalCourseMarks = 100.0;

            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'raw_attendance_pct' => $rawAttPercentage,
                'raw_att_percentage' => $rawAttPercentage,
                'attendance_override_pct' => $overridePct,
                'effective_attendance_pct' => $effectivePct,
                'attendance_percent' => $effectivePct !== null ? round($effectivePct, 2) : null,
                'att_percentage' => $effectivePct !== null ? round($effectivePct, 2) : null,
                'attendance_marks' => $attMarks,
                'att_marks' => $attMarks,
                'attendance_status' => $attStatus,
                'attendance_color' => $attColor,
                'ca1_15' => $ca1,
                'ca2_15' => $ca2,
                'ca3_15' => $ca3,
                'ca4_15' => $ca4,
                'self_learning_15' => $selfLearning15,
                'self_learning_marks' => $selfLearning15,
                'series_1_co1_25' => $s1_co1,
                'series_1_co2_25' => $s1_co2,
                'series_1_50' => $s1_50,
                'series_1_absent' => $s1Absent,
                'series_1_20' => $s1_20,
                'series_2_co3_25' => $s2_co3,
                'series_2_co4_25' => $s2_co4,
                'series_2_50' => $s2_50,
                'series_2_absent' => $s2Absent,
                'series_2_20' => $s2_20,
                'series_20' => $series20,
                'series_exam_marks' => $series20,
                'total_cia' => $totalCiaMarks,
                'total_cia_40' => $totalCiaMarks,
                'ese_marks' => $finalEseMarks,
                'ese_mark_60' => $eseMarks,
                'ese_grade' => $eseGrade ?: '',
                'ese_absent' => $eseAbsent,
                'total_marks_100' => $totalCourseMarks,
                'grand_total' => $totalCourseMarks,
                'co_details' => $coDetails,
                'exam_marks' => $examMarks
            ];
        });

        $studentResults = $studentCiaData;
        $theoryEvaluations = $evaluations;

        $studyMaterials = \App\Models\VirtualLearningMaterial::where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('r26.virtual_classroom_theory', compact('batchSubject', 'classroom', 'students', 'courseFile', 'lessonPlans', 'studentCiaData', 'studentResults', 'theoryEvaluations', 'evaluations', 'selfLearningConfigs', 'seriesExams', 'studyMaterials', 'hasSubjectAttendance', 'hasStaffUpload', 'hasManualLog'));
    }

    /**
     * Upload and parse Revision 2026 Syllabus PDF locally.
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $file = $request->file('syllabus_file');
            $path = $file->store('r26_syllabi', 'public');

            // Execute local Python parser service
            $pyPath = base_path('app/Services/r26_syllabus_parser.py');
            $pythonBin = file_exists('/usr/bin/python3') ? '/usr/bin/python3' : 'python3';
            $sitePkg = '/home/carmel/.local/lib/python3.14/site-packages';
            $fullPdfPath = storage_path('app/public/' . $path);
            $command = "PYTHONIOENCODING=utf-8 PYTHONPATH={$sitePkg}:\$PYTHONPATH {$pythonBin} " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            $jsonOutput = shell_exec($command);
            
            $parsedResult = json_decode($jsonOutput, true);
            if (!$parsedResult || ($parsedResult['status'] ?? '') === 'ERROR') {
                throw new \Exception($parsedResult['message'] ?? 'Failed to execute local syllabus parser.');
            }
            
            $data = $parsedResult['data'];
            
            $totalHours = $data['total_hours'] ?: 60;
            $cos = $data['cos'];
            $modules = $data['modules'];
            $packedCopo_matrix = $data['copo_matrix'];
            $topics = $data['detailed_topics'];

            // Calculate durations
            $coCount = count($cos) ?: 4;
            $avgDuration = floor($totalHours / $coCount);
            foreach ($cos as &$co) {
                $co['duration'] = $avgDuration;
            }
            unset($co);

            // Pack credit, ltpr, cie, ese, totalHours and mappings inside parsed_copo JSON
            $packedCopo = [
                'credit' => $data['credits'] ?: 4,
                'l_t_p_r' => $data['teaching_scheme'] ?: '3:1:0:0',
                'cie_marks' => $data['cie_marks'] ?: 40,
                'ese_marks' => $data['ese_marks'] ?: 60,
                'total_hours' => $totalHours,
                'mappings' => $packedCopo_matrix
            ];

            // Save details to course_files table
            $courseFile = CourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'parsed_cos' => $cos,
                    'parsed_modules' => $modules,
                    'parsed_copo' => $packedCopo,
                    'parsed_textbooks' => ['Textbook Reference 1', 'Textbook Reference 2']
                ]
            );

            // Populate Lesson Plans
            LessonPlan::where('batch_subject_id', $subjectId)->delete();

            // Check if cross-batch template already exists for this subject code
            $templateRows = \DB::table('lesson_plan_templates')
                ->where('subject_code', $batchSubject->subject_code)
                ->orderBy('day_no', 'asc')
                ->get();

            if ($templateRows->isNotEmpty()) {
                foreach ($templateRows as $index => $tmp) {
                    LessonPlan::create([
                        'batch_subject_id' => $subjectId,
                        'day_no'           => $tmp->day_no ?? ($index + 1),
                        'co_id'            => $tmp->co_id,
                        'topic_content'    => $tmp->topic_content,
                        'allocated_hours'  => 1,
                        'pedagogy'         => $tmp->pedagogy ?? 'Lecture',
                        'status'           => 'Pending'
                    ]);
                }
            } else {
                $dayNo = 1;
                
                // Loop through parsed topics and generate day rows
                if (!empty($topics)) {
                    foreach ($topics as $t) {
                        for ($h = 1; $h <= $t['hours']; $h++) {
                            if ($dayNo > $totalHours) break 2;
                            
                            LessonPlan::create([
                                'batch_subject_id' => $subjectId,
                                'day_no'           => $dayNo,
                                'co_id'            => $t['co_id'],
                                'topic_content'    => $t['topic'],
                                'allocated_hours'  => 1,
                                'pedagogy'         => $t['pedagogy'],
                                'taxonomy'         => $t['taxonomy'] ?? null,
                                'status'           => 'Pending'
                            ]);
                            $dayNo++;
                        }
                    }
                }
                
                // Pad remaining days if we have fewer than total hours
                while ($dayNo <= $totalHours) {
                    $coTag = !empty($cos) ? $cos[($dayNo - 1) % count($cos)]['id'] : 'CO1';
                    LessonPlan::create([
                        'batch_subject_id' => $subjectId,
                        'day_no'           => $dayNo,
                        'co_id'            => $coTag,
                        'topic_content'    => 'Revision & Doubt Clearing Session',
                        'allocated_hours'  => 1,
                        'pedagogy'         => 'Lecture',
                        'status'           => 'Pending'
                    ]);
                    $dayNo++;
                }
                
                // AUTOMATICALLY APPEND EXACTLY 4 SERIES TESTS SEQUENTIALLY AT THE END
                for ($testIdx = 1; $testIdx <= 4; $testIdx++) {
                    $coTag = !empty($cos) ? $cos[count($cos) - 1]['id'] : 'CO4';
                    LessonPlan::create([
                        'batch_subject_id' => $subjectId,
                        'day_no'           => $dayNo,
                        'co_id'            => $coTag,
                        'topic_content'    => "Series Test $testIdx / Module Evaluation",
                        'allocated_hours'  => 1,
                        'pedagogy'         => 'Exam',
                        'status'           => 'Pending'
                    ]);
                    $dayNo++;
                }
            }

            return response()->json([
                'status' => 'SUCCESS', 
                'message' => 'Syllabus uploaded and parsed locally successfully. Lesson planner initialized.',
                'course_file' => $courseFile
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Save CO-PO Articulation Matrix for Revision 2026 Theory Course File & Syllabus Registry
     */
    public function saveCoPoMatrix(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        $mappings = $request->input('mappings', []);

        $copoPayload = is_array($courseFile->parsed_copo)
            ? $courseFile->parsed_copo
            : (json_decode($courseFile->parsed_copo, true) ?: []);

        $copoPayload['mappings'] = $mappings;
        $courseFile->parsed_copo = $copoPayload;
        $courseFile->save();

        // Sync globally to syllabus_registry if subject_code is present
        if (!empty($batchSubject->subject_code)) {
            try {
                \DB::table('syllabus_registry')->updateOrInsert(
                    ['subject_code' => $batchSubject->subject_code],
                    ['co_po_mapping' => json_encode($mappings), 'updated_at' => now()]
                );
            } catch (\Exception $e) {
                \Log::warning("Could not sync CO-PO matrix to syllabus_registry: " . $e->getMessage());
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'success' => true,
            'message' => 'CO-PO Articulation Matrix saved successfully!',
            'mappings' => $mappings
        ]);
    }

    /**
     * Print lesson plan for Revision 2026.
     */
    public function printLessonPlan($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $subject = BatchSubject::find($subjectId);
        if (!$subject) {
            abort(404, 'Subject not found.');
        }

        $plans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        $classroom = R26ClassManagement::where('classroom_id', $subject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $subject->classroom_id)->first();

        $branchName = $this->getDepartmentName($subject, $classroom);
        $departmentName = $branchName;
        $assignedStaffList = $subject->getAssignedFacultyList();
        $facultyNames = $subject->getAssignedFacultyNames();
        $lecturerName = $facultyNames ?: Session::get('userName', 'Assigned Faculty');

        return view('r26.lesson_plan_print', compact('subject', 'plans', 'branchName', 'departmentName', 'lecturerName', 'classroom', 'assignedStaffList', 'facultyNames'));
    }

    /**
     * Parse date string into Y-m-d format for database storage.
     * Supports dd/mm/yyyy, dd-mm-yyyy, and YYYY-MM-DD.
     */
    protected function parseDateForStorage($value)
    {
        if (empty($value)) {
            return null;
        }
        $value = trim((string)$value);
        if ($value === '' || $value === '-' || $value === '—' || strtolower($value) === 'null') {
            return null;
        }
        // Check dd/mm/yyyy or dd-mm-yyyy
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        // Check YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Bulk update lesson plans for Revision 2026.
     */
    public function bulkUpdateLessonPlans(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $deletedIds = $request->input('deleted_ids', []);
        if (!empty($deletedIds)) {
            LessonPlan::whereIn('id', $deletedIds)
                ->where('batch_subject_id', $subjectId)
                ->delete();
        }

        $rows = $request->input('rows', []);
        $updated = 0;
        $createdMappings = [];
        foreach ($rows as $row) {
            $id = $row['id'] ?? null;
            if (!$id) continue;

            if (str_starts_with((string)$id, 'new_')) {
                $newPlan = LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no'           => $row['day_no'] ?? 1,
                    'co_id'            => $row['co_id'] ?? 'CO1',
                    'topic_content'    => $row['topic_content'] ?? '',
                    'allocated_hours'  => $row['allocated_hours'] ?? 1,
                    'pedagogy'         => $row['pedagogy'] ?? 'Lecture',
                    'taxonomy'         => $row['taxonomy'] ?? null,
                    'proposed_date'    => $this->parseDateForStorage($row['proposed_date'] ?? null),
                    'actual_date'      => $this->parseDateForStorage($row['actual_date'] ?? null),
                    'status'           => $row['status'] ?? 'Pending'
                ]);
                $createdMappings[$id] = $newPlan->id;
                $updated++;
                continue;
            }

            $plan = LessonPlan::where('id', $id)
                ->where('batch_subject_id', $subjectId)
                ->first();
                
            if (!$plan) continue;

            if (isset($row['co_id'])) $plan->co_id = $row['co_id'];
            if (isset($row['day_no'])) $plan->day_no = $row['day_no'];
            $plan->topic_content   = $row['topic_content']   ?? $plan->topic_content;
            if (array_key_exists('proposed_date', $row)) {
                $plan->proposed_date = $this->parseDateForStorage($row['proposed_date']);
            }
            if (array_key_exists('actual_date', $row)) {
                $plan->actual_date = $this->parseDateForStorage($row['actual_date']);
            }
            $plan->allocated_hours = $row['allocated_hours'] ?? $plan->allocated_hours;
            $plan->pedagogy        = $row['pedagogy']        ?? $plan->pedagogy;
            $plan->taxonomy        = $row['taxonomy']        ?? $plan->taxonomy;
            $plan->status          = $row['status']          ?? $plan->status;
            $plan->save();
            $updated++;
        }

        return response()->json([
            'status' => 'SUCCESS', 
            'message' => "{$updated} lesson plan rows saved successfully.",
            'created_mappings' => $createdMappings
        ]);
    }

    /**
     * Bulk update Continuous Internal Assessment (CIA) marks for Revision 2026.
     */
    public function bulkUpdateCiaMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        $this->ensureSyllabusRegistryExists($batchSubject);

        $rows = $request->input('rows', []);
        $updated = 0;

        foreach ($rows as $row) {
            $regNo = $row['reg_no'] ?? null;
            if (!$regNo) continue;

            $selfLearningVal = isset($row['self_learning_marks']) ? (float)$row['self_learning_marks'] : 0.0;
            $seriesExamVal = isset($row['series_exam_marks']) ? (float)$row['series_exam_marks'] : 0.0;

            // Save Self Learning Marks
            \DB::table('academic_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'batch_subject_id' => $subjectId,
                    'category' => 'Self Learning',
                ],
                [
                    'subject_code' => $batchSubject->subject_code,
                    'co_tag' => 'ALL',
                    'max_marks' => 15,
                    'marks_obtained' => $selfLearningVal,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

            // Save Series Exam Marks
            \DB::table('academic_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'batch_subject_id' => $subjectId,
                    'category' => 'Series Exam',
                ],
                [
                    'subject_code' => $batchSubject->subject_code,
                    'co_tag' => 'ALL',
                    'max_marks' => 20,
                    'marks_obtained' => $seriesExamVal,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

            $updated++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => "{$updated} student CIA marks sheets saved successfully."]);
    }

    /**
     * Save Consolidated Evaluation Marks (Debounced Auto-Save & Manual Submit)
     * For Revision 2026 Theory Courses
     * Assessment: Attendance (5M) + Self-Learning (15M) + Series Exams (20M) = CIA (40M) + ESE (60M) = Total (100M)
     */
    public function saveConsolidatedTheoryEvaluations(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $this->ensureSyllabusRegistryExists($batchSubject);

        $rows = $request->input('rows') ?: $request->input('marks_data', []);
        if (empty($rows) || !is_array($rows)) {
            return response()->json(['status' => 'ERROR', 'message' => 'No student rows provided.'], 422);
        }

        // Fetch Attendance Records
        $attendanceData = DB::table('student_attendance')
            ->where(function($q) use ($batchSubject) {
                $q->where('subject_code', $batchSubject->subject_code)
                  ->orWhere('subject_code', 'like', '%' . substr($batchSubject->subject_code, -4) . '%');
            })
            ->get()
            ->groupBy('reg_no');

        // Fetch authoritative TEAMS uploaded attendance records
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

        $updatedRows = [];
        $updatedData = [];

        foreach ($rows as $row) {
            $regNo = $row['reg_no'] ?? null;
            if (!$regNo) continue;

            // Attendance: Strictly use subject attendance uploaded by concerned staff, else manual log
            // Never use tutor uploads for subject attendance mark calculation.
            $stOfficial = $subjectOfficialAttendance->get($regNo);
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $logAttPct = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : null;

            $staffUploadedPct = null;
            if ($stOfficial) {
                if ($stOfficial->teams_percentage !== null) {
                    $staffUploadedPct = (float)$stOfficial->teams_percentage;
                } elseif ($stOfficial->final_percentage !== null) {
                    $staffUploadedPct = (float)$stOfficial->final_percentage;
                }
            }

            $rawAttPct = $staffUploadedPct !== null ? $staffUploadedPct : $logAttPct;

            $overridePct = isset($row['attendance_override_pct']) && $row['attendance_override_pct'] !== '' && $row['attendance_override_pct'] !== null
                ? (float)$row['attendance_override_pct']
                : null;
            $effectivePct = $overridePct !== null ? $overridePct : $rawAttPct;
            $attMarks = $effectivePct !== null ? R26TheoryEvaluation::calculateAttendanceMarks($effectivePct) : 0.0;

            // Self-Learning Modules 1-4 (Max 15 each)
            $ca1 = isset($row['ca1_15']) && $row['ca1_15'] !== '' && $row['ca1_15'] !== null ? min(15.0, max(0.0, (float)$row['ca1_15'])) : null;
            $ca2 = isset($row['ca2_15']) && $row['ca2_15'] !== '' && $row['ca2_15'] !== null ? min(15.0, max(0.0, (float)$row['ca2_15'])) : null;
            $ca3 = isset($row['ca3_15']) && $row['ca3_15'] !== '' && $row['ca3_15'] !== null ? min(15.0, max(0.0, (float)$row['ca3_15'])) : null;
            $ca4 = isset($row['ca4_15']) && $row['ca4_15'] !== '' && $row['ca4_15'] !== null ? min(15.0, max(0.0, (float)$row['ca4_15'])) : null;

            $caEntered = [];
            if ($ca1 !== null) $caEntered[] = $ca1;
            if ($ca2 !== null) $caEntered[] = $ca2;
            if ($ca3 !== null) $caEntered[] = $ca3;
            if ($ca4 !== null) $caEntered[] = $ca4;

            $selfLearning15 = count($caEntered) > 0 ? round((array_sum($caEntered) / 4.0) * 2) / 2 : 0.0;
            if ($selfLearning15 > 15.0) $selfLearning15 = 15.0;

            // Series 1 & 2 (Series 1: CO1 [25] + CO2 [25] = 50M -> 20M; Series 2: CO3 [25] + CO4 [25] = 50M -> 20M)
            $s1Absent = !empty($row['series_1_absent']);
            $s2Absent = !empty($row['series_2_absent']);

            $s1_co1 = isset($row['series_1_co1_25']) && $row['series_1_co1_25'] !== '' && $row['series_1_co1_25'] !== null ? min(25.0, max(0.0, (float)$row['series_1_co1_25'])) : null;
            $s1_co2 = isset($row['series_1_co2_25']) && $row['series_1_co2_25'] !== '' && $row['series_1_co2_25'] !== null ? min(25.0, max(0.0, (float)$row['series_1_co2_25'])) : null;

            if ($s1_co1 !== null || $s1_co2 !== null) {
                $s1_50 = min(50.0, ($s1_co1 ?? 0) + ($s1_co2 ?? 0));
            } elseif (isset($row['series_1_50']) && $row['series_1_50'] !== '' && $row['series_1_50'] !== null) {
                $s1_50 = min(50.0, max(0.0, (float)$row['series_1_50']));
                $s1_co1 = round(($s1_50 / 2.0) * 2) / 2;
                $s1_co2 = round(($s1_50 / 2.0) * 2) / 2;
            } else {
                $s1_50 = null;
            }

            $s2_co3 = isset($row['series_2_co3_25']) && $row['series_2_co3_25'] !== '' && $row['series_2_co3_25'] !== null ? min(25.0, max(0.0, (float)$row['series_2_co3_25'])) : null;
            $s2_co4 = isset($row['series_2_co4_25']) && $row['series_2_co4_25'] !== '' && $row['series_2_co4_25'] !== null ? min(25.0, max(0.0, (float)$row['series_2_co4_25'])) : null;

            if ($s2_co3 !== null || $s2_co4 !== null) {
                $s2_50 = min(50.0, ($s2_co3 ?? 0) + ($s2_co4 ?? 0));
            } elseif (isset($row['series_2_50']) && $row['series_2_50'] !== '' && $row['series_2_50'] !== null) {
                $s2_50 = min(50.0, max(0.0, (float)$row['series_2_50']));
                $s2_co3 = round(($s2_50 / 2.0) * 2) / 2;
                $s2_co4 = round(($s2_50 / 2.0) * 2) / 2;
            } else {
                $s2_50 = null;
            }

            $s1_20 = ($s1_50 !== null && !$s1Absent) ? round((($s1_50 / 50.0) * 20.0) * 2) / 2 : 0.0;
            $s2_20 = ($s2_50 !== null && !$s2Absent) ? round((($s2_50 / 50.0) * 20.0) * 2) / 2 : 0.0;

            if ($s1_50 !== null || $s2_50 !== null) {
                $series20 = round((($s1_20 + $s2_20) / 2.0) * 2) / 2;
            } else {
                $series20 = 0.0;
            }
            if ($series20 > 20.0) $series20 = 20.0;

            // Total CIE / CIA Marks (Max 40)
            $totalCiaMarks = (float)round($attMarks + $selfLearning15 + $series20);
            if ($totalCiaMarks > 40.0) $totalCiaMarks = 40.0;

            // ESE Marks (60)
            $gradeToMarkScale = [
                'S' => 57.0, 'A' => 51.0, 'B' => 45.0, 'C' => 39.0, 'D' => 33.0, 'E' => 27.0, 'F' => 0.0, 'FE' => 0.0,
            ];
            $eseAbsent = !empty($row['ese_absent']);
            $eseGrade = isset($row['ese_grade']) && $row['ese_grade'] !== '' ? strtoupper(trim($row['ese_grade'])) : null;
            $eseMark60 = isset($row['ese_mark_60']) && $row['ese_mark_60'] !== '' && $row['ese_mark_60'] !== null
                ? min(60.0, max(0.0, (float)$row['ese_mark_60']))
                : null;

            if (!empty($eseGrade) && ($eseMark60 === null || $eseMark60 == 0)) {
                $eseMark60 = $gradeToMarkScale[$eseGrade] ?? 0.0;
            }

            $totalEse = ($eseMark60 !== null && !$eseAbsent) ? $eseMark60 : 0.0;
            $totalCourseMarks = (float)round($totalCiaMarks + $totalEse);
            if ($totalCourseMarks > 100.0) $totalCourseMarks = 100.0;

            // Save to r26_theory_evaluations
            R26TheoryEvaluation::updateOrInsert(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo
                ],
                [
                    'attendance_pct' => $rawAttPct,
                    'attendance_override_pct' => $overridePct,
                    'attendance_mark_5' => $attMarks,
                    'ca1_15' => $ca1,
                    'ca2_15' => $ca2,
                    'ca3_15' => $ca3,
                    'ca4_15' => $ca4,
                    'self_learning_15' => $selfLearning15,
                    'series_1_50' => $s1_50,
                    'series_1_co1_25' => $s1_co1,
                    'series_1_co2_25' => $s1_co2,
                    'series_1_absent' => $s1Absent,
                    'series_2_50' => $s2_50,
                    'series_2_co3_25' => $s2_co3,
                    'series_2_co4_25' => $s2_co4,
                    'series_2_absent' => $s2Absent,
                    'series_20' => $series20,
                    'total_cia_40' => $totalCiaMarks,
                    'ese_grade' => $eseGrade,
                    'ese_mark_60' => $eseMark60,
                    'ese_absent' => $eseAbsent,
                    'total_marks_100' => $totalCourseMarks,
                    'updated_at' => now(),
                ]
            );

            // Backward compatibility: Sync with academic_marks
            DB::table('academic_marks')->updateOrInsert(
                ['reg_no' => $regNo, 'batch_subject_id' => $subjectId, 'category' => 'Self Learning'],
                ['subject_code' => $batchSubject->subject_code, 'co_tag' => 'ALL', 'max_marks' => 15, 'marks_obtained' => $selfLearning15, 'entered_by' => $userId, 'updated_at' => now()]
            );
            if ($s1_50 !== null || $s2_50 !== null || $series20 > 0) {
                DB::table('academic_marks')->updateOrInsert(
                    ['reg_no' => $regNo, 'batch_subject_id' => $subjectId, 'category' => 'Series Exam'],
                    ['subject_code' => $batchSubject->subject_code, 'co_tag' => 'ALL', 'max_marks' => 20, 'marks_obtained' => $series20, 'entered_by' => $userId, 'updated_at' => now()]
                );
            }
            if ($s1_co1 !== null) {
                DB::table('academic_marks')->updateOrInsert(
                    ['reg_no' => $regNo, 'batch_subject_id' => $subjectId, 'category' => 'Series Exam: CO1 Test'],
                    ['subject_code' => $batchSubject->subject_code, 'co_tag' => 'CO1', 'max_marks' => 25, 'marks_obtained' => $s1_co1, 'entered_by' => $userId, 'updated_at' => now()]
                );
            }
            if ($s1_co2 !== null) {
                DB::table('academic_marks')->updateOrInsert(
                    ['reg_no' => $regNo, 'batch_subject_id' => $subjectId, 'category' => 'Series Exam: CO2 Test'],
                    ['subject_code' => $batchSubject->subject_code, 'co_tag' => 'CO2', 'max_marks' => 25, 'marks_obtained' => $s1_co2, 'entered_by' => $userId, 'updated_at' => now()]
                );
            }
            if ($s2_co3 !== null) {
                DB::table('academic_marks')->updateOrInsert(
                    ['reg_no' => $regNo, 'batch_subject_id' => $subjectId, 'category' => 'Series Exam: CO3 Test'],
                    ['subject_code' => $batchSubject->subject_code, 'co_tag' => 'CO3', 'max_marks' => 25, 'marks_obtained' => $s2_co3, 'entered_by' => $userId, 'updated_at' => now()]
                );
            }
            if ($s2_co4 !== null) {
                DB::table('academic_marks')->updateOrInsert(
                    ['reg_no' => $regNo, 'batch_subject_id' => $subjectId, 'category' => 'Series Exam: CO4 Test'],
                    ['subject_code' => $batchSubject->subject_code, 'co_tag' => 'CO4', 'max_marks' => 25, 'marks_obtained' => $s2_co4, 'entered_by' => $userId, 'updated_at' => now()]
                );
            }
            if ($eseMark60 !== null || $eseGrade !== null) {
                DB::table('academic_marks')->updateOrInsert(
                    ['reg_no' => $regNo, 'batch_subject_id' => $subjectId, 'category' => 'ESE'],
                    ['subject_code' => $batchSubject->subject_code, 'co_tag' => 'ALL', 'max_marks' => 60, 'marks_obtained' => $totalEse, 'entered_by' => $userId, 'updated_at' => now()]
                );
            }
            if (!empty($eseGrade)) {
                DB::table('student_board_grades')->updateOrInsert(
                    ['reg_no' => $regNo, 'subject_code' => $batchSubject->subject_code],
                    ['grade' => $eseGrade, 'score' => $totalCourseMarks, 'updated_at' => now()]
                );
            }

            $updatedRows[] = $regNo;
            $updatedData[$regNo] = [
                'effective_pct' => $effectivePct,
                'att_marks' => $attMarks,
                'self_learning_15' => $selfLearning15,
                'series_1_co1_25' => $s1_co1,
                'series_1_co2_25' => $s1_co2,
                'series_1_50' => $s1_50,
                'series_1_20' => $s1_20,
                'series_2_co3_25' => $s2_co3,
                'series_2_co4_25' => $s2_co4,
                'series_2_50' => $s2_50,
                'series_2_20' => $s2_20,
                'series_20' => $series20,
                'total_cia_40' => $totalCiaMarks,
                'ese_mark_60' => $eseMark60,
                'ese_grade' => $eseGrade,
                'total_marks_100' => $totalCourseMarks,
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => count($updatedRows) . ' student evaluations saved successfully.',
            'updated_count' => count($updatedRows),
            'data' => $updatedData
        ]);
    }

    /**
     * Alias for saveConsolidatedTheoryEvaluations
     */
    public function saveConsolidatedMarks(Request $request, $subjectId)
    {
        return $this->saveConsolidatedTheoryEvaluations($request, $subjectId);
    }

    /**
     * Print CIA / CIE Register for R2026 Theory
     */
    public function printCiaRegister($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?? R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $evaluations = R26TheoryEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        $deptCode = $classroom->branch ?? $classroom->department ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->first();

        return view('r26.cia_register_print', compact('batchSubject', 'classroom', 'students', 'evaluations', 'assignedStaff', 'hod'));
    }

    /**
     * Print Series Examination Register for R2026 Theory
     */
    public function printSeriesRegister($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?? R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $evaluations = R26TheoryEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        $deptCode = $classroom->branch ?? $classroom->department ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->first();

        return view('r26.series_register_print', compact('batchSubject', 'classroom', 'students', 'evaluations', 'assignedStaff', 'hod'));
    }

    public function bulkUpdateSelfLearningMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        $this->ensureSyllabusRegistryExists($batchSubject);

        // Save Configurations
        $configs = $request->input('configs');
        if ($configs) {
            $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
            $courseFile->self_learning_configs = $configs;
            $courseFile->save();
        }

        $rows = $request->input('rows', []);
        $updated = 0;

        foreach ($rows as $row) {
            $regNo = $row['reg_no'] ?? null;
            if (!$regNo) continue;

            $coData = $row['co_details'] ?? [];
            foreach ($coData as $coTag => $marks) {
                // Clear old Self Study marks to prevent duplicate accumulation
                \DB::table('academic_marks')
                    ->where('reg_no', $regNo)
                    ->where('batch_subject_id', $subjectId)
                    ->where('co_tag', $coTag)
                    ->where('category', 'like', 'Self Study:%')
                    ->delete();

                foreach (['assignment', 'mcq', 'act3', 'act4', 'act5'] as $field) {
                    $val = isset($marks[$field]) ? (float)$marks[$field] : 0.0;
                    
                    if ($val > 0 || in_array($field, ['assignment', 'mcq'])) {
                        \DB::table('academic_marks')->insert([
                            'reg_no' => $regNo,
                            'batch_subject_id' => $subjectId,
                            'subject_code' => $batchSubject->subject_code,
                            'category' => 'Self Study: ' . ucfirst($field),
                            'co_tag' => $coTag,
                            'max_marks' => 15,
                            'marks_obtained' => $val,
                            'entered_by' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        if ($field === 'assignment') {
                            \DB::table('student_task_submissions')
                                ->where('reg_no', $regNo)
                                ->where('subject_code', $batchSubject->subject_code)
                                ->where('co_tag', $coTag)
                                ->where('category', 'Assignment')
                                ->where('status', 'Submitted')
                                ->update(['status' => 'Graded', 'updated_at' => now()]);
                        }
                    }
                }
            }
            $updated++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => "{$updated} student self-learning sheets updated successfully."]);
    }

    /**
     * Print the Self-Learning Activities evaluation report.
     */
    public function printSelfLearningReport($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            abort(401, 'Unauthorized.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Dedicated redirection for Revision 2026 English (1001)
        if ($batchSubject->syllabus_revision_code === 'REV2026' && (str_contains($batchSubject->subject_code ?? '', '1001') || stripos($batchSubject->subject_name ?? '', 'English') !== false)) {
            return redirect('/r26/classroom/english-1001/' . $subjectId);
        }

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);
        
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get();

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) {
            $courseFile = new CourseFile();
        }

        // Self-Learning Configurations
        $selfLearningConfigs = $courseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO2' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO3' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO4' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
        ];

        // Fetch MCQ Test Configurations & Attempts for Automation
        $testConfigs = \DB::table('test_configs')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('classroom_id', $batchSubject->classroom_id)
            ->get();
            
        $testIds = $testConfigs->pluck('test_id')->toArray();
        
        $testAttempts = \DB::table('test_attempts')
            ->whereIn('test_id', $testIds)
            ->where('status', 'Completed')
            ->get()
            ->groupBy('reg_no');

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $evaluations = R26TheoryEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $studentCiaData = $students->map(function ($student) use ($academicMarks, $selfLearningConfigs, $testConfigs, $testAttempts, $evaluations) {
            $studentMarks = $academicMarks->get($student->reg_no, collect());
            $studentAttempts = $testAttempts->get($student->reg_no, collect());
            $eval = $evaluations->get($student->reg_no);
            
            $coDetails = [];
            $totalAvgSum = 0.0;
            
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $coMarks = $studentMarks->where('co_tag', $coTag);
                
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;

                // MCQ Automated Mark logic
                if ($mcqMark) {
                    $valMcq = (float)$mcqMark->marks_obtained;
                } else {
                    $valMcq = 0.0;
                    $coTest = $testConfigs->filter(function($tc) use ($coTag) {
                        $selected = json_decode($tc->selected_cos, true) ?: [];
                        return in_array($coTag, $selected);
                    })->first();
                    if ($coTest) {
                        $attemptsForTest = $studentAttempts->where('test_id', $coTest->test_id);
                        $maxScore = $attemptsForTest->max('total_score');
                        $mcqMax = (float)($coTest->mcq_count ?: 10);
                        $configMaxMCQ = (float)($selfLearningConfigs[$coTag]['mcq'] ?? 5.0);
                        if ($mcqMax > 0 && $maxScore !== null) {
                            $valMcq = round(($maxScore / $mcqMax) * $configMaxMCQ, 2);
                        }
                    }
                }
                
                $coTotal = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                $totalAvgSum += $coTotal;
                
                $coDetails[$coTag] = [
                    'assignment' => $valAssignment,
                    'mcq' => $valMcq,
                    'act3' => $valAct3,
                    'act4' => $valAct4,
                    'act5' => $valAct5,
                    'total' => $coTotal
                ];
            }

            $ca1 = ($eval && $eval->ca1_15 !== null) ? (float)$eval->ca1_15 : ($coDetails['CO1']['total'] > 0 ? (float)$coDetails['CO1']['total'] : null);
            $ca2 = ($eval && $eval->ca2_15 !== null) ? (float)$eval->ca2_15 : ($coDetails['CO2']['total'] > 0 ? (float)$coDetails['CO2']['total'] : null);
            $ca3 = ($eval && $eval->ca3_15 !== null) ? (float)$eval->ca3_15 : ($coDetails['CO3']['total'] > 0 ? (float)$coDetails['CO3']['total'] : null);
            $ca4 = ($eval && $eval->ca4_15 !== null) ? (float)$eval->ca4_15 : ($coDetails['CO4']['total'] > 0 ? (float)$coDetails['CO4']['total'] : null);

            $caEntered = [];
            if ($ca1 !== null) $caEntered[] = $ca1;
            if ($ca2 !== null) $caEntered[] = $ca2;
            if ($ca3 !== null) $caEntered[] = $ca3;
            if ($ca4 !== null) $caEntered[] = $ca4;

            if ($eval && $eval->self_learning_15 !== null) {
                $slFinal = (float)$eval->self_learning_15;
            } elseif (count($caEntered) > 0) {
                $slFinal = round((array_sum($caEntered) / 4.0) * 2) / 2;
            } else {
                $slFinal = round($totalAvgSum / 4, 2);
            }
            if ($slFinal > 15.0) $slFinal = 15.0;
            
            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'ca1_15' => $ca1,
                'ca2_15' => $ca2,
                'ca3_15' => $ca3,
                'ca4_15' => $ca4,
                'self_learning_marks' => $slFinal,
                'self_learning_15' => $slFinal,
                'co_details' => $coDetails
            ];
        });

        return view('r26.self_learning_print', compact('batchSubject', 'classroom', 'students', 'studentCiaData', 'selfLearningConfigs', 'departmentName'));
    }

    /**
     * Save Assignment questions for a specific Course Outcome (CO).
     */
    public function saveAssignment(Request $request, $subjectId, $coTag)
    {
        $batchSubject = BatchSubject::with('classroom')->find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        
        $questions = $request->input('questions', []);
        $savedQuestions = $courseFile->assignment_questions ?? [];
        $savedQuestions[$coTag] = $questions;
        $courseFile->assignment_questions = $savedQuestions;

        // Save due date
        $dueDate = $this->parseDateForStorage($request->input('due_date'));
        $deadlines = $courseFile->assignment_deadlines ?? [];
        $deadlines[$coTag] = [
            'deadline' => $dueDate,
            'locked' => isset($deadlines[$coTag]['locked']) ? $deadlines[$coTag]['locked'] : false
        ];
        $courseFile->assignment_deadlines = $deadlines;

        $courseFile->save();

        // Register in the general question bank
        $branchCode = $batchSubject->classroom->branch ?? 'General';
        
        \DB::table('question_bank')
            ->where('batch_subject_id', $subjectId)
            ->where('co_tag', $coTag)
            ->where('type', 'Descriptive')
            ->delete();

        $btMapping = [
            'Remember' => 'R',
            'Understand' => 'U',
            'Apply' => 'Ap',
            'Analyze' => 'An',
            'Evaluate' => 'E',
            'Create' => 'C'
        ];

        foreach ($questions as $q) {
            $rawBt = $q['bt_level'] ?? 'Understand';
            $mappedBt = $btMapping[$rawBt] ?? substr($rawBt, 0, 5);

            try {
                \DB::table('question_bank')->insert([
                    'branch_code' => $branchCode,
                    'subject_code' => $batchSubject->subject_code,
                    'batch_subject_id' => $subjectId,
                    'type' => 'Descriptive',
                    'co_tag' => $coTag,
                    'cognitive_level' => $mappedBt,
                    'question_text' => $q['question'],
                    'marks' => intval($q['marks'] ?? 5),
                    'options' => json_encode([]),
                    'rubric' => json_encode([
                        [
                            'desc' => $q['scheme'] ?: 'Evaluation guidelines',
                            'mark' => intval($q['marks'] ?? 5)
                        ]
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $e) {
                // If subject_code is not registered in syllabus_registry yet, log it and proceed (saving to course file succeeds)
                \Illuminate\Support\Facades\Log::info("Skipped question_bank entry for {$batchSubject->subject_code} because: " . $e->getMessage());
            }
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Assignment questions saved successfully.']);
    }

    /**
     * Notify students on their dashboard regarding the assigned CO assignment.
     */
    public function notifyAssignment(Request $request, $subjectId, $coTag)
    {
        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $deadlines = $courseFile->assignment_deadlines ?? [];
        if (!isset($deadlines[$coTag])) {
            $deadlines[$coTag] = ['deadline' => date('Y-m-d'), 'locked' => true];
        } else {
            $deadlines[$coTag]['locked'] = true;
        }
        $courseFile->assignment_deadlines = $deadlines;
        $courseFile->save();

        $students = Student::where('classroom_id', $batchSubject->classroom_id)->get();
        
        foreach ($students as $st) {
            \DB::table('student_task_submissions')->updateOrInsert(
                [
                    'reg_no' => $st->reg_no,
                    'subject_code' => $batchSubject->subject_code,
                    'co_tag' => $coTag,
                    'category' => 'Assignment',
                ],
                [
                    'status' => 'Assigned',
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Assignment activity notifications sent to student dashboards.']);
    }

    /**
     * Print Assignment Question Paper (QP) for a specific Course Outcome.
     */
    public function printAssignmentQp($subjectId, $coTag)
    {
        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Dedicated redirection for Revision 2026 English (1001)
        if ($batchSubject->syllabus_revision_code === 'REV2026' && (str_contains($batchSubject->subject_code ?? '', '1001') || stripos($batchSubject->subject_name ?? '', 'English') !== false)) {
            return redirect('/r26/classroom/english-1001/' . $subjectId);
        }

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);
        
        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        $questions = $courseFile ? ($courseFile->assignment_questions[$coTag] ?? []) : [];

        return view('r26.assignment_qp_print', compact('batchSubject', 'classroom', 'questions', 'coTag', 'courseFile', 'departmentName'));
    }

    /**
     * Print Assignment Evaluation Scheme for a specific Course Outcome.
     */
    public function printAssignmentScheme($subjectId, $coTag)
    {
        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Dedicated redirection for Revision 2026 English (1001)
        if ($batchSubject->syllabus_revision_code === 'REV2026' && (str_contains($batchSubject->subject_code ?? '', '1001') || stripos($batchSubject->subject_name ?? '', 'English') !== false)) {
            return redirect('/r26/classroom/english-1001/' . $subjectId);
        }

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);
        
        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        $questions = $courseFile ? ($courseFile->assignment_questions[$coTag] ?? []) : [];

        return view('r26.assignment_scheme_print', compact('batchSubject', 'classroom', 'questions', 'coTag', 'courseFile', 'departmentName'));
    }

    /**
     * Configure default series exam configurations based on selected mode.
     */
    public function configureSeriesExams(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        if ($request->has('reset') || $request->input('reset') == 1) {
            \DB::table('series_exams')->where('batch_subject_id', $subjectId)->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Configuration reset successfully.']);
        }

        $mode = $request->input('mode') ?: 'combined_co'; // 'single_co' or 'combined_co' (default to combined_co)
        if (!in_array($mode, ['single_co', 'combined_co'])) {
            $mode = 'combined_co';
        }

        // Delete existing series exams for this subject
        \DB::table('series_exams')->where('batch_subject_id', $subjectId)->delete();

        if ($mode === 'single_co') {
            $exams = [
                ['exam_name' => 'CO1 Test', 'co_tags' => ['CO1'], 'max_marks' => 25, 'duration_minutes' => 60],
                ['exam_name' => 'CO2 Test', 'co_tags' => ['CO2'], 'max_marks' => 25, 'duration_minutes' => 60],
                ['exam_name' => 'CO3 Test', 'co_tags' => ['CO3'], 'max_marks' => 25, 'duration_minutes' => 60],
                ['exam_name' => 'CO4 Test', 'co_tags' => ['CO4'], 'max_marks' => 25, 'duration_minutes' => 60],
            ];
        } else {
            $exams = [
                ['exam_name' => 'Series Exam 1 (CO1+CO2)', 'co_tags' => ['CO1', 'CO2'], 'max_marks' => 50, 'duration_minutes' => 120],
                ['exam_name' => 'Series Exam 2 (CO3+CO4)', 'co_tags' => ['CO3', 'CO4'], 'max_marks' => 50, 'duration_minutes' => 120],
            ];
        }

        foreach ($exams as $exam) {
            \DB::table('series_exams')->insert([
                'batch_subject_id' => $subjectId,
                'exam_name' => $exam['exam_name'],
                'mode' => $mode,
                'co_tags' => json_encode($exam['co_tags']),
                'max_marks' => $exam['max_marks'],
                'duration_minutes' => $exam['duration_minutes'],
                'questions' => json_encode(['Part A' => [], 'Part B' => [], 'Part C' => []]),
                'locked' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $createdExams = \App\Models\SeriesExam::where('batch_subject_id', $subjectId)->get();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Series exams successfully configured!',
            'mode' => $mode,
            'exams' => $createdExams
        ]);
    }

    /**
     * Save questions for a specific series exam.
     */
    public function saveSeriesExam(Request $request, $subjectId, $examId)
    {
        $exam = \App\Models\SeriesExam::where('batch_subject_id', $subjectId)->where('id', $examId)->first();
        if (!$exam) {
            return response()->json(['status' => 'ERROR', 'message' => 'Exam not found.']);
        }
        if ($exam->locked) {
            return response()->json(['status' => 'ERROR', 'message' => 'This exam is locked and cannot be edited.']);
        }

        $questions = $request->input('questions', ['Part A' => [], 'Part B' => [], 'Part C' => []]);
        $exam->questions = $questions;
        $exam->save();

        // Populate question bank pool
        $batchSubject = BatchSubject::with('classroom')->find($subjectId);
        $branchCode = $batchSubject->classroom->branch ?? 'General';
        
        $btMapping = [
            'Remember' => 'R', 'Understand' => 'U', 'Apply' => 'Ap', 
            'Analyze' => 'An', 'Evaluate' => 'E', 'Create' => 'C'
        ];

        foreach (['Part A', 'Part B', 'Part C'] as $part) {
            $partQ = $questions[$part] ?? [];
            foreach ($partQ as $q) {
                if (empty($q['question'])) continue;
                $rawBt = $q['bt_level'] ?? 'Understand';
                $mappedBt = $btMapping[$rawBt] ?? substr($rawBt, 0, 5);

                try {
                    \DB::table('question_bank')->insert([
                        'branch_code' => $branchCode,
                        'subject_code' => $batchSubject->subject_code,
                        'batch_subject_id' => $subjectId,
                        'type' => 'Descriptive',
                        'co_tag' => $q['co_tag'] ?? ($exam->co_tags[0] ?? 'CO1'),
                        'cognitive_level' => $mappedBt,
                        'question_text' => $q['question'],
                        'marks' => intval($q['marks'] ?? 5),
                        'options' => json_encode([]),
                        'rubric' => json_encode([
                            [
                                'desc' => $q['scheme'] ?: 'Series exam evaluation rubric',
                                'mark' => intval($q['marks'] ?? 5)
                            ]
                        ]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::info("Skipped series question_bank pool insertion: " . $e->getMessage());
                }
            }
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Questions saved successfully.']);
    }

    /**
     * Lock and publish a specific series exam.
     */
    public function lockSeriesExam(Request $request, $subjectId, $examId)
    {
        $exam = \App\Models\SeriesExam::where('batch_subject_id', $subjectId)->where('id', $examId)->first();
        if (!$exam) {
            return response()->json(['status' => 'ERROR', 'message' => 'Exam not found.']);
        }
        $exam->locked = true;
        $exam->save();

        $batchSubject = BatchSubject::find($subjectId);
        $students = Student::where('classroom_id', $batchSubject->classroom_id)->get();

        foreach ($students as $st) {
            \DB::table('student_task_submissions')->updateOrInsert(
                [
                    'reg_no' => $st->reg_no,
                    'subject_code' => $batchSubject->subject_code,
                    'co_tag' => $exam->co_tags[0] ?? 'CO1',
                    'category' => 'Series Exam: ' . $exam->exam_name,
                ],
                [
                    'status' => 'Assigned',
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Exam successfully locked and published.']);
    }

    /**
     * Save student series exam marks and calculate consolidated score.
     */
    public function bulkUpdateSeriesExamMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        $this->ensureSyllabusRegistryExists($batchSubject);

        $rows = $request->input('rows', []);
        $updated = 0;

        foreach ($rows as $row) {
            $regNo = $row['reg_no'] ?? null;
            if (!$regNo) continue;

            $examMarks = $row['exam_marks'] ?? [];
            $totalMarksObtained = 0.0;
            $totalMaxMarks = 0;

            foreach ($examMarks as $examId => $score) {
                $exam = \App\Models\SeriesExam::find($examId);
                if (!$exam) continue;

                $val = floatval($score);
                $totalMarksObtained += $val;
                $totalMaxMarks += $exam->max_marks;

                $coTag = 'CO1';
                if (!empty($exam->co_tags)) {
                    $tags = is_array($exam->co_tags) ? $exam->co_tags : json_decode($exam->co_tags, true);
                    if (is_array($tags) && count($tags) > 0) {
                        $coTag = implode(',', $tags);
                    } elseif (is_string($exam->co_tags)) {
                        $coTag = $exam->co_tags;
                    }
                }
                $coTag = substr($coTag, 0, 10);

                // Save individual exam mark
                \DB::table('academic_marks')->updateOrInsert(
                    [
                        'reg_no' => $regNo,
                        'batch_subject_id' => $subjectId,
                        'category' => 'Series Exam: ' . $exam->exam_name,
                    ],
                    [
                        'subject_code' => $batchSubject->subject_code,
                        'co_tag' => $coTag,
                        'max_marks' => $exam->max_marks,
                        'marks_obtained' => $val,
                        'entered_by' => $userId,
                        'updated_at' => now(),
                    ]
                );
                
                // Update submission status to Graded
                \DB::table('student_task_submissions')
                    ->where('reg_no', $regNo)
                    ->where('subject_code', $batchSubject->subject_code)
                    ->where('category', 'Series Exam: ' . $exam->exam_name)
                    ->where('status', 'Submitted')
                    ->update(['status' => 'Graded', 'updated_at' => now()]);
            }

            // Save scaled total mark out of 20 in general 'Series Exam' category
            $scaledMark = 0.0;
            if ($totalMaxMarks > 0) {
                $scaledMark = round(($totalMarksObtained / $totalMaxMarks) * 20, 2);
            }

            \DB::table('academic_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'batch_subject_id' => $subjectId,
                    'category' => 'Series Exam',
                ],
                [
                    'subject_code' => $batchSubject->subject_code,
                    'co_tag' => 'ALL',
                    'max_marks' => 20,
                    'marks_obtained' => $scaledMark,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

            // Sync to r26_theory_evaluations for real-time reflection in CIE Register
            $evalRecord = R26TheoryEvaluation::firstOrNew([
                'batch_subject_id' => $subjectId,
                'reg_no' => $regNo,
            ]);

            $syncCo1 = null;
            $syncCo2 = null;
            $syncCo3 = null;
            $syncCo4 = null;
            $syncS1_50 = null;
            $syncS2_50 = null;

            foreach ($examMarks as $eId => $eScore) {
                $eModel = \App\Models\SeriesExam::find($eId);
                if (!$eModel) continue;
                $eVal = floatval($eScore);
                $eName = $eModel->exam_name;
                $eTags = is_array($eModel->co_tags) ? $eModel->co_tags : (json_decode($eModel->co_tags, true) ?: [$eModel->co_tags]);

                if (in_array('CO1', $eTags) || str_contains($eName, 'CO1')) {
                    $syncCo1 = $eVal;
                }
                if (in_array('CO2', $eTags) || str_contains($eName, 'CO2')) {
                    $syncCo2 = $eVal;
                }
                if (in_array('CO3', $eTags) || str_contains($eName, 'CO3')) {
                    $syncCo3 = $eVal;
                }
                if (in_array('CO4', $eTags) || str_contains($eName, 'CO4')) {
                    $syncCo4 = $eVal;
                }
                if (str_contains($eName, 'Series Exam 1') || str_contains($eName, 'Series 1')) {
                    $syncS1_50 = $eVal;
                }
                if (str_contains($eName, 'Series Exam 2') || str_contains($eName, 'Series 2')) {
                    $syncS2_50 = $eVal;
                }
            }

            if ($syncCo1 !== null) $evalRecord->series_1_co1_25 = $syncCo1;
            if ($syncCo2 !== null) $evalRecord->series_1_co2_25 = $syncCo2;
            if ($syncCo3 !== null) $evalRecord->series_2_co3_25 = $syncCo3;
            if ($syncCo4 !== null) $evalRecord->series_2_co4_25 = $syncCo4;

            if ($evalRecord->series_1_co1_25 !== null || $evalRecord->series_1_co2_25 !== null) {
                $evalRecord->series_1_50 = min(50.0, ($evalRecord->series_1_co1_25 ?? 0) + ($evalRecord->series_1_co2_25 ?? 0));
            } elseif ($syncS1_50 !== null) {
                $evalRecord->series_1_50 = $syncS1_50;
                $evalRecord->series_1_co1_25 = round(($syncS1_50 / 2.0) * 2) / 2;
                $evalRecord->series_1_co2_25 = round(($syncS1_50 / 2.0) * 2) / 2;
            }

            if ($evalRecord->series_2_co3_25 !== null || $evalRecord->series_2_co4_25 !== null) {
                $evalRecord->series_2_50 = min(50.0, ($evalRecord->series_2_co3_25 ?? 0) + ($evalRecord->series_2_co4_25 ?? 0));
            } elseif ($syncS2_50 !== null) {
                $evalRecord->series_2_50 = $syncS2_50;
                $evalRecord->series_2_co3_25 = round(($syncS2_50 / 2.0) * 2) / 2;
                $evalRecord->series_2_co4_25 = round(($syncS2_50 / 2.0) * 2) / 2;
            }

            $evalRecord->series_20 = $scaledMark;
            $attPart = (float)($evalRecord->attendance_mark_5 ?? 0);
            $slPart = (float)($evalRecord->self_learning_15 ?? 0);
            $evalRecord->total_cia_40 = min(40.0, (float)round($attPart + $slPart + $scaledMark));
            $esePart = (float)($evalRecord->ese_mark_60 ?? 0);
            $evalRecord->total_marks_100 = min(100.0, (float)round($evalRecord->total_cia_40 + $esePart));
            $evalRecord->save();

            $updated++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => "{$updated} students series marks updated successfully."]);
    }

    /**
     * Get ESE Marks, Grade evaluation, and NBA Attainment Threshold Settings.
     */
    public function getEseMarks($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        $settings = [];
        if ($courseFile && $courseFile->attainment_settings) {
            $settings = is_string($courseFile->attainment_settings) 
                ? json_decode($courseFile->attainment_settings, true) 
                : $courseFile->attainment_settings;
        }

        $eseConfig = $settings['ese_config'] ?? [
            'entry_mode' => 'grades',
            'max_marks' => 60,
            'ese_threshold_grade' => 'D',
            'ese_threshold_percent' => 50.0,
            'cie_threshold_percent' => 50.0,
            'target_student_percent' => 70.0,
        ];

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->where('category', 'ESE')
            ->get()
            ->keyBy('reg_no');

        $boardGrades = \DB::table('student_board_grades')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->keyBy('reg_no');

        $studentList = [];
        $totalStudents = count($students);
        $appearedCount = 0;
        $metTargetCount = 0;
        $maxMarks = (float)($eseConfig['max_marks'] ?? 60);
        $thresholdGrade = $eseConfig['ese_threshold_grade'] ?? $eseConfig['target_grade'] ?? 'D';
        $targetPercent = (float)($eseConfig['ese_threshold_percent'] ?? 50.0);

        foreach ($students as $s) {
            $regNo = $s->reg_no ?: $s->sbte_reg_no;
            $markRecord = $academicMarks->get($regNo);
            $gradeRecord = $boardGrades->get($regNo);

            $markVal = $markRecord ? (float)$markRecord->marks_obtained : null;
            $gradeVal = $gradeRecord ? $gradeRecord->grade : null;

            if ($markVal !== null) {
                $appearedCount++;
                $pct = ($markVal / ($maxMarks > 0 ? $maxMarks : 60)) * 100;
                $calcGrade = \App\Services\AttainmentService::percentageToGrade($pct);
                if (\App\Services\AttainmentService::isGradeMet($calcGrade, $thresholdGrade) || $pct >= $targetPercent) {
                    $metTargetCount++;
                }
            } elseif ($gradeVal !== null && strtoupper(trim($gradeVal)) !== 'FE') {
                $appearedCount++;
                if (\App\Services\AttainmentService::isGradeMet($gradeVal, $thresholdGrade)) {
                    $metTargetCount++;
                }
            }

            $studentList[] = [
                'reg_no' => $regNo,
                'name' => $s->name,
                'roll_no' => $s->roll_no,
                'ese_marks' => $markVal,
                'ese_grade' => $gradeVal,
            ];
        }

        $metPercent = $appearedCount > 0 ? round(($metTargetCount / $appearedCount) * 100, 1) : 0.0;
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);
        $lvl3Val = (float)($eseConfig['level3_percent'] ?? $targetStudentPercent);
        $lvl2Val = (float)($eseConfig['level2_percent'] ?? max(0, $targetStudentPercent - 10));
        $lvl1Val = (float)($eseConfig['level1_percent'] ?? max(0, $targetStudentPercent - 20));

        // Official NBA Attainment Level calculation via AttainmentService
        $level = $appearedCount > 0 ? \App\Services\AttainmentService::calculateBatchLevel($metPercent, $lvl3Val, $lvl2Val, $lvl1Val) : 0;

        return response()->json([
            'status' => 'SUCCESS',
            'config' => $eseConfig,
            'students' => $studentList,
            'summary' => [
                'total_students' => $totalStudents,
                'appeared_count' => $appearedCount,
                'met_target_count' => $metTargetCount,
                'met_target_percent' => $metPercent,
                'attainment_level' => $level,
            ]
        ]);
    }

    /**
     * Bulk Update End Semester Exam (ESE) Marks, Grades & Threshold Settings.
     */
    public function bulkUpdateEseMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $this->ensureSyllabusRegistryExists($batchSubject);

        $entryMode = $request->input('entry_mode', 'marks');
        $maxMarks = (float)$request->input('max_marks', 60);
        $eseThresholdGrade = $request->input('ese_threshold_grade', 'D');
        $eseThresholdPercent = (float)$request->input('ese_threshold_percent', 50.0);
        $cieThresholdPercent = (float)$request->input('cie_threshold_percent', 50.0);
        $targetStudentPercent = (float)$request->input('target_student_percent', 70.0);
        $level3Percent = (float)$request->input('level3_percent', $targetStudentPercent);
        $level2Percent = (float)$request->input('level2_percent', max(0, $targetStudentPercent - 10));
        $level1Percent = (float)$request->input('level1_percent', max(0, $targetStudentPercent - 20));

        $eseConfig = [
            'entry_mode' => $entryMode,
            'max_marks' => $maxMarks,
            'ese_threshold_grade' => $eseThresholdGrade,
            'ese_threshold_percent' => $eseThresholdPercent,
            'cie_threshold_percent' => $cieThresholdPercent,
            'target_student_percent' => $targetStudentPercent,
            // Keep legacy aliases for backwards compatibility
            'target_threshold_percent' => $eseThresholdPercent,
            'target_grade' => $eseThresholdGrade,
            'level3_percent' => $level3Percent,
            'level2_percent' => $level2Percent,
            'level1_percent' => $level1Percent,
        ];

        $courseFile = CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId]
        );
        $existingSettings = is_array($courseFile->attainment_settings)
            ? $courseFile->attainment_settings
            : (json_decode($courseFile->attainment_settings ?? '', true) ?: []);
        
        $existingSettings['ese_config'] = $eseConfig;
        $courseFile->attainment_settings = $existingSettings;
        $courseFile->save();

        // Official SBTE Kerala Diploma Grading Scale
        $gradeToMarkScale = [
            'S' => 0.95, // 90%+ (Outstanding)
            'A' => 0.85, // 80 - 89% (Excellent)
            'B' => 0.75, // 70 - 79% (Very Good)
            'C' => 0.65, // 60 - 69% (Good)
            'D' => 0.55, // 50 - 59% (Average)
            'E' => 0.45, // 40 - 49% (Satisfactory)
            'F' => 0.0,  // Below 40% (Fail)
            'FE' => 0.0,
        ];

        $marks = $request->input('marks', []);
        $grades = $request->input('grades', []);
        $allRegNos = array_unique(array_merge(array_keys($marks), array_keys($grades)));
        $updated = 0;

        foreach ($allRegNos as $regNo) {
            $rawMark = isset($marks[$regNo]) ? trim((string)$marks[$regNo]) : '';
            $rawGrade = isset($grades[$regNo]) ? strtoupper(trim((string)$grades[$regNo])) : '';

            if ($rawMark === '' && $rawGrade === '') continue;

            $numericVal = 0.0;
            $gradeLetter = null;

            if ($rawGrade !== '' && isset($gradeToMarkScale[$rawGrade])) {
                $gradeLetter = $rawGrade;
                if ($rawMark !== '' && is_numeric($rawMark)) {
                    $numericVal = (float)$rawMark;
                } else {
                    $ratio = $gradeToMarkScale[$gradeLetter];
                    $numericVal = round($ratio * $maxMarks, 2);
                }
            } elseif ($rawMark !== '' && is_numeric($rawMark)) {
                $numericVal = (float)$rawMark;
                $pct = $maxMarks > 0 ? ($numericVal / $maxMarks) * 100 : 0;
                if ($pct >= 90) $gradeLetter = 'S';
                elseif ($pct >= 80) $gradeLetter = 'A';
                elseif ($pct >= 70) $gradeLetter = 'B';
                elseif ($pct >= 60) $gradeLetter = 'C';
                elseif ($pct >= 50) $gradeLetter = 'D';
                elseif ($pct >= 40) $gradeLetter = 'E';
                else $gradeLetter = 'F';
            } elseif (isset($gradeToMarkScale[strtoupper($rawMark)])) {
                $gradeLetter = strtoupper($rawMark);
                $ratio = $gradeToMarkScale[$gradeLetter];
                $numericVal = round($ratio * $maxMarks, 2);
            }

            if ($gradeLetter === null) continue;

            \DB::table('academic_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'batch_subject_id' => $subjectId,
                    'category' => 'ESE',
                ],
                [
                    'subject_code' => $batchSubject->subject_code,
                    'co_tag' => 'ALL',
                    'max_marks' => $maxMarks,
                    'marks_obtained' => $numericVal,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

            // Safe update into student_board_grades with foreign key check
            $studentExists = \DB::table('students')->where('reg_no', $regNo)->exists();
            if ($studentExists) {
                $isPassed = ($gradeLetter !== 'F' && $gradeLetter !== 'FE' && $numericVal >= 24);
                \DB::table('student_board_grades')->updateOrInsert(
                    [
                        'reg_no' => $regNo,
                        'subject_code' => $batchSubject->subject_code,
                        'semester' => $batchSubject->semester ?: 1,
                    ],
                    [
                        'grade' => $gradeLetter,
                        'external_marks' => $numericVal,
                        'passed' => $isPassed,
                        'updated_at' => now(),
                    ]
                );
            }

            $updated++;
        }

        $msg = $updated > 0
            ? "Successfully saved threshold settings and updated {$updated} student ESE records."
            : "Attainment threshold settings saved successfully.";

        return response()->json([
            'status' => 'SUCCESS',
            'message' => $msg,
            'updated_count' => $updated
        ]);
    }

    /**
     * Get Real-time Course Attainment Summary Matrix (Direct 80% + Indirect 20%)
     */
    public function getAttainmentSummary($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized']);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) return response()->json(['status' => 'ERROR', 'message' => 'Subject not found']);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        
        $settings = [];
        if ($courseFile && $courseFile->attainment_settings) {
            $settings = is_string($courseFile->attainment_settings)
                ? json_decode($courseFile->attainment_settings, true)
                : $courseFile->attainment_settings;
        }

        $eseConfig = $settings['ese_config'] ?? [];
        $thresholdGrade = $eseConfig['ese_threshold_grade'] ?? $eseConfig['target_grade'] ?? 'D';
        $cieThreshold = (float)($eseConfig['cie_threshold_percent'] ?? 50.0);
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);
        $lvl3Val = (float)($eseConfig['level3_percent'] ?? $targetStudentPercent);
        $lvl2Val = (float)($eseConfig['level2_percent'] ?? max(0, $targetStudentPercent - 10));
        $lvl1Val = (float)($eseConfig['level1_percent'] ?? max(0, $targetStudentPercent - 20));
        $maxEseMarks = (float)($eseConfig['max_marks'] ?? 60.0);

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $boardGrades = \DB::table('student_board_grades')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->keyBy('reg_no');

        // Evaluate batch ESE Attainment (SBTE Kerala Scale)
        $eseAppeared = 0;
        $eseMet = 0;
        foreach ($students as $stud) {
            $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
            $studMarks = $academicMarks->get($regNo, collect());
            $eseRecord = $studMarks->where('category', 'ESE')->first();
            $gradeRecord = $boardGrades->get($regNo);

            $markVal = $eseRecord ? (float)$eseRecord->marks_obtained : null;
            $gradeVal = $gradeRecord ? $gradeRecord->grade : null;

            if ($markVal !== null) {
                $eseAppeared++;
                $pct = ($markVal / ($maxEseMarks > 0 ? $maxEseMarks : 60)) * 100;
                $calcGrade = \App\Services\AttainmentService::percentageToGrade($pct);
                if (\App\Services\AttainmentService::isGradeMet($calcGrade, $thresholdGrade) || $pct >= (float)($eseConfig['ese_threshold_percent'] ?? 50.0)) {
                    $eseMet++;
                }
            } elseif ($gradeVal !== null && strtoupper(trim($gradeVal)) !== 'FE') {
                $eseAppeared++;
                if (\App\Services\AttainmentService::isGradeMet($gradeVal, $thresholdGrade)) {
                    $eseMet++;
                }
            }
        }
        $eseMetPct = $eseAppeared > 0 ? round(($eseMet / $eseAppeared) * 100, 1) : 0.0;
        $eseLevel = \App\Services\AttainmentService::calculateBatchLevel($eseMetPct, $lvl3Val, $lvl2Val, $lvl1Val);

        $exitSurvey = \DB::table('course_exit_surveys')
            ->where('batch_subject_id', $subjectId)
            ->first();
        
        $exitResponses = collect();
        if ($exitSurvey) {
            $exitResponses = \DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $exitSurvey->id)
                ->get();
        }

        $coList = ['CO1', 'CO2', 'CO3', 'CO4'];
        $matrix = [];
        $directSum = 0;
        $indirectSum = 0;
        $overallSum = 0;
        $count = count($coList);

        foreach ($coList as $coTag) {
            $totalAssessed = 0;
            $cieMet = 0;

            foreach ($students as $stud) {
                $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
                $studMarks = $academicMarks->get($regNo, collect());
                $coMarks = $studMarks->where('co_tag', $coTag);

                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();

                // Self Study marks (Max 15)
                $selfStudyScore = ($assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0)
                                + ($mcqMark ? (float)$mcqMark->marks_obtained : 0.0)
                                + ($act3Mark ? (float)$act3Mark->marks_obtained : 0.0)
                                + ($act4Mark ? (float)$act4Mark->marks_obtained : 0.0)
                                + ($act5Mark ? (float)$act5Mark->marks_obtained : 0.0);

                // Series Exam marks mapped to this CO (excluding attendance)
                $seriesMark = $coMarks->where('category', 'Series Exam')->first()
                    ?: $studMarks->where('category', 'Series Exam')->first();
                $seriesScore = $seriesMark ? ((float)$seriesMark->marks_obtained / 2.0) : 0.0;

                // Academic CIE Score (Max 25 per CO: 15 self-study + 10 series)
                $cieScore = $selfStudyScore + $seriesScore;
                $maxCoCie = 25.0;

                $totalAssessed++;
                $pct = ($cieScore / $maxCoCie) * 100;
                if ($pct >= $cieThreshold) {
                    $cieMet++;
                }
            }

            $cieMetPct = $totalAssessed > 0 ? round(($cieMet / $totalAssessed) * 100, 1) : 0.0;
            $cieLevel = \App\Services\AttainmentService::calculateBatchLevel($cieMetPct, $lvl3Val, $lvl2Val, $lvl1Val);

            // Direct Attainment: 30% CIE Level + 70% ESE Level (NBA Polytechnic criteria)
            $directLevel = \App\Services\AttainmentService::calculateDirectAttainment($cieLevel, $eseLevel, 0.30, 0.70);
            $directPct = round(($directLevel / 3.0) * 100, 1);

            // Indirect Attainment: Course Exit Survey (scale 1.0 - 3.0)
            $indirectRating = 0.0;
            if (count($exitResponses) > 0) {
                if ($coTag === 'CO1') {
                    $indirectRating = ($exitResponses->avg('co1_q1') + $exitResponses->avg('co1_q2')) / 2;
                } elseif ($coTag === 'CO2') {
                    $indirectRating = ($exitResponses->avg('co2_q3') + $exitResponses->avg('co2_q4')) / 2;
                } elseif ($coTag === 'CO3') {
                    $indirectRating = ($exitResponses->avg('co3_q5') + $exitResponses->avg('co3_q6')) / 2;
                } else {
                    $indirectRating = ($exitResponses->avg('co4_q7') + $exitResponses->avg('co4_q8') + $exitResponses->avg('co4_q9')) / 3;
                }
            }
            $indirectPct = round(($indirectRating / 3.0) * 100, 1);

            // Overall Combined: 80% Direct + 20% Indirect
            $overallLevel = \App\Services\AttainmentService::calculateOverallAttainment($directLevel, $indirectRating, 0.80, 0.20);
            $overallPct = round(($overallLevel / 3.0) * 100, 1);

            $levelStr = \App\Services\AttainmentService::getLevelLabel((int)round($overallLevel));
            $attained = $overallLevel >= 1.0 && $overallPct >= $cieThreshold;

            $matrix[] = [
                'co' => $coTag,
                'direct_percent' => $directPct,
                'indirect_percent' => $indirectPct,
                'indirect_rating' => round($indirectRating, 2),
                'overall_percent' => $overallPct,
                'target_benchmark' => $targetStudentPercent,
                'attainment_level' => $levelStr,
                'attained' => $attained,
            ];

            $directSum += $directPct;
            $indirectSum += $indirectPct;
            $overallSum += $overallPct;
        }

        $avgDirect = $count > 0 ? round($directSum / $count, 1) : 0.0;
        $avgIndirect = $count > 0 ? round($indirectSum / $count, 1) : 0.0;
        $avgOverall = $count > 0 ? round($overallSum / $count, 1) : 0.0;

        $overallLevel = \App\Services\AttainmentService::getLevelLabel((int)round(($avgOverall / 100.0) * 3.0));

        return response()->json([
            'status' => 'SUCCESS',
            'summary' => [
                'direct_attainment_percent' => $avgDirect,
                'indirect_attainment_percent' => $avgIndirect,
                'overall_attainment_percent' => $avgOverall,
                'overall_attainment_level' => $overallLevel,
                'target_benchmark' => $targetStudentPercent,
            ],
            'matrix' => $matrix
        ]);
    }


    /**
     * Print Series Exam Question Paper.
     */
    public function printSeriesExamQp($examId)
    {
        $exam = \App\Models\SeriesExam::find($examId);
        if (!$exam) abort(404);

        $batchSubject = BatchSubject::find($exam->batch_subject_id);
        if (!$batchSubject) abort(404);

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);

        return view('r26.series_qp_print', compact('batchSubject', 'classroom', 'exam', 'departmentName'));
    }

    /**
     * Print Series Exam Answer Key / Evaluation Scheme.
     */
    public function printSeriesExamScheme($examId)
    {
        $exam = \App\Models\SeriesExam::find($examId);
        if (!$exam) abort(404);

        $batchSubject = BatchSubject::find($exam->batch_subject_id);
        if (!$batchSubject) abort(404);

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);

        return view('r26.series_scheme_print', compact('batchSubject', 'classroom', 'exam', 'departmentName'));
    }

    /**
     * Print Series Examination Detailed Marks Report
     */
    public function printSeriesExamMarks($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Dedicated redirection for Revision 2026 English (1001)
        if ($batchSubject->syllabus_revision_code === 'REV2026' && (str_contains($batchSubject->subject_code ?? '', '1001') || stripos($batchSubject->subject_name ?? '', 'English') !== false)) {
            return redirect('/r26/classroom/english-1001/' . $subjectId);
        }

        $isPractical = (str_contains(strtolower($batchSubject->subject_type ?? ''), 'lab') || str_contains(strtolower($batchSubject->subject_type ?? ''), 'practical') || str_contains(strtolower($batchSubject->subject_type ?? ''), 'drawing'));
        if ($isPractical) {
            return app(\App\Http\Controllers\VirtualClassroomPracticalController::class)->printSeriesReport($subjectId);
        }

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();

        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $seriesExams = \App\Models\SeriesExam::where('batch_subject_id', $subjectId)->get();

        $studentCiaData = $students->map(function ($student) use ($academicMarks, $seriesExams) {
            $studentMarks = $academicMarks->get($student->reg_no, collect());
            
            $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
            $seriesExamMarks = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;
            
            $examMarks = [];
            foreach ($seriesExams as $ex) {
                $eMark = $studentMarks->where('category', 'Series Exam: ' . $ex->exam_name)->first();
                $examMarks[$ex->id] = $eMark ? (float)$eMark->marks_obtained : 0.0;
            }

            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'series_exam_marks' => $seriesExamMarks,
                'exam_marks' => $examMarks
            ];
        });

        return view('r26.series_marks_print', compact('batchSubject', 'classroom', 'students', 'studentCiaData', 'seriesExams', 'departmentName'));
    }

    /**
     * Print Consolidated CIE Marks Report
     */
    public function printInternalMarksheet($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) abort(404);

        $isPractical = (str_contains(strtolower($batchSubject->subject_type ?? ''), 'lab') || str_contains(strtolower($batchSubject->subject_type ?? ''), 'practical') || str_contains(strtolower($batchSubject->subject_type ?? ''), 'drawing'));
        if ($isPractical) {
            return app(\App\Http\Controllers\VirtualClassroomPracticalController::class)->printReport($subjectId);
        }

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) abort(404);
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) abort(404);

        $attendanceData = \DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $selfLearningConfigs = $courseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO2' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO3' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO4' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
        ];

        $testConfigs = \DB::table('test_configs')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('classroom_id', $batchSubject->classroom_id)
            ->get();
            
        $testIds = $testConfigs->pluck('test_id')->toArray();
        
        $testAttempts = \DB::table('test_attempts')
            ->whereIn('test_id', $testIds)
            ->where('status', 'Completed')
            ->get()
            ->groupBy('reg_no');

        $submissions = \DB::table('student_task_submissions')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('category', 'Assignment')
            ->get()
            ->groupBy('reg_no');

        $seriesExams = \App\Models\SeriesExam::where('batch_subject_id', $subjectId)->get();
        $evaluations = \App\Models\R26TheoryEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $studentCiaData = $students->map(function ($student) use ($attendanceData, $academicMarks, $subjectId, $batchSubject, $selfLearningConfigs, $testConfigs, $testAttempts, $submissions, $seriesExams, $evaluations) {
            $studentSubmissions = $submissions->get($student->reg_no, collect());
            $studentAttendance = $attendanceData->get($student->reg_no, collect());
            $totalAttendance = $studentAttendance->count();
            $present = $studentAttendance->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAttendance > 0 ? ($present / $totalAttendance) * 100 : 0.00;
            $attPercentage = round($attPercentage, 2);

            $ev = $evaluations->get($student->reg_no);
            if ($ev && $ev->attendance_override_pct !== null) {
                $attPercentage = round((float)$ev->attendance_override_pct, 2);
            }
            
            // Table 2.1 Attendance Marks Conversion
            $attMarks = 0;
            if ($ev && $ev->attendance_mark_5 !== null) {
                $attMarks = (float)$ev->attendance_mark_5;
            } else {
                if ($attPercentage >= 90) {
                    $attMarks = 5;
                } elseif ($attPercentage >= 80) {
                    $attMarks = 4;
                } elseif ($attPercentage >= 75) {
                    $attMarks = 3;
                } elseif ($attPercentage >= 70) {
                    $attMarks = 2;
                } elseif ($attPercentage >= 65) {
                    $attMarks = 1;
                } else {
                    $attMarks = 0;
                }
            }

            // Attendance eligibility & condonation possibilities
            $attStatus = 'Eligible';
            $attColor = 'emerald-500';
            if ($attPercentage >= 75) {
                $attStatus = 'Eligible';
                $attColor = 'emerald-450';
            } elseif ($attPercentage >= 60) {
                $attStatus = 'Condonation Possible';
                $attColor = 'amber-500';
            } elseif ($attPercentage >= 50) {
                $attStatus = 'Special Condonation';
                $attColor = 'purple-400';
            } else {
                $attStatus = 'Shortage (Detained)';
                $attColor = 'rose-500';
            }
            
            $studentMarks = $academicMarks->get($student->reg_no, collect());
            $studentAttempts = $testAttempts->get($student->reg_no, collect());
            
            $coDetails = [];
            $totalAvgSum = 0.0;
            
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $coMarks = $studentMarks->where('co_tag', $coTag);
                
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;

                // MCQ Automated Mark logic
                if ($mcqMark) {
                    $valMcq = (float)$mcqMark->marks_obtained;
                } else {
                    $valMcq = 0.0;
                    $coTest = $testConfigs->filter(function($tc) use ($coTag) {
                        $selected = json_decode($tc->selected_cos, true) ?: [];
                        return in_array($coTag, $selected);
                    })->first();
                    if ($coTest) {
                        $attemptsForTest = $studentAttempts->where('test_id', $coTest->test_id);
                        $maxScore = $attemptsForTest->max('total_score');
                        $mcqMax = (float)($coTest->mcq_count ?: 10);
                        $configMaxMCQ = (float)($selfLearningConfigs[$coTag]['mcq'] ?? 5.0);
                        if ($mcqMax > 0 && $maxScore !== null) {
                            $valMcq = round(($maxScore / $mcqMax) * $configMaxMCQ, 2);
                        }
                    }
                }
                
                $coTotal = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                $totalAvgSum += $coTotal;
            }
            
            $selfLearningMarks = round($totalAvgSum / 4, 2);
            if ($ev && $ev->self_learning_15 !== null) {
                $selfLearningMarks = (float)$ev->self_learning_15;
            }

            $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
            $seriesExamMarks = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;
            if ($ev && $ev->series_20 !== null) {
                $seriesExamMarks = (float)$ev->series_20;
            }

            $totalCia = $ev && $ev->total_cia_40 !== null ? (float)round($ev->total_cia_40) : (float)round($attMarks + $selfLearningMarks + $seriesExamMarks);

            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'attendance_percent' => round($attPercentage, 2),
                'attendance_marks' => $attMarks,
                'attendance_status' => $attStatus,
                'attendance_color' => $attColor,
                'self_learning_marks' => $selfLearningMarks,
                'series_exam_marks' => $seriesExamMarks,
                'total_cia' => $totalCia
            ];
        });

        return view('r26.internals_cie_print', compact('batchSubject', 'classroom', 'studentCiaData', 'departmentName'));
    }

    /**
     * Print Consolidated Student Results Report (CIE & ESE)
     */
    public function printFinalResults($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) abort(404);

        $isPractical = (str_contains(strtolower($batchSubject->subject_type ?? ''), 'lab') || str_contains(strtolower($batchSubject->subject_type ?? ''), 'practical') || str_contains(strtolower($batchSubject->subject_type ?? ''), 'drawing'));
        if ($isPractical) {
            return app(\App\Http\Controllers\VirtualClassroomPracticalController::class)->printFinalResults($subjectId);
        }

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) abort(404);
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) abort(404);

        $attendanceData = \DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $selfLearningConfigs = $courseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO2' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO3' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO4' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
        ];

        $testConfigs = \DB::table('test_configs')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('classroom_id', $batchSubject->classroom_id)
            ->get();
            
        $testIds = $testConfigs->pluck('test_id')->toArray();
        
        $testAttempts = \DB::table('test_attempts')
            ->whereIn('test_id', $testIds)
            ->where('status', 'Completed')
            ->get()
            ->groupBy('reg_no');

        $submissions = \DB::table('student_task_submissions')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('category', 'Assignment')
            ->get()
            ->groupBy('reg_no');

        $seriesExams = \App\Models\SeriesExam::where('batch_subject_id', $subjectId)->get();
        $evaluations = \App\Models\R26TheoryEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $boardGrades = \DB::table('student_board_grades')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->keyBy('reg_no');

        $studentCiaData = $students->map(function ($student) use ($attendanceData, $academicMarks, $subjectId, $batchSubject, $selfLearningConfigs, $testConfigs, $testAttempts, $submissions, $seriesExams, $boardGrades, $evaluations) {
            $studentSubmissions = $submissions->get($student->reg_no, collect());
            $studentAttendance = $attendanceData->get($student->reg_no, collect());
            $totalAttendance = $studentAttendance->count();
            $present = $studentAttendance->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAttendance > 0 ? ($present / $totalAttendance) * 100 : 0.00;
            $attPercentage = round($attPercentage, 2);

            $ev = $evaluations->get($student->reg_no);
            if ($ev && $ev->attendance_override_pct !== null) {
                $attPercentage = round((float)$ev->attendance_override_pct, 2);
            } elseif ($ev && $ev->attendance_pct !== null) {
                $attPercentage = round((float)$ev->attendance_pct, 2);
            }
            
            // Table 2.1 Attendance Marks Conversion
            $attMarks = 0;
            if ($ev && $ev->attendance_mark_5 !== null) {
                $attMarks = (float)$ev->attendance_mark_5;
            } else {
                if ($attPercentage >= 90) {
                    $attMarks = 5;
                } elseif ($attPercentage >= 80) {
                    $attMarks = 4;
                } elseif ($attPercentage >= 75) {
                    $attMarks = 3;
                } elseif ($attPercentage >= 70) {
                    $attMarks = 2;
                } elseif ($attPercentage >= 65) {
                    $attMarks = 1;
                } else {
                    $attMarks = 0;
                }
            }

            // Attendance eligibility & condonation possibilities
            $attStatus = 'Eligible';
            $attColor = 'emerald-500';
            if ($attPercentage >= 75) {
                $attStatus = 'Eligible';
                $attColor = 'emerald-450';
            } elseif ($attPercentage >= 60) {
                $attStatus = 'Condonation Possible';
                $attColor = 'amber-500';
            } elseif ($attPercentage >= 50) {
                $attStatus = 'Special Condonation';
                $attColor = 'purple-400';
            } else {
                $attStatus = 'Shortage (Detained)';
                $attColor = 'rose-500';
            }
            
            $studentMarks = $academicMarks->get($student->reg_no, collect());
            $studentAttempts = $testAttempts->get($student->reg_no, collect());
            
            $totalAvgSum = 0.0;
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $coMarks = $studentMarks->where('co_tag', $coTag);
                
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;

                if ($mcqMark) {
                    $valMcq = (float)$mcqMark->marks_obtained;
                } else {
                    $valMcq = 0.0;
                    $coTest = $testConfigs->filter(function($tc) use ($coTag) {
                        $selected = json_decode($tc->selected_cos, true) ?: [];
                        return in_array($coTag, $selected);
                    })->first();
                    if ($coTest) {
                        $attemptsForTest = $studentAttempts->where('test_id', $coTest->test_id);
                        $maxScore = $attemptsForTest->max('total_score');
                        $mcqMax = (float)($coTest->mcq_count ?: 10);
                        $configMaxMCQ = (float)($selfLearningConfigs[$coTag]['mcq'] ?? 5.0);
                        if ($mcqMax > 0 && $maxScore !== null) {
                            $valMcq = round(($maxScore / $mcqMax) * $configMaxMCQ, 2);
                        }
                    }
                }
                
                $coTotal = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                $totalAvgSum += $coTotal;
            }
            
            $selfLearningMarks = round($totalAvgSum / 4, 2);
            if ($ev && $ev->self_learning_15 !== null) {
                $selfLearningMarks = (float)$ev->self_learning_15;
            }

            $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
            $seriesExamMarks = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;
            if ($ev && $ev->series_20 !== null) {
                $seriesExamMarks = (float)$ev->series_20;
            }

            $totalCiaMarks = $ev && $ev->total_cia_40 !== null ? (float)round($ev->total_cia_40) : (float)round($attMarks + $selfLearningMarks + $seriesExamMarks);

            $eseRecord = $studentMarks->where('category', 'ESE')->first();
            $gradeRecord = $boardGrades->get($student->reg_no) ?: $boardGrades->get($student->sbte_reg_no);
            
            $eseMarks = $eseRecord ? (float)$eseRecord->marks_obtained : null;
            $eseGrade = $gradeRecord ? trim($gradeRecord->grade) : null;
            
            $gradeToMarkScale = [
                'S' => 57.0, // 95% of 60
                'A' => 51.0, // 85% of 60
                'B' => 45.0, // 75% of 60
                'C' => 39.0, // 65% of 60
                'D' => 33.0, // 55% of 60
                'E' => 27.0, // 45% of 60
                'F' => 0.0,
                'FE' => 0.0,
            ];

            if ($ev && $ev->ese_mark_60 !== null) {
                $eseMarks = (float)$ev->ese_mark_60;
            }
            if ($ev && !empty($ev->ese_grade)) {
                $eseGrade = $ev->ese_grade;
            }

            if (empty($eseGrade) && $eseMarks !== null && $eseMarks > 0) {
                $pct = ($eseMarks / 60.0) * 100.0;
                if ($pct >= 90) $eseGrade = 'S';
                elseif ($pct >= 80) $eseGrade = 'A';
                elseif ($pct >= 70) $eseGrade = 'B';
                elseif ($pct >= 60) $eseGrade = 'C';
                elseif ($pct >= 50) $eseGrade = 'D';
                elseif ($pct >= 40) $eseGrade = 'E';
                else $eseGrade = 'F';
            } elseif (!empty($eseGrade) && ($eseMarks === null || $eseMarks == 0)) {
                $eseMarks = $gradeToMarkScale[strtoupper($eseGrade)] ?? 0.0;
            }

            $finalEseMarks = $eseMarks !== null ? (float)$eseMarks : 0.0;

            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'attendance_percent' => round($attPercentage, 2),
                'attendance_marks' => $attMarks,
                'attendance_status' => $attStatus,
                'attendance_color' => $attColor,
                'self_learning_marks' => $selfLearningMarks,
                'series_exam_marks' => $seriesExamMarks,
                'total_cia' => $totalCiaMarks,
                'ese_marks' => $finalEseMarks,
                'ese_grade' => $eseGrade ?: ''
            ];
        });

        return view('r26.student_final_results_print', compact('batchSubject', 'classroom', 'studentCiaData', 'departmentName'));
    }

    /**
     * Print NBA 2026 Direct/Indirect CO-PO Attainment Report
     */
    public function printAttainmentReport($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) abort(404);

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);
        if (!$classroom) {
            $classroom = (object)[
                'classroom_id' => $batchSubject->classroom_id,
                'classroom_name' => $batchSubject->classroom_id,
                'department' => $departmentName,
                'branch' => $departmentName,
                'current_semester' => $batchSubject->semester ?? 1
            ];
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first()
            ?: \App\Models\R26PracticumCourseFile::where('batch_subject_id', $subjectId)->first();

        $copoData = [];
        $settings = [];
        if ($courseFile) {
            $rawCopo = $courseFile->parsed_copo ?? $courseFile->parsed_copo_data ?? [];
            $copoData = is_string($rawCopo) ? json_decode($rawCopo, true) : ($rawCopo ?: []);
            $settings = is_string($courseFile->attainment_settings) ? json_decode($courseFile->attainment_settings, true) : ($courseFile->attainment_settings ?: []);
        }
        $mappings = $copoData['mappings'] ?? [];

        // Let's get direct assessment marks
        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $practicumSeriesTheory = \DB::table('r26_practicum_series_theory')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Exit Survey Responses for Indirect
        $exitSurvey = \DB::table('course_exit_surveys')
            ->where('batch_subject_id', $subjectId)
            ->first();
        
        $exitResponses = collect();
        if ($exitSurvey) {
            $exitResponses = \DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $exitSurvey->id)
                ->get();
        }
        $eseConfig = $settings['ese_config'] ?? [];
        $thresholdGrade = $eseConfig['ese_threshold_grade'] ?? $eseConfig['target_grade'] ?? 'D';
        $cieThreshold = (float)($eseConfig['cie_threshold_percent'] ?? 50.0);
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);
        $lvl3Val = (float)($eseConfig['level3_percent'] ?? $targetStudentPercent);
        $lvl2Val = (float)($eseConfig['level2_percent'] ?? max(0, $targetStudentPercent - 10));
        $lvl1Val = (float)($eseConfig['level1_percent'] ?? max(0, $targetStudentPercent - 20));
        $maxEseMarks = (float)($eseConfig['max_marks'] ?? 60.0);

        $boardGrades = \DB::table('student_board_grades')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->keyBy('reg_no');

        // Evaluate batch ESE Attainment (SBTE Kerala Scale)
        $eseAppeared = 0;
        $eseMet = 0;
        foreach ($students as $stud) {
            $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
            $studMarks = $academicMarks->get($regNo, collect());
            $eseRecord = $studMarks->where('category', 'ESE')->first();
            $gradeRecord = $boardGrades->get($regNo);

            $markVal = $eseRecord ? (float)$eseRecord->marks_obtained : null;
            $gradeVal = $gradeRecord ? $gradeRecord->grade : null;

            if ($markVal !== null) {
                $eseAppeared++;
                $pct = ($markVal / ($maxEseMarks > 0 ? $maxEseMarks : 60)) * 100;
                $calcGrade = \App\Services\AttainmentService::percentageToGrade($pct);
                if (\App\Services\AttainmentService::isGradeMet($calcGrade, $thresholdGrade) || $pct >= (float)($eseConfig['ese_threshold_percent'] ?? 50.0)) {
                    $eseMet++;
                }
            } elseif ($gradeVal !== null && strtoupper(trim($gradeVal)) !== 'FE') {
                $eseAppeared++;
                if (\App\Services\AttainmentService::isGradeMet($gradeVal, $thresholdGrade)) {
                    $eseMet++;
                }
            }
        }
        $eseMetPct = $eseAppeared > 0 ? round(($eseMet / $eseAppeared) * 100, 1) : 0.0;
        $eseLevel = \App\Services\AttainmentService::calculateBatchLevel($eseMetPct, $lvl3Val, $lvl2Val, $lvl1Val);
        
        $directStats = [];
        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $totalAssessed = 0;
            $cieMet = 0;
            
            foreach ($students as $stud) {
                $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
                $studMarks = $academicMarks->get($regNo, collect());
                
                $coMarks = $studMarks->where('co_tag', $coTag);
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $selfStudyScore = ($assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0)
                                + ($mcqMark ? (float)$mcqMark->marks_obtained : 0.0)
                                + ($act3Mark ? (float)$act3Mark->marks_obtained : 0.0)
                                + ($act4Mark ? (float)$act4Mark->marks_obtained : 0.0)
                                + ($act5Mark ? (float)$act5Mark->marks_obtained : 0.0);
                
                // Series Exam marks mapped to this CO (excluding attendance)
                $seriesMark = $coMarks->where('category', 'Series Exam')->first()
                    ?: $studMarks->where('category', 'Series Exam')->first();
                $seriesScore = $seriesMark ? ((float)$seriesMark->marks_obtained / 2.0) : 0.0;
                if ($seriesScore == 0.0 && $practicumSeriesTheory->has($regNo)) {
                    $coSeriesMap = [
                        'CO1' => ['Series 1', 'CO1'],
                        'CO2' => ['Series 2', 'CO2'],
                        'CO3' => ['Series 3', 'CO3'],
                        'CO4' => ['Series 4', 'CO4'],
                    ];
                    $tags = $coSeriesMap[$coTag] ?? [$coTag];
                    $pRec = $practicumSeriesTheory->get($regNo, collect())->whereIn('series_no', $tags)->first();
                    $seriesScore = $pRec ? (float)$pRec->total_score_50 : 0.0;
                }
                
                $cieScore = $selfStudyScore + $seriesScore;
                $maxCoCie = 25.0;
                
                $totalAssessed++;
                $pct = ($cieScore / $maxCoCie) * 100;
                if ($pct >= $cieThreshold) {
                    $cieMet++;
                }
            }
            
            $cieMetPercentage = $totalAssessed > 0 ? ($cieMet / $totalAssessed) * 100 : 0.0;
            $cieLevel = \App\Services\AttainmentService::calculateBatchLevel($cieMetPercentage, $lvl3Val, $lvl2Val, $lvl1Val);
            
            // Direct Attainment: 30% CIE + 70% ESE (or CIE level if ESE not yet conducted)
            $directLevel = $eseAppeared > 0
                ? \App\Services\AttainmentService::calculateDirectAttainment($cieLevel, $eseLevel, 0.30, 0.70)
                : (float)$cieLevel;
            
            $directStats[$coTag] = [
                'met_percent' => round($cieMetPercentage, 1),
                'cie_level' => $cieLevel,
                'ese_level' => $eseLevel,
                'level' => $directLevel
            ];
        }

        $indirectStats = [];
        $exitResponsesCount = count($exitResponses);
        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $level = 0.0;
            if ($exitResponsesCount > 0) {
                if ($coTag === 'CO1') {
                    $avgRating = ($exitResponses->avg('co1_q1') + $exitResponses->avg('co1_q2')) / 2;
                } elseif ($coTag === 'CO2') {
                    $avgRating = ($exitResponses->avg('co2_q3') + $exitResponses->avg('co2_q4')) / 2;
                } elseif ($coTag === 'CO3') {
                    $avgRating = ($exitResponses->avg('co3_q5') + $exitResponses->avg('co3_q6')) / 2;
                } else {
                    $avgRating = ($exitResponses->avg('co4_q7') + $exitResponses->avg('co4_q8') + $exitResponses->avg('co4_q9')) / 3;
                }
                $level = round($avgRating, 2);
            }
            $indirectStats[$coTag] = [
                'level' => $level
            ];
        }

        $combinedStats = [];
        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $direct = $directStats[$coTag]['level'];
            $indirect = $indirectStats[$coTag]['level'];
            $combined = (0.80 * $direct) + (0.20 * $indirect);
            $combinedStats[$coTag] = round($combined, 2);
        }

        $poAttainments = [];
        for ($p = 1; $p <= 11; $p++) {
            $poName = "PO" . $p;
            $sumWeight = 0;
            $sumAttainment = 0;
            
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $correlation = isset($mappings[$coTag][$poName]) ? (int)$mappings[$coTag][$poName] : 0;
                if ($correlation > 0) {
                    $sumWeight += $correlation;
                    $sumAttainment += $combinedStats[$coTag] * $correlation;
                }
            }
            
            $poAttainments[$poName] = [
                'value' => $sumWeight > 0 ? round($sumAttainment / $sumWeight, 2) : 0.0,
                'weight' => $sumWeight
            ];
        }

        return view('r26.attainment_report_print', compact('batchSubject', 'classroom', 'directStats', 'indirectStats', 'combinedStats', 'poAttainments', 'mappings', 'departmentName', 'eseConfig'));
    }

    public function viewCourseFile($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Dedicated redirection for Revision 2026 English (1001)
        if ($batchSubject->syllabus_revision_code === 'REV2026' && (str_contains($batchSubject->subject_code ?? '', '1001') || stripos($batchSubject->subject_name ?? '', 'English') !== false)) {
            return redirect('/r26/classroom/english-1001/' . $subjectId);
        }

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        // Initialize / Get R26 course file record
        $courseFile = R26CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId, 'academic_year' => '2026-2027'],
            ['status' => 'Draft']
        );

        // Seed 25 documents
        $docs = [
            1 => 'Class Time table (current semester Program timetable)',
            2 => 'Faculty Workload',
            3 => 'Student List with register numbers',
            4 => 'Course Syllabus with Recommended Books (SITTTR)',
            5 => 'Course information sheet',
            6 => 'Course outcomes & CO-PO Mappings',
            7 => 'Academic calender & Semester Layout',
            8 => 'Course Plan / Lesson Planner',
            9 => 'Course log and Attendance',
            10 => 'Internal Exam Question Papers CO 1,2,3,4 with mark splitup / Scheme',
            11 => 'Internal Examination Result Analysis NBA',
            12 => 'Weaker student coaching schedule and proof',
            13 => 'Teaching and Learning Methods Proof - handouts, capsule notes etc.',
            14 => 'Assignment questions with rubrics',
            15 => 'Internal Marks - SBTE (CIA)',
            16 => 'Grade Sheet - Proof of CO evaluations',
            17 => 'External Exam Question Papers / Question bank',
            18 => 'SBTE examination result',
            19 => 'Attainment of Course Outcome (CO) Co-Po-Pso Map',
            20 => 'Attainment of PO/PSO report',
            21 => 'Mid semester survey & report',
            22 => 'End semester / Course exit survey & report',
            23 => 'Internal Examination sample answer scripts',
            24 => 'Assignment sample scripts',
            25 => 'Others'
        ];
        foreach ($docs as $no => $name) {
            R26CourseFileDocument::firstOrCreate([
                'r26_course_file_id' => $courseFile->id,
                'document_number' => $no
            ], [
                'document_name' => $name,
                'is_checked' => false,
                'remarks' => ''
            ]);
        }

        $documents = R26CourseFileDocument::where('r26_course_file_id', $courseFile->id)
            ->orderBy('document_number', 'asc')
            ->get();

        $activeCalendar = \DB::table('academic_calendars')->orderBy('id', 'desc')->first();
        $calendarId = $activeCalendar ? $activeCalendar->id : null;

        return view('r26.course_file_preparation', compact('batchSubject', 'classroom', 'courseFile', 'documents', 'calendarId'));
    }

    public function saveCourseFileDoc(Request $request, $subjectId)
    {
        $courseFile = R26CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course file record not found.'], 404);
        }

        $docId = $request->input('doc_id');
        $doc = R26CourseFileDocument::where('r26_course_file_id', $courseFile->id)->where('id', $docId)->first();
        if (!$doc) {
            return response()->json(['status' => 'ERROR', 'message' => 'Document not found.'], 404);
        }

        $doc->is_checked = filter_var($request->input('is_checked'), FILTER_VALIDATE_BOOLEAN);
        $doc->remarks = $request->input('remarks', '');
        $doc->save();

        // If all 25 are checked, mark course file as Complete, else Draft
        $totalChecked = R26CourseFileDocument::where('r26_course_file_id', $courseFile->id)
            ->where('is_checked', true)
            ->count();

        $courseFile->status = ($totalChecked === 25) ? 'Complete' : 'Draft';
        $courseFile->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Document status updated successfully.',
            'file_status' => $courseFile->status,
            'checked_count' => $totalChecked
        ]);
    }

    public function uploadCourseFileDocAttachment(Request $request, $subjectId)
    {
        $courseFile = R26CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course file record not found.'], 404);
        }

        $docId = $request->input('doc_id');
        $doc = R26CourseFileDocument::where('r26_course_file_id', $courseFile->id)->where('id', $docId)->first();
        if (!$doc) {
            return response()->json(['status' => 'ERROR', 'message' => 'Document not found.'], 404);
        }

        if (!$request->hasFile('file')) {
            return response()->json(['status' => 'ERROR', 'message' => 'No file uploaded.'], 400);
        }

        $file = $request->file('file');
        $fileName = 'doc_' . $doc->document_number . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/r26_course_files', $fileName);
        $publicUrl = 'storage/r26_course_files/' . $fileName;

        $doc->data_payload = json_encode(['file_path' => $publicUrl]);
        $doc->is_checked = true; // Auto-verify upon successful upload
        $doc->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'File uploaded successfully.',
            'file_path' => $publicUrl
        ]);
    }

    public function printCourseFilePdf($subjectId)
    {
        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Dedicated redirection for Revision 2026 English (1001)
        if ($batchSubject->syllabus_revision_code === 'REV2026' && (str_contains($batchSubject->subject_code ?? '', '1001') || stripos($batchSubject->subject_name ?? '', 'English') !== false)) {
            return redirect('/r26/classroom/english-1001/' . $subjectId);
        }

        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $departmentName = $this->getDepartmentName($batchSubject, $classroom);

        $courseFile = R26CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) {
            abort(404, 'Course file not initialized.');
        }

        $documents = R26CourseFileDocument::where('r26_course_file_id', $courseFile->id)
            ->orderBy('document_number', 'asc')
            ->get();

        try {
            $pdf = \PDF::loadView('r26.course_file_pdf', compact('batchSubject', 'classroom', 'courseFile', 'documents', 'departmentName'));
            $pdf->setPaper('a4', 'portrait');
            
            $fileName = 'CourseFile_R2026_' . ($batchSubject->subject_code ?? 'Sub') . '.pdf';
            
            $path = 'public/course_files_r2026/' . $fileName;
            \Storage::put($path, $pdf->output());
            
            $courseFile->generated_pdf_path = 'storage/course_files_r2026/' . $fileName;
            $courseFile->save();

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Ensure syllabus_registry entry exists for foreign key constraint in academic_marks
     */
    protected function ensureSyllabusRegistryExists($batchSubject)
    {
        if (!$batchSubject || empty($batchSubject->subject_code)) {
            return;
        }

        $exists = \DB::table('syllabus_registry')
            ->where('subject_code', $batchSubject->subject_code)
            ->exists();

        if (!$exists) {
            \DB::table('syllabus_registry')->insert([
                'subject_code' => $batchSubject->subject_code,
                'revision_year' => 2026,
                'subject_name' => $batchSubject->subject_name ?? $batchSubject->subject_code,
                'co_count' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Resolve exact full department name for a batchSubject and/or classroom.
     */
    protected function getDepartmentName($batchSubject, $classroom = null)
    {
        $code = null;
        
        // 1. Check classroom object
        if ($classroom) {
            $candidate = trim($classroom->branch ?? $classroom->department ?? '');
            if (!empty($candidate) && strtoupper($candidate) !== 'ENGINEERING' && strtoupper($candidate) !== 'GENERAL') {
                $code = $candidate;
            }
        }

        // 2. Check batchSubject classroom_id (e.g., 'AU_2026_2029' -> 'AU')
        if (empty($code) && $batchSubject && !empty($batchSubject->classroom_id)) {
            $parts = explode('_', $batchSubject->classroom_id);
            if (!empty($parts[0]) && strtoupper($parts[0]) !== 'ENGINEERING') {
                $code = $parts[0];
            }
        }

        // 3. Check batchSubject subject_code (e.g., 'AU-1002' -> 'AU')
        if (empty($code) && $batchSubject && !empty($batchSubject->subject_code)) {
            $parts = explode('-', $batchSubject->subject_code);
            if (!empty($parts[0]) && preg_match('/[A-Za-z]+/', $parts[0], $matches)) {
                $code = $matches[0];
            }
        }

        // 4. Fallback to session userBranch if not generic
        if (empty($code)) {
            $sessBranch = Session::get('userBranch', '');
            if (!empty($sessBranch) && strtoupper($sessBranch) !== 'ENGINEERING') {
                $code = $sessBranch;
            }
        }

        $branchMap = [
            'AU' => 'Automobile Engineering',
            'ME' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering',
            'CT' => 'Computer Engineering',
            'CS' => 'Computer Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'EL' => 'Electronics Engineering',
            'EC' => 'Electronics Engineering',
            'CH' => 'Chemical Engineering',
        ];

        $upperCode = strtoupper(trim($code ?? ''));
        if (isset($branchMap[$upperCode])) {
            return $branchMap[$upperCode];
        }

        $fullName = null;
        if (!empty($code) && function_exists('getFullBranchName')) {
            $fullName = getFullBranchName($code);
        }

        // If it still evaluates to generic 'Engineering' or is empty, deduce from classroom_id or subject_code
        if (empty($fullName) || strtoupper($fullName) === 'ENGINEERING' || strtoupper($fullName) === 'GENERAL') {
            if ($batchSubject && !empty($batchSubject->classroom_id)) {
                $p = explode('_', $batchSubject->classroom_id)[0] ?? '';
                if (isset($branchMap[strtoupper($p)])) {
                    return $branchMap[strtoupper($p)];
                }
            }
            if ($batchSubject && !empty($batchSubject->subject_code)) {
                $p = explode('-', $batchSubject->subject_code)[0] ?? '';
                if (isset($branchMap[strtoupper($p)])) {
                    return $branchMap[strtoupper($p)];
                }
            }
            $fullName = 'Automobile Engineering';
        }

        // Strip leading "Department of " if already present in value
        return preg_replace('/^Department\s+of\s+/i', '', trim($fullName));
    }
}

