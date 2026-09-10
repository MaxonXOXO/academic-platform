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
        Schema::create('program_attainments', function (Blueprint $table) {
            $table->id();
            $table->string('classroom_id')->index();
            $table->string('branch')->nullable()->index();
            $table->integer('batch_year')->nullable();
            $table->string('revision')->default('REV2021'); // REV2021 or REV2026
            $table->json('po_targets')->nullable();
            $table->json('indirect_surveys')->nullable();
            $table->json('action_plans')->nullable();
            $table->json('cached_results')->nullable();
            $table->string('status')->default('Draft'); // Draft or Finalized
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_attainments');
    }
};
