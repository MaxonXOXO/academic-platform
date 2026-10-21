<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Consolidated Evaluation Scheme for R2026 Engineering Graphics / Drawing (Course 1003)
     * Total 100 Marks: CIE 40 Marks + ESE 60 Marks
     * - Attendance: Max 5 Marks (based on R2026 slabs with percentage override)
     * - Self-Learning (CA1-CA4, Mod 1-4): Max 15 Marks each, averaged/converted to 15 Marks total
     * - Series Exams: S1 (Mod 1&2, Max 40 -> 20), S2 (Mod 3&4, Max 40 -> 20), combined into 20 Marks total
     * - Total CIA: Attendance (5) + Self-Learning (15) + Series (20) = 40 Marks
     * - ESE: 60 Marks (Grade or Marks)
     * - Grand Total: CIE (40) + ESE (60) = 100 Marks
     */
    public function up(): void
    {
        if (!Schema::hasTable('r26_drawing_evaluations')) {
            Schema::create('r26_drawing_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);

                // Attendance
                $table->decimal('attendance_pct', 5, 2)->nullable()->default(null);
                $table->decimal('attendance_override_pct', 5, 2)->nullable();
                $table->decimal('attendance_mark_5', 4, 2)->default(0.00);

                // Self Learning Continuous Assessment (CA1 - CA4 out of 15 each)
                $table->decimal('ca1_15', 5, 2)->nullable();
                $table->decimal('ca2_15', 5, 2)->nullable();
                $table->decimal('ca3_15', 5, 2)->nullable();
                $table->decimal('ca4_15', 5, 2)->nullable();
                $table->decimal('self_learning_15', 5, 2)->default(0.00);

                // Series Tests (Max 40 each, each converted to 20, combined into 20)
                $table->decimal('series_1_40', 5, 2)->nullable();
                $table->boolean('series_1_absent')->default(false);
                $table->decimal('series_2_40', 5, 2)->nullable();
                $table->boolean('series_2_absent')->default(false);
                $table->decimal('series_20', 5, 2)->default(0.00);

                // Total CIE / CIA (Max 40)
                $table->decimal('total_cia_40', 5, 2)->default(0.00);

                // ESE (End Semester Examination - Max 60 Marks or Board Grade)
                $table->string('ese_grade', 10)->nullable();
                $table->decimal('ese_mark_60', 5, 2)->nullable();
                $table->boolean('ese_absent')->default(false);

                // Grand Total (Max 100)
                $table->decimal('total_marks_100', 5, 2)->default(0.00);

                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r26_drawing_eval_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r26_drawing_evaluations');
    }
};
