<?php

use App\Http\Controllers\HerramientaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/herramientas')->name('inicio');

Route::resource('herramientas', HerramientaController::class);
