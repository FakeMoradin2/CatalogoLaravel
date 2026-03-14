<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [PageController::class, 'nosotros'])->name('nosotros');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::post('/contacto', [PageController::class, 'enviarContacto'])->name('contacto.enviar');
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');
