<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Written Test Marks Report - {{ $subject->subject_code }}</title>
    <style>
        :root {
            --primary: #851414;
            --border: #cbd5e1;
            --bg-light: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-main);
            line-height: 1.5;
            font-size: 12px;
        }

        .a4-container {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            position: relative;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            z-index: 50;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #600d0d;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: var(--primary);
            font-size: 22px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .header h3 {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
            padding: 12px;
            border-radius: 6px;
            background-color: var(--bg-light);
        }

        .details-item {
            display: flex;
            flex-direction: column;
        }

        .details-label {
            font-size: 9px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
        }

        .details-val {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
            margin-top: 1px;
        }

        .section-title {
            background: var(--primary);
            color: white;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 12px;
            text-transform: uppercase;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: var(--bg-light);
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            border-bottom: 2px solid var(--border);
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        @media print {
            body {
                background-color: white;
            }
            .print-controls {
                display: none;
            }
            .a4-container {
                width: auto;
                min-height: auto;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button onclick="window.print()" class="btn-print">Print Report</button>
        <button onclick="window.close()" class="btn-print" style="background:#475569;">Close</button>
    </div>

    <div class="a4-container">
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE</h1>
            <h3>Summative Assessment Marks Report (Written Tests)</h3>
        </div>

        <div class="details-grid">
            <div class="details-item">
                <span class="details-label">Department / Branch</span>
                <span class="details-val">{{ $fullDepartment }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Batch Code</span>
                <span class="details-val">{{ $cleanedBatch }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Current Academic Year</span>
                <span class="details-val">{{ $currentYear }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Subject Title</span>
                <span class="details-val">{{ $subject->subject_name }} ({{ $subject->subject_code }})</span>
            </div>
            <div class="details-item">
                <span class="details-label">Semester / Term</span>
                <span class="details-val">Semester {{ $subject->semester }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Total Enrolled Students</span>
                <span class="details-val">{{ $totalStudents }} Students</span>
            </div>
        </div>

        <div class="section-title">Written Marks Grid</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 45px;">S.No.</th>
                    <th>Student Name</th>
                    <th>College Adm No</th>
                    <th>SBTE Reg No</th>
                    <th style="text-align: center; width: 65px;">CO1<br><span style="font-size: 8px; font-weight: normal; color: var(--text-muted);">Test 1</span></th>
                    <th style="text-align: center; width: 65px;">CO2<br><span style="font-size: 8px; font-weight: normal; color: var(--text-muted);">Test 2</span></th>
                    <th style="text-align: center; width: 65px;">CO3<br><span style="font-size: 8px; font-weight: normal; color: var(--text-muted);">Test 3</span></th>
                    <th style="text-align: center; width: 65px;">CO4<br><span style="font-size: 8px; font-weight: normal; color: var(--text-muted);">Test 4</span></th>
                    <th style="text-align: center; width: 85px; background: #f1f5f9;">Written Avg<br><span style="font-size: 8px; font-weight: normal; color: var(--text-muted);">Best 2 (/20)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $s)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight: bold;">{{ $s->name }}</td>
                        <td style="font-family: monospace;">{{ $s->reg_no }}</td>
                        <td style="font-family: monospace;">{{ $s->sbte_reg_no ?: '-' }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $s->summative_marks['CO1'] }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $s->summative_marks['CO2'] }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $s->summative_marks['CO3'] }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $s->summative_marks['CO4'] }}</td>
                        <td style="text-align: center; font-weight: 800; background: #f8fafc; color: #1e3a8a;">{{ $s->best2_avg !== '-' ? $s->best2_avg . ' / 20' : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 20px;">No student marks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="font-size: 9.5px; color: var(--text-muted); margin-top: 6px; font-style: italic;">
            * Note: In accordance with SBTE Revision 2021 regulations, Written Test Average (Max 20) is determined from the best of the highest two test scores. Separate CO marks are retained for NBA Course Outcome attainment analysis.
        </div>

        <div style="margin-top: 80px; display: flex; justify-content: space-between;">
            <div style="text-align: center; width: 200px; border-top: 1px solid var(--text-muted); padding-top: 5px;">
                <p style="font-weight: bold;">Subject Faculty</p>
            </div>
            <div style="text-align: center; width: 200px; border-top: 1px solid var(--text-muted); padding-top: 5px;">
                <p style="font-weight: bold;">Head of Department</p>
            </div>
        </div>
    </div>

</body>
</html>
