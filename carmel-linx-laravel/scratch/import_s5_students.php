<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$jsonPath = __DIR__ . '/parsed_s5_students.json';
if (!file_exists($jsonPath)) {
    die("JSON file not found: $jsonPath\n");
}

$data = json_decode(file_get_contents($jsonPath), true);
$ceStudents = $data['CE'] ?? [];
$ctStudents = $data['CT'] ?? [];

echo "Loaded " . count($ceStudents) . " CE students and " . count($ctStudents) . " CT students.\n";

DB::beginTransaction();

try {
    // 1. Ensure class_management entries exist
    DB::table('class_management')->updateOrInsert(
        ['classroom_id' => 'CE_2024_2027'],
        [
            'branch' => 'CE',
            'batch_year' => 2024,
            'current_semester' => 5,
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    DB::table('class_management')->updateOrInsert(
        ['classroom_id' => 'CT_2024_2027'],
        [
            'branch' => 'CT',
            'batch_year' => 2024,
            'current_semester' => 5,
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    $defaultPassword = Hash::make('Carmel@123');

    $importedCE = 0;
    foreach ($ceStudents as $s) {
        DB::table('students')->updateOrInsert(
            ['reg_no' => $s['reg_no']],
            [
                'adm_no' => $s['adm_no'],
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => $defaultPassword,
                'phone' => $s['phone'] ?: null,
                'branch' => 'CE',
                'admission_year' => 2024,
                'roll_no' => $s['roll_no'],
                'classroom_id' => 'CE_2024_2027',
                'semester' => 5,
                'sbte_reg_no' => $s['sbte_reg_no'] ?: null,
                'status' => 'Approved',
                'academic_status' => 'Active',
                'admission_type' => 'Regular',
                'residential_status' => 'Day Scholar',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $importedCE++;
    }

    $importedCT = 0;
    foreach ($ctStudents as $s) {
        DB::table('students')->updateOrInsert(
            ['reg_no' => $s['reg_no']],
            [
                'adm_no' => $s['adm_no'],
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => $defaultPassword,
                'phone' => $s['phone'] ?: null,
                'branch' => 'CT',
                'admission_year' => 2024,
                'roll_no' => $s['roll_no'],
                'classroom_id' => 'CT_2024_2027',
                'semester' => 5,
                'sbte_reg_no' => $s['sbte_reg_no'] ?: null,
                'status' => 'Approved',
                'academic_status' => 'Active',
                'admission_type' => 'Regular',
                'residential_status' => 'Day Scholar',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $importedCT++;
    }

    DB::commit();
    echo "Successfully imported/updated $importedCE CE S5 students and $importedCT CT S5 students!\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR during import: " . $e->getMessage() . "\n";
    exit(1);
}
