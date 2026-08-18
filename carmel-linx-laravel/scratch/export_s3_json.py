import xlrd
import re
import json
import os

def clean_val(val):
    if val is None:
        return ''
    if isinstance(val, float):
        if val.is_integer():
            return str(int(val))
        return str(val)
    return str(val).strip()

def parse_excel(path, branch_code, classroom_id):
    wb = xlrd.open_workbook(path)
    sheet = wb.sheet_by_index(0)
    students = []
    
    for r in range(sheet.nrows):
        row = [sheet.cell_value(r, c) for c in range(sheet.ncols)]
        c0 = clean_val(row[0])
        c1 = clean_val(row[1])
        c2 = clean_val(row[2])
        c3 = clean_val(row[3])
        c4 = clean_val(row[4])
        c5 = clean_val(row[5])
        
        # Header or title row check
        if not c0 or c0.lower() in ['roll. no.', 'roll.no.', 'roll no', 'roll. no'] or 'carmel' in c0.lower() or 'programme' in c0.lower() or 'semester' in c0.lower() or 'registration list' in c0.lower():
            continue
            
        try:
            roll_no = int(float(c0))
        except ValueError:
            continue
            
        adm_no_raw = c1
        adm_no = re.sub(r'/+', '/', adm_no_raw)
        
        sbte_reg_no = c2 if c2 and c2 != '0' else ''
        name = c3.upper().strip()
        phone = c4
        email = c5
        
        adm_num_match = re.search(r'^\d+', adm_no)
        adm_num = adm_num_match.group(0) if adm_num_match else adm_no
        
        reg_no = f"25{branch_code}{adm_num}"
        
        if not email:
            email = f"{adm_num}@carmelpoly.in"
            
        students.append({
            'roll_no': roll_no,
            'adm_no': adm_no,
            'sbte_reg_no': sbte_reg_no,
            'name': name,
            'phone': phone,
            'email': email,
            'reg_no': reg_no,
            'branch': branch_code,
            'classroom_id': classroom_id,
            'admission_year': 2025,
            'semester': 3,
        })
        
    return students

files = [
    ('/home/carmel/Downloads/S3 AU.xls', 'AU', 'AU_2025_2028'),
    ('/home/carmel/Downloads/S3 EL.xls', 'EL', 'EL_2025_2028'),
    ('/home/carmel/Downloads/S3 EE.xls', 'EEE', 'EEE_2025_2028'),
    ('/home/carmel/Downloads/S3 CE.xls', 'CE', 'CE_2025_2028'),
    ('/home/carmel/Downloads/S3 CT.xls', 'CT', 'CT_2025_2028'),
    ('/home/carmel/Downloads/S3 ME.xls', 'ME', 'ME_2025_2028'),
]

payload = {}
total_students = 0

for path, branch, cid in files:
    studs = parse_excel(path, branch, cid)
    payload[branch] = studs
    total_students += len(studs)
    print(f"{branch} ({cid}): Extracted {len(studs)} students")

with open('/home/carmel/Downloads/Carmel-linx/academic-platform/carmel-linx-laravel/scratch/parsed_s3_students.json', 'w') as f:
    json.dump(payload, f, indent=2)

print(f"\nTOTAL S3 STUDENTS EXTRACTED ACROSS ALL 6 BRANCHES: {total_students}")
