<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimEvidencesTable extends Migration
{
    public function up()
    {
        Schema::create('claim_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims')->onDelete('cascade');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->string('description')->nullable();
            $table->enum('type', ['damage_photo', 'receipt', 'communication', 'other'])->default('other');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_evidences');
    }
}
