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
use App\Models\PracticalEvaluation;
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
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
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

        // Extract Hours row (e.g. 1,2,3 or 4,5,6 per date session)
        preg_match('/Total\s*\n\s*([\d\s,]+)\n/i', $fullText, $hoursM);
        $rawHoursStr = trim($hoursM[1] ?? '');
        $hoursList = [];

        if (!empty($rawHoursStr)) {
            $cleanedHoursStr = preg_replace('/\s*,\s*/', ',', $rawHoursStr);
            preg_match_all('/([1-7](?:,[1-7])*?)(?=[1-7],|\s|\$)/', $cleanedHoursStr, $sessionHourMatches);
            if (!empty($sessionHourMatches[0])) {
                foreach ($sessionHourMatches[0] as $shStr) {
                    $parts = array_map('intval', explode(',', $shStr));
                    $validParts = array_values(array_filter($parts, fn($p) => $p >= 1 && $p <= 7));
                    if (!empty($validParts)) {
                        $hoursList[] = $validParts;
                    }
                }
            }
        }

        // Fill default hours if needed (practical sessions default to 3 hours, theory to 1 hour)
        $defaultSessionPeriods = ($batchSubject->subject_type !== 'Theory') ? [1, 2, 3] : [1];
        while (count($hoursList) < $sessionCount) {
            $hoursList[] = $defaultSessionPeriods;
        }

        // Extract Students attendance rows: Roll. No. Name [Marks...] Total
        // Line-based token extraction: Last token is Total hours, preceding $sessionCount tokens are exact session marks, and preceding tokens are Roll & Name.
        $studMatches = [];
        $lines = preg_split('/\r\n|\r|\n/', $fullText);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            if (!preg_match('/^(\d+)[\.\s]+(.*)$/', $line)) continue;
            $tokens = preg_split('/\s+/', $line);
            if (count($tokens) < $sessionCount + 3) continue;
            $lastToken = end($tokens);
            if (!is_numeric($lastToken)) continue;

            $rollNo = (int)rtrim($tokens[0], '.');
            $totalVal = (int)$lastToken;
            $marks = array_slice($tokens, -($sessionCount + 1), $sessionCount);
            $nameTokens = array_slice($tokens, 1, count($tokens) - $sessionCount - 2);
            $rawName = implode(' ', $nameTokens);

            $studMatches[] = [
                0 => $line,
                1 => (string)$rollNo,
                2 => $rawName,
                3 => implode(' ', $marks),
                'roll_no' => $rollNo,
                'name' => $rawName,
                'marks' => $marks,
                'total' => $totalVal
            ];
        }

        // Fallback to regex if line token parsing found nothing
        if (empty($studMatches)) {
            preg_match_all('/(?m)^(\d+)\.\s+([A-Za-z\s\.\'\-]+?)\t?\s+([A-Z0-9\-\s\.\/]+?)\s+(\d+)\s*$/', $fullText, $studMatches, PREG_SET_ORDER);
        }

        if (empty($studMatches)) {
            // Fallback: match without requiring leading dot or strict total
            preg_match_all('/(?m)^(\d+)[\.\s]+([A-Za-z\s\.\'\-]+?)\t?\s+([A-Z0-9\-\s\.\/]{5,})\s+(\d+)\s*$/', $fullText, $studMatches, PREG_SET_ORDER);
        }

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
        $matchedRegNos = [];
        foreach ($studMatches as $sm) {
            $rollNo = (int)$sm[1];
            $rawName = trim($sm[2]);
            $cleanName = strtoupper(preg_replace('/[^A-Z]/', '', $rawName));
            $marks = is_array($sm['marks'] ?? null) ? $sm['marks'] : preg_split('/\s+/', trim($sm[3]));

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
            $matchedRegNos[] = $regNo;

            for ($i = 0; $i < $sessionCount; $i++) {
                $mark = strtoupper(trim($marks[$i] ?? 'A'));
                if ($mark === 'A' || $mark === '0' || $mark === '-' || $mark === 'AB') {
                    $sessionAttendance[$i]['absent'][] = $regNo;
                } else {
                    $sessionAttendance[$i]['present'][] = $regNo;
                }
            }
        }

        // Safety fallback: Ensure any enrolled classroom student not explicitly in the absent list is counted present
        foreach ($classroomStudents as $cs) {
            $rNo = $cs->reg_no;
            for ($i = 0; $i < $sessionCount; $i++) {
                if (!in_array($rNo, $sessionAttendance[$i]['absent']) && !in_array($rNo, $sessionAttendance[$i]['present'])) {
                    $sessionAttendance[$i]['present'][] = $rNo;
                }
            }
        }
        $matchedStudentsCount = max($matchedStudentsCount, $classroomStudents->count());

        // Fetch lesson plans & practical experiments
        $lessonPlans = LessonPlan::where('batch_subject_id', $batchSubject->id)
            ->orderBy('day_no', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $practicalExperiments = PracticalExperiment::where('batch_subject_id', $batchSubject->id)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED), experiment_no ASC')
            ->get();

        $labBatches = DB::table('r26_student_lab_batches')
            ->where('batch_subject_id', $batchSubject->id)
            ->pluck('lab_batch', 'reg_no')
            ->toArray();
        $isSplitLab = ($batchSubject->subject_type !== 'Theory') && ($batchSubject->lab_batch_mode === 'split' || !empty($batchSubject->lab_batch_cutoff) || !empty($labBatches));

        $importedSessions = 0;
        $totalHoursLogged = 0;
        $mappedLpCount = 0;
        $usedLpIds = [];
        $lpSeqIndex = 0;

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $sessionCount; $i++) {
                $date = $sessionDates[$i];
                $periods = $hoursList[$i] ?? [1];
                $presentRegNos = array_values(array_unique($sessionAttendance[$i]['present']));
                $absentRegNos = array_values(array_unique($sessionAttendance[$i]['absent']));

                $sessionSubBatch = $subBatch;
                if ($isSplitLab && $subBatch === 'Whole') {
                    $b1PresCount = 0;
                    $b2PresCount = 0;
                    foreach ($presentRegNos as $rNo) {
                        $b = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && ($st = $studentsByRoll[$rNo] ?? null) ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? '1' : '2') : null);
                        if ($b === '1') $b1PresCount++;
                        elseif ($b === '2') $b2PresCount++;
                    }
                    if ($b1PresCount > 0 && $b2PresCount === 0) {
                        $sessionSubBatch = '1';
                    } elseif ($b2PresCount > 0 && $b1PresCount === 0) {
                        $sessionSubBatch = '2';
                    } elseif ($b1PresCount >= 10 && $b2PresCount < 5) {
                        $sessionSubBatch = '1';
                    } elseif ($b2PresCount >= 10 && $b1PresCount < 5) {
                        $sessionSubBatch = '2';
                    } else {
                        $sessionSubBatch = 'Whole';
                    }
                }

                // If this is a split lab session (Batch 1 or Batch 2), scope student present/absent lists strictly to this batch
                if ($isSplitLab && ($sessionSubBatch === '1' || $sessionSubBatch === '2')) {
                    $presentRegNos = array_values(array_filter($presentRegNos, function($rNo) use ($labBatches, $sessionSubBatch, $studentsByRoll, $batchSubject) {
                        $stBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && ($st = $studentsByRoll[$rNo] ?? null) ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? '1' : '2') : null);
                        return $stBatch === $sessionSubBatch;
                    }));
                    $absentRegNos = array_values(array_filter($absentRegNos, function($rNo) use ($labBatches, $sessionSubBatch, $studentsByRoll, $batchSubject) {
                        $stBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && ($st = $studentsByRoll[$rNo] ?? null) ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? '1' : '2') : null);
                        return $stBatch === $sessionSubBatch;
                    }));
                }

                // Create distinct period logs for exact hour calculations
                foreach ($periods as $period) {
                    $period = (int)$period;

                    // Topic assignment per period hour
                    $assignedLpId = null;
                    $assignedTopic = null;

                    if ($autoFillLp) {
                        $assignedLp = $lessonPlans->get($lpSeqIndex) ?: $lessonPlans->first(function ($lp) use ($usedLpIds) {
                            return !in_array($lp->id, $usedLpIds);
                        });

                        if ($assignedLp) {
                            $assignedTopic = $assignedLp->topic_content;
                            $assignedLpId = $assignedLp->id;
                            $usedLpIds[] = $assignedLp->id;
                            $assignedLp->status = 'Completed';
                            $assignedLp->actual_date = $date;
                            $assignedLp->actual_hours = 1;
                            $assignedLp->save();
                            $mappedLpCount++;
                            $lpSeqIndex++;
                        } elseif ($practicalExperiments->isNotEmpty()) {
                            $expIndex = $totalHoursLogged % $practicalExperiments->count();
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

                    $existingLog = DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $batchSubject->id)
                        ->where('date', $date)
                        ->where('period', $period)
                        ->where(function($q) use ($sessionSubBatch) {
                            $q->where('sub_batch', $sessionSubBatch)->orWhere('sub_batch', 'Whole');
                        })
                        ->first();

                    if ($existingLog) {
                        DB::table('class_logs_attendance')
                            ->where('id', $existingLog->id)
                            ->update([
                                'lesson_plan_id' => $assignedLpId ?: $existingLog->lesson_plan_id,
                                'topics_covered' => $assignedTopic ?: $existingLog->topics_covered,
                                'present_students' => json_encode($presentRegNos),
                                'absent_students' => json_encode($absentRegNos),
                                'sub_batch' => $sessionSubBatch,
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
                            'sub_batch' => $sessionSubBatch,
                            'recorded_by' => $recordedBy,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $totalHoursLogged++;
                }

                // Sync student_attendance table once per session date
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
                                'sub_batch' => $sessionSubBatch,
                                'lesson_plan_id' => $assignedLpId,
                                'updated_at' => now(),
                            ]
                        );
                    }

                    // For absent students: In split lab sessions, only record absent if student was scheduled for this batch!
                    $absentsToRecord = $absentRegNos;
                    if ($isSplitLab && ($sessionSubBatch === '1' || $sessionSubBatch === '2')) {
                        // Safely clean up any erroneous absent records for students of the other batch on this date
                        $otherBatchStudents = array_filter($classroomStudents->pluck('reg_no')->toArray(), function($rNo) use ($labBatches, $sessionSubBatch, $studentsByRoll, $batchSubject) {
                            $stBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && ($st = $studentsByRoll[$rNo] ?? null) ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? '1' : '2') : null);
                            return $stBatch !== null && $stBatch !== $sessionSubBatch;
                        });
                        if (!empty($otherBatchStudents)) {
                            DB::table('student_attendance')
                                ->where('subject_code', $batchSubject->subject_code)
                                ->where('date', $date)
                                ->whereIn('reg_no', $otherBatchStudents)
                                ->where('status', 'Absent')
                                ->delete();
                        }
                    }

                    foreach ($absentsToRecord as $rNo) {
                        DB::table('student_attendance')->updateOrInsert(
                            [
                                'reg_no' => $rNo,
                                'subject_code' => $batchSubject->subject_code,
                                'date' => $date,
                            ],
                            [
                                'status' => 'Absent',
                                'sub_batch' => $sessionSubBatch,
                                'lesson_plan_id' => $assignedLpId,
                                'updated_at' => now(),
                            ]
                        );
                    }
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

            // For practical courses in Revision 2021, synchronize official attendance marks (out of 15) to PracticalEvaluation
            if ($batchSubject->subject_type === 'Practical / Lab') {
                $assessorMobile = Session::get('userId') ?: ($staffMobile ?? null);
                if (!$assessorMobile) {
                    $assessorMobile = DB::table('subject_staff_assignments')
                        ->where('batch_subject_id', $batchSubject->id)
                        ->value('staff_mobile_no');
                }

                foreach ($classroomStudents as $cs) {
                    $rNo = $cs->reg_no;
                    $labBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $cs->roll_no !== null ? ((int)$cs->roll_no <= (int)$batchSubject->lab_batch_cutoff ? '1' : '2') : null);

                    $stOfficial = DB::table('student_attendance')
                        ->where('reg_no', $rNo)
                        ->where('subject_code', $batchSubject->subject_code)
                        ->get();

                    if ($stOfficial->isNotEmpty()) {
                        if ($batchSubject->lab_batch_mode === 'split' || !empty($labBatch)) {
                            $stFiltered = $stOfficial->filter(function($att) use ($labBatch) {
                                $sb = (string)($att->sub_batch ?? 'Whole');
                                if ($sb === $labBatch || $sb === 'Whole') return true;
                                return in_array($att->status, ['Present', 'Late']);
                            });
                            if ($stFiltered->isNotEmpty()) {
                                $stOfficial = $stFiltered;
                            }
                        }
                        $offTot = $stOfficial->count();
                        $offPres = $stOfficial->whereIn('status', ['Present', 'Late'])->count();
                        $pct = ($offTot > 0) ? round(($offPres / $offTot) * 100, 2) : 100.0;
                        $attMark = \App\Services\AttainmentService::calculateR21AttendanceMark($pct, 15.0);

                        $evalRecord = PracticalEvaluation::where('batch_subject_id', $batchSubject->id)
                            ->where('reg_no', $rNo)
                            ->first();

                        if ($evalRecord) {
                            $evalRecord->attendance_marks = $attMark;
                            $evalRecord->updated_at = now();
                            $evalRecord->save();
                        } else {
                            PracticalEvaluation::create([
                                'batch_subject_id' => $batchSubject->id,
                                'reg_no' => $rNo,
                                'assessor_mobile_no' => $assessorMobile ?: '9000000000',
                                'attendance_marks' => $attMark,
                                'micro_project' => 0.00,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

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

        $labBatches = DB::table('r26_student_lab_batches')
            ->where('batch_subject_id', $batchSubject->id)
            ->pluck('lab_batch', 'reg_no')
            ->toArray();
        $isSplitLab = ($batchSubject->subject_type !== 'Theory') && ($batchSubject->lab_batch_mode === 'split' || !empty($batchSubject->lab_batch_cutoff) || !empty($labBatches));

        if ($isSplitLab && ($subBatch === '1' || $subBatch === '2')) {
            $studentRegNos = $classroomStudents->filter(function($st) use ($labBatches, $subBatch, $batchSubject) {
                $stBatch = $labBatches[$st->reg_no] ?? ($batchSubject->lab_batch_cutoff && $st->roll_no !== null ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? '1' : '2') : null);
                return $stBatch === $subBatch;
            })->pluck('reg_no')->toArray();
        } else {
            $studentRegNos = $classroomStudents->pluck('reg_no')->toArray();
        }

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

            // For practical courses in Revision 2021, synchronize official attendance marks (out of 15) to PracticalEvaluation
            if ($batchSubject->subject_type === 'Practical / Lab') {
                $assessorMobile = Session::get('userId') ?: ($staffMobile ?? null);
                if (!$assessorMobile) {
                    $assessorMobile = DB::table('subject_staff_assignments')
                        ->where('batch_subject_id', $batchSubject->id)
                        ->value('staff_mobile_no');
                }

                foreach ($classroomStudents as $cs) {
                    $rNo = $cs->reg_no;
                    $labBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $cs->roll_no !== null ? ((int)$cs->roll_no <= (int)$batchSubject->lab_batch_cutoff ? '1' : '2') : null);

                    $stOfficial = DB::table('student_attendance')
                        ->where('reg_no', $rNo)
                        ->where('subject_code', $batchSubject->subject_code)
                        ->get();

                    if ($stOfficial->isNotEmpty()) {
                        if ($batchSubject->lab_batch_mode === 'split' || !empty($labBatch)) {
                            $stFiltered = $stOfficial->filter(function($att) use ($labBatch) {
                                $sb = (string)($att->sub_batch ?? 'Whole');
                                if ($sb === $labBatch || $sb === 'Whole') return true;
                                return in_array($att->status, ['Present', 'Late']);
                            });
                            if ($stFiltered->isNotEmpty()) {
                                $stOfficial = $stFiltered;
                            }
                        }
                        $offTot = $stOfficial->count();
                        $offPres = $stOfficial->whereIn('status', ['Present', 'Late'])->count();
                        $pct = ($offTot > 0) ? round(($offPres / $offTot) * 100, 2) : 100.0;
                        $attMark = \App\Services\AttainmentService::calculateR21AttendanceMark($pct, 15.0);

                        $evalRecord = PracticalEvaluation::where('batch_subject_id', $batchSubject->id)
                            ->where('reg_no', $rNo)
                            ->first();

                        if ($evalRecord) {
                            $evalRecord->attendance_marks = $attMark;
                            $evalRecord->updated_at = now();
                            $evalRecord->save();
                        } else {
                            PracticalEvaluation::create([
                                'batch_subject_id' => $batchSubject->id,
                                'reg_no' => $rNo,
                                'assessor_mobile_no' => $assessorMobile ?: '9000000000',
                                'attendance_marks' => $attMark,
                                'micro_project' => 0.00,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
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
            ->orderBy('day_no', 'asc')
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

        $syncedCount = 0;

        DB::beginTransaction();
        try {
            if ($lessonPlans->isNotEmpty()) {
                // For theory subjects, each distinct log (hour) maps to a distinct sequential lesson plan
                foreach ($allLogs as $idx => $log) {
                    $assignedLp = $lessonPlans->get($idx);
                    if ($assignedLp) {
                        $newTopic = $assignedLp->topic_content;
                        $newLpId = $assignedLp->id;

                        $assignedLp->status = 'Completed';
                        $assignedLp->actual_date = $log->date;
                        $assignedLp->actual_hours = 1;
                        $assignedLp->save();

                        DB::table('class_logs_attendance')
                            ->where('id', $log->id)
                            ->update([
                                'topics_covered' => $newTopic,
                                'lesson_plan_id' => $newLpId,
                                'updated_at' => now()
                            ]);

                        if (Schema::hasTable('student_attendance')) {
                            DB::table('student_attendance')
                                ->where('subject_code', $batchSubject->subject_code)
                                ->where('date', $log->date)
                                ->update([
                                    'lesson_plan_id' => $newLpId,
                                    'updated_at' => now()
                                ]);
                        }

                        $syncedCount++;
                    }
                }

                // Reset remaining lesson plans to Pending
                for ($p = count($allLogs); $p < $lessonPlans->count(); $p++) {
                    $pendingLp = $lessonPlans->get($p);
                    if ($pendingLp) {
                        $pendingLp->status = 'Pending';
                        $pendingLp->actual_date = null;
                        $pendingLp->actual_hours = null;
                        $pendingLp->save();
                    }
                }
            } else {
                // Practical experiments grouping by date and sub_batch
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

                $sessionIndex = 0;
                foreach ($groupedSessions as $session) {
                    $expIdx = $sessionIndex % $practicalExperiments->count();
                    $assignedExp = $practicalExperiments->get($expIdx);
                    if ($assignedExp) {
                        $newTopic = "Exp #{$assignedExp->experiment_no}: {$assignedExp->title}";
                        $assignedExp->conducted_date = $session['date'];
                        $assignedExp->save();

                        DB::table('class_logs_attendance')
                            ->whereIn('id', $session['log_ids'])
                            ->update([
                                'topics_covered' => $newTopic,
                                'lesson_plan_id' => null,
                                'updated_at' => now()
                            ]);

                        $syncedCount++;
                    }
                    $sessionIndex++;
                }
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
