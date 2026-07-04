<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('service_name');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Prevent the same user from double-booking the exact same slot
            $table->unique(['booking_date', 'booking_time', 'service_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
