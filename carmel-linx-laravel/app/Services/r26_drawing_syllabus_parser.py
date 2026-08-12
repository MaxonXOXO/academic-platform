import sys
import os

# Ensure user site-packages are accessible when invoked under www-data web server environment
extra_paths = [
    '/home/carmel/.local/lib/python3.14/site-packages',
    '/home/carmel/.local/lib/python3.12/site-packages',
    '/home/carmel/.local/lib/python3.11/site-packages',
    '/home/carmel/.local/lib/python3.10/site-packages',
    '/usr/local/lib/python3.14/site-packages',
    '/usr/local/lib/python3.12/site-packages'
]
for p in extra_paths:
    if os.path.exists(p) and p not in sys.path:
        sys.path.insert(0, p)

import json
import re
import io
from pypdf import PdfReader

if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8')
else:
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def parse_drawing_syllabus(pdf_path):
    reader = PdfReader(pdf_path)
    full_text = ""
    standard_text = ""
    for page in reader.pages:
        full_text += page.extract_text(extraction_mode="layout") + "\n\n"
        standard_text += page.extract_text() + "\n\n"

    # Default Meta Details
    course_code = "1004"
    course_title = "Engineering Drawing with CAD"
    credits_val = 1.5
    ltpr_val = "0:0:3:0"
    cie_marks = 60
    ese_marks = 40
    total_hours = 45
    type_of_course = "Lab"
    semester = "I"
    program = "Diploma Engineering"

    # Type of Course
    match_type = re.search(r'Type of Course\s+([^\n]+)', full_text, re.IGNORECASE)
    if match_type:
        type_raw = match_type.group(1).strip()
        for kw in ["Course Title", "Course Code", "Semester", "Credits", "Teaching Scheme", "CIE", "ESE"]:
            if kw in type_raw:
                type_raw = type_raw.split(kw)[0].strip()
        if type_raw:
            type_of_course = type_raw[:60]

    # Semester
    match_sem = re.search(r'Semester\s+([IVXLCDM\d]+)', full_text, re.IGNORECASE)
    if match_sem:
        semester = match_sem.group(1).strip()

    # Program
    match_prog = re.search(r'Program\s+([^\n]+)', full_text, re.IGNORECASE)
    if match_prog:
        prog_raw = match_prog.group(1).strip()
        if "Course Title" in prog_raw:
            prog_raw = prog_raw.split("Course Title")[0].strip()
        program = prog_raw[:250]

    # CIE Marks
    match_cie = re.search(r'(?:CIE Marks|Continuous Internal Evaluation|CIE)\s*[:\-]?\s*(\d+)', full_text, re.IGNORECASE)
    if match_cie:
        cie_marks = int(match_cie.group(1))

    # ESE Marks
    match_ese = re.search(r'(?:ESE Marks|End Semester Examination|End Sem Exam|ESE)\s*[:\-]?\s*(\d+)', full_text, re.IGNORECASE)
    if match_ese:
        ese_marks = int(match_ese.group(1))

    # Credits
    match_cred = re.search(r'Credits\s+([\d\.]+)', full_text, re.IGNORECASE)
    if match_cred:
        credits_val = float(match_cred.group(1))

    # Course Code
    match_code = re.search(r'Course Code\s*[:\-]?\s*([A-Za-z0-9\-]+)', full_text, re.IGNORECASE)
    if match_code:
        course_code = match_code.group(1).strip()

    # Course Title
    match_title = re.search(r'Course Title\s+([^\n]+)', full_text, re.IGNORECASE)
    if match_title:
        title_raw = match_title.group(1).strip()
        if "Course Code" in title_raw:
            title_raw = title_raw.split("Course Code")[0].strip()
        course_title = title_raw[:250]

    # L:T:P:R Teaching Scheme
    match_ltpr = re.search(r'Teaching Scheme\s*\(L:\s*T:P:R\)\s*([\d:]+)', full_text, re.IGNORECASE)
    if not match_ltpr:
        match_ltpr = re.search(r'Teaching Scheme\s+([\d:]+)', full_text, re.IGNORECASE)
    if match_ltpr:
        ltpr_val = match_ltpr.group(1).strip()

    # Contact Hours
    match_ch = re.search(r'(?:Contact Hours|Total Hours|Hours)\s*[:\-]?\s*(\d+)', full_text, re.IGNORECASE)
    if match_ch:
        total_hours = int(match_ch.group(1))

    # Course Outcomes (COs)
    cos = []
    co_pattern = r'^\s*(CO\d+)\s+(.*?)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create)\s*$'
    current_co = None

    for line in standard_text.split('\n'):
        match = re.match(co_pattern, line, re.IGNORECASE)
        if match:
            if current_co:
                cos.append(current_co)
            current_co = {
                'id': match.group(1).upper(),
                'desc_parts': [match.group(2).strip()],
                'cognitive_level': match.group(3).strip()
            }
        else:
            if re.match(r'^\s*(CO-PO|COURSE|Legends|Teaching|Assessment|Syllabus|Suggested|Examination|Expt No|Module)', line, re.IGNORECASE):
                if current_co:
                    cos.append(current_co)
                current_co = None
                continue
            if current_co and line.strip():
                if len(line.strip()) < 120 and not "Page #" in line:
                    current_co['desc_parts'].append(line.strip())

    if current_co:
        cos.append(current_co)

    formatted_cos = []
    for c in cos:
        desc_full = " ".join(c['desc_parts']).strip()
        desc_full = re.sub(r'\s+', ' ', desc_full)
        formatted_cos.append({
            'id': c['id'],
            'description': desc_full,
            'cognitive_level': c['cognitive_level']
        })
    cos = formatted_cos

    # Fallback COs if parsing yielded empty
    if not cos:
        cos = [
            {'id': 'CO1', 'description': 'Illustrate knowledge of polygons, conic sections, and development of surfaces.', 'cognitive_level': 'Apply'},
            {'id': 'CO2', 'description': 'Demonstrate and develop orthographic projections and sectional views of engineering objects.', 'cognitive_level': 'Apply'},
            {'id': 'CO3', 'description': 'Familiarize with CAD software commands and draw 2D orthographic/sectional views.', 'cognitive_level': 'Apply'},
            {'id': 'CO4', 'description': 'Develop orthographic & sectional views in CAD software with dimensioning and text.', 'cognitive_level': 'Apply'}
        ]

    # CO-PO Matrix
    copo_matrix = {}
    matrix_lines = re.findall(r'^\s*(CO\d+)\s+([0-9\-\s]+)', standard_text, re.MULTILINE)
    for ml in matrix_lines:
        co_tag = ml[0].upper()
        cols = re.split(r'\s+', ml[1].strip())
        padded_cols = []
        for val in cols[:11]:
            padded_cols.append(val if val in ['1', '2', '3'] else '-')
        while len(padded_cols) < 11:
            padded_cols.append('-')

        po_mapping = {}
        for p in range(1, 12):
            po_mapping[f"PO{p}"] = padded_cols[p-1]
        copo_matrix[co_tag] = po_mapping

    if not copo_matrix:
        copo_matrix = {
            'CO1': {'PO1':'3', 'PO2':'2', 'PO3':'1', 'PO4':'3', 'PO5':'2', 'PO6':'-', 'PO7':'-', 'PO8':'-', 'PO9':'-', 'PO10':'-', 'PO11':'1'},
            'CO2': {'PO1':'3', 'PO2':'3', 'PO3':'2', 'PO4':'3', 'PO5':'2', 'PO6':'-', 'PO7':'-', 'PO8':'-', 'PO9':'-', 'PO10':'-', 'PO11':'1'},
            'CO3': {'PO1':'3', 'PO2':'2', 'PO3':'3', 'PO4':'3', 'PO5':'3', 'PO6':'-', 'PO7':'-', 'PO8':'-', 'PO9':'-', 'PO10':'-', 'PO11':'1'},
            'CO4': {'PO1':'3', 'PO2':'2', 'PO3':'3', 'PO4':'3', 'PO5':'3', 'PO6':'-', 'PO7':'-', 'PO8':'-', 'PO9':'-', 'PO10':'-', 'PO11':'1'}
        }

    # Experiments / Drawing Exercises
    exercises = []
    exp_matches = re.findall(r'Drawing\s+([^\n]+)', standard_text)
    
    # Custom extraction of experiments table from syllabus
    lines = standard_text.split('\n')
    current_module = "Module I"
    for line in lines:
        if "Module I" in line: current_module = "Module I"
        elif "Module II" in line: current_module = "Module II"
        elif "Module III" in line: current_module = "Module III"
        elif "Module IV" in line: current_module = "Module IV"

        if re.search(r'Drawing|Familiarize|Developing|Sectional views', line, re.IGNORECASE) and ("CO1" in line or "CO2" in line or "CO3" in line or "CO4" in line):
            co_match = re.search(r'(CO\d+)', line)
            co_id = co_match.group(1) if co_match else "CO1"
            hrs_match = re.search(r'(\d+)\s*$', line)
            hrs = float(hrs_match.group(1)) if hrs_match else 3.0
            
            exercises.append({
                'exercise_no': f"EXE-0{len(exercises)+1}",
                'module': current_module,
                'title': line[:100].strip(),
                'co_id': co_id,
                'hours': hrs
            })

    if not exercises:
        exercises = [
            {'exercise_no': 'EXE-01', 'module': 'Module I', 'title': 'Drawing Regular Polygons (Pentagon & Hexagon)', 'co_id': 'CO1', 'hours': 3.0},
            {'exercise_no': 'EXE-02', 'module': 'Module I', 'title': 'Drawing Conic Sections (Ellipse by Rectangular & Concentric Circle Method)', 'co_id': 'CO1', 'hours': 3.0},
            {'exercise_no': 'EXE-03', 'module': 'Module I', 'title': 'Drawing Development of Surfaces (Prism & Cylinder)', 'co_id': 'CO1', 'hours': 3.0},
            {'exercise_no': 'EXE-04', 'module': 'Module II', 'title': 'Drawing Basic Projections of Points & Lines in Quadrants', 'co_id': 'CO2', 'hours': 6.0},
            {'exercise_no': 'EXE-05', 'module': 'Module II', 'title': 'Drawing Orthographic Projections & Sectional Views of Engineering Objects', 'co_id': 'CO2', 'hours': 6.0},
            {'exercise_no': 'EXE-06', 'module': 'Module III', 'title': 'CAD Software Basics & Familiarization of Draw and Modify Commands', 'co_id': 'CO3', 'hours': 6.0},
            {'exercise_no': 'EXE-07', 'module': 'Module III', 'title': 'CAD Line Properties, Layers, Text, and Dimensioning Practice', 'co_id': 'CO3', 'hours': 6.0},
            {'exercise_no': 'EXE-08', 'module': 'Module IV', 'title': 'Developing Orthographic Views of Components in CAD', 'co_id': 'CO4', 'hours': 6.0},
            {'exercise_no': 'EXE-09', 'module': 'Module IV', 'title': 'Developing Sectional Views & Plotting CAD Drawings', 'co_id': 'CO4', 'hours': 6.0}
        ]

    modules = [
        {'module_id': 'I', 'title': 'Engineering Graphics Fundamentals & Conic Sections', 'hours': 9.0, 'content': 'Regular Polygons, Ellipse, Parabola, Development of Surfaces'},
        {'module_id': 'II', 'title': 'Projections & Sectional Views', 'hours': 12.0, 'content': 'Projections of Points, Lines, Orthographic Projections, Sectional Views'},
        {'module_id': 'III', 'title': 'Computer Aided Drafting (CAD) Basics', 'hours': 12.0, 'content': 'CAD editor, Draw/Modify commands, Line properties, Text, Dimensions'},
        {'module_id': 'IV', 'title': 'CAD 2D Drafting & Plotting', 'hours': 12.0, 'content': 'Orthographic components in CAD, Sectional views in CAD, Printing/Plotting'}
    ]

    result = {
        'course_code': course_code,
        'course_title': course_title,
        'program': program,
        'semester': semester,
        'type_of_course': type_of_course,
        'credits': credits_val,
        'teaching_scheme': ltpr_val,
        'cie_marks': cie_marks,
        'ese_marks': ese_marks,
        'total_hours': total_hours,
        'cos': cos,
        'copo_matrix': copo_matrix,
        'modules': modules,
        'exercises': exercises
    }
    return result

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({'status': 'ERROR', 'message': 'No PDF file path provided.'}))
        sys.exit(1)
        
    pdf_file_path = sys.argv[1]
    try:
        data = parse_drawing_syllabus(pdf_file_path)
        print(json.dumps({'status': 'SUCCESS', 'data': data}, ensure_ascii=True))
    except Exception as e:
        print(json.dumps({'status': 'ERROR', 'message': str(e)}, ensure_ascii=True))
