<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // ربط الإشعار بالمستخدم (صاحب الحجز مثلاً)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('title'); // عنوان الإشعار (مثلاً: تم قبول حجزك)
            $table->text('body');    // نص الإشعار
            $table->boolean('is_read')->default(false); // هل قرأه المستخدم في التطبيق؟
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};