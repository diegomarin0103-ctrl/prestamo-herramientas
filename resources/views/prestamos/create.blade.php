@extends('layouts.app')

@section('title', 'Nuevo préstamo')

@section('content')
    <h1 class="h3 mb-3"><i class="bi bi-plus-circle"></i> Registrar préstamo</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('prestamos.store') }}" method="POST" novalidate>
                @csrf
                @include('prestamos._form')
            </form>
        </div>
    </div>
@endsection
