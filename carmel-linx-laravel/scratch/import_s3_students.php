<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$jsonPath = __DIR__ . '/parsed_s3_students.json';
if (!file_exists($jsonPath)) {
    die("JSON file not found: $jsonPath\n");
}

$data = json_decode(file_get_contents($jsonPath), true);

$branches = [
    'AU'  => 'AU_2025_2028',
    'EL'  => 'EL_2025_2028',
    'EEE' => 'EEE_2025_2028',
    'CE'  => 'CE_2025_2028',
    'CT'  => 'CT_2025_2028',
    'ME'  => 'ME_2025_2028',
];

DB::beginTransaction();

try {
    $defaultPassword = Hash::make('Carmel@123');
    $summary = [];
    $totalImported = 0;

    foreach ($branches as $branchCode => $classroomId) {
        // Ensure class_management entry exists
        DB::table('class_management')->updateOrInsert(
            ['classroom_id' => $classroomId],
            [
                'branch' => $branchCode,
                'batch_year' => 2025,
                'current_semester' => 3,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $students = $data[$branchCode] ?? [];
        $count = 0;

        foreach ($students as $s) {
            DB::table('students')->updateOrInsert(
                ['reg_no' => $s['reg_no']],
                [
                    'adm_no' => $s['adm_no'],
                    'name' => $s['name'],
                    'email' => $s['email'],
                    'password' => $defaultPassword,
                    'phone' => $s['phone'] ?: null,
                    'branch' => $branchCode,
                    'admission_year' => 2025,
                    'roll_no' => $s['roll_no'],
                    'classroom_id' => $classroomId,
                    'semester' => 3,
                    'sbte_reg_no' => $s['sbte_reg_no'] ?: null,
                    'status' => 'Approved',
                    'academic_status' => 'Active',
                    'admission_type' => 'Regular',
                    'residential_status' => 'Day Scholar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }

        $summary[$branchCode] = $count;
        $totalImported += $count;
    }

    DB::commit();

    echo "=== S3 STUDENT IMPORT SUCCESSFUL ===\n";
    foreach ($summary as $b => $cnt) {
        echo "Branch $b ({$branches[$b]}): $cnt students\n";
    }
    echo "TOTAL S3 STUDENTS IMPORTED/UPDATED: $totalImported\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR during import: " . $e->getMessage() . "\n";
    exit(1);
}
