<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Electronics", "Consoles"
            $table->string('slug');
            $table->text('description')->nullable(); // Description for the category
            $table->string('photo')->nullable(); // Photo for the category
            $table->foreignId('parent_id')->nullable()->constrained('product_categories'); // Self-referencing for subcategories
            $table->unsignedTinyInteger('status')->default(1); // Status field (1 = active, 0 = inactive)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
