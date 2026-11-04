@extends('layouts.app')

@section('title', 'Nueva herramienta')

@section('content')
    <h1 class="h3 mb-3"><i class="bi bi-plus-circle"></i> Registrar herramienta</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('herramientas.store') }}" method="POST" novalidate>
                @csrf
                @include('herramientas._form')
            </form>
        </div>
    </div>
@endsection
