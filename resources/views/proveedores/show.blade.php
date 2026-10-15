@extends('adminlte::page')

@section('title', 'Detalle del proveedor')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle del proveedor</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Volver</a>
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
                <dt class="col-sm-3">Empresa</dt><dd class="col-sm-9">{{ $proveedor->nombre_empresa }}</dd>
                <dt class="col-sm-3">Contacto</dt><dd class="col-sm-9">{{ $proveedor->contacto ?: '—' }}</dd>
                <dt class="col-sm-3">Teléfono</dt><dd class="col-sm-9">{{ $proveedor->telefono ?: '—' }}</dd>
                <dt class="col-sm-3">Correo</dt><dd class="col-sm-9">{{ $proveedor->correo ?: '—' }}</dd>
            </dl>
        </div>
    </div>
@stop