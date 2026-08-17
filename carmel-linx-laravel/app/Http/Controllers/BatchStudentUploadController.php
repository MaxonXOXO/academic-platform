<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\ClassManagement;
use App\Models\AuditLog;

class BatchStudentUploadController extends Controller
{
    /**
     * Parse and import 2026 batch (or general) student rosters from Excel (.xls / .xlsx / .csv).
     * Specifically designed to parse official Joining Lists like 'Regular.xls' as well as standard CSVs.
     */
    public function uploadBatchStudents(Request $request)
    {
        $userRole = Session::get('userRole');
        if (!in_array($userRole, ['Super_Admin', 'Admin', 'HOD', 'Principal', 'Workshop_Superintendent'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        if (!$request->hasFile('file')) {
            return response()->json(['status' => 'ERROR', 'message' => 'Please select a valid Excel (.xls/.xlsx) or CSV file to upload.']);
        }

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $fileContent = file_get_contents($filePath);

        try {
            $importedCount = 0;
            $updatedCount = 0;
            $departmentStats = [];
            $commonHashedPassword = Hash::make('carmel2026');

            // Detect if file is HTML-based Excel export (like Regular.xls)
            if (str_contains($fileContent, '<table') || str_contains($fileContent, '<tr')) {
                list($importedCount, $updatedCount, $departmentStats) = $this->parseHtmlExcel($fileContent, $commonHashedPassword);
            } else {
                list($importedCount, $updatedCount, $departmentStats) = $this->parseCsvExcel($filePath, $commonHashedPassword);
            }

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId') ?: 'Admin',
                'performed_by_name' => Session::get('userName') ?: 'Batch Uploader',
                'target_id' => 'BATCH_2026',
                'target_name' => '2026 Batch Roster',
                'action' => 'Bulk 2026 Batch Student Registration',
                'details' => "Batch imported {$importedCount} new students and updated {$updatedCount} existing records across " . count($departmentStats) . " departments.",
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Batch registration completed successfully! Registered {$importedCount} new students and updated {$updatedCount} existing records.",
                'imported_count' => $importedCount,
                'updated_count' => $updatedCount,
                'department_stats' => $departmentStats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Batch processing failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * HTML-based Excel export parser (handles joining lists like Regular.xls).
     */
    private function parseHtmlExcel(string $htmlContent, string $commonHashedPassword): array
    {
        $importedCount = 0;
        $updatedCount = 0;
        $departmentStats = [];

        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $htmlContent, $trMatches);

        $currentDepartment = 'General';
        $currentBranchCode = 'GEN';

        foreach ($trMatches[1] as $trContent) {
            preg_match_all('/<td[^>]*>(.*?)<\/td>/is', $trContent, $tdMatches);
            $cols = array_map(function($val) {
                return trim(strip_tags($val));
            }, $tdMatches[1]);

            if (empty($cols)) continue;

            // Detect Department Banner Row (e.g., Automobile Engineering, Civil Engineering, etc.)
            if (count($cols) == 1 && preg_match('/(Automobile|Civil|Computer|Electrical|Electronics|Mechanical)/i', $cols[0])) {
                $currentDepartment = $cols[0];
                $currentBranchCode = $this->mapDepartmentToBranchCode($currentDepartment);
                if (!isset($departmentStats[$currentDepartment])) {
                    $departmentStats[$currentDepartment] = 0;
                }
                continue;
            }

            // Check if this row is a student data row (col 0 is numeric SL No)
            if (count($cols) >= 10 && is_numeric($cols[0])) {
                $appnNo = trim($cols[1] ?? '');
                $name = strtoupper(trim($cols[2] ?? ''));
                $admNoRaw = trim($cols[3] ?? '');
                $dojRaw = trim($cols[4] ?? '');
                $quota = trim($cols[5] ?? 'GN');
                $dobRaw = trim($cols[9] ?? '');
                $mobile = trim($cols[10] ?? '');

                if (empty($name) || empty($admNoRaw)) {
                    continue;
                }

                // Extract clean admission number (e.g., "4613/26" or "4613")
                $admNoClean = strtok($admNoRaw, '/');
                $admissionYear = 2026;

                // Extract year from DOJ or default to 2026
                if (!empty($dojRaw) && preg_match('/(\d{4})/', $dojRaw, $m)) {
                    $admissionYear = (int)$m[1];
                }

                // Auto-generate Registration Number (e.g., 26AU4613)
                $yy = substr((string)$admissionYear, -2);
                $regNo = $yy . $currentBranchCode . $admNoClean;

                // Auto-calculate Classroom ID (e.g., AU_2026_2029)
                $endYear = $admissionYear + 3;
                $classroomId = "{$currentBranchCode}_{$admissionYear}_{$endYear}";

                // Ensure classroom exists in ClassManagement
                $this->ensureClassroomExists($classroomId, $currentBranchCode, $admissionYear);

                // Format dates safely
                $dob = $this->parseDateSafely($dobRaw);
                $doj = $this->parseDateSafely($dojRaw);

                $email = strtolower($admNoClean) . '@carmelpoly.in';

                // Check existing by reg_no or adm_no
                $existing = Student::where('reg_no', $regNo)
                    ->orWhere('adm_no', $admNoRaw)
                    ->orWhere('adm_no', $admNoClean)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'name' => $name,
                        'adm_no' => $admNoRaw,
                        'phone' => $mobile ?: $existing->phone,
                        'branch' => $currentBranchCode,
                        'admission_year' => $admissionYear,
                        'admission_type' => 'Regular',
                        'classroom_id' => $classroomId,
                        'semester' => 1,
                        'status' => 'Approved',
                        'academic_status' => 'Active',
                        'date_of_birth' => $dob ?: $existing->date_of_birth,
                        'application_no' => $appnNo ?: $existing->application_no,
                        'quota' => $quota ?: $existing->quota,
                        'date_of_joining' => $doj ?: $existing->date_of_joining,
                    ]);
                    $updatedCount++;
                } else {
                    Student::create([
                        'reg_no' => $regNo,
                        'adm_no' => $admNoRaw,
                        'name' => $name,
                        'email' => $email,
                        'password' => $commonHashedPassword,
                        'phone' => $mobile,
                        'branch' => $currentBranchCode,
                        'admission_year' => $admissionYear,
                        'admission_type' => 'Regular',
                        'classroom_id' => $classroomId,
                        'semester' => 1,
                        'status' => 'Approved',
                        'academic_status' => 'Active',
                        'date_of_birth' => $dob,
                        'application_no' => $appnNo,
                        'quota' => $quota,
                        'date_of_joining' => $doj,
                    ]);
                    $importedCount++;
                }

                $departmentStats[$currentDepartment] = ($departmentStats[$currentDepartment] ?? 0) + 1;
            }
        }

        return [$importedCount, $updatedCount, $departmentStats];
    }

    /**
     * CSV File Parser for custom student rosters.
     */
    private function parseCsvExcel(string $filePath, string $commonHashedPassword): array
    {
        $importedCount = 0;
        $updatedCount = 0;
        $departmentStats = [];

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) continue;

            $name = strtoupper(trim($row[0] ?? ''));
            $admNoRaw = trim($row[1] ?? '');
            $branchInput = strtoupper(trim($row[2] ?? 'GEN'));
            $admissionYear = (int)trim($row[3] ?? 2026);

            if (empty($name) || empty($admNoRaw)) continue;

            $branchCode = $this->mapDepartmentToBranchCode($branchInput);
            $admNoClean = strtok($admNoRaw, '/');
            $admissionType = (isset($row[4]) && strcasecmp(trim($row[4]), 'LET') === 0) ? 'LET' : 'Regular';

            $yy = substr((string)$admissionYear, -2);
            $regNo = $yy . $branchCode . $admNoClean . ($admissionType === 'LET' ? 'L' : '');

            $startYear = ($admissionType === 'LET') ? ($admissionYear - 1) : $admissionYear;
            $endYear = $startYear + 3;
            $classroomId = "{$branchCode}_{$startYear}_{$endYear}";

            $this->ensureClassroomExists($classroomId, $branchCode, $admissionYear);

            $email = strtolower(trim($row[5] ?? (strtolower($admNoClean) . '@carmelpoly.in')));

            $existing = Student::where('reg_no', $regNo)
                ->orWhere('adm_no', $admNoRaw)
                ->orWhere('adm_no', $admNoClean)
                ->first();

            if ($existing) {
                $existing->update([
                    'name' => $name,
                    'adm_no' => $admNoRaw,
                    'branch' => $branchCode,
                    'admission_year' => $admissionYear,
                    'admission_type' => $admissionType,
                    'classroom_id' => $classroomId,
                    'semester' => ($admissionType === 'LET' ? 3 : 1),
                    'status' => 'Approved',
                    'academic_status' => 'Active',
                ]);
                $updatedCount++;
            } else {
                Student::create([
                    'reg_no' => $regNo,
                    'adm_no' => $admNoRaw,
                    'name' => $name,
                    'email' => $email,
                    'password' => $commonHashedPassword,
                    'branch' => $branchCode,
                    'admission_year' => $admissionYear,
                    'admission_type' => $admissionType,
                    'classroom_id' => $classroomId,
                    'semester' => ($admissionType === 'LET' ? 3 : 1),
                    'status' => 'Approved',
                    'academic_status' => 'Active',
                ]);
                $importedCount++;
            }

            $departmentStats[$branchCode] = ($departmentStats[$branchCode] ?? 0) + 1;
        }

        fclose($handle);
        return [$importedCount, $updatedCount, $departmentStats];
    }

    /**
     * Map full department names or codes to standard branch codes.
     */
    private function mapDepartmentToBranchCode(string $deptInput): string
    {
        $dept = strtolower(trim($deptInput));
        if (str_contains($dept, 'auto') || $dept === 'au') return 'AU';
        if (str_contains($dept, 'civil') || $dept === 'ce') return 'CE';
        if (str_contains($dept, 'comp') || $dept === 'ct') return 'CT';
        if (str_contains($dept, 'electrical') || $dept === 'eee') return 'EEE';
        if (str_contains($dept, 'electronics') || $dept === 'el') return 'EL';
        if (str_contains($dept, 'mech') || $dept === 'me') return 'ME';
        return strtoupper(substr($deptInput, 0, 3));
    }

    /**
     * Ensure classroom record exists in appropriate class management table (R26 for 2026+, R21 for older).
     */
    private function ensureClassroomExists(string $classroomId, string $branchCode, int $admissionYear)
    {
        if ($admissionYear >= 2026) {
            $existingR26 = \DB::table('r26_class_management')->where('classroom_id', $classroomId)->first();
            if (!$existingR26) {
                \DB::table('r26_class_management')->insert([
                    'classroom_id' => $classroomId,
                    'branch' => $branchCode,
                    'batch_year' => $admissionYear,
                    'current_semester' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            $existingClass = ClassManagement::where('classroom_id', $classroomId)->first();
            if (!$existingClass) {
                ClassManagement::create([
                    'classroom_id' => $classroomId,
                    'branch' => $branchCode,
                    'batch_year' => $admissionYear,
                    'current_semester' => 1,
                ]);
            }
        }
    }

    /**
     * Safely parse date strings (e.g. DD-MM-YYYY to YYYY-MM-DD).
     */
    private function parseDateSafely(?string $dateStr): ?string
    {
        if (empty($dateStr)) return null;
        try {
            $ts = strtotime(str_replace('/', '-', $dateStr));
            if ($ts !== false) {
                return date('Y-m-d', $ts);
            }
        } catch (\Throwable $e) {}
        return null;
    }

    /**
     * API for student to complete compulsory profile setup upon first login with "carmel2026".
     */
    public function completeFirstLoginProfile(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId || $userRole !== 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access. Please log in first.']);
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:6',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'sbte_reg_no' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $validator->errors()->first()
            ]);
        }

        if (trim($request->input('new_password')) === 'carmel2026') {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'New password cannot be the default password "carmel2026". Please enter a unique password.'
            ]);
        }

        try {
            $student = Student::where('reg_no', $userId)
                ->orWhere('adm_no', $userId)
                ->first();

            if (!$student) {
                return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
            }

            $updateData = [
                'password' => Hash::make($request->input('new_password')),
                'email' => strtolower(trim($request->input('email'))),
            ];

            if ($request->filled('phone')) {
                $updateData['phone'] = trim($request->input('phone'));
            }

            if ($request->filled('sbte_reg_no')) {
                $updateData['sbte_reg_no'] = strtoupper(trim($request->input('sbte_reg_no')));
            }

            // Photo upload handling
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = 'student_' . $student->reg_no . '_' . time() . '.' . $file->getClientOriginalExtension();
                $publicPath = public_path('uploads/students');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }
                $file->move($publicPath, $filename);
                $photoUrl = '/uploads/students/' . $filename;
                $updateData['photo_url'] = $photoUrl;
                Session::put('userPhoto', $photoUrl);
            }

            $student->update($updateData);

            // Update session values
            Session::put('userEmail', $updateData['email']);
            if (isset($updateData['sbte_reg_no'])) {
                Session::put('sbteRegNo', $updateData['sbte_reg_no']);
            }
            Session::forget('must_update_profile');

            AuditLog::create([
                'performed_by' => $student->reg_no,
                'performed_by_name' => $student->name,
                'target_id' => $student->reg_no,
                'target_name' => $student->name,
                'action' => 'First Login Profile Setup',
                'details' => "Student completed initial profile setup and changed default password.",
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Profile configured successfully! Welcome to Carmel Linx Student Portal.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Profile update failed: ' . $e->getMessage()
            ]);
        }
    }
}
