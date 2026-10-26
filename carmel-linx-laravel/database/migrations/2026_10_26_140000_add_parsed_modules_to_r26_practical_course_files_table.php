<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('r26_practical_course_files', function (Blueprint $table) {
            if (!Schema::hasColumn('r26_practical_course_files', 'parsed_modules')) {
                $table->longText('parsed_modules')->nullable()->after('parsed_cos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('r26_practical_course_files', function (Blueprint $table) {
            if (Schema::hasColumn('r26_practical_course_files', 'parsed_modules')) {
                $table->dropColumn('parsed_modules');
            }
        });
    }
};
