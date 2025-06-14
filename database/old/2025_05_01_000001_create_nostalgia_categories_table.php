<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNostalgiaCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('nostalgia_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('nostalgia_categories')->onDelete('cascade');
            $table->integer('level')->default(1); // 1=main, 2=sub, 3=child
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nostalgia_categories');
    }
}
