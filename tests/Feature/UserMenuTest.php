<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_open_the_users_menu_and_listing(): void
    {
        $administrator = User::factory()->create([
            'nombre' => 'Admin Uno',
            'correo' => 'admin@example.com',
            'rol' => 'Administrador',
        ]);
        User::factory()->create([
            'nombre' => 'Vendedor Uno',
            'correo' => 'vendedor@example.com',
        ]);

        $this->actingAs($administrator)
            ->get('/users')
            ->assertOk()
            ->assertSee('Usuarios')
            ->assertSee('Lista de usuarios')
            ->assertSee('vendedor@example.com');
    }

    public function test_non_administrator_cannot_access_the_users_listing(): void
    {
        $user = User::factory()->create(['rol' => 'Vendedor']);

        $this->actingAs($user)
            ->get('/users')
            ->assertForbidden();
    }
}