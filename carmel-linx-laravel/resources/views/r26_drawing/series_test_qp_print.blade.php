<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Series {{ $testNo }} - {{ strtoupper($docType) }} - {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 4px;
            color: #000;
            background: #fff;
            font-size: 9.5px;
            line-height: 1.25;
        }

        /* Institutional Header Grid Block */
        .meta-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border: 1.5px solid #000;
            font-size: 9.5px;
        }

        .meta-header-table td {
            border: 1px solid #000 !important;
            padding: 3px 6px;
            vertical-align: middle;
        }

        .meta-header-title {
            text-align: center;
            background: #f8fafc;
            padding: 4px;
        }

        .meta-header-title h1 {
            margin: 0;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-header-title h2 {
            margin: 2px 0 0 0;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .meta-header-title h3 {
            margin: 2px 0 0 0;
            font-size: 10px;
            font-weight: 700;
            color: #0284c7;
            text-transform: uppercase;
        }

        .section-badge {
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            background-color: #e2e8f0;
            padding: 4px 8px;
            border: 1px solid #000;
            margin-top: 6px;
            margin-bottom: 6px;
            text-align: center;
        }

        .instructions-box {
            font-size: 9px;
            border: 1px solid #000;
            padding: 4px 8px;
            margin-bottom: 6px;
            background: #fafafa;
        }

        /* Question Paper Table */
        table.qp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-bottom: 6px;
        }

        table.qp-table, table.qp-table th, table.qp-table td {
            border: 1px solid #000 !important;
        }

        table.qp-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            padding: 4px;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
        }

        table.qp-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .choice-divider {
            text-align: center;
            font-weight: 800;
            font-size: 10px;
            background: #fee2e2;
            color: #991b1b;
            padding: 2px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin: 4px 0;
        }

        .sub-q-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .scheme-item {
            background: #f8fafc;
            border: 1px dashed #475569;
            padding: 4px 6px;
            margin-top: 3px;
            font-size: 9px;
        }

        .key-item {
            background: #ecfdf5;
            border: 1px solid #059669;
            padding: 4px 6px;
            margin-top: 3px;
            font-size: 9px;
        }

        .footer-signatures {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            page-break-inside: avoid;
        }

        .signature-box {
            border-top: 1px solid #000;
            width: 160px;
            text-align: center;
            padding-top: 3px;
            font-weight: 600;
        }

        .btn-print-group {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-bottom: 8px;
        }

        .btn-print {
            padding: 5px 12px;
            background: #0284c7;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 11px;
            text-decoration: none;
        }

        .btn-print-outline {
            padding: 5px 12px;
            background: #fff;
            color: #0284c7;
            border: 1px solid #0284c7;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 11px;
            text-decoration: none;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>

    <!-- Print Control Bar -->
    <div class="no-print btn-print-group">
        <a href="?doc_type=qp" class="btn-print {{ $docType == 'qp' ? '' : 'btn-print-outline' }}">📄 Question Paper (1 Page)</a>
        <a href="?doc_type=scheme" class="btn-print {{ $docType == 'scheme' ? '' : 'btn-print-outline' }}">📊 Valuation Scheme</a>
        <a href="?doc_type=key" class="btn-print {{ $docType == 'key' ? '' : 'btn-print-outline' }}">🔑 Answer Key</a>
        <a href="?doc_type=all" class="btn-print {{ $docType == 'all' ? '' : 'btn-print-outline' }}">📚 Complete Package</a>
        <button onclick="window.print()" class="btn-print" style="background: #059669;">🖨️ Print View</button>
    </div>

    <!-- DOCUMENT 1: QUESTION PAPER (QP - STRICT SINGLE A4 PAGE FIT) -->
    @if($docType == 'qp' || $docType == 'all')
    <table class="meta-header-table">
        <tr>
            <td colspan="4" class="meta-header-title">
                <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                <h2>DEPARTMENT OF {{ strtoupper($classroom->branch ?? 'Mechanical') }} ENGINEERING</h2>
                <h3>{{ $qpData['test_title'] }} — REVISION 2026</h3>
            </td>
        </tr>
        <tr>
            <td style="width: 35%;"><strong>Course:</strong> {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</td>
            <td style="width: 25%;"><strong>Semester:</strong> SEMESTER {{ $classroom->current_semester ?? 'I' }}</td>
            <td style="width: 20%;"><strong>Batch:</strong> {{ $classroom->batch_year ? $classroom->batch_year.'-'.($classroom->batch_year+3) : '2026-2029' }}</td>
            <td style="width: 20%;"><strong>Acad Year:</strong> {{ date('Y').'-'.(date('Y')+1) }}</td>
        </tr>
        <tr>
            <td><strong>Modules Covered:</strong> {{ $qpData['modules_covered'] }}</td>
            <td><strong>Mapped COs:</strong> {{ $qpData['co_tags'] }}</td>
            <td><strong>Duration:</strong> {{ $qpData['duration'] }}</td>
            <td><strong>Max Marks:</strong> {{ $qpData['max_marks'] }} Marks</td>
        </tr>
    </table>

    <div class="instructions-box">
        <strong>INSTRUCTIONS TO CANDIDATES:</strong> {{ $qpData['instructions'] }}
    </div>

    <table class="qp-table">
        <thead>
            <tr>
                <th style="width: 45px;">Q.No</th>
                <th style="width: 65px;">Module</th>
                <th style="width: 45px;">CO</th>
                <th>Question Description & Drawing Specifications</th>
                <th style="width: 50px;">Marks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qpData['questions'] as $q)
            <!-- Option A -->
            <tr>
                <td rowspan="2" class="text-center" style="font-weight: 800; font-size: 11px;">{{ $q['q_no'] }}</td>
                <td rowspan="2" style="text-align: center; vertical-align: middle;">{{ $q['module'] }}</td>
                <td rowspan="2" style="text-align: center; font-weight: 700; vertical-align: middle;">{{ $q['co'] }}</td>
                <td>
                    <div style="font-weight: 700; color: #0369a1; margin-bottom: 2px;">{{ $q['option_a']['title'] }}</div>
                    @foreach($q['option_a']['sub_questions'] as $sub)
                    <div class="sub-q-row">
                        <span><strong>{{ $sub['sub_no'] }}</strong> {{ $sub['text'] }}</span>
                        <strong style="white-space: nowrap; margin-left: 8px;">[{{ $sub['marks'] }}]</strong>
                    </div>
                    @endforeach
                </td>
                <td style="text-align: center; font-weight: 700; vertical-align: middle;">{{ $q['total_marks'] }}</td>
            </tr>
            <!-- Choice Divider / OR -->
            <tr>
                <td colspan="2" style="padding: 0;">
                    <div class="choice-divider">--- OR ---</div>
                    <div style="padding: 4px 6px;">
                        <div style="font-weight: 700; color: #0369a1; margin-bottom: 2px;">{{ $q['option_b']['title'] }}</div>
                        @foreach($q['option_b']['sub_questions'] as $sub)
                        <div class="sub-q-row">
                            <span><strong>{{ $sub['sub_no'] }}</strong> {{ $sub['text'] }}</span>
                            <strong style="white-space: nowrap; margin-left: 8px;">[{{ $sub['marks'] }}]</strong>
                        </div>
                        @endforeach
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-signatures">
        <div class="signature-box">Faculty In-Charge</div>
        <div class="signature-box">Head of Department</div>
    </div>
    @endif

    <!-- DOCUMENT 2: VALUATION SCHEME -->
    @if($docType == 'scheme' || $docType == 'all')
    @if($docType == 'all') <div class="page-break"></div> @endif
    <table class="meta-header-table">
        <tr>
            <td colspan="4" class="meta-header-title">
                <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                <h2>DEPARTMENT OF {{ strtoupper($classroom->branch ?? 'Mechanical') }} ENGINEERING</h2>
                <h3 style="color: #d97706;">VALUATION SCHEME & MARKING RUBRICS — {{ $qpData['test_title'] }}</h3>
            </td>
        </tr>
        <tr>
            <td style="width: 40%;"><strong>Course:</strong> {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</td>
            <td style="width: 20%;"><strong>Semester:</strong> SEMESTER {{ $classroom->current_semester ?? 'I' }}</td>
            <td style="width: 20%;"><strong>Test:</strong> Series {{ $testNo }}</td>
            <td style="width: 20%;"><strong>Max Marks:</strong> {{ $qpData['max_marks'] }} Marks</td>
        </tr>
    </table>

    <div class="section-badge">PART B: VALUATION SCHEME & RUBRIC DISTRIBUTION</div>

    <table class="qp-table">
        <thead>
            <tr>
                <th style="width: 40px;">Q.No</th>
                <th style="width: 40px;">CO</th>
                <th>Option / Sub-Question</th>
                <th>Detailed Step-by-Step Marking Breakdown</th>
                <th style="width: 50px;">Marks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qpData['questions'] as $q)
            @foreach(['option_a', 'option_b'] as $optKey)
            @foreach($q[$optKey]['sub_questions'] as $sub)
            <tr>
                <td style="text-align: center; font-weight: 700;">{{ $q['q_no'] }} ({{ $optKey == 'option_a' ? 'A' : 'B' }})</td>
                <td style="text-align: center; font-weight: 700;">{{ $q['co'] }}</td>
                <td><strong>{{ $sub['sub_no'] }}</strong> {{ Str::limit($sub['text'], 90) }}</td>
                <td>
                    <div class="scheme-item">
                        <strong>Rubric Breakdown:</strong> {{ $sub['scheme'] }}
                    </div>
                </td>
                <td style="text-align: center; font-weight: 700;">{{ $sub['marks'] }}</td>
            </tr>
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer-signatures">
        <div class="signature-box">Evaluator Signature</div>
        <div class="signature-box">HOD Verification</div>
    </div>
    @endif

    <!-- DOCUMENT 3: ANSWER KEY -->
    @if($docType == 'key' || $docType == 'all')
    @if($docType == 'all') <div class="page-break"></div> @endif
    <table class="meta-header-table">
        <tr>
            <td colspan="4" class="meta-header-title">
                <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                <h2>DEPARTMENT OF {{ strtoupper($classroom->branch ?? 'Mechanical') }} ENGINEERING</h2>
                <h3 style="color: #059669;">MODEL SOLUTION & ANSWER KEY — {{ $qpData['test_title'] }}</h3>
            </td>
        </tr>
        <tr>
            <td style="width: 40%;"><strong>Course:</strong> {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</td>
            <td style="width: 20%;"><strong>Semester:</strong> SEMESTER {{ $classroom->current_semester ?? 'I' }}</td>
            <td style="width: 20%;"><strong>Test:</strong> Series {{ $testNo }}</td>
            <td style="width: 20%;"><strong>Max Marks:</strong> {{ $qpData['max_marks'] }} Marks</td>
        </tr>
    </table>

    <div class="section-badge">PART C: MODEL SOLUTION STEPS & ANSWER KEY</div>

    @foreach($qpData['questions'] as $q)
    <div style="margin-bottom: 8px; border: 1px solid #000; padding: 6px;">
        <div style="font-weight: 800; font-size: 10.5px; color: #0284c7; margin-bottom: 4px;">
            {{ $q['q_no'] }} Solutions [{{ $q['module'] }} — {{ $q['co'] }}]:
        </div>
        @foreach(['option_a', 'option_b'] as $optKey)
        <div style="font-weight: 700; color: #475569; margin-top: 4px;">
            {{ $q[$optKey]['title'] }}:
        </div>
        @foreach($q[$optKey]['sub_questions'] as $sub)
        <div class="key-item">
            <strong>{{ $sub['sub_no'] }} Key Steps [{{ $sub['marks'] }} Marks]:</strong><br>
            {{ $sub['answer_key'] }}
        </div>
        @endforeach
        @endforeach
    </div>
    @endforeach

    <div class="footer-signatures">
        <div class="signature-box">Author / Prepared By</div>
        <div class="signature-box">HOD Approval</div>
    </div>
    @endif

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
