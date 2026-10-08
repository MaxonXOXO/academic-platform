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
        if (!Schema::hasTable('consolidated_cia_approvals')) {
            Schema::create('consolidated_cia_approvals', function (Blueprint $table) {
                $table->id();
                $table->string('classroom_id', 50)->index();
                $table->integer('semester')->index();
                $table->string('academic_year', 50)->nullable();
                $table->boolean('is_locked')->default(false);
                $table->boolean('approved_by_hod')->default(false);
                $table->string('hod_user_id', 50)->nullable();
                $table->string('hod_name', 100)->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->boolean('submitted_by_tutor')->default(false);
                $table->string('tutor_user_id', 50)->nullable();
                $table->string('tutor_name', 100)->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->unique(['classroom_id', 'semester'], 'unique_class_sem_cia_approval');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consolidated_cia_approvals');
    }
};
