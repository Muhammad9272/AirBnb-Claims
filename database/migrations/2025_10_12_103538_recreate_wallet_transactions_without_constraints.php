<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing table if exists
        Schema::dropIfExists('wallet_transactions');
        
        // Recreate without constraints
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('transaction_type', ['referral_earned', 'subscription_used', 'commission_earned'])->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedBigInteger('related_user_id')->nullable();
            $table->unsignedBigInteger('related_subscription_id')->nullable();
            $table->unsignedBigInteger('related_commission_id')->nullable();
            $table->unsignedBigInteger('related_referral_transaction_id')->nullable();
            $table->decimal('balance_before', 10, 2)->nullable();
            $table->decimal('balance_after', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
            
            // Optional: Add indexes for better query performance
            $table->index('user_id');
            $table->index('related_user_id');
            $table->index('related_subscription_id');
            $table->index('related_commission_id');
            $table->index('related_referral_transaction_id');
            $table->index('transaction_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};