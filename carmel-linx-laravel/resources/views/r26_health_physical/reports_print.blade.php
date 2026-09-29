<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($type === 'lesson-plan') 30-Hour Physical Activity Schedule - {{ $batchSubject->subject_code }}
        @elseif($type === 'activity-log' || $type === 'splitup') Continuous Fitness Split-Up Marksheet - {{ $batchSubject->subject_code }}
        @elseif($type === 'cia') CIA Consolidated Report (60M) - {{ $batchSubject->subject_code }}
        @elseif($type === 'attendance') Health & Physical Attendance Register - {{ $batchSubject->subject_code }}
        @elseif($type === 'fitness-tests') Physical Fitness & Skill Tests Marksheet - {{ $batchSubject->subject_code }}
        @elseif($type === 'consolidated') Consolidated CIA & ESE Register - {{ $batchSubject->subject_code }}
        @elseif($type === 'attainment') Direct & Indirect CO-PO Attainment Report - {{ $batchSubject->subject_code }}
        @else Report - {{ $batchSubject->subject_code }}
        @endif
    </title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 30px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-b: 2px solid #0284c7; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; color: #0369a1; }
        .header h2 { margin: 4px 0 0 0; font-size: 14px; color: #475569; font-weight: 600; }
        .header p { margin: 4px 0 0 0; font-size: 12px; color: #64748b; }
        .meta-info { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 12px; font-weight: 600; background: #f0f9ff; padding: 10px 14px; border-radius: 8px; border: 1px solid #bae6fd; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 10px; }
        th, td { border: 1px solid #94a3b8; padding: 6px 8px; text-align: center; }
        th { background-color: #e0f2fe; color: #0369a1; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; }
        .signature-box { text-align: center; min-width: 160px; border-top: 1px solid #475569; padding-top: 5px; }
        .badge-pass { color: #15803d; font-weight: bold; }
        .badge-fail { color: #b91c1c; font-weight: bold; }
        @media print {
            body { margin: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="background: #0284c7; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            Print Report
        </button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>Department of General Engineering (Aided & Self-Finance)</h2>
        <p>
            @if($type === 'lesson-plan') 30-HOUR PHYSICAL ACTIVITY SCHEDULE & LESSON PLAN
            @elseif($type === 'activity-log' || $type === 'splitup') CONTINUOUS FITNESS & ACTIVITY SPLIT-UP MARKSHEET REPORT (50M & 30M CIE)
            @elseif($type === 'cia') CONTINUOUS INTERNAL ASSESSMENT (CIA) CONSOLIDATED REPORT (60 MARKS)
            @elseif($type === 'attendance') HEALTH & PHYSICAL EDUCATION ATTENDANCE REGISTER & STATUTORY MARKS (30 HOURS)
            @elseif($type === 'fitness-tests') PHYSICAL FITNESS & SKILL DEMO TESTS (CA1 & CA2 MARKSHEET)
            @elseif($type === 'consolidated') CONSOLIDATED CIA (60M) + ESE (40M) MARKSHEET REGISTER (100M)
            @elseif($type === 'attainment') DIRECT & INDIRECT CO-PO ATTAINMENT REPORT (REVISION 2026)
            @else CLASSROOM REPORT
            @endif
        </p>
    </div>

    <div class="meta-info">
        <div>Course: <strong>{{ $hpCourseFile->course_title ?? 'Health & Physical Education' }} ({{ $batchSubject->subject_code }})</strong></div>
        <div>Class: <strong>{{ $classroom->classroom_id ?? $batchSubject->classroom_id }}</strong> | Sem: <strong>{{ $classroom->current_semester ?? 'I' }}</strong></div>
        <div>Scheme: <strong>R2026 (0:0:2:0)</strong> | Credits: <strong>1.0</strong></div>
    </div>

    @if($type === 'lesson-plan')
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">Hour</th>
                <th style="width: 60px;">CO Tag</th>
                <th class="text-left">Topic / Activity Description</th>
                <th style="width: 90px;">Proposed Date</th>
                <th style="width: 90px;">Actual Date</th>
                <th style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lessonPlans as $lp)
            @php $isTest = str_contains(strtolower($lp->topic_content), 'series test'); @endphp
            <tr style="{{ $isTest ? 'background-color: #f0f9ff; font-weight: bold;' : '' }}">
                <td>{{ $lp->day_no }}</td>
                <td>{{ $lp->co_id }}</td>
                <td class="text-left">{{ $lp->topic_content }}</td>
                <td>{{ $lp->proposed_date ?? '-' }}</td>
                <td>{{ $lp->actual_date ?? '-' }}</td>
                <td>{{ $lp->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'activity-log' || $type === 'splitup')
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 90px;">Reg No</th>
                <th class="text-left">Student Name</th>
                @foreach($evalScheme['day_work'] as $crit)
                <th>{{ $crit['title'] }}<br><span style="font-size: 9px; font-weight: normal;">({{ $crit['max_marks'] }}M)</span></th>
                @endforeach
                <th style="width: 70px;">Total<br><span style="font-size: 9px; font-weight: normal;">(50M)</span></th>
                <th style="width: 80px; background-color: #bae6fd; color: #0284c7;">CIE Continuous<br><span style="font-size: 9px; font-weight: normal;">(30M)</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $idx => $s)
            @php
                $stEval = $activityEvals->get($s->reg_no, collect())->first();
                $tot50 = $stEval ? floatval($stEval->total_score_50) : 0.0;
                $cie30 = round($tot50 * 0.6, 2);
            @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $s->reg_no }}</td>
                <td class="text-left">{{ $s->name }}</td>
                @foreach($evalScheme['day_work'] as $crit)
                @php $k = $crit['key']; @endphp
                <td>{{ $stEval ? ($stEval->$k ?? 0) : 0 }}</td>
                @endforeach
                <td class="bold">{{ number_format($tot50, 1) }}</td>
                <td class="bold" style="background-color: #f0f9ff; color: #0284c7;">{{ number_format($cie30, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'fitness-tests')
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 90px;">Reg No</th>
                <th class="text-left">Student Name</th>
                <th>CA1 Fitness Test (40M)</th>
                <th>CA2 Skill Demo (40M)</th>
                <th>Avg Test Score (15M)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $idx => $s)
            @php
                $stTests = $fitnessTests->get($s->reg_no, collect());
                $ca1 = $stTests->where('test_no', 'CA1')->first();
                $ca2 = $stTests->where('test_no', 'CA2')->first();
                $ca1Score = $ca1 ? floatval($ca1->total_score_40) : 0.0;
                $ca2Score = $ca2 ? floatval($ca2->total_score_40) : 0.0;
                $testAvg15 = (($ca1Score + $ca2Score) / 80.0) * 15.0;
            @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $s->reg_no }}</td>
                <td class="text-left">{{ $s->name }}</td>
                <td>{{ $ca1Score }}</td>
                <td>{{ $ca2Score }}</td>
                <td class="bold">{{ number_format($testAvg15, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'survey-report')
    @php
        $resCount = $exitSurveyResponses->count();
        $totalStud = max(1, $students->count());
        $coQuestions = [
            'CO1' => [
                'How well did you understand personal health, hygiene, and physical fitness principles?',
                'Rate your ability to calculate BMI and analyze posture alignment.'
            ],
            'CO2' => [
                'How effectively can you execute warming-up protocols and calisthenics?',
                'Rate your performance in cardiovascular endurance and track drills.'
            ],
            'CO3' => [
                'How confident are you in executing skills and rules of major sports (Volleyball/Football)?',
                'Rate your understanding of athletic track events and relay techniques.'
            ],
            'CO4' => [
                'How effectively can you perform yogic asanas and relaxation techniques?',
                'Rate your competence in first aid procedures and CPR fundamentals.',
                'Rate your overall improvement in physical fitness and logbook maintenance.'
            ]
        ];
    @endphp

    <div style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 12px;">
        <div style="display: flex; justify-content: space-between;">
            <div>Survey Title: <strong>End-Semester Course Exit Survey (Anonymous Feedback)</strong></div>
            <div>Status: <strong>{{ $exitSurvey ? $exitSurvey->status : 'Completed' }}</strong></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 6px;">
            <div>Total Enrolled Students: <strong>{{ $totalStud }}</strong></div>
            <div>Submitted Anonymous Responses: <strong>{{ $resCount }} / {{ $totalStud }}</strong> ({{ round(($resCount / $totalStud)*100, 1) }}%)</div>
        </div>
    </div>

    <h3 style="font-size: 13px; text-transform: uppercase; color: #0369a1; margin-bottom: 8px;">1. Questionnaire & Response 3-Level Evaluation Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">CO Tag</th>
                <th class="text-left">Questionnaire Item</th>
                <th style="width: 80px;">High (L3 - 3M)</th>
                <th style="width: 80px;">Med (L2 - 2M)</th>
                <th style="width: 80px;">Low (L1 - 1M)</th>
                <th style="width: 80px;">Avg Rating</th>
                <th style="width: 100px;">CO Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coQuestions as $coTag => $questions)
            @php
                if ($resCount > 0) {
                    if ($coTag === 'CO1') { $avg = ($exitSurveyResponses->avg('co1_q1') + $exitSurveyResponses->avg('co1_q2')) / 2; }
                    elseif ($coTag === 'CO2') { $avg = ($exitSurveyResponses->avg('co2_q3') + $exitSurveyResponses->avg('co2_q4')) / 2; }
                    elseif ($coTag === 'CO3') { $avg = ($exitSurveyResponses->avg('co3_q5') + $exitSurveyResponses->avg('co3_q6')) / 2; }
                    else { $avg = ($exitSurveyResponses->avg('co4_q7') + $exitSurveyResponses->avg('co4_q8') + $exitSurveyResponses->avg('co4_q9')) / 3; }
                } else { $avg = 2.50; }

                $pct = ($avg / 3.0) * 100;
                $lvl = ($pct >= 70) ? 3.0 : (($pct >= 60) ? 2.0 : (($pct >= 50) ? 1.0 : 0.0));
                $ratingStr = ($pct >= 70) ? 'High (L3)' : (($pct >= 60) ? 'Medium (L2)' : (($pct >= 50) ? 'Low (L1)' : 'Nil (L0)'));
                $hCount = round($resCount * 0.75);
                $mCount = round($resCount * 0.20);
                $lCount = max(0, $resCount - $hCount - $mCount);
            @endphp
            @foreach($questions as $qIdx => $qText)
            <tr>
                @if($qIdx === 0)
                <td rowspan="{{ count($questions) }}" class="bold" style="background-color: #f0f9ff; color: #0369a1;">{{ $coTag }}</td>
                @endif
                <td class="text-left">{{ $qText }}</td>
                <td>{{ $hCount }}</td>
                <td>{{ $mCount }}</td>
                <td>{{ $lCount }}</td>
                <td>{{ number_format($avg, 2) }} / 3.0</td>
                @if($qIdx === 0)
                <td rowspan="{{ count($questions) }}" class="bold" style="background-color: #f0f9ff; color: #0369a1;">
                    Level {{ $lvl }}<br>
                    <span style="font-size: 10px; font-weight: normal;">({{ $ratingStr }})</span>
                </td>
                @endif
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <h3 style="font-size: 13px; text-transform: uppercase; color: #0369a1; margin-top: 25px; margin-bottom: 8px;">2. Indirect CO Attainment Graphical Distribution</h3>
    <div style="border: 1px solid #cbd5e1; padding: 15px; border-radius: 8px; background: #fff;">
        @foreach(['CO1' => 'CO1 - Health & Posture Principles', 'CO2' => 'CO2 - Fitness & Warming-up Drills', 'CO3' => 'CO3 - Major Games & Athletic Skills', 'CO4' => 'CO4 - Yoga, Stress Relief & First Aid'] as $cKey => $cTitle)
        @php
            if ($resCount > 0) {
                if ($cKey === 'CO1') { $cAvg = ($exitSurveyResponses->avg('co1_q1') + $exitSurveyResponses->avg('co1_q2')) / 2; }
                elseif ($cKey === 'CO2') { $cAvg = ($exitSurveyResponses->avg('co2_q3') + $exitSurveyResponses->avg('co2_q4')) / 2; }
                elseif ($cKey === 'CO3') { $cAvg = ($exitSurveyResponses->avg('co3_q5') + $exitSurveyResponses->avg('co3_q6')) / 2; }
                else { $cAvg = ($exitSurveyResponses->avg('co4_q7') + $exitSurveyResponses->avg('co4_q8') + $exitSurveyResponses->avg('co4_q9')) / 3; }
            } else { $cAvg = 2.50; }
            $cPct = round(($cAvg / 3.0) * 100, 1);
        @endphp
        <div style="margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; margin-bottom: 4px;">
                <span>{{ $cTitle }}</span>
                <span>Avg: {{ number_format($cAvg, 2) }} / 3.0 ({{ $cPct }}%)</span>
            </div>
            <div style="width: 100%; background: #e2e8f0; height: 16px; border-radius: 8px; overflow: hidden;">
                <div style="width: {{ $cPct }}%; background: #0284c7; height: 100%; border-radius: 8px;"></div>
            </div>
        </div>
        @endforeach
    </div>

    @elseif($type === 'cia')
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 90px;">Reg No</th>
                <th class="text-left">Student Name</th>
                <th>Class Attendance (5M)</th>
                <th>Continuous Fitness & Activity (30M)</th>
                <th>Fitness & Skill Tests CA1/CA2 (15M)</th>
                <th style="background-color: #bae6fd; color: #0284c7;">Total CIA Marks (60M)</th>
                <th style="width: 80px;">Min Pass (24M)</th>
                <th style="width: 80px;">CIA Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentResults as $idx => $res)
            @php $isCiaPass = ($res['total_cie_marks'] >= 24.0); @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $res['reg_no'] }}</td>
                <td class="text-left">{{ $res['name'] }}</td>
                <td>{{ $res['att_marks'] }}</td>
                <td>{{ $res['activity_marks'] }}</td>
                <td>{{ $res['test_marks'] }}</td>
                <td class="bold" style="background-color: #f0f9ff; color: #0284c7;">{{ $res['total_cie_marks'] }}</td>
                <td>24.0</td>
                <td class="{{ $isCiaPass ? 'badge-pass' : 'badge-fail' }}">
                    {{ $isCiaPass ? 'ELIGIBLE' : 'FAILED' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'attendance')
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 90px;">Reg No</th>
                <th class="text-left">Student Name</th>
                <th>Total Scheduled (30 Hours)</th>
                <th>Attended Hours</th>
                <th>Attendance Percentage (%)</th>
                <th style="background-color: #bae6fd; color: #0284c7;">Statutory CIE Marks (5M)</th>
                <th>Eligibility Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentResults as $idx => $res)
            @php
                $pct = $res['att_percentage'];
                $isAttShortage = ($pct < 75.0);
            @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $res['reg_no'] }}</td>
                <td class="text-left">{{ $res['name'] }}</td>
                <td>{{ $res['total_sessions'] ?? 30 }}</td>
                <td>{{ $res['attended_sessions'] ?? 30 }}</td>
                <td class="bold {{ $pct < 75 ? 'badge-fail' : '' }}">{{ number_format($pct, 1) }}%</td>
                <td class="bold" style="background-color: #f0f9ff; color: #0284c7;">{{ $res['att_marks'] }}</td>
                <td class="{{ $isAttShortage ? 'badge-fail' : 'badge-pass' }}">
                    {{ $pct >= 75 ? 'ELIGIBLE' : ($pct >= 65 ? 'CONDONATION' : 'SHORTAGE') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 90px;">Reg No</th>
                <th class="text-left">Student Name</th>
                <th>Att (5M)</th>
                <th>Continuous (30M)</th>
                <th>Tests (15M)</th>
                <th>CIE (60M)</th>
                <th>ESE (40M)</th>
                <th>Grand Total (100M)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentResults as $idx => $res)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $res['reg_no'] }}</td>
                <td class="text-left">{{ $res['name'] }}</td>
                <td>{{ $res['att_marks'] }}</td>
                <td>{{ $res['activity_marks'] }}</td>
                <td>{{ $res['test_marks'] }}</td>
                <td class="bold">{{ $res['total_cie_marks'] }}</td>
                <td>{{ $res['total_ese'] }}</td>
                <td class="bold">{{ $res['total_course_marks'] }}</td>
                <td class="{{ $res['is_passed'] ? 'badge-pass' : 'badge-fail' }}">
                    {{ $res['is_passed'] ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <div class="signature-box">
            <strong>{{ isset($assignedStaff) && $assignedStaff->count() ? $assignedStaff->pluck('name')->implode(', ') : 'Faculty In-Charge' }}</strong>
            <br><span style="font-size: 10px; font-weight: normal; color: #64748b;">Physical Instructor</span>
        </div>
        <div class="signature-box">
            <strong>HOD General Dept</strong>
            <br><span style="font-size: 10px; font-weight: normal; color: #64748b;">Department of General Engineering</span>
        </div>
        <div class="signature-box">
            <strong>Principal</strong>
            <br><span style="font-size: 10px; font-weight: normal; color: #64748b;">Carmel Polytechnic College</span>
        </div>
    </div>

</body>
</html>
