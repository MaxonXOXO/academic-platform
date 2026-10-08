<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Special Attendance & Duty Leaves granted by Tutor for SBTE Exam Eligibility.
     * Isolated from regular course logs to keep CIA continuous evaluation marks unaffected.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tutor_special_attendances')) {
            Schema::create('tutor_special_attendances', function (Blueprint $table) {
                $table->id();
                $table->string('classroom_id', 50);
                $table->string('reg_no', 50);
                $table->date('date')->nullable();
                $table->decimal('hours', 4, 1)->default(1.0);
                $table->string('category', 50)->default('Duty Leave'); // NCC, NSS, IEDC, Placement, Sports, Cultural, Menstrual Leave, PWD, Duty Leave, Other
                $table->string('reason', 255)->nullable();
                $table->string('source', 50)->default('MANUAL'); // MANUAL, TEAMS_UPLOAD
                $table->string('document_path', 255)->nullable();
                $table->string('recorded_by', 100)->nullable();
                $table->timestamps();

                $table->index(['classroom_id', 'reg_no']);
                $table->index('date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_special_attendances');
    }
};
