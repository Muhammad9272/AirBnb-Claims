<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update the status enum column to include new statuses
        DB::statement("ALTER TABLE claims MODIFY COLUMN status ENUM('pending', 'under_review', 'additional_info_requested', 'challenge_1', 'challenge_2', 'approved', 'partial_payout', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to the original status enum if rollback is needed
        DB::statement("ALTER TABLE claims MODIFY COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
