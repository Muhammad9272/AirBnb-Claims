<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthRoleIsolationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_regular_user_cannot_authenticate_through_admin_guard(): void
    {
        $user = User::create([
            'name' => 'Role Test User',
            'email' => 'role-user@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'user',
        ]);

        $this->post(route('admin.login.submit'), [
            'email' => $user->email,
            'password' => 'Testing123',
        ]);

        $this->assertGuest('admin');
    }

    public function test_admin_cannot_authenticate_through_regular_user_guard(): void
    {
        $admin = User::create([
            'name' => 'Role Test Admin',
            'email' => 'role-admin@example.test',
            'password' => Hash::make('Testing123'),
            'role_type' => 'admin',
        ]);

        $this->post(route('user.login.submit'), [
            'email' => $admin->email,
            'password' => 'Testing123',
        ]);

        $this->assertGuest('web');
    }
}
