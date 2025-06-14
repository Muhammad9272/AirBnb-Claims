<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNostalgiaItemsTable extends Migration
{
    public function up()
    {
        Schema::create('nostalgia_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('nostalgia_categories')->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained('nostalgia_categories');
            $table->foreignId('childcategory_id')->nullable()->constrained('nostalgia_categories');
            $table->string('photo')->nullable();
            $table->json('gallery')->nullable();
            $table->json('tags')->nullable();
            $table->year('release_year')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->json('specifications')->nullable(); // For release type, prototype, etc.
            $table->json('external_resources')->nullable(); // For links, guides, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nostalgia_items');
    }
}
