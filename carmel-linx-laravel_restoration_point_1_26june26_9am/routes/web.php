<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\MentoringController;
use App\Http\Controllers\ActivityPointsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Gates
Route::get('/', function () {
    if (Session::has('userId')) {
        $role = Session::get('userRole');
        if ($role === 'Student') return redirect('/dashboard/student');
        if ($role === 'Super_Admin') return redirect('/dashboard/superadmin');
        if ($role === 'Admin') return redirect('/dashboard/admin');
        if ($role === 'Principal') return redirect('/dashboard/principal');
        if ($role === 'HOD') return redirect('/dashboard/hod');
        if ($role === 'Gen_Dept_Coordinator_Aided') return redirect('/dashboard/general-coordinator-aided');
        if ($role === 'Gen_Dept_Coordinator_Self_Finance') return redirect('/dashboard/general-coordinator-sf');
        if ($role === 'Lecturer') return redirect('/dashboard/lecturer');
        if ($role === 'Demonstrator') return redirect('/dashboard/demonstrator');
        if ($role === 'Trade_Instructor') return redirect('/dashboard/tradeinstructor');
        if ($role === 'Workshop_Superintendent') return redirect('/dashboard/workshop');
        return redirect('/dashboard/lecturer');
    }
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register/student', [AuthController::class, 'registerStudent']);
Route::post('/register/staff', [AuthController::class, 'registerStaff']);
Route::get('/logout', [AuthController::class, 'logout']);

// Protected Dashboard Renders
Route::middleware(['web'])->group(function () {
    
    Route::get('/dashboard/student', function () {
        if (Session::get('userRole') !== 'Student') return redirect('/');
        return view('student_dashboard');
    });

    Route::get('/dashboard/superadmin', function () {
        $role = Session::get('userRole');
        if ($role !== 'Super_Admin' && $role !== 'Principal') return redirect('/');
        return view('admin_control_desk');
    });

    Route::get('/dashboard/admin', function () {
        if (Session::get('userRole') !== 'Admin') return redirect('/');
        return view('admin_dashboard');
    });

    Route::get('/dashboard/principal', function () {
        $role = Session::get('userRole');
        if ($role !== 'Super_Admin' && $role !== 'Principal') return redirect('/');
        return view('admin_control_desk');
    });

    Route::get('/dashboard/hod', function () {
        if (Session::get('userRole') !== 'HOD') return redirect('/');
        return view('hod_dashboard');
    });

    Route::get('/dashboard/general-coordinator-aided', function () {
        if (Session::get('userRole') !== 'Gen_Dept_Coordinator_Aided') return redirect('/');
        return view('general_coordinator_aided_dashboard');
    });

    Route::get('/dashboard/general-coordinator-sf', function () {
        if (Session::get('userRole') !== 'Gen_Dept_Coordinator_Self_Finance') return redirect('/');
        return view('general_coordinator_sf_dashboard');
    });

    Route::get('/dashboard/lecturer', function () {
        if (Session::get('userRole') !== 'Lecturer') return redirect('/');
        return view('lecturer_dashboard');
    });

    Route::get('/dashboard/demonstrator', function () {
        if (Session::get('userRole') !== 'Demonstrator') return redirect('/');
        return view('demonstrator_dashboard');
    });

    Route::get('/dashboard/tradeinstructor', function () {
        if (Session::get('userRole') !== 'Trade_Instructor') return redirect('/');
        return view('trade_instructor_dashboard');
    });

    Route::get('/dashboard/tutor', function () {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') return redirect('/');
        return view('tutor_dashboard');
    });

    Route::get('/dashboard/workshop', function () {
        if (Session::get('userRole') !== 'Workshop_Superintendent') return redirect('/');
        return view('workshop_superintendent_dashboard');
    });

    Route::get('/course-files', function () {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') return redirect('/');
        return view('course_files_dashboard');
    });

    // Course File API Routes
    Route::get('/api/course-files/subjects', [App\Http\Controllers\CourseFileController::class, 'getStaffSubjects']);
    Route::get('/api/course-files/{id}', [App\Http\Controllers\CourseFileController::class, 'getCourseFile']);
    Route::post('/api/course-files/{id}', [App\Http\Controllers\CourseFileController::class, 'saveCourseFile']);
    Route::get('/api/course-files/{id}/preview/{docNo}', [App\Http\Controllers\CourseFileController::class, 'previewDocument']);
    Route::post('/api/course-files/{id}/document/{docNo}/save', [App\Http\Controllers\CourseFileController::class, 'saveDocumentPayload']);
    Route::post('/api/course-files/{id}/document/5/upload-cis', [App\Http\Controllers\CourseFileController::class, 'uploadCisPdf']);
    Route::post('/api/course-files/{id}/document/6/save-copo', [App\Http\Controllers\CourseFileController::class, 'saveCoPoMapping']);
    Route::get('/api/course-files/{id}/pdf', [App\Http\Controllers\CourseFileController::class, 'generatePdf']);

    // Core Data Actions
    Route::post('/api/approve-account', [DataController::class, 'approveAccount']);
    Route::post('/api/student/update/{regNo}', [DataController::class, 'updateStudentProfile']);
    Route::delete('/api/student/delete/{regNo}', [DataController::class, 'deleteStudentProfile']);
    Route::get('/api/tutor/classroom/{tutorMobile}', [DataController::class, 'getTutorClassroomRoster']);
    Route::post('/api/system/backup', [BackupController::class, 'backupDatabaseToDrive']);

    // Admin/Super Admin Endpoints
    Route::get('/api/admin/stats', [DataController::class, 'getAdminStats']);
    Route::get('/api/admin/users', [DataController::class, 'getUsersList']);
    Route::post('/api/admin/user/toggle-status', [DataController::class, 'toggleUserStatus']);
    Route::post('/api/admin/user/reset-password', [DataController::class, 'resetUserPassword']);
    Route::post('/api/admin/user/change-role', [DataController::class, 'changeUserRole']);
    Route::post('/api/admin/user/delete', [DataController::class, 'deleteUser']);
    Route::get('/api/audit-logs', [DataController::class, 'getAuditLogs']);

    // HOD Batch Management
    Route::get('/api/hod/batches', [DataController::class, 'getHodBatches']);
    Route::post('/api/hod/batches', [DataController::class, 'createHodBatch']);
    Route::post('/api/hod/batches/assign-tutor', [DataController::class, 'assignBatchTutor']);
    Route::post('/api/hod/batches/assign-mentor', [DataController::class, 'assignBatchMentor']);
    Route::get('/api/hod/batches/{classroomId}/students', [DataController::class, 'getBatchStudents']);
    Route::get('/api/hod/dept-staff', [DataController::class, 'getDeptStaff']);

    // HOD Subject Allocation
    Route::get('/api/hod/batches/{classroomId}/subjects', [DataController::class, 'getBatchSubjects']);
    Route::post('/api/hod/batches/subjects/create', [DataController::class, 'createBatchSubject']);
    Route::post('/api/hod/batches/subjects/{subjectId}/assign-staff', [DataController::class, 'assignSubjectStaff']);
    Route::delete('/api/hod/batches/subjects/{subjectId}', [DataController::class, 'deleteBatchSubject']);

    // Lecturer Endpoints
    Route::get('/api/lecturer/my-batches', [DataController::class, 'getLecturerBatches']);
    Route::post('/api/classroom/{subjectId}/syllabus', [App\Http\Controllers\ClassroomController::class, 'uploadSyllabus']);
    Route::get('/api/classroom/{subjectId}/details', [App\Http\Controllers\ClassroomController::class, 'getCourseDetails']);
    Route::post('/api/classroom/{subjectId}/save-lesson-plans', [App\Http\Controllers\ClassroomController::class, 'saveLessonPlans']);
    Route::get('/api/classroom/{subjectId}/generate-questions', [App\Http\Controllers\ClassroomController::class, 'generateAssignmentQuestions']);
    Route::post('/api/classroom/{subjectId}/save-assignment-deadline', [App\Http\Controllers\ClassroomController::class, 'saveAssignmentDeadline']);
    Route::post('/api/classroom/{subjectId}/save-assignment-marks', [App\Http\Controllers\ClassroomController::class, 'saveAssignmentMarks']);
    Route::post('/api/classroom/{subjectId}/generate-summative-paper', [App\Http\Controllers\ClassroomController::class, 'generateSummativePaper']);
    Route::post('/api/classroom/{subjectId}/save-summative-config', [App\Http\Controllers\ClassroomController::class, 'saveSummativeConfig']);
    Route::post('/api/classroom/{subjectId}/save-written-test-marks', [App\Http\Controllers\ClassroomController::class, 'saveWrittenTestMarks']);
    Route::post('/api/classroom/{subjectId}/publish-online-test', [App\Http\Controllers\TestEngineController::class, 'publishOnlineTest']);
    Route::get('/api/classroom/{subjectId}/active-online-tests', [App\Http\Controllers\TestEngineController::class, 'getActiveTestsLecturer']);
    Route::get('/api/test-engine/report/{testId}', [App\Http\Controllers\TestEngineController::class, 'generateTestReport']);
    Route::delete('/api/classroom/online-tests/{testId}', [App\Http\Controllers\TestEngineController::class, 'deleteOnlineTest']);
    Route::get('/api/classroom/online-tests/{testId}/key', [App\Http\Controllers\TestEngineController::class, 'getLecturerAnswerKey']);

    // Mentoring Endpoints
    Route::get('/api/mentoring/my-batches', [MentoringController::class, 'getMyBatches']);
    Route::get('/api/mentoring/students/{classroomId}', [MentoringController::class, 'getClassroomStudents']);
    Route::post('/api/mentoring/assign-batch', [MentoringController::class, 'assignBatch']);
    Route::post('/api/mentoring/assign-mentor2', [MentoringController::class, 'assignMentor2']);
    Route::get('/api/mentoring/diary/{regNo}', [MentoringController::class, 'getStudentDiary']);
    Route::post('/api/mentoring/diary/add', [MentoringController::class, 'addDiaryEntry']);
    Route::post('/api/mentoring/diary/approve', [MentoringController::class, 'approveDiaryEntry']);
    Route::get('/api/mentoring/report/{classroomId}', [MentoringController::class, 'getMentoringReport']);

    // Student Self-Service Mentoring
    Route::post('/api/student/mentoring/self-entry', [MentoringController::class, 'studentSelfEntry']);
    Route::get('/api/student/mentoring/diary', [MentoringController::class, 'studentViewDiary']);

    // Student Online Tests
    Route::get('/api/student/academic-report', [App\Http\Controllers\DataController::class, 'getAcademicReport']);
    Route::get('/api/student/online-tests', [App\Http\Controllers\TestEngineController::class, 'getAvailableTests']);
    Route::post('/api/student/online-tests/{testId}/start', [App\Http\Controllers\TestEngineController::class, 'startTest']);
    Route::post('/api/student/online-tests/{testId}/submit', [App\Http\Controllers\TestEngineController::class, 'submitTest']);

    // Activity Points
    Route::get('/api/student/activity-points', [ActivityPointsController::class, 'getStudentPoints']);
    Route::post('/api/student/activity-points', [ActivityPointsController::class, 'submitClaim']);
    Route::get('/api/tutor/activity-points', [ActivityPointsController::class, 'getClassroomClaims']);
    Route::post('/api/tutor/activity-points/{id}/verify', [ActivityPointsController::class, 'verifyClaim']);
    Route::get('/api/student/{regNo}/activity-summary', [ActivityPointsController::class, 'getStudentSummary']);
});
