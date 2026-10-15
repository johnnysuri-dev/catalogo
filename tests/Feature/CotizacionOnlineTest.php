<?php

namespace Tests\Feature;

use App\Models\Cotizacion;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CotizacionOnlineTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_shows_active_products_and_can_filter_them(): void
    {
        $laptop = $this->createProduct();
        $monitor = $this->createProduct(['nombre' => 'Monitor de oficina', 'sku' => 'MON-01', 'category' => 'Monitores']);
        $this->createProduct(['nombre' => 'Equipo archivado', 'sku' => 'OLD-01', 'activo' => false]);

        $this->get(route('catalogo.index'))
            ->assertOk()
            ->assertSee($laptop->nombre)
            ->assertSee('technology-1.svg')
            ->assertSee('technology-2.svg')
            ->assertSee('technology-3.svg')
            ->assertDontSee('Equipo archivado');

        $this->get(route('catalogo.index', ['categoria' => $monitor->categoria_id]))
            ->assertOk()
            ->assertSee('Monitor de oficina')
            ->assertDontSee($laptop->nombre);
    }

    public function test_visitor_can_submit_a_quote_with_server_calculated_price_snapshots(): void
    {
        $producto = $this->createProduct(['precio' => 125.50]);

        $this->post(route('catalogo.store'), [
            'nombre_cliente' => 'Ana Pérez',
            'empresa' => 'Soluciones Ejemplo',
            'correo' => 'ana@example.com',
            'telefono' => '5551234',
            'items' => [$producto->id => 3],
        ])->assertRedirect(route('catalogo.index'))
            ->assertSessionHas('cotizacion_codigo');

        $cotizacion = Cotizacion::query()->with('detalles')->firstOrFail();
        $this->assertSame(376.50, (float) $cotizacion->total);
        $this->assertSame('pendiente', $cotizacion->estado);
        $this->assertSame('USD', $cotizacion->moneda);
        $this->assertSame(125.50, (float) $cotizacion->detalles->first()->precio_unitario);
        $this->assertSame(3, $cotizacion->detalles->first()->cantidad);
        $this->assertSame(376.50, (float) $cotizacion->detalles->first()->subtotal);
        $this->assertSame('Ana Pérez', $cotizacion->nombre_cliente);
    }

    public function test_quote_rejects_a_product_that_is_no_longer_available(): void
    {
        $producto = $this->createProduct(['activo' => false]);

        $this->from(route('catalogo.index'))
            ->post(route('catalogo.store'), [
                'nombre_cliente' => 'Ana Pérez',
                'correo' => 'ana@example.com',
                'telefono' => '5551234',
                'items' => [$producto->id => 1],
            ])
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('cotizaciones', 0);
    }

    public function test_staff_can_review_and_update_a_quote_but_visitors_cannot(): void
    {
        $producto = $this->createProduct();
        $this->post(route('catalogo.store'), [
            'nombre_cliente' => 'Ana Pérez',
            'correo' => 'ana@example.com',
            'telefono' => '5551234',
            'items' => [$producto->id => 1],
        ]);
        $cotizacion = Cotizacion::query()->firstOrFail();

        $this->get(route('cotizaciones.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('cotizaciones.show', $cotizacion))
            ->assertOk()
            ->assertSee($cotizacion->codigo)
            ->assertSee($producto->nombre);

        $this->patch(route('cotizaciones.update', $cotizacion), ['estado' => 'enviada'])
            ->assertRedirect(route('cotizaciones.show', $cotizacion));

        $this->assertDatabaseHas('cotizaciones', [
            'id' => $cotizacion->id,
            'estado' => 'enviada',
        ]);
    }

    public function test_authenticated_staff_can_create_a_product(): void
    {
        Storage::fake('public');
        $categoria = Categoria::firstOrCreate(['nombre_categoria' => 'Accesorios']);

        $this->actingAs(User::factory()->create())
            ->get(route('productos.create'))
            ->assertOk()
            ->assertSee('Nuevo producto')
            ->assertSee('Fotografías del producto');

        $this->get(route('productos.index'))
            ->assertOk()
            ->assertSee('Catálogo de productos');

        $this->post(route('productos.store'), [
            'nombre' => 'Teclado inalámbrico',
            'sku' => 'TEC-01',
            'categoria_id' => $categoria->id,
            'descripcion' => 'Teclado compacto.',
            'precio' => 49.99,
            'stock' => 8,
            'activo' => 1,
            'fotografias' => [
                $this->fakeImage('teclado-frente.png'),
                $this->fakeImage('teclado-lateral.png'),
            ],
        ])
            ->assertRedirect(route('productos.index'));

        $producto = Producto::query()->where('sku', 'TEC-01')->firstOrFail();
        $this->assertDatabaseHas('productos', [
            'sku' => 'TEC-01',
            'nombre' => 'Teclado inalámbrico',
            'activo' => true,
        ]);
        $this->assertCount(2, $producto->fotos);
        foreach ($producto->fotos as $fotografia) {
            Storage::disk('public')->assertExists($fotografia);
        }

        $this->get(route('catalogo.index'))
            ->assertOk()
            ->assertSee(asset('storage/'.$producto->fotos[0]), false)
            ->assertSee($producto->nombre);

        $this->post(route('catalogo.store'), [
            'nombre_cliente' => 'Ana Pérez',
            'correo' => 'ana@example.com',
            'telefono' => '5551234',
            'items' => [$producto->id => 1],
        ]);

        $cotizacion = Cotizacion::query()->firstOrFail();
        $this->assertSame($producto->fotos[0], $cotizacion->detalles()->firstOrFail()->fotografia);
        $this->actingAs(User::query()->firstOrFail())
            ->get(route('cotizaciones.show', $cotizacion))
            ->assertOk()
            ->assertSee(asset('storage/'.$producto->fotos[0]), false);
    }

    public function test_product_photographs_are_validated_and_limited_to_three(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $categoria = Categoria::firstOrCreate(['nombre_categoria' => 'Cámaras']);

        $this->actingAs($user)
            ->from(route('productos.create'))
            ->post(route('productos.store'), [
                'nombre' => 'Cámara de prueba',
                'sku' => 'CAM-01',
                'categoria_id' => $categoria->id,
                'precio' => 300,
                'stock' => 2,
                'activo' => 1,
                'fotografias' => [
                    $this->fakeImage('foto-1.png'),
                    $this->fakeImage('foto-2.png'),
                    $this->fakeImage('foto-3.png'),
                    $this->fakeImage('foto-4.png'),
                ],
            ])
            ->assertSessionHasErrors('fotografias');

        $this->assertDatabaseMissing('productos', ['sku' => 'CAM-01']);
    }

    public function test_staff_can_open_the_quotes_inbox(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('cotizaciones.index'))
            ->assertOk()
            ->assertSee('Solicitudes de cotización');
    }

    public function test_staff_can_manage_categories_and_cannot_delete_one_with_products(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('categorias.index'))
            ->assertOk()
            ->assertSee('Categorías de productos');

        $this->post(route('categorias.store'), [
            'nombre_categoria' => 'Redes',
            'activo' => 1,
        ])->assertRedirect(route('categorias.index'));

        $categoria = Categoria::query()->where('nombre_categoria', 'Redes')->firstOrFail();
        $this->createProduct(['category' => 'Redes']);

        $this->delete(route('categorias.destroy', $categoria))
            ->assertRedirect(route('categorias.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categoria', ['id' => $categoria->id]);
    }

    private function createProduct(array $attributes = []): Producto
    {
        $nombreCategoria = $attributes['category'] ?? 'Computadoras';
        unset($attributes['category']);
        $categoria = Categoria::firstOrCreate(['nombre_categoria' => $nombreCategoria]);

        return Producto::create(array_merge([
            'nombre' => 'Laptop profesional',
            'sku' => 'LAP-01',
            'categoria_id' => $categoria->id,
            'descripcion' => 'Laptop de prueba.',
            'precio' => 100,
            'stock' => 5,
            'activo' => true,
        ], $attributes));
    }

    private function fakeImage(string $name): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/jXcAAAAASUVORK5CYII=');

        return UploadedFile::fake()->createWithContent($name, $png);
    }
}
