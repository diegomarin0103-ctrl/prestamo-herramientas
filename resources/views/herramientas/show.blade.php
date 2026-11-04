@extends('layouts.app')

@section('title', 'Detalle de la herramienta')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0"><i class="bi bi-wrench-adjustable"></i> {{ $herramienta->nombre }}</h1>
        <div class="d-flex gap-2">
            @if ($herramienta->estado === 'activa' && $herramienta->cantidad_disponible > 0)
                <a href="{{ route('prestamos.create', ['herramienta_id' => $herramienta->id]) }}" class="btn btn-outline-success"><i class="bi bi-box-arrow-up-right"></i> Prestar</a>
            @endif
            <a href="{{ route('herramientas.edit', $herramienta) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Editar</a>
            <a href="{{ route('herramientas.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Código</dt>
                <dd class="col-sm-8">{{ $herramienta->codigo }}</dd>

                <dt class="col-sm-4">Tipo</dt>
                <dd class="col-sm-8">{{ $herramienta->tipo }}</dd>

                <dt class="col-sm-4">Marca</dt>
                <dd class="col-sm-8">{{ $herramienta->marca }}</dd>

                <dt class="col-sm-4">Cantidad disponible</dt>
                <dd class="col-sm-8">{{ $herramienta->cantidad_disponible }}</dd>

                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8 mb-0"><span class="badge text-bg-{{ $herramienta->colorEstado() }}">{{ \App\Models\Herramienta::ESTADOS[$herramienta->estado] ?? $herramienta->estado }}</span></dd>
            </dl>
        </div>
    </div>

    <h2 class="h5 mb-3">Historial de préstamos ({{ $prestamos->count() }})</h2>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Responsable</th>
                        <th>Préstamo</th>
                        <th>Devolución</th>
                        <th class="text-center">Cantidad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prestamos as $prestamo)
                        <tr>
                            <td><a href="{{ route('prestamos.show', $prestamo) }}">{{ $prestamo->id }}</a></td>
                            <td>{{ $prestamo->nombre_responsable }}</td>
                            <td>{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</td>
                            <td>{{ $prestamo->fecha_devolucion?->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-center">{{ $prestamo->cantidad_prestada }}</td>
                            <td><span class="badge text-bg-{{ $prestamo->colorEstado() }}">{{ \App\Models\Prestamo::ESTADOS[$prestamo->estado] ?? $prestamo->estado }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Esta herramienta todavía no tiene préstamos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
