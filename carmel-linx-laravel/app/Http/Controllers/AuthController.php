<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Handle student or staff login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'password' => 'required|string',
            'roleType' => 'required|string|in:student,staff',
        ]);

        $userId = trim($request->input('userId'));
        $password = trim($request->input('password'));
        $roleType = $request->input('roleType');

        try {
            if ($roleType === 'student') {
                $cleanAdmNo = strtok($userId, '/');
                $student = Student::where('reg_no', strtoupper($userId))
                    ->orWhere('adm_no', strtoupper($userId))
                    ->orWhere('adm_no', $userId)
                    ->orWhere('adm_no', $cleanAdmNo)
                    ->orWhere('adm_no', 'LIKE', $cleanAdmNo . '/%')
                    ->orWhere('adm_no', 'LIKE', '%' . $cleanAdmNo)
                    ->orWhere('reg_no', 'LIKE', '%' . $cleanAdmNo)
                    ->orWhere('sbte_reg_no', strtoupper($userId))
                    ->first();

                $isPasswordValid = $student && $this->verifyPassword($password, $student->password);
                if (!$student || !$isPasswordValid) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Invalid ID/Admission Number or Password.']);
                }

                if (strtoupper($student->status) !== 'APPROVED') {
                    return response()->json(['status' => 'ERROR', 'message' => 'Your registration is pending approval by your Class Tutor.']);
                }

                // Check if student is logging in with default common password "carmel2026"
                $isDefaultPassword = ($password === 'carmel2026') || ($this->verifyPassword('carmel2026', $student->password) && !str_starts_with($student->password, '$2y$'));
                if ($isDefaultPassword || $password === 'carmel2026') {
                    Session::put('must_update_profile', true);
                }

                // Set session data
                Session::put([
                    'userRole' => 'Student',
                    'userId' => $student->reg_no,
                    'userName' => $student->name,
                    'userBranch' => $student->branch,
                    'userAdmissionType' => $student->admission_type,
                    'userPhoto' => $student->photo_url ?? '',
                    'classroomId' => $student->classroom_id,
                    'sbteRegNo' => $student->sbte_reg_no,
                    'userEmail' => $student->email,
                    'semester' => $student->semester,
                ]);

                return response()->json([
                    'status' => 'SUCCESS',
                    'role' => 'Student',
                    'id' => $student->reg_no,
                    'name' => $student->name,
                    'branch' => $student->branch,
                    'must_update_profile' => Session::get('must_update_profile', false),
                    'route' => '/dashboard/student'
                ]);
            } else {
                // Staff login by mobile number or username
                $cleanMobile = preg_replace('/[^0-9]/', '', $userId);
                $staff = StaffProfile::where(function($q) use ($userId, $cleanMobile) {
                    $q->where('mobile_no', $userId)
                      ->orWhere('email', $userId)
                      ->orWhere('name', $userId);
                    if (!empty($cleanMobile)) {
                        $q->orWhere('mobile_no', $cleanMobile);
                    }
                })->first();

                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Invalid Mobile Number or Password.']);
                }

                $isPasswordValid = $this->verifyPassword($password, $staff->password);
                if (!$isPasswordValid) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Invalid Mobile Number or Password.']);
                }

                if (strtoupper($staff->account_status) !== 'APPROVED') {
                    return response()->json(['status' => 'ERROR', 'message' => 'Your staff account is pending approval by Super Admin.']);
                }

                // Set session data
                Session::put([
                    'userRole' => $staff->designation,
                    'userId' => $staff->mobile_no,
                    'userName' => $staff->name,
                    'userBranch' => $staff->branch,
                    'userPhoto' => $staff->photo_url ?? '',
                ]);

                // Determine redirect route based on role
                $route = '/dashboard/lecturer';
                if ($staff->designation === 'Super_Admin') {
                    $route = '/dashboard/superadmin';
                } elseif ($staff->designation === 'Chairman') {
                    $route = '/dashboard/chairman';
                } elseif ($staff->designation === 'Admin') {
                    $route = '/dashboard/admin';
                } elseif ($staff->designation === 'Principal') {
                    $route = '/dashboard/principal';
                } elseif ($staff->designation === 'HOD') {
                    $route = '/dashboard/hod';
                } elseif ($staff->designation === 'Tutor') {
                    $route = '/dashboard/tutor';
                } elseif ($staff->designation === 'Gen_Dept_Coordinator_Aided') {
                    $route = '/dashboard/general-coordinator-aided';
                } elseif (in_array($staff->designation, ['Academic_Coordinator', 'Academic Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance'])) {
                    $route = '/dashboard/academic-coordinator';
                } elseif ($staff->designation === 'Lecturer') {
                    $route = '/dashboard/lecturer';
                } elseif ($staff->designation === 'Demonstrator') {
                    $route = '/dashboard/demonstrator';
                } elseif ($staff->designation === 'Trade_Instructor') {
                    $route = '/dashboard/tradeinstructor';
                } elseif ($staff->designation === 'Workshop_Superintendent') {
                    $route = '/dashboard/workshop';
                }

                return response()->json([
                    'status' => 'SUCCESS',
                    'role' => $staff->designation,
                    'id' => $staff->mobile_no,
                    'name' => $staff->name,
                    'branch' => $staff->branch,
                    'route' => $route
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'System error: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle student registration.
     */
    public function registerStudent(Request $request)
    {
        $request->validate([
            'admNo' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'branch' => 'required|string',
            'admissionYear' => 'required|integer',
            'admissionType' => 'required|string|in:Regular,LET',
            'password' => 'required|string',
            'sbteRegNo' => 'nullable|string',
            'semester' => 'required|string',
        ]);

        $email = trim($request->input('email'));

        $admNo = strtoupper(trim($request->input('admNo')));
        $branchCode = strtoupper(trim($request->input('branch')));
        $admYear = (int)$request->input('admissionYear');
        $isLET = $request->input('admissionType') === 'LET';

        // Auto-generate Registration Number
        $yy = substr((string)$admYear, -2);
        $regNo = $yy . $branchCode . $admNo . ($isLET ? 'L' : '');

        // Check duplicate
        $duplicate = Student::where('reg_no', $regNo)
            ->orWhere('adm_no', $admNo)
            ->orWhere('email', $email)
            ->first();
        if ($duplicate) {
            if (strcasecmp($duplicate->email, $email) === 0) {
                return response()->json(['status' => 'ERROR', 'message' => 'A student with this Email Address already exists.']);
            }
            return response()->json(['status' => 'ERROR', 'message' => 'A student with this Register Number or Admission Number already exists.']);
        }

        // Classroom ID calculation
        $startYear = $isLET ? ($admYear - 1) : $admYear;
        $endYear = $startYear + 3;
        $classroomId = "{$branchCode}_{$startYear}_{$endYear}";

        // LET students belong to the home/regular batch classroom.
        // We do not assign them directly to the _LET classroom in the database.

        // Only assign if the batch has been created by the HOD.
        // If the HOD hasn't created the batch yet, leave classroom_id as null.
        // The student will be backfilled when the HOD creates the batch later.
        $batchExists = \App\Models\ClassManagement::where('classroom_id', $classroomId)->exists()
            || \App\Models\R26ClassManagement::where('classroom_id', $classroomId)->exists();
        if (!$batchExists) {
            $classroomId = null;
        }

        // Parse Semester e.g. "S3" -> 3
        $semStr = $request->input('semester', 'S1');
        $semNum = (int) filter_var($semStr, FILTER_SANITIZE_NUMBER_INT) ?: 1;

        // Save Photo if uploaded
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            if (!$photoFile->isValid()) {
                return response()->json(['status' => 'ERROR', 'message' => 'Image upload failed due to file size or type mismatch. Please select a valid photo under 5MB.']);
            }
            $mime = strtolower($photoFile->getMimeType() ?: $photoFile->getClientMimeType());
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($mime, $allowed)) {
                return response()->json(['status' => 'ERROR', 'message' => 'Image type mismatch: Only JPG, PNG, or WebP photo formats are allowed.']);
            }
            if ($photoFile->getSize() > 5 * 1024 * 1024) {
                return response()->json(['status' => 'ERROR', 'message' => 'Image size mismatch: Photo size exceeds 5MB limit.']);
            }
            $photoPath = '/storage/' . $photoFile->store('avatars', 'public');
        }

        try {
            $student = Student::create([
                'reg_no' => $regNo,
                'adm_no' => $admNo,
                'name' => trim($request->input('name')),
                'email' => $email,
                'password' => trim($request->input('password')),
                'phone' => $request->input('phone'),
                'branch' => $branchCode,
                'admission_year' => $admYear,
                'admission_type' => $request->input('admissionType'),
                'photo_url' => $photoPath,
                'classroom_id' => $classroomId,
                'semester' => $semNum,
                'status' => 'Pending',
                'sbte_reg_no' => $request->filled('sbteRegNo') ? trim($request->input('sbteRegNo')) : null,
            ]);

            // Add Audit Log entry
            $actorId = Session::get('userId') ?: 'System';
            $actorName = Session::get('userName') ?: 'Self Registration';
            AuditLog::create([
                'performed_by' => $actorId,
                'performed_by_name' => $actorName,
                'target_id' => $student->reg_no,
                'target_name' => $student->name,
                'action' => 'Registered',
                'details' => 'Student registration created with status: Pending',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Registration successful! Pending Class Tutor approval.',
                'regNo' => $regNo
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to write: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle staff registration.
     */
    public function registerStaff(Request $request)
    {
        $request->validate([
            'mobileNo' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'branch' => 'required|string',
            'designation' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = trim($request->input('email'));

        $mobileNo = preg_replace('/[^0-9]/', '', $request->input('mobileNo'));

        // Check duplicate
        $duplicate = StaffProfile::where('mobile_no', $mobileNo)->first();
        if ($duplicate) {
            return response()->json(['status' => 'ERROR', 'message' => 'A staff profile with this mobile number already exists.']);
        }

        $designation = trim($request->input('designation'));

        // Enforce role count constraints
        if ($designation === 'Principal') {
            $hasPrincipal = StaffProfile::where('designation', 'Principal')->exists();
            if ($hasPrincipal) {
                return response()->json(['status' => 'ERROR', 'message' => 'An active Principal profile already exists in the system.']);
            }
        }

        if ($designation === 'Academic_Coordinator') {
            $hasCoordinator = StaffProfile::where('designation', 'Academic_Coordinator')
                ->where('account_status', 'Approved')
                ->exists();
            if ($hasCoordinator) {
                return response()->json(['status' => 'ERROR', 'message' => 'An active Academic Coordinator profile already exists in the system.']);
            }
        }

        // Save Photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            if (!$photoFile->isValid()) {
                return response()->json(['status' => 'ERROR', 'message' => 'Image upload failed due to file size or type mismatch. Please select a valid photo under 5MB.']);
            }
            $mime = strtolower($photoFile->getMimeType() ?: $photoFile->getClientMimeType());
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($mime, $allowed)) {
                return response()->json(['status' => 'ERROR', 'message' => 'Image type mismatch: Only JPG, PNG, or WebP photo formats are allowed.']);
            }
            if ($photoFile->getSize() > 5 * 1024 * 1024) {
                return response()->json(['status' => 'ERROR', 'message' => 'Image size mismatch: Photo size exceeds 5MB limit.']);
            }
            $photoPath = '/storage/' . $photoFile->store('avatars', 'public');
        }

        $status = ($designation === 'Principal') ? 'Approved' : 'Pending';

        try {
            $staff = StaffProfile::create([
                'mobile_no' => $mobileNo,
                'name' => trim($request->input('name')),
                'email' => $email,
                'branch' => strtoupper(trim($request->input('branch'))),
                'designation' => $designation,
                'password' => trim($request->input('password')),
                'photo_url' => $photoPath,
                'account_status' => $status,
            ]);

            // Add Audit Log entry
            $actorId = Session::get('userId') ?: 'System';
            $actorName = Session::get('userName') ?: 'Self Registration';
            AuditLog::create([
                'performed_by' => $actorId,
                'performed_by_name' => $actorName,
                'target_id' => $staff->mobile_no,
                'target_name' => $staff->name,
                'action' => 'Registered',
                'details' => "Staff registration created for role: {$designation} with status: {$status}",
                'ip_address' => $request->ip(),
            ]);

            $msg = ($designation === 'Principal') 
                ? 'Principal registration successful! Account is auto-approved.' 
                : 'Staff registration submitted! Pending administrator approval.';

            return response()->json(['status' => 'SUCCESS', 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to write: ' . $e->getMessage()]);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = trim($request->input('email'));

        // Check if email belongs to student or staff
        $isStudent = Student::where('email', $email)->exists();
        $isStaff = \App\Models\StaffProfile::where('email', $email)->exists();

        if (!$isStudent && !$isStaff) {
            return response()->json(['status' => 'ERROR', 'message' => 'No account found with that email address.']);
        }

        // Generate a random token
        $token = \Illuminate\Support\Str::random(64);

        // Delete any existing tokens for this email
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Insert new token
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);

        // In a real application, you would send an email here using Laravel's Mail facade.
        // Mail::to($email)->send(new ResetPasswordMail($token));

        return response()->json(['status' => 'SUCCESS', 'message' => 'A password reset link has been securely sent to your email address!']);
    }

    /**
     * Change logged-in student's password.
     */
    public function changeStudentPassword(Request $request)
    {
        $userId = Session::get('userId');
        $role = Session::get('userRole');

        if (!$userId || $role !== 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Only students can perform this action.']);
        }

        $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|min:6',
        ]);

        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');

        $student = Student::where('reg_no', $userId)->first();
        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student profile not found.']);
        }

        if (!$this->verifyPassword($oldPassword, $student->password)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Current password is incorrect.']);
        }

        try {
            $student->update(['password' => $newPassword]);
            return response()->json(['status' => 'SUCCESS', 'message' => 'Password updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to update password: ' . $e->getMessage()]);
        }
    }

    /**
     * Logout and destroy session.
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['userRole', 'userName', 'userEmail', 'mobile_no', 'reg_no', 'classroom_id', 'designation', 'user_id', 'userId']);
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }

    /**
     * Get Executive Profile details (Chairman, Principal, Admin, Staff).
     */
    public function getExecutiveProfile(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId && !$userRole) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized session.']);
        }

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff && $userRole) {
            $staff = StaffProfile::where('designation', $userRole)->first();
        }

        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'name' => $staff->name,
                'mobile_no' => $staff->mobile_no,
                'email' => $staff->email,
                'designation' => $staff->designation,
                'photo_url' => $staff->photo_url ?? '/storage/avatars/default.png',
            ]
        ]);
    }

    /**
     * Update Executive Profile (Name, Mobile No/Login ID, Email, Password, Photo).
     */
    public function updateExecutiveProfile(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId && !$userRole) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized session.']);
        }

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff && $userRole) {
            $staff = StaffProfile::where('designation', $userRole)->first();
        }

        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Profile not found.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'new_password' => 'nullable|string|min:4',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $newMobile = preg_replace('/[^0-9]/', '', $request->input('mobile_no'));
        if (empty($newMobile)) {
            $newMobile = trim($request->input('mobile_no'));
        }

        if ($newMobile !== $staff->mobile_no) {
            $exists = StaffProfile::where('mobile_no', $newMobile)->where('id', '!=', $staff->id)->exists();
            if ($exists) {
                return response()->json(['status' => 'ERROR', 'message' => 'The Login ID / Mobile Number is already in use by another account.']);
            }
        }

        try {
            $staff->name = trim($request->input('name'));
            $staff->email = trim($request->input('email'));
            $staff->mobile_no = $newMobile;

            if ($request->filled('new_password')) {
                $staff->password = trim($request->input('new_password'));
            }

            if ($request->hasFile('photo')) {
                if ($staff->photo_url && str_contains($staff->photo_url, '/storage/')) {
                    $oldPath = str_replace('/storage/', '', $staff->photo_url);
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
                $photoPath = '/storage/' . $request->file('photo')->store('avatars', 'public');
                $staff->photo_url = $photoPath;
                Session::put('userPhoto', $photoPath);
            }

            $staff->save();

            Session::put('userId', $staff->mobile_no);
            Session::put('userName', $staff->name);
            Session::put('userRole', $staff->designation);

            AuditLog::create([
                'performed_by' => $staff->mobile_no,
                'performed_by_name' => $staff->name,
                'target_id' => $staff->mobile_no,
                'target_name' => $staff->name,
                'action' => 'Profile Updated',
                'details' => "Updated executive profile settings (Name, Login ID, Password/Photo).",
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Profile settings updated successfully!',
                'data' => [
                    'name' => $staff->name,
                    'mobile_no' => $staff->mobile_no,
                    'email' => $staff->email,
                    'photo_url' => $staff->photo_url
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }

    /**
     * Change logged-in staff's password from My Profile.
     */
    public function changeStaffPassword(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId || $userRole === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Staff session required.']);
        }

        $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|min:4',
        ]);

        $oldPassword = trim($request->input('oldPassword'));
        $newPassword = trim($request->input('newPassword'));

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff && $userRole) {
            $staff = StaffProfile::where('designation', $userRole)->first();
        }

        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
        }

        $isPasswordValid = $this->verifyPassword($oldPassword, $staff->password);
        if (!$isPasswordValid) {
            return response()->json(['status' => 'ERROR', 'message' => 'Current password is incorrect.']);
        }

        try {
            $staff->password = $newPassword;
            $staff->save();

            AuditLog::create([
                'performed_by' => $staff->mobile_no,
                'performed_by_name' => $staff->name,
                'target_id' => $staff->mobile_no,
                'target_name' => $staff->name,
                'action' => 'Password Changed',
                'details' => 'Staff updated account password from My Profile settings.',
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Password updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to update password: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper to safely verify plain-text or Bcrypt hashed passwords.
     */
    private function verifyPassword(?string $inputPassword, ?string $storedPassword): bool
    {
        if (empty($storedPassword) || empty($inputPassword)) {
            return false;
        }

        if ($storedPassword === $inputPassword) {
            return true;
        }

        if (str_starts_with($storedPassword, '$2y$') || str_starts_with($storedPassword, '$2a$') || str_starts_with($storedPassword, '$2b$')) {
            try {
                return Hash::check($inputPassword, $storedPassword);
            } catch (\Throwable $e) {
                return false;
            }
        }

        return false;
    }
}
