<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_cannot_access_user_management(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_petugas_cannot_delete_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->delete(route('users.destroy', $admin));
        $response->assertStatus(403);

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_delete_the_last_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        // First admin can delete second admin
        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin2));
        $this->assertDatabaseMissing('users', ['id' => $admin2->id]);

        // But now $admin is the last admin remaining; attempting to delete should be blocked
        // (Even if requested through backdoor or another admin session)
        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));
        $response->assertSessionHas('error');
    }
}
