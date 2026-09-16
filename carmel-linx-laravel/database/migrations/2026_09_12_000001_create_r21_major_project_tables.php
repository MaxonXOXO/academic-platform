<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * R2021 Virtual Major Project Tables - SBTE Kerala Regulation 11.2.5 & 11.3.4
     * Ratio 3:2 -> CIA = 75 Marks, ESE = 50 Marks (Total = 125 Marks)
     */
    public function up(): void
    {
        // 1. Major Project Course File Registry (R2021)
        if (!Schema::hasTable('r21_major_project_course_files')) {
            Schema::create('r21_major_project_course_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->unique();
                $table->string('syllabus_pdf_path', 255)->nullable();
                $table->string('course_title', 255)->nullable();
                $table->string('course_code', 50)->nullable();
                $table->string('semester', 20)->nullable();
                $table->integer('cia_marks')->default(75); // 30 Summative + 30 Formative + 15 Attendance
                $table->integer('ese_marks')->default(50); // External & Internal Evaluation
                $table->decimal('credits', 4, 1)->default(4.0);
                $table->json('parsed_cos')->nullable();
                $table->json('parsed_copo')->nullable();
                $table->json('project_groups')->nullable(); // Group allocations & titles
                $table->json('attainment_settings')->nullable(); // ESE and CIA threshold configurations
                $table->timestamps();

                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            });
        }

        // 2. Major Project Student Evaluations (Clause 11.2.5 & 11.3.4)
        if (!Schema::hasTable('r21_major_project_evaluations')) {
            Schema::create('r21_major_project_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->string('group_id', 50)->nullable();
                $table->string('project_title', 255)->nullable();

                // Formative: Weekly Activity Report / Diary (Clause 11.2.5 - Max 30 Marks = 40% of CIA)
                $table->decimal('formative_diary_marks', 5, 2)->default(0.00);

                // Summative: Department Evaluation (Clause 11.2.5 - Max 30 Marks = 40% of CIA)
                $table->decimal('summative_dept_marks', 5, 2)->default(0.00);

                // Attendance & Punctuality (Clause 11.2.5 - Max 15 Marks = 20% of CIA)
                $table->decimal('attendance_marks', 5, 2)->default(0.00);

                // Total CIA (Max 75 Marks)
                $table->decimal('total_cia_75', 5, 2)->default(0.00);

                // ESE Assessment Components (Clause 11.3.4 - Total 50 Marks)
                $table->decimal('ese_prototype', 5, 2)->default(0.00);        // 20% = 10M
                $table->decimal('ese_modern_tools', 5, 2)->default(0.00);     // 10% = 5M
                $table->decimal('ese_presentation', 5, 2)->default(0.00);     // 15% = 7.5M
                $table->decimal('ese_innovativeness', 5, 2)->default(0.00);   // 5%  = 2.5M
                $table->decimal('ese_viva', 5, 2)->default(0.00);             // 15% = 7.5M
                $table->decimal('ese_individual_contrib', 5, 2)->default(0.00); // 15% = 7.5M
                $table->decimal('ese_group_activity', 5, 2)->default(0.00);   // 10% = 5M
                $table->decimal('ese_project_report', 5, 2)->default(0.00);   // 10% = 5M
                $table->decimal('total_ese_50', 5, 2)->default(0.00);         // Total ESE / 50
                $table->string('ese_grade', 5)->nullable();                  // S, A, B, C, D, E, F, FE

                // Combined Assessment
                $table->decimal('grand_total_125', 5, 2)->default(0.00);      // Max 125
                $table->boolean('passed')->default(false);
                $table->string('remarks', 255)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r21_project_eval_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('r21_major_project_evaluations');
        Schema::dropIfExists('r21_major_project_course_files');
    }
};
