#!/usr/bin/env python3
import sys
import json
import re
import os
import glob

# Ensure user site packages are available even under www-data
for p in glob.glob('/home/*/.local/lib/python*/site-packages'):
    if p not in sys.path:
        sys.path.insert(0, p)

try:
    import pypdf
except ImportError:
    print(json.dumps({"status": "ERROR", "message": "pypdf library not found on system."}))
    sys.exit(1)

def parse_sbte_pdf(pdf_path):
    if not os.path.exists(pdf_path):
        return {"status": "ERROR", "message": f"File not found: {pdf_path}"}

    try:
        reader = pypdf.PdfReader(pdf_path)
    except Exception as e:
        return {"status": "ERROR", "message": f"Could not read PDF: {str(e)}"}

    full_text = '\n'.join(p.extract_text() or '' for p in reader.pages)

    if not full_text.strip():
        return {"status": "ERROR", "message": "The PDF does not contain extractable text."}

    # Extract Header
    prog_m = re.search(r'Programme:\s*(.*)', full_text)
    course_m = re.search(r'Course:\s*(.*?)\s*\((\w+)\)\s*Semester:\s*(\w+)', full_text)
    if not course_m:
        course_m = re.search(r'Course:\s*(.*?)\s*Semester:\s*(\w+)', full_text)
    faculty_m = re.search(r'Faculty:\s*(.*)', full_text)

    course_title = course_m.group(1).strip() if course_m else ''
    course_code = course_m.group(2).strip() if (course_m and len(course_m.groups()) >= 3) else ''
    semester = course_m.group(3).strip() if (course_m and len(course_m.groups()) >= 3) else (course_m.group(2).strip() if course_m else '')
    faculty_name = faculty_m.group(1).strip() if faculty_m else ''
    programme = prog_m.group(1).strip() if prog_m else ''

    # Clean multi-line page breaks that might interfere with rows
    matches = re.findall(r'(?ms)^(\d+)\.\s+(\d{2}-\d{2}-\d{4})\s+([\d,\s]+?)\s*(.*?)(?=\n\d+\.|\Z)', full_text)

    sessions = []
    for m in matches:
        sl = int(m[0])
        raw_dt = m[1].strip()
        d_parts = raw_dt.split('-')
        iso_date = f'{d_parts[2]}-{d_parts[1]}-{d_parts[0]}' if len(d_parts) == 3 else raw_dt

        raw_hours = m[2].strip()
        hours = [int(h.strip()) for h in re.findall(r'\d+', raw_hours)]

        raw_content = m[3].strip()
        if faculty_name:
            raw_content = re.sub(r'\s*' + re.escape(faculty_name) + r'.*$', '', raw_content, flags=re.IGNORECASE).strip()
        raw_content = re.sub(r'\s+\d+\s*$', '', raw_content).strip()
        content = ' '.join(raw_content.split())

        if content.startswith(','):
            extra_h = re.findall(r'\d+', content[:6])
            for eh in extra_h:
                eh_int = int(eh)
                if eh_int not in hours:
                    hours.append(eh_int)
            content = re.sub(r'^[\d,\s]+', '', content).strip()

        if not hours:
            hours = [1]

        sessions.append({
            'sl_no': sl,
            'date': iso_date,
            'display_date': raw_dt,
            'hours': sorted(hours),
            'hours_count': len(hours),
            'contents': content,
            'faculty': faculty_name
        })

    return {
        'status': 'SUCCESS',
        'programme': programme,
        'course_title': course_title,
        'course_code': course_code,
        'semester': semester,
        'faculty': faculty_name,
        'total_sessions': len(sessions),
        'total_hours': sum(s['hours_count'] for s in sessions),
        'sessions': sessions
    }

if __name__ == '__main__':
    if len(sys.argv) < 2:
        print(json.dumps({"status": "ERROR", "message": "Usage: parse_sbte_subject_log.py <pdf_path>"}))
        sys.exit(1)

    pdf_file = sys.argv[1]
    res = parse_sbte_pdf(pdf_file)
    print(json.dumps(res))
