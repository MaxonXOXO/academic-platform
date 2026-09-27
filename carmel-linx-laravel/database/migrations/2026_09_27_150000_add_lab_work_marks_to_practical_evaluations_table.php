<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('practical_evaluations') && !Schema::hasColumn('practical_evaluations', 'lab_work_marks')) {
            Schema::table('practical_evaluations', function (Blueprint $table) {
                $table->decimal('lab_work_marks', 5, 2)->nullable()->after('assessor_mobile_no');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('practical_evaluations') && Schema::hasColumn('practical_evaluations', 'lab_work_marks')) {
            Schema::table('practical_evaluations', function (Blueprint $table) {
                $table->dropColumn('lab_work_marks');
            });
        }
    }
};
