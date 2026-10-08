<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use App\Models\BatchSubject;
use App\Models\Student;
use App\Models\StaffProfile;
use App\Models\TutorSpecialAttendance;
use Smalot\PdfParser\Parser as PdfParser;

class TutorSpecialAttendanceController extends Controller
{
    /**
     * Resolve the supervised classroom for the current authenticated tutor/staff.
     */
    public static function resolveTutorClassroom(Request $request)
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$staffMobile) {
            return null;
        }

        $staff = StaffProfile::where('mobile_no', $staffMobile)
            ->orWhere('email', $staffMobile)
            ->orWhere('id', $staffMobile)
            ->first();
        if ($staff && $staff->mobile_no) {
            $staffMobile = $staff->mobile_no;
        }

        $cleanMobile = preg_replace('/[^0-9]/', '', $staffMobile);

        $classes1 = DB::table('class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
            $q->where('tutor_mobile_no', $staffMobile)->orWhere('mentor_mobile_no', $staffMobile);
            if ($cleanMobile) {
                $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
            }
        })->get();

        $classes2 = collect();
        if (Schema::hasTable('r26_class_management')) {
            $classes2 = DB::table('r26_class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
                $q->where('tutor_mobile_no', $staffMobile)->orWhere('mentor_mobile_no', $staffMobile);
                if ($cleanMobile) {
                    $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
                }
            })->get();
        }

        $allClasses = $classes1->concat($classes2);

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

        return $classroom;
    }

    /**
     * Detect whether the classroom falls under Revision 2021 or Revision 2026.
     */
    public static function detectScheme($classroom)
    {
        if (!$classroom) {
            return 'R21';
        }

        $classroomId = $classroom->classroom_id ?? '';
        $batchYear = (int)($classroom->batch_year ?? 0);

        if ($batchYear >= 2026 || str_contains($classroomId, '2026')) {
            return 'R26';
        }

        if (Schema::hasTable('r26_class_management')) {
            $isR26 = DB::table('r26_class_management')->where('classroom_id', $classroomId)->exists();
            if ($isR26) return 'R26';
        }

        return 'R21';
    }

    /**
     * Evaluate SBTE Exam Eligibility & Condonation based on scheme rules.
     * Revision 2021: Clause 10 (>=75% Eligible, 65-74.9% Condonation, <65% Detained)
     * Revision 2026: Rule 7 (>=75% Eligible, with 2% Menstrual / 5% PWD relaxation; 60-74.9% Regular Condonation; 50-59.9% Special Condonation; <50% Detained)
     */
    public static function evaluateEligibility($percentage, $scheme = 'R21', $relaxations = [])
    {
        $percentage = (float)$percentage;

        if ($scheme === 'R26') {
            $threshold = 75.0;
            $relaxationLabels = [];

            if (in_array('Menstrual Leave', $relaxations)) {
                $threshold = min($threshold, 73.0);
                $relaxationLabels[] = 'Menstrual Leave (-2%)';
            }
            if (in_array('PWD', $relaxations)) {
                $threshold = min($threshold, 70.0);
                $relaxationLabels[] = 'PWD (-5%)';
            }

            if ($percentage >= $threshold) {
                return [
                    'status' => 'Eligible',
                    'rule' => 'Rule 7.1',
                    'badge' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30',
                    'text_color' => 'text-emerald-400',
                    'required_pct' => $threshold,
                    'shortage_pct' => 0.0,
                    'relaxations' => $relaxationLabels,
                    'decision' => 'Eligible to appear for ESE (Rule 7.1).'
                ];
            } elseif ($percentage >= 60.0) {
                return [
                    'status' => 'Condonation',
                    'rule' => 'Rule 7.3 (Regular)',
                    'badge' => 'bg-amber-500/10 text-amber-400 border border-amber-500/30',
                    'text_color' => 'text-amber-400',
                    'required_pct' => $threshold,
                    'shortage_pct' => round($threshold - $percentage, 1),
                    'relaxations' => $relaxationLabels,
                    'decision' => 'Requires Regular Condonation approval by Principal / RDTE / DTE (Rule 7.3).'
                ];
            } elseif ($percentage >= 50.0) {
                return [
                    'status' => 'Special Condonation',
                    'rule' => 'Rule 7.3 (Special DTE)',
                    'badge' => 'bg-purple-500/10 text-purple-400 border border-purple-500/30',
                    'text_color' => 'text-purple-400',
                    'required_pct' => $threshold,
                    'shortage_pct' => round($threshold - $percentage, 1),
                    'relaxations' => $relaxationLabels,
                    'decision' => 'Requires Special Condonation by DTE (One-time, passed >=50% courses, Rs. 1500 fee).'
                ];
            } else {
                return [
                    'status' => 'Detained',
                    'rule' => 'Rule 7.3 (Detained)',
                    'badge' => 'bg-rose-500/10 text-rose-400 border border-rose-500/30',
                    'text_color' => 'text-rose-400',
                    'required_pct' => $threshold,
                    'shortage_pct' => round($threshold - $percentage, 1),
                    'relaxations' => $relaxationLabels,
                    'decision' => 'Severe Shortage (<50%). Not eligible for condonation. Must repeat semester.'
                ];
            }
        }

        // Revision 2021 (Default)
        if ($percentage >= 75.0) {
            return [
                'status' => 'Eligible',
                'rule' => 'Clause 10.1',
                'badge' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30',
                'text_color' => 'text-emerald-400',
                'required_pct' => 75.0,
                'shortage_pct' => 0.0,
                'relaxations' => [],
                'decision' => 'Eligible to appear for ESE (Clause 10.1).'
            ];
        } elseif ($percentage >= 65.0) {
            return [
                'status' => 'Condonation',
                'rule' => 'Clause 10.2',
                'badge' => 'bg-amber-500/10 text-amber-400 border border-amber-500/30',
                'text_color' => 'text-amber-400',
                'required_pct' => 75.0,
                'shortage_pct' => round(75.0 - $percentage, 1),
                'relaxations' => [],
                'decision' => 'Condonation Required (Clause 10.2). Condonable by Principal on genuine grounds.'
            ];
        } else {
            return [
                'status' => 'Detained',
                'rule' => 'Clause 10.3',
                'badge' => 'bg-rose-500/10 text-rose-400 border border-rose-500/30',
                'text_color' => 'text-rose-400',
                'required_pct' => 75.0,
                'shortage_pct' => round(75.0 - $percentage, 1),
                'relaxations' => [],
                'decision' => 'Severe Shortage (<65%). Strictly non-condonable. Must repeat semester.'
            ];
        }
    }

    /**
     * API: Get Exam Eligibility & Condonation Register (Table 2 data).
     * Incorporates original attendance + special attendance / duty leaves.
     */
    public function getCondonationRegister(Request $request)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $classroom = self::resolveTutorClassroom($request);
        if (!$classroom) {
            return response()->json(['status' => 'ERROR', 'message' => 'No classroom assigned as advisor/tutor/mentor to your profile.']);
        }

        $classroomId = $classroom->classroom_id;
        $scheme = self::detectScheme($classroom);

        // Fetch primary attendance calculations from AttendanceController without touching existing data
        $attCtrl = new AttendanceController();
        $baseRes = $attCtrl->getConsolidatedTutorAttendance($request);
        $baseData = $baseRes->getData(true);

        if (($baseData['status'] ?? '') !== 'SUCCESS') {
            return response()->json($baseData);
        }

        $baseStudents = $baseData['students'] ?? [];

        // Fetch all special attendance records for this classroom
        $specialRecords = TutorSpecialAttendance::where('classroom_id', $classroomId)
            ->orderBy('date', 'desc')
            ->get();
        $specialGrouped = $specialRecords->groupBy('reg_no');

        $studentsRows = [];
        $totalOriginalEligible = 0;
        $totalRevisedEligible = 0;
        $totalCondonation = 0;
        $totalSpecialCondonation = 0;
        $totalDetained = 0;
        $totalSpecialHoursCredited = 0;
        $promotedCount = 0;

        foreach ($baseStudents as $st) {
            $regNo = $st['reg_no'];
            $origConducted = (int)($st['total_conducted'] ?? 0);
            $origAttended = (int)($st['total_attended'] ?? 0);
            $origPct = (float)($st['overall_percentage'] ?? 0.0);
            $origStatus = $st['status'] ?? 'Detained';

            if ($origStatus === 'Eligible') {
                $totalOriginalEligible++;
            }

            // Student's special attendance records
            $stSpecial = $specialGrouped->get($regNo, collect());
            $specialHours = (float)$stSpecial->sum('hours');
            $totalSpecialHoursCredited += $specialHours;

            // Extract relaxations if any (e.g. Menstrual Leave, PWD)
            $relaxations = $stSpecial->pluck('category')->filter(fn($c) => in_array($c, ['Menstrual Leave', 'PWD']))->unique()->toArray();

            // Revised calculations for Exam Eligibility only (CIA marks untouched)
            $revisedAttended = min($origConducted, $origAttended + $specialHours);
            $revisedPct = $origConducted > 0 ? round(($revisedAttended / $origConducted) * 100, 1) : 0.0;

            $revisedEvaluation = self::evaluateEligibility($revisedPct, $scheme, $relaxations);
            $revisedStatus = $revisedEvaluation['status'];

            if ($revisedStatus === 'Eligible') {
                $totalRevisedEligible++;
                if ($origStatus !== 'Eligible') {
                    $promotedCount++;
                }
            } elseif ($revisedStatus === 'Condonation') {
                $totalCondonation++;
            } elseif ($revisedStatus === 'Special Condonation') {
                $totalSpecialCondonation++;
            } else {
                $totalDetained++;
            }

            // Categories breakdown
            $categoriesBreakdown = [];
            foreach ($stSpecial as $rec) {
                $cat = $rec->category ?: 'Duty Leave';
                $categoriesBreakdown[$cat] = ($categoriesBreakdown[$cat] ?? 0) + (float)$rec->hours;
            }

            $studentsRows[] = [
                'roll_no' => $st['roll_no'],
                'reg_no' => $regNo,
                'sbte_reg_no' => $st['sbte_reg_no'],
                'name' => $st['name'],
                'phone' => $st['phone'],
                'original' => [
                    'conducted' => $origConducted,
                    'attended' => $origAttended,
                    'missed' => max(0, $origConducted - $origAttended),
                    'percentage' => $origPct,
                    'status' => $origStatus,
                ],
                'special_attendance' => [
                    'hours' => $specialHours,
                    'records_count' => $stSpecial->count(),
                    'categories' => $categoriesBreakdown,
                    'records' => $stSpecial->map(fn($r) => [
                        'id' => $r->id,
                        'date' => $r->date ? $r->date->format('Y-m-d') : null,
                        'formatted_date' => $r->date ? $r->date->format('d/m/Y') : 'Semester Credit',
                        'hours' => $r->hours,
                        'category' => $r->category,
                        'reason' => $r->reason,
                        'source' => $r->source,
                    ]),
                ],
                'revised' => [
                    'conducted' => $origConducted,
                    'attended' => $revisedAttended,
                    'missed' => max(0, $origConducted - $revisedAttended),
                    'percentage' => $revisedPct,
                    'status' => $revisedStatus,
                    'rule' => $revisedEvaluation['rule'],
                    'badge' => $revisedEvaluation['badge'],
                    'decision' => $revisedEvaluation['decision'],
                    'shortage_pct' => $revisedEvaluation['shortage_pct'],
                    'promoted' => ($origStatus !== 'Eligible' && $revisedStatus === 'Eligible'),
                ],
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'scheme' => $scheme,
            'regulation_title' => $scheme === 'R26' ? 'SBTE Diploma Regulation 2026 — Rule 7' : 'SBTE Diploma Regulation 2021 — Clause 10',
            'classroom' => [
                'id' => $classroom->classroom_id,
                'branch' => $classroom->branch ?? '',
                'semester' => $classroom->current_semester ?? '',
                'batch_year' => $classroom->batch_year ?? '',
            ],
            'period' => $baseData['period'] ?? ['label' => 'Full Semester'],
            'summary' => [
                'total_students' => count($baseStudents),
                'original_eligible_count' => $totalOriginalEligible,
                'revised_eligible_count' => $totalRevisedEligible,
                'promoted_count' => $promotedCount,
                'condonation_count' => $totalCondonation,
                'special_condonation_count' => $totalSpecialCondonation,
                'detained_count' => $totalDetained,
                'total_special_hours' => $totalSpecialHoursCredited,
            ],
            'students' => $studentsRows,
        ]);
    }

    /**
     * API: Save Special Attendance / Duty Leave entry manually (Single or Multi-student).
     */
    public function saveSpecialAttendance(Request $request)
    {
        $role = Session::get('userRole');
        $staffMobile = Session::get('userId');

        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $classroom = self::resolveTutorClassroom($request);
        if (!$classroom) {
            return response()->json(['status' => 'ERROR', 'message' => 'Supervised classroom not found.'], 422);
        }

        $request->validate([
            'reg_no' => 'required',
            'hours' => 'required|numeric|min:0.5|max:150',
            'category' => 'required|string',
            'reason' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $regNos = is_array($request->reg_no) ? $request->reg_no : [$request->reg_no];
        $hours = (float)$request->hours;
        $category = trim($request->category);
        $reason = trim($request->input('reason', ''));
        $date = $request->date ?: null;

        $createdCount = 0;
        foreach ($regNos as $rNo) {
            TutorSpecialAttendance::create([
                'classroom_id' => $classroom->classroom_id,
                'reg_no' => trim($rNo),
                'date' => $date,
                'hours' => $hours,
                'category' => $category,
                'reason' => $reason,
                'source' => 'MANUAL',
                'recorded_by' => $staffMobile ?: 'Class Tutor',
            ]);
            $createdCount++;
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Successfully credited {$hours} duty/special hours for {$createdCount} student(s) under '{$category}'!",
        ]);
    }

    /**
     * API: Delete / Revoke a special attendance entry.
     */
    public function deleteSpecialAttendance(Request $request, $id)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $entry = TutorSpecialAttendance::find($id);
        if (!$entry) {
            return response()->json(['status' => 'ERROR', 'message' => 'Special attendance record not found.'], 404);
        }

        $entry->delete();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Special attendance record deleted successfully.',
        ]);
    }

    /**
     * API: Upload & parse TEAMS Attendance Log PDF.
     * The TEAMS tutor attendance log contains dates and hours without course details.
     */
    public function uploadTeamsAttendanceLog(Request $request)
    {
        $role = Session::get('userRole');
        $staffMobile = Session::get('userId');

        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $classroom = self::resolveTutorClassroom($request);
        if (!$classroom) {
            return response()->json(['status' => 'ERROR', 'message' => 'Supervised classroom not found.'], 422);
        }

        $request->validate([
            'file' => 'required|file|max:20480',
            'default_category' => 'nullable|string',
            'custom_reason' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'pdf') {
            return response()->json(['status' => 'ERROR', 'message' => 'Please upload a valid PDF file (.pdf).'], 422);
        }

        $defaultCategory = $request->input('default_category') ?: 'Duty Leave';
        $customReason = $request->input('custom_reason') ?: 'TEAMS Attendance Log Import';

        $fullText = '';
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($file->getRealPath());
            foreach ($pdf->getPages() as $page) {
                $fullText .= $page->getText() . "\n";
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to parse PDF text: ' . $e->getMessage()], 422);
        }

        if (empty(trim($fullText))) {
            return response()->json(['status' => 'ERROR', 'message' => 'Uploaded PDF contains no extractable text.'], 422);
        }

        // Fetch classroom students for matching
        $classroomStudents = Student::getClassroomStudentsQuery($classroom->classroom_id)->get();
        $studentsByRoll = [];
        $studentsByReg = [];
        $studentsByName = [];

        foreach ($classroomStudents as $cs) {
            if ($cs->roll_no !== null) {
                $studentsByRoll[(int)$cs->roll_no] = $cs;
            }
            if (!empty($cs->sbte_reg_no)) {
                $studentsByReg[strtoupper(trim($cs->sbte_reg_no))] = $cs;
            }
            $studentsByReg[strtoupper(trim($cs->reg_no))] = $cs;
            $cleanName = strtoupper(preg_replace('/[^A-Z]/', '', $cs->name));
            $studentsByName[$cleanName] = $cs;
        }

        // Detect Dates (e.g. DD/MM/YYYY or DD-MM-YYYY or DD/MM)
        preg_match_all('/\b(\d{2}[\/\-]\d{2}(?:[\/\-]\d{4})?)\b/', $fullText, $dateMatches);
        $detectedDates = array_values(array_unique($dateMatches[0] ?? []));

        // Line-by-line student parsing
        $lines = preg_split('/\r\n|\r|\n/', $fullText);
        $parsedEntries = [];
        $savedCount = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Pattern A: Roll Number + Name + Tokens... + Total
            if (preg_match('/^(\d+)[\.\s]+([A-Za-z\s\.\'\-]+?)(?:\s+([A-Z0-9\-\s\.\/]+))?$/', $line, $lm)) {
                $rollNo = (int)$lm[1];
                $name = trim($lm[2]);
                $matchedStudent = $studentsByRoll[$rollNo] ?? null;

                if (!$matchedStudent) {
                    $cleanName = strtoupper(preg_replace('/[^A-Z]/', '', $name));
                    $matchedStudent = $studentsByName[$cleanName] ?? null;
                }

                if ($matchedStudent) {
                    // Extract numeric hours from line
                    $tokens = preg_split('/\s+/', $line);
                    $numericTokens = array_filter($tokens, fn($t) => is_numeric($t));
                    $hoursValue = 0;

                    // If last token is total
                    if (!empty($numericTokens)) {
                        $lastNum = (float)end($numericTokens);
                        // Check if line mentions duty leaves or special hours
                        $hoursValue = $lastNum;
                    }

                    if ($hoursValue > 0) {
                        $parsedEntries[] = [
                            'reg_no' => $matchedStudent->reg_no,
                            'name' => $matchedStudent->name,
                            'roll_no' => $matchedStudent->roll_no,
                            'hours' => $hoursValue,
                        ];
                    }
                }
            }
        }

        // If line token matching found specific records, persist them
        if (!empty($parsedEntries)) {
            DB::beginTransaction();
            try {
                foreach ($parsedEntries as $pe) {
                    TutorSpecialAttendance::create([
                        'classroom_id' => $classroom->classroom_id,
                        'reg_no' => $pe['reg_no'],
                        'date' => null,
                        'hours' => $pe['hours'],
                        'category' => $defaultCategory,
                        'reason' => $customReason,
                        'source' => 'TEAMS_UPLOAD',
                        'recorded_by' => $staffMobile ?: 'Class Tutor',
                    ]);
                    $savedCount++;
                }
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollBack();
                return response()->json(['status' => 'ERROR', 'message' => 'Database error while saving parsed records: ' . $ex->getMessage()], 500);
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => $savedCount > 0
                ? "Successfully processed TEAMS attendance log! Credited special attendance for {$savedCount} student(s)."
                : "PDF parsed successfully. Found " . count($detectedDates) . " dates. If automatic rows did not match, please verify or use manual duty leave entry.",
            'detected_dates' => array_slice($detectedDates, 0, 15),
            'matched_students_count' => $savedCount,
            'entries' => $parsedEntries,
        ]);
    }

    /**
     * Printable A4 SBTE Attendance & Condonation Certificate with Chronological Absent Dates and Hours.
     * Complies with SBTE Kerala Regulation 2021 Clause 10.2 & Regulation 2026 Rule 7.3.
     */
    public function printCondonationCertificate(Request $request)
    {
        $regNo = $request->input('reg_no') ?: $request->input('student');
        if (!$regNo) {
            abort(404, 'Student registration number is required.');
        }

        $classroom = self::resolveTutorClassroom($request);
        if (!$classroom) {
            abort(404, 'Classroom not found.');
        }

        $classroomId = $classroom->classroom_id;
        $scheme = self::detectScheme($classroom);

        $student = Student::where('reg_no', $regNo)
            ->orWhere('sbte_reg_no', $regNo)
            ->first();
        if (!$student) {
            abort(404, "Student '{$regNo}' not found.");
        }

        // 1. Fetch all BatchSubjects for this classroom
        $subjectsQuery = BatchSubject::where('classroom_id', $classroomId);
        if (!empty($classroom->current_semester)) {
            $subjectsQuery->where('semester', (int)$classroom->current_semester);
        }
        $subjects = $subjectsQuery->orderBy('subject_code', 'asc')->get();
        $subjectIds = $subjects->pluck('id');
        $subjectCodes = $subjects->pluck('subject_code')->filter()->unique();

        // 2. Query class logs & collect absent sessions
        $classLogs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get();

        $subjectsById = $subjects->keyBy('id');

        $absentDatesMap = [];
        $totalConductedHours = 0;
        $totalAttendedHours = 0;

        foreach ($classLogs as $log) {
            $totalConductedHours++;
            $presentArr = json_decode($log->present_students ?? '[]', true) ?: [];
            $absentArr = json_decode($log->absent_students ?? '[]', true) ?: [];

            $isAbsent = in_array($student->reg_no, $absentArr) || (!in_array($student->reg_no, $presentArr) && !empty($absentArr));

            if ($isAbsent) {
                $d = $log->date;
                if (!isset($absentDatesMap[$d])) {
                    $absentDatesMap[$d] = [
                        'date' => $d,
                        'formatted_date' => date('d-m-Y', strtotime($d)),
                        'day' => date('l', strtotime($d)),
                        'periods' => [],
                        'hours_count' => 0,
                        'subjects' => [],
                    ];
                }
                $absentDatesMap[$d]['periods'][] = $log->period;
                $absentDatesMap[$d]['hours_count']++;
                $subj = $subjectsById->get($log->batch_subject_id);
                if ($subj && !in_array($subj->subject_code, $absentDatesMap[$d]['subjects'])) {
                    $absentDatesMap[$d]['subjects'][] = $subj->subject_code;
                }
            } else {
                $totalAttendedHours++;
            }
        }

        // Cross-reference with leave_records if student submitted leave requests
        $studentLeaves = collect();
        if (Schema::hasTable('leave_records')) {
            $studentLeaves = DB::table('leave_records')
                ->where('reg_no', $student->reg_no)
                ->get()
                ->keyBy('leave_date');
        }

        // Cross-reference with tutor_special_attendances for duty leaves
        $specialRecords = TutorSpecialAttendance::where('classroom_id', $classroomId)
            ->where('reg_no', $student->reg_no)
            ->get();
        $specialHoursCredited = (float)$specialRecords->sum('hours');
        $specialByDate = $specialRecords->filter(fn($r) => !empty($r->date))->keyBy(fn($r) => $r->date->format('Y-m-d'));

        // Enrich absent dates list
        $chronologicalAbsences = [];
        $totalMissedHours = 0;

        foreach ($absentDatesMap as $d => $item) {
            $totalMissedHours += $item['hours_count'];

            $leave = $studentLeaves->get($d);
            $special = $specialByDate->get($d);

            $reason = 'Uninformed Absence';
            $docSubmitted = 'No';
            $category = 'Absent';

            if ($special) {
                $category = $special->category;
                $reason = $special->reason ?: "Duty Leave ({$special->category}) sanctioned by Tutor";
                $docSubmitted = 'Duty Certificate Verified';
            } elseif ($leave) {
                $category = 'Leave Request';
                $reason = $leave->reason ?: 'Medical / Casual Leave';
                $docSubmitted = $leave->status === 'Approved' ? 'Approved Leave Record' : 'Submitted (Pending)';
            }

            $chronologicalAbsences[] = [
                'date' => $item['date'],
                'formatted_date' => $item['formatted_date'],
                'day' => $item['day'],
                'hours_count' => $item['hours_count'],
                'periods_str' => 'Period ' . implode(', ', array_unique($item['periods'])),
                'subjects_str' => implode(', ', $item['subjects']) ?: 'Class Sessions',
                'category' => $category,
                'reason' => $reason,
                'document_submitted' => $docSubmitted,
            ];
        }

        // Compute Condonation Statistics
        $origPct = $totalConductedHours > 0 ? round(($totalAttendedHours / $totalConductedHours) * 100, 1) : 0.0;
        $effectiveAttended = min($totalConductedHours, $totalAttendedHours + $specialHoursCredited);
        $effectivePct = $totalConductedHours > 0 ? round(($effectiveAttended / $totalConductedHours) * 100, 1) : 0.0;

        $relaxations = $specialRecords->pluck('category')->filter(fn($c) => in_array($c, ['Menstrual Leave', 'PWD']))->unique()->toArray();
        $evaluation = self::evaluateEligibility($effectivePct, $scheme, $relaxations);

        // Required hours for eligibility
        $requiredThreshold = $evaluation['required_pct'];
        $requiredHours = (int)ceil(($requiredThreshold / 100.0) * $totalConductedHours);
        $grossShortageHours = max(0, $requiredHours - $totalAttendedHours);
        $netShortageHours = max(0, $requiredHours - $effectiveAttended);

        // Prescribed condonation fee
        $feeAmount = ($scheme === 'R26' && $evaluation['status'] === 'Special Condonation') ? 1500 : 750;

        return view('tutor.attendance_condonation_certificate', [
            'classroom' => $classroom,
            'student' => $student,
            'scheme' => $scheme,
            'evaluation' => $evaluation,
            'stats' => [
                'total_conducted' => $totalConductedHours,
                'original_attended' => $totalAttendedHours,
                'original_percentage' => $origPct,
                'duty_hours_credited' => $specialHoursCredited,
                'effective_attended' => $effectiveAttended,
                'effective_percentage' => $effectivePct,
                'total_missed_hours' => $totalMissedHours,
                'gross_shortage_hours' => $grossShortageHours,
                'net_shortage_hours' => $netShortageHours,
                'fee_amount' => $feeAmount,
            ],
            'absences' => $chronologicalAbsences,
            'special_records' => $specialRecords,
        ]);
    }

    /**
     * Printable A4 Class-Level Condonation Register (All students with ESE status & shortage).
     */
    public function printCondonationRegister(Request $request)
    {
        $res = $this->getCondonationRegister($request);
        $data = $res->getData(true);

        if (($data['status'] ?? '') !== 'SUCCESS') {
            abort(404, $data['message'] ?? 'Failed to load condonation register.');
        }

        $classroom = self::resolveTutorClassroom($request);

        return view('tutor.attendance_condonation_register_print', [
            'classroom' => $classroom,
            'scheme' => $data['scheme'] ?? 'R21',
            'regulation_title' => $data['regulation_title'] ?? '',
            'period' => $data['period'] ?? ['label' => 'Full Semester'],
            'summary' => $data['summary'] ?? [],
            'students' => $data['students'] ?? [],
        ]);
    }
}
