<div class="row g-3">
    <div class="col-md-6">
        <label for="nombre_completo" class="form-label">Nombre completo <span class="text-danger">*</span></label>
        <input id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo', $cliente->nombre_completo) }}" maxlength="150" required class="form-control @error('nombre_completo') is-invalid @enderror">
        @error('nombre_completo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="empresa" class="form-label">Empresa</label>
        <input id="empresa" name="empresa" value="{{ old('empresa', $cliente->empresa) }}" maxlength="100" class="form-control @error('empresa') is-invalid @enderror">
        @error('empresa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="nit_ci" class="form-label">NIT/CI</label>
        <input id="nit_ci" name="nit_ci" value="{{ old('nit_ci', $cliente->nit_ci) }}" maxlength="30" class="form-control @error('nit_ci') is-invalid @enderror">
        @error('nit_ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="correo" class="form-label">Correo</label>
        <input id="correo" type="email" name="correo" value="{{ old('correo', $cliente->correo) }}" maxlength="100" class="form-control @error('correo') is-invalid @enderror">
        @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono</label>
        <input id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" maxlength="20" class="form-control @error('telefono') is-invalid @enderror">
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="direccion" class="form-label">Dirección</label>
        <textarea id="direccion" name="direccion" rows="3" class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion', $cliente->direccion) }}</textarea>
        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>{{ $cliente->exists ? 'Guardar cambios' : 'Crear cliente' }}</button>
    <a href="{{ $cliente->exists ? route('clientes.show', $cliente) : route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>