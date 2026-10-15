<?php

namespace Tests\Feature;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProveedorCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_view_and_delete_a_proveedor(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('proveedores.index'))
            ->assertOk()
            ->assertSee('Proveedores')
            ->assertSee('Nuevo proveedor');

        $this->post(route('proveedores.store'), [
            'nombre_empresa' => 'Distribuidora Central',
            'contacto' => 'María López',
            'telefono' => '555-0123',
            'correo' => 'ventas@central.example',
        ])->assertRedirect();

        $proveedor = Proveedor::query()->firstOrFail();
        $this->assertDatabaseHas('proveedores', [
            'id_proveedor' => $proveedor->id_proveedor,
            'nombre_empresa' => 'Distribuidora Central',
        ]);

        $this->get(route('proveedores.show', $proveedor))
            ->assertOk()
            ->assertSee('María López');

        $this->get(route('proveedores.edit', $proveedor))
            ->assertOk()
            ->assertSee('Distribuidora Central');

        $this->put(route('proveedores.update', $proveedor), [
            'nombre_empresa' => 'Distribuidora Norte',
            'contacto' => 'María López',
            'telefono' => '555-0199',
            'correo' => 'ventas@norte.example',
        ])->assertRedirect(route('proveedores.show', $proveedor));

        $this->assertDatabaseHas('proveedores', [
            'id_proveedor' => $proveedor->id_proveedor,
            'nombre_empresa' => 'Distribuidora Norte',
        ]);

        $this->delete(route('proveedores.destroy', $proveedor))
            ->assertRedirect(route('proveedores.index'));

        $this->assertDatabaseMissing('proveedores', ['id_proveedor' => $proveedor->id_proveedor]);
    }

    public function test_guests_are_redirected_from_the_proveedor_list(): void
    {
        $this->get(route('proveedores.index'))
            ->assertRedirect(route('login'));
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('proveedores.create'))
            ->post(route('proveedores.store'), [
                'nombre_empresa' => 'Distribuidora Central',
                'correo' => 'correo-invalido',
            ])
            ->assertSessionHasErrors('correo');
    }
}