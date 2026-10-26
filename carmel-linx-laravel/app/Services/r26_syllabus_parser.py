import sys
import json
import re
from pypdf import PdfReader

CANONICAL_MODULES = {
    '1051': {'I': 'Introduction to Automobile', 'II': 'Basics of Thermodynamics', 'III': 'Engine Basics', 'IV': 'Fuels'},
    '1011': {'I': 'Fundamentals of Civil Engineering', 'II': 'Masonry and Finishes', 'III': 'Introduction to Building components', 'IV': 'Building services and Fire Safety'},
    '1131': {'I': 'Problem Solving & Python Fundamentals', 'II': 'Control Structures & Looping Structure', 'III': 'Strings & Data Structures (List, Tuple, Set & Dictionary)', 'IV': 'Functional and Modular Programming in Python'},
    '1031': {'I': 'Fundamentals of Electrical systems and combinations of resistors', 'II': 'Basic parameters of AC systems and Electrical safety', 'III': 'Different types of passive components', 'IV': 'Basic Electronic Components and Logic gates'},
    '1021': {'I': 'Introduction to Mechanical Engineering & Safety', 'II': 'Manufacturing Processes & Materials', 'III': 'Power Transmission & Thermal Engineering', 'IV': 'Energy Sources & Power Plants'},
    '1041': {'I': 'Introduction to Electronics, Signals and Measurements', 'II': 'Semiconductor Devices and Applications', 'III': 'Linear Integrated Circuits & Sensors', 'IV': 'Digital Electronics & Communication Basics'},
    '2003A': {'I': 'Fundamentals of Chemistry, Solutions & Thermodynamics', 'II': 'Analytical Chemistry, Water Treatment & Acids-Bases', 'III': 'Electrochemistry, Corrosion and Emerging Battery Technologies', 'IV': 'Engineering Materials & Green Chemistry'},
    '2003': {'I': 'Fundamentals of Chemistry, Solutions & Thermodynamics', 'II': 'Analytical Chemistry, Water Treatment & Acids-Bases', 'III': 'Electrochemistry, Corrosion and Emerging Battery Technologies', 'IV': 'Engineering Materials & Green Chemistry'},
    '2002A': {'I': 'Mechanics', 'II': 'Properties of Matter and Thermodynamics', 'III': 'Rotational and Wave Motion', 'IV': 'Optics & Electromagnetism'},
    '2002': {'I': 'Mechanics', 'II': 'Properties of Matter and Thermodynamics', 'III': 'Rotational and Wave Motion', 'IV': 'Optics & Electromagnetism'},
    '1001': {'I': 'Prose', 'II': 'Poetry', 'III': 'Listening and Speaking', 'IV': 'Writing'},
    '1002': {'I': 'Matrices & Determinants', 'II': 'Trigonometry', 'III': 'Coordinate Geometry', 'IV': 'Differential Calculus'},
    '1008': {'I': 'Hardware, Linux GUI & Connectivity', 'II': 'Office Productivity & Cloud Collaboration', 'III': 'Data Analysis, Visualization & AI Dashboards', 'IV': 'Generative AI Tools & Digital Graphic Design'},
    '1009': {'I': 'Physical Fitness & Wellness', 'II': 'Health Education & Nutrition', 'III': 'Sports Skills & Games', 'IV': 'First Aid, Safety & Disaster Management'}
}

def parse_syllabus(pdf_path):
    reader = PdfReader(pdf_path)
    full_text = ""
    standard_text = ""
    for page in reader.pages:
        full_text += page.extract_text(extraction_mode="layout") + "\n\n"
        standard_text += page.extract_text() + "\n\n"

    # 1. Parse Meta Details
    course_code = "1002"
    course_title = "Engineering Mathematics"
    credits_val = 4.0
    ltpr_val = "3:1:0:0"
    cie_marks = 40
    ese_marks = 60
    total_hours = 60
    type_of_course = "Theory"
    semester = "I"
    program = "Diploma Engineering"

    # Type of Course
    match_type = re.search(r'Type of Course\s+(Practicum|Theory|Practical|Drawing|PBL|PSI|Lab|Laboratory)', standard_text, re.IGNORECASE)
    if not match_type:
        match_type = re.search(r'Type of Course\s+(Practicum|Theory|Practical|Drawing|PBL|PSI|Lab|Laboratory)', full_text, re.IGNORECASE)
    if match_type:
        type_of_course = match_type.group(1).strip()
    else:
        match_type_raw = re.search(r'Type of Course\s+([A-Za-z]+)', standard_text, re.IGNORECASE)
        if match_type_raw:
            type_of_course = match_type_raw.group(1).strip()

    # Semester
    match_sem = re.search(r'Semester\s+([IVXLCDM\d]+)', standard_text, re.IGNORECASE)
    if not match_sem:
        match_sem = re.search(r'Semester\s+([IVXLCDM\d]+)', full_text, re.IGNORECASE)
    if match_sem:
        semester = match_sem.group(1).strip()

    # Course Code
    match_code = re.search(r'Course Code\s+([A-Za-z0-9\-]+)', standard_text, re.IGNORECASE)
    if not match_code:
        match_code = re.search(r'Course Code\s+([A-Za-z0-9\-]+)', full_text, re.IGNORECASE)
    if match_code:
        c_raw = match_code.group(1).strip()
        if "semester" in c_raw.lower():
            c_raw = re.split(r'semester', c_raw, flags=re.IGNORECASE)[0].strip()
        course_code = c_raw

    # Course Title
    match_title = re.search(r'Course Title\s+([^\n]+)', standard_text, re.IGNORECASE)
    if not match_title:
        match_title = re.search(r'Course Title\s+([^\n]+)', full_text, re.IGNORECASE)
    if match_title:
        title_raw = match_title.group(1).strip()
        if "Course Code" in title_raw:
            title_raw = title_raw.split("Course Code")[0].strip()
        if "Semester" in title_raw:
            title_raw = title_raw.split("Semester")[0].strip()
        course_title = title_raw[:250]

    # Program
    match_prog = re.search(r'Program\s+([^\n]+)', standard_text, re.IGNORECASE)
    if not match_prog:
        match_prog = re.search(r'Program\s+([^\n]+)', full_text, re.IGNORECASE)
    if match_prog:
        prog_raw = match_prog.group(1).strip()
        if "Course Title" in prog_raw:
            prog_raw = prog_raw.split("Course Title")[0].strip()
        program = prog_raw[:250]

    # Credits
    match_cred = re.search(r'Credits\s+([\d\.]+)', standard_text, re.IGNORECASE)
    if not match_cred:
        match_cred = re.search(r'Credits\s+([\d\.]+)', full_text, re.IGNORECASE)
    if match_cred:
        credits_val = float(match_cred.group(1))
    elif type_of_course == "Practicum":
        credits_val = 4.5

    # Teaching Scheme
    match_ltpr = re.search(r'Teaching Scheme(?:\s*\([^\)]+\))?\s*([\d:]+)', standard_text, re.IGNORECASE)
    if not match_ltpr:
        match_ltpr = re.search(r'Teaching Scheme(?:\s*\([^\)]+\))?\s*([\d:]+)', full_text, re.IGNORECASE)
    if match_ltpr:
        ltpr_val = match_ltpr.group(1).strip()
    elif type_of_course == "Practicum":
        ltpr_val = "3:0:3:0"

    # Contact Hours
    match_ch = re.search(r'Contact Hours\s+(\d+)', standard_text, re.IGNORECASE)
    if not match_ch:
        match_ch = re.search(r'Contact Hours\s+(\d+)', full_text, re.IGNORECASE)
    if match_ch:
        total_hours = int(match_ch.group(1))
    elif type_of_course == "Practicum":
        total_hours = 90

    # CIE Marks
    match_cie = re.search(r'CIE Marks\s+(\d+)', standard_text, re.IGNORECASE)
    if not match_cie:
        match_cie = re.search(r'CIE Marks\s+(\d+)', full_text, re.IGNORECASE)
    if match_cie:
        cie_marks = int(match_cie.group(1))

    # ESE Marks
    match_ese = re.search(r'ESE Marks\s+(\d+)', standard_text, re.IGNORECASE)
    if not match_ese:
        match_ese = re.search(r'ESE Marks\s+(\d+)', full_text, re.IGNORECASE)
    if match_ese:
        ese_marks = int(match_ese.group(1))
    elif type_of_course == "Practicum":
        ese_marks = 100

    # 2. Parse Course Outcomes (CO1..CO4)
    cos = []
    co_pattern = r'^\s*(CO\d+)\s+(.*?)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create)\s*$'
    current_co = None
    for line in full_text.split('\n'):
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
            if re.match(r'^\s*(CO-PO|COURSE|Legends|Teaching|Assessment|Syllabus|Suggested|Examination|CA\d+|Week)', line, re.IGNORECASE):
                if current_co:
                    cos.append(current_co)
                current_co = None
                continue
            if current_co and line.strip():
                desc_slice = line.strip()
                desc_slice = re.sub(r'\s+(Remember|Understand|Apply|Analyze|Evaluate|Create)$', '', desc_slice, flags=re.IGNORECASE)
                if desc_slice:
                    current_co['desc_parts'].append(desc_slice)
    if current_co:
        cos.append(current_co)

    formatted_cos = []
    for c in cos:
        desc_full = " ".join(c['desc_parts']).strip()
        desc_full = re.sub(r'\s+', ' ', desc_full)
        formatted_cos.append({
            'id': c['id'],
            'description': desc_full,
            'statement': desc_full,
            'cognitive_level': c['cognitive_level'],
            'level': f"Cognitive Level: {c['cognitive_level']}"
        })
    cos = formatted_cos

    # 3. Parse CO-PO Matrix (11 PO columns)
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

    # 4. Check if course is Practical / Lab
    is_lab = type_of_course.lower() in ['lab', 'practical', 'laboratory'] or "Practical Outcome" in standard_text or "Experiment name" in standard_text

    modules = []
    experiments = []
    detailed_topics = []

    if is_lab:
        pos = standard_text.find("Detailed Syllabus")
        if pos == -1:
            pos = standard_text.find("Course Outline")
        detailed_text = standard_text[pos:] if pos != -1 else standard_text

        end_pos = detailed_text.find("Open-ended experiment\nObjective")
        if end_pos == -1:
            end_pos = detailed_text.find("Student Activities for Self-Learning")
        if end_pos == -1:
            end_pos = detailed_text.find("Major Equipment")
        if end_pos != -1:
            detailed_text = detailed_text[:end_pos]

        module_chunks = re.split(r'\bModule\s+([IVX]+)\b', detailed_text, flags=re.IGNORECASE)

        for i in range(1, len(module_chunks), 2):
            mod_id = module_chunks[i].strip().upper()
            mod_text = module_chunks[i+1]

            exp_pattern = r'(?:^|\n)\s*(?:(\d+)\s+|(\d+)\n|(Series Test \d+)|(Open-Ended Experiment))\s*(.*?)\s*(CO\d+)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create)\s+(\d+)'
            matches = list(re.finditer(exp_pattern, mod_text, re.DOTALL | re.IGNORECASE))
            mod_exps = []

            for m in matches:
                num = m.group(1) or m.group(2)
                series = m.group(3)
                oee = m.group(4)
                body = m.group(5).strip()
                co = m.group(6).upper()
                tax = m.group(7)
                hrs = int(m.group(8))

                expt_no = f'Expt {num}' if num else (series if series else ('OEE' if oee else 'Expt'))

                if series:
                    title = series
                    desc = body
                elif oee:
                    title = oee
                    desc = body
                else:
                    split_pat = r'\b(1\.\s+|Create\s+|Practice\s+|Use\s+|Design\s+a\s+|Perform\s+|Measure\s+|Execute\s+|Implement\s+)'
                    m_split = re.search(split_pat, body)
                    if m_split and m_split.start() > 2:
                        title = body[:m_split.start()].strip()
                        desc = body[m_split.start():].strip()
                    else:
                        title = body
                        desc = ''

                title = re.sub(r'\s+', ' ', title).strip()
                desc = re.sub(r'\s+', ' ', desc).strip()

                exp_item = {
                    'module_id': mod_id,
                    'expt_no': expt_no,
                    'title': title,
                    'description': desc,
                    'student_learning_outcome': desc,
                    'co': co,
                    'co_id': co,
                    'taxonomy': tax,
                    'hours': hrs,
                    'pedagogy': 'Practical'
                }
                mod_exps.append(exp_item)
                experiments.append(exp_item)
                detailed_topics.append({
                    'topic': title,
                    'learning_outcome': desc,
                    'pedagogy': 'Practical',
                    'co_id': co,
                    'taxonomy': tax,
                    'hours': hrs
                })

            mod_hrs = sum(e['hours'] for e in mod_exps) if mod_exps else 8
            exp_titles = [e['title'] for e in mod_exps if e['expt_no'].startswith('Expt')]

            mod_title = ', '.join(exp_titles[:2]) if exp_titles else f'Module {mod_id}'
            if mod_id == 'I' and 'Hardware' in mod_text:
                mod_title = 'Hardware, Linux GUI & Connectivity'
            elif mod_id == 'II' and 'Documentation' in mod_text:
                mod_title = 'Office Productivity & Cloud Collaboration'
            elif mod_id == 'III' and 'Data Analysis' in mod_text:
                mod_title = 'Data Analysis, Visualization & AI Dashboards'
            elif mod_id == 'IV' and 'Generative' in mod_text:
                mod_title = 'Generative AI Tools & Digital Graphic Design'

            roman_map = {'I': 1, 'II': 2, 'III': 3, 'IV': 4}
            mod_num = roman_map.get(mod_id, len(modules) + 1)
            topic_str = '; '.join([f"{e['expt_no']}: {e['title']}" for e in mod_exps]) if mod_exps else 'Practical laboratory experiments and hands-on exercises.'

            modules.append({
                'module_id': mod_id,
                'module_no': mod_num,
                'title': mod_title,
                'hours': mod_hrs,
                'content': topic_str,
                'topics': topic_str
            })

    else:
        # Standard Theory & Practicum parsing
        pos_detailed = full_text.find("Detailed Syllabus")
        if pos_detailed == -1:
            pos_detailed = full_text.find("Course Outline")
        detailed_text = full_text[pos_detailed:] if pos_detailed != -1 else full_text
        lines = detailed_text.split("\n")

        current_block = {'topic_lines': [], 'slo_lines': [], 'metadata': None}
        row_pattern = r'\b([LTP]|L/T|L/P)\s+(CO\d+)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create|Remembering|Understanding|Applying|Analyzing|Evaluating|Creating)\s+([\d\.]+)\s*$'

        def save_block():
            if current_block['metadata']:
                t = " ".join(current_block['topic_lines']).strip()
                t = re.sub(r'\s+', ' ', t)
                s = " ".join(current_block['slo_lines']).strip()
                s = re.sub(r'\s+', ' ', s)
                m = current_block['metadata']
                mode_code = m[0]
                pedagogy = 'Practical' if mode_code in ['P', 'L/P'] else ('Tutorial' if mode_code == 'T' else 'Lecture')
                detailed_topics.append({
                    'topic': t,
                    'learning_outcome': s,
                    'pedagogy': pedagogy,
                    'co_id': m[1],
                    'taxonomy': m[2],
                    'hours': float(m[3])
                })
            current_block['topic_lines'] = []
            current_block['slo_lines'] = []
            current_block['metadata'] = None

        for line in lines:
            if not line.strip():
                continue
            if any(k in line for k in ["Detailed Syllabus", "Subtopic", "Student Learning", "Diploma Curriculum", "Page #", "Self-Learning", "Major Equipment"]):
                save_block()
                continue
            if re.search(r'\bModule\s+[IVX]+\b', line):
                save_block()
                continue
            m = re.search(row_pattern, line)
            if m:
                save_block()
                current_block['metadata'] = (m.group(1), m.group(2), m.group(3), m.group(4))
                content = line[:m.start()].rstrip()
                gap_m = re.search(r'^(\s{0,12}\S.*?)\s{3,}(\S.*)$', content)
                if gap_m:
                    current_block['topic_lines'].append(gap_m.group(1).strip())
                    current_block['slo_lines'].append(gap_m.group(2).strip())
                else:
                    if len(content) > 35 and content[:30].strip() == '':
                        current_block['slo_lines'].append(content.strip())
                    else:
                        current_block['topic_lines'].append(content.strip())
            else:
                stripped = line.strip()
                leading_spaces = len(line) - len(line.lstrip())
                if leading_spaces >= 30:
                    current_block['slo_lines'].append(stripped)
                else:
                    gap_m = re.search(r'^(\s{0,12}\S.*?)\s{3,}(\S.*)$', line.rstrip())
                    if gap_m:
                        current_block['topic_lines'].append(gap_m.group(1).strip())
                        current_block['slo_lines'].append(gap_m.group(2).strip())
                    else:
                        current_block['topic_lines'].append(stripped)
        save_block()

        # Modules Construction (Always Exactly 4 modules)
        clean_code = course_code.replace("AU-", "").replace("CE-", "").replace("CT-", "").replace("EEE-", "").replace("ME-", "").replace("EL-", "").strip()
        canon = CANONICAL_MODULES.get(clean_code) or CANONICAL_MODULES.get(course_code)

        # Dynamic title extraction from Syllabus - Major Topics
        s_start = standard_text.find("Syllabus – Major Topics")
        if s_start == -1:
            s_start = standard_text.find("Course Outline")
        if s_start == -1:
            s_start = standard_text.find("Major Topics")
        s_end = standard_text.find("Detailed Syllabus")
        if s_end == -1:
            s_end = standard_text.find("Student Activities for Self-Learning")
        major_section = standard_text[s_start:s_end] if s_start != -1 and s_end != -1 else standard_text

        dynamic_titles = {}
        for mid in ['I', 'II', 'III', 'IV']:
            pattern = rf'\bModule\s+{mid}\b\s+([^\n]+(?:\n[^\n]+){{0,2}})'
            cand_m = re.search(pattern, major_section)
            if cand_m:
                cand_lines = [l.strip() for l in cand_m.group(1).split("\n") if l.strip()]
                cparts = []
                for cl in cand_lines:
                    if re.search(r'^(•|\d+\.|\d+\s+\d+|Theory|Laboratory|Student|Instructional|L\s+P|Evolution|Atoms|Units,)', cl, re.IGNORECASE):
                        break
                    if ":" in cl:
                        break
                    if cl in ['L P', 'Title', 'Major Topics', 'Instructional', 'Hours']:
                        continue
                    cparts.append(cl)
                    if len(cparts) >= 2:
                        break
                cand_str = " ".join(cparts).strip()
                cand_str = re.sub(r'\s+', ' ', cand_str)
                cand_str = re.sub(r'^(Introduction to\s+)?(Major Topics|Title)\s*', '', cand_str, flags=re.IGNORECASE).strip()
                if cand_str and 4 <= len(cand_str) <= 85 and not any(k in cand_str.lower() for k in ['laboratory', 'experiments']):
                    dynamic_titles[mid] = cand_str

        roman_map = {'I': 1, 'II': 2, 'III': 3, 'IV': 4}
        for mod_id in ['I', 'II', 'III', 'IV']:
            mod_num = roman_map[mod_id]
            co_tag = f"CO{mod_num}"

            # Title resolution: Canonical list > Dynamic extraction > Fallback
            if canon and mod_id in canon:
                title = canon[mod_id]
            elif mod_id in dynamic_titles:
                title = dynamic_titles[mod_id]
            else:
                if "math" in course_title.lower() or course_code in ["1002", "2002"]:
                    title = CANONICAL_MODULES['1002'][mod_id]
                else:
                    default_titles = {
                        'I': 'Foundational Principles & Concepts',
                        'II': 'Core Methodologies & Applications',
                        'III': 'Advanced Analytical Topics',
                        'IV': 'Applied Implementation & Synthesis'
                    }
                    title = default_titles[mod_id]

            mod_topics = [t['topic'] for t in detailed_topics if t['co_id'] == co_tag and t['topic']]
            seen = set()
            clean_topics = [t for t in mod_topics if not (t in seen or seen.add(t))]
            topic_summary = ", ".join(clean_topics[:8]) if clean_topics else f"Prescribed curriculum topics for Module {mod_id}."

            mod_hrs = sum(t['hours'] for t in detailed_topics if t['co_id'] == co_tag)
            if mod_hrs <= 0:
                mod_hrs = 22 if type_of_course == "Practicum" else 15

            modules.append({
                'module_id': mod_id,
                'module_no': mod_num,
                'title': title,
                'hours': round(mod_hrs, 1),
                'content': topic_summary,
                'topics': topic_summary
            })

        # Experiments Generation for Practicum/Theory
        exp_idx = 1
        for t in detailed_topics:
            if t['pedagogy'] == 'Practical':
                total_hrs = float(t['hours'])
                if total_hrs > 3:
                    num_sessions = int(total_hrs // 3)
                    rem_hrs = total_hrs % 3
                    if rem_hrs > 0:
                        num_sessions += 1
                    for s in range(1, num_sessions + 1):
                        sess_hrs = 3 if (s < num_sessions or rem_hrs == 0) else rem_hrs
                        experiments.append({
                            'experiment_no': f"EXP-{exp_idx:02d}-S{s}",
                            'title': f"{t['topic']} (Session {s})",
                            'student_learning_outcome': t['learning_outcome'],
                            'co_id': t['co_id'],
                            'taxonomy': t['taxonomy'],
                            'hours': sess_hrs
                        })
                else:
                    experiments.append({
                        'experiment_no': f"EXP-{exp_idx:02d}",
                        'title': t['topic'],
                        'student_learning_outcome': t['learning_outcome'],
                        'co_id': t['co_id'],
                        'taxonomy': t['taxonomy'],
                        'hours': total_hrs
                    })
                exp_idx += 1

    # Compile the final result
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
        'experiments': experiments,
        'detailed_topics': detailed_topics
    }
    return result

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({'status': 'ERROR', 'message': 'No PDF file path provided.'}))
        sys.exit(1)

    pdf_file_path = sys.argv[1]
    try:
        data = parse_syllabus(pdf_file_path)
        print(json.dumps({'status': 'SUCCESS', 'data': data}, ensure_ascii=True))
    except Exception as e:
        print(json.dumps({'status': 'ERROR', 'message': str(e)}, ensure_ascii=True))
