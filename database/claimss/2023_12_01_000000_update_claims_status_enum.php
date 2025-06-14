<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateClaimsStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // MySQL requires this approach to modify an enum
        DB::statement("ALTER TABLE claims MODIFY COLUMN status ENUM('pending', 'under_review', 'pending_evidence', 'approved', 'rejected', 'paid') NOT NULL DEFAULT 'pending'");
        
        // Add rejection_reason column if it doesn't exist
        if (!Schema::hasColumn('claims', 'rejection_reason')) {
            Schema::table('claims', function (Blueprint $table) {
                $table->text('rejection_reason')->nullable()->after('airbnb_reservation_code');
            });
        }
        
        // Add commission_amount column if it doesn't exist
        if (!Schema::hasColumn('claims', 'commission_amount')) {
            Schema::table('claims', function (Blueprint $table) {
                $table->decimal('commission_amount', 10, 2)->nullable()->after('amount_approved');
            });
        }
        
        // Add paid_date column if it doesn't exist
        if (!Schema::hasColumn('claims', 'paid_date')) {
            Schema::table('claims', function (Blueprint $table) {
                $table->timestamp('paid_date')->nullable()->after('rejection_reason');
            });
        }
        
        // Add uploaded_by column to claim_evidences if it doesn't exist
        if (!Schema::hasColumn('claim_evidences', 'uploaded_by')) {
            Schema::table('claim_evidences', function (Blueprint $table) {
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the enum to original values
        DB::statement("ALTER TABLE claims MODIFY COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
        
        // Remove added columns
        Schema::table('claims', function (Blueprint $table) {
            if (Schema::hasColumn('claims', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('claims', 'commission_amount')) {
                $table->dropColumn('commission_amount');
            }
            if (Schema::hasColumn('claims', 'paid_date')) {
                $table->dropColumn('paid_date');
            }
        });
        
        Schema::table('claim_evidences', function (Blueprint $table) {
            if (Schema::hasColumn('claim_evidences', 'uploaded_by')) {
                $table->dropForeign(['uploaded_by']);
                $table->dropColumn('uploaded_by');
            }
        });
    }
}
