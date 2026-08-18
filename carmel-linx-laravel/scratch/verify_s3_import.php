<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$classrooms = [
    'AU_2025_2028',
    'EL_2025_2028',
    'EEE_2025_2028',
    'CE_2025_2028',
    'CT_2025_2028',
    'ME_2025_2028',
];

echo "=== S3 CLASSROOMS IN CLASS_MANAGEMENT ===\n";
$cms = DB::table('class_management')->whereIn('classroom_id', $classrooms)->get();
foreach ($cms as $c) {
    echo "ID: {$c->classroom_id} | Branch: {$c->branch} | Year: {$c->batch_year} | Semester: {$c->current_semester}\n";
}

echo "\n=== S3 STUDENT COUNTS IN STUDENTS TABLE ===\n";
$grandTotal = 0;
foreach ($classrooms as $cid) {
    $cnt = DB::table('students')->where('classroom_id', $cid)->count();
    $grandTotal += $cnt;
    echo "Classroom $cid: $cnt students\n";
}
echo "GRAND TOTAL IN DB: $grandTotal\n";

echo "\n=== SAMPLES (FIRST STUDENT IN EACH S3 BATCH) ===\n";
foreach ($classrooms as $cid) {
    $first = DB::table('students')->where('classroom_id', $cid)->orderBy('roll_no')->first();
    if ($first) {
        echo "[$cid] Roll {$first->roll_no} | Reg: {$first->reg_no} | Adm: {$first->adm_no} | Name: {$first->name} | SBTE: {$first->sbte_reg_no}\n";
    }
}
