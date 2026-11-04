<?php

use App\Http\Controllers\HerramientaController;
use App\Http\Controllers\PrestamoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'inicio')->name('inicio');

Route::resource('herramientas', HerramientaController::class);
Route::resource('prestamos', PrestamoController::class);
