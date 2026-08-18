<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$classrooms = [
    'AU_2024_2027',
    'CE_2024_2027',
    'CT_2024_2027',
    'EL_2024_2027',
    'ME_2024_2027',
    'EEE_2024_2027',
];

echo "=== ALL S5 CLASSROOMS IN CLASS_MANAGEMENT ===\n";
$cms = DB::table('class_management')->whereIn('classroom_id', $classrooms)->get();
foreach ($cms as $c) {
    echo "ID: {$c->classroom_id} | Branch: {$c->branch} | Year: {$c->batch_year} | Semester: {$c->current_semester}\n";
}

echo "\n=== ALL S5 STUDENT COUNTS IN DB ===\n";
$grandTotal = 0;
foreach ($classrooms as $cid) {
    $cnt = DB::table('students')->where('classroom_id', $cid)->count();
    $grandTotal += $cnt;
    echo "Classroom $cid: $cnt students\n";
}
echo "GRAND TOTAL S5 STUDENTS IN DB: $grandTotal\n";

echo "\n=== SAMPLES (FIRST STUDENT IN NEW S5 BATCHES) ===\n";
foreach (['EL_2024_2027', 'ME_2024_2027', 'EEE_2024_2027'] as $cid) {
    $first = DB::table('students')->where('classroom_id', $cid)->orderBy('roll_no')->first();
    if ($first) {
        echo "[$cid] Roll {$first->roll_no} | Reg: {$first->reg_no} | Adm: {$first->adm_no} | Name: {$first->name} | SBTE: {$first->sbte_reg_no}\n";
    }
}
