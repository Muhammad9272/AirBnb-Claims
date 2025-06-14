<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->string('coupon_code')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['session_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
