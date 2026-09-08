<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\SubjectStaffAssignment;
use App\Models\Student;
use App\Models\LessonPlan;

class AttendanceController extends Controller
{
    /**
     * Render the standalone attendance log page.
     */
    public function viewPage()
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        return view('attendance_log');
    }

    /**
     * Get list of active subjects/batches for the logged-in staff member.
     * Filtered to show only assigned subjects for active classes.
     */
    public function getActiveSubjects(Request $request)
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $userBranch = Session::get('userBranch');
        $assignedIds = SubjectStaffAssignment::where('staff_mobile_no', $staffMobile)->pluck('batch_subject_id');

        if ($assignedIds->isNotEmpty()) {
            $querySubjects = BatchSubject::whereIn('id', $assignedIds);
        } elseif (in_array($role, ['HOD', 'Workshop Superintendent', 'Principal'])) {
            // Fallback for HOD/Principal without explicit subject assignments: fetch subjects in branch/all
            $querySubjects = BatchSubject::query();
            if ($userBranch && $role === 'HOD') {
                $querySubjects->where('classroom_id', 'LIKE', strtoupper($userBranch) . '%');
            }
        } else {
            $querySubjects = BatchSubject::whereRaw('1 = 0');
        }

        $allSubjects = $querySubjects->orderBy('classroom_id', 'asc')
            ->orderBy('semester', 'asc')
            ->get();

        // Filter to keep ONLY active classes (where batch current_semester <= 6 and subject semester >= batch current_semester)
        $activeSubjects = $allSubjects->filter(function ($subj) {
            $batch = \App\Models\ClassManagement::where('classroom_id', $subj->classroom_id)->first();
            if (!$batch) {
                $batch = \App\Models\R26ClassManagement::where('classroom_id', $subj->classroom_id)->first();
            }
            if (!$batch) return false;

            $currentSem = (int) $batch->current_semester;
            $subjectSem = (int) $subj->semester;

            // Must be an active batch (current_semester <= 6) AND subject semester must match current active semester
            return $currentSem <= 6 && $subjectSem >= $currentSem;
        })->values();

        return response()->json([
            'status' => 'SUCCESS',
            'subjects' => $activeSubjects
        ]);
    }

    /**
     * Get students and lesson plans for a specific subject/batch.
     */
    public function getSubjectDetails($id)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($id);

        // Fetch students ordered by roll number, then name
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->where(function($q) {
                $q->where('status', 'Approved')->orWhere('status', 'Active');
            })
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        // Fetch pending/in-progress lesson plans for dropdown selection
        $lessonPlans = LessonPlan::where('batch_subject_id', $id)
            ->orderBy('id', 'asc')
            ->get(['id', 'topic_content', 'co_id', 'status']);

        $lastLogCount = DB::table('class_logs_attendance')->where('batch_subject_id', $id)->count();
        $hasLessonPlans = LessonPlan::where('batch_subject_id', $id)->exists();

        // Fetch practical experiments if practical/lab subject
        $practicalExperiments = \App\Models\PracticalExperiment::where('batch_subject_id', $id)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED), experiment_no ASC')
            ->get(['id', 'experiment_no', 'title', 'co_tag', 'conducted_date']);

        $nextLogSlNo = ($lastLogCount > 0 || $hasLessonPlans || $practicalExperiments->isNotEmpty()) ? ($lastLogCount + 1) : 0;

        return response()->json([
            'status' => 'SUCCESS',
            'students' => $students,
            'lesson_plans' => $lessonPlans,
            'experiments' => $practicalExperiments,
            'classroom_id' => $batchSubject->classroom_id,
            'subject_type' => $batchSubject->subject_type,
            'syllabus_revision_code' => $batchSubject->syllabus_revision_code,
            'last_log_sl_no' => $lastLogCount,
            'next_log_sl_no' => $nextLogSlNo
        ]);
    }

    /**
     * Check if attendance has already been recorded for a session/slot.
     */
    public function checkSessionAttendance(Request $request)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubjectId = $request->input('batch_subject_id');
        $date = $request->input('date');
        $periodsParam = $request->input('periods');
        $subBatch = $request->input('sub_batch', 'Whole');

        if (!$batchSubjectId || !$date) {
            return response()->json(['status' => 'ERROR', 'message' => 'Missing required parameters.'], 400);
        }

        $periods = [];
        if (is_array($periodsParam)) {
            $periods = array_map('intval', $periodsParam);
        } elseif (!empty($periodsParam)) {
            $periods = array_map('intval', explode(',', $periodsParam));
        }

        $query = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->where('date', $date)
            ->where('sub_batch', $subBatch);

        if (!empty($periods)) {
            $query->whereIn('period', $periods);
        }

        $existingLogs = $query->orderBy('id', 'asc')->get();

        if ($existingLogs->isEmpty()) {
            return response()->json([
                'status' => 'SUCCESS',
                'exists' => false,
                'has_existing' => false,
                'present_students' => [],
                'absent_students' => [],
                'message' => 'No prior attendance recorded for this session.'
            ]);
        }

        // Find the first log with present or absent students populated
        $attLog = $existingLogs->first(function ($l) {
            $p = json_decode($l->present_students ?? '[]', true);
            $a = json_decode($l->absent_students ?? '[]', true);
            return (!empty($p) || !empty($a));
        }) ?: $existingLogs->first();

        $presentStudents = json_decode($attLog->present_students ?? '[]', true) ?: [];
        $absentStudents = json_decode($attLog->absent_students ?? '[]', true) ?: [];
        $topics = $existingLogs->pluck('topics_covered')->filter()->unique()->values()->all();

        return response()->json([
            'status' => 'SUCCESS',
            'exists' => true,
            'has_existing' => true,
            'present_students' => $presentStudents,
            'absent_students' => $absentStudents,
            'existing_topics' => $topics,
            'entries_count' => $existingLogs->count(),
            'message' => 'Attendance for this timetable session is already recorded.'
        ]);
    }

    /**
     * Save the Class Log and Attendance data.
     * Supports multiple experiments on the same date/slot sharing session attendance without multiplying hours.
     */
    public function saveAttendance(Request $request)
    {
        $role = Session::get('userRole');
        $recordedBy = Session::get('userId');
        if (!$role || $role === 'Student' || !$recordedBy) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'batch_subject_id' => 'required|exists:batch_subjects,id',
            'date' => 'required|date',
            'periods' => 'required|array|min:1',
            'periods.*' => 'integer|min:1|max:7',
            'lesson_plan_id' => 'nullable|integer',
            'practical_experiment_id' => 'nullable|integer',
            'topics_covered' => 'required|string',
            'present_students' => 'nullable|array',
            'absent_students' => 'nullable|array',
            'sub_batch' => 'nullable|string|in:Whole,1,2',
            'log_id' => 'nullable|integer',
            'log_ids' => 'nullable',
            'is_additional_log' => 'nullable|boolean',
        ]);

        $subBatch = $request->input('sub_batch', 'Whole');
        $submittedTopics = trim($request->topics_covered);
        $isAdditionalLog = $request->boolean('is_additional_log');
        $explicitLogId = $request->input('log_id');
        $explicitLogIds = $request->input('log_ids');
        if (!empty($explicitLogIds) && !is_array($explicitLogIds)) {
            $explicitLogIds = array_map('intval', explode(',', (string)$explicitLogIds));
        } elseif (!empty($explicitLogIds) && is_array($explicitLogIds)) {
            $explicitLogIds = array_map('intval', $explicitLogIds);
        } else {
            $explicitLogIds = [];
        }
        if ($explicitLogId && empty($explicitLogIds)) {
            $explicitLogIds = [(int)$explicitLogId];
        }

        // Look for existing session attendance if not explicitly supplied
        $presentStudents = $request->present_students;
        $absentStudents = $request->absent_students;

        if (empty($presentStudents) && empty($absentStudents)) {
            $existingSessionLog = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $request->batch_subject_id)
                ->where('date', $request->date)
                ->whereIn('period', $request->periods)
                ->where('sub_batch', $subBatch)
                ->whereNotNull('present_students')
                ->first();

            if ($existingSessionLog) {
                $presentStudents = json_decode($existingSessionLog->present_students, true) ?: [];
                $absentStudents = json_decode($existingSessionLog->absent_students, true) ?: [];
            }
        }

        DB::transaction(function () use ($request, $recordedBy, $subBatch, $submittedTopics, $isAdditionalLog, $explicitLogIds, $presentStudents, $absentStudents) {
            if (!empty($explicitLogIds)) {
                // Bulk update the specific log IDs being edited
                DB::table('class_logs_attendance')
                    ->whereIn('id', $explicitLogIds)
                    ->update([
                        'date' => $request->date,
                        'sub_batch' => $subBatch,
                        'lesson_plan_id' => $request->lesson_plan_id,
                        'topics_covered' => $submittedTopics,
                        'present_students' => json_encode($presentStudents ?? []),
                        'absent_students' => json_encode($absentStudents ?? []),
                        'recorded_by' => $recordedBy,
                        'updated_at' => now(),
                    ]);

                // Ensure all requested periods exist in database
                $existingLoggedPeriods = DB::table('class_logs_attendance')
                    ->whereIn('id', $explicitLogIds)
                    ->pluck('period')
                    ->toArray();

                foreach ($request->periods as $period) {
                    if (!in_array($period, $existingLoggedPeriods)) {
                        DB::table('class_logs_attendance')->insert([
                            'batch_subject_id' => $request->batch_subject_id,
                            'date' => $request->date,
                            'period' => $period,
                            'lesson_plan_id' => $request->lesson_plan_id,
                            'topics_covered' => $submittedTopics,
                            'present_students' => json_encode($presentStudents ?? []),
                            'absent_students' => json_encode($absentStudents ?? []),
                            'sub_batch' => $subBatch,
                            'recorded_by' => $recordedBy,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                return;
            }

            foreach ($request->periods as $period) {

                // Check for existing entries on this date, period, and batch
                $existingLogs = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $request->batch_subject_id)
                    ->where('date', $request->date)
                    ->where('period', $period)
                    ->where('sub_batch', $subBatch)
                    ->get();

                // Check if any existing log has the matching topic, lesson plan, or experiment
                $matchingLog = $existingLogs->first(function ($l) use ($request, $submittedTopics) {
                    if ($request->lesson_plan_id && $l->lesson_plan_id == $request->lesson_plan_id) return true;
                    if (strcasecmp(trim($l->topics_covered ?? ''), $submittedTopics) === 0) return true;
                    if ($request->practical_experiment_id) {
                        $pExp = \App\Models\PracticalExperiment::find($request->practical_experiment_id);
                        if ($pExp && !empty($pExp->experiment_no) && preg_match('/\b(?:Exp|Experiment|Ex)\.?\s*#?\s*0*' . preg_quote($pExp->experiment_no, '/') . '\b/i', $l->topics_covered ?? '')) {
                            return true;
                        }
                    }
                    return false;
                });

                // If not matched by exact topic/ID, but there is already a single log for this period slot and it's not an additional log, update that slot log
                if (!$matchingLog && !$isAdditionalLog && $existingLogs->count() === 1) {
                    $matchingLog = $existingLogs->first();
                }

                if ($matchingLog && !$isAdditionalLog) {
                    // Update the matching log
                    DB::table('class_logs_attendance')
                        ->where('id', $matchingLog->id)
                        ->update([
                            'lesson_plan_id' => $request->lesson_plan_id,
                            'topics_covered' => $submittedTopics,
                            'present_students' => json_encode($presentStudents ?? []),
                            'absent_students' => json_encode($absentStudents ?? []),
                            'recorded_by' => $recordedBy,
                            'updated_at' => now(),
                        ]);
                } else {
                    // Additional experiment on the same date or brand new slot entry -> insert new log
                    DB::table('class_logs_attendance')->insert([
                        'batch_subject_id' => $request->batch_subject_id,
                        'date' => $request->date,
                        'period' => $period,
                        'lesson_plan_id' => $request->lesson_plan_id,
                        'topics_covered' => $submittedTopics,
                        'present_students' => json_encode($presentStudents ?? []),
                        'absent_students' => json_encode($absentStudents ?? []),
                        'sub_batch' => $subBatch,
                        'recorded_by' => $recordedBy,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        // If a lesson plan was selected or topic matches, update actual_date and set status to Completed
        if ($request->lesson_plan_id) {
            $lp = LessonPlan::find($request->lesson_plan_id);
            if ($lp) {
                $lp->status = 'Completed';
                $lp->actual_date = $request->date;
                $lp->save();
            }
        } else if (!empty($submittedTopics)) {
            $lp = LessonPlan::where('batch_subject_id', $request->batch_subject_id)
                ->where('topic_content', $submittedTopics)
                ->first();
            if ($lp) {
                $lp->status = 'Completed';
                $lp->actual_date = $request->date;
                $lp->save();
            }
        }

        // Also sync practical_experiments conducted_date and student_attendance if this is a practical course
        try {
            $batchSubject = \App\Models\BatchSubject::find($request->batch_subject_id);
            $matchedExp = null;

            if ($request->practical_experiment_id) {
                $pExp = \App\Models\PracticalExperiment::find($request->practical_experiment_id);
                if ($pExp) {
                    $pExp->conducted_date = $request->date;
                    $pExp->save();
                    $matchedExp = $pExp;
                }
            }

            $practicalExps = \App\Models\PracticalExperiment::where('batch_subject_id', $request->batch_subject_id)->get();
            foreach ($practicalExps as $pExp) {
                $matched = false;
                if (!empty($pExp->title) && stripos($submittedTopics, trim($pExp->title)) !== false) {
                    $matched = true;
                } elseif (!empty($submittedTopics) && stripos(trim($pExp->title), $submittedTopics) !== false) {
                    $matched = true;
                } elseif (preg_match('/\b(?:Exp|Experiment|Ex)\.?\s*#?\s*0*' . preg_quote($pExp->experiment_no, '/') . '\b/i', $submittedTopics)) {
                    $matched = true;
                }

                if ($matched) {
                    $pExp->conducted_date = $request->date;
                    $pExp->save();
                    if (!$matchedExp) {
                        $matchedExp = $pExp;
                    }
                }
            }

            // Sync student_attendance table for all present and absent students
            if (\Schema::hasTable('student_attendance') && $batchSubject) {
                if (!empty($presentStudents)) {
                    foreach ($presentStudents as $rNo) {
                        \DB::table('student_attendance')->updateOrInsert(
                            [
                                'reg_no' => $rNo,
                                'subject_code' => $batchSubject->subject_code,
                                'date' => $request->date,
                            ],
                            [
                                'status' => 'Present',
                                'sub_batch' => $subBatch,
                                'lesson_plan_id' => $request->lesson_plan_id,
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
                if (!empty($absentStudents)) {
                    foreach ($absentStudents as $rNo) {
                        \DB::table('student_attendance')->updateOrInsert(
                            [
                                'reg_no' => $rNo,
                                'subject_code' => $batchSubject->subject_code,
                                'date' => $request->date,
                            ],
                            [
                                'status' => 'Absent',
                                'sub_batch' => $subBatch,
                                'lesson_plan_id' => $request->lesson_plan_id,
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }

            // If matched practical experiment, also sync practical_experiment_marks evaluation_date for present students
            if ($matchedExp && !empty($presentStudents)) {
                \App\Models\PracticalExperimentMark::where('practical_experiment_id', $matchedExp->id)
                    ->whereIn('reg_no', $presentStudents)
                    ->whereNull('evaluation_date')
                    ->update([
                        'evaluation_date' => $request->date,
                        'updated_at' => now(),
                    ]);
            }
        } catch (\Exception $ex) {
            \Log::warning("PracticalExperiment / student_attendance sync notice: " . $ex->getMessage());
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Class log and attendance recorded successfully!'
        ]);
    }

    /**
     * Get tutor class students list to assign roll numbers.
     */
    public function getTutorStudents(?Request $request = null)
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$staffMobile || !in_array($role, ['Tutor', 'HOD', 'Lecturer', 'Demonstrator', 'Workshop Superintendent'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $staff = \App\Models\StaffProfile::where('mobile_no', $staffMobile)
            ->orWhere('email', $staffMobile)
            ->orWhere('id', $staffMobile)
            ->first();
        if ($staff && $staff->mobile_no) {
            $staffMobile = $staff->mobile_no;
        }

        $cleanMobile = preg_replace('/[^0-9]/', '', $staffMobile);

        $classes1 = DB::table('class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
            $q->where('tutor_mobile_no', $staffMobile)
              ->orWhere('mentor_mobile_no', $staffMobile);
            if ($cleanMobile) {
                $q->orWhere('tutor_mobile_no', $cleanMobile)
                  ->orWhere('mentor_mobile_no', $cleanMobile);
            }
        })->get();

        $classes2 = DB::table('r26_class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
            $q->where('tutor_mobile_no', $staffMobile)
              ->orWhere('mentor_mobile_no', $staffMobile);
            if ($cleanMobile) {
                $q->orWhere('tutor_mobile_no', $cleanMobile)
                  ->orWhere('mentor_mobile_no', $cleanMobile);
            }
        })->get();

        $allClasses = $classes1->concat($classes2);

        if ($allClasses->isEmpty()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No classroom assigned as advisor/tutor/mentor to your profile.'
            ]);
        }

        $requestedClassId = request('classroom_id') ?? request('classroom');
        $classroom = null;
        if ($requestedClassId) {
            $classroom = $allClasses->firstWhere('classroom_id', $requestedClassId);
        }
        if (!$classroom) {
            $classroom = $allClasses->first();
        }

        $students = Student::getClassroomStudentsQuery($classroom->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no']);

        return response()->json([
            'status' => 'SUCCESS',
            'classroom_id' => $classroom->classroom_id,
            'students' => $students
        ]);
    }

    /**
     * Update student roll numbers in bulk.
     */
    public function updateRollNumbers(Request $request)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Tutor', 'HOD', 'Lecturer', 'Workshop Superintendent'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'roll_numbers' => 'required|array',
            'roll_numbers.*.reg_no' => 'required|exists:students,reg_no',
            'roll_numbers.*.roll_no' => 'nullable|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->roll_numbers as $item) {
                Student::where('reg_no', $item['reg_no'])->update([
                    'roll_no' => $item['roll_no'] ?: null
                ]);
            }
        });

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Student roll numbers updated successfully!'
        ]);
    }

    /**
     * Get attendance reports (logs and matrix) for a specific subject.
     */
    public function getReports($batchSubjectId)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        // 1. Fetch Class Attendance Logs in chronological order with staff profile join
        $rawLogs = DB::table('class_logs_attendance')
            ->leftJoin('staff_profiles', 'class_logs_attendance.recorded_by', '=', 'staff_profiles.mobile_no')
            ->where('class_logs_attendance.batch_subject_id', $batchSubjectId)
            ->select(
                'class_logs_attendance.*',
                'staff_profiles.name as staff_name',
                'staff_profiles.mobile_no as staff_mobile'
            )
            ->orderBy('class_logs_attendance.date', 'asc')
            ->orderBy('class_logs_attendance.period', 'asc')
            ->orderBy('class_logs_attendance.id', 'asc')
            ->get();

        // Group continuous per-period logs into consolidated session entries (by date, sub_batch, topics_covered, lesson_plan_id)
        $groupedSessions = [];
        foreach ($rawLogs as $raw) {
            $key = $raw->date . '__' . ($raw->sub_batch ?? 'Whole') . '__' . trim($raw->topics_covered ?? '') . '__' . ($raw->lesson_plan_id ?? '0');
            if (!isset($groupedSessions[$key])) {
                $groupedSessions[$key] = [
                    'id' => $raw->id,
                    'log_ids' => [$raw->id],
                    'date' => $raw->date,
                    'period' => (string)$raw->period,
                    'periods' => [(int)$raw->period],
                    'sub_batch' => $raw->sub_batch ?? 'Whole',
                    'lesson_plan_id' => $raw->lesson_plan_id,
                    'topics_covered' => $raw->topics_covered,
                    'present_students' => $raw->present_students,
                    'absent_students' => $raw->absent_students,
                    'present_count' => count(json_decode($raw->present_students ?? '[]', true) ?: []),
                    'absent_count' => count(json_decode($raw->absent_students ?? '[]', true) ?: []),
                    'recorded_by' => $raw->recorded_by,
                    'staff_name' => $raw->staff_name ?: ($raw->recorded_by ?: 'Faculty'),
                    'created_at' => $raw->created_at,
                    'updated_at' => $raw->updated_at,
                ];
            } else {
                $groupedSessions[$key]['log_ids'][] = $raw->id;
                if (!in_array((int)$raw->period, $groupedSessions[$key]['periods'])) {
                    $groupedSessions[$key]['periods'][] = (int)$raw->period;
                    sort($groupedSessions[$key]['periods']);
                }
                $pCount = count(json_decode($raw->present_students ?? '[]', true) ?: []);
                if ($pCount > 0 && $groupedSessions[$key]['present_count'] === 0) {
                    $groupedSessions[$key]['present_students'] = $raw->present_students;
                    $groupedSessions[$key]['absent_students'] = $raw->absent_students;
                    $groupedSessions[$key]['present_count'] = $pCount;
                    $groupedSessions[$key]['absent_count'] = count(json_decode($raw->absent_students ?? '[]', true) ?: []);
                }
            }
        }

        // Convert to indexed list and compute permanent serial numbers ascending
        $sessionList = array_values($groupedSessions);
        usort($sessionList, function($a, $b) {
            $cmp = strcmp($a['date'], $b['date']);
            if ($cmp !== 0) return $cmp;
            return ($a['periods'][0] ?? 0) <=> ($b['periods'][0] ?? 0);
        });

        $sl = 1;
        foreach ($sessionList as &$s) {
            $s['sl_no'] = $sl++;
            $s['period'] = implode(', ', $s['periods']);
            $s['period_display'] = count($s['periods']) > 1 ? ('Periods ' . implode(', ', $s['periods'])) : ('Period ' . ($s['periods'][0] ?? 1));
        }
        unset($s);

        // Sort descending (latest date and latest logs on top) for UI presentation
        usort($sessionList, function($a, $b) {
            $cmp = strcmp($b['date'], $a['date']);
            if ($cmp !== 0) return $cmp;
            return ($b['periods'][0] ?? 0) <=> ($a['periods'][0] ?? 0);
        });

        $logs = $sessionList;

        // 2. Fetch Date-Wise Attendance Matrix
        $batchSubject = BatchSubject::findOrFail($batchSubjectId);
        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->where('status', 'Approved')
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        // Gather unique date/periods
        $dates = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get(['date', 'period']);

        $matrix = [];
        foreach ($students as $s) {
            $attendanceData = [];
            foreach ($dates as $d) {
                // Find log record for this date and period
                $log = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $batchSubjectId)
                    ->where('date', $d->date)
                    ->where('period', $d->period)
                    ->first();
                
                $status = '-'; // Not marked
                if ($log) {
                    $presentList = json_decode($log->present_students ?? '[]', true);
                    $absentList = json_decode($log->absent_students ?? '[]', true);
                    if (in_array($s->reg_no, $presentList)) {
                        $status = 'P';
                    } elseif (in_array($s->reg_no, $absentList)) {
                        $status = 'A';
                    }
                }
                $key = $d->date . ' | P' . $d->period;
                $attendanceData[$key] = $status;
            }
            $matrix[] = [
                'roll_no' => $s->roll_no,
                'name' => $s->name,
                'reg_no' => $s->reg_no,
                'attendance' => $attendanceData
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'logs' => $logs,
            'dates' => $dates,
            'matrix' => $matrix
        ]);
    }

    /**
     * Delete an accidental or duplicate class log entry and clean up connected records safely.
     */
    public function deleteAttendanceLog(Request $request)
    {
        $role = Session::get('userRole');
        $recordedBy = Session::get('userId');
        if (!$role || $role === 'Student' || !$recordedBy) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'batch_subject_id' => 'required|exists:batch_subjects,id',
            'log_ids' => 'nullable',
            'log_id' => 'nullable|integer',
        ]);

        $batchSubjectId = $request->batch_subject_id;
        $explicitLogIds = $request->input('log_ids');
        if (!empty($explicitLogIds) && !is_array($explicitLogIds)) {
            $explicitLogIds = array_map('intval', explode(',', (string)$explicitLogIds));
        } elseif (!empty($explicitLogIds) && is_array($explicitLogIds)) {
            $explicitLogIds = array_map('intval', $explicitLogIds);
        } else {
            $explicitLogIds = [];
        }
        if ($request->log_id && empty($explicitLogIds)) {
            $explicitLogIds = [(int)$request->log_id];
        }

        if (empty($explicitLogIds)) {
            return response()->json(['status' => 'ERROR', 'message' => 'No log ID specified for deletion.'], 422);
        }

        $batchSubject = \App\Models\BatchSubject::find($batchSubjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch subject not found.'], 404);
        }

        $logsToDelete = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->whereIn('id', $explicitLogIds)
            ->get();

        if ($logsToDelete->isEmpty()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Log entry not found or already deleted.'], 404);
        }

        $firstLog = $logsToDelete->first();
        $date = $firstLog->date;
        $subBatch = $firstLog->sub_batch ?? 'Whole';
        $lessonPlanId = $firstLog->lesson_plan_id;
        $topicsCovered = $firstLog->topics_covered;

        DB::transaction(function () use ($logsToDelete, $batchSubjectId, $batchSubject, $date, $subBatch, $lessonPlanId, $topicsCovered) {
            $deleteIds = $logsToDelete->pluck('id')->toArray();

            // 1. Delete the specified class_logs_attendance rows
            DB::table('class_logs_attendance')
                ->whereIn('id', $deleteIds)
                ->delete();

            // 2. Duplicate check: verify if remaining logs exist for this subject on this date & sub_batch
            $remainingLogs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $batchSubjectId)
                ->where('date', $date)
                ->where('sub_batch', $subBatch)
                ->get();

            if ($remainingLogs->isEmpty()) {
                // If NO other logs exist on this date for this batch, safely clean up student_attendance
                if (\Schema::hasTable('student_attendance')) {
                    DB::table('student_attendance')
                        ->where('subject_code', $batchSubject->subject_code)
                        ->where('date', $date)
                        ->where(function ($q) use ($subBatch) {
                            if ($subBatch && $subBatch !== 'Whole') {
                                $q->where('sub_batch', $subBatch);
                            }
                        })
                        ->delete();
                }
            } else {
                // Legitimate surviving duplicate exists!
                // Keep student_attendance intact so valid attendance records are fully preserved.
            }

            // 3. Revert Lesson Plan status if this was the sole log for it
            if ($lessonPlanId) {
                $otherLpLogs = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $batchSubjectId)
                    ->where('lesson_plan_id', $lessonPlanId)
                    ->exists();

                if (!$otherLpLogs) {
                    $lp = \App\Models\LessonPlan::find($lessonPlanId);
                    if ($lp && $lp->actual_date === $date) {
                        $lp->actual_date = null;
                        $lp->status = 'Pending';
                        $lp->save();
                    }
                }
            }

            // 4. Revert Practical Experiment conducted_date if no other logs reference it
            try {
                $practicalExps = \App\Models\PracticalExperiment::where('batch_subject_id', $batchSubjectId)
                    ->where('conducted_date', $date)
                    ->get();

                foreach ($practicalExps as $pExp) {
                    // Check if other logs for this subject still reference this experiment
                    $anyRemainingExpLog = DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $batchSubjectId)
                        ->get()
                        ->filter(function ($l) use ($pExp) {
                            $t = strtolower($l->topics_covered ?? '');
                            return (stripos($t, strtolower($pExp->title ?? '')) !== false)
                                || preg_match('/\b(?:exp|experiment|ex)\.?\s*#?\s*0*' . preg_quote($pExp->experiment_no, '/') . '\b/i', $t);
                        });

                    if ($anyRemainingExpLog->isEmpty()) {
                        $pExp->conducted_date = null;
                        $pExp->save();
                    }
                }
            } catch (\Exception $e) {
                \Log::warning("Revert practical experiment notice on log delete: " . $e->getMessage());
            }
        });

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Log entry and connected records removed successfully.'
        ]);
    }
}
