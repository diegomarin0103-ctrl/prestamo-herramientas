@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0"><i class="bi bi-arrow-left-right"></i> Préstamos de herramientas</h1>
        <a href="{{ route('prestamos.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nuevo préstamo</a>
    </div>

    <form method="GET" action="{{ route('prestamos.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="search" name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por responsable">
        </div>
        <div class="col-sm-6 col-md-3">
            <select name="herramienta_id" class="form-select" aria-label="Filtrar por herramienta">
                <option value="">Todas las herramientas</option>
                @foreach ($herramientas as $herramienta)
                    <option value="{{ $herramienta->id }}" @selected($herramientaId == $herramienta->id)>{{ $herramienta->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6 col-md-2">
            <select name="estado" class="form-select" aria-label="Filtrar por estado">
                <option value="">Todos los estados</option>
                @foreach (\App\Models\Prestamo::ESTADOS as $valor => $texto)
                    <option value="{{ $valor }}" @selected($estado === $valor)>{{ $texto }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i> Buscar</button>
            @if ($buscar || $estado || $herramientaId)
                <a href="{{ route('prestamos.index') }}" class="btn btn-link">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Herramienta</th>
                        <th>Responsable</th>
                        <th>Préstamo</th>
                        <th>Devolución</th>
                        <th class="text-center">Cantidad</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prestamos as $prestamo)
                        <tr>
                            <td class="text-muted">{{ $prestamo->id }}</td>
                            <td>
                                <a href="{{ route('herramientas.show', $prestamo->herramienta) }}" class="text-decoration-none">{{ $prestamo->herramienta->nombre }}</a>
                                <div class="small text-muted">{{ $prestamo->herramienta->codigo }}</div>
                            </td>
                            <td>{{ $prestamo->nombre_responsable }}</td>
                            <td>{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</td>
                            <td>{{ $prestamo->fecha_devolucion?->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-center">{{ $prestamo->cantidad_prestada }}</td>
                            <td><span class="badge text-bg-{{ $prestamo->colorEstado() }}">{{ \App\Models\Prestamo::ESTADOS[$prestamo->estado] ?? $prestamo->estado }}</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('prestamos.show', $prestamo) }}" class="btn btn-sm btn-outline-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('prestamos.edit', $prestamo) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar el préstamo #{{ $prestamo->id }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay préstamos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $prestamos->links() }}
    </div>
@endsection
