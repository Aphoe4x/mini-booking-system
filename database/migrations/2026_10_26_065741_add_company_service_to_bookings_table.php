<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('company_service_id')->nullable()->constrained()->after('id');
            $table->decimal('price_paid', 8, 2)->nullable()->after('company_service_id');
            $table->string('service_name')->nullable()->change(); // keep for backward compat
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['company_service_id']);
            $table->dropColumn(['company_service_id', 'price_paid']);
        });
    }
};
