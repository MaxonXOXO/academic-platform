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

        // Order subjects logically: Theory first, Practical second, Drawing, Seminar, Project
        $typePriority = [
            'Theory' => 1,
            'Theory / Lecture' => 1,
            'Practical / Lab' => 2,
            'Practical' => 2,
            'Lab' => 2,
            'Drawing' => 3,
            'Seminar' => 4,
            'Project' => 5
        ];

        $subjects = $rawSubjects->sortBy(function ($s) use ($typePriority) {
            $p = $typePriority[$s->subject_type] ?? 9;
            return sprintf('%02d_%s', $p, $s->subject_code);
        })->values();

        $subjectIds = $subjects->pluck('id')->toArray();
        $subjectCodes = $subjects->pluck('subject_code')->filter()->unique()->toArray();

        // 2. Fetch Students for this Classroom
        $students = Student::getClassroomStudentsQuery($classroomId)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no', 'phone', 'guardian_mobile']);

        $studentRegNos = $students->pluck('reg_no')->toArray();

        // 3. Gather Series Exam / Written Test Marks for all subjects & students
        // Query A: academic_marks (Theory, Written Test, Series Test, Summative)
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

        // 4. Gather Attendance Data (aligned with Attendance Consolidated Register)
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

        // 5. Build Student Report Rows with Subject Series Marks & Attendance
        $studentsData = [];
        $totalConductedClasswide = 0;
        $totalAttendedClasswide = 0;

        foreach ($students as $stud) {
            $regNo = $stud->reg_no;
            $stStudAtt = $studentAttGrouped->get($regNo, collect());

            $subjectMarksData = [];
            $studentGrandTotalMarks = 0.0;
            $hasAnySeriesMark = false;
            $studentTotalAttended = 0;
            $studentTotalConducted = 0;

            foreach ($subjects as $subj) {
                $subjId = $subj->id;
                $sCode = $subj->subject_code;

                // --- Calculate Attendance for this subject ---
                $sLogs = $classLogsBySubject->get($subjId, collect());
                $stSubjAttRecords = $stStudAtt->where('subject_code', $sCode);

                $conducted = $sLogs->count();
                if ($conducted == 0) {
                    $conducted = $stSubjAttRecords->count();
                }

                $attended = 0;
                if ($sLogs->isNotEmpty()) {
                    foreach ($sLogs as $log) {
                        $pArr = json_decode($log->present_students ?? '[]', true) ?: [];
                        if (in_array($regNo, $pArr)) {
                            $attended++;
                        }
                    }
                } else {
                    $attended = $stSubjAttRecords->whereIn('status', ['Present', 'Late'])->count();
                }

                $subjAttPct = $conducted > 0 ? round(($attended / $conducted) * 100, 1) : 100.0;
                $studentTotalConducted += $conducted;
                $studentTotalAttended += $attended;

                // --- Resolve Series Exam Marks for CO1, CO2, CO3, CO4 ---
                $coMarks = [
                    'CO1' => null,
                    'CO2' => null,
                    'CO3' => null,
                    'CO4' => null
                ];

                // Check academic_marks
                $stAcademic = $academicMarks->where('reg_no', $regNo)->filter(function($m) use ($subjId, $sCode) {
                    return (isset($m->batch_subject_id) && $m->batch_subject_id == $subjId) || ($m->subject_code == $sCode);
                });

                foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $co) {
                    // Priority 1: Category Written Test / Series Test / Summative
                    $m = $stAcademic->where('co_tag', $co)
                        ->whereIn('category', ['Written Test', 'Series Test', 'Summative', 'Series Exam', 'Test', 'Internal Assessment'])
                        ->first();

                    if (!$m) {
                        // Fallback: any category for this CO
                        $m = $stAcademic->where('co_tag', $co)->first();
                    }

                    if ($m && is_numeric($m->marks_obtained)) {
                        $coMarks[$co] = (float)$m->marks_obtained;
                        $hasAnySeriesMark = true;
                    }
                }

                // Check practical_test_marks if practical subject and CO marks still missing
                $stPractical = $practicalMarks->where('reg_no', $regNo)->where('batch_subject_id', $subjId);
                if ($stPractical->isNotEmpty()) {
                    foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $co) {
                        if ($coMarks[$co] === null) {
                            $pm = $stPractical->where('co_tag', $co)->first();
                            if ($pm && is_numeric($pm->marks_obtained)) {
                                $coMarks[$co] = (float)$pm->marks_obtained;
                                $hasAnySeriesMark = true;
                            }
                        }
                    }
                }

                // Check drawing tests if drawing subject
                $stDraw = $drawingTests->where('reg_no', $regNo)->where('batch_subject_id', $subjId);
                if ($stDraw->isNotEmpty()) {
                    $t1 = $stDraw->where('test_no', 'Test 1')->first();
                    $t2 = $stDraw->where('test_no', 'Test 2')->first();
                    if ($t1 && is_numeric($t1->total_score_100)) {
                        if ($coMarks['CO1'] === null) $coMarks['CO1'] = round($t1->total_score_100 / 2, 1);
                        if ($coMarks['CO2'] === null) $coMarks['CO2'] = round($t1->total_score_100 / 2, 1);
                        $hasAnySeriesMark = true;
                    }
                    if ($t2 && is_numeric($t2->total_score_100)) {
                        if ($coMarks['CO3'] === null) $coMarks['CO3'] = round($t2->total_score_100 / 2, 1);
                        if ($coMarks['CO4'] === null) $coMarks['CO4'] = round($t2->total_score_100 / 2, 1);
                        $hasAnySeriesMark = true;
                    }
                }

                // Subject Total Series Marks
                $subjValidScores = array_filter($coMarks, fn($v) => $v !== null);
                $subjTotal = !empty($subjValidScores) ? array_sum($subjValidScores) : null;
                if ($subjTotal !== null) {
                    $studentGrandTotalMarks += $subjTotal;
                }

                $subjectMarksData[$subjId] = [
                    'subject_id'   => $subjId,
                    'subject_code' => $sCode,
                    'subject_name' => $subj->subject_name,
                    'subject_type' => $subj->subject_type,
                    'co_marks'     => $coMarks,
                    'subject_total'=> $subjTotal !== null ? round($subjTotal, 1) : null,
                    'attendance'   => [
                        'attended'   => $attended,
                        'conducted'  => $conducted,
                        'percentage' => $subjAttPct
                    ]
                ];
            }

            // Total Attendance %
            $overallAttPct = $studentTotalConducted > 0 
                ? round(($studentTotalAttended / $studentTotalConducted) * 100, 1) 
                : 100.0;

            $totalConductedClasswide += $studentTotalConducted;
            $totalAttendedClasswide += $studentTotalAttended;

            // Attendance eligibility status (SBTE Kerala Clause 10)
            if ($overallAttPct >= 75.0) {
                $status = 'Eligible';
                $statusBadge = 'status-eligible';
            } elseif ($overallAttPct >= 65.0) {
                $status = 'Condonation';
                $statusBadge = 'status-condonation';
            } else {
                $status = 'Detained';
                $statusBadge = 'status-detained';
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
                'total_attended'      => $studentTotalAttended,
                'total_conducted'     => $studentTotalConducted,
                'overall_attendance'  => $overallAttPct,
                'status'              => $status,
                'status_badge'        => $statusBadge,
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
                'revision'      => 'REV2021',
                'scheme_name'   => 'Revision 2021 (Diploma Engineering)',
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
    public function printStudentProgressCard(Request $request, $regNo)
    {
        $request->merge(['student' => $regNo, 'mode' => 'card']);
        return $this->printProgressReport($request);
    }
}

