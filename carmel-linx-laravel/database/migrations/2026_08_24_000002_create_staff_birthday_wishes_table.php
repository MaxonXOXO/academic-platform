<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staff_birthday_wishes')) {
            Schema::create('staff_birthday_wishes', function (Blueprint $table) {
                $table->id();
                $table->date('wish_date');
                $table->string('celebrant_mobile_no', 20);
                $table->string('sender_mobile_no', 20);
                $table->string('sender_name', 255);
                $table->string('emoji', 50)->nullable();
                $table->text('message')->nullable();
                $table->timestamps();

                $table->index(['wish_date', 'celebrant_mobile_no']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_birthday_wishes');
    }
};
