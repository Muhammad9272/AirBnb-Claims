<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->string('payment_method');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status');
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Indexes
            $table->index(['transaction_id', 'order_id']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
