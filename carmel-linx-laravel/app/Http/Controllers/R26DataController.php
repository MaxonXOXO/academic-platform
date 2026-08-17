<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\R26ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\AuditLog;

class R26DataController extends Controller
{
    private function checkHodOrPrincipalAccess(Request $request, &$branchOut)
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || !in_array($currentRole, ['HOD', 'Principal', 'Super_Admin', 'Admin', 'Chairman'])) {
            return false;
        }

        if (in_array($currentRole, ['Principal', 'Super_Admin', 'Admin', 'Chairman'])) {
            $branchOut = $request->input('branch') ?? $request->query('branch') ?? $currentBranch;
        } else {
            $branchOut = $currentBranch;
        }
        return true;
    }

    /**
     * HOD: List all Revision 2026 batches/classrooms for this branch.
     */
    public function getBatches(Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }
        $currentBranch = $branch;

        $filterStatus = $request->query('status', 'active');

        try {
            $query = R26ClassManagement::where('branch', strtoupper($currentBranch));
            
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
                    
                    // Note: R26 subjects can be loaded differently later if needed. For now, fetch from regular batch_subjects if they exist.
                    $subjects = \App\Models\BatchSubject::where('classroom_id', $cls->classroom_id)
                        ->where('semester', $activeSem)
                        ->get()
                        ->map(function ($subj) {
                            $staffNames = \App\Models\SubjectStaffAssignment::where('batch_subject_id', $subj->id)
                                ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
                                ->pluck('staff_profiles.name')
                                ->toArray();

                            // Use 2021 lesson plan reference for now or we will map R26 lesson plans when needed
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
                        'is_r26'            => true,
                    ];
                });

            return response()->json(['status' => 'SUCCESS', 'batches' => $batches]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch R26 batches: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Create a new Revision 2026 batch/classroom.
     */
    public function createBatch(Request $request)
    {
        $branch = null;
        if (!$this->checkHodOrPrincipalAccess($request, $branch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD or Principal access required.']);
        }
        $currentUserId = Session::get('userId');
        $branchCode = strtoupper($branch);

        $request->validate([
            'admission_year'    => 'required|integer|min:2000|max:2100',
            'tutor_mobile_no'   => 'nullable|string',
            'mentor_mobile_no'  => 'nullable|string',
            'current_semester'  => 'nullable|integer|min:1|max:8',
        ]);

        $admYear      = (int) $request->input('admission_year');
        $baseYear     = $admYear;
        $semester     = (int) $request->input('current_semester', 1);
        $tutorMobile  = $request->input('tutor_mobile_no');
        $mentorMobile = $request->input('mentor_mobile_no');

        if ($tutorMobile) {
            $tutor = StaffProfile::where('mobile_no', $tutorMobile)->first();
            if (!$tutor || strtoupper($tutor->branch) !== $branchCode) {
                return response()->json(['status' => 'ERROR', 'message' => 'Selected Tutor does not belong to your department.']);
            }
        }
        if ($mentorMobile) {
            $mentor = StaffProfile::where('mobile_no', $mentorMobile)->first();
            if (!$mentor || strtoupper($mentor->branch) !== $branchCode) {
                return response()->json(['status' => 'ERROR', 'message' => 'Selected Mentor does not belong to your department.']);
            }
        }

        $classroomId = "{$branchCode}_{$baseYear}_" . ($baseYear + 3);

        // Check if batch already exists in standard table (don't duplicate)
        if (\App\Models\ClassManagement::where('classroom_id', $classroomId)->exists()) {
            return response()->json(['status' => 'ERROR', 'message' => "A standard 2021 batch with ID {$classroomId} already exists."]);
        }

        // Check if batch already exists in R26 table
        $existing = R26ClassManagement::where('classroom_id', $classroomId)->first();
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
                    'target_name'       => "Revision 2026 Batch {$classroomId}",
                    'action'            => 'R26 Batch Rollover',
                    'details'           => "HOD rolled over R26 batch {$classroomId} to Semester {$semester}.",
                    'ip_address'        => $request->ip(),
                ]);

                return response()->json([
                    'status'       => 'SUCCESS',
                    'message'      => "Revision 2026 Batch {$classroomId} rolled over/updated successfully.",
                    'classroom_id' => $classroomId,
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERROR', 'message' => 'Failed to update R26 batch: ' . $e->getMessage()]);
            }
        }

        try {
            R26ClassManagement::create([
                'classroom_id'     => $classroomId,
                'branch'           => $branchCode,
                'batch_year'       => $baseYear,
                'tutor_mobile_no'  => $tutorMobile  ?: null,
                'mentor_mobile_no' => $mentorMobile ?: null,
                'current_semester' => $semester,
            ]);

            // Backfill any unassigned students matching this classroom ID
            $backfilledCount = Student::whereNull('classroom_id')
                ->where('branch', $branchCode)
                ->where('admission_year', $admYear)
                ->update([
                    'classroom_id' => $classroomId,
                    'semester'     => $semester
                ]);

            AuditLog::create([
                'performed_by'      => $currentUserId,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Revision 2026 Batch {$classroomId}",
                'action'            => 'Create R26 Batch',
                'details'           => "HOD created R26 batch {$classroomId} with tutor/mentor assignment, auto-backfilling {$backfilledCount} students.",
                'ip_address'        => $request->ip(),
            ]);

            return response()->json([
                'status'       => 'SUCCESS',
                'message'      => "Revision 2026 Batch {$classroomId} created successfully with {$backfilledCount} students auto-assigned.",
                'classroom_id' => $classroomId,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to create R26 batch: ' . $e->getMessage()]);
        }
    }
}
