<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StudentMockTestController extends Controller
{
    /**
     * Render the main isolated mock test page.
     */
    public function index()
    {
        $regNo = Session::get('userId');
        if (!$regNo || Session::get('userRole') !== 'Student') {
            return redirect('/');
        }
        return view('student_mock_test');
    }

    /**
     * Fetch all current semester subjects and check their attempt limits.
     */
    public function getSubjects()
    {
        $regNo = Session::get('userId');
        if (!$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $student = DB::table('students')->where('reg_no', $regNo)->first();
            if (!$student) {
                return response()->json(['status' => 'ERROR', 'message' => 'Student not found.']);
            }

            $classroom = DB::table('class_management')->where('classroom_id', $student->classroom_id)->first();
            $currentSem = $classroom ? $classroom->current_semester : 1;

            $subjects = DB::table('batch_subjects')
                ->where('classroom_id', $student->classroom_id)
                ->where('semester', $currentSem)
                ->get(['id', 'subject_name', 'subject_code']);

            $today = now()->toDateString();
            $attempts = DB::table('student_mock_test_attempts')
                ->where('reg_no', $regNo)
                ->where('attempted_date', $today)
                ->pluck('subject_code')
                ->toArray();

            $result = $subjects->map(function ($subj) use ($attempts) {
                return [
                    'id' => $subj->id,
                    'subject_name' => $subj->subject_name,
                    'subject_code' => $subj->subject_code,
                    'already_attempted_today' => in_array($subj->subject_code, $attempts)
                ];
            });

            return response()->json([
                'status' => 'SUCCESS',
                'data' => [
                    'student_name' => $student->name,
                    'sbte_reg_no' => $student->sbte_reg_no ?? 'N/A',
                    'roll_no' => $student->roll_no ?? 'N/A',
                    'classroom_name' => $classroom->classroom_name ?? 'N/A',
                    'batch' => $classroom->batch ?? 'N/A',
                    'subjects' => $result
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Start a mock test: verify limits, pool questions, fallback to Gemini API generation.
     */
    public function startMockTest(Request $request)
    {
        $regNo = Session::get('userId');
        if (!$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $request->validate([
            'subject_code' => 'required|string',
            'co_tag' => 'required|string',
            'num_questions' => 'required|integer|min:1|max:30'
        ]);

        $subjectCode = $request->input('subject_code');
        $coTag = $request->input('co_tag');
        $numQuestions = (int) $request->input('num_questions');
        $today = now()->toDateString();

        try {
            // 1. Verify Daily Limit
            $alreadyAttempted = DB::table('student_mock_test_attempts')
                ->where('reg_no', $regNo)
                ->where('subject_code', $subjectCode)
                ->where('attempted_date', $today)
                ->exists();

            if ($alreadyAttempted) {
                return response()->json([
                    'status' => 'ERROR', 
                    'message' => 'Daily practice test limit reached for this subject. Only 1 mock test per subject is allowed per day.'
                ]);
            }

            // Get batch subject details
            $subj = DB::table('batch_subjects')->where('subject_code', $subjectCode)->first();
            if (!$subj) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
            }

            // 2. Fetch from Database Pool
            $query = DB::table('question_bank')
                ->where('subject_code', $subjectCode)
                ->where('type', 'MCQ');

            if ($coTag !== 'All') {
                $query->where('co_tag', $coTag);
            }

            $dbQuestions = $query->inRandomOrder()->get();
            $questions = [];

            foreach ($dbQuestions as $q) {
                $options = json_decode($q->options, true) ?: [];
                $questions[] = [
                    'question_text' => $q->question_text,
                    'options' => $options,
                    'correct_answer' => $q->correct_answer,
                    'co_tag' => $q->co_tag,
                    'source' => 'Database'
                ];
            }

            // 3. Fallback to Gemini AI Generation if Database lacks questions
            $needed = $numQuestions - count($questions);
            if ($needed > 0) {
                $apiKey = env('GEMINI_API_KEY');
                if ($apiKey && \App\Http\Controllers\SystemSettingController::isAiEnabled()) {
                    // Load course file details as context
                    $cf = DB::table('course_files')->where('batch_subject_id', $subj->id)->first();
                    $coContext = "";
                    if ($cf && !empty($cf->parsed_cos)) {
                        $cos = json_decode($cf->parsed_cos, true);
                        if (is_array($cos)) {
                            foreach ($cos as $coItem) {
                                if ($coTag === 'All' || $coItem['id'] === $coTag) {
                                    $coContext .= "CO Outcome '{$coItem['id']}': {$coItem['description']}. ";
                                }
                            }
                        }
                    }

                    if ($cf && !empty($cf->parsed_modules)) {
                        $modules = json_decode($cf->parsed_modules, true);
                        if (is_array($modules)) {
                            $coContext .= "\nCourse Modules:\n";
                            foreach ($modules as $m) {
                                $coContext .= "- Module {$m['module_id']}: {$m['content']}\n";
                            }
                        }
                    }

                    $coTargetText = ($coTag === 'All') ? 'across all syllabus units' : "focusing strictly on '{$coTag}' ({$coContext})";

                    try {
                        $prompt = "You are an engineering examiner creating a practice multiple choice test for the course '{$subj->subject_name}' (Code: {$subjectCode}).
Generate exactly {$needed} multiple-choice questions (MCQs) {$coTargetText}.

Universal settings for question difficulty:
- The questions MUST be moderate and simple in difficulty.
- Focus on core, fundamental concepts.
- Avoid overly complex calculations, trick questions, or obscure trivia.
- Ensure language is clear and straightforward.

Each question MUST have exactly 4 distinct options.
The correct answer must be one of the four options exactly.

Return ONLY a valid JSON array of objects with this schema:
[
  {
    \"question_text\": \"...\",
    \"options\": [\"Option A\", \"Option B\", \"Option C\", \"Option D\"],
    \"correct_answer\": \"Option A\"
  }
]
No extra markdown blocks, no leading/trailing commentary.";

                        $response = Http::timeout(45)->post(
                            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                            [
                                'contents' => [['parts' => [['text' => $prompt]]]],
                                'generationConfig' => ['responseMimeType' => 'application/json']
                            ]
                        );

                        if ($response->successful()) {
                            $jsonString = $response->json('candidates.0.content.parts.0.text');
                            $cleanJson = trim(str_replace(['```json', '```JSON', '```'], '', $jsonString));
                            $parsed = json_decode($cleanJson, true);
                            if (is_array($parsed)) {
                                foreach ($parsed as $aiQ) {
                                    if (!empty($aiQ['question_text']) && !empty($aiQ['options']) && count($aiQ['options']) === 4 && !empty($aiQ['correct_answer'])) {
                                        $questions[] = [
                                            'question_text' => $aiQ['question_text'],
                                            'options' => $aiQ['options'],
                                            'correct_answer' => $aiQ['correct_answer'],
                                            'co_tag' => ($coTag === 'All') ? 'CO1' : $coTag,
                                            'source' => 'Gemini AI'
                                        ];
                                    }
                                }
                            }
                        }
                    } catch (\Exception $aiEx) {
                        Log::warning("Gemini Mock MCQ generation failed: " . $aiEx->getMessage());
                    }
                }
            }

            // Shuffle questions and options to desired count
            if (count($questions) > 0) {
                shuffle($questions);
                $questions = array_slice($questions, 0, $numQuestions);
                foreach ($questions as &$q) {
                    if (!empty($q['options']) && is_array($q['options'])) {
                        $opts = array_values(array_map('trim', $q['options']));
                        shuffle($opts);
                        $q['options'] = $opts;
                    }
                }
                unset($q);
            }

            // Verify if we actually got questions
            if (count($questions) === 0) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'No questions could be fetched or generated for this subject/CO combination.'
                ]);
            }

            // 4. Log attempt to prevent starting another test today
            DB::table('student_mock_test_attempts')->insert([
                'reg_no' => $regNo,
                'subject_code' => $subjectCode,
                'attempted_date' => $today,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'data' => [
                    'questions' => $questions
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }
}
