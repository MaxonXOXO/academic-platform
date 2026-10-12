<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('r21_drawing_attendance_evaluations')) {
            Schema::table('r21_drawing_attendance_evaluations', function (Blueprint $table) {
                if (!Schema::hasColumn('r21_drawing_attendance_evaluations', 'override_percentage')) {
                    $table->decimal('override_percentage', 5, 2)->nullable()->after('attendance_percentage');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('r21_drawing_attendance_evaluations')) {
            Schema::table('r21_drawing_attendance_evaluations', function (Blueprint $table) {
                if (Schema::hasColumn('r21_drawing_attendance_evaluations', 'override_percentage')) {
                    $table->dropColumn('override_percentage');
                }
            });
        }
    }
};
