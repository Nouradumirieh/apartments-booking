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
     Schema::create('apartments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('province_id')->constrained('provinces');
    $table->foreignId('city_id')->constrained('cities');
    $table->string('title', 255);
    $table->text('description');
    $table->decimal('price', 10, 2);
    $table->json('images')->nullable();
    $table->integer('number_of_rooms');
    $table->integer('number_of_bathrooms');
    $table->boolean('has_elevator')->default(false);
    $table->boolean('has_balcony')->default(false);
    $table->string('address_details');
    $table->decimal('area', 8, 2);
    $table->enum('status', ['available', 'booked', 'inactive'])->default('available');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
