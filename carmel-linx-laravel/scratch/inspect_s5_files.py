import xlrd
import os

files = [
    ('/home/carmel/Downloads/S5 EL.xls', 'EL', 'EL_2024_2027'),
    ('/home/carmel/Downloads/S5 ME.xls', 'ME', 'ME_2024_2027'),
    ('/home/carmel/Downloads/S5 EE.xls', 'EEE', 'EEE_2024_2027'),
]

for path, branch, classroom_id in files:
    print('==================================================')
    print(f'File: {os.path.basename(path)} | Branch: {branch} | Target Classroom: {classroom_id}')
    wb = xlrd.open_workbook(path)
    sheet = wb.sheet_by_index(0)
    print(f'Rows: {sheet.nrows}, Cols: {sheet.ncols}')
    for r in range(min(sheet.nrows, 10)):
        row_vals = [sheet.cell_value(r, c) for c in range(sheet.ncols)]
        print(f'Row {r:02d}: {row_vals}')
