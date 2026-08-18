<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_profiles', 'avatar_zoom')) {
                $table->decimal('avatar_zoom', 4, 2)->default(1.08)->nullable()->after('photo_url');
            }
            if (!Schema::hasColumn('staff_profiles', 'avatar_pos')) {
                $table->integer('avatar_pos')->default(15)->nullable()->after('avatar_zoom');
            }
        });
    }

    public function down(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('staff_profiles', 'avatar_zoom')) {
                $table->dropColumn('avatar_zoom');
            }
            if (Schema::hasColumn('staff_profiles', 'avatar_pos')) {
                $table->dropColumn('avatar_pos');
            }
        });
    }
};
