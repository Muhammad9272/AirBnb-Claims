<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('transaction_type', ['referral_earned', 'subscription_used', 'commission_earned'])->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('related_subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->foreignId('related_commission_id')->nullable()->constrained('influencer_commissions')->onDelete('set null');
            $table->foreignId('related_referral_transaction_id')->nullable()->constrained('referral_transactions')->onDelete('set null');
            $table->decimal('balance_before', 10, 2)->nullable();
            $table->decimal('balance_after', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
