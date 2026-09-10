<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * R2021 Virtual Drawing Hall Tables - SBTE Kerala Regulation 11.2.3
     */
    public function up(): void
    {
        // 1. Drawing Course File Registry (R2021)
        if (!Schema::hasTable('r21_drawing_course_files')) {
            Schema::create('r21_drawing_course_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->unique();
                $table->string('syllabus_pdf_path', 255)->nullable();
                $table->text('program')->nullable();
                $table->string('course_title', 255)->nullable();
                $table->string('course_code', 50)->nullable();
                $table->string('semester', 20)->nullable();
                $table->string('type_of_course', 255)->default('Drawing Courses');
                $table->string('teaching_scheme', 50)->default('0:0:3:0'); // L:T:P:R
                $table->integer('contact_hours')->default(45);
                $table->decimal('credits', 4, 1)->default(2.0);
                $table->integer('cia_marks')->default(50); // 40% Formative + 40% Summative + 20% Attendance
                $table->integer('ese_marks')->default(100);
                $table->json('parsed_cos')->nullable();
                $table->json('parsed_modules')->nullable();
                $table->json('parsed_sheets')->nullable(); // Configured drawing sheets (min 2 per module)
                $table->json('parsed_copo')->nullable();
                $table->json('parsed_textbooks')->nullable();
                $table->json('series_test_qps')->nullable();
                $table->timestamps();

                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            });
        }

        // 2. Formative Assessment: Drawing Sheets Continuous Evaluation (Clause 11.2.3.b)
        // Timely Completion: 50% | Appearance and Organization: 50%
        if (!Schema::hasTable('r21_drawing_sheet_evaluations')) {
            Schema::create('r21_drawing_sheet_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('sheet_no', 50); // 'Sheet 1', 'Sheet 2', etc.
                $table->string('sheet_title', 255)->nullable();
                $table->string('module_no', 50)->nullable(); // 'Module 1', 'Module 2', etc.
                $table->string('co_id', 50)->nullable(); // 'CO1', 'CO2', etc.
                $table->string('reg_no', 50);
                $table->decimal('timely_completion', 5, 2)->default(0.00); // Max 50
                $table->decimal('appearance_organization', 5, 2)->default(0.00); // Max 50
                $table->decimal('total_score_100', 5, 2)->default(0.00); // Max 100
                $table->boolean('is_absent')->default(false);
                $table->string('remarks', 255)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'sheet_no', 'reg_no'], 'r21_drawing_sheet_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 3. Summative Assessment: Series Tests (Clause 11.2.3.a)
        // Average of 2 tests: Procedure (40%) + Final drawing (30%) + Dimensioning (20%) + Neatness (10%)
        if (!Schema::hasTable('r21_drawing_series_tests')) {
            Schema::create('r21_drawing_series_tests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('test_no', 20)->default('Test 1'); // 'Test 1', 'Test 2'
                $table->string('reg_no', 50);
                $table->decimal('procedure_drawing', 5, 2)->default(0.00); // Max 40
                $table->decimal('final_drawing', 5, 2)->default(0.00);     // Max 30
                $table->decimal('dimensioning', 5, 2)->default(0.00);       // Max 20
                $table->decimal('neatness', 5, 2)->default(0.00);           // Max 10
                $table->decimal('total_score_100', 5, 2)->default(0.00);    // Max 100
                $table->boolean('is_absent')->default(false);
                $table->string('remarks', 255)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'test_no', 'reg_no'], 'r21_drawing_test_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 4. Attendance & Performance (Clause 11.2.3.c - 20% of CIA)
        if (!Schema::hasTable('r21_drawing_attendance_evaluations')) {
            Schema::create('r21_drawing_attendance_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->integer('total_hours')->default(0);
                $table->integer('attended_hours')->default(0);
                $table->decimal('attendance_percentage', 5, 2)->default(0.00);
                $table->decimal('attendance_mark', 5, 2)->default(0.00); // 20% of CIA (e.g. 10M for 50 CIA)
                $table->decimal('override_mark', 5, 2)->nullable();
                $table->decimal('final_attendance_mark', 5, 2)->default(0.00);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r21_drawing_att_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('r21_drawing_attendance_evaluations');
        Schema::dropIfExists('r21_drawing_series_tests');
        Schema::dropIfExists('r21_drawing_sheet_evaluations');
        Schema::dropIfExists('r21_drawing_course_files');
    }
};
