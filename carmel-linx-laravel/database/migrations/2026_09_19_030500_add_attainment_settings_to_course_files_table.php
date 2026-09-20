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
        Schema::table('course_files', function (Blueprint $table) {
            if (!Schema::hasColumn('course_files', 'attainment_settings')) {
                $table->json('attainment_settings')->nullable()->after('self_learning_configs');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_files', function (Blueprint $table) {
            if (Schema::hasColumn('course_files', 'attainment_settings')) {
                $table->dropColumn('attainment_settings');
            }
        });
    }
};
