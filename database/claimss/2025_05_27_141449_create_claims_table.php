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
        Schema::create('claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('claim_number', 191);
            $table->string('title', 191)->nullable();
            $table->text('description')->nullable();
            $table->string('property_address', 191)->nullable();
            $table->decimal('amount_requested', 10, 2)->nullable();
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->timestamp('incident_date')->useCurrent()->nullable();
            $table->string('guest_name', 191)->nullable();
            $table->string('guest_email', 191)->nullable();
            $table->string('airbnb_reservation_code', 191)->nullable();
            $table->timestamps(); // includes created_at and updated_at

            // Optional: Add foreign key if users table exists
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claims');
    }
};
