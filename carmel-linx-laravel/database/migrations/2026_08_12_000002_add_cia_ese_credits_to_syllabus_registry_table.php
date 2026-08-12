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
        Schema::table('syllabus_registry', function (Blueprint $table) {
            if (!Schema::hasColumn('syllabus_registry', 'cia_marks')) {
                $table->integer('cia_marks')->default(60)->after('co_count');
            }
            if (!Schema::hasColumn('syllabus_registry', 'ese_marks')) {
                $table->integer('ese_marks')->default(40)->after('cia_marks');
            }
            if (!Schema::hasColumn('syllabus_registry', 'credits')) {
                $table->decimal('credits', 4, 2)->default(2.00)->after('ese_marks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syllabus_registry', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('syllabus_registry', 'cia_marks')) $columns[] = 'cia_marks';
            if (Schema::hasColumn('syllabus_registry', 'ese_marks')) $columns[] = 'ese_marks';
            if (Schema::hasColumn('syllabus_registry', 'credits')) $columns[] = 'credits';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
