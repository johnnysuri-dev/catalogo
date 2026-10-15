<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_view_and_delete_a_cliente(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('Clientes')
            ->assertSee('Nuevo cliente');

        $this->post(route('clientes.store'), [
            'nombre_completo' => 'Lucía Pérez',
            'empresa' => 'Comercial Pérez',
            'nit_ci' => '12345678',
            'correo' => 'lucia@example.com',
            'telefono' => '555-0100',
            'direccion' => 'Calle Central 10',
        ])->assertRedirect();

        $cliente = Cliente::query()->firstOrFail();
        $this->assertDatabaseHas('clientes', [
            'id_cliente' => $cliente->id_cliente,
            'nombre_completo' => 'Lucía Pérez',
            'correo' => 'lucia@example.com',
        ]);

        $this->get(route('clientes.show', $cliente))
            ->assertOk()
            ->assertSee('Lucía Pérez');

        $this->get(route('clientes.edit', $cliente))
            ->assertOk()
            ->assertSee('lucia@example.com');

        $this->put(route('clientes.update', $cliente), [
            'nombre_completo' => 'Lucía María Pérez',
            'empresa' => 'Comercial Pérez',
            'nit_ci' => '12345678',
            'correo' => 'lucia@example.com',
            'telefono' => '555-0101',
            'direccion' => 'Calle Sur 20',
        ])->assertRedirect(route('clientes.show', $cliente));

        $this->assertDatabaseHas('clientes', [
            'id_cliente' => $cliente->id_cliente,
            'nombre_completo' => 'Lucía María Pérez',
        ]);

        $this->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'));

        $this->assertDatabaseMissing('clientes', ['id_cliente' => $cliente->id_cliente]);
    }

    public function test_guests_are_redirected_from_the_client_list(): void
    {
        $this->get(route('clientes.index'))
            ->assertRedirect(route('login'));
    }

    public function test_duplicate_document_is_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        Cliente::create([
            'nombre_completo' => 'Cliente existente',
            'nit_ci' => '12345678',
        ]);

        $this->from(route('clientes.create'))
            ->post(route('clientes.store'), [
                'nombre_completo' => 'Otro Cliente',
                'nit_ci' => '12345678',
            ])
            ->assertSessionHasErrors('nit_ci');
    }
}