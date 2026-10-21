<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add columns for Machine Shop 4 works and Sheet Metal Aluminium Fabrication Work + Rough & Fair records.
     */
    public function up(): void
    {
        if (Schema::hasTable('r21_workshop_work_evaluations')) {
            Schema::table('r21_workshop_work_evaluations', function (Blueprint $table) {
                if (!Schema::hasColumn('r21_workshop_work_evaluations', 'work_3_mark')) {
                    $table->decimal('work_3_mark', 5, 2)->nullable()->default(0.00)->after('work_2_mark');
                }
                if (!Schema::hasColumn('r21_workshop_work_evaluations', 'work_4_mark')) {
                    $table->decimal('work_4_mark', 5, 2)->nullable()->default(0.00)->after('work_3_mark');
                }
                if (!Schema::hasColumn('r21_workshop_work_evaluations', 'alum_work_mark')) {
                    $table->decimal('alum_work_mark', 5, 2)->nullable()->default(0.00)->after('work_4_mark');
                }
            });
        }

        if (Schema::hasTable('r21_workshop_section_evaluations')) {
            Schema::table('r21_workshop_section_evaluations', function (Blueprint $table) {
                if (!Schema::hasColumn('r21_workshop_section_evaluations', 'alum_rough_record_mark')) {
                    $table->decimal('alum_rough_record_mark', 5, 2)->nullable()->default(null)->after('fair_record_mark');
                }
                if (!Schema::hasColumn('r21_workshop_section_evaluations', 'alum_fair_record_mark')) {
                    $table->decimal('alum_fair_record_mark', 5, 2)->nullable()->default(null)->after('alum_rough_record_mark');
                }
                if (!Schema::hasColumn('r21_workshop_section_evaluations', 'alum_record_total_20')) {
                    $table->decimal('alum_record_total_20', 5, 2)->nullable()->default(null)->after('alum_fair_record_mark');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('r21_workshop_work_evaluations')) {
            Schema::table('r21_workshop_work_evaluations', function (Blueprint $table) {
                $columns = ['work_3_mark', 'work_4_mark', 'alum_work_mark'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('r21_workshop_work_evaluations', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('r21_workshop_section_evaluations')) {
            Schema::table('r21_workshop_section_evaluations', function (Blueprint $table) {
                $columns = ['alum_rough_record_mark', 'alum_fair_record_mark', 'alum_record_total_20'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('r21_workshop_section_evaluations', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
