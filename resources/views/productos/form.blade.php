@extends('adminlte::page')
@section('title', $producto->exists ? 'Editar producto' : 'Nuevo producto')
@section('content_header')
    <h1>{{ $producto->exists ? 'Editar producto' : 'Nuevo producto' }}</h1>
@stop
@section('content')
    <div class="card">
        <form action="{{ $producto->exists ? route('productos.update', $producto) : route('productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($producto->exists) @method('PUT') @endif
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <div class="form-group"><label for="nombre">Nombre del producto</label><input id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required maxlength="150"></div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label for="sku">SKU</label><input id="sku" name="sku" class="form-control" value="{{ old('sku', $producto->sku) }}" required maxlength="60"></div>
                    <div class="form-group col-md-6">
                        <label for="categoria_id">Categoría</label>
                        <select id="categoria_id" name="categoria_id" class="form-control" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categorias as $opcion)
                                <option value="{{ $opcion->id }}" @selected((string) old('categoria_id', $producto->categoria_id) === (string) $opcion->id)>
                                    {{ $opcion->nombre_categoria }}{{ $opcion->activo ? '' : ' (inactiva)' }}
                                </option>
                            @endforeach
                        </select>
                        @if ($categorias->isEmpty())
                            <small class="text-danger">Crea una categoría antes de registrar productos. <a href="{{ route('categorias.create') }}">Crear categoría</a></small>
                        @else
                            <small class="form-text text-muted"><a href="{{ route('categorias.index') }}">Administrar categorías</a></small>
                        @endif
                    </div>
                </div>
                <div class="form-group"><label for="descripcion">Descripción</label><textarea id="descripcion" name="descripcion" class="form-control" rows="3" maxlength="2000">{{ old('descripcion', $producto->descripcion) }}</textarea></div>
                <div class="form-group">
                    <label for="fotografias">Fotografías del producto (máximo 3)</label>
                    <input
                        id="fotografias"
                        name="fotografias[]"
                        type="file"
                        class="form-control-file"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                    >
                    <small class="form-text text-muted">Sube imágenes JPG, PNG o WebP de hasta 5 MB cada una. Las fotos se muestran en el catálogo y en el detalle de la cotización.</small>
                    <div id="photo-preview" class="d-flex flex-wrap mt-3" style="gap:12px"></div>
                    @if ($producto->exists && count($producto->fotos ?? []) > 0)
                        <div class="mt-3">
                            <strong>Fotografías guardadas</strong>
                            <div class="d-flex flex-wrap mt-2" style="gap:12px">
                                @foreach ($producto->fotos as $fotografia)
                                    <label class="text-center" style="width:132px">
                                        <img src="{{ asset('storage/'.$fotografia) }}" alt="Fotografía de {{ $producto->nombre }}" style="width:132px;height:94px;object-fit:cover;border-radius:6px">
                                        <span class="d-block mt-1"><input type="checkbox" name="eliminar_fotografias[]" value="{{ $fotografia }}" @checked(in_array($fotografia, old('eliminar_fotografias', []), true))> Eliminar foto</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label for="precio">Precio ({{ config('catalogo.moneda', 'USD') }})</label><input id="precio" type="number" name="precio" class="form-control" value="{{ old('precio', $producto->precio) }}" min="0.01" step="0.01" required></div>
                    <div class="form-group col-md-6"><label for="stock">Unidades disponibles</label><input id="stock" type="number" name="stock" class="form-control" value="{{ old('stock', $producto->stock ?? 0) }}" min="0" required></div>
                </div>
                <div class="form-group"><label for="activo">Visibilidad</label><select id="activo" name="activo" class="form-control" required><option value="1" @selected((string) old('activo', $producto->exists ? (int) $producto->activo : 1) === '1')>Activo — visible en el catálogo</option><option value="0" @selected((string) old('activo', $producto->exists ? (int) $producto->activo : 1) === '0')>Inactivo — oculto del catálogo</option></select></div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar producto</button>
            </div>
        </form>
    </div>
@stop
@section('js')
    <script>
        (() => {
            const input = document.getElementById('fotografias');
            const preview = document.getElementById('photo-preview');
            const existingPhotos = {{ count($producto->fotos ?? []) }};
            const removeCheckboxes = [...document.querySelectorAll('input[name="eliminar_fotografias[]"]')];

            function updatePhotoLimit() {
                const remaining = existingPhotos - removeCheckboxes.filter(checkbox => checkbox.checked).length;
                input.max = Math.max(0, 3 - remaining);
                input.disabled = input.max === 0;
                input.title = input.disabled ? 'Elimina una foto guardada para agregar otra.' : '';
            }

            removeCheckboxes.forEach(checkbox => checkbox.addEventListener('change', updatePhotoLimit));
            input.addEventListener('change', () => {
                preview.replaceChildren();
                const maxNewPhotos = Number(input.max);

                if (input.files.length > maxNewPhotos) {
                    input.setCustomValidity(`Puedes seleccionar como máximo ${maxNewPhotos} fotografía(s) adicionales.`);
                    input.reportValidity();
                    input.value = '';
                    return;
                }

                input.setCustomValidity('');
                [...input.files].forEach(file => {
                    const image = document.createElement('img');
                    image.src = URL.createObjectURL(file);
                    image.alt = `Vista previa: ${file.name}`;
                    image.style.cssText = 'width:132px;height:94px;object-fit:cover;border-radius:6px';
                    preview.append(image);
                });
            });

            updatePhotoLimit();
        })();
    </script>
@stop
