<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\Student;
use App\Models\LessonPlan;
use App\Models\R26PracticumCourseFile;
use App\Models\R26PracticumExperimentEvaluation;
use App\Models\R26PracticumSeriesTheory;
use App\Models\R26PracticumSeriesPractical;
use App\Models\R26PracticumEseMark;

class R26VirtualClassroomPracticumController extends Controller
{
    /**
     * Practicum Integration Breakpoint - 2026-07-24
     * Main Practicum Virtual Classroom Dashboard (Joint Theory + Lab)
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

        // Fetch classroom details
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Fetch or Create Practicum Course File Record
        $practicumCourseFile = R26PracticumCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program' => $classroom->department ?? 'Engineering',
                'course_title' => $batchSubject->subject_name,
                'course_code' => $batchSubject->subject_code,
                'semester' => $classroom->current_semester ?? 'I',
                'type_of_course' => 'Practicum',
                'teaching_scheme' => '3:0:3:0',
                'contact_hours' => 90,
                'credits' => 4.5,
                'cie_marks' => 40,
                'ese_marks' => 100, // 100 for standard practicum (60 theory + 40 practical)
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Demonstrate basic concepts, electrical quantities, signal types, and electronic measuring instruments.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO2', 'description' => 'Construct and analyze basic electronic circuits using passive electronic components.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO3', 'description' => 'Apply semiconductor theory to demonstrate the operation of semiconductor devices.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Demonstrate Printed Circuit Boards and apply soldering techniques.', 'cognitive_level' => 'Apply']
                ],
                'parsed_modules' => [
                    ['module_id' => 'I', 'title' => 'Introduction to Electronics, Signals and Measurements', 'content' => 'Introduction, applications, electrical quantities, signals, lab familiarization', 'hours' => 11],
                    ['module_id' => 'II', 'title' => 'Passive Electronic Components', 'content' => 'Resistors, capacitors, inductors, transformers, series/parallel testing', 'hours' => 12],
                    ['module_id' => 'III', 'title' => 'Active Electronic Components', 'content' => 'Semiconductor theory, PN junction diode, Zener diode, LED, photodiode', 'hours' => 12],
                    ['module_id' => 'IV', 'title' => 'PCB and Soldering', 'content' => 'PCB types, layout design, fabrication steps, soldering practice', 'hours' => 10]
                ],
                'parsed_experiments' => [
                    ['experiment_no' => 'EXP-01', 'title' => 'Familiarization of electronics labs and measuring instruments', 'co_id' => 'CO1', 'hours' => 3],
                    ['experiment_no' => 'EXP-02', 'title' => 'Identification and testing of passive components', 'co_id' => 'CO2', 'hours' => 3],
                    ['experiment_no' => 'EXP-03', 'title' => 'Series and Parallel Resistor circuit construction and testing', 'co_id' => 'CO2', 'hours' => 3],
                    ['experiment_no' => 'EXP-04', 'title' => 'Series and Parallel Capacitor circuit construction and testing', 'co_id' => 'CO2', 'hours' => 3],
                    ['experiment_no' => 'EXP-05', 'title' => 'Identification of PN Junction Diode and plotting VI characteristics', 'co_id' => 'CO3', 'hours' => 3],
                    ['experiment_no' => 'EXP-06', 'title' => 'Plotting VI characteristics of Zener Diode', 'co_id' => 'CO3', 'hours' => 3],
                    ['experiment_no' => 'EXP-07', 'title' => 'PCB layout preparation and soldering practice', 'co_id' => 'CO4', 'hours' => 3],
                    ['experiment_no' => 'EXP-08', 'title' => 'Identification and testing of BJT Transistors', 'co_id' => 'CO3', 'hours' => 3],
                    ['experiment_no' => 'EXP-09', 'title' => 'Half wave rectifier circuit assembly and waveform measurement', 'co_id' => 'CO3', 'hours' => 3],
                    ['experiment_no' => 'EXP-10', 'title' => 'Full wave center-tapped rectifier circuit assembly and testing', 'co_id' => 'CO3', 'hours' => 3],
                    ['experiment_no' => 'EXP-11', 'title' => 'Capacitor filter circuit testing with DC output ripple analysis', 'co_id' => 'CO3', 'hours' => 3],
                    ['experiment_no' => 'EXP-12', 'title' => 'Logic gate IC pinout familiarization and truth table verification', 'co_id' => 'CO4', 'hours' => 3],
                    ['experiment_no' => 'EXP-13', 'title' => 'Through-hole component soldering on general purpose PCB', 'co_id' => 'CO4', 'hours' => 3],
                    ['experiment_no' => 'EXP-14', 'title' => 'Desoldering techniques and component replacement practice', 'co_id' => 'CO4', 'hours' => 3],
                    ['experiment_no' => 'EXP-15', 'title' => 'Mini hardware project circuit testing and troubleshooting', 'co_id' => 'CO4', 'hours' => 3]
                ],
                'parsed_copo' => [
                    'credit' => 4.5,
                    'l_t_p_r' => '3:0:3:0',
                    'cie_marks' => 40,
                    'ese_marks' => 100,
                    'total_hours' => 90,
                    'mappings' => [
                        'CO1' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'1', 'PO4'=>'3', 'PO5'=>'2', 'PO6'=>'1', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'1'],
                        'CO2' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'2', 'PO4'=>'3', 'PO5'=>'2', 'PO6'=>'1', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'1'],
                        'CO3' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'2', 'PO4'=>'3', 'PO5'=>'2', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'1'],
                        'CO4' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'1', 'PO4'=>'2', 'PO5'=>'2', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'1']
                    ]
                ],
                'parsed_textbooks' => [
                    'Electronic Inventions and Discoveries - G.W.A Drummer',
                    'Grob\'s Basic Electronics - Mitchel E. Schultz',
                    'Textbook of Electrical Technology: Part 1 - B.L. Thereja'
                ],
                'self_learning_configs' => [
                    'CO1' => ['assignment' => 5.0, 'mcq' => 5.0],
                    'CO2' => ['assignment' => 5.0, 'mcq' => 5.0],
                    'CO3' => ['assignment' => 5.0, 'mcq' => 5.0],
                    'CO4' => ['assignment' => 5.0, 'mcq' => 5.0]
                ]
            ]
        );

        // Fetch / Auto-Generate 90-Hour Practicum Combined Lesson Plan
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        if ($lessonPlans->count() < 90) {
            $this->generate90HourLessonPlan($batchSubject, $practicumCourseFile);
            $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
                ->orderBy('day_no', 'asc')
                ->get();
        } else {
            // Self-healing: if no actual class logs have been recorded yet, reset all pre-completed default entries to Pending
            $classLogsCount = DB::table('class_logs_attendance')->where('batch_subject_id', $subjectId)->count();
            if ($classLogsCount === 0) {
                $hasCompleted = LessonPlan::where('batch_subject_id', $subjectId)->where('status', 'Completed')->exists();
                if ($hasCompleted) {
                    LessonPlan::where('batch_subject_id', $subjectId)->update([
                        'status' => 'Pending',
                        'actual_date' => null,
                        'actual_hours' => null
                    ]);
                    $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
                        ->orderBy('day_no', 'asc')
                        ->get();
                }
            }

            // Auto-classify P / SP rows if all rows were previously imported as 'L'
            $hasLabModes = $lessonPlans->whereIn('mode', ['P', 'SP'])->count() > 0;
            if (!$hasLabModes) {
                foreach ($lessonPlans as $p) {
                    $topic = $p->topic_content;
                    $newMode = 'L';
                    $newPedagogy = $p->pedagogy;

                    if (str_contains($topic, 'Practical Series Exam') || str_contains($topic, 'Lab Test')) {
                        $newMode = 'SP';
                        $newPedagogy = 'Practical Series Exam (SP)';
                    } elseif (str_contains($topic, 'Theory Series Exam') || str_contains($topic, 'Written Exam') || str_contains($topic, 'Written 1 Hour Test')) {
                        $newMode = 'ST';
                        $newPedagogy = 'Theory Series Exam (ST)';
                    } elseif (str_contains($topic, 'EXP-') || str_contains($topic, 'Practical Session Topic') || str_contains($topic, 'Lab Series') || str_contains($topic, 'Practical Lab')) {
                        $newMode = 'P';
                        $newPedagogy = 'Practical Lab (P)';
                    }

                    if ($newMode !== $p->mode || empty($p->pedagogy)) {
                        $p->mode = $newMode;
                        $p->pedagogy = $newPedagogy;
                        $p->save();
                    }
                }
                $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
                    ->orderBy('day_no', 'asc')
                    ->get();
            }
        }

        // Fetch Attendance Records
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Fetch Continuous Practical Evaluations
        $experimentEvals = R26PracticumExperimentEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Fetch Theory Series Exam Marks
        $seriesTheoryEvals = R26PracticumSeriesTheory::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Fetch Practical Series Exam Marks
        $seriesPracticalEvals = R26PracticumSeriesPractical::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Fetch ESE Marks
        $eseMarks = R26PracticumEseMark::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Self-Learning Academic Marks & Submissions
        $slAcademicMarks = DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->where('category', 'like', 'Self Study:%')
            ->get()
            ->groupBy('reg_no');

        $slSubmissions = DB::table('student_task_submissions')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Build Student Splitup Matrix
        $slStudentSplitup = [];
        foreach ($students as $st) {
            $rNo = $st->reg_no;
            $stMarks = $slAcademicMarks->get($rNo, collect());
            
            $split = [
                'CO1' => ['assignment' => 0, 'mcq' => 0],
                'CO2' => ['assignment' => 0, 'mcq' => 0],
                'CO3' => ['assignment' => 0, 'mcq' => 0],
                'CO4' => ['assignment' => 0, 'mcq' => 0],
            ];

            foreach ($stMarks as $m) {
                $co = $m->co_tag ?: 'CO1';
                $rawCat = str_replace('Self Study: ', '', $m->category);
                $actKey = strtolower(str_replace(' ', '_', $rawCat));
                $split[$co][$actKey] = floatval($m->marks_obtained);
            }

            $slStudentSplitup[$rNo] = $split;
        }

        // Map Students and Compute Consolidated CIA & ESE Scores
        $studentResults = $students->map(function ($student) use (
            $attendanceData,
            $experimentEvals,
            $seriesTheoryEvals,
            $seriesPracticalEvals,
            $eseMarks,
            $slAcademicMarks,
            $slSubmissions,
            $practicumCourseFile,
            $batchSubject
        ) {
            $regNo = $student->reg_no;

            // 1. Attendance Marks (Table 2.1 - Max 5)
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : 100.00;

            $attMarks = 0;
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

            // 2. Self-Learning Marks (Max 5 CIA Marks)
            $stSlMarks = $slAcademicMarks->get($regNo, collect());
            if ($stSlMarks->count() > 0) {
                $slScoreRaw = $stSlMarks->avg('marks_obtained') ?: 0.00;
                $slMarks = round((($slScoreRaw / 15.0) * 5.0) * 2) / 2;
            } else {
                $stSub = $slSubmissions->get($regNo, collect());
                $slScoreRaw = $stSub->avg('score') ?: 0.00;
                $slMarks = round((($slScoreRaw / 10.0) * 5.0) * 2) / 2;
            }
            if ($slMarks > 5.0) $slMarks = 5.0;

            // 3. Continuous Practical Evaluation (Max 10 CIA Marks)
            $stExps = $experimentEvals->get($regNo, collect());
            $avgExpScore50 = $stExps->avg('total_score_50') ?: 0.00;
            $continuousEvalMarks = round((($avgExpScore50 / 50.0) * 10.0) * 2) / 2;

            // 4. Theory Series Exam Marks (4 CO 1-Hour Tests: CO1, CO2, CO3, CO4 - Max 10 CIA Marks)
            $stStEvals = $seriesTheoryEvals->get($regNo, collect());
            $st1 = $stStEvals->whereIn('series_no', ['Series 1', 'CO1'])->first();
            $st2 = $stStEvals->whereIn('series_no', ['Series 2', 'CO2'])->first();
            $st3 = $stStEvals->whereIn('series_no', ['Series 3', 'CO3'])->first();
            $st4 = $stStEvals->whereIn('series_no', ['Series 4', 'CO4'])->first();
            $st1Score = $st1 ? $st1->total_score_50 : 0.00;
            $st2Score = $st2 ? $st2->total_score_50 : 0.00;
            $st3Score = $st3 ? $st3->total_score_50 : 0.00;
            $st4Score = $st4 ? $st4->total_score_50 : 0.00;
            $avgTheorySeries50 = ($st1Score + $st2Score + $st3Score + $st4Score) / 4.0;
            $seriesTheoryMarks = round((($avgTheorySeries50 / 50.0) * 10.0) * 2) / 2;

            // 5. Practical Series Exam Marks (2 Tests: Test 1 CO1+CO2 & Test 2 CO3+CO4 - Max 10 CIA Marks)
            $stSpEvals = $seriesPracticalEvals->get($regNo, collect());
            $sp1 = $stSpEvals->whereIn('series_no', ['Series 1', 'Test 1 (CO1+CO2)'])->first();
            $sp2 = $stSpEvals->whereIn('series_no', ['Series 2', 'Test 2 (CO3+CO4)'])->first();
            $sp1Score = $sp1 ? $sp1->total_score_40 : 0.00;
            $sp2Score = $sp2 ? $sp2->total_score_40 : 0.00;
            $avgPracticalSeries40 = ($sp1Score + $sp2Score) / 2.0;
            $seriesPracticalMarks = round((($avgPracticalSeries40 / 40.0) * 10.0) * 2) / 2;

            // Consolidated Total CIA Marks (Max 40)
            $totalCiaMarks = round(($attMarks + $slMarks + $continuousEvalMarks + $seriesTheoryMarks + $seriesPracticalMarks) * 2) / 2;

            // ESE Marks & Grades (Fetch from stored ESE record, else fetch from student upload tables)
            $stEse = $eseMarks->get($regNo);
            
            $eseTheoryGrade = null;
            if ($stEse && $stEse->ese_theory_grade) {
                $eseTheoryGrade = $stEse->ese_theory_grade;
            } else {
                $eseTheoryGrade = DB::table('student_board_grades')
                    ->where('reg_no', $regNo)
                    ->where('subject_code', $batchSubject->subject_code)
                    ->value('grade');
                if (!$eseTheoryGrade) {
                    $eseTheoryGrade = DB::table('student_semester_marks')
                        ->where('reg_no', $regNo)
                        ->where('subject_code', $batchSubject->subject_code)
                        ->value('grade');
                }
            }

            // Map grade to marks if grade exists, otherwise use numerical marks from ESE log
            $eseTheory = 0.00;
            if ($eseTheoryGrade) {
                $eseTheory = $this->convertGradeToMarks($eseTheoryGrade);
            } elseif ($stEse) {
                $eseTheory = floatval($stEse->ese_theory_marks);
            }

            $esePractical = $stEse ? floatval($stEse->ese_practical_marks) : 0.00;
            $totalEse = $eseTheory + $esePractical;

            $maxEse = $practicumCourseFile->ese_marks; // 100 or 60
            $totalCourseMarks = $totalCiaMarks + $totalEse;
            $maxCourseMarks = 40 + $maxEse; // 140 or 100

            // Pass Criteria Check:
            // 1. Min 40% in ESE Theory = 24 / 60 (or S, A, B, C, D, P grade)
            // 2. Min 40% in Total Combined = 56 / 140 (or 40 / 100)
            $passTheoryEse = ($eseTheory >= 24.0 || (in_array(strtoupper(trim($eseTheoryGrade ?? '')), ['S','A','B','C','D','P'])));
            $passCombined = ($totalCourseMarks >= ($maxCourseMarks * 0.40));
            $isPassed = ($passTheoryEse && $passCombined);

            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'att_percentage' => $attPercentage,
                'att_marks' => $attMarks,
                'sl_marks' => $slMarks,
                'continuous_eval_marks' => $continuousEvalMarks,
                'series_theory_marks' => $seriesTheoryMarks,
                'series_practical_marks' => $seriesPracticalMarks,
                'total_cia_marks' => $totalCiaMarks,
                'ese_theory' => $eseTheory,
                'ese_theory_grade' => $eseTheoryGrade ?: '-',
                'ese_practical' => $esePractical,
                'total_ese' => $totalEse,
                'total_course_marks' => $totalCourseMarks,
                'max_course_marks' => $maxCourseMarks,
                'is_passed' => $isPassed
            ];
        });

        // Fetch assigned staff and HOD
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation', 'mobile_no')
            ->first();

        // Calculate Total Theory Hours and Total Practical Hours
        $parsedMods = $practicumCourseFile->parsed_modules ?: [];
        $parsedExps = $practicumCourseFile->parsed_experiments ?: [];

        $theoryHours = 0;
        foreach ($parsedMods as $m) {
            $theoryHours += floatval($m['hours'] ?? 10);
        }

        $practicalHours = 0;
        foreach ($parsedExps as $e) {
            $practicalHours += floatval($e['hours'] ?? 3);
        }

        if (empty($practicumCourseFile->syllabus_pdf_path)) {
            $theoryHours = 45;
            $practicalHours = 45;
        } else {
            if ($theoryHours == 0) $theoryHours = 45;
            if ($practicalHours == 0) $practicalHours = 45;
        }

        // CO-PO Matrix & Attainment Calculation
        $copoPayload = $practicumCourseFile->parsed_copo ?: [];
        $mappings = $copoPayload['mappings'] ?? [];

        if (empty($mappings)) {
            $mappings = [
                'CO1' => ['PO1'=>'3','PO2'=>'2','PO3'=>'1','PO4'=>'-','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO2' => ['PO1'=>'3','PO2'=>'3','PO3'=>'2','PO4'=>'1','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO3' => ['PO1'=>'3','PO2'=>'2','PO3'=>'3','PO4'=>'2','PO5'=>'1','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO4' => ['PO1'=>'2','PO2'=>'3','PO3'=>'2','PO4'=>'3','PO5'=>'2','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-']
            ];
        }

        $totalStudents = max(1, $studentResults->count());
        $directStats = [];
        $indirectStats = [];
        $combinedStats = [];

        // Check Course Exit Survey data for indirect attainment
        $exitSurvey = \DB::table('course_exit_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $exitSurveyResponses = collect();
        if ($exitSurvey) {
            $exitSurveyResponses = \DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $exitSurvey->id)
                ->get();
        }

        $midSemSurvey = \DB::table('mid_semester_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $midSemResponses = collect();
        if ($midSemSurvey) {
            $midSemResponses = \DB::table('student_survey_responses')
                ->where('survey_id', $midSemSurvey->id)
                ->get();
        }

        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $attainedCount = $studentResults->filter(function($s) {
                return $s['total_course_marks'] >= ($s['max_course_marks'] * 0.55);
            })->count();

            $percentage = ($attainedCount / $totalStudents) * 100;
            $directLevel = ($percentage >= 70) ? 3.0 : (($percentage >= 60) ? 2.0 : (($percentage >= 50) ? 1.0 : 0.0));
            
            $directStats[$coTag] = [
                'count' => $attainedCount,
                'percentage' => round($percentage, 1),
                'level' => $directLevel
            ];

            // Compute indirect attainment level out of 3.0 from exit survey if available
            $indirectLevel = 2.5;
            $indirectAvg = 2.50;
            $indirectPct = 83.3;

            if ($exitSurveyResponses->count() > 0) {
                if ($coTag === 'CO1') {
                    $indirectAvg = ($exitSurveyResponses->avg('co1_q1') + $exitSurveyResponses->avg('co1_q2')) / 2;
                } elseif ($coTag === 'CO2') {
                    $indirectAvg = ($exitSurveyResponses->avg('co2_q3') + $exitSurveyResponses->avg('co2_q4')) / 2;
                } elseif ($coTag === 'CO3') {
                    $indirectAvg = ($exitSurveyResponses->avg('co3_q5') + $exitSurveyResponses->avg('co3_q6')) / 2;
                } else {
                    $indirectAvg = ($exitSurveyResponses->avg('co4_q7') + $exitSurveyResponses->avg('co4_q8') + $exitSurveyResponses->avg('co4_q9')) / 3;
                }
                $indirectPct = ($indirectAvg / 3.0) * 100;
                $indirectLevel = ($indirectPct >= 70) ? 3.0 : (($indirectPct >= 60) ? 2.0 : (($indirectPct >= 50) ? 1.0 : 0.0));
            }

            $rating = ($indirectPct >= 70) ? 'High' : (($indirectPct >= 60) ? 'Medium' : (($indirectPct >= 50) ? 'Low' : 'Nil'));

            $indirectStats[$coTag] = [
                'avg_score' => round($indirectAvg, 2),
                'percentage' => round($indirectPct, 1),
                'level' => $indirectLevel,
                'rating' => $rating
            ];

            $combinedLevel = round((0.80 * $directLevel) + (0.20 * $indirectLevel), 2);
            $combinedStats[$coTag] = $combinedLevel;
        }

        $poAttainments = [];
        for ($p = 1; $p <= 11; $p++) {
            $poName = "PO" . $p;
            $sumWeight = 0;
            $sumAttainment = 0;

            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $correlation = isset($mappings[$coTag][$poName]) && is_numeric($mappings[$coTag][$poName]) ? (int)$mappings[$coTag][$poName] : 0;
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

        $slConfigs = $practicumCourseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => true, 'mcq' => true],
            'CO2' => ['assignment' => true, 'mcq' => true],
            'CO3' => ['assignment' => true, 'mcq' => true],
            'CO4' => ['assignment' => true, 'mcq' => true]
        ];

        $subjectType = $this->resolveSubjectType($practicumCourseFile, $batchSubject);
        $seriesQps = \App\Models\R26SeriesExamQp::where('batch_subject_id', $subjectId)->get()->keyBy('series_no');

        return view('r26_practicum.virtual_classroom_practicum', compact(
            'batchSubject',
            'classroom',
            'students',
            'practicumCourseFile',
            'lessonPlans',
            'studentResults',
            'experimentEvals',
            'seriesTheoryEvals',
            'seriesPracticalEvals',
            'eseMarks',
            'assignedStaff',
            'hod',
            'theoryHours',
            'practicalHours',
            'mappings',
            'directStats',
            'indirectStats',
            'combinedStats',
            'poAttainments',
            'slStudentSplitup',
            'slConfigs',
            'subjectType',
            'seriesQps',
            'midSemSurvey',
            'exitSurvey',
            'midSemResponses',
            'exitSurveyResponses'
        ));
    }

    /**
     * Upload and Parse Syllabus PDF for Practicum
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
            $path = $file->store('r26_practicum_syllabi', 'public');

            $pyPath = base_path('app/Services/r26_syllabus_parser.py');
            $fullPdfPath = storage_path('app/public/' . $path);

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = "python " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            } else {
                $pythonBin = file_exists('/usr/bin/python3') ? '/usr/bin/python3' : 'python3';
                $sitePkg = '/home/carmel/.local/lib/python3.14/site-packages';
                $command = "PYTHONIOENCODING=utf-8 PYTHONPATH={$sitePkg}:\$PYTHONPATH {$pythonBin} " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            }

            $jsonOutput = shell_exec($command);

            $parsedResult = json_decode($jsonOutput, true);
            if (!$parsedResult || !isset($parsedResult['status'])) {
                \Illuminate\Support\Facades\Log::error("Syllabus parser failed output: " . $jsonOutput);
                throw new \Exception('Failed to parse Practicum syllabus PDF. Raw output: ' . substr($jsonOutput ?? '', 0, 200));
            }

            if ($parsedResult['status'] === 'ERROR') {
                throw new \Exception($parsedResult['message'] ?? 'Failed to parse Practicum syllabus PDF.');
            }

            $data = $parsedResult['data'];

            // Update or Create Practicum Course File Record
            $practicumFile = R26PracticumCourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'program' => $data['program'] ?? 'Engineering',
                    'course_title' => $data['course_title'] ?? $batchSubject->subject_name,
                    'course_code' => $data['course_code'] ?? $batchSubject->subject_code,
                    'semester' => $data['semester'] ?? 'I',
                    'type_of_course' => (isset($data['type_of_course']) && stripos($data['type_of_course'], 'Practicum') !== false) ? 'Practicum' : ($data['type_of_course'] ?? 'Practicum'),
                    'teaching_scheme' => $data['teaching_scheme'] ?? '3:0:3:0',
                    'contact_hours' => $data['total_hours'] ?? 90,
                    'credits' => $data['credits'] ?? 4.5,
                    'cie_marks' => $data['cie_marks'] ?? 40,
                    'ese_marks' => $data['ese_marks'] ?? 100,
                    'parsed_cos' => $data['cos'] ?? [],
                    'parsed_modules' => $data['modules'] ?? [],
                    'parsed_experiments' => $data['experiments'] ?? [],
                    'parsed_copo' => [
                        'credit' => $data['credits'] ?? 4.5,
                        'l_t_p_r' => $data['teaching_scheme'] ?? '3:0:3:0',
                        'cie_marks' => $data['cie_marks'] ?? 40,
                        'ese_marks' => $data['ese_marks'] ?? 100,
                        'total_hours' => $data['total_hours'] ?? 90,
                        'mappings' => $data['copo_matrix'] ?? []
                    ],
                    'parsed_textbooks' => ['Textbook Reference 1', 'Textbook Reference 2']
                ]
            );

            // Regenerate Lesson Plans according to newly uploaded syllabus content
            $this->generate90HourLessonPlan($batchSubject, $practicumFile);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Practicum syllabus uploaded, parsed, and lesson plan dynamically generated successfully!',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Save CO-PO & PSO Mapping Matrix for Practicum Course File & Syllabus Registry
     */
    public function saveCoPoMatrix(Request $request, $subjectId)
    {
        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$practicumCourseFile) {
            $batchSubject = BatchSubject::findOrFail($subjectId);
            $practicumCourseFile = R26PracticumCourseFile::create([
                'batch_subject_id' => $subjectId,
                'course_title' => $batchSubject->subject_name,
                'course_code' => $batchSubject->subject_code,
            ]);
        }

        $mappings = $request->input('mappings') ?? $request->input('mapping') ?? $request->input('co_po_mapping') ?? [];
        $copoPayload = $practicumCourseFile->parsed_copo ?: [];
        $copoPayload['mappings'] = $mappings;
        $practicumCourseFile->parsed_copo = $copoPayload;
        $practicumCourseFile->save();

        // Sync globally to syllabus_registry table
        $batchSubject = BatchSubject::find($subjectId);
        if ($batchSubject) {
            \DB::table('syllabus_registry')->updateOrInsert(
                ['subject_code' => $batchSubject->subject_code],
                ['co_po_mapping' => json_encode($mappings), 'updated_at' => now()]
            );
        }

        return response()->json([
            'status' => 'SUCCESS',
            'success' => true,
            'message' => 'Course Articulation Matrix (PO1-PO11 & PSO1-PSO3) saved successfully!'
        ]);
    }

    /**
     * Save Continuous Practical Experiment Marks (Table 2.2 Rubrics - Max 50)
     */
    public function saveExperimentMarks(Request $request, $subjectId)
    {
        $request->validate([
            'experiment_no' => 'required|string',
            'experiment_title' => 'nullable|string',
            'marks_data' => 'required|array'
        ]);

        $expNo = $request->input('experiment_no');
        $expTitle = $request->input('experiment_title');
        $marksData = $request->input('marks_data');
        $assessorMobile = Session::get('mobileNo');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $p1 = floatval($row['prep_punctuality'] ?? 0);
            $p2 = floatval($row['setup_procedure'] ?? 0);
            $p3 = floatval($row['observation_recording'] ?? 0);
            $p4 = floatval($row['analysis_interpretation'] ?? 0);
            $p5 = floatval($row['viva_voce'] ?? 0);
            $p6 = floatval($row['workmanship_discipline'] ?? 0);
            $total50 = $p1 + $p2 + $p3 + $p4 + $p5 + $p6;

            R26PracticumExperimentEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'experiment_no' => $expNo,
                    'reg_no' => $regNo
                ],
                [
                    'experiment_title' => $expTitle,
                    'prep_punctuality' => $p1,
                    'setup_procedure' => $p2,
                    'observation_recording' => $p3,
                    'analysis_interpretation' => $p4,
                    'viva_voce' => $p5,
                    'workmanship_discipline' => $p6,
                    'total_score_50' => $total50,
                    'assessor_mobile_no' => $assessorMobile
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Experiment evaluations saved successfully!']);
    }

    /**
     * Save Theory Series Exam Marks (Max 50)
     */
    public function saveSeriesTheoryMarks(Request $request, $subjectId)
    {
        $request->validate([
            'series_no' => 'required|string',
            'marks_data' => 'required|array'
        ]);

        $seriesNo = $request->input('series_no');
        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $total50 = floatval($row['total_score_50'] ?? 0);
            $isAbsent = !empty($row['is_absent']);

            R26PracticumSeriesTheory::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'series_no' => $seriesNo,
                    'reg_no' => $regNo
                ],
                [
                    'part_a_score' => 0.00,
                    'part_b_score' => 0.00,
                    'part_c_score' => 0.00,
                    'total_score_50' => $isAbsent ? 0.00 : $total50,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Theory Series Exam marks saved successfully!']);
    }

    /**
     * Save Practical Series Exam Marks (Table 3.1 Rubrics - Max 40)
     */
    public function saveSeriesPracticalMarks(Request $request, $subjectId)
    {
        $request->validate([
            'series_no' => 'required|string',
            'marks_data' => 'required|array'
        ]);

        $seriesNo = $request->input('series_no');
        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $w = floatval($row['writeup_procedure'] ?? 0);
            $s = floatval($row['setup_execution'] ?? 0);
            $o = floatval($row['observation_result'] ?? 0);
            $v = floatval($row['viva_voce'] ?? 0);
            $r = floatval($row['record_completion'] ?? 0);
            $total40 = $w + $s + $o + $v + $r;
            $isAbsent = !empty($row['is_absent']);

            R26PracticumSeriesPractical::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'series_no' => $seriesNo,
                    'reg_no' => $regNo
                ],
                [
                    'writeup_procedure' => $w,
                    'setup_execution' => $s,
                    'observation_result' => $o,
                    'viva_voce' => $v,
                    'record_completion' => $r,
                    'total_score_40' => $isAbsent ? 0.00 : $total40,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Practical Series Exam marks saved successfully!']);
    }

    public function saveEseMarks(Request $request, $subjectId)
    {
        $request->validate([
            'marks_data' => 'required|array'
        ]);

        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $record = R26PracticumEseMark::firstOrNew([
                'batch_subject_id' => $subjectId,
                'reg_no' => $regNo
            ]);

            if (array_key_exists('ese_theory_grade', $row)) {
                $grade = $row['ese_theory_grade'];
                $tAbs = !empty($row['theory_absent']);
                if ($tAbs) { $grade = 'FE'; }
                $record->ese_theory_grade = $grade;
                $record->ese_theory_marks = $tAbs ? 0.00 : $this->convertGradeToMarks($grade);
                $record->theory_absent = $tAbs;
            }

            if (array_key_exists('ese_practical_marks', $row)) {
                $pAbs = !empty($row['practical_absent']);
                $ep = floatval($row['ese_practical_marks']);
                $record->ese_practical_marks = $pAbs ? 0.00 : $ep;
                $record->practical_absent = $pAbs;
            }

            $record->total_ese_marks = ($record->theory_absent ? 0.00 : floatval($record->ese_theory_marks)) + ($record->practical_absent ? 0.00 : floatval($record->ese_practical_marks));
            $record->save();
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'ESE marks and grades saved successfully!']);
    }

    /**
     * Print NBA Course File PDF for Practicum
     */
    public function printCourseFilePdf($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
        
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->get();

        $attendanceData = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->get()->groupBy('reg_no');
        $experimentEvals = R26PracticumExperimentEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $seriesTheoryEvals = R26PracticumSeriesTheory::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $seriesPracticalEvals = R26PracticumSeriesPractical::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $eseMarks = R26PracticumEseMark::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $slSubmissions = DB::table('student_task_submissions')->where('subject_code', $batchSubject->subject_code)->get()->groupBy('reg_no');

        $studentResults = $students->map(function ($student) use ($attendanceData, $experimentEvals, $seriesTheoryEvals, $seriesPracticalEvals, $eseMarks, $slSubmissions, $practicumCourseFile) {
            $regNo = $student->reg_no;
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : 100.00;

            $attMarks = ($attPercentage >= 90) ? 5 : (($attPercentage >= 80) ? 4 : (($attPercentage >= 75) ? 3 : (($attPercentage >= 70) ? 2 : (($attPercentage >= 65) ? 1 : 0))));

            $stSub = $slSubmissions->get($regNo, collect());
            $slScoreRaw = $stSub->avg('score') ?: 0.00;
            $slMarks = min(5.0, round((($slScoreRaw / 10.0) * 5.0) * 2) / 2);

            $stExps = $experimentEvals->get($regNo, collect());
            $avgExpScore50 = $stExps->avg('total_score_50') ?: 0.00;
            $continuousEvalMarks = round((($avgExpScore50 / 50.0) * 10.0) * 2) / 2;

            $stStEvals = $seriesTheoryEvals->get($regNo, collect());
            $st1 = $stStEvals->where('series_no', 'Series 1')->first();
            $st2 = $stStEvals->where('series_no', 'Series 2')->first();
            $avgTheorySeries50 = (($st1 ? $st1->total_score_50 : 0) + ($st2 ? $st2->total_score_50 : 0)) / 2.0;
            $seriesTheoryMarks = round((($avgTheorySeries50 / 50.0) * 10.0) * 2) / 2;

            $stSpEvals = $seriesPracticalEvals->get($regNo, collect());
            $sp1 = $stSpEvals->where('series_no', 'Series 1')->first();
            $sp2 = $stSpEvals->where('series_no', 'Series 2')->first();
            $avgPracticalSeries40 = (($sp1 ? $sp1->total_score_40 : 0) + ($sp2 ? $sp2->total_score_40 : 0)) / 2.0;
            $seriesPracticalMarks = round((($avgPracticalSeries40 / 40.0) * 10.0) * 2) / 2;

            $totalCiaMarks = round(($attMarks + $slMarks + $continuousEvalMarks + $seriesTheoryMarks + $seriesPracticalMarks) * 2) / 2;

            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'att_marks' => $attMarks,
                'sl_marks' => $slMarks,
                'continuous_eval_marks' => $continuousEvalMarks,
                'series_theory_marks' => $seriesTheoryMarks,
                'series_practical_marks' => $seriesPracticalMarks,
                'total_cia_marks' => $totalCiaMarks
            ];
        });

        return view('r26_practicum.course_file_pdf', compact('batchSubject', 'practicumCourseFile', 'studentResults'));
    }

    /**
     * View Course File Preparation Console for Practicum
     */
    public function viewCourseFile($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $practicumCourseFile = R26PracticumCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId]
        );

        $savedChecklist = $practicumCourseFile->doc_checklist ?: [];

        // Real-time evaluation of generated system components
        $hasSyllabus = !empty($practicumCourseFile->syllabus_pdf_path);
        $hasLessonPlan = LessonPlan::where('batch_subject_id', $subjectId)->exists();
        $studentCount = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->count();
        $hasAttendance = \DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->exists();
        $hasExpEval = \DB::table('r26_practicum_experiment_evaluations')->where('batch_subject_id', $subjectId)->exists();
        $hasSeriesTheory = \DB::table('r26_practicum_series_theory')->where('batch_subject_id', $subjectId)->exists();
        $hasSeriesPractical = \DB::table('r26_practicum_series_practical')->where('batch_subject_id', $subjectId)->exists();
        $hasEse = \DB::table('r26_practicum_ese_marks')->where('batch_subject_id', $subjectId)->exists();
        $hasCoPo = !empty($practicumCourseFile->parsed_copo);
        $hasSelfLearning = !empty($practicumCourseFile->self_learning_configs);

        $docCatalog = [
            1 => ['name' => 'Class Time table (current semester Program timetable)', 'auto' => true, 'auto_remark' => 'Active timetable mapped'],
            2 => ['name' => 'Faculty Workload', 'auto' => true, 'auto_remark' => 'Faculty allocation assigned'],
            3 => ['name' => 'Student List with register numbers', 'auto' => ($studentCount > 0), 'auto_remark' => $studentCount > 0 ? "{$studentCount} students enrolled" : 'Pending student registration'],
            4 => ['name' => 'Course Syllabus with Recommended Books (SITTTR)', 'auto' => $hasSyllabus, 'auto_remark' => $hasSyllabus ? 'SITTTR Syllabus PDF parsed' : 'Pending syllabus upload'],
            5 => ['name' => 'Course information sheet', 'auto' => true, 'auto_remark' => 'Generated from course metadata'],
            6 => ['name' => 'Course outcomes & CO-PO Mappings', 'auto' => $hasCoPo, 'auto_remark' => $hasCoPo ? 'CO-PO matrix mapped' : 'Pending CO-PO mapping'],
            7 => ['name' => 'Academic calender & Semester Layout', 'auto' => true, 'auto_remark' => 'Institutional calendar mapped'],
            8 => ['name' => 'Course Plan / Combined Lesson Planner', 'auto' => $hasLessonPlan, 'auto_remark' => $hasLessonPlan ? 'Lesson plan generated' : 'Pending lesson plan generation'],
            9 => ['name' => 'Course log and Attendance', 'auto' => $hasAttendance, 'auto_remark' => $hasAttendance ? 'Attendance logs recorded' : 'Pending attendance logs'],
            10 => ['name' => 'Theory Series Exam Question Papers & Scheme', 'auto' => $hasSeriesTheory, 'auto_remark' => $hasSeriesTheory ? 'Series 1 & 2 theory configured' : 'Pending series exam setup'],
            11 => ['name' => 'Internal Examination Result Analysis NBA', 'auto' => $hasSeriesTheory, 'auto_remark' => $hasSeriesTheory ? 'Internal result analysis generated' : 'Pending internal exam scores'],
            12 => ['name' => 'Weaker student coaching schedule and proof', 'auto' => false, 'auto_remark' => 'Pending remediation log'],
            13 => ['name' => 'Teaching and Learning Methods Proof - handouts, capsule notes etc.', 'auto' => true, 'auto_remark' => 'Dynamic pedagogy tracking active'],
            14 => ['name' => 'Self-Learning / Assignment questions with rubrics', 'auto' => $hasSelfLearning, 'auto_remark' => $hasSelfLearning ? 'Self-learning scheme configured' : 'Pending self-learning setup'],
            15 => ['name' => 'Internal Marks - SBTE (CIA 40M Summary)', 'auto' => ($hasExpEval || $hasSeriesTheory), 'auto_remark' => ($hasExpEval || $hasSeriesTheory) ? 'CIA 40M summary generated' : 'Pending CIA evaluation'],
            16 => ['name' => 'Practical Series Exam Task Sheet & Rubric Evaluation Scheme', 'auto' => true, 'auto_remark' => 'Table 2.2 & 3.1 Rubrics mapped'],
            17 => ['name' => 'Continuous Practical Evaluation Log (Table 2.2)', 'auto' => $hasExpEval, 'auto_remark' => $hasExpEval ? 'Experiment evaluations logged' : 'Pending experiment evaluation'],
            18 => ['name' => 'Institutional Practical ESE Examination Results', 'auto' => $hasEse, 'auto_remark' => $hasEse ? 'ESE marks recorded' : 'Pending ESE evaluation'],
            19 => ['name' => 'Attainment of Course Outcome (CO) Co-Po-Pso Map', 'auto' => $hasCoPo, 'auto_remark' => $hasCoPo ? 'CO attainment mapped' : 'Pending CO attainment'],
            20 => ['name' => 'Attainment of PO/PSO report', 'auto' => $hasCoPo, 'auto_remark' => $hasCoPo ? 'PO/PSO attainment calculated' : 'Pending PO attainment'],
            21 => ['name' => 'Mid semester survey & report', 'auto' => false, 'auto_remark' => 'Pending mid-sem survey'],
            22 => ['name' => 'End semester / Course exit survey & report', 'auto' => false, 'auto_remark' => 'Pending course exit survey'],
            23 => ['name' => 'Internal Examination sample answer scripts', 'auto' => false, 'auto_remark' => 'Pending physical sample upload'],
            24 => ['name' => 'Practical record sample pages', 'auto' => false, 'auto_remark' => 'Pending record sample upload'],
            25 => ['name' => 'Others', 'auto' => false, 'auto_remark' => 'Optional audit attachments']
        ];

        $documents = collect();
        foreach ($docCatalog as $num => $info) {
            $saved = $savedChecklist[$num] ?? null;
            $isChecked = $saved ? (bool)($saved['is_checked'] ?? false) : $info['auto'];
            $remarks = $saved ? ($saved['remarks'] ?? '') : $info['auto_remark'];

            $documents->push((object)[
                'id' => $num,
                'document_number' => $num,
                'document_name' => $info['name'],
                'is_checked' => $isChecked,
                'remarks' => $remarks
            ]);
        }

        return view('r26_practicum.course_file_preparation', compact('batchSubject', 'practicumCourseFile', 'documents'));
    }

    /**
     * Save Practicum Course File Document Checklist Audit Status
     */
    public function saveCourseFileDoc(Request $request, $subjectId)
    {
        $practicumFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$practicumFile) {
            return response()->json(['status' => 'ERROR', 'message' => 'Practicum course file not found.'], 404);
        }

        $docId = (int)$request->input('doc_id');
        $isChecked = filter_var($request->input('is_checked'), FILTER_VALIDATE_BOOLEAN);
        $remarks = $request->input('remarks', '');

        $checklist = $practicumFile->doc_checklist ?: [];
        $checklist[$docId] = [
            'is_checked' => $isChecked,
            'remarks' => $remarks,
            'updated_at' => now()->toDateTimeString()
        ];

        $practicumFile->doc_checklist = $checklist;
        $practicumFile->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Document audit status updated successfully.'
        ]);
    }

    /**
     * Print Current Semester Program Class Timetable for Batch
     */
    public function printClassroomTimetable($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroomId = $batchSubject->classroom_id;
        $semester = $batchSubject->semester;

        // Fetch timetable data from storage
        $cleanId = preg_replace('/[^a-zA-Z0-9_-]/', '', $classroomId);
        $path = storage_path("app/timetables/{$cleanId}.json");
        $timetableData = file_exists($path) ? (json_decode(file_get_contents($path), true) ?: []) : [];

        // Fetch allocated subjects for this batch & semester with staff relations
        $allocatedSubjects = BatchSubject::where('classroom_id', $classroomId)
            ->where('semester', $semester)
            ->with(['staffAssignments.staff'])
            ->get();

        // Department mapping
        $deptNames = [
            "EL" => "Electronics Engineering",
            "CS" => "Computer Engineering",
            "ME" => "Mechanical Engineering",
            "EE" => "Electrical & Electronics Engineering",
            "CE" => "Civil Engineering",
            "CH" => "Chemical Engineering"
        ];
        $deptShort = explode('-', $classroomId)[0] ?? 'DEPT';
        $fullDept = $deptNames[strtoupper($deptShort)] ?? $deptShort;

        return view('r26_practicum.timetable_print', compact(
            'batchSubject',
            'classroomId',
            'semester',
            'timetableData',
            'allocatedSubjects',
            'fullDept'
        ));
    }

    /**
     * Auto-Generate 90-Hour Practicum Combined Lesson Plan
     */
    public function generate90HourLessonPlan($batchSubject, $practicumCourseFile)
    {
        $subjectId = $batchSubject->id;

        // Delete existing partial plans to generate clean 90-hour plan
        LessonPlan::where('batch_subject_id', $subjectId)->delete();

        $modules = $practicumCourseFile->parsed_modules ?: [];
        $experiments = $practicumCourseFile->parsed_experiments ?: [];

        // Build 45 Theory Topics
        $theoryList = [];
        foreach ($modules as $m) {
            $mId = $m['module_id'] ?? 'I';
            $coId = 'CO' . (ctype_digit(strval($mId)) ? $mId : ($mId === 'I' ? 1 : ($mId === 'II' ? 2 : ($mId === 'III' ? 3 : 4))));
            $title = $m['title'] ?? 'Module Topic';
            $content = $m['content'] ?? $title;
            $parts = array_filter(array_map('trim', explode(',', $content)));
            $mHrs = intval($m['hours'] ?? 11);

            for ($h = 1; $h <= $mHrs; $h++) {
                $sub = $parts[($h - 1) % count($parts)] ?? $title;
                $theoryList[] = [
                    'topic' => "Module {$mId}: " . $sub,
                    'co_id' => $coId,
                    'mode' => 'L'
                ];
            }
        }
        while (count($theoryList) < 45) {
            $idx = count($theoryList) + 1;
            $coId = 'CO' . min(4, max(1, intval(ceil($idx / 11.25))));
            $theoryList[] = [
                'topic' => "Theory Lecture Topic " . $idx,
                'co_id' => $coId,
                'mode' => 'L'
            ];
        }

        // Build 45 Practical Session Topics
        $practicalList = [];
        foreach ($experiments as $exp) {
            $eNo = $exp['experiment_no'] ?? 'EXP';
            $eTitle = $exp['title'] ?? 'Experiment Topic';
            $coId = $exp['co_id'] ?? 'CO1';
            $eHrs = intval($exp['hours'] ?? 3);

            for ($h = 1; $h <= $eHrs; $h++) {
                $practicalList[] = [
                    'topic' => "{$eNo}: {$eTitle} (Hour {$h}/{$eHrs})",
                    'co_id' => $coId,
                    'mode' => 'P'
                ];
            }
        }
        while (count($practicalList) < 45) {
            $idx = count($practicalList) + 1;
            $coId = 'CO' . min(4, max(1, intval(ceil($idx / 11.25))));
            $practicalList[] = [
                'topic' => "Practical Session Topic " . $idx,
                'co_id' => $coId,
                'mode' => 'P'
            ];
        }

        // Build 90 Hours Schedule
        $tIdx = 0;
        $pIdx = 0;
        $startDate = now()->startOfWeek();

        for ($day = 1; $day <= 90; $day++) {
            $proposedDate = $startDate->copy()->addDays(floor(($day - 1) * 1.2))->format('Y-m-d');
            
            // Special Exam Hours
            if ($day == 23) {
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'ST',
                    'pedagogy' => 'Theory Series Exam (ST)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => 'Theory Series Exam 1 (CO1 - Written 1 Hour Test)',
                    'co_id' => 'CO1',
                    'sub_batch' => 'All Students',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => '1 Hour Written Exam'
                ]);
            } elseif ($day == 45) {
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'ST',
                    'pedagogy' => 'Theory Series Exam (ST)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => 'Theory Series Exam 2 (CO2 - Written 1 Hour Test)',
                    'co_id' => 'CO2',
                    'sub_batch' => 'All Students',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => '1 Hour Written Exam'
                ]);
            } elseif ($day == 67) {
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'ST',
                    'pedagogy' => 'Theory Series Exam (ST)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => 'Theory Series Exam 3 (CO3 - Written 1 Hour Test)',
                    'co_id' => 'CO3',
                    'sub_batch' => 'All Students',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => '1 Hour Written Exam'
                ]);
            } elseif ($day == 89) {
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'ST',
                    'pedagogy' => 'Theory Series Exam (ST)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => 'Theory Series Exam 4 (CO4 - Written 1 Hour Test)',
                    'co_id' => 'CO4',
                    'sub_batch' => 'All Students',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => '1 Hour Written Exam'
                ]);
            } elseif ($day >= 42 && $day <= 44) {
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'SP',
                    'pedagogy' => 'Practical Series Exam (SP)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => 'Practical Series Exam 1 (CO1+CO2 - 3-Hour Combined Lab Test)',
                    'co_id' => 'CO1',
                    'sub_batch' => 'Batch A & B',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => '3 Hour Practical Exam'
                ]);
            } elseif ($day >= 86 && $day <= 88) {
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'SP',
                    'pedagogy' => 'Practical Series Exam (SP)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => 'Practical Series Exam 2 (CO3+CO4 - 3-Hour Combined Lab Test)',
                    'co_id' => 'CO3',
                    'sub_batch' => 'Batch A & B',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => '3 Hour Practical Exam'
                ]);
            } elseif (($day % 6 >= 1 && $day % 6 <= 3) && $tIdx < count($theoryList)) {
                $top = $theoryList[$tIdx++];
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'L',
                    'pedagogy' => 'Lecture (L)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => $top['topic'],
                    'co_id' => $top['co_id'],
                    'sub_batch' => 'All Students',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => 'Lecture Session'
                ]);
            } elseif ($pIdx < count($practicalList)) {
                $top = $practicalList[$pIdx++];
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'P',
                    'pedagogy' => 'Practical Lab (P)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => $top['topic'],
                    'co_id' => $top['co_id'],
                    'sub_batch' => 'Batch A & B',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => 'Practical Lab Session'
                ]);
            } else {
                $top = $theoryList[$tIdx % count($theoryList)];
                $tIdx++;
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $day,
                    'mode' => 'L',
                    'pedagogy' => 'Lecture (L)',
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => $top['topic'],
                    'co_id' => $top['co_id'],
                    'sub_batch' => 'All Students',
                    'allocated_hours' => 1,
                    'actual_hours' => null,
                    'status' => 'Pending',
                    'remarks' => 'Lecture Session'
                ]);
            }
        }
    }

    /**
     * Save/Edit Single Lesson Plan Row
     */
    public function saveLessonPlanRow(Request $request, $subjectId)
    {
        $request->validate([
            'plan_id' => 'required|integer',
            'topic_content' => 'required|string',
            'proposed_date' => 'nullable|date',
            'actual_date' => 'nullable|date',
            'co_id' => 'required|string',
            'sub_batch' => 'nullable|string',
            'mode' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $plan = LessonPlan::where('id', $request->input('plan_id'))
            ->where('batch_subject_id', $subjectId)
            ->firstOrFail();

        $plan->update([
            'topic_content' => $request->input('topic_content'),
            'proposed_date' => $request->input('proposed_date'),
            'actual_date' => $request->input('actual_date'),
            'co_id' => $request->input('co_id'),
            'sub_batch' => $request->input('sub_batch'),
            'mode' => $request->input('mode'),
            'remarks' => $request->input('remarks'),
            'status' => $request->input('status', 'Completed')
        ]);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Lesson plan topic updated successfully!']);
    }

    /**
     * Bulk Save All Lesson Plan Rows
     */
    public function saveAllLessonPlans(Request $request, $subjectId)
    {
        $request->validate([
            'plans' => 'required|array'
        ]);

        $plansData = $request->input('plans');

        foreach ($plansData as $item) {
            if (!isset($item['id'])) continue;
            
            $pedagogy = $item['pedagogy'] ?? ($item['mode'] ?? 'Lecture (L)');
            $mode = 'L';
            if (stripos($pedagogy, 'Practical') !== false || stripos($pedagogy, 'Lab') !== false) {
                $mode = 'P';
            } elseif (stripos($pedagogy, 'Series Exam') !== false || stripos($pedagogy, 'ST') !== false) {
                $mode = 'ST';
            } elseif (stripos($pedagogy, 'SP') !== false) {
                $mode = 'SP';
            }

            $topicText = trim($item['topic_content'] ?? '');

            if (str_starts_with((string)$item['id'], 'new_')) {
                // If new row added with no text entered, never save or calculate that row
                if ($topicText === '') {
                    continue;
                }

                $maxDay = LessonPlan::where('batch_subject_id', $subjectId)->max('day_no') ?? 0;
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no' => $maxDay + 1,
                    'topic_content' => $topicText,
                    'proposed_date' => !empty($item['proposed_date']) ? $item['proposed_date'] : null,
                    'actual_date' => !empty($item['actual_date']) ? $item['actual_date'] : null,
                    'co_id' => $item['co_id'] ?? 'CO1',
                    'sub_batch' => $item['sub_batch'] ?? 'ALL',
                    'pedagogy' => $pedagogy,
                    'mode' => $mode,
                    'remarks' => $item['remarks'] ?? '',
                    'status' => !empty($item['actual_date']) ? 'Completed' : 'Pending'
                ]);
            } else {
                LessonPlan::where('id', $item['id'])
                    ->where('batch_subject_id', $subjectId)
                    ->update([
                        'topic_content' => $topicText,
                        'proposed_date' => !empty($item['proposed_date']) ? $item['proposed_date'] : null,
                        'actual_date' => !empty($item['actual_date']) ? $item['actual_date'] : null,
                        'co_id' => $item['co_id'] ?? 'CO1',
                        'sub_batch' => $item['sub_batch'] ?? '',
                        'pedagogy' => $pedagogy,
                        'mode' => $mode,
                        'remarks' => $item['remarks'] ?? '',
                        'status' => !empty($item['actual_date']) ? 'Completed' : 'Pending'
                    ]);
            }
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'All lesson plan rows saved successfully!']);
    }

    /**
     * Helper to resolve Lecturer Name specifically (filtering out Demonstrators, Tradesmen, etc.)
     */
    private function resolveLecturerName($subjectId, $branchCode)
    {
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        // Priority 1: Assigned staff with explicit Lecturer designation
        foreach ($assignedStaff as $s) {
            $desg = strtolower(trim($s->designation));
            if (in_array($desg, ['lecturer', 'assistant professor', 'associate professor', 'professor'])) {
                return $s->name;
            }
        }

        // Priority 2: Query staff_profiles for a Lecturer in this department/branch
        $deptLecturer = DB::table('staff_profiles')
            ->where('branch', $branchCode)
            ->where('designation', 'Lecturer')
            ->value('name');

        if ($deptLecturer) {
            return $deptLecturer;
        }

        // Priority 3: Non-demonstrator assigned staff (e.g. HOD / Coordinator)
        foreach ($assignedStaff as $s) {
            $desg = strtolower(trim($s->designation));
            if (!in_array($desg, ['demonstrator', 'tradesman', 'trade_instructor', 'laboratory_assistant'])) {
                return $s->name;
            }
        }

        return 'Lecturer Name';
    }

    /**
     * Resolve Global Practicum Subject Type Classification (SBTE 2026 Table 6.4 vs 6.3 vs Table 4.2 Design)
     */
    private function resolveSubjectType($practicumCourseFile, $batchSubject)
    {
        $title = strtolower(($practicumCourseFile ? $practicumCourseFile->course_title : '') . ' ' . ($batchSubject ? $batchSubject->subject_name : ''));
        
        if (str_contains($title, 'design') || str_contains($title, 'drawing') || str_contains($title, 'cad') || str_contains($title, 'drafting')) {
            $ese = $practicumCourseFile->ese_marks ?? 60;
            return [
                'type' => 'design_paper',
                'label' => "📐 Design Paper - ESE {$ese}M",
                'pattern' => 'table_4_2_design',
                'ese_marks' => $ese
            ];
        }

        $ese = $practicumCourseFile->ese_marks ?? 100;
        if ($ese >= 100) {
            return [
                'type' => 'program_core',
                'label' => '💻 Program Core - ESE 100M',
                'pattern' => 'table_4_1_standard',
                'ese_marks' => 100
            ];
        } else {
            return [
                'type' => 'basic_science',
                'label' => '🔬 Basic Science - ESE 60M',
                'pattern' => 'table_4_1_standard',
                'ese_marks' => 60
            ];
        }
    }

    /**
     * Helper to get classroom and resolve DB department/batch metadata
     */
    private function resolveClassroomMeta($subjectId, $classroom_id)
    {
        $classroom = R26ClassManagement::where('classroom_id', $classroom_id)->first();
        if (!$classroom) {
            $classroom = ClassManagement::where('classroom_id', $classroom_id)->first();
        }

        $branchCode = strtoupper($classroom->branch ?? $classroom->department ?? 'EL');
        $deptMap = [
            'EL' => 'Electronics Engineering',
            'ECE' => 'Electronics Engineering',
            'ELECTRONICS' => 'Electronics Engineering',
            'CT' => 'Computer Engineering',
            'CSE' => 'Computer Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'ME' => 'Mechanical Engineering',
            'MECHANICAL' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering',
            'CIVIL' => 'Civil Engineering',
            'AU' => 'Automobile Engineering',
        ];

        $departmentName = $deptMap[$branchCode] ?? ($classroom->department ?? ($branchCode . ' Engineering'));
        $batchYear = $classroom->batch_year ?? 2026;
        $batchName = $departmentName . ' (' . $batchYear . '-' . ($batchYear + 3) . ')';
        $lecturerName = $this->resolveLecturerName($subjectId, $branchCode);

        return [
            'classroom' => $classroom,
            'departmentName' => $departmentName,
            'batchName' => $batchName,
            'batchYear' => $batchYear,
            'lecturerName' => $lecturerName
        ];
    }

    /**
     * Print NBA 90-Hour Combined Lesson Planner PDF
     */
    public function printLessonPlanPdf($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $meta = $this->resolveClassroomMeta($subjectId, $batchSubject->classroom_id);
        $classroom = $meta['classroom'];
        $departmentName = $meta['departmentName'];
        $batchName = $meta['batchName'];
        $batchYear = $meta['batchYear'];
        $lecturerName = $meta['lecturerName'];

        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
        
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        return view('r26_practicum.lesson_plan_print', compact('batchSubject', 'classroom', 'practicumCourseFile', 'lessonPlans', 'assignedStaff', 'departmentName', 'batchName', 'batchYear', 'lecturerName'));
    }

    /**
     * Save Self-Learning Activity Configurations (CA1)
     */
    public function saveSelfLearningConfigs(Request $request, $subjectId)
    {
        try {
            $request->validate([
                'configs' => 'required|array'
            ]);

            $practicumFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
            $practicumFile->self_learning_configs = $request->input('configs');
            $practicumFile->save();

            return response()->json(['status' => 'SUCCESS', 'message' => 'Self-learning activities customized successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Save Self-Learning Student Marks (CA1 - Max 15 Marks -> 5 CIA)
     */
    public function saveSelfLearningMarks(Request $request, $subjectId)
    {
        try {
            $batchSubject = BatchSubject::findOrFail($subjectId);
            $userId = Session::get('userId');
            $marksData = $request->input('marks_data', []);

            // Ensure syllabus_registry entry exists for foreign key constraint
            $existsInRegistry = DB::table('syllabus_registry')
                ->where('subject_code', $batchSubject->subject_code)
                ->exists();

            if (!$existsInRegistry) {
                DB::table('syllabus_registry')->insert([
                    'subject_code' => $batchSubject->subject_code,
                    'revision_year' => 2026,
                    'subject_name' => $batchSubject->subject_name,
                    'co_count' => 4,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Ensure entered_by exists in staff_profiles
            $enteredBy = DB::table('staff_profiles')
                ->where('mobile_no', $userId)
                ->value('mobile_no');

            foreach ($marksData as $row) {
                $regNo = $row['reg_no'] ?? null;
                if (!$regNo) continue;

                $coData = $row['co_details'] ?? [];
                foreach ($coData as $coTag => $activities) {
                    // Delete old Self Study entries for this student/CO
                    DB::table('academic_marks')
                        ->where('reg_no', $regNo)
                        ->where('batch_subject_id', $subjectId)
                        ->where('co_tag', $coTag)
                        ->where('category', 'like', 'Self Study:%')
                        ->delete();

                    foreach ($activities as $actKey => $val) {
                        $score = floatval($val ?? 0);
                        $actName = ucfirst(str_replace('_', ' ', $actKey));
                        
                        DB::table('academic_marks')->insert([
                            'mark_id' => (string) \Illuminate\Support\Str::uuid(),
                            'reg_no' => $regNo,
                            'batch_subject_id' => $subjectId,
                            'subject_code' => $batchSubject->subject_code,
                            'category' => 'Self Study: ' . $actName,
                            'co_tag' => $coTag,
                            'max_marks' => 15,
                            'marks_obtained' => $score,
                            'entered_by' => $enteredBy,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Self-learning CA marks saved successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Print Self-Learning Activity-Wise Splitup Report PDF (Report 1)
     */
    public function printSelfLearningSplitupPdf($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $meta = $this->resolveClassroomMeta($subjectId, $batchSubject->classroom_id);
        $classroom = $meta['classroom'];
        $departmentName = $meta['departmentName'];
        $batchName = $meta['batchName'];
        $batchYear = $meta['batchYear'];
        $lecturerName = $meta['lecturerName'];

        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
        $students = Student::where('classroom_id', $batchSubject->classroom_id)->orderBy('roll_no', 'asc')->get();

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        $slAcademicMarks = DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->where('category', 'like', 'Self Study:%')
            ->get()
            ->groupBy('reg_no');

        $slStudentSplitup = [];
        foreach ($students as $st) {
            $rNo = $st->reg_no;
            $stMarks = $slAcademicMarks->get($rNo, collect());
            
            $split = [
                'CO1' => ['assignment' => 0, 'mcq' => 0],
                'CO2' => ['assignment' => 0, 'mcq' => 0],
                'CO3' => ['assignment' => 0, 'mcq' => 0],
                'CO4' => ['assignment' => 0, 'mcq' => 0],
            ];

            foreach ($stMarks as $m) {
                $co = $m->co_tag ?: 'CO1';
                $rawCat = str_replace('Self Study: ', '', $m->category);
                $actKey = strtolower(str_replace(' ', '_', $rawCat));
                $split[$co][$actKey] = floatval($m->marks_obtained);
            }

            $slStudentSplitup[$rNo] = $split;
        }

        $slConfigs = $practicumCourseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => true, 'mcq' => true],
            'CO2' => ['assignment' => true, 'mcq' => true],
            'CO3' => ['assignment' => true, 'mcq' => true],
            'CO4' => ['assignment' => true, 'mcq' => true]
        ];

        return view('r26_practicum.self_learning_splitup_print', compact('batchSubject', 'classroom', 'practicumCourseFile', 'students', 'slStudentSplitup', 'slConfigs', 'assignedStaff', 'departmentName', 'batchName', 'batchYear', 'lecturerName'));
    }

    /**
     * Print Self-Learning Total Summary Report PDF (Report 2)
     */
    public function printSelfLearningSummaryPdf($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $meta = $this->resolveClassroomMeta($subjectId, $batchSubject->classroom_id);
        $classroom = $meta['classroom'];
        $departmentName = $meta['departmentName'];
        $batchName = $meta['batchName'];
        $batchYear = $meta['batchYear'];
        $lecturerName = $meta['lecturerName'];

        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
        $students = Student::where('classroom_id', $batchSubject->classroom_id)->orderBy('roll_no', 'asc')->get();

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        $slAcademicMarks = DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->where('category', 'like', 'Self Study:%')
            ->get()
            ->groupBy('reg_no');

        $studentResults = $students->map(function ($student) use ($slAcademicMarks) {
            $regNo = $student->reg_no;
            $stSlMarks = $slAcademicMarks->get($regNo, collect());
            $slScoreRaw = $stSlMarks->count() > 0 ? ($stSlMarks->avg('marks_obtained') ?: 0.00) : 0.00;
            $slMarks = round(($slScoreRaw / 15.0) * 5.0, 2);

            $coScores = ['CO1' => 0.0, 'CO2' => 0.0, 'CO3' => 0.0, 'CO4' => 0.0];
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $coMarks = $stSlMarks->where('co_tag', $coTag);
                $coScores[$coTag] = $coMarks->count() > 0 ? round($coMarks->avg('marks_obtained'), 2) : 0.00;
            }

            return [
                'roll_no' => $student->roll_no,
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'co_scores' => $coScores,
                'sl_score_raw' => $slScoreRaw,
                'sl_marks' => $slMarks
            ];
        });

        return view('r26_practicum.self_learning_summary_print', compact('batchSubject', 'classroom', 'practicumCourseFile', 'studentResults', 'assignedStaff', 'departmentName', 'batchName', 'batchYear', 'lecturerName'));
    }

    /**
     * Generate Series Exam QP Draft (Preview Only — does NOT save).
     * Pulls from r26_question_bank first; falls back to built-in templates.
     */
    public function generateSeriesQp(Request $request, $subjectId, $seriesNo)
    {
        try {
            $batchSubject = BatchSubject::findOrFail($subjectId);
            $practicumFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->first();
            $subjectType = $this->resolveSubjectType($practicumFile, $batchSubject);
            $patternType = $subjectType['pattern'];

            $coTag = match($seriesNo) {
                'Series 1', 'CO1' => 'CO1',
                'Series 2', 'CO2' => 'CO2',
                'Series 3', 'CO3' => 'CO3',
                'Series 4', 'CO4' => 'CO4',
                'Practical Series 1', 'Test 1 (CO1+CO2)' => 'CO1+CO2',
                'Practical Series 2', 'Test 2 (CO3+CO4)' => 'CO3+CO4',
                default => 'CO1'
            };

            $isPractical = (strpos($seriesNo, 'Practical') !== false || strpos($seriesNo, 'Test 1') !== false || strpos($seriesNo, 'Test 2') !== false);
            if ($isPractical) {
                $patternType = 'practical_series';
            }

            // ── Try question bank first ────────────────────────────────────
            $bankGrouped = \App\Models\R26QuestionBank::getForSubjectCo(
                $batchSubject->subject_code, $coTag, $patternType
            );

            $bankHasData = !empty($bankGrouped['part_a']) || !empty($bankGrouped['part_b']);

            if ($bankHasData) {
                $qpData = $bankGrouped;
                $source = 'question_bank';
            } else {
                // ── Fallback to dynamic syllabus parsing & AI generation ───
                $source = 'template';

                // Decode parsed COS and Modules
                $parsedCos = null;
                $parsedModules = null;
                if ($practicumFile) {
                    $parsedCos = is_array($practicumFile->parsed_cos) ? $practicumFile->parsed_cos : json_decode($practicumFile->parsed_cos, true);
                    $parsedModules = is_array($practicumFile->parsed_modules) ? $practicumFile->parsed_modules : json_decode($practicumFile->parsed_modules, true);
                }

                // Match CO description
                $coDesc = '';
                if ($parsedCos) {
                    foreach ($parsedCos as $coObj) {
                        if (($coObj['id'] ?? '') === $coTag) {
                            $coDesc = $coObj['description'] ?? '';
                            break;
                        }
                    }
                }
                if (empty($coDesc)) {
                    $defaults = [
                        'CO1' => 'Demonstrate basic concepts, electrical quantities, signal types, and electronic measuring instruments.',
                        'CO2' => 'Construct and analyze basic electronic circuits using passive electronic components.',
                        'CO3' => 'Apply semiconductor theory to demonstrate the operation of semiconductor devices.',
                        'CO4' => 'Demonstrate Printed Circuit Boards and apply soldering techniques.'
                    ];
                    $coDesc = $defaults[$coTag] ?? 'Apply subject knowledge.';
                }

                // Match Module Content
                $moduleTitle = '';
                $moduleContent = '';
                if ($parsedModules) {
                    $moduleIdMap = [
                        'CO1' => ['I', '1'],
                        'CO2' => ['II', '2'],
                        'CO3' => ['III', '3'],
                        'CO4' => ['IV', '4']
                    ];
                    $targetIds = $moduleIdMap[$coTag] ?? [];
                    foreach ($parsedModules as $mod) {
                        $mId = strval($mod['module_id'] ?? $mod['id'] ?? '');
                        if (in_array($mId, $targetIds)) {
                            $moduleTitle = $mod['title'] ?? '';
                            $moduleContent = $mod['content'] ?? '';
                            break;
                        }
                    }
                }

                if (empty($moduleContent)) {
                    $defaultsMod = [
                        'CO1' => ['title' => 'Introduction to Electronics, Signals and Measurements', 'content' => 'Introduction, electrical quantities, signals, laboratory measuring instruments (CRO, Multimeter)'],
                        'CO2' => ['title' => 'Passive Electronic Components', 'content' => 'Resistors, capacitors, inductors, transformers, series and parallel circuit testing'],
                        'CO3' => ['title' => 'Active Electronic Components', 'content' => 'Semiconductor devices, PN junction diode, Zener diode, LED, VI characteristics'],
                        'CO4' => ['title' => 'PCB and Soldering', 'content' => 'PCB types, layout design, fabrication steps, soldering practice and techniques']
                    ];
                    $moduleTitle = $defaultsMod[$coTag]['title'] ?? 'Syllabus Module';
                    $moduleContent = $defaultsMod[$coTag]['content'] ?? 'Course topics and practical applications.';
                }

                // Try Gemini AI Generation if enabled in settings
                $apiKey = env('GEMINI_API_KEY');
                $aiEnabled = \App\Http\Controllers\SystemSettingController::isAiEnabled();
                $geminiSuccess = false;

                if ($aiEnabled && $apiKey) {
                    try {
                        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
                            ?: R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
                        
                        if ($patternType === 'practical_series') {
                            $prompt = "You are an expert engineering college lab examiner.
Generate a complete practical series exam question paper for: '{$coTag}'
Course Name: '{$batchSubject->subject_name}' (Code: '{$batchSubject->subject_code}')
Department: '" . ($classroom->branch ?? 'Engineering') . "'

Generate exactly 2 alternative practical tasks for 'part_a' (each task is worth 40 marks, cognitive level 'Apply' or 'Analyze').
Each task must be a practical lab experiment task relevant to this course.

For EACH task, you must provide:
1. 'scheme_key': Detailed rubric marking split based on Table 3.1: Writeup & Procedure (10M) + Setup & Execution (10M) + Observation & Result (10M) + Viva Voce (5M) + Record Completion (5M) = 40 Marks total.
2. 'answer_key': The model answer/key details (expected circuit/block diagram, apparatus list, steps, and expected readings).

Return ONLY a valid JSON object matching the exact schema:
{
  \"part_a\": [
    {
      \"q_no\": \"1\",
      \"text\": \"Practical Task 1 description...\",
      \"marks\": 40,
      \"co\": \"{$coTag}\",
      \"bloom\": \"Apply\",
      \"choice_group\": \"Answer any ONE\",
      \"scheme_key\": \"1. Writeup & Procedure: 10 Marks\\n2. Setup & Execution: 10 Marks\\n3. Observation & Result: 10 Marks\\n4. Viva Voce: 5 Marks\\n5. Record Completion: 5 Marks\",
      \"answer_key\": \"Expected diagram, readings, and procedure...\"
    }
  ]
}";
                        } else {
                            $prompt = "You are an expert university engineering examiner.
Generate a complete examination question paper for the course outcome: '{$coTag}'
Course: '{$batchSubject->subject_name}' (Code: '{$batchSubject->subject_code}')
Department/Branch: '" . ($classroom->branch ?? 'Engineering') . "'
Course Outcome Description: '{$coDesc}'
Module/Syllabus content: '{$moduleContent}'

The question paper must follow the pattern type '{$patternType}'.

" . ($patternType === 'table_4_2_design' ? "
For 'table_4_2_design', generate:
- Exactly 6 questions for 'part_a' (5 marks each, cognitive levels should be 'Understand' or 'Apply').
- Exactly 4 questions for 'part_b' (10 marks each, cognitive levels should be 'Analyze' or 'Evaluate' or 'Create'). Q7(a) and Q7(b) must have choice_group 'Set 1'. Q8(a) and Q8(b) must have choice_group 'Set 2'.
" : "
For 'table_4_1_standard', generate:
- Exactly 2 questions for 'part_a' (1 mark each, cognitive levels should be 'Remember' or 'Understand').
- Exactly 3 questions for 'part_b' (3 marks each, cognitive levels should be 'Understand' or 'Apply').
- Exactly 3 questions for 'part_c' (7 marks each, cognitive levels should be 'Apply' or 'Analyze' or 'Evaluate'). All Part C questions must have choice_group 'Answer any 2 of 3'.
") . "

For EACH question, you must also provide:
1. 'scheme_key': Short marking breakdown/guidelines (e.g., 'Correct definition (2M) + diagram (3M)').
2. 'answer_key': The detailed model answer/key details.

Return ONLY a valid JSON object matching the exact schema (do not include markdown code block syntax):
{
  \"part_a\": [
    {
      \"q_no\": \"1\",
      \"text\": \"Question text here?\",
      \"marks\": 5,
      \"co\": \"{$coTag}\",
      \"bloom\": \"Understand\",
      \"choice_group\": \"\",
      \"scheme_key\": \"Marking split...\",
      \"answer_key\": \"Detailed model answer...\"
    }
  ],
  \"part_b\": [
    ...
  ],
  \"part_c\": [
    ...
  ]
}";
                        }

                        $response = \Illuminate\Support\Facades\Http::timeout(60)->post(
                            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                            [
                                'contents' => [
                                    [
                                        'parts' => [
                                            ['text' => $prompt]
                                        ]
                                    ]
                                ],
                                'generationConfig' => [
                                    'responseMimeType' => 'application/json',
                                ]
                            ]
                        );

                        if ($response->successful()) {
                            $geminiText = $response->json('candidates.0.content.parts.0.text', '');
                            $cleanText = trim(preg_replace('/```json|```/i', '', $geminiText));
                            $decoded = json_decode($cleanText, true);
                            if (is_array($decoded) && (!empty($decoded['part_a']) || !empty($decoded['part_b']))) {
                                $qpData = $decoded;
                                $geminiSuccess = true;
                                $source = 'ai_gemini';
                            }
                        }
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning("Gemini Practicum QP generation failed: " . $e->getMessage());
                    }
                }

                if (!$geminiSuccess) {
                    if ($patternType === 'practical_series') {
                        $targetCos = ($coTag === 'CO1+CO2') ? ['CO1', 'CO2'] : ['CO3', 'CO4'];
                        $filteredExps = [];
                        if ($practicumFile && $practicumFile->parsed_experiments) {
                            $allExps = is_array($practicumFile->parsed_experiments) ? $practicumFile->parsed_experiments : json_decode($practicumFile->parsed_experiments, true);
                            foreach ($allExps as $e) {
                                $eCo = $e['co_id'] ?? $e['co'] ?? '';
                                if (in_array($eCo, $targetCos)) {
                                    $filteredExps[] = $e;
                                }
                            }
                        }

                        if (empty($filteredExps)) {
                            $filteredExps = [
                                ['experiment_no' => 'EXP-01', 'title' => 'Identification and testing of passive components', 'co_id' => 'CO1'],
                                ['experiment_no' => 'EXP-02', 'title' => 'Series and Parallel Resistor circuit construction and testing', 'co_id' => 'CO2'],
                                ['experiment_no' => 'EXP-03', 'title' => 'PN Junction Diode characteristics plotting', 'co_id' => 'CO3'],
                                ['experiment_no' => 'EXP-04', 'title' => 'PCB layout preparation and soldering practice', 'co_id' => 'CO4']
                            ];
                        }

                        $qpData = [
                            'part_a' => []
                        ];
                        $idx = 1;
                        foreach ($filteredExps as $exp) {
                            $qpData['part_a'][] = [
                                'q_no' => strval($idx++),
                                'text' => "Perform the practical experiment: " . ($exp['title'] ?? ''),
                                'marks' => 40,
                                'co' => $exp['co_id'] ?? $coTag,
                                'bloom' => 'Apply',
                                'choice_group' => 'Answer any ONE',
                                'scheme_key' => "1. Writeup & Procedure: 10 Marks\n2. Setup & Execution: 10 Marks\n3. Observation & Result: 10 Marks\n4. Viva Voce: 5 Marks\n5. Record Completion: 5 Marks",
                                'answer_key' => "Expected circuit/block diagram, apparatus list, standard steps, and sample observation readings for " . ($exp['title'] ?? '')
                            ];
                            if ($idx > 2) break; // Limit to 2 choice tasks
                        }
                    } else {
                        // Parse syllabus module content into key topics
                        $topics = array_filter(array_map('trim', explode(',', $moduleContent)));
                        if (count($topics) < 3) {
                            $topics = array_filter(array_map('trim', explode(';', $moduleContent)));
                        }
                        $topics = array_values(array_unique($topics));
                        while (count($topics) < 8) {
                            $topics[] = $coDesc;
                        }

                        // Generate customized QP Data from the actual syllabus topics
                        if ($patternType === 'table_4_2_design') {
                            $qpData = [
                                'part_a' => [
                                    ['q_no' => '1', 'text' => "State the key design criteria and basic working equations for {$topics[0]} system.", 'marks' => 5, 'co' => $coTag, 'bloom' => 'Understand', 'scheme_key' => "Define design criteria (2M) + write standard equations (3M)", 'answer_key' => "Governing mathematical equations and design criteria details for {$topics[0]}."],
                                    ['q_no' => '2', 'text' => "Explain the safety factors, stress margins, and tolerance limits applicable in {$topics[1]}.", 'marks' => 5, 'co' => $coTag, 'bloom' => 'Understand', 'scheme_key' => "Safety margin definition (2M) + stress limits explanation (3M)", 'answer_key' => "Stress limits, safety margins, and tolerance values defined for {$topics[1]}."],
                                    ['q_no' => '3', 'text' => "Describe the step-by-step layout design and drafting procedure for {$topics[2]} component.", 'marks' => 5, 'co' => $coTag, 'bloom' => 'Understand', 'scheme_key' => "Procedural steps listed (3M) + block layout diagram (2M)", 'answer_key' => "Complete layout drafting steps and procedural design parameters for {$topics[2]}."],
                                    ['q_no' => '4', 'text' => "List the functional material specifications, standards, and dimensions for {$topics[3]}.", 'marks' => 5, 'co' => $coTag, 'bloom' => 'Understand', 'scheme_key' => "Material standard grades (2M) + dimension listing (3M)", 'answer_key' => "BIS / ISO grade specifications and standard dimensions for {$topics[3]}."],
                                    ['q_no' => '5', 'text' => "Apply the principles of {$topics[4]} to explain its functional requirements and fits.", 'marks' => 5, 'co' => $coTag, 'bloom' => 'Apply', 'scheme_key' => "Functional requirements (2M) + fit explanation (3M)", 'answer_key' => "Requirements analysis and selection of correct mechanical/electrical fits for {$topics[4]}."],
                                    ['q_no' => '6', 'text' => "Identify the boundary conditions and load characteristics for {$topics[5]} system under test.", 'marks' => 5, 'co' => $coTag, 'bloom' => 'Apply', 'scheme_key' => "Boundary conditions identified (2M) + load curve (3M)", 'answer_key' => "Load conditions, constraints, and boundary criteria for {$topics[5]}."],
                                ],
                                'part_b' => [
                                    ['q_no' => '7(a)', 'text' => "Design and analyze the structural setup for {$topics[6]} given standard operating conditions.", 'marks' => 10, 'co' => $coTag, 'bloom' => 'Analyze', 'choice_group' => 'Set 1', 'scheme_key' => "Design setup (2M) + calculation steps (5M) + schematic diagram (3M)", 'answer_key' => "Complete analytical solution, calculations, and schematic diagram for {$topics[6]}."],
                                    ['q_no' => '7(b)', 'text' => "OR: Develop a comprehensive schematic design layout and detailed CAD drafting plan for {$topics[7]}.", 'marks' => 10, 'co' => $coTag, 'bloom' => 'Analyze', 'choice_group' => 'Set 1', 'scheme_key' => "Schematic layout (4M) + drafting sequence (4M) + annotations (2M)", 'answer_key' => "CAD drafting plan, annotations, and orthographic projections for {$topics[7]}."],
                                    ['q_no' => '8(a)', 'text' => "Perform complete stress-strain analysis and dimension optimization for {$topics[0]}.", 'marks' => 10, 'co' => $coTag, 'bloom' => 'Analyze', 'choice_group' => 'Set 2', 'scheme_key' => "Optimized design setup (3M) + analysis details (5M) + conclusion (2M)", 'answer_key' => "Optimized parameters and strain analysis calculations for {$topics[0]}."],
                                    ['q_no' => '8(b)', 'text' => "OR: Formulate design equations and draw detailed cross-sectional assembly views for {$topics[1]}.", 'marks' => 10, 'co' => $coTag, 'bloom' => 'Analyze', 'choice_group' => 'Set 2', 'scheme_key' => "Formulated equations (4M) + cross-section layout (4M) + labelling (2M)", 'answer_key' => "Assembled sectional views, labels, and mathematical design for {$topics[1]}."],
                                ]
                            ];
                        } else {
                            $qpData = [
                                'part_a' => [
                                    ['q_no' => '1', 'text' => "Define the fundamental concept and primary function of {$topics[0]}.", 'marks' => 1, 'co' => $coTag, 'bloom' => 'Remember', 'scheme_key' => "Correct definition or function = 1M", 'answer_key' => "Standard definition of {$topics[0]} as per the syllabus."],
                                    ['q_no' => '2', 'text' => "State the standard unit, formula, or law governing the operation of {$topics[1]}.", 'marks' => 1, 'co' => $coTag, 'bloom' => 'Remember', 'scheme_key' => "Correct law, unit or formula = 1M", 'answer_key' => "Governing law / formula / SI unit for {$topics[1]}."],
                                ],
                                'part_b' => [
                                    ['q_no' => '3', 'text' => "Explain the operating mechanism of {$topics[2]} using a block schematic or circuit diagram.", 'marks' => 3, 'co' => $coTag, 'bloom' => 'Understand', 'scheme_key' => "Functional explanation (2M) + block/circuit diagram (1M)", 'answer_key' => "Schematic representation and process explanation for {$topics[2]}."],
                                    ['q_no' => '4', 'text' => "Distinguish between the primary features and secondary features of {$topics[3]}.", 'marks' => 3, 'co' => $coTag, 'bloom' => 'Understand', 'scheme_key' => "Comparison points listed (3M)", 'answer_key' => "At least 3 valid comparison points for {$topics[3]} characteristics."],
                                    ['q_no' => '5', 'text' => "Derive the mathematical expression or setup equation for {$topics[4]} output response.", 'marks' => 3, 'co' => $coTag, 'bloom' => 'Apply', 'scheme_key' => "Derivation setup (1M) + step-by-step derivation (2M)", 'answer_key' => "Analytical derivation leading to standard expression for {$topics[4]}."],
                                ],
                                'part_c' => [
                                    ['q_no' => '6', 'text' => "Design and analyze a complete {$topics[5]} system to satisfy the given system specifications.", 'marks' => 7, 'co' => $coTag, 'bloom' => 'Analyze', 'choice_group' => 'Answer any 2 of 3', 'scheme_key' => "Circuit/System setup (2M) + calculations (3M) + diagram/schematic (2M)", 'answer_key' => "Complete layout, design specifications, and schematic diagram for {$topics[5]}."],
                                    ['q_no' => '7', 'text' => "Evaluate the performance parameters and construct detailed working equations for {$topics[6]}.", 'marks' => 7, 'co' => $coTag, 'bloom' => 'Analyze', 'choice_group' => 'Answer any 2 of 3', 'scheme_key' => "Parameters list (2M) + working equations (3M) + validation (2M)", 'answer_key' => "Performance validation and mathematical models for {$topics[6]}."],
                                    ['q_no' => '8', 'text' => "Formulate and solve the implementation or application problem for {$topics[7]} with complete working.", 'marks' => 7, 'co' => $coTag, 'bloom' => 'Analyze', 'choice_group' => 'Answer any 2 of 3', 'scheme_key' => "Problem setup (2M) + step-by-step solution (4M) + final results (1M)", 'answer_key' => "Full design solution, steps, and final results for {$topics[7]} application."],
                                ]
                            ];
                        }
                    }
                }
            }

            // Return as preview — no DB save
            return response()->json([
                'status'       => 'SUCCESS',
                'source'       => $source,
                'message'      => $source === 'question_bank'
                    ? '✅ Questions loaded from Question Bank!'
                    : ($source === 'ai_gemini' ? '🤖 AI questions generated using Gemini API!' : '🤖 AI template questions generated (edit before saving)'),
                'co_tag'       => $coTag,
                'pattern_type' => $patternType,
                'qp_data'      => $qpData,
            ]);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Save Customized Series Exam Question Paper, Scheme & Answer Key.
     * Also upserts each question to r26_question_bank for future reuse.
     */
    public function saveSeriesQp(Request $request, $subjectId, $seriesNo)
    {
        try {
            $batchSubject = BatchSubject::findOrFail($subjectId);
            $qpData     = $request->input('qp_data', []);
            $schemeData = $request->input('scheme_data', []);
            $answerKey  = $request->input('answer_key', []);
            $patternType = $request->input('pattern_type', 'table_4_1_standard');
            $coTag       = $request->input('co_tag', 'CO1');
            $userId      = Session::get('userId');

            // 1. Save / update the QP record
            $qpRecord = \App\Models\R26SeriesExamQp::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'series_no' => $seriesNo],
                [
                    'co_tag'           => $coTag,
                    'pattern_type'     => $patternType,
                    'max_marks'        => ($patternType === 'practical_series') ? 40 : (($patternType === 'table_4_2_design') ? 50 : 25),
                    'duration_minutes' => ($patternType === 'practical_series' || str_contains($coTag, '+') || str_contains($coTag, ',')) ? 180 : 60,
                    'qp_data'          => $qpData,
                    'scheme_data'      => $schemeData,
                    'answer_key'       => $answerKey,
                    'status'           => 'saved',
                    'created_by'       => $userId,
                ]
            );

            // 2. Upsert every question to r26_question_bank
            $allParts = ['part_a' => $qpData['part_a'] ?? [],
                         'part_b' => $qpData['part_b'] ?? [],
                         'part_c' => $qpData['part_c'] ?? []];

            $schemeFlat = [];
            foreach (['part_a','part_b','part_c'] as $pt) {
                foreach ($schemeData[$pt] ?? [] as $sq) {
                    $schemeFlat[$sq['q_no'] ?? ''] = $sq['scheme_key'] ?? null;
                }
            }
            $keyFlat = [];
            foreach (['part_a','part_b','part_c'] as $pt) {
                foreach ($answerKey[$pt] ?? [] as $aq) {
                    $keyFlat[$aq['q_no'] ?? ''] = $aq['answer_key'] ?? null;
                }
            }

            $bloomMap = [
                'Remember'   => 'L1',
                'Understand' => 'L2',
                'Apply'      => 'L3',
                'Analyze'    => 'L4',
                'Evaluate'   => 'L5',
                'Create'     => 'L6'
            ];

            foreach ($allParts as $part => $questions) {
                foreach ($questions as $q) {
                    $qNo = $q['q_no'] ?? null;
                    $rawBloom = trim($q['bloom'] ?? 'L2');
                    $bloomLevel = $bloomMap[$rawBloom] ?? (strlen($rawBloom) > 5 ? substr($rawBloom, 0, 5) : $rawBloom);

                    \App\Models\R26QuestionBank::updateOrCreate(
                        [
                            'subject_code' => $batchSubject->subject_code,
                            'co_tag'       => $coTag,
                            'pattern_type' => $patternType,
                            'part'         => $part,
                            'question_text'=> $q['text'] ?? '',
                        ],
                        [
                            'batch_subject_id' => $subjectId,
                            'series_no'        => $seriesNo,
                            'q_no'             => $qNo,
                            'marks'            => $q['marks'] ?? 1,
                            'bloom_level'      => $bloomLevel,
                            'choice_group'     => $q['choice_group'] ?? null,
                            'scheme_key'       => $schemeFlat[$qNo] ?? null,
                            'answer_key'       => $keyFlat[$qNo] ?? null,
                            'is_active'        => true,
                            'created_by'       => $userId,
                        ]
                    );
                }
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'QP saved and added to Question Bank!', 'qp' => $qpRecord]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Print Series Question Paper PDF View
     */
    public function printSeriesQpPdf($subjectId, $seriesNo)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $meta = $this->resolveClassroomMeta($subjectId, $batchSubject->classroom_id);
        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
        $seriesNo = str_replace('+', ' ', urldecode($seriesNo));
        $qpRecord = \App\Models\R26SeriesExamQp::where('batch_subject_id', $subjectId)->where('series_no', $seriesNo)->firstOrFail();
        $subjectType = $this->resolveSubjectType($practicumCourseFile, $batchSubject);
        return view('r26_practicum.series_qp_print', array_merge($meta, compact('batchSubject', 'practicumCourseFile', 'qpRecord', 'seriesNo', 'subjectType')));
    }

    /**
     * Print Series Evaluation Scheme PDF View
     */
    public function printSeriesSchemePdf($subjectId, $seriesNo)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $meta = $this->resolveClassroomMeta($subjectId, $batchSubject->classroom_id);
        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
        $seriesNo = str_replace('+', ' ', urldecode($seriesNo));
        $qpRecord = \App\Models\R26SeriesExamQp::where('batch_subject_id', $subjectId)->where('series_no', $seriesNo)->firstOrFail();
        $subjectType = $this->resolveSubjectType($practicumCourseFile, $batchSubject);
        return view('r26_practicum.series_scheme_print', array_merge($meta, compact('batchSubject', 'practicumCourseFile', 'qpRecord', 'seriesNo', 'subjectType')));
    }

    /**
     * Print Series Answer Key PDF View (Confidential)
     */
    public function printSeriesAnswerKeyPdf($subjectId, $seriesNo)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $meta = $this->resolveClassroomMeta($subjectId, $batchSubject->classroom_id);
        $practicumCourseFile = R26PracticumCourseFile::where('batch_subject_id', $subjectId)->firstOrFail();
        $seriesNo = str_replace('+', ' ', urldecode($seriesNo));
        $qpRecord = \App\Models\R26SeriesExamQp::where('batch_subject_id', $subjectId)->where('series_no', $seriesNo)->firstOrFail();
        $subjectType = $this->resolveSubjectType($practicumCourseFile, $batchSubject);
        return view('r26_practicum.series_answer_key_print', array_merge($meta, compact('batchSubject', 'practicumCourseFile', 'qpRecord', 'seriesNo', 'subjectType')));
    }

    /**
     * Map SBTE grade letter to numeric marks out of 60
     */
    private function convertGradeToMarks($grade)
    {
        switch (strtoupper(trim($grade))) {
            case 'S': return 57.00; // 95%
            case 'A': return 51.00; // 85%
            case 'B': return 45.00; // 75%
            case 'C': return 39.00; // 65%
            case 'D': return 33.00; // 55%
            case 'P': return 27.00; // 45% (Pass)
            case 'F': return 0.00;  // Fail
            case 'FE': return 0.00;
            case 'I': return 0.00;
            default: return 0.00;
        }
    }

    /**
     * Print Attendance Report (Theory & Lab Separately) for Practicum Subject
     * Route: GET /r26/classroom/practicum/{subjectId}/attendance-report
     */
    public function printAttendanceReport($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = \App\Models\R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $practicumCourseFile = \App\Models\R26PracticumCourseFile::where('batch_subject_id', $subjectId)->first();

        // Fetch all lesson plans for this subject, separated by mode
        $theoryPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->where(function($q) {
                $q->whereIn('mode', ['L', 'ST'])
                  ->orWhereNull('mode')
                  ->orWhere('mode', '');
            })
            ->where(function($q) {
                $q->whereNotIn('pedagogy', ['Practical Lab (P)', 'Practical Series Exam (SP)', 'Series Practical Test (SP)'])
                  ->orWhereNull('pedagogy');
            })
            ->orderBy('day_no', 'asc')
            ->get(['id', 'day_no', 'proposed_date', 'actual_date', 'topic_content', 'co_id', 'pedagogy', 'mode']);

        $labPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->where(function($q) {
                $q->whereIn('mode', ['P', 'SP'])
                  ->orWhereIn('pedagogy', ['Practical Lab (P)', 'Practical Series Exam (SP)']);
            })
            ->orderBy('day_no', 'asc')
            ->get(['id', 'day_no', 'proposed_date', 'actual_date', 'topic_content', 'co_id', 'pedagogy', 'mode']);
        $allAttendance = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get();

        // Build attendance matrix for Theory sessions
        $theoryPlanIds = $theoryPlans->pluck('id')->all();
        $labPlanIds    = $labPlans->pluck('id')->all();

        // Group attendance by lesson_plan_id
        $attByPlan = $allAttendance->groupBy('lesson_plan_id');
        // Also group by reg_no for summary
        $attByRegNo = $allAttendance->groupBy('reg_no');

        // Build Theory Matrix: [reg_no => [plan_id => status]]
        $theoryMatrix = [];
        $theoryTotals = []; // [reg_no => ['present' => 0, 'total' => 0]]
        foreach ($students as $st) {
            $theoryMatrix[$st->reg_no] = [];
            $theoryTotals[$st->reg_no] = ['present' => 0, 'total' => 0];
        }
        foreach ($theoryPlans as $plan) {
            $planAtt = $attByPlan->get($plan->id, collect());
            $planAttByReg = $planAtt->keyBy('reg_no');
            foreach ($students as $st) {
                $rec = $planAttByReg->get($st->reg_no);
                $status = $rec ? $rec->status : null;
                $theoryMatrix[$st->reg_no][$plan->id] = $status;
                if ($status !== null) {
                    $theoryTotals[$st->reg_no]['total']++;
                    if (in_array($status, ['Present', 'Late'])) {
                        $theoryTotals[$st->reg_no]['present']++;
                    }
                }
            }
        }

        // Build Lab Matrix: [reg_no => [plan_id => status]]
        $labMatrix = [];
        $labTotals = []; // [reg_no => ['present' => 0, 'total' => 0]]
        foreach ($students as $st) {
            $labMatrix[$st->reg_no] = [];
            $labTotals[$st->reg_no] = ['present' => 0, 'total' => 0];
        }
        foreach ($labPlans as $plan) {
            $planAtt = $attByPlan->get($plan->id, collect());
            $planAttByReg = $planAtt->keyBy('reg_no');
            foreach ($students as $st) {
                $rec = $planAttByReg->get($st->reg_no);
                $status = $rec ? $rec->status : null;
                $labMatrix[$st->reg_no][$plan->id] = $status;
                if ($status !== null) {
                    $labTotals[$st->reg_no]['total']++;
                    if (in_array($status, ['Present', 'Late'])) {
                        $labTotals[$st->reg_no]['present']++;
                    }
                }
            }
        }

        // Staff info
        // Staff info
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        return view('r26_practicum.attendance_report_print', compact(
            'batchSubject',
            'classroom',
            'students',
            'practicumCourseFile',
            'theoryPlans',
            'labPlans',
            'theoryMatrix',
            'labMatrix',
            'theoryTotals',
            'labTotals',
            'assignedStaff'
        ));
    }

    /**
     * Print Consolidated Attendance Report (Theory & Lab summaries) for Practicum Subject
     * Route: GET /r26/classroom/practicum/{subjectId}/attendance-consolidated
     */
    public function printConsolidatedAttendanceReport($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = \App\Models\R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $practicumCourseFile = \App\Models\R26PracticumCourseFile::where('batch_subject_id', $subjectId)->first();

        // Fetch all lesson plans for this subject, separated by mode
        $theoryPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->where(function($q) {
                $q->whereIn('mode', ['L', 'ST'])
                  ->orWhereNull('mode')
                  ->orWhere('mode', '');
            })
            ->where(function($q) {
                $q->whereNotIn('pedagogy', ['Practical Lab (P)', 'Practical Series Exam (SP)', 'Series Practical Test (SP)'])
                  ->orWhereNull('pedagogy');
            })
            ->orderBy('day_no', 'asc')
            ->get(['id', 'day_no', 'proposed_date', 'actual_date', 'topic_content', 'co_id', 'pedagogy', 'mode']);

        $labPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->where(function($q) {
                $q->whereIn('mode', ['P', 'SP'])
                  ->orWhereIn('pedagogy', ['Practical Lab (P)', 'Practical Series Exam (SP)']);
            })
            ->orderBy('day_no', 'asc')
            ->get(['id', 'day_no', 'proposed_date', 'actual_date', 'topic_content', 'co_id', 'pedagogy', 'mode']);

        // Fetch all attendance records for this subject, keyed by lesson_plan_id + reg_no
        $allAttendance = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get();

        // Group attendance by lesson_plan_id
        $attByPlan = $allAttendance->groupBy('lesson_plan_id');

        // Build Theory Totals: [reg_no => ['present' => 0, 'total' => 0]]
        $theoryTotals = [];
        foreach ($students as $st) {
            $theoryTotals[$st->reg_no] = ['present' => 0, 'total' => 0];
        }
        foreach ($theoryPlans as $plan) {
            $planAtt = $attByPlan->get($plan->id, collect());
            $planAttByReg = $planAtt->keyBy('reg_no');
            foreach ($students as $st) {
                $rec = $planAttByReg->get($st->reg_no);
                $status = $rec ? $rec->status : null;
                if ($status !== null) {
                    $theoryTotals[$st->reg_no]['total']++;
                    if (in_array($status, ['Present', 'Late'])) {
                        $theoryTotals[$st->reg_no]['present']++;
                    }
                }
            }
        }

        // Build Lab Totals: [reg_no => ['present' => 0, 'total' => 0]]
        $labTotals = [];
        foreach ($students as $st) {
            $labTotals[$st->reg_no] = ['present' => 0, 'total' => 0];
        }
        foreach ($labPlans as $plan) {
            $planAtt = $attByPlan->get($plan->id, collect());
            $planAttByReg = $planAtt->keyBy('reg_no');
            foreach ($students as $st) {
                $rec = $planAttByReg->get($st->reg_no);
                $status = $rec ? $rec->status : null;
                if ($status !== null) {
                    $labTotals[$st->reg_no]['total']++;
                    if (in_array($status, ['Present', 'Late'])) {
                        $labTotals[$st->reg_no]['present']++;
                    }
                }
            }
        }

        // Staff info
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        return view('r26_practicum.attendance_consolidated_print', compact(
            'batchSubject',
            'classroom',
            'students',
            'practicumCourseFile',
            'theoryPlans',
            'labPlans',
            'theoryTotals',
            'labTotals',
            'assignedStaff'
        ));
    }
}

