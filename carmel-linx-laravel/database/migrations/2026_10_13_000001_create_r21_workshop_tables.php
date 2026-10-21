<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * R2021 Virtual Mechanical Workshop Tables - SBTE Kerala Regulation
     * Total CIA: 75 Marks | Total ESE: 50 Marks (Total = 125 Marks)
     * Sections: Machine shop, Fitting, Smithy, Welding, Sheet metal, Foundry
     */
    public function up(): void
    {
        // 1. Mechanical Workshop Course File Registry (R2021)
        if (!Schema::hasTable('r21_workshop_course_files')) {
            Schema::create('r21_workshop_course_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->unique();
                $table->string('syllabus_pdf_path', 255)->nullable();
                $table->text('program')->nullable();
                $table->string('course_title', 255)->nullable();
                $table->string('course_code', 50)->nullable();
                $table->string('semester', 20)->nullable();
                $table->string('type_of_course', 255)->default('Mechanical Workshop');
                $table->string('teaching_scheme', 50)->default('0:0:3:0'); // L:T:P:R
                $table->integer('contact_hours')->default(45);
                $table->decimal('credits', 4, 1)->default(1.5);
                $table->integer('cia_marks')->default(75); // 37.5 Work + 7.5 Record + 15 Test + 15 Att
                $table->integer('ese_marks')->default(50);
                $table->json('sections_config')->nullable(); // 6 sections
                $table->json('batch_allocation')->nullable(); // Batch 1 and Batch 2 chosen 3 sections
                $table->integer('batch_split_cutoff')->nullable();
                $table->json('parsed_cos')->nullable();
                $table->json('parsed_copo')->nullable();
                $table->timestamps();

                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            });
        }

        // 2. Student Batch & Section Allocations (Batch 1 or 2, 3 sections assigned)
        if (!Schema::hasTable('r21_workshop_student_allocations')) {
            Schema::create('r21_workshop_student_allocations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->string('batch_no', 10)->default('1'); // '1' or '2'
                $table->json('assigned_sections')->nullable(); // Array of 3 section names
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r21_ws_student_alloc_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 3. Work Evaluation (2 Works per section, each out of 20 marks) -> 6 works scaled to 37.5
        if (!Schema::hasTable('r21_workshop_work_evaluations')) {
            Schema::create('r21_workshop_work_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->string('section_name', 100); // Machine shop, Fitting, Smithy, Welding, Sheet metal, Foundry
                $table->decimal('work_1_mark', 5, 2)->default(0.00); // Max 20
                $table->decimal('work_2_mark', 5, 2)->default(0.00); // Max 20
                $table->decimal('work_avg_20', 5, 2)->default(0.00); // Average / 20
                $table->string('remarks', 255)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no', 'section_name'], 'r21_ws_work_eval_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 4. Section Evaluation: Rough Record (10M), Fair Record (10M), Test (15M)
        if (!Schema::hasTable('r21_workshop_section_evaluations')) {
            Schema::create('r21_workshop_section_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->string('section_name', 100);
                $table->decimal('rough_record_mark', 5, 2)->default(0.00); // Max 10
                $table->decimal('fair_record_mark', 5, 2)->default(0.00);  // Max 10
                $table->decimal('record_total_20', 5, 2)->default(0.00);   // Max 20 (R10+F10)
                $table->decimal('test_mark', 5, 2)->default(0.00);         // Max 15
                $table->boolean('is_test_absent')->default(false);
                $table->string('remarks', 255)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no', 'section_name'], 'r21_ws_sec_eval_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 5. Attendance & Performance (Max 15M from TEAMS / Official attendance)
        if (!Schema::hasTable('r21_workshop_attendance_evaluations')) {
            Schema::create('r21_workshop_attendance_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->integer('total_hours')->default(0);
                $table->integer('attended_hours')->default(0);
                $table->decimal('attendance_percentage', 5, 2)->default(0.00);
                $table->decimal('teams_percentage', 5, 2)->nullable();
                $table->decimal('override_percentage', 5, 2)->nullable();
                $table->decimal('final_percentage', 5, 2)->default(0.00);
                $table->decimal('attendance_mark', 5, 2)->default(0.00); // Max 15.0
                $table->decimal('override_mark', 5, 2)->nullable();
                $table->decimal('final_attendance_mark', 5, 2)->default(0.00); // Max 15.0
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r21_ws_att_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 6. Consolidated CIA Summaries & Overrides (Max 75M)
        if (!Schema::hasTable('r21_workshop_cia_summaries')) {
            Schema::create('r21_workshop_cia_summaries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->decimal('work_score_375', 5, 2)->default(0.00);
                $table->decimal('open_ended_75', 5, 2)->default(0.00);
                $table->decimal('test_avg_15', 5, 2)->default(0.00);
                $table->decimal('attendance_15', 5, 2)->default(0.00);
                $table->decimal('total_cia_75', 5, 2)->default(0.00);
                $table->decimal('override_work_375', 5, 2)->nullable();
                $table->decimal('override_open_ended_75', 5, 2)->nullable();
                $table->decimal('override_test_15', 5, 2)->nullable();
                $table->decimal('override_attendance_15', 5, 2)->nullable();
                $table->decimal('override_total_cia', 5, 2)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r21_ws_cia_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('r21_workshop_cia_summaries');
        Schema::dropIfExists('r21_workshop_attendance_evaluations');
        Schema::dropIfExists('r21_workshop_section_evaluations');
        Schema::dropIfExists('r21_workshop_work_evaluations');
        Schema::dropIfExists('r21_workshop_student_allocations');
        Schema::dropIfExists('r21_workshop_course_files');
    }
};
