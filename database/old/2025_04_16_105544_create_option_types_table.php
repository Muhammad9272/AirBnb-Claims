<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionTypesTable extends Migration
{
    public function up()
    {
        Schema::create('option_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Size", "Color"
            $table->unsignedTinyInteger('status')->default(1); // Status field (1 = active, 0 = inactive)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('option_types');
    }
}
