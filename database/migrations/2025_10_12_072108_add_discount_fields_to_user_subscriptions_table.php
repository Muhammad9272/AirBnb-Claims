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
        Schema::table('user_subscriptions', function (Blueprint $table) {
            // Discount tracking
            $table->string('discount_code', 50)->nullable()->after('price');
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('discount_code');
            $table->decimal('discount_percentage', 5, 2)->default(0.00)->after('discount_amount');
            
            // Wallet credit used
            $table->decimal('wallet_credit_used', 10, 2)->default(0.00)->after('discount_percentage');
            
            // Final amount paid (after discount + wallet)
            $table->decimal('amount_paid', 10, 2)->default(0.00)->after('wallet_credit_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'discount_code',
                'discount_amount', 
                'discount_percentage',
                'wallet_credit_used',
                'amount_paid'
            ]);
        });
    }
};