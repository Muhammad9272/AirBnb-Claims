<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_user_id')->nullable();
            $table->unsignedBigInteger('referee_user_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->decimal('credit_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->nullable();
            $table->timestamps();
            
            // Optional: Add indexes for better query performance
            $table->index('referrer_user_id');
            $table->index('referee_user_id');
            $table->index('subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_transactions');
    }
};