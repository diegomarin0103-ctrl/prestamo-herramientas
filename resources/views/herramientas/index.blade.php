@extends('layouts.app')

@section('title', 'Herramientas')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0"><i class="bi bi-wrench-adjustable"></i> Herramientas</h1>
        <a href="{{ route('herramientas.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nueva herramienta</a>
    </div>

    <form method="GET" action="{{ route('herramientas.index') }}" class="row g-2 mb-3">
        <div class="col-sm-8 col-md-6">
            <input type="search" name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por código, nombre, tipo o marca">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i> Buscar</button>
            @if ($buscar)
                <a href="{{ route('herramientas.index') }}" class="btn btn-link">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th class="text-center">Disponibles</th>
                        <th class="text-center">Prestadas</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($herramientas as $herramienta)
                        <tr>
                            <td class="fw-semibold text-nowrap">{{ $herramienta->codigo }}</td>
                            <td>{{ $herramienta->nombre }}</td>
                            <td>{{ $herramienta->tipo }}</td>
                            <td>{{ $herramienta->marca }}</td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $herramienta->cantidad_disponible > 0 ? 'text-bg-light border' : 'text-bg-danger' }}">{{ $herramienta->cantidad_disponible }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('prestamos.index', ['herramienta_id' => $herramienta->id]) }}" class="text-decoration-none">{{ (int) $herramienta->unidades_prestadas }}</a>
                            </td>
                            <td><span class="badge text-bg-{{ $herramienta->colorEstado() }}">{{ \App\Models\Herramienta::ESTADOS[$herramienta->estado] ?? $herramienta->estado }}</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('herramientas.show', $herramienta) }}" class="btn btn-sm btn-outline-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('herramientas.edit', $herramienta) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('herramientas.destroy', $herramienta) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar la herramienta {{ $herramienta->codigo }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay herramientas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $herramientas->links() }}
    </div>
@endsection
