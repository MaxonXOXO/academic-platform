<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Dedicated isolated tables for Revision 2026 Subject Code 1001: English for Communication
     */
    public function up(): void
    {
        // 1. Course File & Outline Metadata for R26 English (1001)
        if (!Schema::hasTable('r26_english_course_files')) {
            Schema::create('r26_english_course_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->unique();
                $table->string('syllabus_pdf_path')->nullable();
                $table->string('program')->default('Engineering');
                $table->string('course_title')->default('English for Communication');
                $table->string('course_code', 30)->default('1001');
                $table->string('semester', 20)->default('I');
                $table->string('type_of_course')->default('Basic Science & Humanities Practicum');
                $table->string('teaching_scheme', 30)->default('2:0:2:0');
                $table->integer('contact_hours')->default(60);
                $table->decimal('credits', 4, 2)->default(3.00);
                $table->integer('cie_marks')->default(40);
                $table->integer('ese_marks')->default(60);
                $table->json('parsed_cos')->nullable();
                $table->json('parsed_modules')->nullable();
                $table->json('parsed_exercises')->nullable();
                $table->json('parsed_copo')->nullable();
                $table->json('parsed_textbooks')->nullable();
                $table->json('self_learning_configs')->nullable();
                $table->json('attainment_settings')->nullable();
                $table->json('doc_checklist')->nullable();
                $table->timestamps();
            });
        }

        // 2. Dedicated Lesson Planner Table for R26 English (1001)
        if (!Schema::hasTable('r26_english_lesson_plans')) {
            Schema::create('r26_english_lesson_plans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->index();
                $table->integer('day_no')->default(1);
                $table->date('proposed_date')->nullable();
                $table->date('actual_date')->nullable();
                $table->text('topic_content');
                $table->string('module_id', 20)->default('M1');
                $table->string('co_tag', 20)->default('CO1');
                $table->integer('allocated_hours')->default(1);
                $table->string('mode', 30)->default('L'); // L = Lecture, P = Practicum, PT = Practical Test, ST = Series Test
                $table->string('pedagogy', 100)->default('Interactive Lecture & Practicum');
                $table->string('status', 30)->default('Pending');
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 3. Dedicated Consolidated Evaluation Table for R26 English (1001)
        // Assessment Methodology - Practicum (40 CIA + 60 ESE = 100 Total)
        if (!Schema::hasTable('r26_english_evaluations')) {
            Schema::create('r26_english_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->index();
                $table->string('reg_no', 50)->index();

                // Attendance (Max 5 Marks, End of Semester)
                $table->decimal('attendance_pct', 5, 2)->nullable();
                $table->decimal('attendance_override_pct', 5, 2)->nullable();
                $table->decimal('attendance_mark_5', 5, 2)->default(0.00);

                // CA1: Self Learning Activities (M1, M2, M3, M4 each out of 10 -> Avg out of 10 -> Converted to 5 Marks)
                $table->decimal('sl_m1_10', 5, 2)->nullable();
                $table->decimal('sl_m2_10', 5, 2)->nullable();
                $table->decimal('sl_m3_10', 5, 2)->nullable();
                $table->decimal('sl_m4_10', 5, 2)->nullable();
                $table->decimal('sl_avg_10', 5, 2)->default(0.00);
                $table->decimal('sl_conv_5', 5, 2)->default(0.00);

                // CE: Continuous Evaluation (M1, M2, M3, M4 each out of 50 -> Avg out of 50 -> Converted to 10 Marks)
                $table->decimal('ce_m1_50', 5, 2)->nullable();
                $table->decimal('ce_m2_50', 5, 2)->nullable();
                $table->decimal('ce_m3_50', 5, 2)->nullable();
                $table->decimal('ce_m4_50', 5, 2)->nullable();
                $table->decimal('ce_avg_50', 5, 2)->default(0.00);
                $table->decimal('ce_conv_10', 5, 2)->default(0.00);

                // CA2 & CA3: Practical Tests (Test 1 & Test 2 each out of 40 -> Converted to 10 each -> Average 10 Marks)
                $table->decimal('prac_test1_40', 5, 2)->nullable();
                $table->boolean('prac_test1_absent')->default(false);
                $table->decimal('prac_test1_10', 5, 2)->default(0.00);
                $table->decimal('prac_test2_40', 5, 2)->nullable();
                $table->boolean('prac_test2_absent')->default(false);
                $table->decimal('prac_test2_10', 5, 2)->default(0.00);
                $table->decimal('prac_avg_40', 5, 2)->default(0.00);
                $table->decimal('prac_conv_10', 5, 2)->default(0.00);

                // CA4 & CA5: Series Exams (Series 1 & Series 2 each out of 50 -> Converted to 10 each -> Average 10 Marks)
                $table->decimal('series1_50', 5, 2)->nullable();
                $table->boolean('series1_absent')->default(false);
                $table->decimal('series1_10', 5, 2)->default(0.00);
                $table->decimal('series2_50', 5, 2)->nullable();
                $table->boolean('series2_absent')->default(false);
                $table->decimal('series2_10', 5, 2)->default(0.00);
                $table->decimal('series_avg_50', 5, 2)->default(0.00);
                $table->decimal('series_conv_10', 5, 2)->default(0.00);

                // Total Internal (CIA) Marks out of 40 (5 + 5 + 10 + 10 + 10)
                $table->decimal('total_cia_40', 5, 2)->default(0.00);

                // ESE: Lab Examination (Internal) - 2.5 Hours, Max 60 Marks
                $table->decimal('ese_mark_60', 5, 2)->nullable();
                $table->string('ese_grade', 15)->nullable();
                $table->boolean('ese_absent')->default(false);

                // Grand Total out of 100 (40 CIA + 60 ESE)
                $table->decimal('total_marks_100', 5, 2)->default(0.00);
                $table->boolean('is_passed')->default(false);
                $table->string('updated_by', 60)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r26_eng_eval_bs_reg_uniq');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r26_english_evaluations');
        Schema::dropIfExists('r26_english_lesson_plans');
        Schema::dropIfExists('r26_english_course_files');
    }
};
