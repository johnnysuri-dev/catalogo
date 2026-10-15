@extends('adminlte::page')
@section('title', $categoria->exists ? 'Editar categoría' : 'Nueva categoría')
@section('content_header')
    <h1>{{ $categoria->exists ? 'Editar categoría' : 'Nueva categoría' }}</h1>
@stop
@section('content')
    <div class="card">
        <form action="{{ $categoria->exists ? route('categorias.update', $categoria) : route('categorias.store') }}" method="POST">
            @csrf
            @if ($categoria->exists) @method('PUT') @endif
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <div class="form-group">
                    <label for="nombre_categoria">Nombre de la categoría</label>
                    <input id="nombre_categoria" name="nombre_categoria" class="form-control" value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}" required maxlength="80" placeholder="Ej. Computadoras">
                </div>
                <div class="form-group">
                    <label for="activo">Estado</label>
                    <select id="activo" name="activo" class="form-control" required>
                        <option value="1" @selected((string) old('activo', $categoria->exists ? (int) $categoria->activo : 1) === '1')>Activa — disponible en el catálogo</option>
                        <option value="0" @selected((string) old('activo', $categoria->exists ? (int) $categoria->activo : 1) === '0')>Inactiva — oculta del catálogo</option>
                    </select>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar categoría</button>
            </div>
        </form>
    </div>
@stop
