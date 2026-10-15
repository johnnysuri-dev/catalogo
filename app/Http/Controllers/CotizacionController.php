<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CotizacionController extends Controller
{
    public function index(): View
    {
        $cotizaciones = Cotizacion::query()->latest()->paginate(15);

        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function show(Cotizacion $cotizacion): View
    {
        $cotizacion->load('detalles');

        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function update(Request $request, Cotizacion $cotizacion): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(['pendiente', 'enviada', 'aceptada', 'rechazada', 'vencida'])],
        ]);

        $cotizacion->update([
            ...$datos,
            'user_id' => $request->user()->getAuthIdentifier(),
        ]);

        return redirect()->route('cotizaciones.show', $cotizacion)->with('success', 'Estado de la cotización actualizado.');
    }
}
