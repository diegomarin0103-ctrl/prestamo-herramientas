@extends('layouts.app')

@section('title', 'Editar herramienta')

@section('content')
    <h1 class="h3 mb-3"><i class="bi bi-pencil-square"></i> Editar herramienta {{ $herramienta->codigo }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('herramientas.update', $herramienta) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('herramientas._form')
            </form>
        </div>
    </div>
@endsection
