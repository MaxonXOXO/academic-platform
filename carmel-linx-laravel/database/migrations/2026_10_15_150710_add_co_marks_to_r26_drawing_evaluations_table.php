<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Add separate CO marks for NBA Direct Attainment calculation:
     * - Series 1 (40 Marks): CO1 (20 Marks) & CO2 (20 Marks)
     * - Series 2 (40 Marks): CO3 (20 Marks) & CO4 (20 Marks)
     */
    public function up(): void
    {
        Schema::table('r26_drawing_evaluations', function (Blueprint $table) {
            $table->decimal('series_1_co1_20', 5, 2)->nullable()->after('series_1_40');
            $table->decimal('series_1_co2_20', 5, 2)->nullable()->after('series_1_co1_20');
            $table->decimal('series_2_co3_20', 5, 2)->nullable()->after('series_2_40');
            $table->decimal('series_2_co4_20', 5, 2)->nullable()->after('series_2_co3_20');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r26_drawing_evaluations', function (Blueprint $table) {
            $table->dropColumn([
                'series_1_co1_20',
                'series_1_co2_20',
                'series_2_co3_20',
                'series_2_co4_20'
            ]);
        });
    }
};
