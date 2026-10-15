@extends('adminlte::page')
@section('title', 'Cotizaciones')
@section('content_header')
    <h1>Solicitudes de cotización</h1>
@stop
@section('content')
    <div class="card">
        <div class="card-header">Solicitudes recibidas desde el catálogo online.</div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th>Código</th><th>Fecha</th><th>Cliente</th><th>Contacto</th><th>Total</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @forelse ($cotizaciones as $cotizacion)
                        <tr>
                            <td><strong>{{ $cotizacion->codigo }}</strong></td>
                            <td>{{ $cotizacion->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $cotizacion->nombre_cliente }}<br><small class="text-muted">{{ $cotizacion->empresa }}</small></td>
                            <td>{{ $cotizacion->correo }}<br><small class="text-muted">{{ $cotizacion->telefono }}</small></td>
                            <td>{{ $cotizacion->moneda }} {{ number_format((float) $cotizacion->total, 2) }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($cotizacion->estado) }}</span></td>
                            <td><a href="{{ route('cotizaciones.show', $cotizacion) }}" class="btn btn-sm btn-outline-primary">Ver detalle</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center p-4">Todavía no se han recibido cotizaciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($cotizaciones->hasPages())
            <div class="card-footer">{{ $cotizaciones->links() }}</div>
        @endif
    </div>
@stop
