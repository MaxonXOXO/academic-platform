<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('staff_profiles', 'dob')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->date('dob')->nullable()->after('designation');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('staff_profiles', 'dob')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->dropColumn('dob');
            });
        }
    }
};
