<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Master Courses offered by Departments for S6 Open Elective
        if (!Schema::hasTable('oe_courses')) {
            Schema::create('oe_courses', function (Blueprint $table) {
                $table->id();
                $table->string('academic_year', 10)->default('2026-2027');
                $table->unsignedTinyInteger('semester')->default(6);
                $table->string('offering_branch', 10); // AU, CE, CT, EEE, EL, ME
                $table->string('course_code', 20);     // e.g. 6001, 6002
                $table->string('course_title', 150);
                $table->text('course_description')->nullable();
                $table->unsignedSmallInteger('max_capacity')->default(60);
                $table->string('faculty_name', 100)->nullable();
                $table->string('faculty_phone', 20)->nullable();
                $table->string('faculty_staff_id', 50)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['offering_branch', 'academic_year', 'semester'], 'uq_oe_course_branch_sem');
            });
        }

        // 2. Settings, Countdown Timers & HOD Release Gate
        if (!Schema::hasTable('oe_settings')) {
            Schema::create('oe_settings', function (Blueprint $table) {
                $table->id();
                $table->string('academic_year', 10)->default('2026-2027');
                $table->unsignedTinyInteger('semester')->default(6);
                $table->dateTime('reg_start_at')->nullable();
                $table->dateTime('reg_end_at')->nullable();
                $table->boolean('is_registration_open')->default(false);
                $table->boolean('is_released_to_hods')->default(false);
                $table->dateTime('released_at')->nullable();
                $table->string('released_by_staff_id', 50)->nullable();
                $table->string('push_title', 100)->default('S6 Open Elective Registration Open');
                $table->string('push_body', 255)->default('Please select your 6th semester Open Elective preferences.');
                $table->timestamps();
            });
        }

        // 3. Student Preference Submissions (Options 1 to 5)
        if (!Schema::hasTable('oe_registrations')) {
            Schema::create('oe_registrations', function (Blueprint $table) {
                $table->id();
                $table->string('student_adm_no', 50);
                $table->string('sbte_reg_no', 50);
                $table->string('student_name', 150);
                $table->string('parent_branch', 10);
                $table->unsignedBigInteger('option_1_course_id');
                $table->unsignedBigInteger('option_2_course_id');
                $table->unsignedBigInteger('option_3_course_id');
                $table->unsignedBigInteger('option_4_course_id');
                $table->unsignedBigInteger('option_5_course_id');
                $table->unsignedBigInteger('allotted_course_id')->nullable();
                $table->string('allocation_source', 30)->nullable(); // option_1_fcfs, option_2_rollover, admin_override
                $table->dateTime('registered_at');
                $table->string('ip_address', 45)->nullable();
                $table->enum('status', ['submitted', 'allotted', 'finalized'])->default('submitted');
                $table->timestamps();

                $table->unique('student_adm_no', 'uq_oe_student_adm');
                $table->index('sbte_reg_no', 'idx_oe_sbte');
                $table->index('parent_branch', 'idx_oe_parent_branch');
                $table->index('option_1_course_id', 'idx_oe_opt1');
                $table->index('allotted_course_id', 'idx_oe_allotted');
            });
        }

        // 4. Multi-Branch Virtual Classrooms (Independent of existing R21/R26 tables)
        if (!Schema::hasTable('oe_virtual_classrooms')) {
            Schema::create('oe_virtual_classrooms', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('oe_course_id');
                $table->string('student_adm_no', 50);
                $table->string('sbte_reg_no', 50);
                $table->string('student_name', 150);
                $table->string('parent_branch', 10);
                $table->string('roll_no', 20)->nullable();
                $table->string('academic_year', 10)->default('2026-2027');
                $table->unsignedTinyInteger('semester')->default(6);
                $table->string('assigned_faculty_id', 50)->nullable();
                $table->timestamps();

                $table->unique(['oe_course_id', 'student_adm_no'], 'uq_oe_class_student');
                $table->index('oe_course_id', 'idx_oe_class_course');
                $table->index('parent_branch', 'idx_oe_class_branch');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oe_virtual_classrooms');
        Schema::dropIfExists('oe_registrations');
        Schema::dropIfExists('oe_settings');
        Schema::dropIfExists('oe_courses');
    }
};
