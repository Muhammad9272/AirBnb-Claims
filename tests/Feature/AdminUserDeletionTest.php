<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserDeletionTest extends TestCase
{
    use DatabaseTransactions;

    private function superAdmin(): User
    {
        $admin = new User([
            'name' => 'Deletion Test Admin',
            'email' => 'delete-admin@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'admin',
        ]);
        $admin->id = 1;
        $admin->status = 1;
        $admin->save();

        return $admin;
    }

    public function test_deletion_is_blocked_when_user_has_claims(): void
    {
        $admin = $this->superAdmin();
        $user = User::create([
            'name' => 'Claim Owner',
            'email' => 'claim-owner@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'user',
        ]);
        Claim::create([
            'user_id' => $user->id,
            'claim_number' => 'DELETE-BLOCK-1',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->deleteJson(route('admin.users.destroy', $user->id));

        $response->assertStatus(422);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_clean_user_and_non_audit_relations_are_deleted(): void
    {
        $admin = $this->superAdmin();
        $user = User::create([
            'name' => 'Clean User',
            'email' => 'clean-user@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'user',
        ]);
        $referredUser = User::create([
            'name' => 'Referred User',
            'email' => 'referred-user@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'user',
            'referred_by' => $user->id,
        ]);

        DB::table('wallet_transactions')->insert([
            'user_id' => $user->id,
            'transaction_type' => 'referral_earned',
            'amount' => 10,
            'balance_before' => 0,
            'balance_after' => 10,
            'description' => 'Deletion test',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->deleteJson(route('admin.users.destroy', $user->id));

        $response->assertOk()->assertJson(['message' => 'User deleted successfully.']);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('wallet_transactions', ['user_id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $referredUser->id, 'referred_by' => null]);
    }

    public function test_delete_endpoint_rejects_get_requests(): void
    {
        $admin = $this->superAdmin();
        $user = User::create([
            'name' => 'Method Test User',
            'email' => 'method-user@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'user',
        ]);

        $this->actingAs($admin, 'admin')
            ->get('/management0712/users/delete/' . $user->id)
            ->assertStatus(405);
    }
}
