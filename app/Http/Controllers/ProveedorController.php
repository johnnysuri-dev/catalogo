<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorRequest;
use App\Models\Proveedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProveedorController extends Controller
{
    public function index(Request $request): View
    {
        $termino = trim((string) $request->query('buscar', ''));

        $proveedores = Proveedor::query()
            ->when($termino !== '', function ($query) use ($termino) {
                $query->where(function ($query) use ($termino) {
                    $query->where('nombre_empresa', 'like', "%{$termino}%")
                        ->orWhere('contacto', 'like', "%{$termino}%")
                        ->orWhere('telefono', 'like', "%{$termino}%")
                        ->orWhere('correo', 'like', "%{$termino}%");
                });
            })
            ->orderBy('nombre_empresa')
            ->paginate(15)
            ->withQueryString();

        return view('proveedores.index', compact('proveedores', 'termino'));
    }

    public function create(): View
    {
        return view('proveedores.create', ['proveedor' => new Proveedor()]);
    }

    public function store(ProveedorRequest $request): RedirectResponse
    {
        $proveedor = Proveedor::create($request->validated());

        return redirect()->route('proveedores.show', $proveedor)
            ->with('success', 'Proveedor creado correctamente.');
    }

    public function show(Proveedor $proveedor): View
    {
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor): View
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(ProveedorRequest $request, Proveedor $proveedor): RedirectResponse
    {
        $proveedor->update($request->validated());

        return redirect()->route('proveedores.show', $proveedor)
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}