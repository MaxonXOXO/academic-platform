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

        $nextLogSlNo = ($lastLogCount > 0 || $hasLessonPlans) ? ($lastLogCount + 1) : 0;

        return response()->json([
            'status' => 'SUCCESS',
            'students' => $students,
            'lesson_plans' => $lessonPlans,
            'classroom_id' => $batchSubject->classroom_id,
            'subject_type' => $batchSubject->subject_type,
            'last_log_sl_no' => $lastLogCount,
            'next_log_sl_no' => $nextLogSlNo
        ]);
    }

    /**
     * Save the Class Log and Attendance data.
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
            'topics_covered' => 'required|string',
            'present_students' => 'nullable|array',
            'absent_students' => 'nullable|array',
            'sub_batch' => 'nullable|string|in:Whole,1,2',
        ]);

        $subBatch = $request->input('sub_batch', 'Whole');

        DB::transaction(function () use ($request, $recordedBy, $subBatch) {
            foreach ($request->periods as $period) {
                // Pre-schedule or update existing class log & attendance
                $exists = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $request->batch_subject_id)
                    ->where('date', $request->date)
                    ->where('period', $period)
                    ->where('sub_batch', $subBatch)
                    ->first();

                if ($exists) {
                    DB::table('class_logs_attendance')
                        ->where('id', $exists->id)
                        ->update([
                            'lesson_plan_id' => $request->lesson_plan_id,
                            'topics_covered' => $request->topics_covered,
                            'present_students' => json_encode($request->present_students ?? []),
                            'absent_students' => json_encode($request->absent_students ?? []),
                            'recorded_by' => $recordedBy,
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('class_logs_attendance')->insert([
                        'batch_subject_id' => $request->batch_subject_id,
                        'date' => $request->date,
                        'period' => $period,
                        'lesson_plan_id' => $request->lesson_plan_id,
                        'topics_covered' => $request->topics_covered,
                        'present_students' => json_encode($request->present_students ?? []),
                        'absent_students' => json_encode($request->absent_students ?? []),
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
        } else if (!empty($request->topics_covered)) {
            $lp = LessonPlan::where('batch_subject_id', $request->batch_subject_id)
                ->where('topic_content', trim($request->topics_covered))
                ->first();
            if ($lp) {
                $lp->status = 'Completed';
                $lp->actual_date = $request->date;
                $lp->save();
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Class log and attendance recorded successfully!'
        ]);
    }

    /**
     * Get tutor class students list to assign roll numbers.
     */
    public function getTutorStudents()
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$staffMobile || !in_array($role, ['Tutor', 'HOD', 'Lecturer', 'Workshop Superintendent'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        // Find classroom managed by this tutor (staff mobile matches classroom advisor/tutor)
        $classroom = DB::table('class_management')
            ->where('tutor_mobile_no', $staffMobile)
            ->first();

        if (!$classroom) {
            $classroom = DB::table('r26_class_management')
                ->where('tutor_mobile_no', $staffMobile)
                ->first();
        }

        if (!$classroom) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No classroom assigned as advisor/tutor to your profile.'
            ]);
        }

        $students = Student::where('classroom_id', $classroom->classroom_id)
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

        // 1. Fetch Class Attendance Logs in chronological order to compute serial number
        $rawLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $slNo = 1;
        foreach ($rawLogs as $log) {
            $log->sl_no = $slNo++;
            $log->present_count = count(json_decode($log->present_students ?? '[]'));
            $log->absent_count = count(json_decode($log->absent_students ?? '[]'));
        }

        // Return logs in reverse (newest first) for UI presentation
        $logs = $rawLogs->reverse()->values();

        // Decode JSON arrays for counts
        foreach ($logs as $log) {
            $log->present_count = count(json_decode($log->present_students ?? '[]'));
            $log->absent_count = count(json_decode($log->absent_students ?? '[]'));
        }

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
}
