<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_persists_users_with_the_catalog_fields(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ana Vendedor',
            'email' => 'ana@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertDatabaseHas('users', [
            'nombre' => 'Ana Vendedor',
            'correo' => 'ana@example.com',
            'rol' => 'Vendedor',
            'estado' => true,
        ]);
    }

    public function test_login_authenticates_using_the_correo_and_password_hash_columns(): void
    {
        $user = User::factory()->create([
            'correo' => 'ana@example.com',
            'password_hash' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => 'ana@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        User::factory()->create([
            'correo' => 'inactivo@example.com',
            'password_hash' => 'password123',
            'estado' => false,
        ]);

        $this->post('/login', [
            'email' => 'inactivo@example.com',
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_reset_updates_the_password_hash_column(): void
    {
        Notification::fake();
        $user = User::factory()->create(['correo' => 'ana@example.com']);

        $this->post('/password/email', ['email' => $user->correo])
            ->assertSessionHas('status');

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->correo,
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ])->assertRedirect('/home');

        $this->assertTrue(Hash::check('new-password123', $user->fresh()->password_hash));
    }
}