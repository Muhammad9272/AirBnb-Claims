<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Final price including options
            $table->decimal('unit_price', 10, 2); // Base price without options
            $table->decimal('options_price', 10, 2)->default(0); // Additional price from options
            $table->json('options')->nullable(); // Store selected options
            $table->json('variations')->nullable(); // Store product variations at time of purchase
            $table->timestamps();

            // Indexes
            $table->index(['order_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
