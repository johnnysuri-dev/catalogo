@extends('adminlte::page')
@section('title', 'Productos')
@section('content_header')
    <h1>Catálogo de productos</h1>
@stop
@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Administra los productos que aparecen en el catálogo online.</span>
            <a href="{{ route('productos.create') }}" class="btn btn-primary">Nuevo producto</a>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th>Producto</th><th>SKU</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->sku }}</td>
                            <td>{{ $producto->categoria?->nombre_categoria ?? 'Sin categoría' }}</td>
                            <td>{{ config('catalogo.moneda', 'USD') }} {{ number_format((float) $producto->precio, 2) }}</td>
                            <td>{{ $producto->stock }}</td>
                            <td><span class="badge {{ $producto->activo ? 'badge-success' : 'badge-secondary' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                @if ($producto->activo)
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar este producto?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Desactivar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center p-4">Aún no hay productos en el catálogo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($productos->hasPages())
            <div class="card-footer">{{ $productos->links() }}</div>
        @endif
    </div>
@stop
