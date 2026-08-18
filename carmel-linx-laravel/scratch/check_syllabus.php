<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SYLLABUS REGISTRY FOR CT REV 2021 / SEMESTER 5 ===\n";
$syll = \DB::table('syllabus_registry')
    ->where('revision_year', 'like', '%2021%')
    ->orWhere('subject_code', 'like', '50%')
    ->orWhere('subject_code', 'like', 'CT%')
    ->get();

echo "Count: " . count($syll) . "\n";
foreach ($syll as $s) {
    echo "{$s->subject_code} | {$s->subject_name} | Rev: {$s->revision_year}\n";
}
