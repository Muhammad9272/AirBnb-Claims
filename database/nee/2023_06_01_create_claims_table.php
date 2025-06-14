<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('claim_number')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('property_address')->nullable();
            $table->decimal('amount_requested', 10, 2)->nullable();
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'paid'])->default('pending');
            $table->timestamp('incident_date');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('airbnb_reservation_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}
