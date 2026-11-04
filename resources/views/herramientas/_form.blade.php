{{-- Campos compartidos por los formularios de crear y editar --}}
<div class="row g-3">
    <div class="col-md-4">
        <label for="codigo" class="form-label">Código *</label>
        <input type="text" id="codigo" name="codigo" maxlength="30" required
               value="{{ old('codigo', $herramienta->codigo) }}"
               class="form-control @error('codigo') is-invalid @enderror">
        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8">
        <label for="nombre" class="form-label">Nombre *</label>
        <input type="text" id="nombre" name="nombre" maxlength="100" required
               value="{{ old('nombre', $herramienta->nombre) }}"
               class="form-control @error('nombre') is-invalid @enderror">
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="tipo" class="form-label">Tipo de herramienta *</label>
        <input type="text" id="tipo" name="tipo" maxlength="60" required list="tipos"
               value="{{ old('tipo', $herramienta->tipo) }}"
               class="form-control @error('tipo') is-invalid @enderror">
        <datalist id="tipos">
            @foreach (\App\Models\Herramienta::TIPOS as $tipo)
                <option value="{{ $tipo }}">
            @endforeach
        </datalist>
        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="marca" class="form-label">Marca *</label>
        <input type="text" id="marca" name="marca" maxlength="60" required
               value="{{ old('marca', $herramienta->marca) }}"
               class="form-control @error('marca') is-invalid @enderror">
        @error('marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="cantidad_disponible" class="form-label">Cantidad disponible *</label>
        <input type="number" id="cantidad_disponible" name="cantidad_disponible" min="0" step="1" required
               value="{{ old('cantidad_disponible', $herramienta->cantidad_disponible) }}"
               class="form-control @error('cantidad_disponible') is-invalid @enderror">
        @error('cantidad_disponible') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="estado" class="form-label">Estado *</label>
        <select id="estado" name="estado" required class="form-select @error('estado') is-invalid @enderror">
            @foreach (\App\Models\Herramienta::ESTADOS as $valor => $texto)
                <option value="{{ $valor }}" @selected(old('estado', $herramienta->estado) === $valor)>{{ $texto }}</option>
            @endforeach
        </select>
        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
    <a href="{{ route('herramientas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
