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
        Schema::table('batch_subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('batch_subjects', 'lab_batch_mode')) {
                $table->string('lab_batch_mode', 20)->default('split')->after('syllabus_revision_code');
            }
            if (!Schema::hasColumn('batch_subjects', 'lab_batch_cutoff')) {
                $table->integer('lab_batch_cutoff')->nullable()->after('lab_batch_mode');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_subjects', function (Blueprint $table) {
            if (Schema::hasColumn('batch_subjects', 'lab_batch_cutoff')) {
                $table->dropColumn('lab_batch_cutoff');
            }
            if (Schema::hasColumn('batch_subjects', 'lab_batch_mode')) {
                $table->dropColumn('lab_batch_mode');
            }
        });
    }
};
