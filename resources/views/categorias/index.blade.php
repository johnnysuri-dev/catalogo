@extends('adminlte::page')
@section('title', 'Categorías')
@section('content_header')
    <h1>Categorías de productos</h1>
@stop
@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Organiza los productos y controla qué categorías se muestran en el catálogo.</span>
            <a href="{{ route('categorias.create') }}" class="btn btn-primary">Nueva categoría</a>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th>Categoría</th><th>Productos asignados</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre_categoria }}</td>
                            <td>{{ $categoria->productos_count }}</td>
                            <td><span class="badge {{ $categoria->activo ? 'badge-success' : 'badge-secondary' }}">{{ $categoria->activo ? 'Activa' : 'Inactiva' }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                @if ($categoria->productos_count === 0)
                                    <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center p-4">Aún no hay categorías.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categorias->hasPages())
            <div class="card-footer">{{ $categorias->links() }}</div>
        @endif
    </div>
@stop
