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
        if (!Schema::hasTable('subject_official_attendances')) {
            Schema::create('subject_official_attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->index();
                $table->string('subject_code', 30)->index();
                $table->string('reg_no', 30)->index();
                $table->string('classroom_id', 50)->nullable();
                $table->unsignedInteger('total_hours')->default(0);
                $table->unsignedInteger('attended_hours')->default(0);
                $table->decimal('teams_percentage', 5, 2)->default(0.00);
                $table->decimal('override_percentage', 5, 2)->nullable();
                $table->decimal('final_percentage', 5, 2)->default(0.00);
                $table->decimal('max_attendance_marks', 4, 2)->default(10.00);
                $table->decimal('attendance_mark', 4, 2)->default(0.00);
                $table->decimal('override_mark', 4, 2)->nullable();
                $table->decimal('final_mark', 4, 2)->default(0.00);
                $table->string('source', 50)->default('TEAMS_PDF_UPLOAD');
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'uniq_sub_att_batch_reg');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_official_attendances');
    }
};
