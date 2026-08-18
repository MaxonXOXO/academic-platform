<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== BATCH SUBJECTS FOR 2024 BATCHES ===\n";
$bs = \DB::table('batch_subjects')->where('classroom_id', 'like', '%2024%')->get();
echo "Count: " . count($bs) . "\n";
print_r($bs->toArray());
