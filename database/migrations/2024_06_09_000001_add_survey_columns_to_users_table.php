<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSurveyColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('survey_completed')->default(false)->after('remember_token');
            $table->unsignedBigInteger('survey_answer')->nullable()->after('survey_completed');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['survey_completed', 'survey_answer']);
        });
    }
}
