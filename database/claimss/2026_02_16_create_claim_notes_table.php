<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('claim_id');
            $table->unsignedBigInteger('admin_user_id');
            $table->text('note_content');
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->timestamps(); // includes created_at and updated_at

            // Foreign keys
            $table->foreign('claim_id')->references('id')->on('claims')->onDelete('cascade');
            $table->foreign('admin_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('edited_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for better query performance
            $table->index('claim_id');
            $table->index('admin_user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_notes');
    }
};
