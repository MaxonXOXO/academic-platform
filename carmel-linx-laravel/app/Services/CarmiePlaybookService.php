<?php

namespace App\Services;

class CarmiePlaybookService
{
    protected ?array $playbook = null;

    /**
     * Load playbook data from JSON file.
     */
    public function getPlaybook(): array
    {
        if ($this->playbook !== null) {
            return $this->playbook;
        }

        $path = storage_path('app/carmie_playbook.json');
        $fallbackPath = database_path('data/carmie_playbook.json');

        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            $this->playbook = is_array($data) ? $data : ['topics' => []];
        } elseif (file_exists($fallbackPath)) {
            $data = json_decode(file_get_contents($fallbackPath), true);
            $this->playbook = is_array($data) ? $data : ['topics' => []];
        } else {
            $this->playbook = ['topics' => []];
        }

        return $this->playbook;
    }

    /**
     * Intelligent normalization for user queries (corrects typos and colloquialisms).
     */
    public function normalizeQuery(string $query): string
    {
        $q = strtolower(trim($query));
        $typoMap = [
            '/\bcrate\b/' => 'create',
            '/\bcreat\b/' => 'create',
            '/\bbtach\b/' => 'batch',
            '/\bbtaches\b/' => 'batches',
            '/\bassesment\b/' => 'assessment',
            '/\bassement\b/' => 'assessment',
            '/\basess\b/' => 'assess',
            '/\bcrieteria\b/' => 'criteria',
            '/\bexpernal\b/' => 'external',
            '/\bgruop\b/' => 'group',
            '/\bgruops\b/' => 'groups',
            '/\battendnace\b/' => 'attendance',
            '/\battendence\b/' => 'attendance',
            '/\bdreawing\b/' => 'drawing',
            '/\bevalution\b/' => 'evaluation',
            '/\bevaluateing\b/' => 'evaluating',
            '/\brvision\b/' => 'revision',
            '/\brevison\b/' => 'revision',
            '/\btryti\b/' => 'try to',
            '/\bseperate\b/' => 'separate',
            '/\bsemster\b/' => 'semester',
            '/\bmemr\b/' => 'member',
            '/\bdetalis\b/' => 'details',
            '/\bsuevsy\b/' => 'survey',
            '/\bsurvay\b/' => 'survey'
        ];
        return preg_replace(array_keys($typoMap), array_values($typoMap), $q);
    }

    /**
     * Search the verified playbook for the best matching topic.
     */
    public function search(string $query, array $context = []): ?array
    {
        $data = $this->getPlaybook();
        $topics = $data['topics'] ?? [];
        if (empty($topics)) {
            return null;
        }

        $queryLower = strtolower(trim($query));
        $normQuery = $this->normalizeQuery($query);
        
        // Clean and tokenize query into words (from both original and normalized)
        $wordsOriginal = preg_split('/[\s,\.\?\!\:\;\-]+/', $queryLower, -1, PREG_SPLIT_NO_EMPTY);
        $wordsNorm = preg_split('/[\s,\.\?\!\:\;\-]+/', $normQuery, -1, PREG_SPLIT_NO_EMPTY);
        $allWords = array_unique(array_merge($wordsOriginal, $wordsNorm));

        $stopWords = ['is', 'in', 'the', 'how', 'to', 'do', 'can', 'i', 'me', 'a', 'an', 'and', 'or', 'for', 'of', 'on', 'at', 'this', 'that', 'with', 'after', 'from'];
        $words = array_filter($allWords, fn($w) => strlen($w) > 1 && !in_array($w, $stopWords));

        $currentUrl = strtolower($context['current_url'] ?? '');
        $currentRole = strtolower($context['user_role'] ?? '');
        $subjectCode = strtolower($context['subject_code'] ?? '');

        // Special handling for "where am i" / "what can i do here"
        if (str_contains($normQuery, 'where am i') || str_contains($normQuery, 'what can i do') || str_contains($normQuery, 'help here')) {
            return $this->getPageContextGuide($currentUrl, $currentRole);
        }

        $bestScore = 0;
        $bestTopic = null;

        foreach ($topics as $topic) {
            $score = 0;
            $titleLower = strtolower($topic['title']);
            $summaryLower = strtolower($topic['summary']);
            $keywords = array_map('strtolower', $topic['keywords'] ?? []);
            $revisions = $topic['revisions'] ?? [];

            // Exact phrase match in title or summary
            if (str_contains($titleLower, $queryLower) || str_contains($titleLower, $normQuery)) {
                $score += 55;
            }
            if (str_contains($summaryLower, $queryLower) || str_contains($summaryLower, $normQuery)) {
                $score += 25;
            }

            // Keyword hits
            foreach ($keywords as $kw) {
                if (str_contains($queryLower, $kw) || str_contains($normQuery, $kw)) {
                    $score += 22;
                }
            }

            // Word-level hits
            foreach ($words as $word) {
                if (str_contains($titleLower, $word)) {
                    $score += 10;
                }
                foreach ($keywords as $kw) {
                    if ($kw === $word || str_contains($kw, $word)) {
                        $score += 8;
                    }
                }
                if (str_contains($summaryLower, $word)) {
                    $score += 3;
                }
            }

            // Context boosters
            // Subject code match (e.g. 5041)
            if ($subjectCode && (str_contains($titleLower, $subjectCode) || in_array($subjectCode, $keywords))) {
                $score += 25;
            }

            // Revision match (2021 vs 2026)
            if (str_contains($normQuery, '2026') && in_array('2026', $revisions)) {
                $score += 30;
            }
            if (str_contains($normQuery, '2021') && in_array('2021', $revisions)) {
                $score += 30;
            }
            if (str_contains($currentUrl, 'r26') && in_array('2026', $revisions)) {
                $score += 15;
            }

            // Specific high-intent terms
            if (str_contains($normQuery, 'copo') || str_contains($normQuery, 'co po') || str_contains($normQuery, 'matrix')) {
                if ($topic['id'] === 'rev2026_theory_copo_matrix') {
                    $score += 40;
                }
            }
            if (str_contains($normQuery, 'assignment') || str_contains($normQuery, 'formative')) {
                if ($topic['id'] === 'rev2021_formative_assessment') {
                    $score += 40;
                }
            }
            if (str_contains($normQuery, 'self learning') || str_contains($normQuery, 'table 2.2')) {
                if ($topic['id'] === 'rev2026_self_learning_config') {
                    $score += 40;
                }
            }
            if (str_contains($normQuery, 'attendance') || str_contains($normQuery, 'subject log') || str_contains($normQuery, 'grid view') || str_contains($normQuery, 'easy access') || str_contains($normQuery, 'attendance log')) {
                if ($topic['id'] === 'attendance_logging' && !str_contains($normQuery, 'teams') && !str_contains($normQuery, 'condonation')) {
                    $score += 40;
                }
            }
            if (str_contains($normQuery, 'sitttr') || str_contains($normQuery, '2021 theory') || str_contains($normQuery, 'r2021 theory') || str_contains($normQuery, 'r-2021 theory')) {
                if ($topic['id'] === 'r21_virtual_theory_classroom_overview') {
                    $score += 50;
                }
            }
            if (str_contains($normQuery, 'sync dates') || str_contains($normQuery, 'log data') || str_contains($normQuery, '62 sessions')) {
                if ($topic['id'] === 'r21_theory_lesson_planner') {
                    $score += 50;
                }
            }
            if (str_contains($normQuery, 'amber') || str_contains($normQuery, 'hardcopy') || str_contains($normQuery, 'lock assignment') || str_contains($normQuery, 'assignment lock')) {
                if ($topic['id'] === 'rev2021_formative_assessment') {
                    $score += 50;
                }
            }
            if (str_contains($normQuery, 'scheme of valuation') || str_contains($normQuery, 'answer key') || str_contains($normQuery, 'mcq test')) {
                if ($topic['id'] === 'r21_theory_summative_and_mcq') {
                    $score += 50;
                }
            }
            if (str_contains($normQuery, 'virtual lab') || str_contains($normQuery, 'r2021 lab') || str_contains($normQuery, 'setup experiments') || str_contains($normQuery, 'shared workload')) {
                if ($topic['id'] === 'r21_virtual_lab_classroom_overview') {
                    $score += 50;
                }
            }
            if (str_contains($normQuery, '37.5') || str_contains($normQuery, 'rough record') || str_contains($normQuery, 'fair record') || str_contains($normQuery, 'open ended') || str_contains($normQuery, '5 components')) {
                if ($topic['id'] === 'r21_lab_formative_evaluation_rubrics') {
                    $score += 50;
                }
            }
            if (str_contains($normQuery, 'lab test') || str_contains($normQuery, 'setup lab test') || str_contains($normQuery, '75 marks') || str_contains($normQuery, 'cia 75')) {
                if ($topic['id'] === 'r21_lab_summative_test_and_cia') {
                    $score += 50;
                }
            }
            if (str_contains($normQuery, 'teams') || str_contains($normQuery, 'teams attendance') || str_contains($normQuery, 'teams pdf')) {
                if ($topic['id'] === 'teams_attendance_pdf_upload') {
                    $score += 50;
                }
            }

            // CRITICAL DISAMBIGUATION: HOD Batch Creation vs My Batches
            if ((str_contains($normQuery, 'create') || str_contains($normQuery, 'add') || str_contains($normQuery, 'new') || str_contains($normQuery, 'setup') || str_contains($normQuery, 'make')) && str_contains($normQuery, 'batch')) {
                if (str_contains($normQuery, 'hod') || $currentRole === 'hod' || str_contains($currentUrl, 'hod')) {
                    if ($topic['id'] === 'hod_create_batch_and_tutor_mentor') {
                        $score += 100;
                    }
                    if ($topic['id'] === 'hod_my_batches_lecturer_role') {
                        $score -= 60; // Never route batch creation to 'My Batches'
                    }
                }
            }

            // Penalize 'My Batches' if user didn't specifically ask about teaching or my batches
            if ($topic['id'] === 'hod_my_batches_lecturer_role') {
                if (!str_contains($normQuery, 'my batches') && !str_contains($normQuery, 'teaching role') && !str_contains($normQuery, 'class i teach')) {
                    $score -= 40;
                }
            }

            // High-intent: Revision 2021 Major Project
            if (str_contains($normQuery, 'project') || str_contains($normQuery, 'major project') || str_contains($normQuery, 'dossier') || str_contains($normQuery, 'group breakdown') || str_contains($normQuery, 'ese 50') || str_contains($normQuery, '75 cia') || str_contains($normQuery, '125 marks') || str_contains($normQuery, '8 rubrics') || str_contains($normQuery, 'external examiner')) {
                if ($topic['id'] === 'r21_major_project_workflow') {
                    $score += 65;
                }
            }

            // High-intent: Revision 2021 Seminar
            if (str_contains($normQuery, 'seminar') || str_contains($normQuery, 'seminar 75') || str_contains($normQuery, 'seminar guide')) {
                if ($topic['id'] === 'r21_seminar_workflow') {
                    $score += 65;
                }
            }

            // High-intent: Revision 2021 Drawing
            if (str_contains($normQuery, 'drawing') || str_contains($normQuery, 'graphics') || str_contains($normQuery, 'drawing sheet') || str_contains($normQuery, 'drawing plate') || str_contains($normQuery, 'drafting')) {
                if ($topic['id'] === 'r21_drawing_classroom_workflow') {
                    $score += 65;
                }
            }

            // High-intent: Universal Exit Survey & Attainment
            if (str_contains($normQuery, 'exit survey') || str_contains($normQuery, 'program attainment') || str_contains($normQuery, 'course exit survey') || str_contains($normQuery, 'program exit survey') || str_contains($normQuery, 'indirect attainment') || str_contains($normQuery, '80 20') || str_contains($normQuery, '80/20') || str_contains($normQuery, 'run survey') || str_contains($normQuery, 'survey online') || str_contains($normQuery, 'attainment')) {
                if ($topic['id'] === 'universal_exit_survey_and_attainment') {
                    $score += 60;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestTopic = $topic;
            }
        }

        // Return best topic if score threshold is met
        return ($bestScore >= 12) ? $bestTopic : null;
    }

    /**
     * Keep user questions as a model / query memory log for continuous learning.
     */
    public function logUserQuestion(string $rawQuery, string $normalizedQuery, array $context, ?array $matchedTopic, string $reply, string $source): void
    {
        try {
            $logPath = storage_path('app/carmie_query_model.json');
            $existing = [];
            if (file_exists($logPath)) {
                $content = @file_get_contents($logPath);
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $existing = $decoded;
                }
            }

            $newEntry = [
                'timestamp'        => date('c'),
                'raw_query'        => $rawQuery,
                'normalized_query' => $normalizedQuery,
                'user_role'        => $context['user_role'] ?? 'Staff',
                'current_url'      => $context['current_url'] ?? '',
                'subject_code'     => $context['subject_code'] ?? '',
                'matched_topic_id' => $matchedTopic['id'] ?? null,
                'matched_title'    => $matchedTopic['title'] ?? null,
                'manual_reference' => $matchedTopic['manual_reference'] ?? null,
                'source'           => $source,
                'reply_snippet'    => substr(strip_tags($reply), 0, 180)
            ];

            array_unshift($existing, $newEntry);
            // Retain last 300 interactions as evolving query model
            $existing = array_slice($existing, 0, 300);

            @file_put_contents($logPath, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } catch (\Throwable $e) {
            // Non-blocking log
        }
    }

    /**
     * Provide a contextual "What can I do here?" guide based on the active URL.
     */
    public function getPageContextGuide(string $url, string $role): array
    {
        if (str_contains($url, '/r26/classroom/theory')) {
            return [
                'id' => 'context_r26_theory',
                'title' => 'Revision 2026 Theory Classroom Workspace',
                'summary' => 'You are currently inside the official Revision 2026 Theory Virtual Classroom.',
                'steps' => [
                    "**1. Syllabus & Outline:** View official Bloom's taxonomy CO1–CO4 statements and 4 module breakdowns.",
                    "**2. CO-PO Matrix:** Map Course Outcomes to PO1–PO11 with real-time continuous autosave and column averages.",
                    "**3. Lesson Planner:** Plan 60 instructional lecture (L) and tutorial (T) periods with calendar date pickers.",
                    "**4. CIA Marksheet:** Manage Table 2.1 continuous attendance marks (5M), Table 2.2 Self-Learning (15M), and Series Exams (20M).",
                    "**5. Series Examinations:** Configure Series 1 & 2 exams, upload question papers, and enter 50M scores.",
                    "**6. Attainment Reports:** Review combined Direct (80%) + Indirect (20%) NBA Criterion 3 attainment sheet."
                ],
                'action_label' => 'Explore Theory Classroom',
                'action_route' => $url
            ];
        }

        if (str_contains($url, '/classroom/project')) {
            return [
                'id' => 'context_r21_project',
                'title' => 'Revision 2021 Major Project Workspace',
                'summary' => 'You are inside the SBTE Revision 2021 Major Project Virtual Classroom (125 Marks).',
                'steps' => [
                    "**1. Groups & Guides:** Create project groups (3–5 members), assign approved project titles, and allocate internal faculty guides.",
                    "**2. CIA Evaluation (75M):** 40% Formative (30M activity diary), 40% Summative (30M dept review), and 20% Attendance (15M). Use `[Group Common CIA (60M)]` or `[Evaluate CIA]`.",
                    "**3. ESE Evaluation (50M):** Internal joint evaluation by 1 Internal Examiner and 1 External Examiner across 8 statutory rubrics.",
                    "**4. Reports Hub:** Print Group-Wise Breakdown (single-page separate filing), CIA Register (75M), ESE 8-Rubric Score Sheet (50M), and SBTE Mark Entry Statement (125M)."
                ],
                'action_label' => 'Explore Major Project',
                'action_route' => $url
            ];
        }

        if (str_contains($url, '/classroom/seminar')) {
            return [
                'id' => 'context_r21_seminar',
                'title' => 'Revision 2021 Seminar Workspace',
                'summary' => 'You are inside the Revision 2021 Seminar Virtual Classroom (75 Marks CIA only).',
                'steps' => [
                    "**1. Guide & Presentation Slots:** Allocate seminar guides and schedule presentation slots.",
                    "**2. Two-Faculty Evaluation:** Joint assessment by the Seminar Guide and Review Committee evaluator across 6 rubrics.",
                    "**3. Responsive Cards:** Fast slider evaluation with live SBTE grade calculations.",
                    "**4. Consolidated Reports:** Print the official Seminar Evaluation Report with attendance and CIA for SBTE portal."
                ],
                'action_label' => 'Explore Seminar Classroom',
                'action_route' => $url
            ];
        }

        if (str_contains($url, '/classroom/drawing') || str_contains($url, 'drawing')) {
            return [
                'id' => 'context_r21_drawing',
                'title' => 'Revision 2021 Virtual Drawing Hall',
                'summary' => 'You are inside the Drawing Classroom governed under SBTE Practical / Lab criteria.',
                'steps' => [
                    "**1. Sheet Assessment:** Continuous assessment of assigned drawing plates across 5 drafting rubrics.",
                    "**2. Hall Tests:** Conduct mid-term drawing exams under hall conditions for summative scores.",
                    "**3. Attendance Integration:** Sync continuous drawing attendance directly from attendance logs.",
                    "**4. Drawing Broadsheets:** Generate Drawing CIA Registers and SBTE marksheets."
                ],
                'action_label' => 'Explore Drawing Hall',
                'action_route' => $url
            ];
        }

        if (str_contains($url, '/r26/classroom/practicum') || str_contains($url, 'practicum')) {
            return [
                'id' => 'context_r26_practicum',
                'title' => 'Revision 2026 Practicum Classroom',
                'summary' => 'You are inside the combined 90-Hour Practicum (Theory + Laboratory) workspace.',
                'steps' => [
                    "**1. Theory Modules:** 45 Hours lecture schedule and instructional topics.",
                    "**2. Lab Experiments:** 45 Hours hands-on laboratory rubrics, series tests, and continuous evaluation.",
                    "**3. CO-PO & PSO Matrix:** Map COs across PO1–PO11 and PSO1–PSO3.",
                    "**4. Day-to-Day Practical Log:** Enter continuous rubric observations."
                ],
                'action_label' => 'Explore Practicum Workspace',
                'action_route' => $url
            ];
        }

        if (str_contains($url, 'attendance')) {
            return [
                'id' => 'context_attendance',
                'title' => 'Daily Attendance & Subject Log Workspace',
                'summary' => 'You are recording hourly student attendance and lesson delivery logs.',
                'steps' => [
                    "Select the batch, subject, date, and hour period.",
                    "Toggle student states: **Present (Green)**, **Absent (Red)**, or **Late (Amber)**.",
                    "Enter the topic taught in the Subject Log description box.",
                    "Submit to update semester percentages and Table 2.1 marks."
                ],
                'action_label' => 'Log Attendance',
                'action_route' => $url
            ];
        }

        if (str_contains($url, 'hod')) {
            return [
                'id' => 'context_hod',
                'title' => 'HOD Department Console',
                'summary' => 'Welcome to the Head of Department administrative command console.',
                'steps' => [
                    "Monitor weekly teaching workload for all department lecturers.",
                    "Audit and digitally sign off submitted faculty Course Files.",
                    "View the department-wide consolidated master timetable.",
                    "Manage slow-learner identification and approve Remedial coaching batches."
                ],
                'action_label' => 'Review Department Console',
                'action_route' => $url
            ];
        }

        // Default Lecturer Dashboard
        return [
            'id' => 'context_lecturer_dashboard',
            'title' => 'Lecturer Command Center',
            'summary' => 'You are on the Lecturer Dashboard managing your assigned academic subjects.',
            'steps' => [
                "**1. Virtual Classrooms:** Click on any subject card to open its full classroom workspace.",
                "**2. Formative Assessment:** Generate syllabus-mapped assignments with Cognitive levels.",
                "**3. Attendance Log:** Enter hourly student presence and subject topics.",
                "**4. Course Files:** Complete NBA documentation and checklist items.",
                "**5. Student Mentoring:** Review assigned mentee profiles and counsel records."
            ],
            'action_label' => 'Explore Dashboard',
            'action_route' => '/lecturer/dashboard'
        ];
    }

    /**
     * Get starter quick-prompt suggestion chips for the chat drawer.
     */
    public function getStarterSuggestions(string $url, string $role, string $category = 'all'): array
    {
        if ($category === '2021') {
            return [
                ['label' => '🚀 Project 2021 (75 CIA & 50 ESE)', 'query' => 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?'],
                ['label' => '🎤 Seminar 2021 (75 CIA Only)', 'query' => 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?'],
                ['label' => '📐 Drawing 2021 (Lab Criteria)', 'query' => 'How does Revision 2021 Drawing class practical evaluation work?'],
                ['label' => '📑 Print Group-Wise Breakdown', 'query' => 'How do I print the Group-Wise Breakdown for separate filing in Major Project?'],
                ['label' => '🔬 Lab 2021 (37.5 Formative + Tests)', 'query' => 'Explain Revision 2021 Lab 75 CIA split-up with rough record and fair record']
            ];
        }

        if ($category === '2026') {
            return [
                ['label' => '🎯 CO-PO Autosave in Rev 2026', 'query' => 'How does CO-PO matrix autosave work in Revision 2026 theory?'],
                ['label' => '📝 Theory 40 CIE Breakdown', 'query' => 'Explain the Revision 2026 theory 40 CIE marks breakdown'],
                ['label' => '📚 Table 2.2 Self-Learning', 'query' => 'How to configure Table 2.2 self-learning marks in Rev 2026?'],
                ['label' => '🔬 Practicum 90-Hour Workspace', 'query' => 'How does Revision 2026 Practicum 90-Hour combined workspace operate?']
            ];
        }

        if ($category === 'attainment') {
            return [
                ['label' => '📊 Run Online Exit Survey & Attainment', 'query' => 'How do HOD and Tutor run online exit surveys and generate program attainment?'],
                ['label' => '📈 80% Direct + 20% Indirect Formula', 'query' => 'How is the 80% Direct + 20% Indirect Attainment formula calculated in Carmel-Linx?'],
                ['label' => '📁 NBA Criterion 3 Compliance Dossier', 'query' => 'How to export the NBA Criterion 3 attainment reports for department audit?']
            ];
        }

        // Default 'all' - context aware
        if (str_contains($url, 'project')) {
            return [
                ['label' => '🚀 Project 75 CIA & 50 ESE Split-up', 'query' => 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?'],
                ['label' => '📑 Print Group-Wise Breakdown', 'query' => 'How do I print the Group-Wise Breakdown for separate filing in Major Project?'],
                ['label' => '👥 Group Common CIA & ESE Scoring', 'query' => 'How to use 1-click group common scoring in Major Project 2021?'],
                ['label' => '💡 What can I do on this page?', 'query' => 'Where am I and what can I do on this page?']
            ];
        }

        if (str_contains($url, 'seminar')) {
            return [
                ['label' => '🎤 Seminar 75 CIA Only Criteria', 'query' => 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?'],
                ['label' => '👥 Two-Faculty Evaluation Panel', 'query' => 'How do the guide and review committee evaluate seminar jointly?'],
                ['label' => '📑 Consolidated Seminar Report', 'query' => 'How to generate the consolidated seminar evaluation report for SBTE?'],
                ['label' => '💡 What can I do on this page?', 'query' => 'Where am I and what can I do on this page?']
            ];
        }

        if (str_contains($url, 'r26')) {
            return [
                ['label' => '🎯 How to edit CO-PO Matrix & Autosave?', 'query' => 'How does CO-PO matrix autosave work in Revision 2026 theory?'],
                ['label' => '📝 Theory 40 CIE breakdown', 'query' => 'Explain the Revision 2026 theory 40 CIE marks breakdown'],
                ['label' => '📚 Table 2.2 Self-Learning', 'query' => 'How to configure Table 2.2 self-learning marks?'],
                ['label' => '💡 What can I do on this page?', 'query' => 'Where am I and what can I do on this page?']
            ];
        }

        return [
            ['label' => '🚀 Project 2021 (75 CIA + 50 ESE)', 'query' => 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?'],
            ['label' => '🎤 Seminar 2021 (75 CIA Only)', 'query' => 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?'],
            ['label' => '📐 Drawing 2021 (Lab Criteria)', 'query' => 'How does Revision 2021 Drawing class practical evaluation work?'],
            ['label' => '📊 Online Exit Survey & Attainment', 'query' => 'How do HOD and Tutor run online exit surveys and generate program attainment?'],
            ['label' => '🎯 CO-PO Autosave in Rev 2026', 'query' => 'How does CO-PO articulation matrix autosave work in Revision 2026?'],
            ['label' => '📅 Daily Attendance & Subject Log', 'query' => 'How do I submit hourly attendance and subject log?'],
            ['label' => '💡 What can I do on this page?', 'query' => 'What can I do on this page?']
        ];
    }
}
