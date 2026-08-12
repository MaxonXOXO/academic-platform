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
        // Table 1: Isolated Diagnostic Logging for Beta Telemetry & Issue Catcher
        if (!Schema::hasTable('ams_system_logs')) {
            Schema::create('ams_system_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('department', 50)->nullable();
                $table->string('endpoint', 255)->nullable();
                $table->string('severity', 20)->default('INFO'); // INFO, WARNING, ERROR, CRITICAL
                $table->string('error_code', 50)->nullable();
                $table->text('message');
                $table->json('stack_trace')->nullable();
                $table->json('context')->nullable();
                $table->string('status', 20)->default('UNRESOLVED'); // UNRESOLVED, INVESTIGATING, RESOLVED
                $table->timestamps();

                $table->index(['severity', 'status']);
                $table->index('department');
            });
        }

        // Table 2: AI Syllabus Metadata Registry (Revision 2021 & 2026 Rules)
        if (!Schema::hasTable('ams_syllabus_metadata')) {
            Schema::create('ams_syllabus_metadata', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->nullable()->index();
                $table->string('subject_code', 50)->index();
                $table->string('revision_code', 20)->default('2026'); // 2021, 2026
                $table->string('course_type', 50)->default('THEORY'); // DRAWING_CAD, PRACTICUM, THEORY, LAB, PROJECT
                $table->json('credit_distribution')->nullable(); // {L:1, T:0, P:3, R:0}
                $table->json('evaluation_scheme')->nullable(); // CE, IA, ESE split
                $table->json('exam_pattern')->nullable(); // Part A, Part B, Duration
                $table->json('co_po_matrix')->nullable(); // CO to PO/PSO mappings
                $table->boolean('is_ai_parsed')->default(false);
                $table->timestamp('parsed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ams_syllabus_metadata');
        Schema::dropIfExists('ams_system_logs');
    }
};
