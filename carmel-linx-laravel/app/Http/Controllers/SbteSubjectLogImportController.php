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
use App\Models\SubjectOfficialAttendance;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SbteSubjectLogImportController extends Controller
{
    /**
     * Import official SBTE Attendance Statement or Subject Log (Spreadsheet or PDF).
     */
    public function importPdf(Request $request)
    {
        $role = Session::get("userRole");
        $staffMobile = Session::get("userId");

        if (!$role || $role === "Student") {
            return response()->json(["status" => "ERROR", "message" => "Unauthorized access."], 403);
        }

        $request->validate([
            "file" => "required|file|max:20480", // max 20MB
            "batch_subject_id" => "required|integer",
        ]);

        $batchSubjectId = (int)$request->batch_subject_id;
        $batchSubject = BatchSubject::findOrFail($batchSubjectId);
        $subBatch = $request->input("sub_batch", "Whole") ?: "Whole";
        $autoFillLp = filter_var($request->input("auto_fill_lesson_plan", true), FILTER_VALIDATE_BOOLEAN);

        $file = $request->file("file");
        $ext = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ["xlsx", "xls", "csv", "html", "htm", "pdf"];

        if (!in_array($ext, $allowedExtensions)) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Please upload a valid TEAMS Attendance spreadsheet (.xlsx, .xls, .csv, .html) or PDF file."
            ], 422);
        }

        $tempPath = $file->getRealPath();

        // Fetch all active/approved students in this classroom
        $classroomStudents = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->where(function ($q) {
                $q->where("status", "Approved")->orWhere("status", "Active");
            })
            ->orderByRaw("ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \x27LET\x27 THEN 1 ELSE 0 END ASC, UPPER(name) ASC")
            ->get(["roll_no", "name", "reg_no", "admission_type", "date_of_joining"]);

        if ($classroomStudents->isEmpty()) {
            return response()->json([
                "status" => "ERROR",
                "message" => "No approved students were found for classroom: {$batchSubject->classroom_id}."
            ], 422);
        }

        // Branch 1: Spreadsheet / HTML table upload (Excel .xlsx, .xls, .csv, .html, .htm)
        if ($ext !== "pdf") {
            return $this->processSpreadsheet(
                $tempPath,
                $ext,
                $batchSubject,
                $classroomStudents,
                $subBatch,
                $autoFillLp,
                $staffMobile,
                $request
            );
        }

        // Branch 2: PDF upload (Smalot PDF parser)
        $fullText = "";
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
                "status" => "ERROR",
                "message" => "The uploaded PDF does not contain extractable text."
            ], 422);
        }

        // Detect if Attendance Statement or Subject Log
        $isAttendanceStatement = stripos($fullText, "ATTENDANCE STATEMENT") !== false;

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
     * Process uploaded spreadsheet / HTML table files (.xlsx, .xls, .csv, .html, .htm).
     */
    private function processSpreadsheet(
        string $filePath,
        string $ext,
        BatchSubject $batchSubject,
        $classroomStudents,
        string $subBatch,
        bool $autoFillLp,
        ?string $staffMobile,
        Request $request
    ) {
        $rows = $this->extractSpreadsheetRows($filePath, $ext);

        if (empty($rows)) {
            return response()->json([
                "status" => "ERROR",
                "message" => "The uploaded spreadsheet does not contain any readable data or rows."
            ], 422);
        }

        // Try Attendance Statement first (matrix with dates across columns and student rows)
        $attResult = $this->processSpreadsheetAttendanceStatement(
            $rows,
            $batchSubject,
            $classroomStudents,
            $subBatch,
            $autoFillLp,
            $staffMobile,
            $request
        );

        if ($attResult !== null) {
            return $attResult;
        }

        // Fallback to Subject Log (vertical rows with Date, Hours, Topics)
        $subLogResult = $this->processSpreadsheetSubjectLog(
            $rows,
            $batchSubject,
            $classroomStudents,
            $subBatch,
            $autoFillLp,
            $staffMobile,
            $request
        );

        if ($subLogResult !== null) {
            return $subLogResult;
        }

        return response()->json([
            "status" => "ERROR",
            "message" => "Could not detect an Attendance Statement or Subject Log structure in the uploaded spreadsheet. Please ensure the file was exported from the TEAMS portal."
        ], 422);
    }

    /**
     * Extract tabular rows from any spreadsheet format (OpenXML, BIFF8, CSV, HTML table).
     */
    private function extractSpreadsheetRows(string $filePath, string $ext): array
    {
        $rows = [];

        // 1. Try PhpSpreadsheet IOFactory (auto-detects xlsx, xls, csv, html)
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            \Log::warning("PhpSpreadsheet IOFactory load failed on {$ext} file: " . $e->getMessage());
        }

        // 2. Fallback for HTML tables (saved as .xls, .html, or .htm)
        if (empty($rows)) {
            try {
                $content = @file_get_contents($filePath);
                if ($content && (stripos($content, "<table") !== false || stripos($content, "<html") !== false)) {
                    $dom = new \DOMDocument();
                    @$dom->loadHTML($content);
                    $tables = $dom->getElementsByTagName("table");
                    $domRows = [];
                    foreach ($tables as $tbl) {
                        foreach ($tbl->getElementsByTagName("tr") as $tr) {
                            $rowCells = [];
                            foreach ($tr->childNodes as $child) {
                                if ($child->nodeName === "td" || $child->nodeName === "th") {
                                    $rowCells[] = trim(preg_replace("/\s+/", " ", $child->textContent));
                                }
                            }
                            if (!empty($rowCells)) {
                                $domRows[] = $rowCells;
                            }
                        }
                    }
                    if (!empty($domRows)) {
                        $rows = $domRows;
                    }
                }
            } catch (\Throwable $e2) {
                \Log::warning("DOMDocument fallback table extraction failed: " . $e2->getMessage());
            }
        }

        // 3. Fallback for CSV
        if (empty($rows) && $ext === "csv") {
            if (($handle = fopen($filePath, "r")) !== false) {
                while (($data = fgetcsv($handle, 4096, ",")) !== false) {
                    $rows[] = $data;
                }
                fclose($handle);
            }
        }

        return $rows;
    }

    /**
     * Process Attendance Statement from extracted spreadsheet rows.
     */
    private function processSpreadsheetAttendanceStatement(
        array $rows,
        BatchSubject $batchSubject,
        $classroomStudents,
        string $subBatch,
        bool $autoFillLp,
        ?string $staffMobile,
        Request $request
    ) {
        $programme = "";
        $facultyName = "";
        $dateRangeStr = "";
        $year = date("Y");

        // Extract metadata from header rows
        foreach (array_slice($rows, 0, 20) as $row) {
            $rowStr = implode(" ", array_filter(array_map(fn($v) => trim((string)$v), $row)));
            if (empty($rowStr)) continue;

            if (stripos($rowStr, "Programme:") !== false && preg_match("/Programme:\s*([^;\n]+)/i", $rowStr, $m)) {
                $programme = trim(explode("Course:", $m[1])[0]);
            }
            if (stripos($rowStr, "Faculty:") !== false && preg_match("/Faculty:\s*([^;\n]+)/i", $rowStr, $m)) {
                $facultyName = trim($m[1]);
            }
            if (preg_match("/(?:FROM\s+)?(\d{2}[-\/]\d{2}[-\/]\d{4})\s+TO\s+(\d{2}[-\/]\d{2}[-\/]\d{4})/i", $rowStr, $m)) {
                $dateRangeStr = "{$m[1]} to {$m[2]}";
                $year = substr($m[1], -4);
            }
        }

        // Locate table header row with Roll/Name and session dates
        $headerRowIdx = null;
        $dateColMap = []; // colIndex => \x27YYYY-MM-DD\x27
        foreach ($rows as $rIdx => $row) {
            $cleanRow = array_map(function($v) { return trim(preg_replace("/\s+/", " ", (string)$v)); }, $row);
            $hasRollOrName = false;
            $datesInRow = [];

            foreach ($cleanRow as $cIdx => $cell) {
                if (preg_match("/Roll/i", $cell) || preg_match("/Sl\.?\s*No/i", $cell) || preg_match("/Name/i", $cell)) {
                    $hasRollOrName = true;
                }

                $parsedDate = null;
                if (preg_match("#^(\d{1,2})[/.-](\d{1,2})(?:[/.-](\d{2,4}))?$#", $cell, $dm)) {
                    $d = str_pad($dm[1], 2, "0", STR_PAD_LEFT);
                    $m = str_pad($dm[2], 2, "0", STR_PAD_LEFT);
                    $y = !empty($dm[3]) ? (strlen($dm[3]) == 2 ? "20" . $dm[3] : $dm[3]) : $year;
                    if ((int)$d >= 1 && (int)$d <= 31 && (int)$m >= 1 && (int)$m <= 12) {
                        $parsedDate = "{$y}-{$m}-{$d}";
                    }
                } elseif (preg_match("/^\d{4}-\d{2}-\d{2}$/", $cell)) {
                    $parsedDate = $cell;
                } elseif (is_numeric($cell) && (int)$cell > 40000 && (int)$cell < 60000) {
                    try {
                        $parsedDate = ExcelDate::excelToDateTimeObject((int)$cell)->format("Y-m-d");
                    } catch (\Throwable $ex) {}
                }

                if ($parsedDate) {
                    $datesInRow[$cIdx] = $parsedDate;
                }
            }

            if ($hasRollOrName && count($datesInRow) >= 2) {
                $headerRowIdx = $rIdx;
                $dateColMap = $datesInRow;
                break;
            }
        }

        if ($headerRowIdx === null || empty($dateColMap)) {
            return null; // Not an attendance statement
        }

        $sessionDates = array_values($dateColMap);
        $sessionCount = count($sessionDates);

        if (empty($dateRangeStr) && !empty($sessionDates)) {
            $dateRangeStr = date("d-m-Y", strtotime($sessionDates[0])) . " to " . date("d-m-Y", strtotime(end($sessionDates)));
        }

        // Determine hours/periods per session date
        $hoursList = [];
        $startStudentRowIdx = $headerRowIdx + 1;

        if (isset($rows[$headerRowIdx + 1])) {
            $nextRow = $rows[$headerRowIdx + 1];
            $c0 = trim((string)($nextRow[0] ?? ""));

            // If row does not start with a student roll number, it is the periods/hours row
            if (!preg_match("/^\d+[\.]?$/", $c0)) {
                $startStudentRowIdx = $headerRowIdx + 2;
                foreach ($dateColMap as $cIdx => $date) {
                    $val = trim((string)($nextRow[$cIdx] ?? ""));
                    if (preg_match("/^([1-7](?:[\s,]+[1-7])*)$/", $val, $hm)) {
                        $periods = array_map("intval", preg_split("/[\s,]+/", $hm[1]));
                        $valid = array_values(array_filter($periods, fn($p) => $p >= 1 && $p <= 7));
                        $hoursList[] = !empty($valid) ? $valid : ($batchSubject->subject_type !== "Theory" ? [1, 2, 3] : [1]);
                    } else {
                        $hoursList[] = ($batchSubject->subject_type !== "Theory" ? [1, 2, 3] : [1]);
                    }
                }
            }
        }

        while (count($hoursList) < $sessionCount) {
            $hoursList[] = ($batchSubject->subject_type !== "Theory" ? [1, 2, 3] : [1]);
        }

        // Extract student attendance rows
        $studMatches = [];
        for ($rIdx = $startStudentRowIdx; $rIdx < count($rows); $rIdx++) {
            $row = $rows[$rIdx];
            $c0 = trim((string)($row[0] ?? ""));
            $c1 = trim((string)($row[1] ?? ""));
            $c2 = trim((string)($row[2] ?? ""));

            if (empty($c0) && empty($c1)) continue;
            if (stripos($c0, "Polytechnic") !== false || stripos($c0, "ATTENDANCE") !== false || stripos($c0, "Roll") !== false) continue;
            if (stripos($c1, "Polytechnic") !== false || stripos($c1, "ATTENDANCE") !== false) continue;

            $rollNo = null;
            $studName = null;

            if (preg_match("/^(\d+)[\.]?$/", $c0, $m0) && !empty($c1) && !is_numeric($c1)) {
                $rollNo = (int)$m0[1];
                $studName = $c1;
            } elseif (preg_match("/^(\d+)[\.]?$/", $c0, $m0) && preg_match("/^(\d+)[\.]?$/", $c1, $m1) && !empty($c2)) {
                $rollNo = (int)$m1[1];
                $studName = $c2;
            } elseif (preg_match("/^(\d+)[\.\s]+(.*)$/", $c0, $mComb)) {
                $rollNo = (int)$mComb[1];
                $studName = $mComb[2];
            }

            if (!$rollNo || empty($studName)) continue;
            if (isset($studMatches[$rollNo])) continue; // avoid duplicates across multi-page tables

            $marks = [];
            foreach ($dateColMap as $cIdx => $dt) {
                $mVal = strtoupper(trim((string)($row[$cIdx] ?? "A")));
                $marks[] = $mVal;
            }

            // Find total attended hours from end of row
            $totalVal = null;
            $maxCol = max(array_keys($dateColMap));
            for ($c = count($row) - 1; $c > $maxCol; $c--) {
                $v = trim((string)($row[$c] ?? ""));
                if ($v !== "" && is_numeric($v)) {
                    $totalVal = (int)$v;
                    break;
                }
            }

            if ($totalVal === null) {
                $calcTot = 0;
                foreach ($marks as $mIdx => $mVal) {
                    if (is_numeric($mVal)) {
                        $calcTot += (int)$mVal;
                    } elseif ($mVal === "P" || $mVal === "PRESENT") {
                        $calcTot += count($hoursList[$mIdx] ?? [1]);
                    }
                }
                $totalVal = $calcTot;
            }

            $studMatches[$rollNo] = [
                0 => "Roll $rollNo $studName",
                1 => (string)$rollNo,
                2 => $studName,
                3 => implode(" ", $marks),
                "roll_no" => $rollNo,
                "name" => $studName,
                "marks" => $marks,
                "total" => $totalVal
            ];
        }

        $studMatches = array_values($studMatches);

        if (empty($studMatches)) {
            return null; // No students found
        }

        return $this->saveAttendanceStatementRecords(
            $batchSubject,
            $classroomStudents,
            $sessionDates,
            $hoursList,
            $studMatches,
            $subBatch,
            $autoFillLp,
            $staffMobile,
            $request,
            $facultyName,
            $programme,
            $dateRangeStr,
            "TEAMS_SPREADSHEET_UPLOAD"
        );
    }

    /**
     * Process Subject Log from extracted spreadsheet rows.
     */
    private function processSpreadsheetSubjectLog(
        array $rows,
        BatchSubject $batchSubject,
        $classroomStudents,
        string $subBatch,
        bool $autoFillLp,
        ?string $staffMobile,
        Request $request
    ) {
        $programme = "";
        $facultyName = "";
        $year = date("Y");

        foreach (array_slice($rows, 0, 15) as $row) {
            $rowStr = implode(" ", array_filter(array_map(fn($v) => trim((string)$v), $row)));
            if (empty($rowStr)) continue;
            if (stripos($rowStr, "Programme:") !== false && preg_match("/Programme:\s*([^;\n]+)/i", $rowStr, $m)) {
                $programme = trim(explode("Course:", $m[1])[0]);
            }
            if (stripos($rowStr, "Faculty:") !== false && preg_match("/Faculty:\s*([^;\n]+)/i", $rowStr, $m)) {
                $facultyName = trim($m[1]);
            }
        }

        // Find header row with Date and Hours/Topics
        $headerRowIdx = null;
        $dateCol = null;
        $hoursCol = null;
        $topicCol = null;

        foreach ($rows as $rIdx => $row) {
            foreach ($row as $cIdx => $cell) {
                $c = strtolower(trim((string)$cell));
                if (stripos($c, "date") !== false) $dateCol = $cIdx;
                if (stripos($c, "hour") !== false || stripos($c, "period") !== false) $hoursCol = $cIdx;
                if (stripos($c, "topic") !== false || stripos($c, "portion") !== false || stripos($c, "content") !== false) $topicCol = $cIdx;
            }
            if ($dateCol !== null && ($hoursCol !== null || $topicCol !== null)) {
                $headerRowIdx = $rIdx;
                break;
            }
        }

        if ($headerRowIdx === null || $dateCol === null) {
            return null;
        }

        $sessions = [];
        for ($r = $headerRowIdx + 1; $r < count($rows); $r++) {
            $row = $rows[$r];
            $dateCell = trim((string)($row[$dateCol] ?? ""));
            if (empty($dateCell)) continue;

            $parsedDate = null;
            if (preg_match("#^(\d{1,2})[/.-](\d{1,2})(?:[/.-](\d{2,4}))?$#", $dateCell, $dm)) {
                $d = str_pad($dm[1], 2, "0", STR_PAD_LEFT);
                $m = str_pad($dm[2], 2, "0", STR_PAD_LEFT);
                $y = !empty($dm[3]) ? (strlen($dm[3]) == 2 ? "20" . $dm[3] : $dm[3]) : $year;
                $parsedDate = "{$y}-{$m}-{$d}";
            } elseif (preg_match("/^\d{4}-\d{2}-\d{2}$/", $dateCell)) {
                $parsedDate = $dateCell;
            } elseif (is_numeric($dateCell) && (int)$dateCell > 40000 && (int)$dateCell < 60000) {
                try {
                    $parsedDate = ExcelDate::excelToDateTimeObject((int)$dateCell)->format("Y-m-d");
                } catch (\Throwable $ex) {}
            }

            if (!$parsedDate) continue;

            $hoursCell = $hoursCol !== null ? trim((string)($row[$hoursCol] ?? "")) : "1";
            preg_match_all("/\d+/", $hoursCell, $hMatches);
            $hours = !empty($hMatches[0]) ? array_map("intval", $hMatches[0]) : [1];

            $topicCell = $topicCol !== null ? trim((string)($row[$topicCol] ?? "")) : "";

            $sessions[] = [
                "date" => $parsedDate,
                "hours" => $hours,
                "topic" => $topicCell,
            ];
        }

        if (empty($sessions)) {
            return null;
        }

        return $this->saveSubjectLogRecords(
            $batchSubject,
            $classroomStudents,
            $sessions,
            $subBatch,
            $autoFillLp,
            $staffMobile,
            $request,
            $facultyName,
            $programme,
            "TEAMS_SPREADSHEET_UPLOAD"
        );
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
        preg_match("/Programme:\s*(.*)/", $fullText, $progM);
        preg_match("/Course:\s*(.*?)\s*(?:\((\w+)\))?\s*Semester:\s*(\w+)/", $fullText, $courseM);
        preg_match("/Faculty:\s*(.*)/", $fullText, $facM);
        preg_match("/(?:FROM\s+)?(\d{2}-\d{2}-\d{4})\s+TO\s+(\d{2}-\d{2}-\d{4})/i", $fullText, $rangeM);

        $facultyName = trim($facM[1] ?? "");
        $year = !empty($rangeM[1]) ? substr($rangeM[1], -4) : date("Y");

        // Extract Dates (DD/MM)
        preg_match("/(?:Roll\.\s*No\.\s*Name\s*)?((?:\d{2}\/\d{2}\s*){10,})/i", $fullText, $datesM);
        $datesStr = preg_replace("/\s+/", "", $datesM[1] ?? "");
        preg_match_all("/\d{2}\/\d{2}/", $datesStr, $dateMatches);
        $datesRaw = $dateMatches[0] ?? [];

        if (empty($datesRaw)) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Could not detect class dates in the Attendance Statement table."
            ], 422);
        }

        $sessionDates = [];
        foreach ($datesRaw as $dr) {
            $p = explode("/", $dr);
            $sessionDates[] = "{$year}-{$p[1]}-{$p[0]}";
        }
        $sessionCount = count($sessionDates);

        // Extract Hours row (e.g. 1,2,3 or 4,5,6 per date session) across all table pages
        preg_match_all("/(?:Total|Period[s]?|Hour[s]?)\s*\n\s*([\d\s,]+)(?:\n|$)/i", $fullText, $allHoursM);
        $rawHoursStr = "";
        if (!empty($allHoursM[1])) {
            $rawHoursStr = implode(" ", $allHoursM[1]);
        }
        if (empty(trim($rawHoursStr))) {
            preg_match("/Total\s*\n\s*([\d\s,]+)\n/i", $fullText, $hoursM);
            $rawHoursStr = trim($hoursM[1] ?? "");
        }
        $hoursList = [];

        if (!empty($rawHoursStr)) {
            $cleanedHoursStr = preg_replace("/\s*,\s*/", ",", $rawHoursStr);
            preg_match_all("/([1-7](?:,[1-7])*?)(?=[1-7],|\s|\$)/", $cleanedHoursStr, $sessionHourMatches);
            if (!empty($sessionHourMatches[0])) {
                foreach ($sessionHourMatches[0] as $shStr) {
                    $parts = array_map("intval", explode(",", $shStr));
                    $validParts = array_values(array_filter($parts, fn($p) => $p >= 1 && $p <= 7));
                    if (!empty($validParts)) {
                        $hoursList[] = $validParts;
                    }
                }
            }
        }

        // Fill default hours if needed
        $defaultSessionPeriods = ($batchSubject->subject_type !== "Theory") ? [1, 2, 3] : [1];
        while (count($hoursList) < $sessionCount) {
            $hoursList[] = $defaultSessionPeriods;
        }

        // Extract Students attendance rows: Roll. No. Name [Marks...] Total
        $filteredLines = [];
        $rawLines = preg_split("/\r\n|\r|\n/", $fullText);
        foreach ($rawLines as $rawLine) {
            $t = trim($rawLine);
            if (empty($t)) continue;

            if (preg_match("/^\d+$/", $t)) continue;
            if (stripos($t, "Polytechnic College") !== false) continue;
            if (stripos($t, "ATTENDANCE STATEMENT") !== false) continue;
            if (stripos($t, "Programme:") !== false) continue;
            if (stripos($t, "Course:") !== false) continue;
            if (stripos($t, "Faculty:") !== false) continue;
            if (preg_match("/^Roll\.\s*No\.\s*Name/i", $t)) continue;
            if (preg_match("/^(?:\d{2}\/\d{2}\s*){3,}/", $t)) continue;
            if (strtolower($t) === "total") continue;
            if (preg_match("/^(?:[1-7]\s*,?\s*){4,}/", $t)) continue;

            $filteredLines[] = $t;
        }

        // Stitch multiline student rows
        $cleanLines = [];
        $pendingLine = "";
        foreach ($filteredLines as $fl) {
            if (preg_match("/^(\d+)[\.\s]+[A-Za-z]/", $fl)) {
                if (!empty($pendingLine)) {
                    $cleanLines[] = $pendingLine;
                }
                $pendingLine = $fl;
            } else {
                if (!empty($pendingLine)) {
                    $pendingLine .= " " . $fl;
                }
            }
        }
        if (!empty($pendingLine)) {
            $cleanLines[] = $pendingLine;
        }

        // Line-based token extraction
        $studMatches = [];
        foreach ($cleanLines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            if (!preg_match("/^(\d+)[\.\s]+(.*)$/", $line)) continue;
            $tokens = preg_split("/\s+/", $line);
            if (count($tokens) < $sessionCount + 3) continue;
            $lastToken = end($tokens);
            if (!is_numeric($lastToken)) continue;

            $rollNo = (int)rtrim($tokens[0], ".");
            $totalVal = (int)$lastToken;
            $marks = array_slice($tokens, -($sessionCount + 1), $sessionCount);
            $nameTokens = array_slice($tokens, 1, count($tokens) - $sessionCount - 2);
            $rawName = implode(" ", $nameTokens);

            $studMatches[] = [
                0 => $line,
                1 => (string)$rollNo,
                2 => $rawName,
                3 => implode(" ", $marks),
                "roll_no" => $rollNo,
                "name" => $rawName,
                "marks" => $marks,
                "total" => $totalVal
            ];
        }

        if (empty($studMatches)) {
            preg_match_all("/(?m)^(\d+)\.\s+([A-Za-z\s\.\x27\-]+?)\t?\s+([A-Z0-9\-\s\.\/]+?)\s+(\d+)\s*$/", $fullText, $studMatches, PREG_SET_ORDER);
        }

        if (empty($studMatches)) {
            preg_match_all("/(?m)^(\d+)[\.\s]+([A-Za-z\s\.\x27\-]+?)\t?\s+([A-Z0-9\-\s\.\/]{5,})\s+(\d+)\s*$/", $fullText, $studMatches, PREG_SET_ORDER);
        }

        if (empty($studMatches)) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Could not detect student attendance rows in the PDF table."
            ], 422);
        }

        $dateRangeStr = (!empty($datesRaw) ? ($datesRaw[0] . " to " . end($datesRaw)) : "");

        return $this->saveAttendanceStatementRecords(
            $batchSubject,
            $classroomStudents,
            $sessionDates,
            $hoursList,
            $studMatches,
            $subBatch,
            $autoFillLp,
            $staffMobile,
            $request,
            $facultyName,
            trim($progM[1] ?? ""),
            $dateRangeStr,
            "TEAMS_PDF_UPLOAD"
        );
    }

    /**
     * Shared persistence logic for Attendance Statement (both PDF and Spreadsheet).
     */
    private function saveAttendanceStatementRecords(
        BatchSubject $batchSubject,
        $classroomStudents,
        array $sessionDates,
        array $hoursList,
        array $studMatches,
        string $subBatch,
        bool $autoFillLp,
        ?string $staffMobile,
        Request $request,
        ?string $facultyName = null,
        ?string $programme = null,
        ?string $dateRangeStr = null,
        string $sourceType = "TEAMS_SPREADSHEET_UPLOAD"
    ) {
        $recordedBy = $staffMobile ?: ($facultyName ?: "Faculty Staff");
        $sessionCount = count($sessionDates);

        // Map classroom students by roll number, reg number, and name
        $studentsByRoll = [];
        $studentsByReg = [];
        $studentsByName = [];
        foreach ($classroomStudents as $cs) {
            if ($cs->roll_no !== null) {
                $studentsByRoll[(int)$cs->roll_no] = $cs;
            }
            if (!empty($cs->reg_no)) {
                $studentsByReg[$cs->reg_no] = $cs;
            }
            $cleanName = strtoupper(preg_replace("/[^A-Z]/", "", $cs->name));
            $studentsByName[$cleanName] = $cs;
        }

        // Build attendance map per session index: sessionIndex => [\x27present\x27 => [regNos], \x27absent\x27 => [regNos]]
        $sessionAttendance = [];
        for ($i = 0; $i < $sessionCount; $i++) {
            $sessionAttendance[$i] = ["present" => [], "absent" => []];
        }

        $matchedStudentsCount = 0;
        $matchedRegNos = [];
        foreach ($studMatches as $sm) {
            $rollNo = (int)($sm["roll_no"] ?? $sm[1] ?? 0);
            $rawName = trim($sm["name"] ?? $sm[2] ?? "");
            $cleanName = strtoupper(preg_replace("/[^A-Z]/", "", $rawName));
            $marks = is_array($sm["marks"] ?? null) ? $sm["marks"] : preg_split("/\s+/", trim($sm[3] ?? ""));

            $studentObj = $studentsByRoll[$rollNo] ?? ($studentsByName[$cleanName] ?? null);
            if (!$studentObj) {
                foreach ($classroomStudents as $cs) {
                    $cName = strtoupper(preg_replace("/[^A-Z]/", "", $cs->name));
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

            $doj = $studentObj->date_of_joining ?: (($studentObj->admission_type === "LET") ? "2026-07-15" : null);
            for ($i = 0; $i < $sessionCount; $i++) {
                $sessDate = $sessionDates[$i] ?? null;
                if ($doj && $sessDate && $sessDate < $doj) {
                    continue; // Skip pre-admission dates for this student
                }

                $mark = strtoupper(trim($marks[$i] ?? "A"));
                if ($mark === "A" || $mark === "0" || $mark === "-" || $mark === "AB" || $mark === "") {
                    $sessionAttendance[$i]["absent"][] = $regNo;
                } else {
                    $sessionAttendance[$i]["present"][] = $regNo;
                }
            }
        }

        // Safety fallback: Only if no students were detected from rows
        if ($matchedStudentsCount === 0) {
            foreach ($classroomStudents as $cs) {
                $rNo = $cs->reg_no;
                $doj = $cs->date_of_joining ?: (($cs->admission_type === "LET") ? "2026-07-15" : null);
                for ($i = 0; $i < $sessionCount; $i++) {
                    $sessDate = $sessionDates[$i] ?? null;
                    if ($doj && $sessDate && $sessDate < $doj) {
                        continue;
                    }
                    if (!in_array($rNo, $sessionAttendance[$i]["absent"]) && !in_array($rNo, $sessionAttendance[$i]["present"])) {
                        $sessionAttendance[$i]["present"][] = $rNo;
                    }
                }
            }
        }
        $matchedStudentsCount = max($matchedStudentsCount, count($matchedRegNos));

        // Fetch lesson plans & practical experiments
        $lessonPlans = LessonPlan::where("batch_subject_id", $batchSubject->id)
            ->orderBy("day_no", "asc")
            ->orderBy("id", "asc")
            ->get();

        $practicalExperiments = PracticalExperiment::where("batch_subject_id", $batchSubject->id)
            ->orderByRaw("CAST(experiment_no AS UNSIGNED), experiment_no ASC")
            ->get();

        $labBatches = DB::table("r26_student_lab_batches")
            ->where("batch_subject_id", $batchSubject->id)
            ->pluck("lab_batch", "reg_no")
            ->toArray();
        $labUploadMode = $request->input("lab_upload_mode", null);
        $isSplitLab = ($batchSubject->subject_type !== "Theory") && (
            $labUploadMode === "split" ||
            ($labUploadMode !== "full" && ($batchSubject->lab_batch_mode === "split" || !empty($batchSubject->lab_batch_cutoff) || !empty($labBatches)))
        );

        $importedSessions = 0;
        $totalHoursLogged = 0;
        $pdfBatchHours = ["1" => 0, "2" => 0, "Whole" => 0];
        $mappedLpCount = 0;
        $usedLpIds = [];
        $lpSeqIndex = 0;
        $processedSlotsInRun = [];

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $sessionCount; $i++) {
                $date = $sessionDates[$i];
                $periods = $hoursList[$i] ?? [1];
                $presentRegNos = array_values(array_unique($sessionAttendance[$i]["present"]));
                $absentRegNos = array_values(array_unique($sessionAttendance[$i]["absent"]));

                $sessionSubBatch = $subBatch;
                if ($isSplitLab && $subBatch === "Whole") {
                    $b1PresCount = 0;
                    $b2PresCount = 0;
                    foreach ($presentRegNos as $rNo) {
                        $st = $studentsByReg[$rNo] ?? ($studentsByRoll[(int)$rNo] ?? null);
                        $b = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $st && $st->roll_no !== null ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null);
                        if ($b === "1") $b1PresCount++;
                        elseif ($b === "2") $b2PresCount++;
                    }
                    if ($b1PresCount > 0 && $b2PresCount === 0) {
                        $sessionSubBatch = "1";
                    } elseif ($b2PresCount > 0 && $b1PresCount === 0) {
                        $sessionSubBatch = "2";
                    } elseif ($b1PresCount >= 8 && $b2PresCount <= 3) {
                        $sessionSubBatch = "1";
                    } elseif ($b2PresCount >= 8 && $b1PresCount <= 3) {
                        $sessionSubBatch = "2";
                    } else {
                        $sessionSubBatch = "Whole";
                    }
                }

                // If this is a split lab session (Batch 1 or Batch 2), scope student present/absent lists strictly to this batch
                if ($isSplitLab && ($sessionSubBatch === "1" || $sessionSubBatch === "2")) {
                    $presentRegNos = array_values(array_filter($presentRegNos, function($rNo) use ($labBatches, $sessionSubBatch, $studentsByReg, $batchSubject) {
                        $st = $studentsByReg[$rNo] ?? null;
                        $stBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $st && $st->roll_no !== null ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null);
                        return $stBatch === $sessionSubBatch;
                    }));
                    $absentRegNos = array_values(array_filter($absentRegNos, function($rNo) use ($labBatches, $sessionSubBatch, $studentsByReg, $batchSubject) {
                        $st = $studentsByReg[$rNo] ?? null;
                        $stBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $st && $st->roll_no !== null ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null);
                        return $stBatch === $sessionSubBatch;
                    }));
                }

                // Filter out students who had not yet joined on this date (LET / late joiners)
                $presentRegNos = array_values(array_filter($presentRegNos, function($rNo) use ($studentsByReg, $classroomStudents, $date) {
                    $cs = $studentsByReg[$rNo] ?? $classroomStudents->firstWhere("reg_no", $rNo);
                    $doj = $cs ? ($cs->date_of_joining ?: (($cs->admission_type === "LET") ? "2026-07-15" : null)) : null;
                    return !$doj || $date >= $doj;
                }));
                $absentRegNos = array_values(array_filter($absentRegNos, function($rNo) use ($studentsByReg, $classroomStudents, $date) {
                    $cs = $studentsByReg[$rNo] ?? $classroomStudents->firstWhere("reg_no", $rNo);
                    $doj = $cs ? ($cs->date_of_joining ?: (($cs->admission_type === "LET") ? "2026-07-15" : null)) : null;
                    return !$doj || $date >= $doj;
                }));

                // Create distinct period logs for exact hour calculations
                foreach ($periods as $period) {
                    $period = (int)$period;

                    $slotKey = $date . "_" . $sessionSubBatch . "_" . $period;
                    while (isset($processedSlotsInRun[$slotKey])) {
                        $period++;
                        $slotKey = $date . "_" . $sessionSubBatch . "_" . $period;
                    }
                    $processedSlotsInRun[$slotKey] = true;

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
                            $assignedLp->status = "Completed";
                            $assignedLp->actual_date = $date;
                            $assignedLp->actual_hours = 1;
                            $assignedLp->save();
                            $mappedLpCount++;
                            $lpSeqIndex++;
                        } elseif ($batchSubject->subject_type === "Theory") {
                            $maxDayNo = DB::table("lesson_plans")->where("batch_subject_id", $batchSubject->id)->max("day_no") ?: 0;
                            $newDayNo = $maxDayNo + 1;
                            $newLp = LessonPlan::create([
                                "batch_subject_id" => $batchSubject->id,
                                "day_no" => $newDayNo,
                                "co_id" => "CO4",
                                "topic_content" => "Extra Class / Revision Session {$newDayNo}",
                                "allocated_hours" => 1,
                                "proposed_date" => null,
                                "actual_date" => $date,
                                "actual_hours" => 1,
                                "pedagogy" => "Lecture",
                                "mode" => "L",
                                "sub_batch" => $sessionSubBatch,
                                "remarks" => "Auto-created from SBTE Attendance Import",
                                "status" => "Completed",
                            ]);
                            $assignedTopic = $newLp->topic_content;
                            $assignedLpId = $newLp->id;
                            $usedLpIds[] = $newLp->id;
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

                    $existingLog = DB::table("class_logs_attendance")
                        ->where("batch_subject_id", $batchSubject->id)
                        ->where("date", $date)
                        ->where("period", $period)
                        ->first();

                    if ($existingLog) {
                        DB::table("class_logs_attendance")
                            ->where("id", $existingLog->id)
                            ->update([
                                "lesson_plan_id" => $assignedLpId ?: $existingLog->lesson_plan_id,
                                "topics_covered" => $assignedTopic ?: $existingLog->topics_covered,
                                "present_students" => json_encode($presentRegNos),
                                "absent_students" => json_encode($absentRegNos),
                                "sub_batch" => $sessionSubBatch,
                                "recorded_by" => $recordedBy,
                                "updated_at" => now(),
                            ]);
                    } else {
                        DB::table("class_logs_attendance")->insert([
                            "batch_subject_id" => $batchSubject->id,
                            "date" => $date,
                            "period" => $period,
                            "lesson_plan_id" => $assignedLpId,
                            "topics_covered" => $assignedTopic,
                            "present_students" => json_encode($presentRegNos),
                            "absent_students" => json_encode($absentRegNos),
                            "sub_batch" => $sessionSubBatch,
                            "recorded_by" => $recordedBy,
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                    }

                    $totalHoursLogged++;
                    $pdfBatchHours[$sessionSubBatch] = ($pdfBatchHours[$sessionSubBatch] ?? 0) + 1;
                }

                // Sync student_attendance table once per session date
                if (Schema::hasTable("student_attendance")) {
                    foreach ($presentRegNos as $rNo) {
                        DB::table("student_attendance")->updateOrInsert(
                            [
                                "reg_no" => $rNo,
                                "subject_code" => $batchSubject->subject_code,
                                "date" => $date,
                            ],
                            [
                                "status" => "Present",
                                "sub_batch" => $sessionSubBatch,
                                "lesson_plan_id" => $assignedLpId,
                                "updated_at" => now(),
                            ]
                        );
                    }

                    // For absent students: In split lab sessions, only record absent if student was scheduled for this batch!
                    $absentsToRecord = $absentRegNos;
                    if ($isSplitLab && ($sessionSubBatch === "1" || $sessionSubBatch === "2")) {
                        $otherBatchStudents = array_filter($classroomStudents->pluck("reg_no")->toArray(), function($rNo) use ($labBatches, $sessionSubBatch, $studentsByReg, $batchSubject) {
                            $st = $studentsByReg[$rNo] ?? null;
                            $stBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $st && $st->roll_no !== null ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null);
                            return $stBatch !== null && $stBatch !== $sessionSubBatch;
                        });
                        if (!empty($otherBatchStudents)) {
                            DB::table("student_attendance")
                                ->where("subject_code", $batchSubject->subject_code)
                                ->where("date", $date)
                                ->whereIn("reg_no", $otherBatchStudents)
                                ->delete();
                        }
                    }

                    foreach ($absentsToRecord as $rNo) {
                        DB::table("student_attendance")->updateOrInsert(
                            [
                                "reg_no" => $rNo,
                                "subject_code" => $batchSubject->subject_code,
                                "date" => $date,
                            ],
                            [
                                "status" => "Absent",
                                "sub_batch" => $sessionSubBatch,
                                "lesson_plan_id" => $assignedLpId,
                                "updated_at" => now(),
                            ]
                        );
                    }
                }

                $importedSessions++;
            }

            // Record Audit Log
            try {
                AuditLog::create([
                    "performed_by" => Session::get("userId") ?: "Staff",
                    "performed_by_name" => Session::get("userName") ?: ($facultyName ?: "Faculty Staff"),
                    "target_id" => (string)$batchSubject->id,
                    "target_name" => "{$batchSubject->subject_name} ({$batchSubject->subject_code})",
                    "action" => "SBTE Attendance Statement Bulk Import",
                    "details" => "Bulk imported {$importedSessions} sessions ({$totalHoursLogged} class hours) with student-wise attendance for {$matchedStudentsCount} students via {$sourceType}.",
                    "ip_address" => $request->ip()
                ]);
            } catch (\Exception $logEx) {}

            // For practical courses in Revision 2021, synchronize official attendance marks (out of 15) to PracticalEvaluation
            if ($batchSubject->subject_type === "Practical / Lab") {
                $assessorMobile = Session::get("userId") ?: ($staffMobile ?? null);
                if (!$assessorMobile) {
                    $assessorMobile = DB::table("subject_staff_assignments")
                        ->where("batch_subject_id", $batchSubject->id)
                        ->value("staff_mobile_no");
                }

                foreach ($classroomStudents as $cs) {
                    $rNo = $cs->reg_no;
                    $labBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $cs->roll_no !== null ? ((int)$cs->roll_no <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null);

                    $stOfficial = DB::table("student_attendance")
                        ->where("reg_no", $rNo)
                        ->where("subject_code", $batchSubject->subject_code)
                        ->get();

                    $doj = $cs->date_of_joining ?: (($cs->admission_type === "LET") ? "2026-07-15" : null);
                    if ($doj) {
                        $stOfficial = $stOfficial->filter(fn($att) => $att->date >= $doj);
                    }

                    if ($stOfficial->isNotEmpty()) {
                        if ($batchSubject->lab_batch_mode === "split" || !empty($labBatch)) {
                            $stFiltered = $stOfficial->filter(function($att) use ($labBatch) {
                                $sb = (string)($att->sub_batch ?? "Whole");
                                if ($sb === $labBatch || $sb === "Whole") return true;
                                return false;
                            });
                            if ($stFiltered->isNotEmpty()) {
                                $stOfficial = $stFiltered;
                            }
                        }
                        $offTot = $stOfficial->count();
                        $offPres = $stOfficial->whereIn("status", ["Present", "Late"])->count();
                        $pct = ($offTot > 0) ? round(($offPres / $offTot) * 100, 2) : 100.0;
                        $attMark = \App\Services\AttainmentService::calculateR21AttendanceMark($pct, 15.0);

                        $evalRecord = PracticalEvaluation::where("batch_subject_id", $batchSubject->id)
                            ->where("reg_no", $rNo)
                            ->first();

                        if ($evalRecord) {
                            $evalRecord->attendance_marks = $attMark;
                            $evalRecord->updated_at = now();
                            $evalRecord->save();
                        } else {
                            PracticalEvaluation::create([
                                "batch_subject_id" => $batchSubject->id,
                                "reg_no" => $rNo,
                                "assessor_mobile_no" => $assessorMobile ?: "9000000000",
                                "attendance_marks" => $attMark,
                                "micro_project" => 0.00,
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                        }
                    }
                }
            }

            // Sync authoritative TEAMS subject attendance for CIA calculation
            $subjType = $batchSubject->subject_type ?? "Theory";
            $maxAttMarks = ($subjType === "Theory") ? 10.0 : (stripos($subjType, "seminar") !== false ? 7.5 : 15.0);
            $studPdfTotals = [];
            $b1PdfTotals = [];
            $b2PdfTotals = [];
            $docConductedHours = $totalHoursLogged;
            foreach ($studMatches as $sm) {
                $rNo = (int)($sm["roll_no"] ?? 0);
                if ($rNo && isset($sm["total"]) && is_numeric($sm["total"])) {
                    $tot = (int)$sm["total"];
                    $studPdfTotals[$rNo] = $tot;

                    // Group attended totals by Virtual Lab Setup batch
                    $stObj = $studentsByRoll[$rNo] ?? null;
                    $stB = $stObj ? ($labBatches[$stObj->reg_no] ?? ($batchSubject->lab_batch_cutoff ? ($rNo <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null)) : null;
                    if ($stB === "1") {
                        if ($tot <= ceil($docConductedHours * 0.85) || empty($b2PdfTotals)) {
                            $b1PdfTotals[] = $tot;
                        }
                    } elseif ($stB === "2") {
                        if ($tot <= ceil($docConductedHours * 0.85) || empty($b1PdfTotals)) {
                            $b2PdfTotals[] = $tot;
                        }
                    }
                }
            }
            $maxPdfAttended = !empty($studPdfTotals) ? max($studPdfTotals) : 0;
            $b1MaxPdfAtt = !empty($b1PdfTotals) ? max($b1PdfTotals) : 0;
            $b2MaxPdfAtt = !empty($b2PdfTotals) ? max($b2PdfTotals) : 0;
            if (!empty($studPdfTotals)) {
                $docConductedHours = max($docConductedHours, $maxPdfAttended);
            }

            // Universal Split Lab Nominal Conducted Hours calculation:
            $splitLabNominalConducted = null;
            if ($isSplitLab) {
                $b1PdfHours = ($pdfBatchHours["1"] ?? 0) + ($pdfBatchHours["Whole"] ?? 0);
                $b2PdfHours = ($pdfBatchHours["2"] ?? 0) + ($pdfBatchHours["Whole"] ?? 0);
                $batchPeak = max($b1MaxPdfAtt, $b2MaxPdfAtt);
                if ($b1PdfHours > 0 && $b2PdfHours > 0) {
                    $splitLabNominalConducted = max($batchPeak, min($b1PdfHours, $b2PdfHours));
                } else {
                    $splitLabNominalConducted = max($batchPeak, max($b1PdfHours, $b2PdfHours));
                }
            }

            foreach ($classroomStudents as $cs) {
                $rNo = $cs->reg_no;
                $roll = (int)($cs->roll_no ?? 0);
                $logsAttendedCount = DB::table("class_logs_attendance")
                    ->where("batch_subject_id", $batchSubject->id)
                    ->whereRaw("JSON_CONTAINS(present_students, \x27\"" . $rNo . "\"\x27)")
                    ->count();

                $pdfAttended = $studPdfTotals[$roll] ?? null;
                $effectiveAttended = ($pdfAttended !== null && $pdfAttended > 0) ? $pdfAttended : $logsAttendedCount;

                // For split practicals, conducted hours is calculated dynamically per student\x27s Virtual Lab Setup range
                $studentConductedHours = $docConductedHours;
                if ($isSplitLab && $splitLabNominalConducted !== null) {
                    $studentConductedHours = max($splitLabNominalConducted, $effectiveAttended);
                }

                $effectiveConducted = max($studentConductedHours, $effectiveAttended);

                $officialPct = ($effectiveConducted > 0) ? round(($effectiveAttended / $effectiveConducted) * 100, 2) : 0.00;
                $officialMark = \App\Services\AttainmentService::calculateR21AttendanceMark($officialPct, $maxAttMarks);

                \App\Models\SubjectOfficialAttendance::updateOrInsert(
                    [
                        "batch_subject_id" => $batchSubject->id,
                        "reg_no" => $rNo,
                    ],
                    [
                        "subject_code" => $batchSubject->subject_code,
                        "classroom_id" => $batchSubject->classroom_id,
                        "total_hours" => $effectiveConducted,
                        "attended_hours" => $effectiveAttended,
                        "teams_percentage" => $officialPct,
                        "final_percentage" => DB::raw("COALESCE(override_percentage, {$officialPct})"),
                        "max_attendance_marks" => $maxAttMarks,
                        "attendance_mark" => $officialMark,
                        "final_mark" => DB::raw("COALESCE(override_mark, {$officialMark})"),
                        "source" => $sourceType,
                        "updated_at" => now(),
                    ]
                );
            }

            DB::commit();

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Successfully imported attendance for {$matchedStudentsCount} students across {$importedSessions} sessions ({$totalHoursLogged} class hours)!",
                "data" => [
                    "programme" => trim($programme ?: ""),
                    "course_title" => $batchSubject->subject_name,
                    "course_code" => $batchSubject->subject_code,
                    "semester" => $batchSubject->semester,
                    "faculty" => $facultyName ?: "",
                    "imported_sessions" => $importedSessions,
                    "total_hours" => $totalHoursLogged,
                    "mapped_lesson_plans" => $mappedLpCount,
                    "students_count" => $matchedStudentsCount,
                    "date_range" => $dateRangeStr ?: "",
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "ERROR",
                "message" => "Failed to save attendance statement: " . $e->getMessage()
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
        preg_match("/Programme:\s*(.*)/", $fullText, $progM);
        preg_match("/Course:\s*(.*?)\s*(?:\((\w+)\))?\s*Semester:\s*(\w+)/", $fullText, $courseM);
        preg_match("/Faculty:\s*(.*)/", $fullText, $facM);

        $facultyName = trim($facM[1] ?? "");

        preg_match_all("/(?ms)^(\d+)\.\s+(\d{2}-\d{2}-\d{4})\s+([\d,\s]+?)\s*(.*?)(?=\n\d+\.|\Z)/", $fullText, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return response()->json([
                "status" => "ERROR",
                "message" => "No class sessions were found in the uploaded PDF."
            ], 422);
        }

        $sessions = [];
        foreach ($matches as $m) {
            $rawDt = trim($m[2]);
            $dParts = explode("-", $rawDt);
            $date = count($dParts) === 3 ? "{$dParts[2]}-{$dParts[1]}-{$dParts[0]}" : $rawDt;

            preg_match_all("/\d+/", $m[3], $hMatches);
            $hours = !empty($hMatches[0]) ? array_map("intval", $hMatches[0]) : [1];

            $rawContent = trim($m[4]);
            if ($facultyName) {
                $rawContent = preg_replace("/\s*" . preg_quote($facultyName, "/") . ".*$/i", "", $rawContent);
            }
            $rawContent = preg_replace("/\s+\d+\s*$/", "", $rawContent);
            $assignedTopic = trim(preg_replace("/\s+/", " ", $rawContent));

            if (str_starts_with($assignedTopic, ",")) {
                preg_match_all("/\d+/", substr($assignedTopic, 0, 6), $extraH);
                foreach ($extraH[0] as $eh) {
                    $ehi = (int)$eh;
                    if (!in_array($ehi, $hours)) $hours[] = $ehi;
                }
                $assignedTopic = trim(preg_replace("/^[\d,\s]+/", "", $assignedTopic));
            }

            $sessions[] = [
                "date" => $date,
                "hours" => $hours,
                "topic" => $assignedTopic,
            ];
        }

        return $this->saveSubjectLogRecords(
            $batchSubject,
            $classroomStudents,
            $sessions,
            $subBatch,
            $autoFillLp,
            $staffMobile,
            $request,
            $facultyName,
            trim($progM[1] ?? ""),
            "TEAMS_PDF_UPLOAD"
        );
    }

    /**
     * Shared persistence logic for Subject Log (both PDF and Spreadsheet).
     */
    private function saveSubjectLogRecords(
        BatchSubject $batchSubject,
        $classroomStudents,
        array $sessions,
        string $subBatch,
        bool $autoFillLp,
        ?string $staffMobile,
        Request $request,
        ?string $facultyName = null,
        ?string $programme = null,
        string $sourceType = "TEAMS_SUBJECT_LOG"
    ) {
        $recordedBy = $staffMobile ?: ($facultyName ?: "Faculty Staff");

        $labBatches = DB::table("r26_student_lab_batches")
            ->where("batch_subject_id", $batchSubject->id)
            ->pluck("lab_batch", "reg_no")
            ->toArray();
        $isSplitLab = ($batchSubject->subject_type !== "Theory") && ($batchSubject->lab_batch_mode === "split" || !empty($batchSubject->lab_batch_cutoff) || !empty($labBatches));

        if ($isSplitLab && ($subBatch === "1" || $subBatch === "2")) {
            $studentRegNos = $classroomStudents->filter(function($st) use ($labBatches, $subBatch, $batchSubject) {
                $stBatch = $labBatches[$st->reg_no] ?? ($batchSubject->lab_batch_cutoff && $st->roll_no !== null ? ((int)$st->roll_no <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null);
                return $stBatch === $subBatch;
            })->pluck("reg_no")->toArray();
        } else {
            $studentRegNos = $classroomStudents->pluck("reg_no")->toArray();
        }

        $lessonPlans = LessonPlan::where("batch_subject_id", $batchSubject->id)->orderBy("id", "asc")->get();
        $practicalExperiments = PracticalExperiment::where("batch_subject_id", $batchSubject->id)->orderByRaw("CAST(experiment_no AS UNSIGNED), experiment_no ASC")->get();

        $importedSessionsCount = 0;
        $totalHoursLogged = 0;
        $mappedLpCount = 0;
        $usedLpIds = [];

        DB::beginTransaction();
        try {
            foreach ($sessions as $s) {
                $date = $s["date"];
                $hours = $s["hours"] ?? [1];
                $assignedTopic = trim($s["topic"] ?? "");

                if ($facultyName) {
                    $assignedTopic = preg_replace("/\s*" . preg_quote($facultyName, "/") . ".*$/i", "", $assignedTopic);
                }
                $assignedTopic = preg_replace("/\s+\d+\s*$/", "", $assignedTopic);
                $assignedTopic = trim(preg_replace("/\s+/", " ", $assignedTopic));

                $assignedLpId = null;

                if (!empty($assignedTopic)) {
                    $matchedLp = $lessonPlans->first(function ($lp) use ($assignedTopic, $usedLpIds) {
                        if (in_array($lp->id, $usedLpIds)) return false;
                        return stripos($lp->topic_content, $assignedTopic) !== false || stripos($assignedTopic, $lp->topic_content) !== false;
                    });
                    if ($matchedLp) {
                        $assignedLpId = $matchedLp->id;
                        $usedLpIds[] = $matchedLp->id;
                        $matchedLp->status = "Completed";
                        $matchedLp->actual_date = $date;
                        $matchedLp->save();
                        $mappedLpCount++;
                    }
                }

                if (empty($assignedTopic) && $autoFillLp) {
                    $pendingLp = $lessonPlans->first(function ($lp) use ($usedLpIds) {
                        return !in_array($lp->id, $usedLpIds) && $lp->status !== "Completed";
                    });
                    if ($pendingLp) {
                        $assignedTopic = $pendingLp->topic_content;
                        $assignedLpId = $pendingLp->id;
                        $usedLpIds[] = $pendingLp->id;
                        $pendingLp->status = "Completed";
                        $pendingLp->actual_date = $date;
                        $pendingLp->save();
                        $mappedLpCount++;
                    }
                }

                if (empty($assignedTopic)) {
                    $assignedTopic = "Topic pending manual update";
                }

                $sessionStudentRegNos = array_values(array_filter($studentRegNos, function($rNo) use ($classroomStudents, $date) {
                    $cs = $classroomStudents->firstWhere("reg_no", $rNo);
                    $doj = $cs ? ($cs->date_of_joining ?: (($cs->admission_type === "LET") ? "2026-07-15" : null)) : null;
                    return !$doj || $date >= $doj;
                }));

                foreach ($hours as $period) {
                    $period = (int)$period;
                    $existingLog = DB::table("class_logs_attendance")
                        ->where("batch_subject_id", $batchSubject->id)
                        ->where("date", $date)
                        ->where("period", $period)
                        ->where("sub_batch", $subBatch)
                        ->first();

                    if ($existingLog) {
                        DB::table("class_logs_attendance")
                            ->where("id", $existingLog->id)
                            ->update([
                                "lesson_plan_id" => $assignedLpId ?: $existingLog->lesson_plan_id,
                                "topics_covered" => $assignedTopic ?: $existingLog->topics_covered,
                                "present_students" => json_encode($sessionStudentRegNos),
                                "absent_students" => json_encode([]),
                                "recorded_by" => $recordedBy,
                                "updated_at" => now(),
                            ]);
                    } else {
                        DB::table("class_logs_attendance")->insert([
                            "batch_subject_id" => $batchSubject->id,
                            "date" => $date,
                            "period" => $period,
                            "lesson_plan_id" => $assignedLpId,
                            "topics_covered" => $assignedTopic,
                            "present_students" => json_encode($sessionStudentRegNos),
                            "absent_students" => json_encode([]),
                            "sub_batch" => $subBatch,
                            "recorded_by" => $recordedBy,
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                    }

                    if (Schema::hasTable("student_attendance")) {
                        foreach ($sessionStudentRegNos as $regNo) {
                            DB::table("student_attendance")->updateOrInsert(
                                [
                                    "reg_no" => $regNo,
                                    "subject_code" => $batchSubject->subject_code,
                                    "date" => $date,
                                ],
                                [
                                    "status" => "Present",
                                    "sub_batch" => $subBatch,
                                    "lesson_plan_id" => $assignedLpId,
                                    "updated_at" => now(),
                                ]
                            );
                        }
                    }

                    $totalHoursLogged++;
                }

                $importedSessionsCount++;
            }

            // For practical courses in Revision 2021, synchronize official attendance marks (out of 15) to PracticalEvaluation
            if ($batchSubject->subject_type === "Practical / Lab") {
                $assessorMobile = Session::get("userId") ?: ($staffMobile ?? null);
                if (!$assessorMobile) {
                    $assessorMobile = DB::table("subject_staff_assignments")
                        ->where("batch_subject_id", $batchSubject->id)
                        ->value("staff_mobile_no");
                }

                foreach ($classroomStudents as $cs) {
                    $rNo = $cs->reg_no;
                    $labBatch = $labBatches[$rNo] ?? ($batchSubject->lab_batch_cutoff && $cs->roll_no !== null ? ((int)$cs->roll_no <= (int)$batchSubject->lab_batch_cutoff ? "1" : "2") : null);

                    $stOfficial = DB::table("student_attendance")
                        ->where("reg_no", $rNo)
                        ->where("subject_code", $batchSubject->subject_code)
                        ->get();

                    if ($stOfficial->isNotEmpty()) {
                        if ($batchSubject->lab_batch_mode === "split" || !empty($labBatch)) {
                            $stFiltered = $stOfficial->filter(function($att) use ($labBatch) {
                                $sb = (string)($att->sub_batch ?? "Whole");
                                if ($sb === $labBatch || $sb === "Whole") return true;
                                return in_array($att->status, ["Present", "Late"]);
                            });
                            if ($stFiltered->isNotEmpty()) {
                                $stOfficial = $stFiltered;
                            }
                        }
                        $offTot = $stOfficial->count();
                        $offPres = $stOfficial->whereIn("status", ["Present", "Late"])->count();
                        $pct = ($offTot > 0) ? round(($offPres / $offTot) * 100, 2) : 100.0;
                        $attMark = \App\Services\AttainmentService::calculateR21AttendanceMark($pct, 15.0);

                        $evalRecord = PracticalEvaluation::where("batch_subject_id", $batchSubject->id)
                            ->where("reg_no", $rNo)
                            ->first();

                        if ($evalRecord) {
                            $evalRecord->attendance_marks = $attMark;
                            $evalRecord->updated_at = now();
                            $evalRecord->save();
                        } else {
                            PracticalEvaluation::create([
                                "batch_subject_id" => $batchSubject->id,
                                "reg_no" => $rNo,
                                "assessor_mobile_no" => $assessorMobile ?: "9000000000",
                                "attendance_marks" => $attMark,
                                "micro_project" => 0.00,
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                        }
                    }
                }
            }

            // Sync authoritative TEAMS subject attendance for CIA calculation
            $subjType = $batchSubject->subject_type ?? "Theory";
            $maxAttMarks = ($subjType === "Theory") ? 10.0 : (stripos($subjType, "seminar") !== false ? 7.5 : 15.0);
            $splitLabNominalHours = null;
            if ($isSplitLab) {
                $b1Hours = DB::table("class_logs_attendance")
                    ->where("batch_subject_id", $batchSubject->id)
                    ->where(function($q) { $q->where("sub_batch", "1")->orWhere("sub_batch", "Whole"); })
                    ->count();
                $b2Hours = DB::table("class_logs_attendance")
                    ->where("batch_subject_id", $batchSubject->id)
                    ->where(function($q) { $q->where("sub_batch", "2")->orWhere("sub_batch", "Whole"); })
                    ->count();
                if ($b1Hours > 0 && $b2Hours > 0) {
                    $splitLabNominalHours = min($b1Hours, $b2Hours);
                } else {
                    $splitLabNominalHours = max($b1Hours, $b2Hours);
                }
            }

            foreach ($classroomStudents as $cs) {
                $rNo = $cs->reg_no;
                $roll = (int)($cs->roll_no ?? 0);
                $logsAttendedCount = DB::table("class_logs_attendance")
                    ->where("batch_subject_id", $batchSubject->id)
                    ->whereRaw("JSON_CONTAINS(present_students, \x27\"" . $rNo . "\"\x27)")
                    ->count();

                $studentConductedHours = $totalHoursLogged;
                if ($isSplitLab && $splitLabNominalHours !== null) {
                    $studentConductedHours = max($splitLabNominalHours, $logsAttendedCount);
                }

                $effectiveConducted = max($studentConductedHours, $logsAttendedCount);
                $officialPct = ($effectiveConducted > 0) ? round(($logsAttendedCount / $effectiveConducted) * 100, 2) : 0.00;
                $officialMark = \App\Services\AttainmentService::calculateR21AttendanceMark($officialPct, $maxAttMarks);

                \App\Models\SubjectOfficialAttendance::updateOrInsert(
                    [
                        "batch_subject_id" => $batchSubject->id,
                        "reg_no" => $rNo,
                    ],
                    [
                        "subject_code" => $batchSubject->subject_code,
                        "classroom_id" => $batchSubject->classroom_id,
                        "total_hours" => $effectiveConducted,
                        "attended_hours" => $logsAttendedCount,
                        "teams_percentage" => $officialPct,
                        "final_percentage" => DB::raw("COALESCE(override_percentage, {$officialPct})"),
                        "max_attendance_marks" => $maxAttMarks,
                        "attendance_mark" => $officialMark,
                        "final_mark" => DB::raw("COALESCE(override_mark, {$officialMark})"),
                        "source" => $sourceType,
                        "updated_at" => now(),
                    ]
                );
            }

            DB::commit();

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Successfully imported {$importedSessionsCount} sessions ({$totalHoursLogged} class hours) and updated attendance for " . count($studentRegNos) . " students!",
                "data" => [
                    "programme" => $programme ?: "",
                    "course_title" => $batchSubject->subject_name,
                    "course_code" => $batchSubject->subject_code,
                    "semester" => $batchSubject->semester,
                    "faculty" => $facultyName ?: "",
                    "imported_sessions" => $importedSessionsCount,
                    "total_hours" => $totalHoursLogged,
                    "mapped_lesson_plans" => $mappedLpCount,
                    "students_count" => count($studentRegNos),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "ERROR",
                "message" => "Failed to import Subject Log: " . $e->getMessage()
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
