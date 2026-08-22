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
        Schema::table('principal_scheduled_events', function (Blueprint $table) {
            if (!Schema::hasColumn('principal_scheduled_events', 'suppress_timetable')) {
                $table->boolean('suppress_timetable')->default(true)->after('is_full_day');
            }
            if (!Schema::hasColumn('principal_scheduled_events', 'suspension_type')) {
                $table->string('suspension_type', 30)->default('full_day')->after('suppress_timetable');
            }
            if (!Schema::hasColumn('principal_scheduled_events', 'end_date')) {
                $table->date('end_date')->nullable()->after('event_date');
            }
            if (!Schema::hasColumn('principal_scheduled_events', 'reopen_date')) {
                $table->date('reopen_date')->nullable()->after('end_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('principal_scheduled_events', function (Blueprint $table) {
            if (Schema::hasColumn('principal_scheduled_events', 'suppress_timetable')) {
                $table->dropColumn('suppress_timetable');
            }
            if (Schema::hasColumn('principal_scheduled_events', 'suspension_type')) {
                $table->dropColumn('suspension_type');
            }
            if (Schema::hasColumn('principal_scheduled_events', 'end_date')) {
                $table->dropColumn('end_date');
            }
            if (Schema::hasColumn('principal_scheduled_events', 'reopen_date')) {
                $table->dropColumn('reopen_date');
            }
        });
    }
};
