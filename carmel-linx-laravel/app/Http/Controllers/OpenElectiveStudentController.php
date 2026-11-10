<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\OeCourse;
use App\Models\OeSetting;
use App\Models\OeRegistration;
use App\Models\Student;
use Carbon\Carbon;

class OpenElectiveStudentController extends Controller
{
    /**
     * Dedicated Student Open Elective Portal / Mobile View.
     */
    public function index(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        $isAdmin = in_array($role, ['Super_Admin', 'Principal', 'Admin', 'Chairman']);

        if (!$isAdmin && (strtolower($role) !== 'student' || !$userId)) {
            return redirect('/')->with('error', 'Please log in with your student credentials (SBTE PRN & password).');
        }

        if ($isAdmin && strtolower($role) !== 'student') {
            $branch = strtoupper($request->input('preview_branch', 'CE'));
            $student = Student::where('semester', 5)->where('branch', $branch)->first()
                    ?: Student::where('semester', 5)->first();
        } else {
            $student = Student::where('reg_no', $userId)
                ->orWhere('sbte_reg_no', Session::get('sbteRegNo'))
                ->orWhere('adm_no', $userId)
                ->first();
        }

        if (!$student) {
            return redirect('/dashboard/student')->with('error', 'Student profile record not found.');
        }

        $settings = OeSetting::getActiveSettings();

        // Check if student has already registered
        $existingReg = OeRegistration::with(['option1Course', 'option2Course', 'option3Course', 'option4Course', 'option5Course', 'allottedCourse'])
            ->where('student_adm_no', $student->adm_no)
            ->first();

        // Courses eligible for this student (Excluding student's own parent department)
        $eligibleCourses = OeCourse::where('offering_branch', '!=', $student->branch)
            ->where('is_active', true)
            ->orderBy('offering_branch')
            ->get();

        // Attach live 1st-choice demand metrics & parse COs
        foreach ($eligibleCourses as $c) {
            $opt1Count = OeRegistration::where('option_1_course_id', $c->id)->count();
            $c->opt1_demand = $opt1Count;
            $c->remaining_indicator = max(0, $c->max_capacity - $opt1Count);

            // Parse outcomes if plain string
            if ($c->course_outcomes && is_string($c->course_outcomes)) {
                $lines = array_filter(array_map('trim', explode("\n", $c->course_outcomes)));
                $c->parsed_outcomes = $lines;
            } else {
                $c->parsed_outcomes = [];
            }
        }

        // Window status check
        $now = Carbon::now();
        $isWindowOpen = $settings->is_registration_open;
        if ($settings->reg_start_at && $now->lt($settings->reg_start_at)) {
            $isWindowOpen = false;
        }
        if ($settings->reg_end_at && $now->gt($settings->reg_end_at)) {
            $isWindowOpen = false;
        }

        return view('open_elective.student_registration', compact(
            'student',
            'settings',
            'eligibleCourses',
            'existingReg',
            'isWindowOpen'
        ));
    }

    /**
     * Submit Ranked Preferences (Options 1 to 5).
     */
    public function submitPreferences(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        $isAdmin = in_array($role, ['Super_Admin', 'Principal', 'Admin', 'Chairman']);

        if (!$isAdmin && (strtolower($role) !== 'student' || !$userId)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Please login.'], 401);
        }

        if ($isAdmin && strtolower($role) !== 'student') {
            $simAdmNo = $request->input('student_adm_no');
            $student = $simAdmNo ? Student::where('adm_no', $simAdmNo)->first() : null;
            if (!$student) {
                $student = Student::where('semester', 5)->first();
            }
        } else {
            $student = Student::where('reg_no', $userId)
                ->orWhere('sbte_reg_no', Session::get('sbteRegNo'))
                ->orWhere('adm_no', $userId)
                ->first();
        }

        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.'], 404);
        }

        $settings = OeSetting::getActiveSettings();
        $now = Carbon::now();

        // Validate window
        if (!$settings->is_registration_open) {
            return response()->json(['status' => 'ERROR', 'message' => 'Open Elective registration window is currently closed.'], 422);
        }
        if ($settings->reg_start_at && $now->lt($settings->reg_start_at)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Registration has not started yet.'], 422);
        }
        if ($settings->reg_end_at && $now->gt($settings->reg_end_at)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Registration deadline has passed.'], 422);
        }

        // Validate 5 choices
        $request->validate([
            'option_1' => 'required|integer|exists:oe_courses,id',
            'option_2' => 'required|integer|exists:oe_courses,id',
            'option_3' => 'required|integer|exists:oe_courses,id',
            'option_4' => 'required|integer|exists:oe_courses,id',
            'option_5' => 'required|integer|exists:oe_courses,id',
        ]);

        $options = [
            (int)$request->option_1,
            (int)$request->option_2,
            (int)$request->option_3,
            (int)$request->option_4,
            (int)$request->option_5,
        ];

        // Ensure all 5 choices are unique
        if (count(array_unique($options)) !== 5) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'All 5 choices must be distinct. You cannot select the same course multiple times.'
            ], 422);
        }

        // Ensure none of the choices belong to student's parent branch
        $chosenCourses = OeCourse::whereIn('id', $options)->get();
        foreach ($chosenCourses as $course) {
            if (strtoupper($course->offering_branch) === strtoupper($student->branch)) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => "Rule violation: You cannot opt for '{$course->course_title}' because it is offered by your own department ({$student->branch})."
                ], 422);
            }
        }

        // Atomic submission
        DB::beginTransaction();
        try {
            $reg = OeRegistration::updateOrCreate(
                ['student_adm_no' => $student->adm_no],
                [
                    'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                    'student_name' => $student->name,
                    'parent_branch' => $student->branch,
                    'option_1_course_id' => $options[0],
                    'option_2_course_id' => $options[1],
                    'option_3_course_id' => $options[2],
                    'option_4_course_id' => $options[3],
                    'option_5_course_id' => $options[4],
                    'registered_at' => $now,
                    'ip_address' => $request->ip(),
                    'status' => 'submitted',
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Your 5 Open Elective preferences have been recorded successfully!',
                'token' => 'OE-2026-' . str_pad($reg->id, 4, '0', STR_PAD_LEFT),
                'registered_at' => $now->format('d M Y, h:i A'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'ERROR', 'message' => 'Submission failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Printable Option Chart & Registration Slip for Student.
     */
    public function printRegistrationSlip(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        $isAdmin = in_array($role, ['Super_Admin', 'Principal', 'Admin', 'Chairman']);

        if (!$isAdmin && (strtolower($role) !== 'student' || !$userId)) {
            return redirect('/')->with('error', 'Please log in to view your registration slip.');
        }

        if ($isAdmin && strtolower($role) !== 'student') {
            $simAdmNo = $request->input('student_adm_no');
            $student = $simAdmNo ? Student::where('adm_no', $simAdmNo)->first() : null;
            if (!$student) {
                $registration = OeRegistration::latest()->first();
                $student = $registration ? Student::where('adm_no', $registration->student_adm_no)->first() : null;
            }
        } else {
            $student = Student::where('reg_no', $userId)
                ->orWhere('sbte_reg_no', Session::get('sbteRegNo'))
                ->orWhere('adm_no', $userId)
                ->first();
        }

        if (!$student) {
            return redirect('/dashboard/student')->with('error', 'Student not found.');
        }

        $registration = OeRegistration::with(['option1Course', 'option2Course', 'option3Course', 'option4Course', 'option5Course', 'allottedCourse'])
            ->where('student_adm_no', $student->adm_no)
            ->first();

        if (!$registration) {
            return redirect('/student/open-elective')->with('error', 'No registration found for this student.');
        }

        $settings = OeSetting::getActiveSettings();

        return view('open_elective.student_option_chart_print', compact('student', 'registration', 'settings'));
    }
}
