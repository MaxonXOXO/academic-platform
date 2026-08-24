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
        // Add remember_token to staff_profiles if not present
        if (!Schema::hasColumn('staff_profiles', 'remember_token')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->string('remember_token', 100)->nullable()->after('password');
            });
        }

        // Add remember_token to students if not present
        if (!Schema::hasColumn('students', 'remember_token')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('remember_token', 100)->nullable()->after('password');
            });
        }

        // Create push_subscriptions table for Web Push Notifications
        if (!Schema::hasTable('push_subscriptions')) {
            Schema::create('push_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('user_id', 50)->index(); // reg_no or mobile_no
                $table->string('role', 20)->index(); // 'student' or 'staff'
                $table->text('endpoint');
                $table->text('p256dh_key')->nullable();
                $table->text('auth_key')->nullable();
                $table->string('device_type', 30)->default('mobile');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');

        if (Schema::hasColumn('staff_profiles', 'remember_token')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }

        if (Schema::hasColumn('students', 'remember_token')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};
