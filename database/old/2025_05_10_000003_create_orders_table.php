<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained();
            $table->string('status');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('coupon_code')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            
            // Billing Details
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone');
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_zipcode');
            $table->string('billing_country');
            
            // Shipping Details
            $table->string('shipping_name');
            $table->string('shipping_email');
            $table->string('shipping_phone');
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_zipcode');
            $table->string('shipping_country');
            
            // Additional Info
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            // Index
            $table->index('order_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
