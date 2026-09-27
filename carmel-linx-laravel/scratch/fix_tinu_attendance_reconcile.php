<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\Student;
use App\Models\LessonPlan;
use App\Models\AuditLog;

echo "=== STARTING RECONCILIATION FOR TINU SCARIA (EEE-5031) ===\n";

$batchSubjectId = 177;
$subject = BatchSubject::findOrFail($batchSubjectId);
$classroomId = $subject->classroom_id; // 'EEE_2024_2027'

echo "Subject: {$subject->subject_name} ({$subject->subject_code}) in {$classroomId}\n";

// 1. Fetch all 67 classroom students
$students = Student::getClassroomStudentsQuery($classroomId)
    ->where(function($q) {
        $q->where('status', 'Approved')->orWhere('status', 'Active');
    })
    ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
    ->get(['roll_no', 'name', 'reg_no']);

$allRegNos = $students->pluck('reg_no')->toArray();
$studentCount = count($allRegNos);
echo "Loaded {$studentCount} approved classroom students.\n";

// 2. Normalize invalid periods on 2026-06-15 (periods 23, 61, 51 -> 3, 6, 7)
$june15Logs = DB::table('class_logs_attendance')
    ->where('batch_subject_id', $batchSubjectId)
    ->where('date', '2026-06-15')
    ->orderBy('id', 'asc')
    ->get();

if ($june15Logs->count() === 7) {
    echo "Normalizing 7 period logs on 2026-06-15...\n";
    $periodSequence = [1, 2, 3, 4, 5, 6, 7];
    foreach ($june15Logs as $idx => $jLog) {
        DB::table('class_logs_attendance')
            ->where('id', $jLog->id)
            ->update([
                'period' => $periodSequence[$idx],
                'updated_at' => now(),
            ]);
    }
    echo "  Updated periods to 1, 2, 3, 4, 5, 6, 7.\n";
}

// 3. Fetch all 38 class logs ordered chronologically
$allLogs = DB::table('class_logs_attendance')
    ->where('batch_subject_id', $batchSubjectId)
    ->orderBy('date', 'asc')
    ->orderBy('period', 'asc')
    ->get();

echo "Total class logs: " . $allLogs->count() . "\n";

// 4. Fetch all lesson plans for this subject
$lessonPlans = LessonPlan::where('batch_subject_id', $batchSubjectId)
    ->orderBy('day_no', 'asc')
    ->orderBy('id', 'asc')
    ->get();

echo "Total lesson plan topics: " . $lessonPlans->count() . "\n";

DB::beginTransaction();
try {
    // 5. Update each of the 38 class logs with sequential lesson plan and full roster attendance
    foreach ($allLogs as $idx => $log) {
        $sessionIndex = $idx; // 0 to 37
        $assignedLp = $lessonPlans->get($sessionIndex);

        $curPresent = json_decode($log->present_students ?: '[]', true);
        $curAbsent = json_decode($log->absent_students ?: '[]', true);

        $newPresent = [];
        $newAbsent = [];

        // Check if full class was absent (like log 562 on 2026-06-09)
        if (empty($curPresent) && count($curAbsent) >= 9) {
            $newPresent = [];
            $newAbsent = $allRegNos;
        } elseif (count($curPresent) === 1 && $curPresent[0] === '24EEE12514') {
            // Logs 570 & 571 on 2026-06-16 where only 1 student was present
            $newPresent = ['24EEE12514'];
            $newAbsent = array_values(array_diff($allRegNos, ['24EEE12514']));
        } else {
            // Normal session: recorded absentees remain absent; all other classroom students are present
            $newAbsent = array_values(array_intersect($curAbsent, $allRegNos));
            $newPresent = array_values(array_diff($allRegNos, $newAbsent));
        }

        $lpId = $assignedLp ? $assignedLp->id : $log->lesson_plan_id;
        $topicContent = $assignedLp ? $assignedLp->topic_content : $log->topics_covered;

        DB::table('class_logs_attendance')
            ->where('id', $log->id)
            ->update([
                'lesson_plan_id' => $lpId,
                'topics_covered' => $topicContent,
                'present_students' => json_encode($newPresent),
                'absent_students' => json_encode($newAbsent),
                'updated_at' => now(),
            ]);

        // Sync lesson plan entry
        if ($assignedLp) {
            $assignedLp->status = 'Completed';
            $assignedLp->actual_date = $log->date;
            $assignedLp->actual_hours = 1;
            $assignedLp->save();
        }
    }

    // 6. Reset lesson plans beyond the 38 completed hours to Pending
    for ($i = 38; $i < $lessonPlans->count(); $i++) {
        $pendingLp = $lessonPlans->get($i);
        if ($pendingLp) {
            $pendingLp->status = 'Pending';
            $pendingLp->actual_date = null;
            $pendingLp->actual_hours = null;
            $pendingLp->save();
        }
    }

    // 7. Synchronize student_attendance table for all 67 students across all 19 unique dates
    echo "Synchronizing student_attendance table...\n";
    $uniqueDates = $allLogs->pluck('date')->unique()->values();

    $syncedAttendanceCount = 0;
    foreach ($uniqueDates as $uDate) {
        // Find logs for this date
        $dateLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->where('date', $uDate)
            ->get();

        $lpForDate = $dateLogs->first()->lesson_plan_id ?? null;

        foreach ($allRegNos as $regNo) {
            // Student is Present on date if they were present in at least 1 session on that date
            $wasPresent = false;
            foreach ($dateLogs as $dl) {
                $pArr = json_decode($dl->present_students ?: '[]', true);
                if (in_array($regNo, $pArr)) {
                    $wasPresent = true;
                    break;
                }
            }

            $status = $wasPresent ? 'Present' : 'Absent';

            DB::table('student_attendance')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'subject_code' => $subject->subject_code,
                    'date' => $uDate,
                ],
                [
                    'status' => $status,
                    'sub_batch' => 'Whole',
                    'lesson_plan_id' => $lpForDate,
                    'updated_at' => now(),
                ]
            );
            $syncedAttendanceCount++;
        }
    }

    echo "Synchronized {$syncedAttendanceCount} student_attendance rows ({$studentCount} students x " . count($uniqueDates) . " dates).\n";

    // 8. Record in audit_logs
    AuditLog::create([
        'performed_by' => '9526414494',
        'performed_by_name' => 'TINU SCARIA',
        'target_id' => (string)$batchSubjectId,
        'target_name' => "{$subject->subject_name} ({$subject->subject_code})",
        'action' => 'Attendance Log & Lesson Plan Reconciliation',
        'details' => "Reconciled attendance logs for 67 enrolled students across 38 conducted hours (19 sessions). Filled lesson planner actual dates for Days 1-38 and set remaining Days 39-61 to Pending.",
        'ip_address' => '127.0.0.1'
    ]);

    DB::commit();
    echo "✅ Database reconciliation committed successfully!\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Error during reconciliation: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
