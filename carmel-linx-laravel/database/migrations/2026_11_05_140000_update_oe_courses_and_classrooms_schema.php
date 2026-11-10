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
        Schema::table('oe_courses', function (Blueprint $table) {
            if (!Schema::hasColumn('oe_courses', 'course_outcomes')) {
                $table->text('course_outcomes')->nullable()->after('course_description');
            }
            if (!Schema::hasColumn('oe_courses', 'syllabus_pdf_path')) {
                $table->string('syllabus_pdf_path', 255)->nullable()->after('course_outcomes');
            }
            if (!Schema::hasColumn('oe_courses', 'classroom_id')) {
                $table->string('classroom_id', 50)->nullable()->after('syllabus_pdf_path');
            }
            if (!Schema::hasColumn('oe_courses', 'batch_subject_id')) {
                $table->unsignedBigInteger('batch_subject_id')->nullable()->after('classroom_id');
            }
            if (!Schema::hasColumn('oe_courses', 'is_hod_submitted')) {
                $table->boolean('is_hod_submitted')->default(false)->after('is_active');
            }
        });

        Schema::table('oe_virtual_classrooms', function (Blueprint $table) {
            if (!Schema::hasColumn('oe_virtual_classrooms', 'classroom_id')) {
                $table->string('classroom_id', 50)->nullable()->after('oe_course_id');
            }
            if (!Schema::hasColumn('oe_virtual_classrooms', 'batch_subject_id')) {
                $table->unsignedBigInteger('batch_subject_id')->nullable()->after('classroom_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oe_courses', function (Blueprint $table) {
            $table->dropColumn(['course_outcomes', 'syllabus_pdf_path', 'classroom_id', 'batch_subject_id', 'is_hod_submitted']);
        });

        Schema::table('oe_virtual_classrooms', function (Blueprint $table) {
            $table->dropColumn(['classroom_id', 'batch_subject_id']);
        });
    }
};
