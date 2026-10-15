<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::query()
            ->withCount('productos')
            ->orderBy('nombre_categoria')
            ->paginate(15);

        return view('categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('categorias.form', ['categoria' => new Categoria()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Categoria::create($this->validatedData($request));

        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria): View
    {
        return view('categorias.form', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $categoria->update($this->validatedData($request, $categoria));

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->productos()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar una categoría que tiene productos asignados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
    }

    private function validatedData(Request $request, ?Categoria $categoria = null): array
    {
        return $request->validate([
            'nombre_categoria' => [
                'required',
                'string',
                'max:80',
                Rule::unique('categoria', 'nombre_categoria')->ignore($categoria),
            ],
            'activo' => ['required', 'boolean'],
        ]);
    }
}
