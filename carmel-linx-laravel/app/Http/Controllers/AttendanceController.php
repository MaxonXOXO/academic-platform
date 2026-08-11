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
     */
    public function getActiveSubjects()
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        // HOD, Workshop Superintendent, Principal can access all subjects
        if (in_array($role, ['HOD', 'Workshop Superintendent', 'Principal'])) {
            $subjects = BatchSubject::orderBy('classroom_id', 'asc')
                ->orderBy('semester', 'asc')
                ->get();
        } else {
            // Other staff members (Lecturer, Demonstrator, Trade Instructor, etc.) see only assigned subjects
            $assignedIds = SubjectStaffAssignment::where('staff_mobile_no', $staffMobile)->pluck('batch_subject_id');
            $subjects = BatchSubject::whereIn('id', $assignedIds)
                ->orderBy('classroom_id', 'asc')
                ->orderBy('semester', 'asc')
                ->get();
        }

        return response()->json([
            'status' => 'SUCCESS',
            'subjects' => $subjects
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
        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->where('status', 'Approved')
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        // Fetch pending/in-progress lesson plans for dropdown selection
        $lessonPlans = LessonPlan::where('batch_subject_id', $id)
            ->orderBy('id', 'asc')
            ->get(['id', 'topic_content', 'co_id', 'status']);

        return response()->json([
            'status' => 'SUCCESS',
            'students' => $students,
            'lesson_plans' => $lessonPlans,
            'classroom_id' => $batchSubject->classroom_id,
            'subject_type' => $batchSubject->subject_type
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

        // If a lesson plan was selected, mark it as Completed
        if ($request->lesson_plan_id) {
            $lp = LessonPlan::find($request->lesson_plan_id);
            if ($lp && $lp->status === 'Pending') {
                $lp->status = 'Completed';
                $lp->status = 'Completed'; // Set status to Completed when checked off
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

        // 1. Fetch Class Attendance Logs
        $logs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('date', 'desc')
            ->orderBy('period', 'desc')
            ->get();

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
