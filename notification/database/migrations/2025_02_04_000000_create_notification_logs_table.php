<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Last updated: 2025-02-06
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sms', 'email', 'otp', 'push']);
            $table->string('gateway');
            $table->string('recipient');
            $table->text('message')->nullable();
            $table->string('subject')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
