@extends('layouts.app')

@section('title', 'Carrito de compras')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-800 mb-8">Carrito de compras</h1>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if(empty($carrito))
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-slate-600 mb-6">Tu carrito está vacío.</p>
            <a href="{{ route('productos.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                Ver catálogo de productos
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-left text-sm font-medium text-slate-600">
                            <th class="px-4 py-3">Imagen</th>
                            <th class="px-4 py-3">Producto</th>
                            <th class="px-4 py-3">Precio</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Subtotal</th>
                            <th class="px-4 py-3 w-20"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                            foreach ($carrito as $item) {
                                $total += $item['price'] * $item['quantity'];
                            }
                            $cuponResumen = session('carrito_cupon_resumen');
                            $cuponCodigo = session('carrito_cupon_codigo');
                        @endphp
                        @foreach($carrito as $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <td class="px-4 py-4">
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">Sin imagen</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('productos.show', $item['id']) }}" class="font-medium text-slate-800 hover:text-indigo-600">
                                    {{ $item['title'] }}
                                </a>
                            </td>
                            <td class="px-4 py-4 text-slate-700">${{ number_format($item['price'], 2) }}</td>
                            <td class="px-4 py-4">
                                <form action="{{ route('carrito.actualizar', $item['id']) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99"
                                        class="w-16 px-2 py-1.5 text-sm rounded border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium" title="Actualizar">
                                        Actualizar
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 font-semibold text-indigo-600">${{ number_format($subtotal, 2) }}</td>
                            <td class="px-4 py-4">
                                <form action="{{ route('carrito.eliminar', $item['id']) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto del carrito?')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(session('auth_token'))
                <div class="px-4 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <form action="{{ route('carrito.cupon.aplicar') }}" method="POST" class="flex flex-col sm:flex-row gap-2 sm:items-center flex-1 max-w-xl">
                        @csrf
                        <label class="text-sm font-medium text-slate-700 sr-only" for="coupon_code">Código de descuento</label>
                        <input type="text" name="coupon_code" id="coupon_code" placeholder="Código de descuento (ej. DESC10)"
                            class="flex-1 min-w-0 px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            value="{{ old('coupon_code', $cuponCodigo ?? '') }}" autocomplete="off">
                        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900 transition whitespace-nowrap">
                            Aplicar cupón
                        </button>
                    </form>
                    @if(!empty($cuponCodigo))
                        <form action="{{ route('carrito.cupon.quitar') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Quitar cupón</button>
                        </form>
                    @endif
                </div>
            @endif

            <div class="px-4 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-slate-800 space-y-1">
                    @if(session('auth_token') && is_array($cuponResumen) && isset($cuponResumen['subtotal']))
                        <p class="text-sm">Subtotal: <span class="font-semibold">${{ number_format((float) $cuponResumen['subtotal'], 2) }}</span></p>
                        @if (($cuponResumen['descuento'] ?? 0) > 0)
                            <p class="text-sm text-emerald-700">Descuento ({{ $cuponResumen['cupon_codigo'] ?? $cuponCodigo }}): <span class="font-semibold">−${{ number_format((float) $cuponResumen['descuento'], 2) }}</span></p>
                        @endif
                        <p class="text-lg font-bold">Total a pagar: <span class="text-indigo-600">${{ number_format((float) ($cuponResumen['total'] ?? $total), 2) }}</span></p>
                    @else
                        <p class="text-lg font-bold">Total: <span class="text-indigo-600">${{ number_format($total, 2) }}</span></p>
                    @endif
                </div>
                <div class="flex flex-wrap gap-3">
                    @if(session('auth_token'))
                        <form action="{{ route('pedidos.store') }}" method="POST" onsubmit="return confirm('¿Confirmas crear el pedido con los productos actuales del carrito?')">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                                Crear pedido
                            </button>
                        </form>
                    @else
                        <a href="{{ route('auth.login.form') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                            Inicia sesión para pedir
                        </a>
                    @endif
                    <form action="{{ route('carrito.vaciar') }}" method="POST" onsubmit="return confirm('¿Vaciar completamente el carrito?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-slate-600 hover:text-red-600 font-medium transition border border-slate-300 rounded-lg hover:border-red-300">
                            Vaciar carrito
                        </button>
                    </form>
                    <a href="{{ route('productos.index') }}" class="px-4 py-2 text-indigo-600 hover:text-indigo-800 font-medium transition">
                        Seguir comprando
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
