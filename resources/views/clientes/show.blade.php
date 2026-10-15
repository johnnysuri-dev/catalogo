@extends('adminlte::page')

@section('title', 'Detalle del cliente')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle del cliente</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nombre completo</dt><dd class="col-sm-9">{{ $cliente->nombre_completo }}</dd>
                <dt class="col-sm-3">Empresa</dt><dd class="col-sm-9">{{ $cliente->empresa ?: '—' }}</dd>
                <dt class="col-sm-3">NIT/CI</dt><dd class="col-sm-9">{{ $cliente->nit_ci ?: '—' }}</dd>
                <dt class="col-sm-3">Correo</dt><dd class="col-sm-9">{{ $cliente->correo ?: '—' }}</dd>
                <dt class="col-sm-3">Teléfono</dt><dd class="col-sm-9">{{ $cliente->telefono ?: '—' }}</dd>
                <dt class="col-sm-3">Dirección</dt><dd class="col-sm-9">{{ $cliente->direccion ?: '—' }}</dd>
            </dl>
        </div>
    </div>
@stop