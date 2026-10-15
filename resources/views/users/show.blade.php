@extends('adminlte::page')

@section('title', 'Detalle del usuario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle del usuario</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Volver</a>
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
                <dt class="col-sm-3">Nombre</dt><dd class="col-sm-9">{{ $user->nombre }}</dd>
                <dt class="col-sm-3">Correo</dt><dd class="col-sm-9">{{ $user->correo }}</dd>
                <dt class="col-sm-3">Rol</dt><dd class="col-sm-9">{{ $user->rol }}</dd>
                <dt class="col-sm-3">Estado</dt><dd class="col-sm-9"><span class="badge {{ $user->estado ? 'bg-success' : 'bg-secondary' }}">{{ $user->estado ? 'Activo' : 'Inactivo' }}</span></dd>
                <dt class="col-sm-3">Creado</dt><dd class="col-sm-9">{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>
    </div>
@stop