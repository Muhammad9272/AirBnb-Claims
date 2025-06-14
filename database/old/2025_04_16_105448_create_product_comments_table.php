<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('product_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products'); // Product ID to associate the comment
            $table->foreignId('user_id')->constrained('users'); // User who posted the comment
            $table->text('comment'); // The actual feedback or comment text
            $table->integer('rating')->default(5); // Rating (e.g., 1 to 5 stars)
            $table->unsignedTinyInteger('status')->default(1); // Status field (1 = active, 0 = inactive)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_comments');
    }
}
