<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\ProgramAttainment;
use App\Models\BatchSubject;
use App\Models\CourseFile;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Services\AttainmentService;

class ProgramAttainmentController extends Controller
{
    /**
     * Program Attainment Dashboard for HOD / Principal.
     * Evaluates NBA Criterion 3 PO1-PO11 and PSO1-PSO3.
     */
    public function index(Request $request, $classroomId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['HOD', 'Principal', 'Super_Admin', 'Admin', 'Chairman'])) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $userBranch = Session::get('userBranch');

        // Locate classroom in Rev 2021 or Rev 2026
        $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
        $revision = 'REV2021';
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $classroomId)->first();
            $revision = 'REV2026';
        }

        if (!$classroom) {
            abort(404, 'Classroom / Cohort not found.');
        }

        $branch = $classroom->branch ?? $classroom->department ?? $userBranch ?? 'Engineering';
        $batchYear = $classroom->batch_year ?? (int)substr($classroomId, -9, 4);

        // Fetch or create program attainment record
        $programRecord = ProgramAttainment::firstOrCreate(
            ['classroom_id' => $classroomId],
            [
                'branch' => $branch,
                'batch_year' => $batchYear,
                'revision' => $revision,
                'status' => 'Draft',
                'po_targets' => [
                    'PO1' => 2.0, 'PO2' => 2.0, 'PO3' => 1.8, 'PO4' => 1.8, 'PO5' => 1.8,
                    'PO6' => 1.8, 'PO7' => 1.8, 'PO8' => 1.8, 'PO9' => 2.0, 'PO10' => 2.0, 'PO11' => 1.8,
                    'PSO1' => 2.0, 'PSO2' => 2.0, 'PSO3' => 1.8
                ],
                'indirect_surveys' => [
                    'PO1' => 2.5, 'PO2' => 2.4, 'PO3' => 2.3, 'PO4' => 2.3, 'PO5' => 2.4,
                    'PO6' => 2.4, 'PO7' => 2.5, 'PO8' => 2.6, 'PO9' => 2.6, 'PO10' => 2.5, 'PO11' => 2.4,
                    'PSO1' => 2.5, 'PSO2' => 2.4, 'PSO3' => 2.3
                ]
            ]
        );

        $data = $this->compileProgramAttainmentData($classroomId, $classroom, $revision, $programRecord);

        return view('hod.program_attainment_dashboard', array_merge($data, [
            'classroom' => $classroom,
            'classroomId' => $classroomId,
            'revision' => $revision,
            'programRecord' => $programRecord,
            'branch' => $branch
        ]));
    }

    /**
     * Save PO targets, indirect survey scores, and action plans.
     */
    public function saveConfig(Request $request, $classroomId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['HOD', 'Principal', 'Super_Admin', 'Admin', 'Chairman'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $record = ProgramAttainment::where('classroom_id', $classroomId)->first();
        if (!$record) {
            return response()->json(['status' => 'ERROR', 'message' => 'Record not found'], 404);
        }

        if ($request->has('po_targets')) {
            $record->po_targets = $request->input('po_targets');
        }
        if ($request->has('indirect_surveys')) {
            $record->indirect_surveys = $request->input('indirect_surveys');
        }
        if ($request->has('action_plans')) {
            $record->action_plans = $request->input('action_plans');
        }
        if ($request->has('status')) {
            $record->status = $request->input('status');
        }

        $record->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Program Attainment configuration saved successfully!'
        ]);
    }

    /**
     * Printable NBA Criteria 3 Report for external evaluation.
     */
    public function printReport(Request $request, $classroomId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['HOD', 'Principal', 'Super_Admin', 'Admin', 'Chairman'])) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
        $revision = 'REV2021';
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $classroomId)->first();
            $revision = 'REV2026';
        }
        if (!$classroom) abort(404, 'Classroom not found.');

        $programRecord = ProgramAttainment::firstOrCreate(
            ['classroom_id' => $classroomId],
            [
                'branch' => $classroom->branch ?? 'Engineering',
                'batch_year' => $classroom->batch_year ?? 2024,
                'revision' => $revision
            ]
        );

        $data = $this->compileProgramAttainmentData($classroomId, $classroom, $revision, $programRecord);

        return view('hod.program_attainment_print', array_merge($data, [
            'classroom' => $classroom,
            'classroomId' => $classroomId,
            'revision' => $revision,
            'programRecord' => $programRecord
        ]));
    }

    /**
     * Internal compiler that aggregates all courses in the cohort and evaluates PO contributions.
     */
    private function compileProgramAttainmentData($classroomId, $classroom, $revision, $programRecord)
    {
        $subjects = BatchSubject::where('classroom_id', $classroomId)
            ->orderBy('semester', 'asc')
            ->orderBy('subject_code', 'asc')
            ->get();

        $poList = AttainmentService::getProgramOutcomes();
        $psoList = AttainmentService::getProgramSpecificOutcomes($classroom->branch ?? '');
        $allPoKeys = array_merge(array_keys($poList), array_keys($psoList));

        $coursesMatrix = [];
        $coursesPoContributions = [];

        foreach ($subjects as $subj) {
            // Find CourseFile or Practicum/Practical CourseFile
            $courseFile = CourseFile::where('batch_subject_id', $subj->id)->first();
            $copoData = [];
            $coAttainments = [
                'CO1' => 2.50, 'CO2' => 2.40, 'CO3' => 2.30, 'CO4' => 2.40
            ];

            if ($courseFile) {
                $rawCopo = is_string($courseFile->parsed_copo_data) 
                    ? json_decode($courseFile->parsed_copo_data, true) 
                    : ($courseFile->parsed_copo_data ?: []);
                $copoData = $rawCopo['mappings'] ?? [];

                // Check stored attainment settings or results
                if ($courseFile->attainment_settings) {
                    $attSettings = is_string($courseFile->attainment_settings)
                        ? json_decode($courseFile->attainment_settings, true)
                        : $courseFile->attainment_settings;
                    if (!empty($attSettings['calculated_cos'])) {
                        foreach ($attSettings['calculated_cos'] as $coKey => $coVal) {
                            $coAttainments[$coKey] = (float)$coVal;
                        }
                    }
                }
            }

            // Fallback default mappings if empty
            if (empty($copoData)) {
                $copoData = [
                    'CO1' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'1', 'PO4'=>'1', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'1', 'PO8'=>'-', 'PO9'=>'2', 'PO10'=>'2', 'PO11'=>'-', 'PSO1'=>'2', 'PSO2'=>'1', 'PSO3'=>'-'],
                    'CO2' => ['PO1'=>'3', 'PO2'=>'3', 'PO3'=>'2', 'PO4'=>'2', 'PO5'=>'1', 'PO6'=>'-', 'PO7'=>'1', 'PO8'=>'-', 'PO9'=>'2', 'PO10'=>'2', 'PO11'=>'-', 'PSO1'=>'3', 'PSO2'=>'2', 'PSO3'=>'-'],
                    'CO3' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'3', 'PO4'=>'2', 'PO5'=>'2', 'PO6'=>'1', 'PO7'=>'1', 'PO8'=>'1', 'PO9'=>'2', 'PO10'=>'2', 'PO11'=>'1', 'PSO1'=>'3', 'PSO2'=>'2', 'PSO3'=>'1'],
                    'CO4' => ['PO1'=>'2', 'PO2'=>'3', 'PO3'=>'2', 'PO4'=>'3', 'PO5'=>'2', 'PO6'=>'2', 'PO7'=>'2', 'PO8'=>'1', 'PO9'=>'3', 'PO10'=>'3', 'PO11'=>'1', 'PSO1'=>'2', 'PSO2'=>'3', 'PSO3'=>'2'],
                ];
            }

            // Calculate PO contribution for this course
            $poContrib = AttainmentService::calculateCoursePoContribution($coAttainments, $copoData);

            $coursesMatrix[] = [
                'subject' => $subj,
                'co_attainments' => $coAttainments,
                'mappings' => $copoData,
                'po_contributions' => $poContrib
            ];

            $coursesPoContributions[] = $poContrib;
        }

        // Direct PO attainment across all courses
        $directPo = AttainmentService::calculateProgramDirectAttainment($coursesPoContributions);

        // Indirect PO attainment (from DB or default)
        $indirectSurveys = $programRecord->indirect_surveys ?: [];
        $poTargets = $programRecord->po_targets ?: [];

        // Final overall PO attainment (80% Direct + 20% Indirect)
        $finalPo = AttainmentService::calculateProgramFinalAttainment($directPo, $indirectSurveys, 0.80, 0.20);

        // Gap analysis & compliance flag
        $gapAnalysis = [];
        foreach ($allPoKeys as $key) {
            $target = isset($poTargets[$key]) ? (float)$poTargets[$key] : 2.0;
            $achieved = $finalPo[$key]['overall'] ?? 0.0;
            $gap = round($achieved - $target, 2);
            $gapAnalysis[$key] = [
                'target' => $target,
                'achieved' => $achieved,
                'gap' => $gap,
                'is_met' => $gap >= 0
            ];
        }

        return [
            'subjects' => $subjects,
            'coursesMatrix' => $coursesMatrix,
            'poList' => $poList,
            'psoList' => $psoList,
            'allPoKeys' => $allPoKeys,
            'directPo' => $directPo,
            'indirectSurveys' => $indirectSurveys,
            'poTargets' => $poTargets,
            'finalPo' => $finalPo,
            'gapAnalysis' => $gapAnalysis
        ];
    }
}
