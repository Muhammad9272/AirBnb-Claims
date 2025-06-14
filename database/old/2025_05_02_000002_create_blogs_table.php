<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('blog_categories');
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('photo')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
