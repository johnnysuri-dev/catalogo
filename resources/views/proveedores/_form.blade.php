<div class="row g-3">
    <div class="col-md-6">
        <label for="nombre_empresa" class="form-label">Nombre de la empresa <span class="text-danger">*</span></label>
        <input id="nombre_empresa" name="nombre_empresa" value="{{ old('nombre_empresa', $proveedor->nombre_empresa) }}" maxlength="150" required class="form-control @error('nombre_empresa') is-invalid @enderror">
        @error('nombre_empresa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="contacto" class="form-label">Persona de contacto</label>
        <input id="contacto" name="contacto" value="{{ old('contacto', $proveedor->contacto) }}" maxlength="100" class="form-control @error('contacto') is-invalid @enderror">
        @error('contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono</label>
        <input id="telefono" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}" maxlength="20" class="form-control @error('telefono') is-invalid @enderror">
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="correo" class="form-label">Correo</label>
        <input id="correo" type="email" name="correo" value="{{ old('correo', $proveedor->correo) }}" maxlength="100" class="form-control @error('correo') is-invalid @enderror">
        @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>{{ $proveedor->exists ? 'Guardar cambios' : 'Crear proveedor' }}</button>
    <a href="{{ $proveedor->exists ? route('proveedores.show', $proveedor) : route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>