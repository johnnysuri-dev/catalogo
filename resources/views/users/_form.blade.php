<div class="row g-3">
    <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
        <input id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" maxlength="100" required class="form-control @error('nombre') is-invalid @enderror">
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
        <input id="correo" type="email" name="correo" value="{{ old('correo', $user->correo) }}" maxlength="100" required class="form-control @error('correo') is-invalid @enderror">
        @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">Contraseña @unless ($user->exists)<span class="text-danger">*</span>@endunless</label>
        <input id="password" type="password" name="password" {{ $user->exists ? '' : 'required' }} minlength="8" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
        @if ($user->exists)<div class="form-text">Déjala vacía para conservar la contraseña actual.</div>@endif
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirmar contraseña @unless ($user->exists)<span class="text-danger">*</span>@endunless</label>
        <input id="password_confirmation" type="password" name="password_confirmation" {{ $user->exists ? '' : 'required' }} minlength="8" autocomplete="new-password" class="form-control">
    </div>
    <div class="col-md-6">
        <label for="rol" class="form-label">Rol</label>
        <select id="rol" name="rol" required class="form-select @error('rol') is-invalid @enderror">
            @foreach (['Administrador', 'Vendedor', 'Cajero'] as $rol)
                <option value="{{ $rol }}" @selected(old('rol', $user->rol ?? 'Vendedor') === $rol)>{{ $rol }}</option>
            @endforeach
        </select>
        @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select id="estado" name="estado" required class="form-select @error('estado') is-invalid @enderror">
            <option value="1" @selected((string) old('estado', $user->exists ? (int) $user->estado : 1) === '1')>Activo</option>
            <option value="0" @selected((string) old('estado', $user->exists ? (int) $user->estado : 1) === '0')>Inactivo</option>
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>{{ $user->exists ? 'Guardar cambios' : 'Crear usuario' }}</button>
    <a href="{{ $user->exists ? route('users.show', $user) : route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>