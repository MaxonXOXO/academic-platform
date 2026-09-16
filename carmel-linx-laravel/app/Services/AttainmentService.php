<?php

namespace App\Services;

class AttainmentService
{
    /**
     * Official SBTE Kerala Polytechnic Grading Scale
     * S (90% and above): Outstanding — 10 Grade Points
     * A (80% to <90%): Excellent — 9 Grade Points
     * B (70% to <80%): Very Good — 8 Grade Points
     * C (60% to <70%): Good — 7 Grade Points
     * D (50% to <60%): Average — 6 Grade Points
     * E (40% to <50%): Pass — 5 Grade Points (40% is the minimum passing mark)
     * F (Below 40%): Fail — 0 Grade Points
     */
    public const SBTE_GRADE_SCALE = [
        'S' => ['points' => 10, 'min_pct' => 90.0, 'max_pct' => 100.0, 'midpoint' => 0.95, 'label' => 'Outstanding'],
        'A' => ['points' => 9,  'min_pct' => 80.0, 'max_pct' => 89.9,  'midpoint' => 0.85, 'label' => 'Excellent'],
        'B' => ['points' => 8,  'min_pct' => 70.0, 'max_pct' => 79.9,  'midpoint' => 0.75, 'label' => 'Very Good'],
        'C' => ['points' => 7,  'min_pct' => 60.0, 'max_pct' => 69.9,  'midpoint' => 0.65, 'label' => 'Good'],
        'D' => ['points' => 6,  'min_pct' => 50.0, 'max_pct' => 59.9,  'midpoint' => 0.55, 'label' => 'Average'],
        'E' => ['points' => 5,  'min_pct' => 40.0, 'max_pct' => 49.9,  'midpoint' => 0.45, 'label' => 'Pass'],
        'F' => ['points' => 0,  'min_pct' => 0.0,  'max_pct' => 39.9,  'midpoint' => 0.00, 'label' => 'Fail'],
        'FE' => ['points' => 0, 'min_pct' => 0.0,  'max_pct' => 0.0,   'midpoint' => 0.00, 'label' => 'Absent/Expelled'],
    ];

    /**
     * Get Grade Points for a given letter grade.
     */
    public static function getGradePoints(?string $grade): int
    {
        if (!$grade) return 0;
        $grade = strtoupper(trim($grade));
        return self::SBTE_GRADE_SCALE[$grade]['points'] ?? 0;
    }

    /**
     * Check if a student's ESE Grade meets or exceeds the configured threshold grade.
     */
    public static function isGradeMet(?string $studentGrade, string $thresholdGrade = 'D'): bool
    {
        if (!$studentGrade) return false;
        $studentGrade = strtoupper(trim($studentGrade));
        $thresholdGrade = strtoupper(trim($thresholdGrade));

        $studentPoints = self::getGradePoints($studentGrade);
        $thresholdPoints = self::getGradePoints($thresholdGrade);

        // Student must have at least 5 points (Pass / E) and meet or exceed the threshold points
        return $studentPoints >= 5 && $studentPoints >= $thresholdPoints;
    }

    /**
     * Determine SBTE letter grade from a numerical percentage score (0 - 100).
     */
    public static function percentageToGrade(float $pct): string
    {
        if ($pct >= 90.0) return 'S';
        if ($pct >= 80.0) return 'A';
        if ($pct >= 70.0) return 'B';
        if ($pct >= 60.0) return 'C';
        if ($pct >= 50.0) return 'D';
        if ($pct >= 40.0) return 'E';
        return 'F';
    }

    /**
     * Convert SBTE letter grade to estimated numeric marks given maximum marks.
     */
    public static function gradeToMarks(string $grade, float $maxMarks = 60.0): float
    {
        $grade = strtoupper(trim($grade));
        $ratio = self::SBTE_GRADE_SCALE[$grade]['midpoint'] ?? 0.45;
        return round($ratio * $maxMarks, 2);
    }

    /**
     * Calculate Batch Attainment Level (3, 2, 1, 0) from the percentage of students meeting threshold.
     */
    public static function calculateBatchLevel(float $metPercent, float $lvl3 = 65.0, float $lvl2 = 55.0, float $lvl1 = 45.0): int
    {
        if ($metPercent >= $lvl3) return 3;
        if ($metPercent >= $lvl2) return 2;
        if ($metPercent >= $lvl1) return 1;
        return 0;
    }

    /**
     * Get textual label for an attainment level.
     */
    public static function getLevelLabel(int $level): string
    {
        return match ($level) {
            3 => 'Level 3 (High)',
            2 => 'Level 2 (Moderate)',
            1 => 'Level 1 (Low)',
            default => 'Level 0 (Nil)'
        };
    }

    /**
     * Calculate direct course outcome attainment combining CIA & ESE as per NBA manual.
     * Standard polytechnic weights: 30% CIE + 70% ESE (or configurable).
     */
    public static function calculateDirectAttainment(float $ciaLevel, float $eseLevel, float $wInternal = 0.30, float $wExternal = 0.70): float
    {
        return round(($wInternal * $ciaLevel) + ($wExternal * $eseLevel), 2);
    }

    /**
     * Calculate overall course outcome attainment combining Direct & Indirect attainment as per NBA manual.
     * Standard weights: 80% Direct + 20% Indirect (or configurable).
     */
    public static function calculateOverallAttainment(float|int $directLevel, float|int $indirectLevel, float $wDirect = 0.80, float $wIndirect = 0.20): float
    {
        return round(($wDirect * (float)$directLevel) + ($wIndirect * (float)$indirectLevel), 2);
    }

    /**
     * Determine SBTE letter grade from numeric marks and maximum marks.
     */
    public static function convertMarksToGrade(float $marks, float $maxMarks = 75.0): string
    {
        if ($maxMarks <= 0) return 'F';
        $pct = ($marks / $maxMarks) * 100.0;
        return self::percentageToGrade($pct);
    }

    /**
     * Convert SBTE letter grade to estimated numeric marks given maximum marks.
     */
    public static function convertGradeToMarks(string $grade, float $maxMarks = 75.0): float
    {
        return self::gradeToMarks($grade, $maxMarks);
    }

    /**
     * Get official default assessment & attainment configuration per subject type and curriculum revision.
     */
    public static function getDefaultEseConfig(string $subjectType = 'Theory', string $revision = 'REV2021'): array
    {
        $type = strtolower(trim($subjectType));
        $isR26 = strtoupper(trim($revision)) === 'REV2026';

        if ($isR26) {
            return [
                'entry_mode' => 'grades',
                'max_marks' => 60,
                'cia_marks' => 60,
                'ese_threshold_grade' => 'D',
                'ese_threshold_percent' => 50.0,
                'cie_threshold_percent' => 50.0,
                'target_student_percent' => 70.0,
                'level3_percent' => 70.0,
                'level2_percent' => 60.0,
                'level1_percent' => 50.0,
                'w_cie' => 0.30,
                'w_ese' => 0.70,
            ];
        }

        // Revision 2021 Official Configurations (SBTE Kerala Rules & Regulations)
        if (str_contains($type, 'practical') || str_contains($type, 'lab')) {
            return [
                'entry_mode' => 'dual', // Supports both marks and grades
                'max_marks' => 50,      // ESE = 50 Marks (Institution Level Exam)
                'cia_marks' => 75,      // CIA = 75 Marks (Lab Work 37.5 + Tests 15 + Open Ended 7.5 + Att 15)
                'ese_threshold_grade' => 'D',
                'ese_threshold_percent' => 50.0,
                'cie_threshold_percent' => 50.0,
                'target_student_percent' => 70.0,
                'level3_percent' => 70.0,
                'level2_percent' => 60.0,
                'level1_percent' => 50.0,
                'w_cie' => 0.30,
                'w_ese' => 0.70,
            ];
        }

        if (str_contains($type, 'seminar')) {
            return [
                'entry_mode' => 'marks',
                'max_marks' => 0,       // No Board ESE for Seminar (Clause 11.2.6)
                'cia_marks' => 75,      // CIA = 75 Marks
                'ese_threshold_grade' => 'D',
                'ese_threshold_percent' => 50.0,
                'cie_threshold_percent' => 50.0,
                'target_student_percent' => 70.0,
                'level3_percent' => 70.0,
                'level2_percent' => 60.0,
                'level1_percent' => 50.0,
                'w_cie' => 1.00,        // 100% CIA-based direct attainment
                'w_ese' => 0.00,
            ];
        }

        if (str_contains($type, 'project')) {
            return [
                'entry_mode' => 'dual', // Dual: marks out of 50 or letter grade
                'max_marks' => 50,      // ESE = 50 Marks (Clause 11.3.4)
                'cia_marks' => 75,      // CIA = 75 Marks (Clause 11.2.5: Summative 30 + Formative 30 + Att 15)
                'ese_threshold_grade' => 'D',
                'ese_threshold_percent' => 50.0,
                'cie_threshold_percent' => 50.0,
                'target_student_percent' => 70.0,
                'level3_percent' => 70.0,
                'level2_percent' => 60.0,
                'level1_percent' => 50.0,
                'w_cie' => 0.30,
                'w_ese' => 0.70,
            ];
        }

        if (str_contains($type, 'drawing')) {
            return [
                'entry_mode' => 'dual',
                'max_marks' => 75,      // ESE = 75 Marks (or 100 as per scheme)
                'cia_marks' => 50,      // CIA = 50 Marks (Summative 20 + Formative 20 + Att 10)
                'ese_threshold_grade' => 'D',
                'ese_threshold_percent' => 50.0,
                'cie_threshold_percent' => 50.0,
                'target_student_percent' => 70.0,
                'level3_percent' => 70.0,
                'level2_percent' => 60.0,
                'level1_percent' => 50.0,
                'w_cie' => 0.30,
                'w_ese' => 0.70,
            ];
        }

        // Standard Theory Course (Revision 2021)
        return [
            'entry_mode' => 'dual',     // Dual: SBTE Grade or Raw Mark
            'max_marks' => 75,          // ESE = 75 Marks (Clause 11.3.2)
            'cia_marks' => 50,          // CIA = 50 Marks (Summative 20 + Formative 20 + Att 10)
            'ese_threshold_grade' => 'D',
            'ese_threshold_percent' => 50.0,
            'cie_threshold_percent' => 50.0,
            'target_student_percent' => 70.0,
            'level3_percent' => 70.0,
            'level2_percent' => 60.0,
            'level1_percent' => 50.0,
            'w_cie' => 0.30,
            'w_ese' => 0.70,
        ];
    }

    /**
     * Calculate CIA Attainment Level for a student cohort.
     */
    public static function calculateCiaAttainmentLevel(array $studentScores, float $maxMarks = 40.0, float $thresholdPct = 50.0, float $lvl3 = 70.0, float $lvl2 = 60.0, float $lvl1 = 50.0): int
    {
        if (empty($studentScores) || $maxMarks <= 0) return 0;
        $metCount = 0;
        $total = count($studentScores);
        foreach ($studentScores as $score) {
            $pct = ((float)$score / $maxMarks) * 100.0;
            if ($pct >= $thresholdPct) {
                $metCount++;
            }
        }
        $metPercent = round(($metCount / $total) * 100.0, 1);
        return self::calculateBatchLevel($metPercent, $lvl3, $lvl2, $lvl1);
    }

    /**
     * Official NBA Diploma Program Outcomes (PO1 to PO11) definitions
     * for Polytechnic Colleges under SBTE Kerala / NBA Diploma Manual.
     */
    public static function getProgramOutcomes(): array
    {
        return [
            'PO1' => ['title' => 'Basic and Discipline specific knowledge', 'desc' => 'Apply knowledge of basic mathematics, science, engineering fundamentals and engineering specialization to solve engineering problems.'],
            'PO2' => ['title' => 'Problem analysis', 'desc' => 'Identify and analyse well-defined engineering problems using codified methods of analysis.'],
            'PO3' => ['title' => 'Design/ development of solutions', 'desc' => 'Design solutions for well-defined technical problems and assist with the design of systems, components or processes.'],
            'PO4' => ['title' => 'Engineering Tools, Experimentation and Testing', 'desc' => 'Apply modern engineering tools and appropriate technique to conduct standard tests and measurements.'],
            'PO5' => ['title' => 'Engineering practices for society, sustainability & environment', 'desc' => 'Apply appropriate technology in context of society, sustainability, environment and ethical practices.'],
            'PO6' => ['title' => 'Project Management', 'desc' => 'Use engineering management principles individually, as a team member or a leader to manage projects.'],
            'PO7' => ['title' => 'Life-long learning', 'desc' => 'Ability to analyse individual needs and engage in updating in the context of technological changes.'],
            'PO8' => ['title' => 'Ethics', 'desc' => 'Apply ethical principles and commit to professional ethics and responsibilities of engineering practice.'],
            'PO9' => ['title' => 'Individual and team work', 'desc' => 'Function effectively as an individual, and as a member or leader in diverse teams and multidisciplinary settings.'],
            'PO10' => ['title' => 'Communication', 'desc' => 'Communicate effectively on well-defined engineering activities with the engineering community and society.'],
            'PO11' => ['title' => 'Modern Environment & Sustainability', 'desc' => 'Understand the impact of engineering solutions in societal and environmental contexts.']
        ];
    }

    /**
     * Program Specific Outcomes template (PSO1 to PSO3).
     */
    public static function getProgramSpecificOutcomes(string $branch = ''): array
    {
        return [
            'PSO1' => ['title' => 'Technical Competence', 'desc' => 'Apply domain-specific engineering principles to maintain, troubleshoot, and develop specialized equipment and software.'],
            'PSO2' => ['title' => 'Industry Readiness', 'desc' => 'Employ modern industry-standard practices, tools, and protocols conforming to current regulatory and industrial benchmarks.'],
            'PSO3' => ['title' => 'Innovation & Entrepreneurship', 'desc' => 'Exhibit innovative thinking, problem-solving skills, and social responsibility to pursue technical careers or entrepreneurship.']
        ];
    }

    /**
     * Calculate PO/PSO contributions for a single course from its CO-PO matrix & overall CO attainment levels.
     * Formula: PO_k = (Sum CO_i * Weight_ik) / (Sum Weight_ik)
     */
    public static function calculateCoursePoContribution(array $coAttainments, array $mappings): array
    {
        $poAttainments = [];
        $keys = array_merge(
            array_map(fn($n) => "PO{$n}", range(1, 11)),
            ['PSO1', 'PSO2', 'PSO3']
        );

        foreach ($keys as $poKey) {
            $sumWeight = 0;
            $sumAttainment = 0.0;

            foreach ($coAttainments as $coTag => $coScore) {
                $correlation = isset($mappings[$coTag][$poKey]) && is_numeric($mappings[$coTag][$poKey]) 
                    ? (int)$mappings[$coTag][$poKey] 
                    : 0;
                if ($correlation > 0) {
                    $sumWeight += $correlation;
                    $sumAttainment += (float)$coScore * $correlation;
                }
            }

            $poAttainments[$poKey] = [
                'attainment' => $sumWeight > 0 ? round($sumAttainment / $sumWeight, 2) : null,
                'weight' => $sumWeight
            ];
        }

        return $poAttainments;
    }

    /**
     * Calculate Program Direct Attainment for each PO/PSO by averaging across all program courses.
     */
    public static function calculateProgramDirectAttainment(array $coursesPoContributions): array
    {
        $directPo = [];
        $keys = array_merge(
            array_map(fn($n) => "PO{$n}", range(1, 11)),
            ['PSO1', 'PSO2', 'PSO3']
        );

        foreach ($keys as $poKey) {
            $scores = [];
            foreach ($coursesPoContributions as $course) {
                if (isset($course[$poKey]['attainment']) && is_numeric($course[$poKey]['attainment'])) {
                    $scores[] = (float)$course[$poKey]['attainment'];
                }
            }
            $directPo[$poKey] = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0.0;
        }

        return $directPo;
    }

    /**
     * Calculate Program Final PO/PSO Attainment combining 80% Direct + 20% Indirect (Exit, Alumni, Employer).
     */
    public static function calculateProgramFinalAttainment(array $directPo, array $indirectPo, float $wDirect = 0.80, float $wIndirect = 0.20): array
    {
        $final = [];
        foreach ($directPo as $poKey => $directVal) {
            $indirectVal = isset($indirectPo[$poKey]) && is_numeric($indirectPo[$poKey]) ? (float)$indirectPo[$poKey] : 2.50;
            $overall = round(($wDirect * $directVal) + ($wIndirect * $indirectVal), 2);
            $final[$poKey] = [
                'direct' => $directVal,
                'indirect' => $indirectVal,
                'overall' => $overall
            ];
        }
        return $final;
    }
}

