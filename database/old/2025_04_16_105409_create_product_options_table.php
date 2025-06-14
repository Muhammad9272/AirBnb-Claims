<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products'); // Product ID
            $table->foreignId('option_type_id')->constrained('option_types'); // Option type like "Size", "Color"
            $table->unsignedTinyInteger('status')->default(1); // Status field (1 = active, 0 = inactive)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_options');
    }
}
