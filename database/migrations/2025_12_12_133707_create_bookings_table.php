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
       Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
    $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade'); 
    $table->date('start_date'); 
    $table->date('end_date');  
    $table->date('requested_start_date')->nullable();
     $table->date('requested_end_date')->nullable();

    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'modified_pending','rejected'])->default('pending');
    
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
