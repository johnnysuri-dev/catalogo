<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    public function index(Request $request): View
    {
        $categoria = trim((string) $request->query('categoria', ''));
        $buscar = trim((string) $request->query('buscar', ''));

        $productos = Producto::query()
            ->where('activo', true)
            ->whereHas('categoria', fn ($query) => $query->where('activo', true))
            ->with('categoria')
            ->when($categoria !== '', fn ($query) => $query->where('categoria_id', $categoria))
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('sku', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy(
                Categoria::query()
                    ->select('nombre_categoria')
                    ->whereColumn('categoria.id', 'productos.categoria_id')
            )
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::query()
            ->where('activo', true)
            ->whereHas('productos', fn ($query) => $query->where('activo', true))
            ->orderBy('nombre_categoria')
            ->get();

        return view('catalogo.index', compact('productos', 'categorias', 'categoria', 'buscar'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_cliente' => ['required', 'string', 'max:150'],
            'empresa' => ['nullable', 'string', 'max:120'],
            'correo' => ['required', 'email', 'max:150'],
            'telefono' => ['required', 'string', 'max:30'],
            'mensaje' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $cotizacion = DB::transaction(function () use ($datos) {
            $productos = Producto::query()
                ->whereIn('id', array_keys($datos['items']))
                ->where('activo', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($productos->count() !== count($datos['items'])) {
                throw ValidationException::withMessages([
                    'items' => 'Uno o más productos ya no están disponibles. Actualiza el catálogo e inténtalo de nuevo.',
                ]);
            }

            $detalles = [];
            $total = 0;

            foreach ($datos['items'] as $id => $cantidad) {
                $producto = $productos->get((int) $id);
                $subtotal = round((float) $producto->precio * $cantidad, 2);
                $total += $subtotal;
                $detalles[] = [
                    'producto_id' => $producto->id,
                    'nombre_producto' => $producto->nombre,
                    'sku' => $producto->sku,
                    'fotografia' => $producto->fotos[0] ?? null,
                    'precio_unitario' => $producto->precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                ];
            }

            $cotizacion = Cotizacion::create([
                'codigo' => 'COT-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                'nombre_cliente' => $datos['nombre_cliente'],
                'empresa' => $datos['empresa'] ?? null,
                'correo' => $datos['correo'],
                'telefono' => $datos['telefono'],
                'mensaje' => $datos['mensaje'] ?? null,
                'moneda' => config('catalogo.moneda', 'USD'),
                'total' => $total,
                'estado' => 'pendiente',
            ]);

            $cotizacion->detalles()->createMany($detalles);

            return $cotizacion;
        });

        return redirect()
            ->route('catalogo.index')
            ->with('cotizacion_codigo', $cotizacion->codigo);
    }
}
