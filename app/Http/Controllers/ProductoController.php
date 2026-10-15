<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::query()->with('categoria')->orderBy('nombre')->paginate(15);

        return view('productos.index', compact('productos'));
    }

    public function create(): View
    {
        return view('productos.form', [
            'producto' => new Producto(),
            'categorias' => Categoria::query()->where('activo', true)->orderBy('nombre_categoria')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validatedData($request);
        $fotografias = $request->file('fotografias', []);
        $rutasFotografias = [];

        try {
            DB::transaction(function () use ($datos, $fotografias, &$rutasFotografias) {
                $rutasFotografias = $this->storePhotographs($fotografias);
                Producto::create([...$datos, 'fotos' => $rutasFotografias]);
            });
        } catch (Throwable $exception) {
            $this->deletePhotographs($rutasFotografias);
            throw $exception;
        }

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto): View
    {
        return view('productos.form', [
            'producto' => $producto,
            'categorias' => Categoria::query()->orderBy('nombre_categoria')->get(),
        ]);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $datos = $this->validatedData($request, $producto);
        $fotografiasNuevas = $request->file('fotografias', []);
        $fotografiasAEliminar = $request->input('eliminar_fotografias', []);
        $fotografiasActuales = $producto->fotos ?? [];
        $fotografiasRestantes = array_values(array_diff($fotografiasActuales, $fotografiasAEliminar));

        if (count($fotografiasRestantes) + count($fotografiasNuevas) > 3) {
            return back()
                ->withErrors(['fotografias' => 'Cada producto puede tener como máximo tres fotografías.'])
                ->withInput();
        }

        $rutasFotografiasNuevas = [];

        try {
            DB::transaction(function () use ($producto, $datos, $fotografiasNuevas, $fotografiasRestantes, &$rutasFotografiasNuevas) {
                $rutasFotografiasNuevas = $this->storePhotographs($fotografiasNuevas);
                $producto->update([
                    ...$datos,
                    'fotos' => [...$fotografiasRestantes, ...$rutasFotografiasNuevas],
                ]);
            });
        } catch (Throwable $exception) {
            $this->deletePhotographs($rutasFotografiasNuevas);
            throw $exception;
        }

        $this->deletePhotographs($fotografiasAEliminar);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->update(['activo' => false]);

        return redirect()->route('productos.index')->with('success', 'Producto desactivado; se conserva el historial de cotizaciones.');
    }

    private function validatedData(Request $request, ?Producto $producto = null): array
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:60', Rule::unique('productos', 'sku')->ignore($producto)],
            'categoria_id' => ['required', 'integer', 'exists:categoria,id'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'activo' => ['required', 'boolean'],
            'fotografias' => ['sometimes', 'array', 'max:3'],
            'fotografias.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];

        if ($producto) {
            $rules['eliminar_fotografias'] = ['sometimes', 'array', 'max:3'];
            $rules['eliminar_fotografias.*'] = ['required', Rule::in($producto->fotos ?? [])];
        }

        $datos = $request->validate($rules);
        unset($datos['fotografias'], $datos['eliminar_fotografias']);

        return $datos;
    }

    /**
     * @param array<int, UploadedFile> $fotografias
     * @return array<int, string>
     */
    private function storePhotographs(array $fotografias): array
    {
        $rutas = [];

        foreach ($fotografias as $fotografia) {
            $ruta = $fotografia->store('products', 'public');

            if ($ruta === false) {
                throw new RuntimeException('No se pudo guardar una fotografía del producto.');
            }

            $rutas[] = $ruta;
        }

        return $rutas;
    }

    /**
     * @param array<int, string> $rutas
     */
    private function deletePhotographs(array $rutas): void
    {
        if ($rutas !== []) {
            $eliminadas = Storage::disk('public')->delete($rutas);

            if (! $eliminadas) {
                throw new RuntimeException('No se pudieron eliminar las fotografías del producto.');
            }
        }
    }
}
