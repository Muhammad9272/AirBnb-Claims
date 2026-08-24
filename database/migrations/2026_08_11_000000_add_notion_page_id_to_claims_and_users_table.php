<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('notion_page_id')->nullable()->after('rejection_reason');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('notion_page_id')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn('notion_page_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notion_page_id');
        });
    }
};
