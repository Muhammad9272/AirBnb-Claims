<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('claim_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_private')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_comments');
    }
}
