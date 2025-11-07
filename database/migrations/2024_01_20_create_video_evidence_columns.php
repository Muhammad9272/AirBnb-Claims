<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('claim_evidences', function (Blueprint $table) {
            $table->boolean('is_video')->default(false);
            $table->string('video_duration')->nullable();
            $table->string('chunk_status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('claim_evidences', function (Blueprint $table) {
            $table->dropColumn(['is_video', 'video_duration', 'chunk_status']);
        });
    }
};
