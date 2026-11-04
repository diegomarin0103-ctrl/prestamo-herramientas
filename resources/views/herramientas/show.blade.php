@extends('layouts.app')

@section('title', 'Detalle de la herramienta')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0"><i class="bi bi-wrench-adjustable"></i> {{ $herramienta->nombre }}</h1>
        <div class="d-flex gap-2">
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
@endsection
