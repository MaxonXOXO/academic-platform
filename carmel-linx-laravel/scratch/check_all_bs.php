<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ALL BATCH SUBJECTS CLASSROOM_IDS ===\n";
$bs = \DB::table('batch_subjects')->select('classroom_id', \DB::raw('count(*) as count'))->groupBy('classroom_id')->get();
foreach ($bs as $b) {
    echo "Classroom: {$b->classroom_id} | Subjects count: {$b->count}\n";
}
