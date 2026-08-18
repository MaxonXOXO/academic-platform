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
        Schema::create('staff_biometric_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('staff_mobile_no', 20)->index();
            $table->text('credential_id');
            $table->text('public_key');
            $table->string('device_name', 100)->nullable();
            $table->unsignedInteger('counter')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_biometric_credentials');
    }
};
