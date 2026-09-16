<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\Student;
use App\Models\StaffProfile;
use App\Models\AcademicMark;
use App\Models\R21MajorProjectCourseFile;
use App\Models\R21MajorProjectEvaluation;
use App\Services\AttainmentService;

class R21VirtualClassroomMajorProjectController extends Controller
{
    /**
     * R2021 Major Project Classroom Dashboard
     * SBTE Kerala Regulation 11.2.5 (CIA 75M) & 11.3.4 (ESE 50M) -> Total 125M (Ratio 3:2)
     */
    public function show($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom not found.');
        }

        // Default COs for Major Project if not set
        $defaultCos = [
            ['id' => 'CO1', 'description' => 'Identify, formulate and analyze complex engineering problems in the chosen domain.', 'cognitive_level' => 'Analyzing'],
            ['id' => 'CO2', 'description' => 'Design and develop solutions, prototypes or modern hardware/software systems.', 'cognitive_level' => 'Applying'],
            ['id' => 'CO3', 'description' => 'Utilize modern engineering tools, techniques and resources for implementation.', 'cognitive_level' => 'Applying'],
            ['id' => 'CO4', 'description' => 'Demonstrate teamwork, project management, ethics and effective technical communication.', 'cognitive_level' => 'Applying'],
            ['id' => 'CO5', 'description' => 'Synthesize results, prepare comprehensive technical documentation and report.', 'cognitive_level' => 'Evaluating']
        ];

        // Find or create course file
        $courseFile = R21MajorProjectCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'course_title' => $batchSubject->subject_name ?: 'Major Project',
                'course_code' => $batchSubject->subject_code ?: '6009',
                'semester' => $classroom->current_semester ?? $batchSubject->semester ?? 'VI',
                'cia_marks' => 75,
                'ese_marks' => 50,
                'credits' => 4.0,
                'parsed_cos' => $defaultCos,
                'project_groups' => [],
                'attainment_settings' => AttainmentService::getDefaultEseConfig('Project', 'REV2021')
            ]
        );

        // Fetch students enrolled
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Fetch existing evaluations
        $evaluations = R21MajorProjectEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Attendance data from student_attendance
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Department Guides
        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $guides = StaffProfile::where(function ($q) use ($deptCode) {
                if (!empty($deptCode)) {
                    $q->where('branch', $deptCode);
                }
            })
            ->whereIn('designation', ['HOD', 'Lecturer', 'Demonstrator', 'Workshop Superintendent', 'Assistant Professor'])
            ->orderBy('name', 'asc')
            ->get(['mobile_no', 'name', 'designation']);

        if ($guides->isEmpty()) {
            $guides = StaffProfile::orderBy('name', 'asc')->get(['mobile_no', 'name', 'designation']);
        }

        $projectGroups = is_array($courseFile->project_groups) ? $courseFile->project_groups : (json_decode($courseFile->project_groups ?? '[]', true) ?: []);

        // Process student results
        $studentResults = $students->map(function ($student) use ($evaluations, $attendanceData, $projectGroups) {
            $regNo = $student->reg_no;
            $eval = $evaluations->get($regNo);

            // Attendance calculation (Max 15 Marks - Clause 11.2.5)
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 1) : 100.0;

            // Suggested Attendance Marks out of 15
            if ($attPercentage >= 90) { $suggestedAttMark = 15.0; }
            elseif ($attPercentage >= 80) { $suggestedAttMark = 12.0; }
            elseif ($attPercentage >= 75) { $suggestedAttMark = 9.0; }
            elseif ($attPercentage >= 70) { $suggestedAttMark = 6.0; }
            elseif ($attPercentage >= 65) { $suggestedAttMark = 3.0; }
            else { $suggestedAttMark = 0.0; }

            // Find assigned group
            $assignedGroup = null;
            foreach ($projectGroups as $grp) {
                if (isset($grp['members']) && in_array($regNo, $grp['members'])) {
                    $assignedGroup = $grp;
                    break;
                }
            }

            $groupId = $eval ? $eval->group_id : ($assignedGroup['id'] ?? null);
            $projectTitle = $eval ? $eval->project_title : ($assignedGroup['title'] ?? null);
            $formative = $eval ? (float)$eval->formative_diary_marks : 0.0;
            $summative = $eval ? (float)$eval->summative_dept_marks : 0.0;
            $attendance = $eval ? (float)$eval->attendance_marks : $suggestedAttMark;
            $totalCia = $eval ? (float)$eval->total_cia_75 : ($formative + $summative + $attendance);

            $esePrototype = $eval ? (float)$eval->ese_prototype : 0.0;
            $eseModernTools = $eval ? (float)$eval->ese_modern_tools : 0.0;
            $esePresentation = $eval ? (float)$eval->ese_presentation : 0.0;
            $eseInnovativeness = $eval ? (float)$eval->ese_innovativeness : 0.0;
            $eseViva = $eval ? (float)$eval->ese_viva : 0.0;
            $eseIndividual = $eval ? (float)$eval->ese_individual_contrib : 0.0;
            $eseGroup = $eval ? (float)$eval->ese_group_activity : 0.0;
            $eseReport = $eval ? (float)$eval->ese_project_report : 0.0;
            $totalEse = $eval ? (float)$eval->total_ese_50 : 0.0;
            $eseGrade = $eval ? $eval->ese_grade : null;
            if (!$eseGrade && $totalEse > 0) {
                $eseGrade = AttainmentService::convertMarksToGrade($totalEse, 50.0);
            }

            $grandTotal = $eval ? (float)$eval->grand_total_125 : ($totalCia + $totalEse);
            $hasEval = ($eval !== null && ($eval->formative_diary_marks > 0 || $eval->summative_dept_marks > 0 || $eval->total_ese_50 > 0));

            $passed = ($totalCia >= 30.0 && $totalEse >= 20.0 && $grandTotal >= 50.0);

            return [
                'reg_no' => $regNo,
                'sbte_reg_no' => $student->sbte_reg_no ?? $regNo,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'group_id' => $groupId,
                'group_name' => $assignedGroup['name'] ?? ($groupId ? "Group $groupId" : 'Unassigned'),
                'project_title' => $projectTitle,
                'guide_name' => $assignedGroup['guide_name'] ?? null,
                'att_percentage' => $attPercentage,
                'suggested_att_mark' => $suggestedAttMark,
                'formative_diary_marks' => $formative,
                'summative_dept_marks' => $summative,
                'attendance_marks' => $attendance,
                'total_cia_75' => $totalCia,
                'ese_prototype' => $esePrototype,
                'ese_modern_tools' => $eseModernTools,
                'ese_presentation' => $esePresentation,
                'ese_innovativeness' => $eseInnovativeness,
                'ese_viva' => $eseViva,
                'ese_individual_contrib' => $eseIndividual,
                'ese_group_activity' => $eseGroup,
                'ese_project_report' => $eseReport,
                'total_ese_50' => $totalEse,
                'ese_grade' => $eseGrade,
                'grand_total_125' => $grandTotal,
                'passed' => $passed,
                'has_eval' => $hasEval,
            ];
        });

        // Statistics
        $totalStudents = $studentResults->count();
        $evaluatedCount = $studentResults->where('has_eval', true)->count();
        $pendingCount = $totalStudents - $evaluatedCount;
        $passedCount = $studentResults->where('passed', true)->count();
        $avgCia = $evaluatedCount > 0 ? round($studentResults->where('has_eval', true)->avg('total_cia_75'), 2) : 0.0;
        $avgEse = $evaluatedCount > 0 ? round($studentResults->where('has_eval', true)->avg('total_ese_50'), 2) : 0.0;

        return view('r21_project.virtual_classroom_project', compact(
            'batchSubject',
            'classroom',
            'courseFile',
            'students',
            'studentResults',
            'guides',
            'projectGroups',
            'totalStudents',
            'evaluatedCount',
            'pendingCount',
            'passedCount',
            'avgCia',
            'avgEse'
        ));
    }

    /**
     * Upload & Save Syllabus PDF for Major Project
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $request->validate([
            'syllabus_file' => 'required|mimes:pdf|max:15360'
        ]);

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $file = $request->file('syllabus_file');
        $filename = 'r21_major_project_syllabus_' . $subjectId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('syllabi', $filename, 'public');

        $courseFile = R21MajorProjectCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $courseFile->syllabus_pdf_path = '/storage/' . $path;
        $courseFile->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Major Project Syllabus PDF uploaded successfully.',
            'path' => $courseFile->syllabus_pdf_path
        ]);
    }

    /**
     * Save Project Groups
     */
    public function saveGroups(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 401);
        }

        $courseFile = R21MajorProjectCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $groups = $request->input('groups', []);

        $courseFile->project_groups = $groups;
        $courseFile->save();

        $assignedRegNos = [];
        // Update evaluations table with group IDs and titles
        foreach ($groups as $grp) {
            $gId = $grp['id'] ?? null;
            $gTitle = $grp['title'] ?? null;
            $members = $grp['members'] ?? [];
            if ($gId && is_array($members)) {
                foreach ($members as $reg) {
                    $assignedRegNos[] = $reg;
                    R21MajorProjectEvaluation::updateOrCreate(
                        ['batch_subject_id' => $subjectId, 'reg_no' => $reg],
                        ['group_id' => $gId, 'project_title' => $gTitle]
                    );
                }
            }
        }

        // Unlink any student evaluations in this subject that are no longer in assigned groups
        R21MajorProjectEvaluation::where('batch_subject_id', $subjectId)
            ->whereNotIn('reg_no', $assignedRegNos)
            ->whereNotNull('group_id')
            ->update(['group_id' => null, 'project_title' => null]);

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Project groups updated successfully.'
        ]);
    }

    /**
     * Save / Upsert Single Student Project Evaluation
     */
    public function saveEvaluation(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 401);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $request->validate([
            'reg_no' => 'required|string',
            'formative_diary_marks' => 'nullable|numeric|min:0|max:30',
            'summative_dept_marks' => 'nullable|numeric|min:0|max:30',
            'attendance_marks' => 'nullable|numeric|min:0|max:15',
            'ese_prototype' => 'nullable|numeric|min:0|max:10',
            'ese_modern_tools' => 'nullable|numeric|min:0|max:5',
            'ese_presentation' => 'nullable|numeric|min:0|max:7.5',
            'ese_innovativeness' => 'nullable|numeric|min:0|max:2.5',
            'ese_viva' => 'nullable|numeric|min:0|max:7.5',
            'ese_individual_contrib' => 'nullable|numeric|min:0|max:7.5',
            'ese_group_activity' => 'nullable|numeric|min:0|max:5',
            'ese_project_report' => 'nullable|numeric|min:0|max:5',
            'total_ese_50' => 'nullable|numeric|min:0|max:50',
            'ese_grade' => 'nullable|string|max:5',
        ]);

        $regNo = $request->input('reg_no');
        $formative = round((float)$request->input('formative_diary_marks', 0), 2);
        $summative = round((float)$request->input('summative_dept_marks', 0), 2);
        $attendance = round((float)$request->input('attendance_marks', 0), 2);
        $totalCia = min(75.0, round($formative + $summative + $attendance, 2));

        // ESE Rubrics
        $proto = (float)$request->input('ese_prototype', 0);
        $tools = (float)$request->input('ese_modern_tools', 0);
        $pres = (float)$request->input('ese_presentation', 0);
        $innov = (float)$request->input('ese_innovativeness', 0);
        $viva = (float)$request->input('ese_viva', 0);
        $indiv = (float)$request->input('ese_individual_contrib', 0);
        $grpAct = (float)$request->input('ese_group_activity', 0);
        $rep = (float)$request->input('ese_project_report', 0);

        $rubricSum = round($proto + $tools + $pres + $innov + $viva + $indiv + $grpAct + $rep, 2);

        $totalEse = $request->filled('total_ese_50') ? (float)$request->input('total_ese_50') : $rubricSum;
        $totalEse = min(50.0, max(0.0, $totalEse));

        // Two-way Grade calculation
        $eseGrade = $request->input('ese_grade');
        if (!empty($eseGrade) && !$request->filled('total_ese_50') && $rubricSum == 0) {
            $totalEse = AttainmentService::convertGradeToMarks(strtoupper(trim($eseGrade)), 50.0);
        } else {
            $eseGrade = AttainmentService::convertMarksToGrade($totalEse, 50.0);
        }

        $grandTotal = round($totalCia + $totalEse, 2);
        $passed = ($totalCia >= 30.0 && $totalEse >= 20.0 && $grandTotal >= 50.0);

        $eval = R21MajorProjectEvaluation::updateOrCreate(
            ['batch_subject_id' => $subjectId, 'reg_no' => $regNo],
            [
                'group_id' => $request->input('group_id'),
                'project_title' => $request->input('project_title'),
                'formative_diary_marks' => $formative,
                'summative_dept_marks' => $summative,
                'attendance_marks' => $attendance,
                'total_cia_75' => $totalCia,
                'ese_prototype' => $proto,
                'ese_modern_tools' => $tools,
                'ese_presentation' => $pres,
                'ese_innovativeness' => $innov,
                'ese_viva' => $viva,
                'ese_individual_contrib' => $indiv,
                'ese_group_activity' => $grpAct,
                'ese_project_report' => $rep,
                'total_ese_50' => $totalEse,
                'ese_grade' => $eseGrade,
                'grand_total_125' => $grandTotal,
                'passed' => $passed,
                'remarks' => $request->input('remarks')
            ]
        );

        // Sync to academic_marks table
        AcademicMark::updateOrCreate(
            [
                'reg_no' => $regNo,
                'subject_code' => $batchSubject->subject_code,
                'category' => 'Major Project CIA',
            ],
            [
                'batch_subject_id' => $subjectId,
                'max_marks' => 75,
                'marks_obtained' => $totalCia,
                'co_tag' => 'CO1',
                'entered_by' => $userId
            ]
        );

        // Sync ESE
        AcademicMark::updateOrCreate(
            [
                'reg_no' => $regNo,
                'subject_code' => $batchSubject->subject_code,
                'category' => 'ESE',
            ],
            [
                'batch_subject_id' => $subjectId,
                'max_marks' => 50,
                'marks_obtained' => $totalEse,
                'co_tag' => 'CO1',
                'entered_by' => $userId
            ]
        );

        // Sync Board Grade
        DB::table('student_board_grades')->updateOrInsert(
            [
                'reg_no' => $regNo,
                'subject_code' => $batchSubject->subject_code,
            ],
            [
                'grade' => $eseGrade,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Project evaluation saved successfully.',
            'data' => [
                'reg_no' => $regNo,
                'total_cia_75' => $totalCia,
                'total_ese_50' => $totalEse,
                'ese_grade' => $eseGrade,
                'grand_total_125' => $grandTotal,
                'passed' => $passed
            ]
        ]);
    }

    /**
     * Get ESE Marks & Board Grades for Dual-Entry
     */
    public function getEseMarks($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21MajorProjectCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        $settings = is_array($courseFile->attainment_settings) ? $courseFile->attainment_settings : (json_decode($courseFile->attainment_settings ?? '[]', true) ?: []);
        $eseConfig = array_merge(AttainmentService::getDefaultEseConfig('Project', 'REV2021'), $settings['ese_config'] ?? []);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $evaluations = R21MajorProjectEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        $boardGrades = DB::table('student_board_grades')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->keyBy('reg_no');

        $maxMarks = (float)($eseConfig['max_marks'] ?? 50.0);
        $targetGrade = $eseConfig['ese_threshold_grade'] ?? 'D';
        $targetPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);

        $studentList = [];
        $totalAppeared = 0;
        $totalMet = 0;

        foreach ($students as $stud) {
            $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
            $ev = $evaluations->get($regNo);
            $bg = $boardGrades->get($regNo);

            $mark = $ev && $ev->total_ese_50 > 0 ? (float)$ev->total_ese_50 : null;
            $grade = $ev && !empty($ev->ese_grade) ? $ev->ese_grade : ($bg ? strtoupper(trim($bg->grade)) : null);

            if ($mark !== null && empty($grade)) {
                $grade = AttainmentService::convertMarksToGrade($mark, $maxMarks);
            } elseif ($grade !== null && $mark === null) {
                $mark = AttainmentService::convertGradeToMarks($grade, $maxMarks);
            }

            $isMet = false;
            if ($mark !== null) {
                $totalAppeared++;
                $isMet = ($mark >= ($maxMarks * 0.40)) && AttainmentService::isGradeMet($grade, $targetGrade);
                if ($isMet) $totalMet++;
            } elseif ($grade !== null && $grade !== 'FE') {
                $totalAppeared++;
                $isMet = AttainmentService::isGradeMet($grade, $targetGrade);
                if ($isMet) $totalMet++;
            }

            $studentList[] = [
                'reg_no' => $regNo,
                'name' => $stud->name,
                'roll_no' => $stud->roll_no,
                'mark' => $mark,
                'grade' => $grade,
                'is_met' => $isMet,
                'group_id' => $ev ? $ev->group_id : null,
                'project_title' => $ev ? $ev->project_title : null
            ];
        }

        $percentageMet = $totalAppeared > 0 ? round(($totalMet / $totalAppeared) * 100, 1) : 0.0;
        $lvl3 = (float)($eseConfig['level3_percent'] ?? $targetPercent);
        $lvl2 = (float)($eseConfig['level2_percent'] ?? max(0, $targetPercent - 10));
        $lvl1 = (float)($eseConfig['level1_percent'] ?? max(0, $targetPercent - 20));
        $attainmentLevel = AttainmentService::calculateBatchLevel($percentageMet, $lvl3, $lvl2, $lvl1);

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'subject_id' => $batchSubject->id,
                'subject_code' => $batchSubject->subject_code,
                'subject_name' => $batchSubject->subject_name,
                'revision' => 'REV2021',
                'max_marks' => $maxMarks,
                'target_grade' => $targetGrade,
                'target_percent' => $targetPercent,
                'total_students' => count($students),
                'appeared' => $totalAppeared,
                'met_count' => $totalMet,
                'percentage_met' => $percentageMet,
                'attainment_level' => $attainmentLevel,
                'ese_config' => $eseConfig,
                'students' => $studentList,
            ]
        ]);
    }

    /**
     * Bulk Update ESE Marks / Grades
     */
    public function bulkUpdateEseMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $marksData = $request->input('marks', []);
        $gradesData = $request->input('grades', []);
        $maxMarks = (float)$request->input('max_marks', 50.0);

        $courseFile = R21MajorProjectCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        if ($request->has('ese_config')) {
            $settings = is_array($courseFile->attainment_settings) ? $courseFile->attainment_settings : (json_decode($courseFile->attainment_settings ?? '[]', true) ?: []);
            $settings['ese_config'] = array_merge(AttainmentService::getDefaultEseConfig('Project', 'REV2021'), $request->input('ese_config'));
            $courseFile->attainment_settings = $settings;
            $courseFile->save();
        }

        $allRegNos = array_unique(array_merge(array_keys($marksData), array_keys($gradesData)));

        foreach ($allRegNos as $regNo) {
            $mark = isset($marksData[$regNo]) && $marksData[$regNo] !== '' && $marksData[$regNo] !== null ? (float)$marksData[$regNo] : null;
            $grade = isset($gradesData[$regNo]) && $gradesData[$regNo] !== '' && $gradesData[$regNo] !== null ? strtoupper(trim($gradesData[$regNo])) : null;

            if ($mark !== null && empty($grade)) {
                $grade = AttainmentService::convertMarksToGrade($mark, $maxMarks);
            } elseif ($grade !== null && $mark === null) {
                $mark = AttainmentService::convertGradeToMarks($grade, $maxMarks);
            }

            if ($mark !== null || $grade !== null) {
                $ev = R21MajorProjectEvaluation::firstOrNew(['batch_subject_id' => $subjectId, 'reg_no' => $regNo]);
                $ev->total_ese_50 = $mark ?? 0.0;
                $ev->ese_grade = $grade;
                $ev->grand_total_125 = round(($ev->total_cia_75 ?? 0.0) + ($mark ?? 0.0), 2);
                $ev->passed = (($ev->total_cia_75 ?? 0.0) >= 30.0 && ($mark ?? 0.0) >= 20.0 && $ev->grand_total_125 >= 50.0);
                $ev->save();

                AcademicMark::updateOrCreate(
                    [
                        'reg_no' => $regNo,
                        'subject_code' => $batchSubject->subject_code,
                        'category' => 'ESE',
                    ],
                    [
                        'batch_subject_id' => $subjectId,
                        'max_marks' => $maxMarks,
                        'marks_obtained' => $mark,
                        'co_tag' => 'CO1',
                        'entered_by' => $userId
                    ]
                );

                if ($grade !== null) {
                    DB::table('student_board_grades')->updateOrInsert(
                        [
                            'reg_no' => $regNo,
                            'subject_code' => $batchSubject->subject_code,
                        ],
                        [
                            'grade' => $grade,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Major Project ESE marks and board grades saved successfully.'
        ]);
    }

    /**
     * Attainment Summary for Major Project
     * Direct Attainment (CIA Academic 60M [Formative 30M + Summative 30M, attendance excluded] + ESE 50M)
     * Indirect Attainment (Course Exit Survey)
     * Overall = 80% Direct + 20% Indirect
     */
    public function getAttainmentSummary($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21MajorProjectCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        $settings = is_array($courseFile->attainment_settings) ? $courseFile->attainment_settings : (json_decode($courseFile->attainment_settings ?? '[]', true) ?: []);
        $eseConfig = array_merge(AttainmentService::getDefaultEseConfig('Project', 'REV2021'), $settings['ese_config'] ?? []);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $evaluations = R21MajorProjectEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $boardGrades = DB::table('student_board_grades')->where('subject_code', $batchSubject->subject_code)->get()->keyBy('reg_no');

        $thresholdGrade = $eseConfig['ese_threshold_grade'] ?? 'D';
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);
        $lvl3 = (float)($eseConfig['level3_percent'] ?? $targetStudentPercent);
        $lvl2 = (float)($eseConfig['level2_percent'] ?? max(0, $targetStudentPercent - 10));
        $lvl1 = (float)($eseConfig['level1_percent'] ?? max(0, $targetStudentPercent - 20));

        // ESE Attainment Level
        $eseAppeared = 0;
        $eseMet = 0;
        foreach ($students as $stud) {
            $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
            $ev = $evaluations->get($regNo);
            $bg = $boardGrades->get($regNo);

            $mark = $ev && $ev->total_ese_50 > 0 ? (float)$ev->total_ese_50 : null;
            $grade = $ev && !empty($ev->ese_grade) ? $ev->ese_grade : ($bg ? strtoupper(trim($bg->grade)) : null);

            if ($mark !== null) {
                $eseAppeared++;
                $pct = ($mark / 50.0) * 100.0;
                $calcGrade = AttainmentService::percentageToGrade($pct);
                if (AttainmentService::isGradeMet($calcGrade, $thresholdGrade) || $pct >= 50.0) {
                    $eseMet++;
                }
            } elseif ($grade !== null && $grade !== 'FE') {
                $eseAppeared++;
                if (AttainmentService::isGradeMet($grade, $thresholdGrade)) {
                    $eseMet++;
                }
            }
        }
        $eseMetPct = $eseAppeared > 0 ? round(($eseMet / $eseAppeared) * 100, 1) : 0.0;
        $eseLevel = AttainmentService::calculateBatchLevel($eseMetPct, $lvl3, $lvl2, $lvl1);

        // Course Exit Survey (Indirect Attainment)
        $exitSurvey = DB::table('course_exit_surveys')->where('batch_subject_id', $subjectId)->first();
        $exitResponses = collect();
        if ($exitSurvey) {
            $exitResponses = DB::table('student_course_exit_responses')->where('exit_survey_id', $exitSurvey->id)->get();
        }

        $cos = is_array($courseFile->parsed_cos) ? $courseFile->parsed_cos : (json_decode($courseFile->parsed_cos ?? '[]', true) ?: []);
        if (empty($cos)) {
            $cos = [
                ['id' => 'CO1', 'description' => 'Identify & analyze problems.'],
                ['id' => 'CO2', 'description' => 'Design & develop prototypes.'],
                ['id' => 'CO3', 'description' => 'Use modern tools.'],
                ['id' => 'CO4', 'description' => 'Teamwork & project management.'],
                ['id' => 'CO5', 'description' => 'Documentation & viva.']
            ];
        }

        $matrix = [];
        $directSum = 0;
        $indirectSum = 0;
        $overallSum = 0;

        foreach ($cos as $co) {
            $coTag = $co['id'] ?? 'CO1';

            // Academic CIA: Formative Diary (30M) + Summative Dept (30M) = 60M (Attendance excluded)
            $totalAssessed = 0;
            $cieMet = 0;
            foreach ($students as $stud) {
                $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
                $ev = $evaluations->get($regNo);
                if ($ev && ($ev->formative_diary_marks > 0 || $ev->summative_dept_marks > 0)) {
                    $totalAssessed++;
                    $academicCiaScore = (float)$ev->formative_diary_marks + (float)$ev->summative_dept_marks; // Max 60
                    if ($academicCiaScore >= (60.0 * 0.50)) { // 50% threshold
                        $cieMet++;
                    }
                }
            }

            $cieMetPct = $totalAssessed > 0 ? round(($cieMet / $totalAssessed) * 100, 1) : 0.0;
            $cieLevel = AttainmentService::calculateBatchLevel($cieMetPct, $lvl3, $lvl2, $lvl1);

            // Direct = 30% CIE + 70% ESE
            $directAttainment = round((0.30 * $cieLevel) + (0.70 * $eseLevel), 2);

            // Indirect from Exit Survey (End Semester Survey - 20% Weightage)
            $indirectLevel = 0.0;
            $indirectPct = 0.0;
            if ($exitResponses->count() > 0) {
                if ($coTag === 'CO1') {
                    $avgScore = ($exitResponses->avg('co1_q1') + $exitResponses->avg('co1_q2')) / 2.0;
                } elseif ($coTag === 'CO2') {
                    $avgScore = ($exitResponses->avg('co2_q3') + $exitResponses->avg('co2_q4')) / 2.0;
                } elseif ($coTag === 'CO3') {
                    $avgScore = ($exitResponses->avg('co3_q5') + $exitResponses->avg('co3_q6')) / 2.0;
                } elseif ($coTag === 'CO4') {
                    $avgScore = ($exitResponses->avg('co4_q7') + $exitResponses->avg('co4_q8')) / 2.0;
                } else {
                    $avgScore = ($exitResponses->avg('co4_q9') + $exitResponses->avg('co_overall_q10')) / 2.0;
                }
                $indirectLevel = round($avgScore, 2);
                $indirectPct = round(($avgScore / 3.0) * 100, 1);
            } else {
                $indirectLevel = $directAttainment > 0 ? round($directAttainment * 0.9, 2) : 2.5;
                $indirectPct = round(($indirectLevel / 3.0) * 100, 1);
            }

            // Overall = 80% Direct + 20% Indirect
            $overallAttainment = round((0.80 * $directAttainment) + (0.20 * $indirectLevel), 2);
            $nbaRating = AttainmentService::getLevelLabel((int)round($overallAttainment));

            $matrix[] = [
                'co_tag' => $coTag,
                'description' => $co['description'] ?? '',
                'cie_assessed' => $totalAssessed,
                'cie_met_pct' => $cieMetPct,
                'cie_level' => $cieLevel,
                'ese_level' => $eseLevel,
                'direct_attainment' => $directAttainment,
                'indirect_attainment' => $indirectLevel,
                'indirect_pct' => $indirectPct,
                'overall_attainment' => $overallAttainment,
                'rating' => $nbaRating
            ];

            $directSum += $directAttainment;
            $indirectSum += $indirectLevel;
            $overallSum += $overallAttainment;
        }

        $numCos = count($matrix);
        $avgDirect = $numCos > 0 ? round($directSum / $numCos, 2) : 0.0;
        $avgIndirect = $numCos > 0 ? round($indirectSum / $numCos, 2) : 0.0;
        $avgOverall = $numCos > 0 ? round($overallSum / $numCos, 2) : 0.0;

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'subject_id' => $batchSubject->id,
                'subject_code' => $batchSubject->subject_code,
                'subject_name' => $batchSubject->subject_name,
                'revision' => 'REV2021',
                'subject_type' => 'Project',
                'matrix' => $matrix,
                'average_direct' => $avgDirect,
                'average_indirect' => $avgIndirect,
                'average_overall' => $avgOverall,
                'ese_config' => $eseConfig,
                'survey' => [
                    'id' => $exitSurvey->id ?? null,
                    'status' => $exitSurvey->status ?? 'Not Initiated',
                    'responded_count' => $exitResponses->count(),
                    'total_students' => count($students),
                    'student_url' => $exitSurvey ? url("/student/course-exit/{$exitSurvey->id}") : null,
                    'report_url' => url("/classroom/{$subjectId}/course-exit/report"),
                    'has_responses' => $exitResponses->count() > 0
                ]
            ]
        ]);
    }

    /**
     * Print Consolidated Major Project Evaluation Register
     */
    /**
     * Print Consolidated & Group-Wise Major Project Evaluation Register (Clauses 11.2.5 & 11.3.4)
     */
    public function printReport($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $courseFile = R21MajorProjectCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $evaluations = R21MajorProjectEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $projectGroups = is_array($courseFile->project_groups) ? $courseFile->project_groups : (json_decode($courseFile->project_groups ?? '[]', true) ?: []);

        // Attendance data from student_attendance & class_logs_attendance
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        $classLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subjectId)
            ->get();
        $totalLogsCount = $classLogs->count();

        // Process student records with full split-ups
        $processedStudents = $students->map(function ($student) use ($evaluations, $projectGroups, $attendanceData, $classLogs, $totalLogsCount) {
            $regNo = $student->reg_no;
            $ev = $evaluations->get($regNo);

            // Attendance split-up
            $stAtt = $attendanceData->get($regNo, collect());
            if ($totalLogsCount > 0) {
                $conducted = $totalLogsCount;
                $attended = 0;
                foreach ($classLogs as $log) {
                    $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                    if (in_array($regNo, $pList)) $attended++;
                }
            } else {
                $conducted = $stAtt->count();
                $attended = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            }
            $attPercentage = $conducted > 0 ? round(($attended / $conducted) * 100, 1) : 100.0;

            // Suggested Attendance Marks (Max 15M, Clause 11.2.5)
            if ($attPercentage >= 90) { $calcAttMark = 15.0; }
            elseif ($attPercentage >= 80) { $calcAttMark = 12.0; }
            elseif ($attPercentage >= 75) { $calcAttMark = 9.0; }
            elseif ($attPercentage >= 70) { $calcAttMark = 6.0; }
            elseif ($attPercentage >= 65) { $calcAttMark = 3.0; }
            else { $calcAttMark = 0.0; }

            $attendanceMark = ($ev && $ev->attendance_marks > 0) ? (float)$ev->attendance_marks : $calcAttMark;

            // Group association
            $assignedGroup = null;
            foreach ($projectGroups as $grp) {
                if (isset($grp['members']) && in_array($regNo, $grp['members'])) {
                    $assignedGroup = $grp;
                    break;
                }
            }

            $groupId = $ev ? $ev->group_id : ($assignedGroup['id'] ?? null);
            $groupName = $assignedGroup['name'] ?? ($groupId ? "Group {$groupId}" : 'Unassigned');
            $projectTitle = $ev ? $ev->project_title : ($assignedGroup['title'] ?? '-');
            $guideName = $assignedGroup['guide_name'] ?? '-';

            // CIA Split-up
            $diaryMark = $ev ? (float)$ev->formative_diary_marks : 0.0;
            $deptMark = $ev ? (float)$ev->summative_dept_marks : 0.0;
            $totalCia = min(75.0, round($diaryMark + $deptMark + $attendanceMark, 2));

            // ESE Rubrics Split-up
            $proto = $ev ? (float)$ev->ese_prototype : 0.0;
            $tools = $ev ? (float)$ev->ese_modern_tools : 0.0;
            $pres = $ev ? (float)$ev->ese_presentation : 0.0;
            $innov = $ev ? (float)$ev->ese_innovativeness : 0.0;
            $viva = $ev ? (float)$ev->ese_viva : 0.0;
            $indiv = $ev ? (float)$ev->ese_individual_contrib : 0.0;
            $grpAct = $ev ? (float)$ev->ese_group_activity : 0.0;
            $rep = $ev ? (float)$ev->ese_project_report : 0.0;
            $totalEse = $ev ? (float)$ev->total_ese_50 : 0.0;
            $eseGrade = $ev ? $ev->ese_grade : null;
            if (!$eseGrade && $totalEse > 0) {
                $eseGrade = AttainmentService::convertMarksToGrade($totalEse, 50.0);
            }

            $hasEse = ($totalEse > 0 || !empty($eseGrade));
            $grandTotal = round($totalCia + $totalEse, 2);
            $hasEval = ($diaryMark > 0 || $deptMark > 0 || $totalEse > 0);
            $passed = ($totalCia >= 30.0 && $totalEse >= 20.0 && $grandTotal >= 50.0);

            return [
                'roll_no' => $student->roll_no,
                'reg_no' => $regNo,
                'sbte_reg_no' => $student->sbte_reg_no ?? $regNo,
                'name' => $student->name,
                'group_id' => $groupId,
                'group_name' => $groupName,
                'project_title' => $projectTitle,
                'guide_name' => $guideName,
                'conducted' => $conducted,
                'attended' => $attended,
                'att_percentage' => $attPercentage,
                'attendance_marks' => $attendanceMark,
                'formative_diary' => $diaryMark,
                'summative_dept' => $deptMark,
                'total_cia_75' => $totalCia,
                'has_ese' => $hasEse,
                'ese_prototype' => $proto,
                'ese_modern_tools' => $tools,
                'ese_presentation' => $pres,
                'ese_innovativeness' => $innov,
                'ese_viva' => $viva,
                'ese_individual_contrib' => $indiv,
                'ese_group_activity' => $grpAct,
                'ese_project_report' => $rep,
                'total_ese_50' => $totalEse,
                'ese_grade' => $eseGrade ?: '-',
                'grand_total_125' => $grandTotal,
                'has_eval' => $hasEval,
                'passed' => $passed,
                'result' => $hasEval ? ($passed ? 'Passed' : 'Failed') : 'Pending'
            ];
        });

        // Organize students into Project Groups
        $groupedProjects = [];
        $assignedRegNos = [];

        foreach ($projectGroups as $grp) {
            $gId = $grp['id'] ?? null;
            $gName = $grp['name'] ?? "Group {$gId}";
            $gTitle = $grp['title'] ?? '-';
            $gGuide = $grp['guide_name'] ?? '-';
            $members = $grp['members'] ?? [];

            $grpStudents = $processedStudents->filter(function ($s) use ($members, $gId) {
                return in_array($s['reg_no'], $members) || ($gId && $s['group_id'] == $gId);
            })->values();

            foreach ($grpStudents as $s) {
                $assignedRegNos[] = $s['reg_no'];
            }

            $evalCount = $grpStudents->where('has_eval', true)->count();
            $avgCia = $evalCount > 0 ? round($grpStudents->where('has_eval', true)->avg('total_cia_75'), 1) : 0.0;
            $avgEse = $evalCount > 0 ? round($grpStudents->where('has_eval', true)->avg('total_ese_50'), 1) : 0.0;
            $avgTotal = $evalCount > 0 ? round($grpStudents->where('has_eval', true)->avg('grand_total_125'), 1) : 0.0;

            $groupedProjects[] = [
                'id' => $gId,
                'name' => $gName,
                'title' => $gTitle,
                'guide_name' => $gGuide,
                'students' => $grpStudents,
                'total_students' => $grpStudents->count(),
                'avg_cia' => $avgCia,
                'avg_ese' => $avgEse,
                'avg_total' => $avgTotal
            ];
        }

        // Catch any students not assigned to any group
        $unassignedStudents = $processedStudents->filter(function ($s) use ($assignedRegNos) {
            return !in_array($s['reg_no'], $assignedRegNos);
        })->values();

        if ($unassignedStudents->isNotEmpty()) {
            $evalCount = $unassignedStudents->where('has_eval', true)->count();
            $groupedProjects[] = [
                'id' => 'unassigned',
                'name' => 'Unassigned Students',
                'title' => 'Pending Topic Allocation',
                'guide_name' => '-',
                'students' => $unassignedStudents,
                'total_students' => $unassignedStudents->count(),
                'avg_cia' => $evalCount > 0 ? round($unassignedStudents->where('has_eval', true)->avg('total_cia_75'), 1) : 0.0,
                'avg_ese' => $evalCount > 0 ? round($unassignedStudents->where('has_eval', true)->avg('total_ese_50'), 1) : 0.0,
                'avg_total' => $evalCount > 0 ? round($unassignedStudents->where('has_eval', true)->avg('grand_total_125'), 1) : 0.0
            ];
        }

        // Attainment Summary Data for Report
        $attainmentResponse = $this->getAttainmentSummary($subjectId);
        $attainmentData = $attainmentResponse->getData(true)['data'] ?? [];

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        return view('r21_project.project_report_print', [
            'subject' => $batchSubject,
            'classroom' => $classroom,
            'department' => $deptCode,
            'groupedProjects' => $groupedProjects,
            'students' => $processedStudents,
            'attainmentSummary' => $attainmentData,
            'totalStudents' => $processedStudents->count(),
            'currentYear' => date('Y')
        ]);
    }
}

