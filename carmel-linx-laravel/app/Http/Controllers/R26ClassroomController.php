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

class R26ClassroomController extends Controller
{
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
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
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

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

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

        $studentCiaData = $students->map(function ($student) use ($attendanceData, $academicMarks, $subjectId, $batchSubject, $selfLearningConfigs, $testConfigs, $testAttempts, $submissions, $seriesExams) {
            $studentSubmissions = $submissions->get($student->reg_no, collect());
            $studentAttendance = $attendanceData->get($student->reg_no, collect());
            $totalAttendance = $studentAttendance->count();
            $present = $studentAttendance->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAttendance > 0 ? ($present / $totalAttendance) * 100 : 100.00;
            $attPercentage = round($attPercentage, 2);
            
            // Table 2.1 Attendance Marks Conversion
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
            
            $selfLearningMarks = round($totalAvgSum / 4, 2);
            $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
            $seriesExamMarks = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;
            
            $examMarks = [];
            foreach ($seriesExams as $ex) {
                $eMark = $studentMarks->where('category', 'Series Exam: ' . $ex->exam_name)->first();
                $examMarks[$ex->id] = $eMark ? (float)$eMark->marks_obtained : 0.0;
            }

            $eseRecord = $studentMarks->where('category', 'ESE')->first();
            $eseMarks = $eseRecord ? (float)$eseRecord->marks_obtained : 0.0;

            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'sbte_reg_no' => $student->sbte_reg_no,
                'attendance_percent' => round($attPercentage, 2),
                'attendance_marks' => $attMarks,
                'attendance_status' => $attStatus,
                'attendance_color' => $attColor,
                'self_learning_marks' => $selfLearningMarks,
                'series_exam_marks' => $seriesExamMarks,
                'total_cia' => $attMarks + $selfLearningMarks + $seriesExamMarks,
                'ese_marks' => $eseMarks,
                'grand_total' => $attMarks + $selfLearningMarks + $seriesExamMarks + $eseMarks,
                'co_details' => $coDetails,
                'exam_marks' => $examMarks
            ];
        });

        return view('r26.virtual_classroom_theory', compact('batchSubject', 'classroom', 'students', 'courseFile', 'lessonPlans', 'studentCiaData', 'selfLearningConfigs', 'seriesExams'));
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
                    'parsed_cos' => json_encode($cos),
                    'parsed_modules' => json_encode($modules),
                    'parsed_copo' => json_encode($packedCopo),
                    'parsed_textbooks' => json_encode(['Textbook Reference 1', 'Textbook Reference 2'])
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

        $branchMapping = [
            'CS' => 'Computer Engineering',
            'EL' => 'Electronics Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'ME' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering'
        ];
        
        $branchCode = strtoupper(Session::get('userBranch', ''));
        $branchName = $branchMapping[$branchCode] ?? 'Engineering';
        $lecturerName = Session::get('userName', 'Assigned Faculty');

        return view('r26.lesson_plan_print', compact('subject', 'plans', 'branchName', 'lecturerName'));
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

        $rows = $request->input('rows', []);
        $updated = 0;
        foreach ($rows as $row) {
            $id = $row['id'] ?? null;
            if (!$id) continue;

            if (str_starts_with((string)$id, 'new_')) {
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no'           => $row['day_no'] ?? 1,
                    'co_id'            => $row['co_id'] ?? 'CO1',
                    'topic_content'    => $row['topic_content'] ?? '',
                    'allocated_hours'  => $row['allocated_hours'] ?? 1,
                    'pedagogy'         => $row['pedagogy'] ?? 'Lecture',
                    'taxonomy'         => $row['taxonomy'] ?? null,
                    'proposed_date'    => $row['proposed_date'] ?? null,
                    'actual_date'      => $row['actual_date'] ?? null,
                    'status'           => $row['status'] ?? 'Pending'
                ]);
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
            $plan->proposed_date   = $row['proposed_date']   ?? $plan->proposed_date;
            $plan->actual_date     = $row['actual_date']     ?? $plan->actual_date;
            $plan->allocated_hours = $row['allocated_hours'] ?? $plan->allocated_hours;
            $plan->pedagogy        = $row['pedagogy']        ?? $plan->pedagogy;
            $plan->taxonomy        = $row['taxonomy']        ?? $plan->taxonomy;
            $plan->status          = $row['status']          ?? $plan->status;
            $plan->save();
            $updated++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => "{$updated} lesson plan rows saved successfully."]);
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        
        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->orderByRaw('CAST(roll_no AS UNSIGNED) ASC')
            ->orderBy('name', 'asc')
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

        $studentCiaData = $students->map(function ($student) use ($academicMarks, $selfLearningConfigs, $testConfigs, $testAttempts) {
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
                
                $coDetails[$coTag] = [
                    'assignment' => $valAssignment,
                    'mcq' => $valMcq,
                    'act3' => $valAct3,
                    'act4' => $valAct4,
                    'act5' => $valAct5,
                    'total' => $coTotal
                ];
            }
            
            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'self_learning_marks' => round($totalAvgSum / 4, 2),
                'co_details' => $coDetails
            ];
        });

        return view('r26.self_learning_print', compact('batchSubject', 'classroom', 'students', 'studentCiaData', 'selfLearningConfigs'));
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
        $dueDate = $request->input('due_date');
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        
        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        $questions = $courseFile ? ($courseFile->assignment_questions[$coTag] ?? []) : [];

        return view('r26.assignment_qp_print', compact('batchSubject', 'classroom', 'questions', 'coTag', 'courseFile'));
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        
        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        $questions = $courseFile ? ($courseFile->assignment_questions[$coTag] ?? []) : [];

        return view('r26.assignment_scheme_print', compact('batchSubject', 'classroom', 'questions', 'coTag', 'courseFile'));
    }

    /**
     * Configure default series exam configurations based on selected mode.
     */
    public function configureSeriesExams(Request $request, $subjectId)
    {
        if ($request->has('reset') || !$request->has('mode')) {
            \DB::table('series_exams')->where('batch_subject_id', $subjectId)->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Configuration reset successfully.']);
        }

        $mode = $request->input('mode'); // 'single_co' or 'combined_co'
        if (!in_array($mode, ['single_co', 'combined_co'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Invalid mode selected.']);
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

        return response()->json(['status' => 'SUCCESS', 'message' => 'Series exams successfully configured!']);
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

                // Save individual exam mark
                \DB::table('academic_marks')->updateOrInsert(
                    [
                        'reg_no' => $regNo,
                        'batch_subject_id' => $subjectId,
                        'category' => 'Series Exam: ' . $exam->exam_name,
                    ],
                    [
                        'subject_code' => $batchSubject->subject_code,
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
                    'max_marks' => 20,
                    'marks_obtained' => $scaledMark,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

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
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
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
                if ($pct >= $targetPercent) {
                    $metTargetCount++;
                }
            } elseif ($gradeVal !== null && $gradeVal !== 'F' && $gradeVal !== 'FE') {
                $appearedCount++;
                $metTargetCount++;
            }

            $studentList[] = [
                'reg_no' => $regNo,
                'name' => $s->name,
                'roll_no' => $s->roll_no,
                'ese_marks' => $markVal,
                'ese_grade' => $gradeVal,
            ];
        }

        $metPercent = $totalStudents > 0 ? round(($metTargetCount / $totalStudents) * 100, 1) : 0.0;
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);

        // Redefined NBA Attainment Levels Rule
        $level = 0;
        if ($metPercent >= $targetStudentPercent) $level = 3;
        elseif ($metPercent >= ($targetStudentPercent - 10)) $level = 2;
        elseif ($metPercent >= ($targetStudentPercent - 20)) $level = 1;

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

        $entryMode = $request->input('entry_mode', 'marks');
        $maxMarks = (float)$request->input('max_marks', 60);
        $eseThresholdGrade = $request->input('ese_threshold_grade', 'D');
        $eseThresholdPercent = (float)$request->input('ese_threshold_percent', 50.0);
        $cieThresholdPercent = (float)$request->input('cie_threshold_percent', 50.0);
        $targetStudentPercent = (float)$request->input('target_student_percent', 70.0);

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
            'level3_percent' => $targetStudentPercent,
            'level2_percent' => max(0, $targetStudentPercent - 10),
            'level1_percent' => max(0, $targetStudentPercent - 20),
        ];

        $courseFile = CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            ['academic_year' => '2026-2027', 'status' => 'Draft']
        );
        $existingSettings = is_string($courseFile->attainment_settings)
            ? json_decode($courseFile->attainment_settings, true) ?: []
            : ($courseFile->attainment_settings ?: []);
        
        $existingSettings['ese_config'] = $eseConfig;
        $courseFile->attainment_settings = json_encode($existingSettings);
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
        $updated = 0;

        foreach ($marks as $regNo => $inputVal) {
            $inputStr = trim((string)$inputVal);
            if ($inputStr === '') continue;

            $numericVal = 0.0;
            $gradeLetter = null;

            if ($entryMode === 'grades' || (is_string($inputVal) && isset($gradeToMarkScale[strtoupper($inputStr)]))) {
                $gradeLetter = strtoupper($inputStr);
                $ratio = $gradeToMarkScale[$gradeLetter] ?? 0.45;
                $numericVal = round($ratio * $maxMarks, 2);
            } else {
                $numericVal = (float)$inputVal;
                $pct = $maxMarks > 0 ? ($numericVal / $maxMarks) * 100 : 0;
                if ($pct >= 90) $gradeLetter = 'S';
                elseif ($pct >= 80) $gradeLetter = 'A';
                elseif ($pct >= 70) $gradeLetter = 'B';
                elseif ($pct >= 60) $gradeLetter = 'C';
                elseif ($pct >= 50) $gradeLetter = 'D';
                elseif ($pct >= 40) $gradeLetter = 'E';
                else $gradeLetter = 'F';
            }

            \DB::table('academic_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'batch_subject_id' => $subjectId,
                    'category' => 'ESE',
                ],
                [
                    'subject_code' => $batchSubject->subject_code,
                    'max_marks' => $maxMarks,
                    'marks_obtained' => $numericVal,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

            \DB::table('student_board_grades')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'subject_code' => $batchSubject->subject_code,
                    'semester' => $batchSubject->semester ?: 1,
                ],
                [
                    'grade' => $gradeLetter,
                    'updated_at' => now(),
                ]
            );

            $updated++;
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "{$updated} students ESE evaluation record updated successfully."
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
            ->orderBy('roll_no', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        
        $settings = [];
        if ($courseFile && $courseFile->attainment_settings) {
            $settings = is_string($courseFile->attainment_settings)
                ? json_decode($courseFile->attainment_settings, true)
                : $courseFile->attainment_settings;
        }

        $eseConfig = $settings['ese_config'] ?? [];
        $cieThreshold = (float)($eseConfig['cie_threshold_percent'] ?? 50.0);
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? $eseConfig['level3_percent'] ?? 70.0);

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

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
            $totalMet = 0;

            foreach ($students as $stud) {
                $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
                $studMarks = $academicMarks->get($regNo, collect());

                $coMarks = $studMarks->where('co_tag', $coTag);
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();

                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valMcq        = $mcqMark ? (float)$mcqMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;

                $cieScore = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                $eseRecord = $studMarks->where('category', 'ESE')->first();
                $eseScore = $eseRecord ? (float)$eseRecord->marks_obtained : 0.0;
                $eseCoScore = $eseScore / 4;

                $totalScore = $cieScore + $eseCoScore;
                $pct = ($totalScore / 30) * 100;
                if ($pct >= $cieThreshold) {
                    $totalMet++;
                }
                $totalAssessed++;
            }

            $directMetPct = $totalAssessed > 0 ? round(($totalMet / $totalAssessed) * 100, 1) : 0.0;

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

            $overallPct = round((0.80 * $directMetPct) + (0.20 * $indirectPct), 1);
            $attained = $overallPct >= $cieThreshold;

            $levelStr = 'Level 0 (Nil)';
            if ($directMetPct >= $targetStudentPercent) $levelStr = 'Level 3 (High)';
            elseif ($directMetPct >= ($targetStudentPercent - 10)) $levelStr = 'Level 2 (Moderate)';
            elseif ($directMetPct >= ($targetStudentPercent - 20)) $levelStr = 'Level 1 (Low)';

            $matrix[] = [
                'co' => $coTag,
                'direct_percent' => $directMetPct,
                'indirect_percent' => $indirectPct,
                'indirect_rating' => round($indirectRating, 2),
                'overall_percent' => $overallPct,
                'target_benchmark' => $targetStudentPercent,
                'attainment_level' => $levelStr,
                'attained' => $attained,
            ];

            $directSum += $directMetPct;
            $indirectSum += $indirectPct;
            $overallSum += $overallPct;
        }

        $avgDirect = $count > 0 ? round($directSum / $count, 1) : 0.0;
        $avgIndirect = $count > 0 ? round($indirectSum / $count, 1) : 0.0;
        $avgOverall = $count > 0 ? round($overallSum / $count, 1) : 0.0;

        $overallLevel = 'Level 0 (Nil)';
        if ($avgDirect >= $targetStudentPercent) $overallLevel = 'Level 3 (High)';
        elseif ($avgDirect >= ($targetStudentPercent - 10)) $overallLevel = 'Level 2 (Moderate)';
        elseif ($avgDirect >= ($targetStudentPercent - 20)) $overallLevel = 'Level 1 (Low)';

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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();

        return view('r26.series_qp_print', compact('batchSubject', 'classroom', 'exam'));
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();

        return view('r26.series_scheme_print', compact('batchSubject', 'classroom', 'exam'));
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();

        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
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

        return view('r26.series_marks_print', compact('batchSubject', 'classroom', 'students', 'studentCiaData', 'seriesExams'));
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) abort(404);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
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

        $studentCiaData = $students->map(function ($student) use ($attendanceData, $academicMarks, $subjectId, $batchSubject, $selfLearningConfigs, $testConfigs, $testAttempts, $submissions, $seriesExams) {
            $studentSubmissions = $submissions->get($student->reg_no, collect());
            $studentAttendance = $attendanceData->get($student->reg_no, collect());
            $totalAttendance = $studentAttendance->count();
            $present = $studentAttendance->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAttendance > 0 ? ($present / $totalAttendance) * 100 : 100.00;
            $attPercentage = round($attPercentage, 2);
            
            // Table 2.1 Attendance Marks Conversion
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
            $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
            $seriesExamMarks = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;

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
                'total_cia' => $attMarks + $selfLearningMarks + $seriesExamMarks
            ];
        });

        return view('r26.internals_cie_print', compact('batchSubject', 'classroom', 'studentCiaData'));
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) abort(404);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
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

        $studentCiaData = $students->map(function ($student) use ($attendanceData, $academicMarks, $subjectId, $batchSubject, $selfLearningConfigs, $testConfigs, $testAttempts, $submissions, $seriesExams) {
            $studentSubmissions = $submissions->get($student->reg_no, collect());
            $studentAttendance = $attendanceData->get($student->reg_no, collect());
            $totalAttendance = $studentAttendance->count();
            $present = $studentAttendance->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAttendance > 0 ? ($present / $totalAttendance) * 100 : 100.00;
            $attPercentage = round($attPercentage, 2);
            
            // Table 2.1 Attendance Marks Conversion
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
            $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
            $seriesExamMarks = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;

            $eseRecord = $studentMarks->where('category', 'ESE')->first();
            $eseMarks = $eseRecord ? (float)$eseRecord->marks_obtained : 0.0;

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
                'total_cia' => $attMarks + $selfLearningMarks + $seriesExamMarks,
                'ese_marks' => $eseMarks
            ];
        });

        return view('r26.student_final_results_print', compact('batchSubject', 'classroom', 'studentCiaData'));
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) abort(404);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) abort(404);

        $copoData = json_decode($courseFile->parsed_copo_data, true) ?: [];
        $mappings = $copoData['mappings'] ?? [];

        // Let's get direct assessment marks
        $academicMarks = \DB::table('academic_marks')
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

        $settings = is_string($courseFile->attainment_settings)
            ? json_decode($courseFile->attainment_settings, true)
            : ($courseFile->attainment_settings ?: []);
        $eseConfig = $settings['ese_config'] ?? [];

        $cieThreshold = (float)($eseConfig['cie_threshold_percent'] ?? 50.0);
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? $eseConfig['level3_percent'] ?? 70.0);
        
        $directStats = [];
        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $totalAssessed = 0;
            $totalMet = 0;
            
            foreach ($students as $stud) {
                $studMarks = $academicMarks->get($stud->reg_no, collect());
                
                $coMarks = $studMarks->where('co_tag', $coTag);
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valMcq        = $mcqMark ? (float)$mcqMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;
                
                $cieScore = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                
                $eseRecord = $studMarks->where('category', 'ESE')->first();
                $eseScore = $eseRecord ? (float)$eseRecord->marks_obtained : 0.0;
                $eseCoScore = $eseScore / 4; 
                
                $totalScore = $cieScore + $eseCoScore; 
                
                $percentage = ($totalScore / 30) * 100;
                if ($percentage >= $cieThreshold) {
                    $totalMet++;
                }
                $totalAssessed++;
            }
            
            $metPercentage = $totalAssessed > 0 ? ($totalMet / $totalAssessed) * 100 : 0.0;
            
            $level = 0;
            if ($metPercentage >= $targetStudentPercent) $level = 3;
            elseif ($metPercentage >= ($targetStudentPercent - 10)) $level = 2;
            elseif ($metPercentage >= ($targetStudentPercent - 20)) $level = 1;
            
            $directStats[$coTag] = [
                'met_percent' => round($metPercentage, 1),
                'level' => $level
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

        return view('r26.attainment_report_print', compact('batchSubject', 'classroom', 'directStats', 'indirectStats', 'combinedStats', 'poAttainments', 'mappings'));
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

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $courseFile = R26CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) {
            abort(404, 'Course file not initialized.');
        }

        $documents = R26CourseFileDocument::where('r26_course_file_id', $courseFile->id)
            ->orderBy('document_number', 'asc')
            ->get();

        try {
            $pdf = \PDF::loadView('r26.course_file_pdf', compact('batchSubject', 'classroom', 'courseFile', 'documents'));
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
}
