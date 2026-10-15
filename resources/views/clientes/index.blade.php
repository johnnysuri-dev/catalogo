@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Nuevo cliente
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('clientes.index') }}" class="row g-2">
                <div class="col-sm-8 col-md-5">
                    <label class="visually-hidden" for="buscar">Buscar clientes</label>
                    <input id="buscar" type="search" name="buscar" value="{{ $termino }}" class="form-control" placeholder="Nombre, empresa, NIT/CI o correo">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Buscar</button>
                    @if ($termino !== '')
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nombre completo</th>
                        <th scope="col">Empresa</th>
                        <th scope="col">NIT/CI</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre_completo }}</td>
                            <td>{{ $cliente->empresa ?: '—' }}</td>
                            <td>{{ $cliente->nit_ci ?: '—' }}</td>
                            <td>{{ $cliente->correo ?: '—' }}</td>
                            <td>{{ $cliente->telefono ?: '—' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-secondary" aria-label="Ver cliente" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar cliente" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este cliente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar cliente" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No hay clientes para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted">{{ $clientes->total() }} cliente(s)</span>
            {{ $clientes->links() }}
        </div>
    </div>
@stop