{{-- Campos compartidos por los formularios de crear y editar --}}
<div class="row g-3">
    <div class="col-md-8">
        <label for="herramienta_id" class="form-label">Herramienta *</label>
        <select id="herramienta_id" name="herramienta_id" required class="form-select @error('herramienta_id') is-invalid @enderror">
            <option value="">Seleccione una herramienta…</option>
            @foreach ($herramientas as $herramienta)
                <option value="{{ $herramienta->id }}" @selected(old('herramienta_id', $prestamo->herramienta_id) == $herramienta->id)>
                    {{ $herramienta->codigo }} — {{ $herramienta->nombre }} ({{ $herramienta->cantidad_disponible }} disponibles{{ $herramienta->estado !== 'activa' ? ', '.mb_strtolower(\App\Models\Herramienta::ESTADOS[$herramienta->estado]) : '' }})
                </option>
            @endforeach
        </select>
        @error('herramienta_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($herramientas->isEmpty())
            <div class="form-text text-danger">No hay herramientas. <a href="{{ route('herramientas.create') }}">Registra una herramienta</a> primero.</div>
        @endif
    </div>

    <div class="col-md-4">
        <label for="cantidad_prestada" class="form-label">Cantidad prestada *</label>
        <input type="number" id="cantidad_prestada" name="cantidad_prestada" min="1" step="1" required
               value="{{ old('cantidad_prestada', $prestamo->cantidad_prestada ?? 1) }}"
               class="form-control @error('cantidad_prestada') is-invalid @enderror">
        @error('cantidad_prestada') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="nombre_responsable" class="form-label">Nombre del responsable *</label>
        <input type="text" id="nombre_responsable" name="nombre_responsable" maxlength="120" required
               value="{{ old('nombre_responsable', $prestamo->nombre_responsable) }}"
               class="form-control @error('nombre_responsable') is-invalid @enderror">
        @error('nombre_responsable') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="fecha_prestamo" class="form-label">Fecha de préstamo *</label>
        <input type="date" id="fecha_prestamo" name="fecha_prestamo" required
               value="{{ old('fecha_prestamo', $prestamo->fecha_prestamo?->format('Y-m-d')) }}"
               class="form-control @error('fecha_prestamo') is-invalid @enderror">
        @error('fecha_prestamo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="fecha_devolucion" class="form-label">Fecha de devolución</label>
        <input type="date" id="fecha_devolucion" name="fecha_devolucion"
               value="{{ old('fecha_devolucion', $prestamo->fecha_devolucion?->format('Y-m-d')) }}"
               class="form-control @error('fecha_devolucion') is-invalid @enderror">
        @error('fecha_devolucion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Fecha pactada o real de devolución.</div>
    </div>

    <div class="col-md-4">
        <label for="estado" class="form-label">Estado del préstamo *</label>
        <select id="estado" name="estado" required class="form-select @error('estado') is-invalid @enderror">
            @foreach (\App\Models\Prestamo::ESTADOS as $valor => $texto)
                <option value="{{ $valor }}" @selected(old('estado', $prestamo->estado) === $valor)>{{ $texto }}</option>
            @endforeach
        </select>
        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
    <a href="{{ route('prestamos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
