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

            $reply = "### 🎓 " . $matchedTopic['title'] . "\n\n"
                   . $matchedTopic['summary'] . "\n\n"
                   . "**Step-by-step instructions:**\n\n"
                   . $formattedSteps;

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
                  . "I can assist you with:\n"
                  . "• **Formative Assessment & Assignments** (Rev 2021 & 2026)\n"
                  . "• **CO-PO Articulation Matrix** (PO1–PO11 mapping & live autosave)\n"
                  . "• **Table 2.1 Attendance** and **Table 2.2 Self-Learning**\n"
                  . "• **Series Examinations & Marks Scaling**\n"
                  . "• **NBA Criterion 3 Attainment Reports**\n"
                  . "• **Course File Preparation & HOD Sign-off**\n\n"
                  . "Try asking: *\"How do I set an assignment question paper?\"* or click one of the quick suggestions below!";

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
                             . "Summary: " . $matchedTopic['summary'] . "\n"
                             . "Steps:\n" . implode("\n", $matchedTopic['steps']) . "\n";
        }

        $systemPrompt = "You are Carmie, a friendly, smart, and cheerful female academic mentor and support guide for Carmel Polytechnic College's portal (Carmel-linx). You are a real human-like college support guide (not a bot or robot).
CRITICAL RULES:
1. Always base your answer strictly on Carmel-linx workflows. Never invent university patterns like 'Part A, Part B, Part C' questions unless specified in the playbook.
2. In Carmel-linx, setting assignments is done under 'Formative Assessment' -> 'Generate Questions' (AI Generate or Manual Entry) with Bloom's Cognitive Level and CO tags.
3. In Revision 2026 Theory, the CO-PO matrix is under 'Syllabus & Course Outline' with editable inputs and continuous autosave.
4. If a playbook snippet is provided below, strictly follow its step names and button labels.
5. Format your answer with clear markdown bullet points or numbered steps, and a warm, cheerful, supportive tone.
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
     * Get starter suggestions based on context.
     */
    public function getSuggestions(Request $request)
    {
        $url = $request->input('current_url', '');
        $role = Session::get('userRole', $request->input('user_role', 'Staff'));
        
        $suggestions = $this->playbookService->getStarterSuggestions($url, $role);
        
        return response()->json([
            'status' => 'SUCCESS',
            'suggestions' => $suggestions
        ]);
    }
}
