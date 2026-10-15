@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Usuarios</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Nuevo usuario</a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de usuarios</h3>
        </div>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('users.index') }}" class="row g-2">
                <div class="col-sm-8 col-md-5">
                    <label class="visually-hidden" for="buscar">Buscar usuarios</label>
                    <input id="buscar" type="search" name="buscar" value="{{ $termino }}" class="form-control" placeholder="Nombre o correo">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Buscar</button>
                    @if ($termino !== '')
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id_usuario }}</td>
                                <td>{{ $user->nombre }}</td>
                                <td>{{ $user->correo }}</td>
                                <td>{{ $user->rol }}</td>
                                <td>
                                    <span class="badge {{ $user->estado ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-secondary" aria-label="Ver usuario" title="Ver"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar usuario" title="Editar"><i class="bi bi-pencil"></i></a>
                                    @if (! $user->is(auth()->user()))
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar usuario" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <span class="text-muted">{{ $users->total() }} usuario(s)</span>
            {{ $users->links() }}
        </div>
    </div>
@stop