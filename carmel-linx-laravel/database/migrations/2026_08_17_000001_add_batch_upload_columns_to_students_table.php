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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('roll_no');
            }
            if (!Schema::hasColumn('students', 'application_no')) {
                $table->string('application_no')->nullable()->after('adm_no');
            }
            if (!Schema::hasColumn('students', 'quota')) {
                $table->string('quota')->nullable()->after('admission_type');
            }
            if (!Schema::hasColumn('students', 'date_of_joining')) {
                $table->date('date_of_joining')->nullable()->after('admission_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'application_no', 'quota', 'date_of_joining']);
        });
    }
};
