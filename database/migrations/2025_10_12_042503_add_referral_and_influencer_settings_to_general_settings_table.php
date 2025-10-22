<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->decimal('referral_reward_percentage', 5, 2)->default(10.00)->after('id');
            $table->decimal('influencer_commission_percentage', 5, 2)->default(10.00)->after('referral_reward_percentage');
            $table->integer('influencer_commission_duration_days')->default(30)->after('influencer_commission_percentage');
            $table->integer('influencer_max_claims')->nullable()->after('influencer_commission_duration_days');
            $table->string('lead_discount_code')->nullable()->after('influencer_max_claims');
            $table->decimal('lead_discount_percentage', 5, 2)->default(20.00)->after('lead_discount_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'referral_reward_percentage',
                'influencer_commission_percentage',
                'influencer_commission_duration_days',
                'influencer_max_claims',
                'lead_discount_code',
                'lead_discount_percentage',
            ]);
        });
    }
};