<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CO-PO Attainment Report - {{ $batchSubject->subject_code }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #555;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>CO-PO Attainment & Articulation Report - Revision 2026</h2>
        <h3 style="margin: 5px 0 0 0; font-size:13px; text-transform:uppercase;">Practical / Laboratory Course</h3>
    </div>

    <div class="meta-info">
        <div>
            <strong>Subject:</strong> {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})<br>
            <strong>Class/Sem:</strong> Sem I - Diploma in Engineering
        </div>
        <div style="text-align: right;">
            <strong>Academic Year:</strong> 2026-2027<br>
            <strong>Faculty:</strong> Lecturer In Charge
        </div>
    </div>

    <!-- CO Attainment Summary -->
    <h3 style="font-size: 14px; margin-top:20px; border-left: 4px solid #333; padding-left: 8px;">1. Course Outcome (CO) Attainment levels</h3>
    <table>
        <thead>
            <tr>
                <th>Course Outcome</th>
                <th>Direct Attainment Level (80%)</th>
                <th>Indirect Attainment Level (20%)</th>
                <th>Combined CO Attainment</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
            @php
                $direct = $directStats[$coTag]['level'] ?? 0;
                $indirect = $indirectStats[$coTag]['level'] ?? 0;
                $combined = $combinedStats[$coTag] ?? 0;
            @endphp
            <tr>
                <td class="bold font-mono">{{ $coTag }}</td>
                <td>{{ $direct }} <span style="font-size:10px; color:#555;">(Met {{ $directStats[$coTag]['met_percent'] ?? 0 }}%)</span></td>
                <td>{{ $indirect }}</td>
                <td class="bold">{{ $combined }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- PO Attainment Summary -->
    <h3 style="font-size: 14px; margin-top:20px; border-left: 4px solid #333; padding-left: 8px;">2. Program Outcome (PO) Attainment Profile</h3>
    <table>
        <thead>
            <tr>
                <th>PO ID</th>
                @for($p = 1; $p <= 11; $p++)
                <th>PO{{ $p }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            <tr class="bold">
                <td>Attainment</td>
                @for($p = 1; $p <= 11; $p++)
                <td>{{ $poAttainments["PO$p"]['value'] ?? '0.00' }}</td>
                @endfor
            </tr>
            <tr style="font-size: 10px; color: #555;">
                <td>Weight</td>
                @for($p = 1; $p <= 11; $p++)
                <td>{{ $poAttainments["PO$p"]['weight'] ?? 0 }}</td>
                @endfor
            </tr>
        </tbody>
    </table>

    <!-- CO-PO Articulation Matrix weight pointers -->
    <h3 style="font-size: 14px; margin-top:20px; border-left: 4px solid #333; padding-left: 8px;">3. Course Mapping Correlation Reference</h3>
    <table>
        <thead>
            <tr>
                <th class="text-left">CO ID</th>
                @for($p = 1; $p <= 11; $p++)
                <th>PO{{ $p }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
            <tr>
                <td class="bold font-mono text-left">{{ $coId }}</td>
                @for($p = 1; $p <= 11; $p++)
                <td>{{ $mappings[$coId]["PO$p"] ?? '-' }}</td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-line">
            Faculty In Charge
        </div>
        <div class="signature-line">
            Head of Department
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
