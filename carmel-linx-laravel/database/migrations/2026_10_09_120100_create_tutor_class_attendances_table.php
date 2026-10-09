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
        if (!Schema::hasTable('tutor_class_attendances')) {
            Schema::create('tutor_class_attendances', function (Blueprint $table) {
                $table->id();
                $table->string('classroom_id', 50)->index();
                $table->string('reg_no', 30)->index();
                $table->unsignedInteger('total_hours')->default(0);
                $table->unsignedInteger('attended_hours')->default(0);
                $table->decimal('attendance_percentage', 5, 2)->default(0.00);
                $table->decimal('override_percentage', 5, 2)->nullable();
                $table->decimal('final_percentage', 5, 2)->default(0.00);
                $table->string('eligibility_status', 30)->default('Eligible');
                $table->string('recorded_by', 50)->nullable();
                $table->string('source', 50)->default('TEAMS_TUTOR_UPLOAD');
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->unique(['classroom_id', 'reg_no'], 'uniq_tutor_att_class_reg');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_class_attendances');
    }
};
