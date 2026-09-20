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
        
        // Clean and tokenize query into words
        $words = preg_split('/[\s,\.\?\!\:\;\-]+/', $queryLower, -1, PREG_SPLIT_NO_EMPTY);
        $words = array_filter($words, fn($w) => strlen($w) > 1 && !in_array($w, ['is', 'in', 'the', 'how', 'to', 'do', 'can', 'i', 'a', 'an', 'and', 'or', 'for', 'of', 'on', 'at', 'this', 'that', 'with']));

        $currentUrl = strtolower($context['current_url'] ?? '');
        $currentRole = strtolower($context['user_role'] ?? '');
        $subjectCode = strtolower($context['subject_code'] ?? '');

        // Special handling for "where am i" / "what can i do here"
        if (str_contains($queryLower, 'where am i') || str_contains($queryLower, 'what can i do') || str_contains($queryLower, 'help here')) {
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
            if (str_contains($titleLower, $queryLower)) {
                $score += 50;
            }
            if (str_contains($summaryLower, $queryLower)) {
                $score += 25;
            }

            // Keyword hits
            foreach ($keywords as $kw) {
                if (str_contains($queryLower, $kw)) {
                    $score += 20;
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
            if (str_contains($queryLower, '2026') && in_array('2026', $revisions)) {
                $score += 30;
            }
            if (str_contains($queryLower, '2021') && in_array('2021', $revisions)) {
                $score += 30;
            }
            if (str_contains($currentUrl, 'r26') && in_array('2026', $revisions)) {
                $score += 15;
            }

            // Specific high-intent terms
            if (str_contains($queryLower, 'copo') || str_contains($queryLower, 'co po') || str_contains($queryLower, 'matrix')) {
                if ($topic['id'] === 'rev2026_theory_copo_matrix') {
                    $score += 40;
                }
            }
            if (str_contains($queryLower, 'assignment') || str_contains($queryLower, 'formative')) {
                if ($topic['id'] === 'rev2021_formative_assessment') {
                    $score += 40;
                }
            }
            if (str_contains($queryLower, 'self learning') || str_contains($queryLower, 'table 2.2')) {
                if ($topic['id'] === 'rev2026_self_learning_config') {
                    $score += 40;
                }
            }
            if (str_contains($queryLower, 'attendance') || str_contains($queryLower, 'subject log') || str_contains($queryLower, 'grid view') || str_contains($queryLower, 'easy access') || str_contains($queryLower, 'attendance log')) {
                if ($topic['id'] === 'attendance_logging' && !str_contains($queryLower, 'teams') && !str_contains($queryLower, 'condonation')) {
                    $score += 40;
                }
            }
            if (str_contains($queryLower, 'sitttr') || str_contains($queryLower, '2021 theory') || str_contains($queryLower, 'r2021 theory') || str_contains($queryLower, 'r-2021 theory')) {
                if ($topic['id'] === 'r21_virtual_theory_classroom_overview') {
                    $score += 50;
                }
            }
            if (str_contains($queryLower, 'sync dates') || str_contains($queryLower, 'log data') || str_contains($queryLower, '62 sessions')) {
                if ($topic['id'] === 'r21_theory_lesson_planner') {
                    $score += 50;
                }
            }
            if (str_contains($queryLower, 'amber') || str_contains($queryLower, 'hardcopy') || str_contains($queryLower, 'lock assignment') || str_contains($queryLower, 'assignment lock')) {
                if ($topic['id'] === 'rev2021_formative_assessment') {
                    $score += 50;
                }
            }
            if (str_contains($queryLower, 'scheme of valuation') || str_contains($queryLower, 'answer key') || str_contains($queryLower, 'mcq test')) {
                if ($topic['id'] === 'r21_theory_summative_and_mcq') {
                    $score += 50;
                }
            }
            if (str_contains($queryLower, 'virtual lab') || str_contains($queryLower, 'r2021 lab') || str_contains($queryLower, 'setup experiments') || str_contains($queryLower, 'shared workload')) {
                if ($topic['id'] === 'r21_virtual_lab_classroom_overview') {
                    $score += 50;
                }
            }
            if (str_contains($queryLower, '37.5') || str_contains($queryLower, 'rough record') || str_contains($queryLower, 'fair record') || str_contains($queryLower, 'open ended') || str_contains($queryLower, '5 components')) {
                if ($topic['id'] === 'r21_lab_formative_evaluation_rubrics') {
                    $score += 50;
                }
            }
            if (str_contains($queryLower, 'lab test') || str_contains($queryLower, 'setup lab test') || str_contains($queryLower, '75 marks') || str_contains($queryLower, 'cia 75')) {
                if ($topic['id'] === 'r21_lab_summative_test_and_cia') {
                    $score += 50;
                }
            }
            if (str_contains($queryLower, 'teams') || str_contains($queryLower, 'teams attendance') || str_contains($queryLower, 'teams pdf')) {
                if ($topic['id'] === 'teams_attendance_pdf_upload') {
                    $score += 50;
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
    public function getStarterSuggestions(string $url, string $role): array
    {
        if (str_contains($url, 'r26')) {
            return [
                ['label' => '🎯 How to edit CO-PO Matrix & Autosave?', 'query' => 'How does CO-PO matrix autosave work in Revision 2026 theory?'],
                ['label' => '📝 How does Theory 40 CIE breakdown work?', 'query' => 'Explain the Revision 2026 theory 40 CIE marks breakdown'],
                ['label' => '📚 How to configure Table 2.2 Self-Learning?', 'query' => 'How to configure Table 2.2 self-learning marks?'],
                ['label' => '💡 What can I do on this page?', 'query' => 'Where am I and what can I do on this page?']
            ];
        }

        return [
            ['label' => '📝 Set Assignment in Embedded Systems (5041)', 'query' => 'How can I set an assignment in 5041 embedded systems theory?'],
            ['label' => '🎯 How does CO-PO matrix autosave work?', 'query' => 'How does CO-PO articulation matrix autosave work in Revision 2026?'],
            ['label' => '📅 How to log daily attendance?', 'query' => 'How do I submit hourly attendance and subject log?'],
            ['label' => '📁 How to complete Course File checklist?', 'query' => 'How do I prepare the course file checklist for HOD approval?'],
            ['label' => '💡 What can I do on this page?', 'query' => 'What can I do on this page?']
        ];
    }
}
