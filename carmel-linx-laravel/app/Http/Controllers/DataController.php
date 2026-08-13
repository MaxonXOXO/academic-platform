<?php

namespace App\Http\Controllers;

use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\StudentResponse;
use App\Models\AcademicMark;
use App\Models\AuditLog;
use App\Models\BatchSubject;
use App\Models\SubjectStaffAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    /**
     * Approve a pending student or staff member.
     */
    public function approveAccount(Request $request)
    {
        $request->validate([
            'targetId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
        ]);

        $targetId = $request->input('targetId');
        $userType = $request->input('userType');

        try {
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($targetId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student registration lookup not found.']);
                }

                $student->update(['status' => 'Approved']);
                return response()->json(['status' => 'SUCCESS', 'message' => 'Student approved successfully.']);
            } else {
                $staff = StaffProfile::where('mobile_no', $targetId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile lookup not found.']);
                }

                if ($staff->designation === 'Principal') {
                    $hasPrincipal = StaffProfile::where('designation', 'Principal')
                        ->where('account_status', 'Approved')
                        ->exists();
                    if ($hasPrincipal) {
                        return response()->json(['status' => 'ERROR', 'message' => 'Another Principal is already approved. Cannot approve multiple active Principals.']);
                    }
                }

                $staff->update(['account_status' => 'Approved']);
                return response()->json(['status' => 'SUCCESS', 'message' => 'Staff member approved successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Operation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/HOD: update a student profile.
     */
    public function updateSbteRegNo(Request $request)
    {
        $request->validate([
            'sbteRegNo' => 'required|string|max:50',
        ]);

        $regNo = Session::get('userId');
        $student = Student::where('reg_no', $regNo)->first();

        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student not found.']);
        }

        try {
            $student->sbte_reg_no = strtoupper(trim($request->sbteRegNo));
            $student->save();
            Session::put('sbteRegNo', $student->sbte_reg_no);
            
            return response()->json(['status' => 'SUCCESS', 'message' => 'SBTE register number updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to save SBTE register number.']);
        }
    }

    public function updateStudentProfile(Request $request, $regNo)
    {
        $student = Student::where('reg_no', strtoupper($regNo))->first();
        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student profile not found.']);
        }

        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'nullable|string',
            'phone' => 'nullable|string',
            'sbte_reg_no' => 'nullable|string',
            'semester' => 'nullable|integer|min:1|max:6',
            'classroom_id' => 'nullable|string',
            'academic_status' => 'nullable|string|in:Active,Discontinued,TC Issued',
            'status_notes' => 'nullable|string',
        ]);

        try {
            $oldStatus = $student->academic_status;
            $newStatus = $request->input('academic_status');
            $oldSem = $student->semester;
            $newSem = $request->input('semester');
            $oldSbte = $student->sbte_reg_no;
            $newSbte = $request->input('sbte_reg_no');

            $fields = array_filter($request->only(['name', 'email', 'password', 'phone', 'sbte_reg_no', 'semester', 'classroom_id', 'academic_status', 'status_notes']), function ($val) {
                return $val !== null;
            });
            if ($request->hasFile('photo')) {
                $fields['photo_url'] = '/storage/' . $request->file('photo')->store('avatars', 'public');
            }

            $student->update($fields);

            $changes = [];
            if ($newStatus && $newStatus !== $oldStatus) {
                $noteText = $request->input('status_notes') ? " (Note: " . $request->input('status_notes') . ")" : "";
                $changes[] = "Enrollment status changed from '{$oldStatus}' to '{$newStatus}'{$noteText}";
            }
            if ($newSem && $newSem != $oldSem) {
                $changes[] = "Semester changed from 'S{$oldSem}' to 'S{$newSem}'";
            }
            if ($request->has('sbte_reg_no') && $newSbte !== $oldSbte) {
                $changes[] = "SBTE Reg No updated from '{$oldSbte}' to '{$newSbte}'";
            }

            if (!empty($changes)) {
                \App\Models\AuditLog::create([
                    'performed_by' => Session::get('userId') ?? 'System',
                    'performed_by_name' => Session::get('userName') ?? 'System',
                    'target_id' => $student->reg_no,
                    'target_name' => $student->name,
                    'action' => 'Profile Updated',
                    'details' => implode(', ', $changes) . '.',
                    'ip_address' => $request->ip()
                ]);
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Student profile updated.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Update failed: ' . $e->getMessage()]);
        }
     }

    public function updateStaffProfileDirect(Request $request, $mobileNo)
    {
        if (!$this->checkUserManagementPermission($mobileNo, 'staff')) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        $staff = StaffProfile::where('mobile_no', $mobileNo)->first();
        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
        }

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'branch' => 'required|string',
            'designation' => 'required|string',
        ]);

        try {
            $oldName = $staff->name;
            $oldEmail = $staff->email;
            $oldBranch = $staff->branch;
            $oldDesig = $staff->designation;

            $newName = $request->input('name');
            $newEmail = $request->input('email');
            $newBranch = $request->input('branch');
            $newDesig = $request->input('designation');

            // Enforce single active Principal constraint
            if ($newDesig === 'Principal' && $oldDesig !== 'Principal') {
                $hasOtherPrincipal = StaffProfile::where('designation', 'Principal')
                    ->where('mobile_no', '!=', $mobileNo)
                    ->where('account_status', 'Approved')
                    ->exists();
                if ($hasOtherPrincipal) {
                    return response()->json(['status' => 'ERROR', 'message' => 'An active Principal already exists in the system.']);
                }
            }

            $staff->update([
                'name' => $newName,
                'email' => $newEmail,
                'branch' => $newBranch,
                'designation' => $newDesig,
            ]);

            $changes = [];
            if ($newName !== $oldName) $changes[] = "Name changed from '{$oldName}' to '{$newName}'";
            if ($newEmail !== $oldEmail) $changes[] = "Email changed from '{$oldEmail}' to '{$newEmail}'";
            if ($newBranch !== $oldBranch) $changes[] = "Branch changed from '{$oldBranch}' to '{$newBranch}'";
            if ($newDesig !== $oldDesig) $changes[] = "Designation changed from '{$oldDesig}' to '{$newDesig}'";

            if (!empty($changes)) {
                AuditLog::create([
                    'performed_by' => Session::get('userId') ?? 'System',
                    'performed_by_name' => Session::get('userName') ?? 'System',
                    'target_id' => $staff->mobile_no,
                    'target_name' => $staff->name,
                    'action' => 'Staff Profile Updated',
                    'details' => implode(', ', $changes) . '.',
                    'ip_address' => $request->ip()
                ]);
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Staff profile updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/HOD: delete a student.
     */
    public function deleteStudentProfile($regNo)
    {
        try {
            $student = Student::where('reg_no', strtoupper($regNo))->first();
            if (!$student) {
                return response()->json(['status' => 'ERROR', 'message' => 'Student lookup registry record not found.']);
            }

            $student->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Student removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Delete failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Tutor: fetch classroom and students.
     */
    public function getTutorClassroomRoster($tutorMobile)
    {
        try {
            // Find the class this tutor is supervising
            $class = ClassManagement::where('tutor_mobile_no', $tutorMobile)
                ->orWhere('mentor_mobile_no', $tutorMobile)
                ->first();

            if (!$class) {
                return response()->json(['status' => 'ERROR', 'message' => 'You are not assigned as a Tutor or Mentor to any classroom.']);
            }

            $students = Student::getClassroomStudentsQuery($class->classroom_id)->get();
            
            $tutorName = null;
            if ($class->tutor_mobile_no) {
                $t = \App\Models\StaffProfile::where('mobile_no', $class->tutor_mobile_no)->first();
                $tutorName = $t ? $t->name : null;
            }
            
            $mentorName = null;
            if ($class->mentor_mobile_no) {
                $m = \App\Models\StaffProfile::where('mobile_no', $class->mentor_mobile_no)->first();
                $mentorName = $m ? $m->name : null;
            }

            return response()->json([
                'status' => 'SUCCESS',
                'classroomId' => $class->classroom_id,
                'batchYear' => $class->batch_year,
                'currentSemester' => $class->current_semester,
                'tutorName' => $tutorName,
                'mentorName' => $mentorName,
                'isClassTutor' => ($class->tutor_mobile_no === $tutorMobile),
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin: Get system metrics/statistics.
     */
    public function getAdminStats()
    {
        try {
            $totalStaff = StaffProfile::count();
            $totalStudents = Student::count();
            $pendingStaff = StaffProfile::where('account_status', 'Pending')->count();
            $pendingStudents = Student::where('status', 'Pending')->count();
            $totalClassrooms = ClassManagement::count();

            return response()->json([
                'status' => 'SUCCESS',
                'stats' => [
                    'totalStaff' => $totalStaff,
                    'totalStudents' => $totalStudents,
                    'pendingApprovals' => $pendingStaff + $pendingStudents,
                    'pendingStaff' => $pendingStaff,
                    'pendingStudents' => $pendingStudents,
                    'totalClassrooms' => $totalClassrooms,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch stats: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: Check if the logged-in user has permission to manage the target user.
     */
    private function checkUserManagementPermission($targetId, $targetType)
    {
        $currentUserId = Session::get('userId');
        $currentRole = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId) return false;

        // Super Admin, Principal, Admin, Chairman, and Workshop Superintendent have elevated access
        if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Chairman', 'Workshop_Superintendent'])) {
            return true;
        }

        // HOD check
        if ($currentRole === 'HOD') {
            if ($targetType === 'student') {
                $student = Student::where('reg_no', strtoupper($targetId))->first();
                return $student && strtoupper($student->branch) === strtoupper($currentBranch);
            } else {
                $staff = StaffProfile::where('mobile_no', $targetId)->first();
                if (!$staff) return false;
                
                // HOD can manage themselves
                if ($staff->mobile_no === $currentUserId) return true;

                // HOD can manage Faculty, Lecturer, Demonstrator, and Trade Instructor in their branch
                return strtoupper($staff->branch) === strtoupper($currentBranch) &&
                       in_array($staff->designation, ['Faculty', 'Lecturer', 'Demonstrator', 'Trade_Instructor', 'Physical_Instructor', 'Physical Instructor']);
            }
        }

        // Tutor check
        $supervisedClass = ClassManagement::where('tutor_mobile_no', $currentUserId)
            ->orWhere('mentor_mobile_no', $currentUserId)
            ->first();
            
        if ($supervisedClass && $targetType === 'student') {
            $student = Student::where('reg_no', strtoupper($targetId))->first();
            return $student && $student->classroom_id === $supervisedClass->classroom_id;
        }

        // Default: can only manage their own staff profile password
        if ($targetType === 'staff' && $targetId === $currentUserId) {
            return true;
        }

        return false;
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Fetch users scoped by permissions and filters.
     */
    public function getUsersList(Request $request)
    {
        $currentUserId = Session::get('userId');
        $currentRole = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $search = trim($request->query('search', ''));
        $branch = trim($request->query('branch', ''));
        $role = trim($request->query('role', '')); // 'student' or staff designation
        $status = trim($request->query('status', '')); // 'Pending' or 'Approved'

        // Determine supervised class (Tutor scope check)
        $supervisedClass = ClassManagement::where('tutor_mobile_no', $currentUserId)
            ->orWhere('mentor_mobile_no', $currentUserId)
            ->first();

        try {
            $users = [];

            // 1. Query Students
            $canQueryStudents = true;
            $studentScopeField = null;
            $studentScopeValue = null;

            if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Chairman', 'Workshop_Superintendent'])) {
                // Full visibility
            } elseif ($currentRole === 'HOD') {
                $studentScopeField = 'branch';
                $studentScopeValue = strtoupper($currentBranch);
            } elseif ($supervisedClass) {
                $studentScopeField = 'classroom_id';
                $studentScopeValue = $supervisedClass->classroom_id;
            } else {
                $canQueryStudents = false;
            }

            if ($canQueryStudents && (empty($role) || strtolower($role) === 'student')) {
                $studentQuery = Student::query();

                if ($studentScopeField) {
                    $studentQuery->where($studentScopeField, $studentScopeValue);
                }

                if (!empty($search)) {
                    $studentQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('reg_no', 'like', "%{$search}%")
                          ->orWhere('adm_no', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                }

                if (!empty($branch)) {
                    $studentQuery->where('branch', strtoupper($branch));
                }

                if (!empty($status)) {
                    $studentQuery->where('status', $status);
                }

                $students = $studentQuery->get()->map(function ($s) {
                    return [
                        'id' => $s->reg_no,
                        'name' => $s->name,
                        'email' => $s->email,
                        'role' => 'Student',
                        'branch' => $s->branch,
                        'status' => $s->status,
                        'academic_status' => $s->academic_status,
                        'status_notes' => $s->status_notes,
                        'photo_url' => $s->photo_url,
                        'type' => 'student',
                        'sbte_reg_no' => $s->sbte_reg_no,
                        'semester' => $s->semester ? 'S' . $s->semester : 'N/A',
                        'classroom_id' => $s->classroom_id,
                    ];
                })->toArray();

                $users = array_merge($users, $students);
            }

            // 2. Query Staff
            $canQueryStaff = true;
            $staffScopeFilter = null;

            if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Chairman', 'Workshop_Superintendent'])) {
                // Full visibility
            } elseif ($currentRole === 'HOD') {
                $staffScopeFilter = 'hod';
            } else {
                $staffScopeFilter = 'self';
            }

            if ($canQueryStaff && (empty($role) || strtolower($role) !== 'student')) {
                $staffQuery = StaffProfile::query();

                if ($staffScopeFilter === 'hod') {
                    $staffQuery->where(function($q) use ($currentBranch, $currentUserId) {
                        $q->where(function($sub) use ($currentBranch) {
                            $sub->where('branch', strtoupper($currentBranch))
                                ->whereIn('designation', ['Lecturer', 'Demonstrator', 'Trade_Instructor', 'Tradesman', 'Laboratory_Assistant', 'Workshop_Instructor', 'Physical_Instructor', 'Physical Instructor']);
                        })->orWhere('mobile_no', $currentUserId);
                    });
                } elseif ($staffScopeFilter === 'self') {
                    $staffQuery->where('mobile_no', $currentUserId);
                }

                if (!empty($search)) {
                    $staffQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('mobile_no', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                }

                if (!empty($branch)) {
                    $staffQuery->where('branch', strtoupper($branch));
                }

                if (!empty($role)) {
                    $staffQuery->where('designation', $role);
                }

                if (!empty($status)) {
                    $staffQuery->where('account_status', $status);
                }

                $staff = $staffQuery->get()->map(function ($f) {
                    return [
                        'id' => $f->mobile_no,
                        'name' => $f->name,
                        'email' => $f->email,
                        'role' => $f->designation,
                        'branch' => $f->branch,
                        'status' => $f->account_status,
                        'photo_url' => $f->photo_url,
                        'type' => 'staff',
                    ];
                })->toArray();

                $users = array_merge($users, $staff);
            }

            // Sort users by name alphabetically
            usort($users, function ($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });

            return response()->json([
                'status' => 'SUCCESS',
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch user directory: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Toggle user status (Approve, Suspend, Pending) and log to AuditTrail.
     */
    public function toggleUserStatus(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
            'newStatus' => 'required|string',
        ]);

        $userId = $request->input('userId');
        $userType = $request->input('userType');
        $newStatus = $request->input('newStatus'); // e.g. 'Approved', 'Pending', 'Suspended'

        if (!$this->checkUserManagementPermission($userId, $userType)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $targetName = '';
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($userId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
                }
                $targetName = $student->name;
                $updateData = ['status' => $newStatus];
                if (empty($student->classroom_id) && $student->branch && $student->admission_year) {
                    $isLet = ($student->admission_type === 'LET');
                    $startYear = $isLet ? ($student->admission_year - 1) : $student->admission_year;
                    $endYear = $startYear + 3;
                    $updateData['classroom_id'] = "{$student->branch}_{$startYear}_{$endYear}";
                }
                $student->update($updateData);
            } else {
                $staff = StaffProfile::where('mobile_no', $userId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
                }
                $targetName = $staff->name;
                
                // Enforce single Principal constraint if trying to approve another Principal
                if ($staff->designation === 'Principal' && $newStatus === 'Approved') {
                    $hasOtherPrincipal = StaffProfile::where('designation', 'Principal')
                        ->where('mobile_no', '!=', $userId)
                        ->where('account_status', 'Approved')
                        ->exists();
                    if ($hasOtherPrincipal) {
                        return response()->json(['status' => 'ERROR', 'message' => 'Cannot approve multiple active Principals.']);
                    }
                }

                $staff->update(['account_status' => $newStatus]);
            }

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $userId,
                'target_name' => $targetName,
                'action' => $newStatus,
                'details' => "Account status changed to: " . $newStatus,
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'User status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to toggle status: ' . $e->getMessage()]);
        }
    }

    public function updateAcademicStatus(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'academicStatus' => 'required|string',
            'statusNotes' => 'nullable|string'
        ]);

        $userId = $request->input('userId');
        
        if (!$this->checkUserManagementPermission($userId, 'student')) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $student = Student::where('reg_no', strtoupper($userId))->first();
            if (!$student) {
                return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
            }

            $student->update([
                'academic_status' => $request->input('academicStatus'),
                'status_notes' => $request->input('statusNotes')
            ]);

            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $userId,
                'target_name' => $student->name,
                'action' => 'Academic Status Update',
                'details' => "Status: {$request->input('academicStatus')}. Notes: {$request->input('statusNotes')}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    public function promoteBatch(Request $request)
    {
        $currentUserId = Session::get('userId');
        
        $supervisedClass = ClassManagement::where('tutor_mobile_no', $currentUserId)->first();
        if (!$supervisedClass) {
            return response()->json(['status' => 'ERROR', 'message' => 'You must be a tutor to promote a batch.']);
        }

        try {
            if ($supervisedClass->current_semester >= 6) {
                return response()->json(['status' => 'ERROR', 'message' => 'Batch is already at Semester 6 and cannot be promoted further.']);
            }

            $supervisedClass->current_semester += 1;
            $supervisedClass->save();

            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $supervisedClass->classroom_id,
                'target_name' => "Classroom {$supervisedClass->classroom_id}",
                'action' => 'Batch Promoted',
                'details' => "Promoted to Semester {$supervisedClass->current_semester}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'SUCCESS', 
                'new_semester' => $supervisedClass->current_semester
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Reset a user's password directly and log.
     */
    public function resetUserPassword(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
            'newPassword' => 'required|string|min:4',
        ]);

        $userId = $request->input('userId');
        $userType = $request->input('userType');
        $newPassword = $request->input('newPassword');

        if (!$this->checkUserManagementPermission($userId, $userType)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $targetName = '';
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($userId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
                }
                $targetName = $student->name;
                $student->update(['password' => $newPassword]);
            } else {
                $staff = StaffProfile::where('mobile_no', $userId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
                }
                $targetName = $staff->name;
                $staff->update(['password' => $newPassword]);
            }

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $userId,
                'target_name' => $targetName,
                'action' => 'Password Reset',
                'details' => 'Account password reset directly by administrator/supervisor',
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Password reset successful.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to reset password: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD: Change a staff member's role designation and log.
     */
    public function changeUserRole(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'newRole' => 'required|string',
        ]);

        $userId = $request->input('userId');
        $newRole = $request->input('newRole'); // designation string

        if (!$this->checkUserManagementPermission($userId, 'staff')) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $staff = StaffProfile::where('mobile_no', $userId)->first();
            if (!$staff) {
                return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
            }

            $oldRole = $staff->designation;

            // Enforce role checks
            if ($newRole === 'Principal') {
                $hasOtherPrincipal = StaffProfile::where('designation', 'Principal')
                    ->where('mobile_no', '!=', $userId)
                    ->where('account_status', 'Approved')
                    ->exists();
                if ($hasOtherPrincipal) {
                    return response()->json(['status' => 'ERROR', 'message' => 'An active Principal already exists in the system.']);
                }
            }

            $staff->update(['designation' => $newRole]);

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $userId,
                'target_name' => $staff->name,
                'action' => 'Role Changed',
                'details' => "Role designation changed from {$oldRole} to {$newRole}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Staff designation changed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to change role: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Delete a user permanently.
     */
    public function deleteUser(Request $request)
    {
        $request->validate([
            'targetId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
        ]);

        $targetId = $request->input('targetId');
        $userType = $request->input('userType');

        if (!$this->checkUserManagementPermission($targetId, $userType)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $targetName = '';
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($targetId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
                }
                $targetName = $student->name;
                $student->delete();
            } else {
                $staff = StaffProfile::where('mobile_no', $targetId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
                }
                $targetName = $staff->name;
                $staff->delete();
            }

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $targetId,
                'target_name' => $targetName,
                'action' => 'Deleted',
                'details' => "Account permanently removed from database ({$userType})",
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'User profile deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Delete failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor/Staff: Retrieve audit logs scoped by credentials.
     */
    public function getAuditLogs(Request $request)
    {
        $currentUserId = Session::get('userId');
        $currentRole = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $targetId = trim($request->query('targetId', ''));

        try {
            $query = AuditLog::query();

            // Scope query according to credentials:
            if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Workshop_Superintendent'])) {
                if (!empty($targetId)) {
                    $query->where('target_id', $targetId);
                }
            } elseif ($currentRole === 'HOD') {
                $query->where(function($q) use ($currentBranch, $currentUserId) {
                    $q->whereIn('target_id', function($sub) use ($currentBranch) {
                        $sub->select('mobile_no')->from('staff_profiles')->where('branch', strtoupper($currentBranch))
                            ->union(
                                \DB::table('students')->select('reg_no')->where('branch', strtoupper($currentBranch))
                            );
                    })->orWhere('performed_by', $currentUserId)
                      ->orWhere('target_id', $currentUserId);
                });

                if (!empty($targetId)) {
                    $query->where('target_id', $targetId);
                }
            } else {
                $supervisedClass = ClassManagement::where('tutor_mobile_no', $currentUserId)
                    ->orWhere('mentor_mobile_no', $currentUserId)
                    ->first();

                if ($supervisedClass) {
                    // Class Tutor - can see logs of classroom students, actions they performed, or their own profile
                    $query->where(function($q) use ($supervisedClass, $currentUserId) {
                        $q->whereIn('target_id', function($sub) use ($supervisedClass) {
                            $sub->select('reg_no')->from('students')->where('classroom_id', $supervisedClass->classroom_id);
                        })->orWhere('performed_by', $currentUserId)
                          ->orWhere('target_id', $currentUserId);
                    });

                    if (!empty($targetId)) {
                        $query->where('target_id', $targetId);
                    }
                } else {
                    // Regular staff members can only inspect logs involving themselves
                    $query->where(function($q) use ($currentUserId) {
                        $q->where('target_id', $currentUserId)
                          ->orWhere('performed_by', $currentUserId);
                    });
                }
            }

            $logs = $query->orderBy('created_at', 'desc')->take(200)->get();

            return response()->json([
                'status' => 'SUCCESS',
                'logs' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to query audit logs: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // HOD BATCH MANAGEMENT METHODS
    // -------------------------------------------------------------------------

    private function checkHodOrPrincipalAccess(Request $request, &$branchOut)
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || !in_array($currentRole, ['HOD', 'Principal', 'Super_Admin'])) {
            return false;
        }

        if (in_array($currentRole, ['Principal', 'Super_Admin'])) {
            $branchOut = $request->input('branch') ?? $request->query('branch') ?? $currentBranch;
        } else {
            $branchOut = $currentBranch;
        }
        return true;
    }

    /**
     * HOD: List all batches/classrooms for this HOD's department branch.
     */
    public function getHodBatches(Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = $branch;

        $filterStatus = $request->query('status', 'active');

        try {
            $query = ClassManagement::where('branch', strtoupper($currentBranch));
            
            if ($filterStatus === 'historical') {
                $query->where('current_semester', '>', 6);
            } else {
                $query->where('current_semester', '<=', 6);
            }

            $batches = $query->orderBy('batch_year', 'desc')
                ->get()
                ->map(function ($cls) {
                    $tutorName  = null;
                    $mentorName = null;

                    if ($cls->tutor_mobile_no) {
                        $tutor     = StaffProfile::where('mobile_no', $cls->tutor_mobile_no)->first();
                        $tutorName = $tutor ? $tutor->name : null;
                    }
                    if ($cls->mentor_mobile_no) {
                        $mentor     = StaffProfile::where('mobile_no', $cls->mentor_mobile_no)->first();
                        $mentorName = $mentor ? $mentor->name : null;
                    }

                    $studentCount = Student::getClassroomStudentsQuery($cls->classroom_id)->count();

                    $activeSem = $cls->current_semester ?: 1;
                    $subjects = \App\Models\BatchSubject::where('classroom_id', $cls->classroom_id)
                        ->where('semester', $activeSem)
                        ->get()
                        ->map(function ($subj) {
                            $staffNames = \App\Models\SubjectStaffAssignment::where('batch_subject_id', $subj->id)
                                ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
                                ->pluck('staff_profiles.name')
                                ->toArray();

                            $total = \App\Models\LessonPlan::where('batch_subject_id', $subj->id)->count();
                            $covered = \App\Models\LessonPlan::where('batch_subject_id', $subj->id)->where('status', 'Completed')->count();
                            $progress = $total > 0 ? round(($covered / $total) * 100) : 0;

                            return [
                                'subject_code' => $subj->subject_code,
                                'subject_name' => $subj->subject_name,
                                'staff_list'   => !empty($staffNames) ? implode(', ', $staffNames) : 'Unassigned',
                                'progress'     => $progress,
                            ];
                        })
                        ->toArray();

                    return [
                        'classroom_id'      => $cls->classroom_id,
                        'branch'            => $cls->branch,
                        'batch_year'        => $cls->batch_year,
                        'tutor_mobile_no'   => $cls->tutor_mobile_no,
                        'tutor_name'        => $tutorName,
                        'mentor_mobile_no'  => $cls->mentor_mobile_no,
                        'mentor_name'       => $mentorName,
                        'student_count'     => $studentCount,
                        'current_semester'  => $cls->current_semester,
                        'subjects'          => $subjects,
                    ];
                });

            return response()->json(['status' => 'SUCCESS', 'batches' => $batches]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch batches: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Create a new batch/classroom for this department.
     * Also backfills any unassigned students (classroom_id IS NULL) that match
     * the derived classroom_id (by branch + admission_year).
     */
    public function createHodBatch(\Illuminate\Http\Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = $branch;

        $isLET = filter_var($request->input('is_lateral_entry'), FILTER_VALIDATE_BOOLEAN);

        if ($isLET) {
            $request->validate([
                'admission_year' => 'required|integer|min:2000|max:2100',
            ]);
            $admYear = (int)$request->input('admission_year');
            $baseYear = $admYear - 1;
            $branchCode = strtoupper($currentBranch);
            $baseClassroomId = "{$branchCode}_{$baseYear}_" . ($baseYear + 3);

            $baseBatch = ClassManagement::where('classroom_id', $baseClassroomId)->first();
            if (!$baseBatch) {
                return response()->json(['status' => 'ERROR', 'message' => "Base admission batch {$baseClassroomId} must be created first before setting up its Lateral Entry batch."]);
            }
            $classroomId = "{$baseClassroomId}_LET";
            $semester = 3; // Starts at S3
            $tutorMobile = $baseBatch->tutor_mobile_no;
            $mentorMobile = $baseBatch->mentor_mobile_no;
        } else {
            $request->validate([
                'admission_year'    => 'required|integer|min:2000|max:2100',
                'tutor_mobile_no'   => 'nullable|string',
                'mentor_mobile_no'  => 'nullable|string',
                'current_semester'  => 'nullable|integer|min:1|max:8',
            ]);
            $admYear    = (int) $request->input('admission_year');
            $branchCode = strtoupper($currentBranch);
            $baseYear   = $admYear;
            $semester   = (int) $request->input('current_semester', 1);
            $classroomId = "{$branchCode}_{$baseYear}_" . ($baseYear + 3);
            $tutorMobile  = $request->input('tutor_mobile_no');
            $mentorMobile = $request->input('mentor_mobile_no');

            if ($tutorMobile) {
                $tutor = StaffProfile::where('mobile_no', $tutorMobile)->first();
                if (!$tutor || (strtoupper($tutor->branch) !== $branchCode && strtoupper($tutor->branch) !== 'GEN')) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Selected Tutor does not belong to your department.']);
                }
            }
            if ($mentorMobile) {
                $mentor = StaffProfile::where('mobile_no', $mentorMobile)->first();
                if (!$mentor || (strtoupper($mentor->branch) !== $branchCode && strtoupper($mentor->branch) !== 'GEN')) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Selected Mentor does not belong to your department.']);
                }
            }
        }

        // Check if batch already exists in R26 table
        if (\App\Models\R26ClassManagement::where('classroom_id', $classroomId)->exists()) {
            return response()->json(['status' => 'ERROR', 'message' => "A Revision 2026 batch with ID {$classroomId} already exists."]);
        }

        // Check if batch already exists
        $existing = ClassManagement::where('classroom_id', $classroomId)->first();
        if ($existing) {
            try {
                $existing->update([
                    'tutor_mobile_no'  => $tutorMobile  ?: $existing->tutor_mobile_no,
                    'mentor_mobile_no' => $mentorMobile ?: $existing->mentor_mobile_no,
                    'current_semester' => $semester,
                ]);

                // Update active students' semesters to match the rollover
                Student::where('classroom_id', $classroomId)
                    ->where('academic_status', 'Active')
                    ->update(['semester' => $semester]);

                AuditLog::create([
                    'performed_by'      => $currentUserId,
                    'performed_by_name' => Session::get('userName'),
                    'target_id'         => $classroomId,
                    'target_name'       => "Batch {$classroomId}",
                    'action'            => 'Batch Rollover',
                    'details'           => "HOD rolled over batch {$classroomId} to Semester {$semester}.",
                    'ip_address'        => $request->ip(),
                ]);

                return response()->json([
                    'status'       => 'SUCCESS',
                    'message'      => "Batch {$classroomId} rolled over/updated to Semester {$semester} successfully.",
                    'classroom_id' => $classroomId,
                    'backfilled'   => 0
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERROR', 'message' => 'Failed to update batch: ' . $e->getMessage()]);
            }
        }

        try {
            $batch = ClassManagement::create([
                'classroom_id'     => $classroomId,
                'branch'           => $branchCode,
                'batch_year'       => $baseYear,
                'tutor_mobile_no'  => $tutorMobile  ?: null,
                'mentor_mobile_no' => $mentorMobile ?: null,
                'current_semester' => $semester,
            ]);

            $backfilledCount = 0;
            if ($isLET) {
                // LET students remain in their base/home regular batch, but are dynamically counted
                // as part of the LET sub-classroom. We do not change their database classroom_id.
                $backfilledCount = Student::where('classroom_id', $baseClassroomId)
                    ->where(function($q) {
                        $q->where('reg_no', 'like', '%L')
                          ->orWhere('sbte_reg_no', 'like', '%L');
                    })
                    ->count();
            } else {
                $backfilledCount = Student::where('branch', $branchCode)
                    ->where('admission_year', $admYear)
                    ->whereNull('classroom_id')
                    ->update([
                        'classroom_id' => $classroomId,
                        'semester'     => $semester
                    ]);

                // Also handle LET students (they join in year 2 → admYear = startYear+1)
                $letBackfilled = Student::where('branch', $branchCode)
                    ->where('admission_year', $admYear + 1)
                    ->where('admission_type', 'LET')
                    ->whereNull('classroom_id')
                    ->update([
                        'classroom_id' => $classroomId,
                        'semester'     => $semester
                    ]);

                $backfilledCount += $letBackfilled;
            }

            AuditLog::create([
                'performed_by'      => $currentUserId,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Batch {$classroomId}",
                'action'            => 'Batch Created',
                'details'           => "HOD created batch {$classroomId} for admission year {$admYear}. Backfilled {$backfilledCount} student(s).",
                'ip_address'        => $request->ip(),
            ]);

            return response()->json([
                'status'          => 'SUCCESS',
                'message'         => "Batch {$classroomId} created successfully. {$backfilledCount} existing student(s) auto-assigned.",
                'classroom_id'    => $classroomId,
                'backfilled'      => $backfilledCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to create batch: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Update batch active current semester
     */
    public function updateBatchSemester(\Illuminate\Http\Request $request, $classroomId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }
        $request->validate(['current_semester' => 'required|integer|min:1|max:8']);

        try {
            $batch = ClassManagement::where('classroom_id', $classroomId)->first();
            if (!$batch) {
                $batch = \App\Models\R26ClassManagement::where('classroom_id', $classroomId)->first();
            }
            if (!$batch || strtoupper($batch->branch) !== strtoupper($branch)) {
                return response()->json(['status' => 'ERROR', 'message' => 'Invalid batch or department mismatch.']);
            }

            $newSemester = $request->input('current_semester');
            $batch->update(['current_semester' => $newSemester]);

            // Promote ONLY active/approved students in this batch to the new semester
            \App\Models\Student::getClassroomStudentsQuery($classroomId)
                ->whereIn('status', ['APPROVED', 'Approved', 'Active', 'active'])
                ->where(function($q) {
                    $q->whereNull('academic_status')
                      ->orWhere('academic_status', 'Active');
                })
                ->update(['semester' => $newSemester]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Batch current semester updated. Active students promoted.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Assign (or change) a Tutor for an existing batch.
     */
    public function assignBatchTutor(\Illuminate\Http\Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = $branch;

        $request->validate([
            'classroom_id'    => 'required|string',
            'tutor_mobile_no' => 'nullable|string',
        ]);

        $classroomId = $request->input('classroom_id');
        $tutorMobile = $request->input('tutor_mobile_no');

        $batch = ClassManagement::where('classroom_id', $classroomId)
            ->where('branch', $currentBranch)
            ->first();
        if (!$batch) {
            $batch = \App\Models\R26ClassManagement::where('classroom_id', $classroomId)
                ->where('branch', $currentBranch)
                ->first();
        }
        if (!$batch) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
        }

        $oldTutor = $batch->tutor_mobile_no;

        if (empty($tutorMobile)) {
            $batch->update(['tutor_mobile_no' => null]);
            AuditLog::create([
                'performed_by'      => $currentUserId,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Batch {$classroomId}",
                'action'            => 'Tutor Removed',
                'details'           => "Tutor removed. Previous: " . ($oldTutor ?: 'None'),
                'ip_address'        => $request->ip(),
            ]);
            return response()->json([
                'status'     => 'SUCCESS',
                'message'    => "Tutor has been removed for batch {$classroomId}.",
                'tutor_name' => null,
            ]);
        }

        $tutor = StaffProfile::where('mobile_no', $tutorMobile)->first();
        if (!$tutor || strtoupper($tutor->branch) !== strtoupper($currentBranch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Selected staff member does not belong to your department.']);
        }

        $batch->update(['tutor_mobile_no' => $tutorMobile]);

        AuditLog::create([
            'performed_by'      => $currentUserId,
            'performed_by_name' => Session::get('userName'),
            'target_id'         => $classroomId,
            'target_name'       => "Batch {$classroomId}",
            'action'            => 'Tutor Assigned',
            'details'           => "Tutor set to {$tutor->name} ({$tutorMobile}). Previous: " . ($oldTutor ?: 'None'),
            'ip_address'        => $request->ip(),
        ]);

        return response()->json([
            'status'     => 'SUCCESS',
            'message'    => "{$tutor->name} has been set as Tutor for batch {$classroomId}.",
            'tutor_name' => $tutor->name,
        ]);
    }

    /**
     * HOD: Assign (or change) a Mentor for an existing batch.
     */
    public function assignBatchMentor(\Illuminate\Http\Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = $branch;

        $request->validate([
            'classroom_id'     => 'required|string',
            'mentor_mobile_no' => 'nullable|string',
        ]);

        $classroomId  = $request->input('classroom_id');
        $mentorMobile = $request->input('mentor_mobile_no');

        $batch = ClassManagement::where('classroom_id', $classroomId)
            ->where('branch', $currentBranch)
            ->first();
        if (!$batch) {
            $batch = \App\Models\R26ClassManagement::where('classroom_id', $classroomId)
                ->where('branch', $currentBranch)
                ->first();
        }
        if (!$batch) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
        }

        $oldMentor = $batch->mentor_mobile_no;

        if (empty($mentorMobile)) {
            $batch->update(['mentor_mobile_no' => null]);
            AuditLog::create([
                'performed_by'      => $currentUserId,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Batch {$classroomId}",
                'action'            => 'Mentor Removed',
                'details'           => "Mentor removed. Previous: " . ($oldMentor ?: 'None'),
                'ip_address'        => $request->ip(),
            ]);
            return response()->json([
                'status'      => 'SUCCESS',
                'message'     => "Mentor has been removed for batch {$classroomId}.",
                'mentor_name' => null,
            ]);
        }

        $mentor = StaffProfile::where('mobile_no', $mentorMobile)->first();
        if (!$mentor || strtoupper($mentor->branch) !== strtoupper($currentBranch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Selected staff member does not belong to your department.']);
        }

        $batch->update(['mentor_mobile_no' => $mentorMobile]);

        AuditLog::create([
            'performed_by'      => $currentUserId,
            'performed_by_name' => Session::get('userName'),
            'target_id'         => $classroomId,
            'target_name'       => "Batch {$classroomId}",
            'action'            => 'Mentor Assigned',
            'details'           => "Mentor set to {$mentor->name} ({$mentorMobile}). Previous: " . ($oldMentor ?: 'None'),
            'ip_address'        => $request->ip(),
        ]);

        return response()->json([
            'status'      => 'SUCCESS',
            'message'     => "{$mentor->name} has been set as Mentor for batch {$classroomId}.",
            'mentor_name' => $mentor->name,
        ]);
    }

    public function getBatchStudents(\Illuminate\Http\Request $request, $classroomId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = $branch;

        $batch = ClassManagement::where('classroom_id', $classroomId)
            ->where('branch', strtoupper($currentBranch))
            ->first();
        if (!$batch) {
            $batch = \App\Models\R26ClassManagement::where('classroom_id', $classroomId)
                ->where('branch', strtoupper($currentBranch))
                ->first();
        }
        if (!$batch) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
        }

        try {
            $students = Student::getClassroomStudentsQuery($classroomId)
                ->orderBy('name')
                ->get()
                ->map(function ($s) {
                    return [
                        'reg_no'         => $s->reg_no,
                        'adm_no'         => $s->adm_no,
                        'name'           => $s->name,
                        'email'          => $s->email,
                        'phone'          => $s->phone,
                        'admission_year' => $s->admission_year,
                        'admission_type' => $s->admission_type,
                        'status'         => $s->status,
                        'photo_url'      => $s->photo_url,
                        'semester'       => $s->semester,
                    ];
                });

            return response()->json(['status' => 'SUCCESS', 'students' => $students]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch students: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Get all staff members in the HOD's department (for tutor/mentor dropdowns).
     */
    public function getDeptStaff(Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD access required.']);
        }
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = $branch;

        try {
            $staff = StaffProfile::where('branch', $currentBranch)
                ->where('account_status', 'Approved')
                ->whereNotIn('designation', ['HOD', 'Principal', 'Super_Admin', 'Admin'])
                ->orderBy('name')
                ->get()
                ->map(function ($f) {
                    return [
                        'mobile_no'   => $f->mobile_no,
                        'name'        => $f->name,
                        'designation' => $f->designation,
                        'photo_url'   => $f->photo_url,
                    ];
                });

            return response()->json(['status' => 'SUCCESS', 'staff' => $staff]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch department staff: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Get Subjects for a Batch
     */
    public function getBatchSubjects(Request $request, $classroomId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }

        $semester = $request->query('semester');
        try {
            $query = BatchSubject::with(['staffAssignments.staffProfile', 'courseFile'])->where('classroom_id', $classroomId);
            if ($semester) {
                $query->where('semester', $semester);
            }
            
            $subjects = $query->get()->map(function ($subj) use ($classroomId) {
                $totalHoursAllotted = \App\Models\LessonPlan::where('batch_subject_id', $subj->id)->sum('allocated_hours') ?: 0;
                $hoursCompleted = \DB::table('class_logs_attendance')->where('batch_subject_id', $subj->id)->count();
                
                $assignmentCount = \App\Models\AcademicMark::where(function($q) use ($subj) {
                        $q->where('batch_subject_id', $subj->id)
                          ->orWhere(function($subQ) use ($subj) {
                              $subQ->whereNull('batch_subject_id')
                                   ->where('subject_code', $subj->subject_code);
                          });
                    })
                    ->where('category', 'Assignment')
                    ->distinct('co_tag')
                    ->count('co_tag');
                $assignmentInitiated = $assignmentCount > 0 ? "{$assignmentCount} / 4 COs Graded" : 'Not Initiated';

                $writtenTestCount = \App\Models\AcademicMark::where(function($q) use ($subj) {
                        $q->where('batch_subject_id', $subj->id)
                          ->orWhere(function($subQ) use ($subj) {
                              $subQ->whereNull('batch_subject_id')
                                   ->where('subject_code', $subj->subject_code);
                          });
                    })
                    ->where('category', 'Written Test')
                    ->distinct('co_tag')
                    ->count('co_tag');
                $writtenTestInitiated = $writtenTestCount > 0 ? "{$writtenTestCount} / 4 COs Graded" : 'Not Initiated';

                $midSem = \DB::table('mid_semester_surveys')->where('batch_subject_id', $subj->id)->first();
                $midSemSurveyStatus = $midSem ? $midSem->status : 'Not Initiated';

                $endSem = \DB::table('course_exit_surveys')->where('batch_subject_id', $subj->id)->first();
                $endSemSurveyStatus = $endSem ? $endSem->status : 'Not Initiated';

                $mcqTestCount = \DB::table('test_configs')
                    ->where('classroom_id', $classroomId)
                    ->where('subject_code', $subj->subject_code)
                    ->where('mcq_count', '>', 0)
                    ->count();
                $mcqStatus = $mcqTestCount > 0 ? "{$mcqTestCount} Tests Created" : 'Not Initiated';

                return [
                    'id' => $subj->id,
                    'semester' => $subj->semester,
                    'subject_code' => $subj->subject_code,
                    'syllabus_revision_code' => $subj->syllabus_revision_code,
                    'subject_name' => $subj->subject_name,
                    'subject_type' => $subj->subject_type,
                    'course_file_status' => $subj->courseFile ? 'Submitted' : 'Pending',
                    'total_hours_allotted' => $totalHoursAllotted,
                    'hours_completed' => $hoursCompleted,
                    'assignment_initiated' => $assignmentInitiated,
                    'written_test_initiated' => $writtenTestInitiated,
                    'mid_sem_survey_status' => $midSemSurveyStatus,
                    'end_sem_survey_status' => $endSemSurveyStatus,
                    'mcq_status' => $mcqStatus,
                    'staff' => $subj->staffAssignments->map(function ($sa) {
                        return [
                            'mobile_no' => $sa->staff_mobile_no,
                            'name' => $sa->staffProfile ? $sa->staffProfile->name : 'Unknown',
                            'branch' => $sa->staffProfile ? $sa->staffProfile->branch : '',
                        ];
                    })
                ];
            });

            // Also fetch ALL approved staff across the college for the assignment dropdown
            // To support inter-department lecturer allocation
            $allStaff = StaffProfile::where('account_status', 'Approved')
                ->whereNotIn('designation', ['Principal', 'Super_Admin', 'Admin'])
                ->orderBy('branch')
                ->orderBy('name')
                ->get()
                ->map(function ($f) {
                    return [
                        'mobile_no' => $f->mobile_no,
                        'name' => $f->name,
                        'branch' => $f->branch,
                        'designation' => $f->designation,
                    ];
                });

            return response()->json([
                'status' => 'SUCCESS', 
                'subjects' => $subjects,
                'all_staff' => $allStaff
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Create a Subject for a Batch
     */
    public function createBatchSubject(Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $request->validate([
            'classroom_id' => 'required|string',
            'semester' => 'required|integer|min:1|max:6',
            'subject_code' => 'required|string',
            'subject_name' => 'required|string',
            'subject_type' => 'required|string'
        ]);

        try {
            // Verify HOD branch vs classroom branch
            $classroom = ClassManagement::where('classroom_id', $request->classroom_id)->first();
            if (!$classroom) {
                $classroom = \App\Models\R26ClassManagement::where('classroom_id', $request->classroom_id)->first();
            }
            if (!$classroom || $classroom->branch !== $branch) {
                return response()->json(['status' => 'ERROR', 'message' => 'Invalid classroom.']);
            }

            BatchSubject::create([
                'classroom_id' => $request->classroom_id,
                'semester' => $request->semester,
                'subject_code' => strtoupper($request->subject_code),
                'subject_name' => $request->subject_name,
                'subject_type' => $request->subject_type,
                'syllabus_revision_code' => $request->syllabus_revision_code ?? 'REV2021'
            ]);

            // Automatically update the batch's active semester to match the subject's semester
            $classroom->update(['current_semester' => $request->semester]);

            // Update all active students in this batch to this semester
            Student::getClassroomStudentsQuery($request->classroom_id)
                ->where('academic_status', 'Active')
                ->update(['semester' => $request->semester]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Subject created successfully. Active batch semester synced.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Assign Staff to Subject
     */
    public function assignSubjectStaff(Request $request, $subjectId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $request->validate([
            'staff_mobile_nos' => 'array',
            'staff_mobile_nos.*' => 'string'
        ]);

        try {
            $subject = BatchSubject::find($subjectId);
            if (!$subject) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
            }
            $cls = ClassManagement::where('classroom_id', $subject->classroom_id)->first();
            if (!$cls) {
                $cls = \App\Models\R26ClassManagement::where('classroom_id', $subject->classroom_id)->first();
            }
            if (!$cls || $cls->branch !== $branch) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found or unauthorized.']);
            }

            // Sync the assignments
            SubjectStaffAssignment::where('batch_subject_id', $subjectId)->delete();
            
            $staffNos = $request->staff_mobile_nos ?? [];
            foreach ($staffNos as $staffNo) {
                SubjectStaffAssignment::create([
                    'batch_subject_id' => $subjectId,
                    'staff_mobile_no' => $staffNo
                ]);
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Staff assigned successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Delete Subject
     */
    public function deleteBatchSubject(Request $request, $subjectId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $subject = BatchSubject::find($subjectId);
            if (!$subject) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
            }
            $cls = ClassManagement::where('classroom_id', $subject->classroom_id)->first();
            if (!$cls) {
                $cls = \App\Models\R26ClassManagement::where('classroom_id', $subject->classroom_id)->first();
            }
            if (!$cls || $cls->branch !== $branch) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found or unauthorized.']);
            }

            $subject->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Subject deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Update Subject (correct a subject name, code, type or syllabus revision)
     */
    public function updateBatchSubject(Request $request, $subjectId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $request->validate([
            'subject_code' => 'required|string|max:20',
            'subject_name' => 'required|string|max:255',
            'subject_type' => 'required|string',
            'syllabus_revision_code' => 'nullable|string|max:20',
        ]);

        try {
            $subject = BatchSubject::find($subjectId);
            if (!$subject) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
            }
            $cls = ClassManagement::where('classroom_id', $subject->classroom_id)->first();
            if (!$cls) {
                $cls = \App\Models\R26ClassManagement::where('classroom_id', $subject->classroom_id)->first();
            }
            if (!$cls || $cls->branch !== $branch) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found or unauthorized.']);
            }

            $subject->update([
                'subject_code'           => strtoupper(trim($request->subject_code)),
                'subject_name'           => trim($request->subject_name),
                'subject_type'           => $request->subject_type,
                'syllabus_revision_code' => $request->syllabus_revision_code ?? $subject->syllabus_revision_code,
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Subject updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }


    /**
     * HOD: Graduate / Archive a batch.
     * Only allowed when current_semester = 6 (final semester).
     * Sets current_semester = 7 → moves batch to Previous Batches list.
     * Marks all Active students as Graduated.
     * PURELY ADDITIVE — does not change any existing methods.
     */
    public function graduateBatch(Request $request, $classroomId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $batch = \App\Models\ClassManagement::where('classroom_id', $classroomId)
                ->where('branch', strtoupper($branch))
                ->first();

            if (!$batch) {
                $batch = \App\Models\R26ClassManagement::where('classroom_id', $classroomId)
                    ->where('branch', strtoupper($branch))
                    ->first();
            }

            if (!$batch) {
                return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
            }

            if ((int) $batch->current_semester !== 6) {
                return response()->json(['status' => 'ERROR', 'message' => 'Only S6 batches can be graduated. Current semester: S' . $batch->current_semester]);
            }

            // Move to Previous Batches by setting semester to 7
            $batch->update(['current_semester' => 7]);

            // Mark all Active students in this batch as Graduated
            $count = \App\Models\Student::getClassroomStudentsQuery($classroomId)
                ->whereIn('status', ['active', 'Active'])
                ->update(['status' => 'Graduated', 'academic_status' => 'Graduated']);

            return response()->json([
                'status'              => 'SUCCESS',
                'message'             => 'Batch graduated and archived to Previous Batches.',
                'students_graduated'  => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Permanently delete a batch (only allowed if it has NO enrolled students).
     */
    public function deleteHodBatch(Request $request, $classroomId)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $batch = \App\Models\ClassManagement::where('classroom_id', $classroomId)
                ->where('branch', strtoupper($branch))
                ->first();

            if (!$batch) {
                $batch = \App\Models\R26ClassManagement::where('classroom_id', $classroomId)
                    ->where('branch', strtoupper($branch))
                    ->first();
            }

            if (!$batch) {
                return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
            }

            // Safety check: refuse delete if there are enrolled students
            $studentCount = \App\Models\Student::getClassroomStudentsQuery($classroomId)->count();
            if ($studentCount > 0) {
                return response()->json([
                    'status'  => 'ERROR',
                    'message' => "Cannot delete batch '{$classroomId}' — it has {$studentCount} enrolled student(s). Remove or transfer students first.",
                ]);
            }

            // Delete all related batch subjects and their staff assignments
            $subjectIds = \App\Models\BatchSubject::where('classroom_id', $classroomId)->pluck('id');
            if ($subjectIds->isNotEmpty()) {
                \App\Models\SubjectStaffAssignment::whereIn('batch_subject_id', $subjectIds)->delete();
                \App\Models\BatchSubject::whereIn('id', $subjectIds)->delete();
            }

            // Delete the classroom record
            $batch->delete();

            AuditLog::create([
                'performed_by'      => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Batch {$classroomId}",
                'action'            => 'Batch Deleted',
                'details'           => "HOD permanently deleted batch {$classroomId}.",
                'ip_address'        => $request->ip(),
            ]);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => "Batch {$classroomId} has been permanently deleted.",
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Get a full per-semester academic snapshot for a batch.
     * Returns subjects+staff, student attendance, and board results for the given semester.
     * PURELY ADDITIVE — does not change any existing methods.
     */
    public function getBatchSemesterSnapshot(Request $request, $classroomId, $semester)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $classroom = \App\Models\ClassManagement::where('classroom_id', $classroomId)
                ->where('branch', strtoupper($branch))
                ->first();
            if (!$classroom) {
                $classroom = \App\Models\R26ClassManagement::where('classroom_id', $classroomId)
                    ->where('branch', strtoupper($branch))
                    ->first();
            }
            if (!$classroom) {
                return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
            }

            // ---- 1. Subjects & Staff ----
            $subjects = \App\Models\BatchSubject::where('classroom_id', $classroomId)
                ->where('semester', $semester)
                ->get()
                ->map(function ($subj) {
                    $staffNames = \App\Models\SubjectStaffAssignment::where('batch_subject_id', $subj->id)
                        ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
                        ->pluck('staff_profiles.name')
                        ->toArray();

                    $classesConducted = \DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $subj->id)
                        ->count();

                    $courseFile = \DB::table('cf_course_files')
                        ->where('batch_subject_id', $subj->id)
                        ->first();

                    return [
                        'subject_code'       => $subj->subject_code,
                        'subject_name'       => $subj->subject_name,
                        'subject_type'       => $subj->subject_type,
                        'staff'              => $staffNames,
                        'classes_conducted'  => $classesConducted,
                        'course_file_status' => $courseFile ? 'Submitted' : 'Pending',
                    ];
                });

            // ---- 2. Students + Attendance ----
            $students = \App\Models\Student::getClassroomStudentsQuery($classroomId)
                ->orderBy('roll_no')
                ->orderBy('name')
                ->get();

            // Get all batch_subject IDs for this semester
            $subjectIds = \App\Models\BatchSubject::where('classroom_id', $classroomId)
                ->where('semester', $semester)
                ->pluck('id', 'subject_code');

            $studentData = $students->map(function ($s) use ($subjectIds) {
                $subjAttendance = [];
                $totalPresent   = 0;
                $totalClasses   = 0;

                foreach ($subjectIds as $code => $bsId) {
                    $total   = \DB::table('student_attendance')
                        ->where('batch_subject_id', $bsId)
                        ->where('reg_no', $s->reg_no)
                        ->count();
                    $present = \DB::table('student_attendance')
                        ->where('batch_subject_id', $bsId)
                        ->where('reg_no', $s->reg_no)
                        ->where('status', 'Present')
                        ->count();

                    if ($total > 0) {
                        $subjAttendance[] = [
                            'subject_code' => $code,
                            'present'      => $present,
                            'total'        => $total,
                            'percent'      => round(($present / $total) * 100),
                        ];
                        $totalPresent += $present;
                        $totalClasses += $total;
                    }
                }

                return [
                    'reg_no'                     => $s->reg_no,
                    'roll_no'                    => $s->roll_no,
                    'name'                       => $s->name,
                    'academic_status'            => $s->academic_status ?? $s->status,
                    'overall_attendance_percent' => $totalClasses > 0 ? round(($totalPresent / $totalClasses) * 100) : null,
                    'subject_attendance'         => $subjAttendance,
                ];
            });

            // ---- 3. Board Results ----
            $regNos = $students->pluck('reg_no')->toArray();
            $semMarks = \App\Models\StudentSemesterMarks::whereIn('reg_no', $regNos)
                ->where('semester', $semester)
                ->get()
                ->keyBy('reg_no');

            $semSummary = \DB::table('student_semester_summaries')
                ->whereIn('reg_no', $regNos)
                ->where('semester', $semester)
                ->get()
                ->keyBy('reg_no');

            $boardResults = $students->map(function ($s) use ($semMarks, $semSummary) {
                $summary = $semSummary->get($s->reg_no);
                $mark    = $semMarks->get($s->reg_no);
                return [
                    'reg_no'      => $s->reg_no,
                    'roll_no'     => $s->roll_no,
                    'name'        => $s->name,
                    'result'      => $summary->result ?? null,
                    'sgpa'        => $summary->sgpa ?? null,
                    'board_marks' => $mark->board_marks ?? null,
                ];
            })->filter(fn($r) => $r['result'] || $r['sgpa'])->values();

            return response()->json([
                'status'        => 'SUCCESS',
                'semester'      => (int) $semester,
                'classroom_id'  => $classroomId,
                'subjects'      => $subjects,
                'students'      => $studentData,
                'board_results' => $boardResults,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * LECTURER: Get all batches assigned to the lecturer (Tutor, Mentor, Subject Staff)
     */
    public function getLecturerBatches(Request $request)
    {
        $userId = \Illuminate\Support\Facades\Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $filterStatus = $request->query('status', 'active'); // 'active' or 'historical'

        try {
            // 1. Get batches where user is Tutor or Mentor
            $managedQuery = \App\Models\ClassManagement::where(function($q) use ($userId) {
                $q->where('tutor_mobile_no', $userId)
                  ->orWhere('mentor_mobile_no', $userId);
            });
            $r26ManagedQuery = \App\Models\R26ClassManagement::where(function($q) use ($userId) {
                $q->where('tutor_mobile_no', $userId)
                  ->orWhere('mentor_mobile_no', $userId);
            });

            if ($filterStatus === 'historical') {
                $managedQuery->where('current_semester', '>', 6);
                $r26ManagedQuery->where('current_semester', '>', 6);
            } else {
                $managedQuery->where('current_semester', '<=', 6);
                $r26ManagedQuery->where('current_semester', '<=', 6);
            }
            $managedBatches = $managedQuery->get();
            $r26ManagedBatches = $r26ManagedQuery->get();

            // 2. Get batches where user is assigned to a subject
            $subjectAssignments = \App\Models\SubjectStaffAssignment::with(['batchSubject'])
                ->where('staff_mobile_no', $userId)
                ->get();

            $batchesMap = [];

            // Add managed batches (2021)
            foreach ($managedBatches as $batch) {
                $cid = $batch->classroom_id;
                if (!isset($batchesMap[$cid])) {
                    $batchesMap[$cid] = [
                        'classroom_id'     => $batch->classroom_id,
                        'batch_year'       => $batch->batch_year,
                        'current_semester' => $batch->current_semester,
                        'branch'           => $batch->branch,
                        'scheme'           => 'R2021',
                        'student_count'    => \App\Models\Student::where('classroom_id', $batch->classroom_id)->count(),
                        'roles'            => [],
                        'subjects'         => []
                    ];
                }
                if ($batch->tutor_mobile_no === $userId) $batchesMap[$cid]['roles'][] = 'Tutor';
                if ($batch->mentor_mobile_no === $userId) $batchesMap[$cid]['roles'][] = 'Mentor';
            }

            // Add managed batches (2026)
            foreach ($r26ManagedBatches as $batch) {
                $cid = $batch->classroom_id;
                if (!isset($batchesMap[$cid])) {
                    $batchesMap[$cid] = [
                        'classroom_id'     => $batch->classroom_id,
                        'batch_year'       => $batch->batch_year,
                        'current_semester' => $batch->current_semester,
                        'branch'           => $batch->branch,
                        'scheme'           => 'R2026',
                        'student_count'    => \App\Models\Student::where('classroom_id', $batch->classroom_id)->count(),
                        'roles'            => [],
                        'subjects'         => []
                    ];
                }
                if ($batch->tutor_mobile_no === $userId) $batchesMap[$cid]['roles'][] = 'Tutor';
                if ($batch->mentor_mobile_no === $userId) $batchesMap[$cid]['roles'][] = 'Mentor';
            }

            // Add subject assignments
            foreach ($subjectAssignments as $sa) {
                if ($sa->batchSubject) {
                    $batch = $sa->batchSubject->classroom; // Check 2021 relationship
                    $isR26 = false;
                    if (!$batch) {
                        // Fallback to R26 Class Management
                        $batch = \App\Models\R26ClassManagement::where('classroom_id', $sa->batchSubject->classroom_id)->first();
                        $isR26 = true;
                    }
                    if (!$batch) continue;

                    $subjectSem = (int) $sa->batchSubject->semester;
                    $currentSem = (int) $batch->current_semester;
                    
                    // Filter based on status
                    if ($filterStatus === 'historical') {
                        // Historical means the subject was taught in a previous semester OR the whole batch is completed (>6)
                        if ($subjectSem >= $currentSem && $currentSem <= 6) {
                            continue; // Skip active subjects when requesting historical
                        }
                    } else {
                        // Active means the subject is for the current semester (or future), AND batch is not completed
                        if ($subjectSem < $currentSem || $currentSem > 6) {
                            continue; // Skip historical subjects when requesting active
                        }
                    }

                    $cid = $batch->classroom_id;
                    if (!isset($batchesMap[$cid])) {
                        $batchesMap[$cid] = [
                            'classroom_id'     => $batch->classroom_id,
                            'batch_year'       => $batch->batch_year,
                            'current_semester' => $batch->current_semester,
                            'branch'           => $batch->branch,
                            'scheme'           => $isR26 ? 'R2026' : (str_contains($batch->classroom_id, '2026') ? 'R2026' : 'R2021'),
                            'student_count'    => \App\Models\Student::where('classroom_id', $batch->classroom_id)->count(),
                            'roles'            => [],
                            'subjects'         => []
                        ];
                    }
                    if (!in_array('Subject Staff', $batchesMap[$cid]['roles'])) {
                        $batchesMap[$cid]['roles'][] = 'Subject Staff';
                    }
                    $subjId = $sa->batchSubject->id;
                    $totalTopics = \App\Models\LessonPlan::where('batch_subject_id', $subjId)->count();
                    $coveredTopics = \App\Models\LessonPlan::where('batch_subject_id', $subjId)->where('status', 'Completed')->count();
                    $engagedHours = \DB::table('class_logs_attendance')->where('batch_subject_id', $subjId)->count();
                    $totalHours = \App\Models\LessonPlan::where('batch_subject_id', $subjId)->sum('allocated_hours') ?: 0;

                    $batchesMap[$cid]['subjects'][] = [
                        'id' => $sa->batchSubject->id,
                        'code' => $sa->batchSubject->subject_code,
                        'name' => $sa->batchSubject->subject_name,
                        'semester' => $sa->batchSubject->semester,
                        'type' => $sa->batchSubject->subject_type,
                        'syllabus_revision_code' => $sa->batchSubject->syllabus_revision_code,
                        'total_topics' => $totalTopics,
                        'covered_topics' => $coveredTopics,
                        'engaged_hours' => $engagedHours,
                        'total_hours' => $totalHours
                    ];
                }
            }

            // 3. Seminar Evaluator is handled separately now; no need to inject into batch list.

            // Sort subjects by semester
            foreach ($batchesMap as &$b) {
                usort($b['subjects'], function($a, $b_item) {
                    return $a['semester'] <=> $b_item['semester'];
                });
            }

            return response()->json(['status' => 'SUCCESS', 'batches' => array_values($batchesMap)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * LECTURER: Fetch students of a specific classroom.
     */
    public function getClassroomStudents($classroomId)
    {
        $userId = \Illuminate\Support\Facades\Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $students = \App\Models\Student::where('classroom_id', $classroomId)
            ->where('status', 'Approved')
            ->orderBy('name')
            ->get(['reg_no', 'name', 'email', 'phone', 'photo_url', 'branch']);

        return response()->json([
            'status' => 'SUCCESS',
            'students' => $students
        ]);
    }

    /**
     * STUDENT: Get academic report (semester wise)
     */
    public function getAcademicReport()
    {
        $regNo = Session::get('userId');
        if (!$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $student = \App\Models\Student::where('reg_no', $regNo)->first();
            if (!$student) throw new \Exception("Student not found");

            // Summaries
            $summaries = DB::table('student_semester_summary')
                ->where('reg_no', $regNo)
                ->orderBy('semester', 'asc')
                ->get();

            $classroomId = $student->classroom_id;
            $classroom = $classroomId ? DB::table('class_management')->where('classroom_id', $classroomId)->first() : null;
            $currentSem = $student->semester ?: ($classroom ? (int)$classroom->current_semester : 1);

            // Auto-create a default Seminar type subject if none exists for this classroom and semester
            if (!empty($classroomId)) {
                $hasSeminarSubject = \App\Models\BatchSubject::where('classroom_id', $classroomId)
                    ->where('semester', $currentSem)
                    ->where('subject_type', 'Seminar')
                    ->exists();

                if (!$hasSeminarSubject) {
                    $branchKey = strtoupper(explode('_', $classroomId)[0] ?? 'EL');
                    \App\Models\BatchSubject::create([
                        'classroom_id' => $classroomId,
                        'semester' => $currentSem,
                        'subject_code' => $branchKey . '-5008',
                        'subject_name' => 'Seminar',
                        'subject_type' => 'Seminar',
                        'credits' => 1
                    ]);
                }
            }

            $isLetStudent = str_ends_with(strtoupper($regNo), 'L') || str_ends_with(strtoupper($student->sbte_reg_no ?? ''), 'L');
            $batchSubjects = \App\Models\BatchSubject::where(function($q) use ($classroomId, $isLetStudent) {
                    if (!empty($classroomId)) {
                        $q->where('classroom_id', $classroomId);
                        if ($isLetStudent) {
                            $q->orWhere('classroom_id', $classroomId . '_LET');
                        }
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                })
                ->orderBy('semester', 'asc')
                ->get();

            $academicMarks = DB::table('academic_marks')
                ->where('reg_no', $regNo)
                ->get()
                ->groupBy('subject_code');

            $taskSubmissions = DB::table('student_task_submissions')
                ->where('reg_no', $regNo)
                ->get();

            $boardGrades = DB::table('student_board_grades')
                ->where('reg_no', $regNo)
                ->get()
                ->groupBy('subject_code');

            // Calculate attendance dynamically from class_logs_attendance
            $attendanceMap = [];
            foreach ($batchSubjects as $subj) {
                $logs = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $subj->id)
                    ->get(['present_students', 'absent_students']);

                $present = 0;
                $absent = 0;
                foreach ($logs as $log) {
                    $presentList = json_decode($log->present_students ?? '[]', true);
                    $absentList = json_decode($log->absent_students ?? '[]', true);

                    if (is_array($presentList) && in_array($regNo, $presentList)) {
                        $present++;
                    } elseif (is_array($absentList) && in_array($regNo, $absentList)) {
                        $absent++;
                    }
                }

                $attendanceMap[$subj->subject_code] = [
                    'Present' => $present,
                    'Absent' => $absent,
                    'Late' => 0
                ];
            }

            // Calculate SGPA and CGPA dynamically from student_board_grades
            $allGrades = DB::table('student_board_grades')
                ->where('reg_no', $regNo)
                ->get();

            $subjTypeMap = $batchSubjects->pluck('subject_type', 'subject_code')->toArray();

            $getGP = function($grade) {
                switch (strtoupper(trim($grade))) {
                    case 'S': return 10;
                    case 'A': return 9;
                    case 'B': return 8;
                    case 'C': return 7;
                    case 'D': return 6;
                    case 'E': return 5;
                    case 'F': return 0;
                    default: return null;
                }
            };

            $getCredit = function($code) use ($subjTypeMap) {
                $type = $subjTypeMap[$code] ?? 'Theory';
                if (stripos($type, 'practical') !== false || stripos($type, 'lab') !== false) {
                    return 2;
                }
                return 4;
            };

            $computedSGPA = [];
            $computedCGPA = [];
            $semestersList = $batchSubjects->pluck('semester')->unique()->sort()->toArray();
            $cumTotalGP = 0;
            $cumTotalCredits = 0;

            foreach ($semestersList as $sem) {
                $semGrades = $allGrades->where('semester', $sem);
                $semTotalGP = 0;
                $semTotalCredits = 0;

                foreach ($semGrades as $g) {
                    $gp = $getGP($g->grade);
                    if ($gp !== null) {
                        $credit = $getCredit($g->subject_code);
                        $semTotalGP += ($gp * $credit);
                        $semTotalCredits += $credit;

                        $cumTotalGP += ($gp * $credit);
                        $cumTotalCredits += $credit;
                    }
                }

                $computedSGPA[$sem] = $semTotalCredits > 0 ? round($semTotalGP / $semTotalCredits, 2) : null;
                $computedCGPA[$sem] = $cumTotalCredits > 0 ? round($cumTotalGP / $cumTotalCredits, 2) : null;
            }

            // Build Report grouped by semester
            $report = [];
            foreach ($batchSubjects as $subj) {
                $sem = $subj->semester;
                if (!isset($report[$sem])) {
                    $summary = $summaries->firstWhere('semester', $sem);
                    $report[$sem] = [
                        'semester' => $sem,
                        'sgpa' => $computedSGPA[$sem] ?? ($summary ? $summary->sgpa : null),
                        'cgpa' => $computedCGPA[$sem] ?? ($summary ? $summary->cgpa : null),
                        'activity_points' => $summary ? $summary->activity_points : 0,
                        'subjects' => []
                    ];
                }

                $subjCode = $subj->subject_code;
                $subjMarks = $academicMarks->get($subjCode, collect());
                
                // Parse marks
                $parsedMarks = [
                    'CO1' => null, 'CO2' => null, 'CO3' => null, 'CO4' => null,
                    'Assg1' => null, 'Assg2' => null, 'Assg3' => null, 'Assg4' => null,
                    'Assg1_status' => null, 'Assg2_status' => null, 'Assg3_status' => null, 'Assg4_status' => null,
                    'WT1' => null, 'WT2' => null, 'WT3' => null, 'WT4' => null,
                    'OT1' => null, 'OT2' => null, 'OT3' => null, 'OT4' => null,
                ];

                foreach ($subjMarks as $m) {
                    if ($m->category === 'Assignment') {
                        if ($m->co_tag === 'CO1') { $parsedMarks['Assg1'] = $m->marks_obtained; $parsedMarks['CO1'] = ($parsedMarks['CO1'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO2') { $parsedMarks['Assg2'] = $m->marks_obtained; $parsedMarks['CO2'] = ($parsedMarks['CO2'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO3') { $parsedMarks['Assg3'] = $m->marks_obtained; $parsedMarks['CO3'] = ($parsedMarks['CO3'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO4') { $parsedMarks['Assg4'] = $m->marks_obtained; $parsedMarks['CO4'] = ($parsedMarks['CO4'] ?? 0) + $m->marks_obtained; }
                    }
                    if ($m->category === 'Written Test') {
                        if ($m->co_tag === 'CO1') { $parsedMarks['WT1'] = $m->marks_obtained; $parsedMarks['CO1'] = ($parsedMarks['CO1'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO2') { $parsedMarks['WT2'] = $m->marks_obtained; $parsedMarks['CO2'] = ($parsedMarks['CO2'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO3') { $parsedMarks['WT3'] = $m->marks_obtained; $parsedMarks['CO3'] = ($parsedMarks['CO3'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO4') { $parsedMarks['WT4'] = $m->marks_obtained; $parsedMarks['CO4'] = ($parsedMarks['CO4'] ?? 0) + $m->marks_obtained; }
                    }
                    if ($m->category === 'Online Test') {
                        if ($m->co_tag === 'CO1') { $parsedMarks['OT1'] = $m->marks_obtained; $parsedMarks['CO1'] = ($parsedMarks['CO1'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO2') { $parsedMarks['OT2'] = $m->marks_obtained; $parsedMarks['CO2'] = ($parsedMarks['CO2'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO3') { $parsedMarks['OT3'] = $m->marks_obtained; $parsedMarks['CO3'] = ($parsedMarks['CO3'] ?? 0) + $m->marks_obtained; }
                        if ($m->co_tag === 'CO4') { $parsedMarks['OT4'] = $m->marks_obtained; $parsedMarks['CO4'] = ($parsedMarks['CO4'] ?? 0) + $m->marks_obtained; }
                    }
                }

                // Map submission statuses from student_task_submissions
                foreach (['CO1' => 'Assg1_status', 'CO2' => 'Assg2_status', 'CO3' => 'Assg3_status', 'CO4' => 'Assg4_status'] as $co => $key) {
                    $sub = $taskSubmissions->where('subject_code', $subjCode)->where('co_tag', $co)->where('category', 'Assignment')->first();
                    if ($sub) {
                        $parsedMarks[$key] = $sub->status;
                    }
                }

                // Parse attendance
                $attData = $attendanceMap[$subjCode] ?? [];
                $present = $attData['Present'] ?? 0;
                $late = $attData['Late'] ?? 0;
                $absent = $attData['Absent'] ?? 0;
                $totalDays = $present + $late + $absent;
                $attPercent = $totalDays > 0 ? round((($present + ($late*0.5)) / $totalDays) * 100, 1) : 0;

                $bgRecord = $boardGrades->get($subjCode);
                $bGrade = $bgRecord ? $bgRecord->first() : null;

                $report[$sem]['subjects'][] = array_merge([
                    'batch_subject_id' => $subj->id,
                    'subject_code' => $subjCode,
                    'subject_name' => $subj->subject_name,
                    'subject_type' => $subj->subject_type,
                    'attendance_percentage' => $attPercent,
                    'board_grade' => $bGrade ? $bGrade->grade : null
                ], $parsedMarks);
            }

            // Calculate global stats for tasks (all semesters)
            $assignmentsGraded = DB::table('academic_marks')
                ->where('reg_no', $regNo)
                ->where('category', 'Assignment')
                ->select('subject_code', 'co_tag')
                ->distinct()
                ->get()
                ->map(function($m) { return $m->subject_code . '-' . $m->co_tag; })
                ->toArray();
            
            $assignmentsManuallySubmitted = DB::table('student_task_submissions')
                ->where('reg_no', $regNo)
                ->where('category', 'Assignment')
                ->select('subject_code', 'co_tag')
                ->distinct()
                ->get()
                ->map(function($m) { return $m->subject_code . '-' . $m->co_tag; })
                ->toArray();

            $uniqueAssignmentsDone = array_unique(array_merge($assignmentsGraded, $assignmentsManuallySubmitted));

            $writtenTestsGraded = DB::table('academic_marks')
                ->where('reg_no', $regNo)
                ->where('category', 'Written Test')
                ->select('subject_code', 'co_tag')
                ->distinct()
                ->get()
                ->map(function($m) { return $m->subject_code . '-' . $m->co_tag; })
                ->toArray();
                
            $writtenTestsSubmittedManually = DB::table('student_task_submissions')
                ->where('reg_no', $regNo)
                ->where('category', 'Written Test')
                ->select('subject_code', 'co_tag')
                ->distinct()
                ->get()
                ->map(function($m) { return $m->subject_code . '-' . $m->co_tag; })
                ->toArray();

            $uniqueAssignmentsDone = array_unique(array_merge($assignmentsGraded, $assignmentsManuallySubmitted));
            $uniqueWrittenTestsDone = array_unique(array_merge($writtenTestsGraded, $writtenTestsSubmittedManually));

            // Fetch Active Assignments and Exams for current semester
            $activeTasks = [];
            $stats = [
                'assignments_active' => 0,
                'assignments_submitted' => count($uniqueAssignmentsDone),
                'written_tests_active' => 0,
                'written_tests_submitted' => count($uniqueWrittenTestsDone)
            ];

            if ($currentSem <= 6) {
                $currentSubjects = $batchSubjects->where('semester', $currentSem);
                
                // Reused $taskSubmissions query from top of function
                
                $allMarks = DB::table('academic_marks')
                    ->where('reg_no', $regNo)
                    ->get();

                foreach ($currentSubjects as $subj) {
                    $courseFile = \App\Models\CourseFile::where('batch_subject_id', $subj->id)->first();
                    if ($courseFile) {
                        // assignment
                        $deadlines = $courseFile->assignment_deadlines ?? [];
                        $questions = $courseFile->assignment_questions ?? [];
                        foreach ($deadlines as $co => $dData) {
                            if (!empty($dData['locked']) && $dData['locked'] === true) {
                                // Filter by graded in academic_marks
                                $isGraded = $allMarks->where('subject_code', $subj->subject_code)->where('co_tag', $co)->where('category', 'Assignment')->isNotEmpty();
                                if ($isGraded) {
                                    continue;
                                }

                                // Filter by manual submission
                                $isSubmitted = $taskSubmissions->where('subject_code', $subj->subject_code)->where('co_tag', $co)->where('category', 'Assignment')->isNotEmpty();
                                if ($isSubmitted) {
                                    continue;
                                }

                                $start = $dData['start'] ?? null;
                                $due = $dData['due'] ?? null;
                                
                                if ($due && strtotime($due . ' 23:59:59') < time()) continue; // Skip expired assignments
                                
                                $stats['assignments_active']++;
                                $activeTasks[] = [
                                    'type' => 'Assignment',
                                    'subject' => $subj->subject_name,
                                    'subject_code' => $subj->subject_code,
                                    'co_tag' => $co,
                                    'start' => $start,
                                    'deadline' => $due,
                                    'status' => 'Active',
                                    'questions' => $questions[$co] ?? []
                                ];
                            }
                        }
                        // summative tests
                        $tests = $courseFile->summative_manual_tests ?? [];
                        foreach ($tests as $co => $tData) {
                            if (!empty($tData['is_locked']) && $tData['is_locked'] === true && !empty($tData['date_of_exam'])) {
                                // Filter by graded in academic_marks
                                $isGraded = $allMarks->where('subject_code', $subj->subject_code)->where('co_tag', $co)->where('category', 'Written Test')->isNotEmpty();
                                if ($isGraded) continue;

                                if (strtotime($tData['date_of_exam'] . ' 23:59:59') < time()) continue; // Skip expired tests
                                
                                $stats['written_tests_active']++;
                                $activeTasks[] = [
                                    'type' => 'Written Test',
                                    'subject' => $subj->subject_name,
                                    'subject_code' => $subj->subject_code,
                                    'co_tag' => $co,
                                    'start' => null,
                                    'deadline' => $tData['date_of_exam'],
                                    'status' => 'Upcoming',
                                    'questions' => []
                                ];
                            }
                        }
                    }
                }
            }

            // Removed online tests from active_tasks as they are now handled exclusively by TestEngineController in the UI

            // Pull activity points from activity_point_claims (Verified) since semester summary may be empty
            $activityClaims = DB::table('activity_point_claims')
                ->where('reg_no', $regNo)
                ->where('status', 'Verified')
                ->select('semester', DB::raw('SUM(points_awarded) as total'))
                ->groupBy('semester')
                ->get()
                ->keyBy('semester');

            $totalActivityPoints = $activityClaims->sum('total');

            // If summaries have points, prefer them; otherwise fallback to claims
            if ($summaries->sum('activity_points') > 0) {
                $totalActivityPoints = $summaries->sum('activity_points');
            }

            // Inject per-semester activity points from claims into report
            foreach ($report as $sem => &$semData) {
                if ($semData['activity_points'] == 0 && isset($activityClaims[$sem])) {
                    $semData['activity_points'] = $activityClaims[$sem]->total;
                }
            }
            unset($semData);

            $latestSummary = $summaries->last();
            $currentCgpa = end($computedCGPA) ?: ($latestSummary ? $latestSummary->cgpa : null);

            // Determine classification
            $hasFail = $allGrades->where('grade', 'F')->count() > 0;
            
            $classification = 'Second Class';
            if ($currentCgpa >= 8.0) {
                $classification = 'First Class with Distinction';
            } elseif ($currentCgpa >= 6.5) {
                $classification = 'First Class';
            } elseif ($currentCgpa === null || $currentCgpa == 0) {
                $classification = 'In Progress';
            }

            if ($hasFail) {
                $classification = 'Needs Improvement (F Grade present)';
            }

            $activeSurveys = [];
            if ($currentSem <= 6) {
                $subjectIds = $currentSubjects->pluck('id')->toArray();

                // 1. Mid-Semester Surveys
                $surveys = DB::table('mid_semester_surveys')
                    ->join('batch_subjects', 'mid_semester_surveys.batch_subject_id', '=', 'batch_subjects.id')
                    ->whereIn('mid_semester_surveys.batch_subject_id', $subjectIds)
                    ->where('mid_semester_surveys.status', 'Active')
                    ->select('mid_semester_surveys.id', 'batch_subjects.subject_name', 'batch_subjects.subject_code')
                    ->get();

                foreach ($surveys as $srv) {
                    $hasResponded = DB::table('student_survey_responses')
                        ->where('survey_id', $srv->id)
                        ->where('reg_no', $regNo)
                        ->exists();

                    if (!$hasResponded) {
                        $activeSurveys[] = [
                            'survey_id' => $srv->id,
                            'type' => 'Mid-Semester',
                            'subject_name' => $srv->subject_name,
                            'subject_code' => $srv->subject_code
                        ];
                    }
                }

                // 2. Course Exit Surveys
                $exitSurveys = DB::table('course_exit_surveys')
                    ->join('batch_subjects', 'course_exit_surveys.batch_subject_id', '=', 'batch_subjects.id')
                    ->whereIn('course_exit_surveys.batch_subject_id', $subjectIds)
                    ->where('course_exit_surveys.status', 'Active')
                    ->select('course_exit_surveys.id', 'batch_subjects.subject_name', 'batch_subjects.subject_code')
                    ->get();

                foreach ($exitSurveys as $esrv) {
                    $hasResponded = DB::table('student_course_exit_responses')
                        ->where('exit_survey_id', $esrv->id)
                        ->where('reg_no', $regNo)
                        ->exists();

                    if (!$hasResponded) {
                        $activeSurveys[] = [
                            'survey_id' => $esrv->id,
                            'type' => 'Course Exit',
                            'subject_name' => $esrv->subject_name,
                            'subject_code' => $esrv->subject_code
                        ];
                    }
                }
            }

            $subjectProgress = [];
            if ($currentSem <= 6) {
                $currentSubjects = $batchSubjects->where('semester', $currentSem);
                foreach ($currentSubjects as $subj) {
                    $staffName = 'Not Assigned';
                    $assignment = DB::table('subject_staff_assignments')
                        ->where('batch_subject_id', $subj->id)
                        ->first();
                    if ($assignment) {
                        $staff = DB::table('staff_profiles')
                            ->where('mobile_no', $assignment->staff_mobile_no)
                            ->first();
                        if ($staff) {
                            $staffName = $staff->name;
                        }
                    }

                    $completedSessions = DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $subj->id)
                        ->count();

                    $totalSessions = DB::table('lesson_plans')
                        ->where('batch_subject_id', $subj->id)
                        ->sum('allocated_hours');

                    if ($totalSessions <= 0) {
                        $totalSessions = DB::table('lesson_plans')
                            ->where('batch_subject_id', $subj->id)
                            ->count();
                        if ($totalSessions <= 0) {
                            $totalSessions = 45;
                        }
                    }

                    $percentage = $totalSessions > 0 ? min(100, round(($completedSessions / $totalSessions) * 100)) : 0;

                    $subjectProgress[] = [
                        'subject_code' => $subj->subject_code,
                        'subject_name' => $subj->subject_name,
                        'staff_name' => $staffName,
                        'completed_sessions' => $completedSessions,
                        'total_sessions' => $totalSessions,
                        'percentage' => $percentage
                    ];
                }
            }

            $currentSemAttendance = [
                'total_hours' => 0,
                'present_hours' => 0,
                'percentage' => 0
            ];
            if ($currentSem <= 6) {
                $totalHours = 0;
                $presentHours = 0;
                foreach ($currentSubjects as $subj) {
                    $att = $attendanceMap[$subj->subject_code] ?? ['Present' => 0, 'Absent' => 0, 'Late' => 0];
                    $totalHours += ($att['Present'] + $att['Absent'] + $att['Late']);
                    $presentHours += ($att['Present'] + ($att['Late'] * 0.5));
                }
                $percentage = $totalHours > 0 ? round(($presentHours / $totalHours) * 100, 1) : 0;
                $currentSemAttendance = [
                    'total_hours' => $totalHours,
                    'present_hours' => $presentHours,
                    'percentage' => $percentage
                ];
            }

            ksort($report);

            return response()->json([
                'status' => 'SUCCESS',
                'overall' => [
                    'cgpa' => $currentCgpa,
                    'activity_points' => $totalActivityPoints,
                    'current_semester' => $currentSem,
                    'classification' => $classification
                ],
                'semesters' => array_values($report),
                'active_tasks' => $activeTasks,
                'active_surveys' => $activeSurveys,
                'stats' => $stats,
                'subject_progress' => $subjectProgress,
                'current_sem_attendance' => $currentSemAttendance
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * TUTOR: Fetch dynamic semester tracking data (subjects + grades matrix).
     */
    public function getTutorSemesterData(Request $request)
    {
        $userId = Session::get('userId');
        $role = Session::get('userRole');
        
        $semester = $request->query('semester', 1);

        $supervisedClass = \App\Models\ClassManagement::where('tutor_mobile_no', $userId)
            ->orWhere('mentor_mobile_no', $userId)
            ->first();

        if (!$supervisedClass) {
            return response()->json(['status' => 'ERROR', 'message' => 'No supervised classroom found.']);
        }

        $classroomId = $supervisedClass->classroom_id;

        // Get all students in this classroom
        $students = Student::where('classroom_id', $classroomId)->get();

        // Get subjects for this batch & semester
        $subjects = \App\Models\BatchSubject::where('classroom_id', $classroomId)
            ->where('semester', $semester)
            ->get();

        // Get all marks for these students for this semester
        $regNos = $students->pluck('reg_no');
        $marks = \App\Models\StudentSemesterMarks::whereIn('reg_no', $regNos)
            ->where('semester', $semester)
            ->get();

        $summaries = \App\Models\StudentSemesterSummary::whereIn('reg_no', $regNos)
            ->where('semester', $semester)
            ->get()->keyBy('reg_no');

        $result = [];
        foreach ($students as $student) {
            $studentMarks = $marks->where('reg_no', $student->reg_no)->keyBy('subject_code');
            $summary = $summaries->get($student->reg_no);

            $subjectGrades = [];
            foreach ($subjects as $sub) {
                $m = $studentMarks->get($sub->subject_code);
                if ($m) {
                    $gradeStr = $m->board_marks ? $m->board_marks . ' (' . $m->grade . ')' : $m->grade;
                    $subjectGrades[$sub->subject_code] = $gradeStr;
                } else {
                    $subjectGrades[$sub->subject_code] = '-';
                }
            }

            $result[] = [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'status' => $student->status,
                'photo_url' => $student->photo_url,
                'subjects' => $subjectGrades,
                'sgpa' => $summary ? $summary->sgpa : '-',
                'attendance' => $summary ? $summary->attendance_percentage : '-',
                'activity_points' => $summary ? $summary->activity_points : '-',
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'semester' => $semester,
            'subjects' => $subjects->map(function($s) { 
                return ['code' => $s->subject_code, 'name' => $s->subject_name]; 
            }),
            'students' => $result
        ]);
    }

    /**
     * TUTOR: Fetch detailed student profile.
     */
    public function getTutorStudentProfile($regNo)
    {
        $userId = Session::get('userId');

        // Check authorization
        $supervisedClass = \App\Models\ClassManagement::where('tutor_mobile_no', $userId)
            ->orWhere('mentor_mobile_no', $userId)
            ->first();

        if (!$supervisedClass) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $student = Student::where('reg_no', $regNo)->where('classroom_id', $supervisedClass->classroom_id)->first();
        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student not found in your class.']);
        }

        $summaries = \App\Models\StudentSemesterSummary::where('reg_no', $regNo)->orderBy('semester')->get();
        
        return response()->json([
            'status' => 'SUCCESS',
            'student' => $student,
            'semesters' => $summaries
        ]);
    }

    /**
     * TUTOR: Update student remarks (higher studies/placement context).
     */
    public function updateTutorStudentRemarks(Request $request, $regNo)
    {
        $userId = Session::get('userId');

        $supervisedClass = \App\Models\ClassManagement::where('tutor_mobile_no', $userId)
            ->orWhere('mentor_mobile_no', $userId)
            ->first();

        if (!$supervisedClass) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $student = Student::where('reg_no', $regNo)->where('classroom_id', $supervisedClass->classroom_id)->first();
        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student not found in your class.']);
        }

        $request->validate([
            'higher_studies_remark' => 'nullable|string'
        ]);

        $student->update([
            'higher_studies_remark' => $request->input('higher_studies_remark')
        ]);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Remarks updated successfully.']);
    }

    public function getTutorActiveStudents(Request $request)
    {
        $userId = Session::get('userId');

        $supervisedClass = \App\Models\ClassManagement::where('tutor_mobile_no', $userId)->first();

        if (!$supervisedClass) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized or no assigned class.']);
        }

        $students = Student::where('classroom_id', $supervisedClass->classroom_id)
            ->where('status', 'Approved')
            ->where('academic_status', 'Active')
            ->orderBy('name')
            ->get(['reg_no', 'name']);

        return response()->json([
            'status' => 'SUCCESS',
            'data' => $students
        ]);
    }

    public function submitTutorPromotion(Request $request)
    {
        $userId = Session::get('userId');
        $supervisedClass = \App\Models\ClassManagement::where('tutor_mobile_no', $userId)->first();

        if (!$supervisedClass) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized or no assigned class.']);
        }

        $promotions = $request->input('promotions', []);

        DB::beginTransaction();
        try {
            // First, update the academic_status of students who are NOT promoted
            foreach ($promotions as $promo) {
                if ($promo['action'] !== 'Promote') {
                    Student::where('reg_no', $promo['reg_no'])
                        ->where('classroom_id', $supervisedClass->classroom_id)
                        ->update([
                            'academic_status' => $promo['action'],
                            'status_notes' => $promo['remarks']
                        ]);
                } else if (!empty($promo['remarks'])) {
                     Student::where('reg_no', $promo['reg_no'])
                        ->where('classroom_id', $supervisedClass->classroom_id)
                        ->update([
                            'status_notes' => $promo['remarks']
                        ]);
                }
            }

            // Increment the semester for the classroom
            $currentSem = (int) $supervisedClass->current_semester;
            $newSem = $currentSem < 8 ? $currentSem + 1 : $currentSem;

            $supervisedClass->update(['current_semester' => $newSem]);

            // Also increment active students' semester
            Student::where('classroom_id', $supervisedClass->classroom_id)
                ->where('academic_status', 'Active')
                ->where('semester', '<', 6)
                ->increment('semester');

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'new_semester' => 'Semester ' . $newSem
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'ERROR', 'message' => 'Promotion failed: ' . $e->getMessage()]);
        }
    }

    public function submitManualTask(Request $request)
    {
        $regNo = Session::get('userId');
        if (!$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $request->validate([
            'subject_code' => 'required|string',
            'category' => 'required|string',
            'co_tag' => 'required|string',
            'status' => 'required|string'
        ]);

        try {
            DB::table('student_task_submissions')->insert([
                'reg_no' => $regNo,
                'subject_code' => $request->input('subject_code'),
                'category' => $request->input('category'),
                'co_tag' => $request->input('co_tag'),
                'status' => $request->input('status'),
                'submitted_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Task marked as submitted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to submit task: ' . $e->getMessage()]);
        }
    }

    /**
     * Student: Update email address.
     */
    public function updateStudentEmail(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId || $userRole !== 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $email = strtolower(trim($request->input('email', '')));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Please enter a valid email address.']);
        }

        $existing = Student::where('email', $email)->where('reg_no', '!=', $userId)->first();
        if ($existing) {
            return response()->json(['status' => 'ERROR', 'message' => 'This email address is already registered to another student.']);
        }

        $student = Student::where('reg_no', $userId)->first();
        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
        }

        $student->email = $email;
        $student->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Email address updated successfully!'
        ]);
    }

    /**
     * Download sample CSV template for bulk student import.
     */
    public function downloadStudentImportTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=student_bulk_import_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Name', 'Admission_No', 'Branch', 'Admission_Year', 'Admission_Type', 'Semester', 'Email', 'SBTE_Reg_No'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['Arun Kumar', 'ADM24CT01', 'CT', '2024', 'Regular', 'S1', '', '']);
            fputcsv($file, ['Beena S', 'ADM24ECL02', 'EL', '2024', 'LET', 'S3', 'beena@carmelpoly.in', '2403210451']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk Import Students from Excel / CSV roster.
     */
    public function bulkImportStudents(Request $request)
    {
        $userRole = Session::get('userRole');
        if (!in_array($userRole, ['Super_Admin', 'Admin', 'HOD', 'Principal', 'Lecturer', 'Demonstrator'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $rows = $request->input('rows');
        if (!is_array($rows) || count($rows) < 1) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $handle = fopen($file->getRealPath(), 'r');
                $header = fgetcsv($handle);
                $rows = [];
                while (($row = fgetcsv($handle)) !== false) {
                    if (!empty(array_filter($row))) {
                        $rows[] = $row;
                    }
                }
                fclose($handle);
            } else {
                return response()->json(['status' => 'ERROR', 'message' => 'Please select a valid CSV/Excel file or roster.']);
            }
        }

        try {
            $importedCount = 0;
            $updatedCount = 0;
            $commonHashedPassword = \Illuminate\Support\Facades\Hash::make('carmel2026');

            foreach ($rows as $index => $row) {
                if ($index === 0 && (strcasecmp($row[0] ?? '', 'Name') === 0 || strcasecmp($row[0] ?? '', 'Full Name') === 0)) {
                    continue;
                }

                $name = trim($row[0] ?? '');
                $admNo = strtoupper(trim($row[1] ?? ''));
                $branch = strtoupper(trim($row[2] ?? ''));
                $admissionYear = intval(trim($row[3] ?? date('Y')));

                if (empty($name) || empty($admNo) || empty($branch)) {
                    continue;
                }

                $admissionType = trim($row[4] ?? 'Regular');
                if (strcasecmp($admissionType, 'LET') !== 0 && strcasecmp($admissionType, 'Lateral') !== 0) {
                    $admissionType = 'Regular';
                } else {
                    $admissionType = 'LET';
                }

                $semRaw = strtoupper(trim($row[5] ?? '1'));
                $semester = (int) filter_var($semRaw, FILTER_SANITIZE_NUMBER_INT);
                if ($semester < 1 || $semester > 6) {
                    $semester = 1;
                }

                $email = strtolower(trim($row[6] ?? ''));
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $email = strtolower($admNo) . '@carmelpoly.in';
                }

                $sbteRegNo = trim($row[7] ?? null);

                $isLet = ($admissionType === 'LET');
                $yy = substr((string)$admissionYear, -2);
                $regNo = $yy . $branch . $admNo . ($isLet ? 'L' : '');

                $startYear = $isLet ? ($admissionYear - 1) : $admissionYear;
                $endYear = $startYear + 3;
                $classroomId = "{$branch}_{$startYear}_{$endYear}";

                // Always retain calculated classroom_id so students belong to their batch
                $existing = Student::where('reg_no', $regNo)
                    ->orWhere('adm_no', $admNo)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'name' => $name,
                        'email' => ($existing->email && !str_ends_with($existing->email, '@carmelpoly.in')) ? $existing->email : $email,
                        'password' => $commonHashedPassword,
                        'branch' => $branch,
                        'admission_year' => $admissionYear,
                        'admission_type' => $admissionType,
                        'semester' => $semester,
                        'classroom_id' => $classroomId,
                        'sbte_reg_no' => $sbteRegNo ?: $existing->sbte_reg_no,
                        'status' => 'Approved'
                    ]);
                    $updatedCount++;
                } else {
                    Student::create([
                        'reg_no' => $regNo,
                        'adm_no' => $admNo,
                        'name' => $name,
                        'email' => $email,
                        'password' => $commonHashedPassword,
                        'branch' => $branch,
                        'admission_year' => $admissionYear,
                        'admission_type' => $admissionType,
                        'semester' => $semester,
                        'classroom_id' => $classroomId,
                        'sbte_reg_no' => $sbteRegNo,
                        'status' => 'Approved',
                        'academic_status' => 'Active'
                    ]);
                    $importedCount++;

                    AuditLog::create([
                        'performed_by' => Session::get('userId') ?: 'HOD/Tutor',
                        'performed_by_name' => Session::get('userName') ?: 'Bulk Import',
                        'action' => 'Bulk Student Registration',
                        'details' => "Registered student {$regNo} ({$name}) in classroom " . ($classroomId ?: 'Unassigned'),
                        'ip_address' => request()->ip()
                    ]);
                }
            }

            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Bulk import completed successfully! Newly registered: {$importedCount} students. Existing roster updated: {$updatedCount} students.",
                'imported_count' => $importedCount,
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Bulk import failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Student: Upload or update profile photo.
     */
    public function uploadStudentPhoto(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');
        
        if (!$userId || $userRole !== 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Image format mismatch: Only JPG, PNG, or WebP photo formats under 5MB are allowed.'
            ]);
        }

        try {
            $student = Student::where('reg_no', $userId)->first();
            if (!$student) {
                return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
            }

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                // Passport-style clear face aspect ratio check
                list($width, $height) = @getimagesize($file->getRealPath());
                if ($width && $height) {
                    $aspectRatio = $width / $height;
                    if ($aspectRatio < 0.65 || $aspectRatio > 1.35) {
                        return response()->json([
                            'status' => 'ERROR',
                            'message' => 'Photo restricted: Please upload a close-up clear face photo (passport style). Full body photos or wide landscape shots are not allowed.'
                        ]);
                    }
                }

                // Delete old photo file if exists on disk
                if ($student->photo_url) {
                    $oldPath = str_replace('/storage/', '', $student->photo_url);
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }

                $photoPath = '/storage/' . $file->store('avatars', 'public');
                $student->photo_url = $photoPath;
                $student->save();

                // Sync user photo with session
                Session::put('userPhoto', $photoPath);

                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Passport photo updated successfully!',
                    'photo_url' => $photoPath
                ]);
            }

            return response()->json(['status' => 'ERROR', 'message' => 'No file uploaded.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to upload photo: ' . $e->getMessage()]);
        }
    }

    /**
     * Staff: Upload or update profile photo.
     */
    public function uploadStaffPhoto(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');
        
        if (!$userId || $userRole === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        if ($validator->fails()) {
            $errors = implode(' ', $validator->errors()->all());
            return response()->json(['status' => 'ERROR', 'message' => $errors ?: 'Invalid image file provided. Maximum size is 10MB.']);
        }

        try {
            $cleanMobile = preg_replace('/[^0-9]/', '', $userId);
            $staff = StaffProfile::where(function($q) use ($userId, $cleanMobile) {
                $q->where('mobile_no', $userId);
                if (!empty($cleanMobile)) {
                    $q->orWhere('mobile_no', $cleanMobile);
                }
            })->first();

            if (!$staff && $userRole) {
                $staff = StaffProfile::where('designation', $userRole)->first();
            }

            if (!$staff) {
                $staff = StaffProfile::create([
                    'mobile_no' => $userId,
                    'name' => Session::get('userName', 'Staff Member'),
                    'branch' => Session::get('userBranch', 'General'),
                    'designation' => $userRole ?: 'Lecturer',
                    'account_status' => 'Approved'
                ]);
            }

            if ($request->hasFile('photo')) {
                // Delete old photo file if exists on disk
                if ($staff->photo_url) {
                    $oldPath = str_replace('/storage/', '', $staff->photo_url);
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }

                $photoPath = '/storage/' . $request->file('photo')->store('avatars', 'public');
                $staff->photo_url = $photoPath;
                $staff->save();

                // Sync user photo with session
                Session::put('userPhoto', $photoPath);

                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Profile photo updated successfully!',
                    'photo_url' => $photoPath
                ]);
            }

            return response()->json(['status' => 'ERROR', 'message' => 'No image file uploaded.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to upload photo: ' . $e->getMessage()]);
        }
    }
}
