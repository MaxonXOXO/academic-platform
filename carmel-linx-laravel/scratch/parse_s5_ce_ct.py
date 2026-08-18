import xlrd
import re
import json

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
        # clean adm_no e.g. "12866//25" -> "12866/25"
        adm_no = re.sub(r'/+', '/', adm_no_raw)
        
        sbte_reg_no = c2 if c2 and c2 != '0' else ''
        name = c3.upper()
        phone = c4
        email = c5
        
        # Extract numeric adm_number for reg_no and default email
        # e.g. "12478/24" -> "12478"
        # e.g. "11629" -> "11629"
        adm_num_match = re.search(r'^\d+', adm_no)
        adm_num = adm_num_match.group(0) if adm_num_match else adm_no
        
        reg_no = f"24{branch_code}{adm_num}"
        
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
            'admission_year': 2024,
            'semester': 5,
        })
        
    return students

ce_students = parse_excel('/home/carmel/Downloads/S5 CE.xls', 'CE', 'CE_2024_2027')
ct_students = parse_excel('/home/carmel/Downloads/S5 CT.xls', 'CT', 'CT_2024_2027')

print(f"CE Extracted: {len(ce_students)} students")
print(f"CT Extracted: {len(ct_students)} students")

print("\n--- FIRST 3 CE ---")
print(json.dumps(ce_students[:3], indent=2))

print("\n--- LAST 3 CE ---")
print(json.dumps(ce_students[-3:], indent=2))

print("\n--- FIRST 3 CT ---")
print(json.dumps(ct_students[:3], indent=2))

print("\n--- LAST 3 CT ---")
print(json.dumps(ct_students[-3:], indent=2))
