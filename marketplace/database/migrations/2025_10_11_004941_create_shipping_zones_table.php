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
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade'); // Null = platform-wide
            $table->string('name');
            $table->json('countries'); // Array of country codes
            $table->json('states')->nullable(); // Array of state codes (if applicable)
            $table->json('postal_codes')->nullable(); // Array or pattern of postal codes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['vendor_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
