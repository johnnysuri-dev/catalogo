<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_create_view_update_and_delete_users(): void
    {
        $administrator = User::factory()->create([
            'nombre' => 'Admin',
            'correo' => 'admin@example.com',
            'rol' => 'Administrador',
        ]);
        $this->actingAs($administrator);

        $this->get(route('users.create'))->assertOk();

        $this->post(route('users.store'), [
            'nombre' => 'Vendedor Uno',
            'correo' => 'vendedor@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'rol' => 'Vendedor',
            'estado' => '1',
        ])->assertRedirect();

        $user = User::query()->where('correo', 'vendedor@example.com')->firstOrFail();
        $this->assertDatabaseHas('users', [
            'id_usuario' => $user->id_usuario,
            'nombre' => 'Vendedor Uno',
            'rol' => 'Vendedor',
        ]);
        $this->assertTrue(Hash::check('password123', $user->password_hash));

        $this->get(route('users.show', $user))->assertOk()->assertSee('vendedor@example.com');
        $this->get(route('users.edit', $user))->assertOk();

        $this->put(route('users.update', $user), [
            'nombre' => 'Vendedor Actualizado',
            'correo' => 'vendedor@example.com',
            'password' => '',
            'password_confirmation' => '',
            'rol' => 'Cajero',
            'estado' => '0',
        ])->assertRedirect(route('users.show', $user));

        $this->assertDatabaseHas('users', [
            'id_usuario' => $user->id_usuario,
            'nombre' => 'Vendedor Actualizado',
            'rol' => 'Cajero',
            'estado' => false,
        ]);
        $this->assertTrue(Hash::check('password123', $user->fresh()->password_hash));

        $this->delete(route('users.destroy', $user))->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id_usuario' => $user->id_usuario]);
    }

    public function test_non_administrator_cannot_open_user_management_routes(): void
    {
        $this->actingAs(User::factory()->create(['rol' => 'Vendedor']))
            ->get(route('users.create'))
            ->assertForbidden();
    }

    public function test_administrator_cannot_delete_their_own_account(): void
    {
        $administrator = User::factory()->create(['rol' => 'Administrador']);

        $this->actingAs($administrator)
            ->delete(route('users.destroy', $administrator))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id_usuario' => $administrator->id_usuario]);
    }
}