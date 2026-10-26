<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 8, 2);
            $table->integer('duration_minutes')->nullable(); // override service default
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'service_id']);
            $table->index('company_id');
            $table->index('service_id');
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_services');
    }
};
