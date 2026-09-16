<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use App\Models\BatchSubject;
use App\Models\Student;
use App\Models\LessonPlan;
use App\Models\PracticalExperiment;
use App\Models\AuditLog;
use Smalot\PdfParser\Parser as PdfParser;

class SbteSubjectLogImportController extends Controller
{
    /**
     * Import official SBTE Attendance Statement or Subject Log PDF.
     */
    public function importPdf(Request $request)
    {
        $role = Session::get('userRole');
        $staffMobile = Session::get('userId');

        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'file' => 'required|file|max:20480', // max 20MB
            'batch_subject_id' => 'required|integer',
        ]);

        $batchSubjectId = (int)$request->batch_subject_id;
        $batchSubject = BatchSubject::findOrFail($batchSubjectId);
        $subBatch = $request->input('sub_batch', 'Whole') ?: 'Whole';
        $autoFillLp = filter_var($request->input('auto_fill_lesson_plan', true), FILTER_VALIDATE_BOOLEAN);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext !== 'pdf') {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Please upload a valid SBTE PDF file (.pdf).'
            ], 422);
        }

        $tempPath = $file->getRealPath();

        // 1. Extract full text using Smalot Parser (or fallback)
        $fullText = '';
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($tempPath);
            foreach ($pdf->getPages() as $p) {
                $fullText .= $p->getText() . "\n";
            }
        } catch (\Exception $e) {
            \Log::warning("Native Smalot PDF parser failed: " . $e->getMessage());
        }

        if (empty(trim($fullText))) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'The uploaded PDF does not contain extractable text.'
            ], 422);
        }

        // Fetch all active/approved students in this classroom
        $classroomStudents = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->where(function ($q) {
                $q->where('status', 'Approved')->orWhere('status', 'Active');
            })
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['roll_no', 'name', 'reg_no']);

        if ($classroomStudents->isEmpty()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => "No approved students were found for classroom: {$batchSubject->classroom_id}."
            ], 422);
        }

        // Detect if Attendance Statement or Subject Log
        $isAttendanceStatement = stripos($fullText, 'ATTENDANCE STATEMENT') !== false;

        if ($isAttendanceStatement) {
            return $this->processAttendanceStatement(
                $fullText,
                $batchSubject,
                $classroomStudents,
                $subBatch,
                $autoFillLp,
                $staffMobile,
                $request
            );
        } else {
            return $this->processSubjectLog(
                $fullText,
                $batchSubject,
                $classroomStudents,
                $subBatch,
                $autoFillLp,
                $staffMobile,
                $request
            );
        }
    }

    /**
     * Process SBTE Attendance Statement PDF (Matrix with individual A, 1, 2, 3 marks).
     */
    private function processAttendanceStatement(
        string $fullText,
        BatchSubject $batchSubject,
        $classroomStudents,
        string $subBatch,
        bool $autoFillLp,
        ?string $staffMobile,
        Request $request
    ) {
        // Extract Header
        preg_match('/Programme:\s*(.*)/', $fullText, $progM);
        preg_match('/Course:\s*(.*?)\s*(?:\((\w+)\))?\s*Semester:\s*(\w+)/', $fullText, $courseM);
        preg_match('/Faculty:\s*(.*)/', $fullText, $facM);
        preg_match('/(?:FROM\s+)?(\d{2}-\d{2}-\d{4})\s+TO\s+(\d{2}-\d{2}-\d{4})/i', $fullText, $rangeM);

        $facultyName = trim($facM[1] ?? '');
        $recordedBy = $staffMobile ?: ($facultyName ?: 'Faculty Staff');
        $year = !empty($rangeM[1]) ? substr($rangeM[1], -4) : date('Y');

        // Extract Dates (DD/MM)
        preg_match('/(?:Roll\.\s*No\.\s*Name\s*)?((?:\d{2}\/\d{2}\s*){10,})/i', $fullText, $datesM);
        $datesStr = preg_replace('/\s+/', '', $datesM[1] ?? '');
        preg_match_all('/\d{2}\/\d{2}/', $datesStr, $dateMatches);
        $datesRaw = $dateMatches[0] ?? [];

        if (empty($datesRaw)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Could not detect class dates in the Attendance Statement table.'
            ], 422);
        }

        $sessionDates = [];
        foreach ($datesRaw as $dr) {
            $p = explode('/', $dr);
            $sessionDates[] = "{$year}-{$p[1]}-{$p[0]}";
        }
        $sessionCount = count($sessionDates);

        // Extract Hours row
        preg_match('/Total\s*\n\s*([\d\s,]+)\n/i', $fullText, $hoursM);
        $hoursRaw = preg_split('/\s+/', trim($hoursM[1] ?? ''));
        $hoursList = [];
        foreach ($hoursRaw as $hr) {
            preg_match_all('/\d+/', $hr, $hm);
            if (!empty($hm[0])) {
                $hoursList[] = array_map('intval', $hm[0]);
            }
        }

        // Fill default hours if needed
        while (count($hoursList) < $sessionCount) {
            $hoursList[] = [1];
        }

        // Extract Students attendance rows: Roll. No. Name [Marks...] Total
        preg_match_all('/(?m)^(\d+)\.\s+([A-Za-z\s\.\']+?)\t?\s+([A123\s]+?)\s+(\d+)\s*$/', $fullText, $studMatches, PREG_SET_ORDER);

        if (empty($studMatches)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Could not detect student attendance rows in the PDF table.'
            ], 422);
        }

        // Map classroom students by roll number and name
        $studentsByRoll = [];
        $studentsByName = [];
        foreach ($classroomStudents as $cs) {
            if ($cs->roll_no !== null) {
                $studentsByRoll[(int)$cs->roll_no] = $cs;
            }
            $cleanName = strtoupper(preg_replace('/[^A-Z]/', '', $cs->name));
            $studentsByName[$cleanName] = $cs;
        }

        // Build attendance map per session index: sessionIndex => ['present' => [regNos], 'absent' => [regNos]]
        $sessionAttendance = [];
        for ($i = 0; $i < $sessionCount; $i++) {
            $sessionAttendance[$i] = ['present' => [], 'absent' => []];
        }

        $matchedStudentsCount = 0;
        foreach ($studMatches as $sm) {
            $rollNo = (int)$sm[1];
            $rawName = trim($sm[2]);
            $cleanName = strtoupper(preg_replace('/[^A-Z]/', '', $rawName));
            $marks = preg_split('/\s+/', trim($sm[3]));

            $studentObj = $studentsByRoll[$rollNo] ?? ($studentsByName[$cleanName] ?? null);
            if (!$studentObj) {
                // Fallback: search partial match
                foreach ($classroomStudents as $cs) {
                    $cName = strtoupper(preg_replace('/[^A-Z]/', '', $cs->name));
                    if (str_contains($cName, $cleanName) || str_contains($cleanName, $cName)) {
                        $studentObj = $cs;
                        break;
                    }
                }
            }

            if (!$studentObj) continue;

            $regNo = $studentObj->reg_no;
            $matchedStudentsCount++;

            for ($i = 0; $i < $sessionCount; $i++) {
                $mark = strtoupper(trim($marks[$i] ?? 'A'));
                if ($mark === 'A' || $mark === '0') {
                    $sessionAttendance[$i]['absent'][] = $regNo;
                } else {
                    $sessionAttendance[$i]['present'][] = $regNo;
                }
            }
        }

        // Fetch lesson plans & practical experiments
        $lessonPlans = LessonPlan::where('batch_subject_id', $batchSubject->id)
            ->orderBy('id', 'asc')
            ->get();

        $practicalExperiments = PracticalExperiment::where('batch_subject_id', $batchSubject->id)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED), experiment_no ASC')
            ->get();

        $importedSessions = 0;
        $totalHoursLogged = 0;
        $mappedLpCount = 0;
        $usedLpIds = [];

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $sessionCount; $i++) {
                $date = $sessionDates[$i];
                $periods = $hoursList[$i] ?? [1];
                $presentRegNos = array_values(array_unique($sessionAttendance[$i]['present']));
                $absentRegNos = array_values(array_unique($sessionAttendance[$i]['absent']));

                // Topic assignment
                $assignedLpId = null;
                $assignedTopic = null;

                if ($autoFillLp) {
                    // Try to get lesson plan by sequential index or next unassigned
                    $assignedLp = $lessonPlans->get($i) ?: $lessonPlans->first(function ($lp) use ($usedLpIds) {
                        return !in_array($lp->id, $usedLpIds);
                    });

                    if ($assignedLp) {
                        $assignedTopic = $assignedLp->topic_content;
                        $assignedLpId = $assignedLp->id;
                        $usedLpIds[] = $assignedLp->id;
                        $assignedLp->status = 'Completed';
                        $assignedLp->actual_date = $date;
                        $assignedLp->save();
                        $mappedLpCount++;
                    } elseif ($practicalExperiments->isNotEmpty()) {
                        $expIndex = $i % $practicalExperiments->count();
                        $assignedExp = $practicalExperiments->get($expIndex);
                        if ($assignedExp) {
                            $assignedTopic = "Exp #{$assignedExp->experiment_no}: {$assignedExp->title}";
                            $assignedExp->conducted_date = $date;
                            $assignedExp->save();
                            $mappedLpCount++;
                        } else {
                            $assignedTopic = "Practical Session";
                        }
                    }
                }

                if (empty($assignedTopic)) {
                    $assignedTopic = "Topic pending manual update";
                }

                // Create distinct period logs for exact hour calculations
                foreach ($periods as $period) {
                    $period = (int)$period;

                    $existingLog = DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $batchSubject->id)
                        ->where('date', $date)
                        ->where('period', $period)
                        ->where('sub_batch', $subBatch)
                        ->first();

                    if ($existingLog) {
                        DB::table('class_logs_attendance')
                            ->where('id', $existingLog->id)
                            ->update([
                                'lesson_plan_id' => $assignedLpId ?: $existingLog->lesson_plan_id,
                                'topics_covered' => $assignedTopic ?: $existingLog->topics_covered,
                                'present_students' => json_encode($presentRegNos),
                                'absent_students' => json_encode($absentRegNos),
                                'recorded_by' => $recordedBy,
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('class_logs_attendance')->insert([
                            'batch_subject_id' => $batchSubject->id,
                            'date' => $date,
                            'period' => $period,
                            'lesson_plan_id' => $assignedLpId,
                            'topics_covered' => $assignedTopic,
                            'present_students' => json_encode($presentRegNos),
                            'absent_students' => json_encode($absentRegNos),
                            'sub_batch' => $subBatch,
                            'recorded_by' => $recordedBy,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Sync student_attendance table
                    if (Schema::hasTable('student_attendance')) {
                        foreach ($presentRegNos as $rNo) {
                            DB::table('student_attendance')->updateOrInsert(
                                [
                                    'reg_no' => $rNo,
                                    'subject_code' => $batchSubject->subject_code,
                                    'date' => $date,
                                ],
                                [
                                    'status' => 'Present',
                                    'sub_batch' => $subBatch,
                                    'lesson_plan_id' => $assignedLpId,
                                    'updated_at' => now(),
                                ]
                            );
                        }
                        foreach ($absentRegNos as $rNo) {
                            DB::table('student_attendance')->updateOrInsert(
                                [
                                    'reg_no' => $rNo,
                                    'subject_code' => $batchSubject->subject_code,
                                    'date' => $date,
                                ],
                                [
                                    'status' => 'Absent',
                                    'sub_batch' => $subBatch,
                                    'lesson_plan_id' => $assignedLpId,
                                    'updated_at' => now(),
                                ]
                            );
                        }
                    }

                    $totalHoursLogged++;
                }

                $importedSessions++;
            }

            // Record Audit Log
            try {
                AuditLog::create([
                    'performed_by' => Session::get('userId') ?: 'Staff',
                    'performed_by_name' => Session::get('userName') ?: ($facultyName ?: 'Faculty Staff'),
                    'target_id' => (string)$batchSubject->id,
                    'target_name' => "{$batchSubject->subject_name} ({$batchSubject->subject_code})",
                    'action' => 'SBTE Attendance Statement Bulk Import',
                    'details' => "Bulk imported {$importedSessions} sessions ({$totalHoursLogged} class hours) with student-wise attendance for {$matchedStudentsCount} students.",
                    'ip_address' => $request->ip()
                ]);
            } catch (\Exception $logEx) {}

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Successfully imported attendance for {$matchedStudentsCount} students across {$importedSessions} sessions ({$totalHoursLogged} class hours)!",
                'data' => [
                    'programme' => trim($progM[1] ?? ''),
                    'course_title' => $batchSubject->subject_name,
                    'course_code' => $batchSubject->subject_code,
                    'semester' => $batchSubject->semester,
                    'faculty' => $facultyName,
                    'imported_sessions' => $importedSessions,
                    'total_hours' => $totalHoursLogged,
                    'mapped_lesson_plans' => $mappedLpCount,
                    'students_count' => $matchedStudentsCount,
                    'date_range' => ($datesRaw[0] ?? '') . ' to ' . (end($datesRaw) ?? ''),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to save attendance statement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process SBTE Subject Log PDF (Vertical rows with SL.No., Date, Hours, Topics).
     */
    private function processSubjectLog(
        string $fullText,
        BatchSubject $batchSubject,
        $classroomStudents,
        string $subBatch,
        bool $autoFillLp,
        ?string $staffMobile,
        Request $request
    ) {
        preg_match('/Programme:\s*(.*)/', $fullText, $progM);
        preg_match('/Course:\s*(.*?)\s*(?:\((\w+)\))?\s*Semester:\s*(\w+)/', $fullText, $courseM);
        preg_match('/Faculty:\s*(.*)/', $fullText, $facM);

        $facultyName = trim($facM[1] ?? '');
        $recordedBy = $staffMobile ?: ($facultyName ?: 'Faculty Staff');
        $studentRegNos = $classroomStudents->pluck('reg_no')->toArray();

        preg_match_all('/(?ms)^(\d+)\.\s+(\d{2}-\d{2}-\d{4})\s+([\d,\s]+?)\s*(.*?)(?=\n\d+\.|\Z)/', $fullText, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No class sessions were found in the uploaded PDF.'
            ], 422);
        }

        $lessonPlans = LessonPlan::where('batch_subject_id', $batchSubject->id)->orderBy('id', 'asc')->get();
        $practicalExperiments = PracticalExperiment::where('batch_subject_id', $batchSubject->id)->orderByRaw('CAST(experiment_no AS UNSIGNED), experiment_no ASC')->get();

        $importedSessionsCount = 0;
        $totalHoursLogged = 0;
        $mappedLpCount = 0;
        $usedLpIds = [];

        DB::beginTransaction();
        try {
            foreach ($matches as $m) {
                $rawDt = trim($m[2]);
                $dParts = explode('-', $rawDt);
                $date = count($dParts) === 3 ? "{$dParts[2]}-{$dParts[1]}-{$dParts[0]}" : $rawDt;

                preg_match_all('/\d+/', $m[3], $hMatches);
                $hours = !empty($hMatches[0]) ? array_map('intval', $hMatches[0]) : [1];

                $rawContent = trim($m[4]);
                if ($facultyName) {
                    $rawContent = preg_replace('/\s*' . preg_quote($facultyName, '/') . '.*$/i', '', $rawContent);
                }
                $rawContent = preg_replace('/\s+\d+\s*$/', '', $rawContent);
                $assignedTopic = trim(preg_replace('/\s+/', ' ', $rawContent));

                if (str_starts_with($assignedTopic, ',')) {
                    preg_match_all('/\d+/', substr($assignedTopic, 0, 6), $extraH);
                    foreach ($extraH[0] as $eh) {
                        $ehi = (int)$eh;
                        if (!in_array($ehi, $hours)) $hours[] = $ehi;
                    }
                    $assignedTopic = trim(preg_replace('/^[\d,\s]+/', '', $assignedTopic));
                }

                $assignedLpId = null;

                if (!empty($assignedTopic)) {
                    $matchedLp = $lessonPlans->first(function ($lp) use ($assignedTopic, $usedLpIds) {
                        if (in_array($lp->id, $usedLpIds)) return false;
                        return stripos($lp->topic_content, $assignedTopic) !== false || stripos($assignedTopic, $lp->topic_content) !== false;
                    });
                    if ($matchedLp) {
                        $assignedLpId = $matchedLp->id;
                        $usedLpIds[] = $matchedLp->id;
                        $matchedLp->status = 'Completed';
                        $matchedLp->actual_date = $date;
                        $matchedLp->save();
                        $mappedLpCount++;
                    }
                }

                if (empty($assignedTopic) && $autoFillLp) {
                    $pendingLp = $lessonPlans->first(function ($lp) use ($usedLpIds) {
                        return !in_array($lp->id, $usedLpIds) && $lp->status !== 'Completed';
                    });
                    if ($pendingLp) {
                        $assignedTopic = $pendingLp->topic_content;
                        $assignedLpId = $pendingLp->id;
                        $usedLpIds[] = $pendingLp->id;
                        $pendingLp->status = 'Completed';
                        $pendingLp->actual_date = $date;
                        $pendingLp->save();
                        $mappedLpCount++;
                    }
                }

                if (empty($assignedTopic)) {
                    $assignedTopic = "Topic pending manual update";
                }

                foreach ($hours as $period) {
                    $period = (int)$period;
                    $existingLog = DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $batchSubject->id)
                        ->where('date', $date)
                        ->where('period', $period)
                        ->where('sub_batch', $subBatch)
                        ->first();

                    if ($existingLog) {
                        DB::table('class_logs_attendance')
                            ->where('id', $existingLog->id)
                            ->update([
                                'lesson_plan_id' => $assignedLpId ?: $existingLog->lesson_plan_id,
                                'topics_covered' => $assignedTopic ?: $existingLog->topics_covered,
                                'present_students' => json_encode($studentRegNos),
                                'absent_students' => json_encode([]),
                                'recorded_by' => $recordedBy,
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('class_logs_attendance')->insert([
                            'batch_subject_id' => $batchSubject->id,
                            'date' => $date,
                            'period' => $period,
                            'lesson_plan_id' => $assignedLpId,
                            'topics_covered' => $assignedTopic,
                            'present_students' => json_encode($studentRegNos),
                            'absent_students' => json_encode([]),
                            'sub_batch' => $subBatch,
                            'recorded_by' => $recordedBy,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    if (Schema::hasTable('student_attendance')) {
                        foreach ($studentRegNos as $regNo) {
                            DB::table('student_attendance')->updateOrInsert(
                                [
                                    'reg_no' => $regNo,
                                    'subject_code' => $batchSubject->subject_code,
                                    'date' => $date,
                                ],
                                [
                                    'status' => 'Present',
                                    'sub_batch' => $subBatch,
                                    'lesson_plan_id' => $assignedLpId,
                                    'updated_at' => now(),
                                ]
                            );
                        }
                    }

                    $totalHoursLogged++;
                }

                $importedSessionsCount++;
            }

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Successfully imported {$importedSessionsCount} sessions ({$totalHoursLogged} class hours) and updated attendance for " . count($studentRegNos) . " students!",
                'data' => [
                    'programme' => trim($progM[1] ?? ''),
                    'course_title' => $batchSubject->subject_name,
                    'course_code' => $batchSubject->subject_code,
                    'semester' => $batchSubject->semester,
                    'faculty' => $facultyName,
                    'imported_sessions' => $importedSessionsCount,
                    'total_hours' => $totalHoursLogged,
                    'mapped_lesson_plans' => $mappedLpCount,
                    'students_count' => count($studentRegNos),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to import Subject Log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync and auto-populate class logs from active Lesson Plans or Practical Experiments.
     */
    public function syncFromLessonPlan(Request $request)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'batch_subject_id' => 'required|integer'
        ]);

        $batchSubjectId = (int)$request->batch_subject_id;
        $batchSubject = BatchSubject::findOrFail($batchSubjectId);

        $result = self::executeLessonPlanSync($batchSubjectId);

        return response()->json($result);
    }

    /**
     * Core reusable method to sync subject logs from lesson plans.
     */
    public static function executeLessonPlanSync(int $batchSubjectId): array
    {
        $batchSubject = BatchSubject::find($batchSubjectId);
        if (!$batchSubject) {
            return ['status' => 'ERROR', 'message' => 'Subject not found.'];
        }

        $lessonPlans = LessonPlan::where('batch_subject_id', $batchSubjectId)
            ->orderBy('id', 'asc')
            ->get();

        $practicalExperiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED), experiment_no ASC')
            ->get();

        if ($lessonPlans->isEmpty() && $practicalExperiments->isEmpty()) {
            return [
                'status' => 'WARNING',
                'message' => 'No Lesson Plans or Practical Experiments found for this subject. Please create a lesson plan first.',
                'synced_count' => 0
            ];
        }

        // Fetch all class logs for this subject ordered chronologically
        $allLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get();

        if ($allLogs->isEmpty()) {
            return [
                'status' => 'WARNING',
                'message' => 'No attendance logs recorded yet for this subject to sync.',
                'synced_count' => 0
            ];
        }

        // Group continuous session logs by date and sub_batch
        $groupedSessions = [];
        foreach ($allLogs as $log) {
            $key = $log->date . '__' . ($log->sub_batch ?? 'Whole');
            if (!isset($groupedSessions[$key])) {
                $groupedSessions[$key] = [
                    'date' => $log->date,
                    'sub_batch' => $log->sub_batch ?? 'Whole',
                    'log_ids' => [$log->id],
                    'current_topic' => trim($log->topics_covered ?? ''),
                    'current_lp_id' => $log->lesson_plan_id
                ];
            } else {
                $groupedSessions[$key]['log_ids'][] = $log->id;
            }
        }

        $syncedCount = 0;
        $sessionIndex = 0;

        DB::beginTransaction();
        try {
            foreach ($groupedSessions as $session) {
                $topic = $session['current_topic'];
                $needsUpdate = empty($topic) || 
                               stripos($topic, 'pending') !== false || 
                               stripos($topic, 'to be updated') !== false ||
                               empty($session['current_lp_id']);

                if ($needsUpdate) {
                    $newTopic = null;
                    $newLpId = null;

                    if ($lessonPlans->isNotEmpty()) {
                        $assignedLp = $lessonPlans->get($sessionIndex);
                        if ($assignedLp) {
                            $newTopic = $assignedLp->topic_content;
                            $newLpId = $assignedLp->id;
                            $assignedLp->status = 'Completed';
                            $assignedLp->actual_date = $session['date'];
                            $assignedLp->save();
                        }
                    } elseif ($practicalExperiments->isNotEmpty()) {
                        $expIdx = $sessionIndex % $practicalExperiments->count();
                        $assignedExp = $practicalExperiments->get($expIdx);
                        if ($assignedExp) {
                            $newTopic = "Exp #{$assignedExp->experiment_no}: {$assignedExp->title}";
                            $assignedExp->conducted_date = $session['date'];
                            $assignedExp->save();
                        }
                    }

                    if ($newTopic) {
                        DB::table('class_logs_attendance')
                            ->whereIn('id', $session['log_ids'])
                            ->update([
                                'topics_covered' => $newTopic,
                                'lesson_plan_id' => $newLpId,
                                'updated_at' => now()
                            ]);

                        if (Schema::hasTable('student_attendance')) {
                            DB::table('student_attendance')
                                ->where('subject_code', $batchSubject->subject_code)
                                ->where('date', $session['date'])
                                ->update([
                                    'lesson_plan_id' => $newLpId,
                                    'updated_at' => now()
                                ]);
                        }

                        $syncedCount++;
                    }
                }

                $sessionIndex++;
            }

            DB::commit();

            return [
                'status' => 'SUCCESS',
                'message' => "Successfully synchronized {$syncedCount} class sessions with syllabus lesson plans!",
                'synced_count' => $syncedCount
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'ERROR',
                'message' => 'Sync failed: ' . $e->getMessage(),
                'synced_count' => 0
            ];
        }
    }
}
