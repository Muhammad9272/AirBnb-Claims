<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('influencer_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('influencer_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('claim_id')->nullable()->constrained('claims')->onDelete('set null');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->decimal('commission_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending')->nullable();
            $table->timestamp('commission_date')->nullable();
            $table->timestamp('paid_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('influencer_commissions');
    }
};
