@extends('layouts.app')

@section('title', 'Editar préstamo')

@section('content')
    <h1 class="h3 mb-3"><i class="bi bi-pencil-square"></i> Editar préstamo #{{ $prestamo->id }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('prestamos.update', $prestamo) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('prestamos._form')
            </form>
        </div>
    </div>
@endsection
