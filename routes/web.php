<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoPagoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [PageController::class, 'nosotros'])->name('nosotros');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::post('/contacto', [PageController::class, 'enviarContacto'])->name('contacto.enviar');
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::post('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

Route::middleware('api.guest')->group(function (): void {
    Route::get('/registro', [AuthController::class, 'showRegisterForm'])->name('auth.register.form');
    Route::post('/registro', [AuthController::class, 'register'])->name('auth.register');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware('api.auth')->group(function (): void {
    Route::post('/carrito/cupon', [CarritoController::class, 'aplicarCupon'])->name('carrito.cupon.aplicar');
    Route::post('/carrito/cupon/quitar', [CarritoController::class, 'quitarCupon'])->name('carrito.cupon.quitar');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/perfil', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
    Route::post('/perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update.avatar');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::put('/pedidos/{id}/cancelar', [PedidoController::class, 'cancel'])->name('pedidos.cancel');

    Route::get('/pedidos/{id}/pagar', [PedidoPagoController::class, 'show'])->name('pedidos.pagar');
    Route::post('/pedidos/{id}/pago/checkout', [PedidoPagoController::class, 'checkout'])->name('pedidos.pago.checkout');
    Route::get('/pedidos/{id}/pago/regreso', [PedidoPagoController::class, 'checkoutReturn'])->name('pedidos.pago.checkout.return');
    Route::post('/pedidos/{id}/pago/preparar', [PedidoPagoController::class, 'prepare'])->name('pedidos.pago.prepare');
    Route::post('/pedidos/{id}/pago/confirmar', [PedidoPagoController::class, 'confirm'])->name('pedidos.pago.confirm');
});
