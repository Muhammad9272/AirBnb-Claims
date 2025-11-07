<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('survey_options', function (Blueprint $table) {
            $table->id();
            $table->string('option_text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_options');
    }
}
