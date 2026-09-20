<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Services\CarmiePlaybookService;

class CarmieAssistantController extends Controller
{
    protected CarmiePlaybookService $playbookService;

    public function __construct(CarmiePlaybookService $playbookService)
    {
        $this->playbookService = $playbookService;
    }

    /**
     * Handle chat messages and questions from users to Carmie.
     */
    public function ask(Request $request)
    {
        $query = trim($request->input('query', ''));
        if (empty($query)) {
            return response()->json([
                'status' => 'ERROR',
                'reply' => "Hi! I'm **Carmie**, your Carmel-linx Academic Assistant! 😊 How can I help you today?"
            ]);
        }

        $context = [
            'current_url'  => $request->input('current_url', ''),
            'user_role'    => Session::get('userRole', $request->input('user_role', 'Staff')),
            'subject_code' => $request->input('subject_code', ''),
            'subject_name' => $request->input('subject_name', ''),
        ];

        $normalizedQuery = $this->playbookService->normalizeQuery($query);

        // 1. Search verified Carmel-linx local playbook
        $matchedTopic = $this->playbookService->search($query, $context);

        // 2. Check if Gemini AI is active and configured
        $apiKey = config('services.gemini.key') ?: env('GEMINI_API_KEY');
        $isGeminiEnabled = !empty($apiKey);

        // Check if Admin has deactivated Gemini AI to save credits
        try {
            $setting = \DB::table('system_settings')->where('setting_key', 'gemini_ai_active')->first();
            if ($setting && ($setting->setting_value === '0' || $setting->setting_value === 'false')) {
                $isGeminiEnabled = false;
            }
        } catch (\Exception $e) {
            // Table may not exist; keep default
        }

        // 3. Try Gemini AI if enabled
        if ($isGeminiEnabled && !empty($apiKey)) {
            try {
                $aiResponse = $this->queryGemini($query, $matchedTopic, $context, $apiKey);
                if (!empty($aiResponse)) {
                    // Record in evolving question model
                    $this->playbookService->logUserQuestion($query, $normalizedQuery, $context, $matchedTopic, $aiResponse, 'gemini_ai');

                    return response()->json([
                        'status'       => 'SUCCESS',
                        'reply'        => $aiResponse,
                        'matched_topic'=> $matchedTopic['title'] ?? null,
                        'action_label' => $matchedTopic['action_label'] ?? null,
                        'action_route' => $matchedTopic['action_route'] ?? null,
                        'source'       => 'gemini_ai'
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning("Carmie Gemini AI fallback triggered: " . $e->getMessage());
            }
        }

        // 4. Local Playbook Response (100% Offline / Zero-Cost Mode)
        if ($matchedTopic) {
            $formattedSteps = "";
            foreach ($matchedTopic['steps'] as $step) {
                $formattedSteps .= "• " . $step . "\n\n";
            }

            $revisions = $matchedTopic['revisions'] ?? [];
            $badge = "";
            if (in_array('2021', $revisions) && !in_array('2026', $revisions)) {
                $badge = "📘 **[Revision 2021 - SBTE Keralam]**\n\n";
            } elseif (in_array('2026', $revisions) && !in_array('2021', $revisions)) {
                $badge = "📙 **[Revision 2026 - Outcome-Based Curriculum]**\n\n";
            } else {
                $badge = "🎓 **[Universal Academic Workflow - SBTE Keralam]**\n\n";
            }

            $refText = !empty($matchedTopic['manual_reference'])
                ? "📖 **Manual Reference:** " . $matchedTopic['manual_reference'] . "\n\n"
                : "";

            $reply = $badge
                   . "### " . $matchedTopic['title'] . "\n\n"
                   . $refText
                   . $matchedTopic['summary'] . "\n\n"
                   . "**Step-by-step instructions:**\n\n"
                   . $formattedSteps
                   . "\n🔗 *[Read full details in Carmel-Linx User Manual](/docs/carmel_linx_user_manual.html)*";

            // Record in evolving question model
            $this->playbookService->logUserQuestion($query, $normalizedQuery, $context, $matchedTopic, $reply, 'local_playbook');

            return response()->json([
                'status'       => 'SUCCESS',
                'reply'        => $reply,
                'matched_topic'=> $matchedTopic['title'],
                'action_label' => $matchedTopic['action_label'] ?? 'Open Workspace',
                'action_route' => $matchedTopic['action_route'] ?? '#',
                'source'       => 'local_playbook'
            ]);
        }

        // Default friendly fallback
        $fallback = "Hi! I'm **Carmie**, your Carmel-linx academic companion and guide! 🌸\n\n"
                  . "I can provide precise, syllabus-verified answers categorized by curriculum revision:\n\n"
                  . "📘 **Revision 2021 Workspaces:**\n"
                  . "• **Major Project (2021)**: 75 CIA (40% diary + 40% review + 20% attendance) + 50 ESE (2 examiners, 8 rubrics) = 125M Total & Group Breakdown reports\n"
                  . "• **Seminar (2021)**: 75 CIA Only, two-faculty evaluation (guide + committee)\n"
                  . "• **Drawing (2021)**: Practical/Lab criteria, drawing plate rubrics & continuous CIA\n"
                  . "• **Theory & Lab (2021)**: SITTTR lesson planner, 75M formative/summative\n\n"
                  . "📙 **Revision 2026 Workspaces:**\n"
                  . "• **Theory (2026)**: 40 CIE (Table 2.1 Attendance 5M + Table 2.2 Self-Learning 15M + Series 20M) + 60 ESE\n"
                  . "• **CO-PO Matrix**: Interactive PO1–PO11 mapping with continuous live autosave\n"
                  . "• **Practicum (2026)**: 90-Hour combined Theory + Practical workspace\n\n"
                  . "📊 **Universal Attainment & Surveys:**\n"
                  . "• 80% Direct + 20% Indirect Online Student Exit Survey across all 5 virtual classrooms.\n\n"
                  . "Click any revision tab above or type your question!\n\n"
                  . "📖 *Reference: [Carmel-Linx Master User Manual](/docs/carmel_linx_user_manual.html)*";

        $this->playbookService->logUserQuestion($query, $normalizedQuery, $context, null, $fallback, 'fallback');

        return response()->json([
            'status'       => 'SUCCESS',
            'reply'        => $fallback,
            'source'       => 'local_playbook'
        ]);
    }

    /**
     * Query Google Gemini Flash with strict ground-truth system context.
     */
    protected function queryGemini(string $query, ?array $matchedTopic, array $context, string $apiKey): ?string
    {
        $playbookSnippet = "";
        if ($matchedTopic) {
            $playbookSnippet = "OFFICIAL CARMEL-LINX PLAYBOOK TOPIC FOR THIS TASK:\n"
                             . "Title: " . $matchedTopic['title'] . "\n"
                             . "Manual Reference: " . ($matchedTopic['manual_reference'] ?? 'User Manual') . "\n"
                             . "Summary: " . $matchedTopic['summary'] . "\n"
                             . "Steps:\n" . implode("\n", $matchedTopic['steps']) . "\n";
        }

        $systemPrompt = "You are Carmie, a friendly, smart, cheerful female academic mentor for Carmel Polytechnic College's portal (Carmel-linx). You are a real human-like college support guide.
CRITICAL SYLLABUS ISOLATION & CATEGORIZATION RULES:
1. ALWAYS explicitly categorize your answer at the top with a clear badge:
   • For Revision 2021 topics, start with: ### 📘 [Revision 2021 - SBTE Keralam]
   • For Revision 2026 topics, start with: ### 📙 [Revision 2026 - Outcome-Based Curriculum]
   • If the user does not specify the revision or if it applies to both, clearly provide distinct sections for **Revision 2021** and **Revision 2026**.

2. MANDATORY USER MANUAL CITATIONS:
   • Every answer MUST conclude with a formal citation:
     `📖 **Reference:** User Manual [Chapter Number], Section [Section Number] ([Section Title])`
     `🔗 [Open Carmel-Linx User Manual](/docs/carmel_linx_user_manual.html)`

3. CRITICAL HOD ROLE GROUND TRUTH (DO NOT HALLUCINATE):
   • When an HOD asks how to CREATE, ADD, or CONFIGURE a new admission batch/class:
     - Direct the HOD to the HOD Dashboard (`/dashboard/hod`) under **Batch Management** -> click **`Create Batch`** (Chapter 2, Section 2.3).
     - NEVER tell an HOD to go to 'My Batches' to create a batch! 'My Batches' (Chapter 2, Section 2.8) is strictly and exclusively for when the HOD teaches a subject as an instructional faculty member to view attendance or syllabus logs!
     - Always clarify: 'To create a new batch, use Batch Management, NOT My Batches!'

4. GROUND TRUTH FOR REVISION 2021:
   • Major Project (2021): Total 125 Marks = 75 CIA + 50 ESE.
     - CIA (75M): 40% Formative (30M Activity Diary) + 40% Summative (30M Dept Project Review Committee) + 20% Attendance (15M SBTE slabs). Min 30.0 (40%) to pass CIA.
     - ESE (50M): Internal joint evaluation by TWO examiners: 1 Internal Examiner and 1 External Examiner. Assessed across 8 statutory rubrics (Prototype 10M, Modern Tools 5M, Presentation 7.5M, Innovativeness 2.5M, Viva 7.5M, Individual Contribution 7.5M, Group Activity 5M, Report 5M). Min 20.0 (40%) to pass ESE. Min 50.0/125 overall.
     - Reports Hub: Group-Wise Breakdown (single-page separate filing), CIA Register (75M), ESE 8-Rubric Score Sheet (50M), SBTE Final Mark Entry Statement (125M), Master Broad Register (125M), Exit Survey & Attainment Statement. (Chapter 4, Section 4.4)
   • Seminar (2021): Total 75 Marks CIA Only (no university ESE). Evaluated jointly by two faculties (Seminar Guide + Review Committee evaluator). Consolidated report with attendance & CIA for SBTE. (Chapter 4, Section 4.5)
   • Drawing (2021): Governed under Practical / Laboratory subject criteria in SBTE (continuous sheet assessments, hall tests, attendance). (Chapter 4, Section 4.6)
   • Theory (2021): 75 CIA + 75 ESE. SITTTR lesson planner, formative assignments (15M). (Chapter 4, Section 4.2)
   • Practical/Lab (2021): 75 CIA + 75 ESE. 37.5M formative across 5 rubrics + 15M lab test + attendance. (Chapter 4, Section 4.3)

5. GROUND TRUTH FOR REVISION 2026:
   • Theory (2026): 40 CIE + 60 ESE (Total 100). Table 2.1 Attendance (5M), Table 2.2 Self-Learning (15M), Series Exams (20M). CO-PO Matrix has editable inputs with live continuous autosave. (Chapter 4, Section 4.8)
   • Practicum (2026): 90 Hours combined (45H Theory + 45H Practical).

6. GROUND TRUTH FOR SURVEYS & ATTAINMENT:
   • Active in ALL 5 virtual classrooms (Theory, Lab, Seminar, Project, Drawing).
   • Overall CO Attainment = (80% Direct Attainment from marks) + (20% Indirect Attainment from Student Online Exit Surveys).
   • Tutors or HODs launch the online exit survey on the portal with 1 click; students submit ratings; system computes program attainment. (Chapter 4, Section 4.7)

7. Never invent university-style 'Part A/B/C' patterns. Follow Carmel-Linx button names, tabs, and workflows strictly.
User context: Role: {$context['user_role']}, Current Page: {$context['current_url']}.

{$playbookSnippet}";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";
        
        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\nUser Question: " . $query]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'maxOutputTokens' => 800
            ]
        ];

        $response = Http::timeout(8)->post($url, $payload);
        if ($response->successful()) {
            return $response->json('candidates.0.content.parts.0.text');
        }

        return null;
    }

    /**
     * Get starter suggestions based on context and category.
     */
    public function getSuggestions(Request $request)
    {
        $url = $request->input('current_url', '');
        $role = Session::get('userRole', $request->input('user_role', 'Staff'));
        $category = $request->input('category', 'all');
        
        $suggestions = $this->playbookService->getStarterSuggestions($url, $role, $category);
        
        return response()->json([
            'status' => 'SUCCESS',
            'suggestions' => $suggestions
        ]);
    }
}
