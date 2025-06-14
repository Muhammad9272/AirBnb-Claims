<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturesToSubPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_plans', function (Blueprint $table) {
            $table->text('features')->nullable()->after('details');
            $table->decimal('commission_percentage', 5, 2)->default(20.00)->after('features');
            $table->string('display_label')->nullable()->after('commission_percentage');
            $table->boolean('is_featured')->default(false)->after('display_label');
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
            $table->dropColumn(['features', 'commission_percentage', 'display_label', 'is_featured']);
        });
    }
}
