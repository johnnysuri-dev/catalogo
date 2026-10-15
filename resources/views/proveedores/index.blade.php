@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Proveedores</h1>
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nuevo proveedor
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
            <form method="GET" action="{{ route('proveedores.index') }}" class="row g-2">
                <div class="col-sm-8 col-md-5">
                    <label class="visually-hidden" for="buscar">Buscar proveedores</label>
                    <input id="buscar" type="search" name="buscar" value="{{ $termino }}" class="form-control" placeholder="Empresa, contacto, teléfono o correo">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Buscar</button>
                    @if ($termino !== '')
                        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Empresa</th>
                        <th scope="col">Contacto</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Correo</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proveedores as $proveedor)
                        <tr>
                            <td>{{ $proveedor->nombre_empresa }}</td>
                            <td>{{ $proveedor->contacto ?: '—' }}</td>
                            <td>{{ $proveedor->telefono ?: '—' }}</td>
                            <td>{{ $proveedor->correo ?: '—' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('proveedores.show', $proveedor) }}" class="btn btn-sm btn-outline-secondary" aria-label="Ver proveedor" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar proveedor" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este proveedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar proveedor" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No hay proveedores para mostrar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted">{{ $proveedores->total() }} proveedor(es)</span>
            {{ $proveedores->links() }}
        </div>
    </div>
@stop