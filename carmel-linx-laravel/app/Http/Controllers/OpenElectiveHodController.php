<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\OeCourse;
use App\Models\OeSetting;
use App\Models\OeVirtualClassroom;
use App\Models\StaffProfile;

class OpenElectiveHodController extends Controller
{
    /**
     * HOD Departmental Open Elective Desk.
     * Allows HOD to configure course code, title, capacity, syllabus PDF, COs, and assigned faculty.
     */
    public function index(Request $request)
    {
        $role = Session::get('userRole');
        $branch = Session::get('userBranch');

        if (!in_array($role, ['HOD', 'Super_Admin', 'Principal', 'Admin'])) {
            return redirect('/')->with('error', 'Unauthorized. HOD or Executive login required.');
        }

        $settings = OeSetting::getActiveSettings();

        // Target branch (Admins can toggle branch, HOD defaults to own branch)
        $targetBranch = strtoupper(trim($request->input('branch', $branch)));
        
        $course = OeCourse::firstOrNew([
            'offering_branch' => $targetBranch,
            'academic_year' => '2026-2027',
            'semester' => 6,
        ]);

        if (!$course->exists) {
            $course->max_capacity = 60;
            $course->is_active = true;
        }

        $enrolledStudents = collect();
        $branchStats = [];

        if ($course->exists) {
            $enrolledStudents = OeVirtualClassroom::where('oe_course_id', $course->id)
                ->orderBy('parent_branch', 'ASC')
                ->orderBy('sbte_reg_no', 'ASC')
                ->get();

            $branchStats = $enrolledStudents->groupBy('parent_branch')->map->count()->toArray();
        }

        // Home department lecturers only for the dropdown
        $departmentFaculty = StaffProfile::where('branch', $targetBranch)
            ->where(function ($q) {
                $q->where('account_status', 'Approved')
                  ->orWhere('account_status', 'active')
                  ->orWhereNull('account_status');
            })
            ->whereNotIn('designation', ['Super_Admin'])
            ->orderBy('name', 'ASC')
            ->get();

        return view('open_elective.hod_index', compact(
            'settings',
            'course',
            'enrolledStudents',
            'branchStats',
            'departmentFaculty',
            'targetBranch'
        ));
    }

    /**
     * HOD sets up or updates their department's Open Elective course offering.
     */
    public function saveCourseOffering(Request $request)
    {
        $role = Session::get('userRole');
        $branch = Session::get('userBranch');

        if (!in_array($role, ['HOD', 'Super_Admin', 'Principal', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'offering_branch' => 'required|string|max:10',
            'course_code' => 'required|string|max:20',
            'course_title' => 'required|string|max:150',
            'max_capacity' => 'nullable|integer|min:10|max:120',
            'syllabus_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $targetBranch = strtoupper(trim($request->input('offering_branch')));

        if ($role === 'HOD' && $targetBranch !== strtoupper(trim($branch))) {
            return response()->json(['status' => 'ERROR', 'message' => 'You can only configure courses for your own department.'], 403);
        }

        $course = OeCourse::firstOrNew([
            'offering_branch' => $targetBranch,
            'academic_year' => '2026-2027',
            'semester' => 6,
        ]);

        $course->course_code = strtoupper(trim($request->input('course_code')));
        $course->course_title = trim($request->input('course_title'));
        $course->course_description = $request->input('course_description');
        $course->course_outcomes = $request->input('course_outcomes');
        $course->max_capacity = (int)($request->input('max_capacity', 60)) ?: 60;
        $course->is_active = true;
        $course->is_hod_submitted = true;

        // Handle Faculty Assignment
        $facultyStaffId = $request->input('faculty_staff_id');
        if (!empty($facultyStaffId)) {
            $faculty = StaffProfile::where('mobile_no', $facultyStaffId)
                ->orWhere('id', $facultyStaffId)
                ->first();

            if ($faculty) {
                $course->faculty_staff_id = $faculty->mobile_no ?? $faculty->id;
                $course->faculty_name = $faculty->name;
                $course->faculty_phone = $faculty->mobile_no ?? '';
            }
        }

        // Handle Syllabus PDF Upload
        if ($request->hasFile('syllabus_pdf')) {
            $file = $request->file('syllabus_pdf');
            $uploadDir = public_path('uploads/open_elective');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $fileName = 'syllabus_' . $targetBranch . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $course->syllabus_pdf_path = '/uploads/open_elective/' . $fileName;
        }

        $course->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Department {$targetBranch} Open Elective course '{$course->course_title}' saved and submitted to Principal/Admin desk.",
            'course' => $course,
        ]);
    }

    /**
     * Assign Faculty in Charge for this department's Open Elective course.
     */
    public function assignFaculty(Request $request)
    {
        $role = Session::get('userRole');
        $branch = Session::get('userBranch');

        if (!in_array($role, ['HOD', 'Super_Admin', 'Principal', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'course_id' => 'required|integer|exists:oe_courses,id',
            'faculty_staff_id' => 'required|string',
        ]);

        $course = OeCourse::findOrFail($request->course_id);

        if ($role === 'HOD' && $course->offering_branch !== $branch) {
            return response()->json(['status' => 'ERROR', 'message' => 'You can only assign faculty to courses offered by your department.'], 403);
        }

        $faculty = StaffProfile::where('mobile_no', $request->faculty_staff_id)
            ->orWhere('id', $request->faculty_staff_id)
            ->first();

        if ($faculty) {
            $course->faculty_staff_id = $faculty->mobile_no ?? $faculty->id;
            $course->faculty_name = $faculty->name;
            $course->faculty_phone = $faculty->mobile_no ?? '';
            $course->save();

            // Also update OeVirtualClassroom records
            OeVirtualClassroom::where('oe_course_id', $course->id)->update([
                'assigned_faculty_id' => $course->faculty_staff_id
            ]);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Faculty in charge updated to {$course->faculty_name}.",
            'faculty_name' => $course->faculty_name,
        ]);
    }
}
