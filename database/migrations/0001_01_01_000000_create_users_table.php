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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->unique();
       // $table->timestamp('phone_verified_at')->nullable();
          //  $table->string('otp_code', 6)->nullable();
            $table->enum('role', ['tenant', 'owner']);
            $table->enum('status', ['pending', 'approved','rejected'])->nullable()->default(null);
            $table->string('first_name', 50); 
            $table->string('last_name', 50);
            $table->date('dob');
            $table->string('avatar', 255)->nullable();
            $table->string('id_image', 255);
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
       Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
