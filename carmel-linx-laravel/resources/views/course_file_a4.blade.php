<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course File - {{ $courseFile->batchSubject->subject->subject_code ?? '' }}</title>
    <style>
        @page { margin: 2.5cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-xs { font-size: 18px; }
        .text-sm { font-size: 24px; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mt-8 { margin-top: 2rem; }
        .uppercase { text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        
        .section-title {
            background-color: #1f2937;
            color: white;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 2rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .placeholder-box {
            border: 2px dashed #9ca3af;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            color: #6b7280;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.15);
            z-index: 50;
            display: flex;
            gap: 10px;
            border: 1px solid #e2e8f0;
        }

        .btn-print {
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-close {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        @media print {
            .print-controls { display: none !important; }
            @page { margin: 2cm; }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
            Print Course File
        </button>
        <button class="btn-close" onclick="window.close()">Close</button>
    </div>

    <!-- Cover Page -->
    <div class="text-center" style="margin-top: 100px;">
        <h1 class="uppercase font-bold mb-4 text-xl">Carmel Polytechnic College</h1>
        <h2 class="uppercase font-bold mb-8 text-lg">Department of {{ $courseFile->batchSubject->classroom->branch ?? $courseFile->batchSubject->batch->branch ?? 'General' }}</h2>
        
        <div style="border: 2px solid #000; padding: 40px; margin: 40px;">
            <h1 class="font-bold uppercase mb-4 text-xl">Course File</h1>
            <h2 class="mb-8 text-lg">Academic Year: {{ $courseFile->academic_year }}</h2>
            
            <table style="width: 80%; margin: 0 auto; border: none;">
                <tr>
                    <td style="border: none; font-weight: bold; width: 40%;">Course Code:</td>
                    <td style="border: none;">{{ $courseFile->batchSubject->formatted_subject_code ?? $courseFile->batchSubject->subject_code ?? $courseFile->batchSubject->subject->subject_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Course Name:</td>
                    <td style="border: none;">{{ $courseFile->batchSubject->subject_name ?? $courseFile->batchSubject->subject->subject_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Semester:</td>
                    <td style="border: none;">Semester {{ $courseFile->batchSubject->semester ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Batch Year:</td>
                    <td style="border: none;">{{ $courseFile->batchSubject->classroom->batch_year ?? $courseFile->batchSubject->batch->batch_year ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Faculty Name:</td>
                    <td style="border: none;">{{ Session::get('userName') ?? 'Assigned Faculty' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Section A: Planning -->
    <div class="section-title">Section A: Course Information & Planning</div>
    
    <h3>1. Gaps Identified (if any)</h3>
    <p>{{ $courseFile->sectionA->gaps_identified ?? 'No gaps identified.' }}</p>

    <h3>2. Bridge Topics to meet outcomes</h3>
    <p>{{ $courseFile->sectionA->bridge_topics ?? 'N/A' }}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert the physical hard copy of the officially approved SBTE Kerala Syllabus here.</p>
    </div>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert the Faculty and Class Timetables here.</p>
    </div>
    
    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert the detailed Day-by-Day Lesson/Lecture Plan here.</p>
    </div>

    <div class="page-break"></div>

    <!-- Section B: Materials -->
    <div class="section-title">Section B: Teaching Materials</div>
    
    <h3>1. NPTEL / Swayam Links</h3>
    <p>{!! nl2br(e($courseFile->sectionB->nptel_swayam_links ?? 'None provided.')) !!}</p>

    <h3>2. Other Resources & Reference Materials</h3>
    <p>{!! nl2br(e($courseFile->sectionB->other_resources ?? 'None provided.')) !!}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert physical Lecture Notes, Printed Handouts, or Lab Manuals here.</p>
    </div>

    <div class="page-break"></div>

    <!-- Section C: Assessments -->
    <div class="section-title">Section C: Assessments & Evaluations</div>
    
    <h3>1. Evaluation Scheme (CIE & End Semester)</h3>
    <p>{!! nl2br(e($courseFile->sectionC->evaluation_scheme ?? 'Standard university evaluation scheme applies.')) !!}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert Mid-term and End-semester Question Papers & Answer Keys here.</p>
    </div>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert Sample Answer Scripts (Best, Average, and Low Performing) here.</p>
    </div>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert Consolidated Attendance & Continuous Internal Evaluation (CIE) Mark Sheets here.</p>
    </div>

    <div class="page-break"></div>

    <!-- Section D: Attainment -->
    <div class="section-title">Section D: Attainment & Continuous Improvement</div>
    
    <h3>1. Action Taken Report</h3>
    <p>{!! nl2br(e($courseFile->sectionD->action_taken_report ?? 'No corrective actions required.')) !!}</p>

    <h3>2. Course Committee Minutes</h3>
    <p>{!! nl2br(e($courseFile->sectionD->committee_minutes ?? 'No committee minutes recorded.')) !!}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert CO/PO/PSO Attainment Calculation Charts & Reports here.</p>
    </div>

    <div style="margin-top: 100px;">
        <table style="border: none;">
            <tr>
                <td style="border: none; text-align: left;">
                    ___________________________<br>
                    <strong>Signature of Faculty</strong>
                </td>
                <td style="border: none; text-align: right;">
                    ___________________________<br>
                    <strong>Signature of HOD</strong>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
