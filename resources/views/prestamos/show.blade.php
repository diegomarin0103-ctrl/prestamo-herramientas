@extends('layouts.app')

@section('title', 'Detalle del préstamo')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0"><i class="bi bi-arrow-left-right"></i> Préstamo #{{ $prestamo->id }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('prestamos.edit', $prestamo) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Editar</a>
            <a href="{{ route('prestamos.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Herramienta</dt>
                <dd class="col-sm-8">{{ $prestamo->herramienta_id }}</dd>

                <dt class="col-sm-4">Responsable</dt>
                <dd class="col-sm-8">{{ $prestamo->nombre_responsable }}</dd>

                <dt class="col-sm-4">Cantidad prestada</dt>
                <dd class="col-sm-8">{{ $prestamo->cantidad_prestada }}</dd>

                <dt class="col-sm-4">Fecha de préstamo</dt>
                <dd class="col-sm-8">{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</dd>

                <dt class="col-sm-4">Fecha de devolución</dt>
                <dd class="col-sm-8">{{ $prestamo->fecha_devolucion?->format('d/m/Y') ?? 'Sin definir' }}</dd>

                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8 mb-0"><span class="badge text-bg-{{ $prestamo->colorEstado() }}">{{ \App\Models\Prestamo::ESTADOS[$prestamo->estado] ?? $prestamo->estado }}</span></dd>
            </dl>
        </div>
    </div>
@endsection
