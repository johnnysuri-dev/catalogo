@extends('adminlte::page')
@section('title', 'Cotización '.$cotizacion->codigo)
@section('content_header')
    <h1>Cotización {{ $cotizacion->codigo }}</h1>
@stop
@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Productos solicitados</div>
                <div class="card-body table-responsive p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Producto</th><th>Precio unitario</th><th>Cantidad</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($cotizacion->detalles as $detalle)
                                <tr>
                                    <td>
                                        @if ($detalle->fotografia)
                                            <img src="{{ asset('storage/'.$detalle->fotografia) }}" alt="Fotografía de {{ $detalle->nombre_producto }}" style="width:64px;height:52px;object-fit:cover;border-radius:5px" class="mr-2">
                                        @endif
                                        {{ $detalle->nombre_producto }}<br><small class="text-muted">{{ $detalle->sku }}</small>
                                    </td>
                                    <td>{{ $cotizacion->moneda }} {{ number_format((float) $detalle->precio_unitario, 2) }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>{{ $cotizacion->moneda }} {{ number_format((float) $detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot><tr><th colspan="3" class="text-right">Total estimado</th><th>{{ $cotizacion->moneda }} {{ number_format((float) $cotizacion->total, 2) }}</th></tr></tfoot>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Datos del cliente</div>
                <div class="card-body">
                    <p><strong>{{ $cotizacion->nombre_cliente }}</strong>@if ($cotizacion->empresa) · {{ $cotizacion->empresa }}@endif</p>
                    <p class="mb-1"><a href="mailto:{{ $cotizacion->correo }}">{{ $cotizacion->correo }}</a></p>
                    <p><a href="tel:{{ $cotizacion->telefono }}">{{ $cotizacion->telefono }}</a></p>
                    <p><strong>Mensaje:</strong> {{ $cotizacion->mensaje ?: 'Sin comentarios adicionales.' }}</p>
                    <p class="mb-0"><strong>Recibida:</strong> {{ $cotizacion->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Seguimiento</div>
                <form action="{{ route('cotizaciones.update', $cotizacion) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="card-body">
                        <label for="estado">Estado de la solicitud</label>
                        <select id="estado" name="estado" class="form-control">
                            @foreach (['pendiente' => 'Pendiente', 'enviada' => 'Enviada', 'aceptada' => 'Aceptada', 'rechazada' => 'Rechazada', 'vencida' => 'Vencida'] as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(old('estado', $cotizacion->estado) === $valor)>{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        @error('estado')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                    </div>
                    <div class="card-footer"><button class="btn btn-primary">Actualizar estado</button></div>
                </form>
            </div>
        </div>
    </div>
    <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Volver a cotizaciones</a>
@stop
