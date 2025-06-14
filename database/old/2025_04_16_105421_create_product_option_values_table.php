<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionValuesTable extends Migration
{
    public function up()
    {
        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_option_id')->constrained('product_options'); // Reference to ProductOption
            $table->string('value'); // Value for the option (e.g., "10GB", "Red")
            $table->decimal('additional_price', 10, 2); // Price for the specific option value
            $table->unsignedTinyInteger('status')->default(1); // Status field (1 = active, 0 = inactive)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_option_values');
    }
}
