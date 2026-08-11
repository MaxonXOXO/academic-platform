<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\RemedialRoom;
use App\Models\RemedialStudent;
use App\Models\RemedialSessionLog;
use App\Models\RemedialAssessment;
use App\Models\RemedialAssessmentScore;
use Illuminate\Support\Str;

class RemedialController extends Controller
{
    /**
     * Get subjects assigned to this staff member (Lecturer or Demonstrator)
     */
    public function getAssignedSubjects(Request $request)
    {
        $userId = Session::get('userId');
        if (!$userId) return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized']);

        // Staff assigned to subjects (Lecturers, Demonstrators)
        $subjectAssignments = \App\Models\SubjectStaffAssignment::with(['batchSubject.classroom'])
            ->where('staff_mobile_no', $userId)
            ->get();

        $subjects = [];
        foreach ($subjectAssignments as $sa) {
            if ($sa->batchSubject && $sa->batchSubject->classroom) {
                if ($sa->batchSubject->classroom->current_semester <= 6) { // Active only
                    $subjects[] = [
                        'classroom_id' => $sa->batchSubject->classroom->classroom_id,
                        'batch_name' => $sa->batchSubject->classroom->batch_name,
                        'subject_code' => $sa->batchSubject->subject_code,
                        'subject_name' => $sa->batchSubject->subject_name,
                        'semester' => $sa->batchSubject->semester,
                    ];
                }
            }
        }

        // De-duplicate by classroom and subject
        $unique = collect($subjects)->unique(function ($item) {
            return $item['classroom_id'] . $item['subject_code'];
        })->values()->all();

        return response()->json(['status' => 'SUCCESS', 'subjects' => $unique]);
    }

    /**
     * Get student performance for a specific subject
     */
    public function getStudentPerformance(Request $request)
    {
        $classroomId = $request->query('classroom_id');
        $subjectCode = $request->query('subject_code');

        $students = DB::table('students')->where('classroom_id', $classroomId)->get();
        $marks = DB::table('academic_marks')
            ->where('subject_code', $subjectCode)
            ->get()
            ->groupBy('reg_no');

        $results = [];
        foreach ($students as $student) {
            $studentMarks = $marks->get($student->reg_no, collect());
            $totalMarks = $studentMarks->sum('marks_obtained');
            
            $results[] = [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'total_marks' => $totalMarks,
            ];
        }

        // Sort ascending by marks
        usort($results, function($a, $b) {
            return $a['total_marks'] <=> $b['total_marks'];
        });

        return response()->json(['status' => 'SUCCESS', 'students' => $results]);
    }

    public function createRoom(Request $request)
    {
        $userId = Session::get('userId');
        $classroomId = $request->input('classroom_id');
        $subjectCode = $request->input('subject_code');
        $studentRegNos = $request->input('students', []);

        if (empty($studentRegNos)) {
            return response()->json(['status' => 'ERROR', 'message' => 'No students selected.']);
        }

        // Check if room already exists for this staff+subject+batch combination
        $existing = RemedialRoom::where('classroom_id', $classroomId)
            ->where('subject_code', $subjectCode)
            ->where('created_by_mobile', $userId)
            ->first();

        if ($existing) {
            $roomId = $existing->room_id;
        } else {
            $roomId = (string) Str::uuid();
            RemedialRoom::create([
                'room_id' => $roomId,
                'classroom_id' => $classroomId,
                'subject_code' => $subjectCode,
                'created_by_mobile' => $userId,
                'status' => 'active'
            ]);
        }

        foreach ($studentRegNos as $reg) {
            RemedialStudent::updateOrCreate([
                'room_id' => $roomId,
                'reg_no' => $reg
            ]);
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Remedial room provisioned successfully.']);
    }

    public function getRooms(Request $request)
    {
        $userId = Session::get('userId');
        $rooms = RemedialRoom::with(['students'])->where('created_by_mobile', $userId)->get();

        $output = [];
        foreach ($rooms as $room) {
            $classroom = DB::table('class_management')->where('classroom_id', $room->classroom_id)->first();
            if (!$classroom) {
                $classroom = DB::table('r26_class_management')->where('classroom_id', $room->classroom_id)->first();
            }
            $subject = DB::table('batch_subjects')->where('subject_code', $room->subject_code)->where('classroom_id', $room->classroom_id)->first();
            
            $lecturer = DB::table('staff_profiles')->where('mobile_no', $room->created_by_mobile)->first();
            $lecturerName = $lecturer ? $lecturer->name : 'Unknown Lecturer';
            
            $branchCode = $classroom ? $classroom->branch : 'Unknown';
            $branches = [
                'EL' => 'Electronics Engineering',
                'ME' => 'Mechanical Engineering',
                'CE' => 'Civil Engineering',
                'EEE' => 'Electrical & Electronics Engineering',
                'CT' => 'Computer Engineering',
                'AU' => 'Automobile Engineering',
                'GEN_AIDED' => 'General Department (Aided)',
                'GEN_SF' => 'General Department (Self Finance)',
                'GEN_DEPT_COORDINATOR_AIDED' => 'General Department (Aided)',
                'GEN_DEPT_COORDINATOR_SELF_FINANCE' => 'General Department (Self Finance)'
            ];
            $deptName = $branches[strtoupper($branchCode)] ?? $branchCode;

            $output[] = [
                'room_id' => $room->room_id,
                'batch_name' => $classroom ? $classroom->classroom_id : 'Unknown',
                'subject_code' => $room->subject_code,
                'subject_name' => $subject ? $subject->subject_name : $room->subject_code,
                'student_count' => $room->students->count(),
                'status' => $room->status,
                'created_at' => $room->created_at->format('Y-m-d'),
                'lecturer_name' => $lecturerName,
                'department' => $deptName
            ];
        }

        return response()->json(['status' => 'SUCCESS', 'rooms' => $output]);
    }

    public function getRoomDetails($roomId)
    {
        $userId = Session::get('userId');
        $room = RemedialRoom::with(['students', 'logs', 'assessments'])->where('room_id', $roomId)->where('created_by_mobile', $userId)->first();
        if (!$room) return response()->json(['status' => 'ERROR', 'message' => 'Room not found.']);

        $classroom = DB::table('class_management')->where('classroom_id', $room->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $room->classroom_id)->first();
        }
        $subject = DB::table('batch_subjects')->where('subject_code', $room->subject_code)->where('classroom_id', $room->classroom_id)->first();

        $studentList = [];
        foreach ($room->students as $rs) {
            $s = DB::table('students')->where('reg_no', $rs->reg_no)->first();
            $studentList[] = [
                'reg_no' => $rs->reg_no,
                'name' => $s ? $s->name : 'Unknown',
                'sbte_reg_no' => $s ? $s->sbte_reg_no : '-',
                'added_at' => $rs->added_at
            ];
        }

        // Get test_configs for linking
        $availableTests = DB::table('test_configs')
            ->where('classroom_id', $room->classroom_id)
            ->where('subject_code', $room->subject_code)
            ->get();

        $lecturer = DB::table('staff_profiles')->where('mobile_no', $room->created_by_mobile)->first();
        $lecturerName = $lecturer ? $lecturer->name : 'Unknown Lecturer';
        
        $branchCode = $classroom ? $classroom->branch : 'Unknown';
        $branches = [
            'EL' => 'Electronics Engineering',
            'ME' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CT' => 'Computer Engineering',
            'AU' => 'Automobile Engineering',
            'GEN_AIDED' => 'General Department (Aided)',
            'GEN_SF' => 'General Department (Self Finance)',
            'GEN_DEPT_COORDINATOR_AIDED' => 'General Department (Aided)',
            'GEN_DEPT_COORDINATOR_SELF_FINANCE' => 'General Department (Self Finance)'
        ];
        $deptName = $branches[strtoupper($branchCode)] ?? $branchCode;

        return response()->json([
            'status' => 'SUCCESS',
            'room' => [
                'room_id' => $room->room_id,
                'batch_name' => $classroom ? $classroom->classroom_id : 'Unknown',
                'semester' => $classroom ? $classroom->current_semester : 'N/A',
                'batch_year' => $classroom ? $classroom->batch_year : 'N/A',
                'subject_code' => $room->subject_code,
                'subject_name' => $subject ? $subject->subject_name : $room->subject_code,
                'students' => $studentList,
                'logs' => $room->logs,
                'assessments' => $room->assessments,
                'available_tests' => $availableTests,
                'lecturer_name' => $lecturerName,
                'department' => $deptName
            ]
        ]);
    }

    public function addStudent(Request $request, $roomId)
    {
        $regNo = $request->input('reg_no');
        RemedialStudent::updateOrCreate([
            'room_id' => $roomId,
            'reg_no' => $regNo
        ]);
        return response()->json(['status' => 'SUCCESS']);
    }

    public function removeStudent(Request $request, $roomId)
    {
        $regNo = $request->input('reg_no');
        RemedialStudent::where('room_id', $roomId)->where('reg_no', $regNo)->delete();
        return response()->json(['status' => 'SUCCESS']);
    }

    public function saveLog(Request $request, $roomId)
    {
        $log = RemedialSessionLog::create([
            'log_id' => (string) Str::uuid(),
            'room_id' => $roomId,
            'session_date' => $request->input('session_date'),
            'start_time' => $request->input('start_time'),
            'duration_minutes' => $request->input('duration_minutes', 60),
            'topic_covered' => $request->input('topic_covered'),
            'attendance_data' => $request->input('attendance', [])
        ]);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Session log saved.']);
    }

    public function getAssessments($roomId)
    {
        $assessments = RemedialAssessment::with('scores')->where('room_id', $roomId)->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'SUCCESS', 'assessments' => $assessments]);
    }

    public function createAssessment(Request $request, $roomId)
    {
        $id = (string) Str::uuid();
        $assessment = RemedialAssessment::create([
            'assessment_id' => $id,
            'room_id' => $roomId,
            'type' => $request->input('type'),
            'linked_test_id' => $request->input('linked_test_id'),
            'title' => $request->input('title'),
            'max_marks' => $request->input('max_marks', 20),
            'co_structure' => $request->input('co_structure')
        ]);

        return response()->json(['status' => 'SUCCESS', 'assessment' => $assessment]);
    }

    public function saveAssessmentScores(Request $request, $roomId, $assessmentId)
    {
        $scores = $request->input('scores', []);
        
        foreach ($scores as $s) {
            RemedialAssessmentScore::updateOrCreate(
                ['assessment_id' => $assessmentId, 'reg_no' => $s['reg_no']],
                ['score' => isset($s['score']) ? $s['score'] : null, 'co_scores' => isset($s['co_scores']) ? $s['co_scores'] : null]
            );
        }

        return response()->json(['status' => 'SUCCESS']);
    }

    public function syncOnlineScores(Request $request, $roomId, $assessmentId)
    {
        $assessment = RemedialAssessment::where('assessment_id', $assessmentId)->first();
        if (!$assessment || !$assessment->linked_test_id) {
            return response()->json(['status' => 'ERROR', 'message' => 'No linked test found']);
        }

        $attempts = DB::table('test_attempts')
            ->where('test_id', $assessment->linked_test_id)
            ->whereIn('status', ['submitted', 'completed'])
            ->get();

        foreach ($attempts as $attempt) {
            RemedialAssessmentScore::updateOrCreate(
                ['assessment_id' => $assessmentId, 'reg_no' => $attempt->reg_no],
                ['score' => $attempt->total_score]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Scores synced']);
    }

    public function printAssessmentReport($roomId, $assessmentId)
    {
        $room = RemedialRoom::where('room_id', $roomId)->first();
        if (!$room) return response("Remedial room not found.", 404);

        $assessment = RemedialAssessment::where('assessment_id', $assessmentId)->first();
        if (!$assessment) return response("Assessment not found.", 404);

        $scores = RemedialAssessmentScore::where('assessment_id', $assessmentId)->get();
        $studentRegs = DB::table('remedial_students')->where('room_id', $roomId)->pluck('reg_no')->toArray();
        $students = DB::table('students')->whereIn('reg_no', $studentRegs)->get(['reg_no', 'name', 'sbte_reg_no']);

        $students = $students->map(function ($student) use ($scores, $assessment) {
            $sc = $scores->where('reg_no', $student->reg_no)->first();
            $student->score = $sc ? $sc->score : '-';
            $coScores = $sc && $sc->co_scores ? (is_string($sc->co_scores) ? json_decode($sc->co_scores, true) : (array)$sc->co_scores) : [];
            $coMap = [];
            if ($assessment->co_structure) {
                foreach (array_keys($assessment->co_structure) as $co) {
                    $coMap[$co] = isset($coScores[$co]) ? $coScores[$co] : '-';
                }
            }
            $student->co_scores = $coMap;
            return $student;
        });

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CS' => 'Computer Engineering',
        ];
        
        $branchKey = strtoupper(explode('_', $room->classroom_id)[0] ?? '');
        $fullDepartment = $branchMap[$branchKey] ?? $branchKey;
        
        $cleanedBatch = preg_replace('/^[A-Z]+_/', '', $room->classroom_id);
        $cleanedBatch = str_replace('_', ' - ', $cleanedBatch);

        $subject = DB::table('batch_subjects')->where('subject_code', $room->subject_code)->where('classroom_id', $room->classroom_id)->first();

        return view('remedial_assessment_report_print', [
            'room' => $room,
            'assessment' => $assessment,
            'subject' => $subject,
            'fullDepartment' => $fullDepartment,
            'cleanedBatch' => $cleanedBatch,
            'students' => $students,
            'totalStudents' => $students->count(),
            'currentYear' => date('Y')
        ]);
    }

    public function printAttendanceReport($roomId)
    {
        $room = RemedialRoom::where('room_id', $roomId)->first();
        if (!$room) return response("Remedial room not found.", 404);

        $logs = RemedialSessionLog::where('room_id', $roomId)->orderBy('session_date', 'asc')->get();
        $studentRegs = DB::table('remedial_students')->where('room_id', $roomId)->pluck('reg_no')->toArray();
        $students = DB::table('students')->whereIn('reg_no', $studentRegs)->orderBy('name', 'asc')->get(['reg_no', 'name', 'sbte_reg_no']);

        $lecturer = DB::table('staff_profiles')->where('mobile_no', $room->created_by_mobile)->first();
        $lecturerName = $lecturer ? $lecturer->name : 'Unknown Lecturer';

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CT' => 'Computer Engineering',
            'AU' => 'Automobile Engineering',
        ];
        
        $branchKey = strtoupper(explode('_', $room->classroom_id)[0] ?? '');
        $fullDepartment = $branchMap[$branchKey] ?? $branchKey;
        
        $cleanedBatch = preg_replace('/^[A-Z_]+_/', '', $room->classroom_id);
        $cleanedBatch = str_replace('_', ' - ', $cleanedBatch);

        $subject = DB::table('batch_subjects')->where('subject_code', $room->subject_code)->where('classroom_id', $room->classroom_id)->first();

        // Calculate attendance matrix
        $attendanceMatrix = [];
        foreach ($students as $student) {
            $presentCount = 0;
            $logsAttendance = [];
            
            foreach ($logs as $log) {
                $isPresent = is_array($log->attendance_data) && in_array($student->reg_no, $log->attendance_data);
                if ($isPresent) {
                    $presentCount++;
                }
                $logsAttendance[$log->log_id] = $isPresent;
            }
            
            $percentage = count($logs) > 0 ? round(($presentCount / count($logs)) * 100) : 0;
            
            $attendanceMatrix[$student->reg_no] = [
                'name' => $student->name,
                'sbte_reg_no' => $student->sbte_reg_no,
                'reg_no' => $student->reg_no,
                'present_count' => $presentCount,
                'percentage' => $percentage,
                'sessions' => $logsAttendance
            ];
        }

        return view('remedial_attendance_report_print', [
            'room' => $room,
            'logs' => $logs,
            'subject' => $subject,
            'lecturerName' => $lecturerName,
            'fullDepartment' => $fullDepartment,
            'cleanedBatch' => $cleanedBatch,
            'attendanceMatrix' => $attendanceMatrix,
            'totalStudents' => $students->count(),
            'currentDate' => date('d-m-Y')
        ]);
    }

    public function printAnalysisReport($roomId)
    {
        $room = RemedialRoom::where('room_id', $roomId)->first();
        if (!$room) return response("Remedial room not found.", 404);

        $logs = RemedialSessionLog::where('room_id', $roomId)->orderBy('session_date', 'asc')->get();
        $studentRegs = DB::table('remedial_students')->where('room_id', $roomId)->pluck('reg_no')->toArray();
        $students = DB::table('students')->whereIn('reg_no', $studentRegs)->orderBy('name', 'asc')->get(['reg_no', 'name', 'sbte_reg_no']);

        $lecturer = DB::table('staff_profiles')->where('mobile_no', $room->created_by_mobile)->first();
        $lecturerName = $lecturer ? $lecturer->name : 'Unknown Lecturer';

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CT' => 'Computer Engineering',
            'AU' => 'Automobile Engineering',
        ];
        
        $branchKey = strtoupper(explode('_', $room->classroom_id)[0] ?? '');
        $fullDepartment = $branchMap[$branchKey] ?? $branchKey;
        
        $cleanedBatch = preg_replace('/^[A-Z_]+_/', '', $room->classroom_id);
        $cleanedBatch = str_replace('_', ' - ', $cleanedBatch);

        $subject = DB::table('batch_subjects')->where('subject_code', $room->subject_code)->where('classroom_id', $room->classroom_id)->first();

        // Get assessments for test attended & improvement status
        $assessments = RemedialAssessment::where('room_id', $roomId)->get();
        $assessmentIds = $assessments->pluck('assessment_id')->toArray();
        $scores = RemedialAssessmentScore::whereIn('assessment_id', $assessmentIds)->get();

        // Calculate analysis matrix
        $analysisMatrix = [];
        foreach ($students as $student) {
            $presentCount = 0;
            $absentCount = 0;
            $logsAttendance = [];
            
            foreach ($logs as $log) {
                $isPresent = is_array($log->attendance_data) && in_array($student->reg_no, $log->attendance_data);
                if ($isPresent) {
                    $presentCount++;
                } else {
                    $absentCount++;
                }
                $logsAttendance[$log->log_id] = $isPresent;
            }
            
            // Tests attended count
            $studentScores = $scores->where('reg_no', $student->reg_no);
            $testsAttended = $studentScores->count();

            // Improvement status & Remarks
            $improvementStatus = 'No Tests';
            $remark = 'Requires attention';
            if ($testsAttended > 0) {
                // Find latest score percentage
                $latestScoreObj = $studentScores->sortByDesc('created_at')->first();
                $latestScoreVal = $latestScoreObj ? $latestScoreObj->score : 0;
                
                $assocAssessment = $assessments->where('assessment_id', $latestScoreObj->assessment_id)->first();
                $maxMark = $assocAssessment ? $assocAssessment->max_marks : 100;
                
                $latestPercent = $maxMark > 0 ? ($latestScoreVal / $maxMark) * 100 : 0;
                
                if ($latestPercent >= 50) {
                    $improvementStatus = 'Improved';
                    $remark = 'Satisfactory progress';
                } else {
                    $improvementStatus = 'Marginal';
                    $remark = 'Need improvement';
                }
            }
            
            if ($presentCount == count($logs) && count($logs) > 0) {
                $remark = 'Regular & Active';
            }

            $analysisMatrix[$student->reg_no] = [
                'name' => $student->name,
                'sbte_reg_no' => $student->sbte_reg_no ?: '-',
                'reg_no' => $student->reg_no,
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'sessions' => $logsAttendance,
                'tests_attended' => $testsAttended . ' / ' . count($assessments),
                'improvement_status' => $improvementStatus,
                'remark' => $remark
            ];
        }

        return view('remedial_analysis_report_print', [
            'room' => $room,
            'logs' => $logs,
            'subject' => $subject,
            'lecturerName' => $lecturerName,
            'fullDepartment' => $fullDepartment,
            'cleanedBatch' => $cleanedBatch,
            'analysisMatrix' => $analysisMatrix,
            'totalStudents' => $students->count(),
            'currentDate' => date('d-m-Y')
        ]);
    }

    public function deleteRoom($roomId)
    {
        $userId = Session::get('userId');
        $room = RemedialRoom::where('room_id', $roomId)->where('created_by_mobile', $userId)->first();
        if (!$room) {
            return response()->json(['status' => 'ERROR', 'message' => 'Room not found or unauthorized.'], 403);
        }

        // Delete related scores
        $assessmentIds = RemedialAssessment::where('room_id', $roomId)->pluck('assessment_id');
        RemedialAssessmentScore::whereIn('assessment_id', $assessmentIds)->delete();

        // Delete assessments
        RemedialAssessment::where('room_id', $roomId)->delete();

        // Delete session logs
        RemedialSessionLog::where('room_id', $roomId)->delete();

        // Delete students
        RemedialStudent::where('room_id', $roomId)->delete();

        // Delete room
        $room->delete();

        return response()->json(['status' => 'SUCCESS', 'message' => 'Room deleted successfully.']);
    }

    public function updateRoomStatus(Request $request, $roomId)
    {
        $userId = Session::get('userId');
        $room = RemedialRoom::where('room_id', $roomId)->where('created_by_mobile', $userId)->first();
        if (!$room) {
            return response()->json(['status' => 'ERROR', 'message' => 'Room not found or unauthorized.'], 403);
        }

        $status = $request->input('status', 'active');
        $room->update(['status' => $status]);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Room status updated successfully.', 'room' => $room]);
    }
}
