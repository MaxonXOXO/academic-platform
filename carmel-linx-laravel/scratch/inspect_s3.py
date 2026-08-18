import xlrd
import os

files = [
    ('/home/carmel/Downloads/S3 AU.xls', 'AU', 'AU_2025_2028'),
    ('/home/carmel/Downloads/S3 EL.xls', 'EL', 'EL_2025_2028'),
    ('/home/carmel/Downloads/S3 EE.xls', 'EEE', 'EEE_2025_2028'),
    ('/home/carmel/Downloads/S3 CE.xls', 'CE', 'CE_2025_2028'),
    ('/home/carmel/Downloads/S3 CT.xls', 'CT', 'CT_2025_2028'),
    ('/home/carmel/Downloads/S3 ME.xls', 'ME', 'ME_2025_2028'),
]

for path, branch, classroom_id in files:
    print('==================================================')
    print(f'File: {os.path.basename(path)} | Branch: {branch} | Target Classroom: {classroom_id}')
    if not os.path.exists(path):
        print("FILE DOES NOT EXIST!")
        continue
    wb = xlrd.open_workbook(path)
    sheet = wb.sheet_by_index(0)
    print(f'Rows: {sheet.nrows}, Cols: {sheet.ncols}')
    for r in range(min(sheet.nrows, 10)):
        row_vals = [sheet.cell_value(r, c) for c in range(sheet.ncols)]
        print(f'Row {r:02d}: {row_vals}')
