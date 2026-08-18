<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CLASS MANAGEMENT S5 2024 ===\n";
$classes = DB::table('class_management')->whereIn('classroom_id', ['CE_2024_2027', 'CT_2024_2027'])->get();
foreach ($classes as $c) {
    echo "ID: {$c->classroom_id} | Branch: {$c->branch} | Year: {$c->batch_year} | Sem: {$c->current_semester}\n";
}

echo "\n=== STUDENT COUNTS ===\n";
$ceCount = DB::table('students')->where('classroom_id', 'CE_2024_2027')->count();
$ctCount = DB::table('students')->where('classroom_id', 'CT_2024_2027')->count();
echo "CE_2024_2027 Total Students: $ceCount\n";
echo "CT_2024_2027 Total Students: $ctCount\n";

echo "\n=== SAMPLE CE STUDENTS ===\n";
$ceSample = DB::table('students')->where('classroom_id', 'CE_2024_2027')->orderBy('roll_no')->take(5)->get();
foreach ($ceSample as $s) {
    echo "Roll: {$s->roll_no} | Reg: {$s->reg_no} | Adm: {$s->adm_no} | Name: {$s->name} | SBTE: {$s->sbte_reg_no} | Email: {$s->email}\n";
}

echo "\n=== SAMPLE CT STUDENTS ===\n";
$ctSample = DB::table('students')->where('classroom_id', 'CT_2024_2027')->orderBy('roll_no')->take(5)->get();
foreach ($ctSample as $s) {
    echo "Roll: {$s->roll_no} | Reg: {$s->reg_no} | Adm: {$s->adm_no} | Name: {$s->name} | SBTE: {$s->sbte_reg_no} | Email: {$s->email}\n";
}
