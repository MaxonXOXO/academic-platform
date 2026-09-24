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
        if (Schema::hasTable('test_attempts') && !Schema::hasColumn('test_attempts', 'questions_payload')) {
            Schema::table('test_attempts', function (Blueprint $table) {
                $table->longText('questions_payload')->nullable()->after('responses');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('test_attempts') && Schema::hasColumn('test_attempts', 'questions_payload')) {
            Schema::table('test_attempts', function (Blueprint $table) {
                $table->dropColumn('questions_payload');
            });
        }
    }
};
