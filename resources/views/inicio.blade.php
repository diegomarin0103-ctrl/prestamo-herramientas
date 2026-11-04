@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="p-4 p-md-5 mb-4 bg-white rounded-3 shadow-sm">
        <h1 class="display-6 fw-semibold">Sistema de préstamos de herramientas</h1>
        <p class="lead text-muted mb-0">Registra las herramientas del taller y controla cuáles se encuentran prestadas y a quién.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5"><i class="bi bi-wrench-adjustable text-success"></i> Herramientas</h2>
                    <p class="display-6 mb-1">{{ \App\Models\Herramienta::count() }}</p>
                    <p class="text-muted">herramientas registradas</p>
                    <a href="{{ route('herramientas.index') }}" class="btn btn-success">Ir a herramientas</a>
                    <a href="{{ route('herramientas.create') }}" class="btn btn-outline-success">Registrar herramienta</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5"><i class="bi bi-arrow-left-right text-success"></i> Préstamos</h2>
                    <p class="display-6 mb-1">{{ \App\Models\Prestamo::count() }}</p>
                    <p class="text-muted">préstamos registrados</p>
                    <a href="{{ route('prestamos.index') }}" class="btn btn-success">Ir a préstamos</a>
                    <a href="{{ route('prestamos.create') }}" class="btn btn-outline-success">Registrar préstamo</a>
                </div>
            </div>
        </div>
    </div>
@endsection
