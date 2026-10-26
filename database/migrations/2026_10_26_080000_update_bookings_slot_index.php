<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // The old uniqueness rule was based on a free-text service name.
            // Slot uniqueness is now enforced per company-service in the app layer.
            $table->dropUnique(['booking_date', 'booking_time', 'service_name']);
            $table->index(['company_service_id', 'booking_date', 'booking_time'], 'bookings_slot_index');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_slot_index');
            $table->unique(['booking_date', 'booking_time', 'service_name']);
        });
    }
};
