<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Last updated: 2025-02-06

    /**
     * Run the migrations.
     */
    
    public function up(): void
    {
        Schema::create('notification_messages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sms', 'email', 'otp', 'push']);
            $table->string('gateway');
            $table->string('recipient');
            $table->text('request_payload')->nullable();
            $table->integer('http_status_code')->nullable();
            $table->string('response_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    
    public function down(): void
    {
        Schema::dropIfExists('notification_messages');
    }
};
