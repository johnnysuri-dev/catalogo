<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $termino = trim((string) $request->query('buscar', ''));

        $clientes = Cliente::query()
            ->when($termino !== '', function ($query) use ($termino) {
                $query->where(function ($query) use ($termino) {
                    $query->where('nombre_completo', 'like', "%{$termino}%")
                        ->orWhere('empresa', 'like', "%{$termino}%")
                        ->orWhere('nit_ci', 'like', "%{$termino}%")
                        ->orWhere('correo', 'like', "%{$termino}%");
                });
            })
            ->orderBy('nombre_completo')
            ->paginate(15)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'termino'));
    }

    public function create(): View
    {
        return view('clientes.create', ['cliente' => new Cliente()]);
    }

    public function store(ClienteRequest $request): RedirectResponse
    {
        $cliente = Cliente::create($request->validated());

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente): View
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}