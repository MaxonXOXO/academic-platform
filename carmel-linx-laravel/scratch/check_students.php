<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SAMPLE STUDENTS FROM DIFFERENT BRANCHES ===\n";
$samples = \DB::table('students')->select('reg_no', 'adm_no', 'branch', 'classroom_id', 'admission_year', 'email', 'sbte_reg_no', 'roll_no')->get()->groupBy('classroom_id');
foreach ($samples as $cid => $studs) {
    echo "Classroom: $cid (Count: " . count($studs) . ")\n";
    print_r($studs->take(3)->toArray());
}
