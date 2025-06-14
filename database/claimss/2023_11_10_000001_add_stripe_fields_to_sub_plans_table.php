<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeFieldsToSubPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_plans', function (Blueprint $table) {
            $table->string('stripe_product_id')->nullable()->after('claims_limit');
            $table->string('stripe_price_id')->nullable()->after('stripe_product_id');
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
            $table->dropColumn(['stripe_product_id', 'stripe_price_id']);
        });
    }
}
