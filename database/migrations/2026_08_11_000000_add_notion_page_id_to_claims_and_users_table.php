<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('claims', 'notion_page_id')) {
            Schema::table('claims', function (Blueprint $table) {
                $table->string('notion_page_id')->nullable()->after('rejection_reason');
            });
        }

        if (!Schema::hasColumn('users', 'notion_page_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('notion_page_id')->nullable()->after('email');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('claims', 'notion_page_id')) {
            Schema::table('claims', function (Blueprint $table) {
                $table->dropColumn('notion_page_id');
            });
        }

        if (Schema::hasColumn('users', 'notion_page_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('notion_page_id');
            });
        }
    }
};
