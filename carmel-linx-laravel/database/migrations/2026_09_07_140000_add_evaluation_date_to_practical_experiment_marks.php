<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('practical_experiment_marks', 'evaluation_date')) {
            Schema::table('practical_experiment_marks', function (Blueprint $table) {
                $table->date('evaluation_date')->nullable()->after('total_mark');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('practical_experiment_marks', 'evaluation_date')) {
            Schema::table('practical_experiment_marks', function (Blueprint $table) {
                $table->dropColumn('evaluation_date');
            });
        }
    }
};
