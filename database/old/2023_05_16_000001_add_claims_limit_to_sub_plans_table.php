<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimsLimitToSubPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_plans', function (Blueprint $table) {
            $table->integer('claims_limit')->nullable()->after('is_featured')
                  ->comment('Number of claims allowed per billing cycle. Null means unlimited.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_plans', function (Blueprint $table) {
            $table->dropColumn('claims_limit');
        });
    }
}
