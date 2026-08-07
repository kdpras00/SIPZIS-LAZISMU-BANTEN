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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('muzakki_id')->nullable();
            $table->string('type'); // payment, distribution, program, account, reminder, message
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->morphs('notifiable'); // For linking to related models (payment, distribution, etc.)
            $table->json('data')->nullable(); // Additional data for the notification
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['muzakki_id', 'is_read']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
