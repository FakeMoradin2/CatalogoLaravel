@extends('layouts.app')

@section('title', 'Pagar pedido')

@section('content')
<div class="max-w-lg mx-auto space-y-6">
    <a href="{{ route('pedidos.show', ['id' => $orderId]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">← Volver al pedido</a>

    <div class="bg-white rounded-lg border border-slate-200 shadow p-6 space-y-2">
        <h1 class="text-2xl font-bold text-slate-800">Pago con Stripe Checkout</h1>
        <p class="text-sm text-slate-600">Pedido <span class="font-semibold">{{ $order['numero'] ?? 'N/A' }}</span></p>
        <p class="text-lg font-semibold text-indigo-600">Total: ${{ number_format((float) ($order['total'] ?? 0), 2) }}</p>
        <p class="text-sm text-slate-600 pt-2">Serás redirigido a la <strong>pantalla segura de Stripe</strong> para ingresar la tarjeta. Si en el servidor está activado <code class="text-xs bg-slate-100 px-1 rounded">allow_promotion_codes</code>, podrás aplicar <strong>códigos promocionales creados en el Dashboard de Stripe</strong> (no son los cupones del carrito de la tienda).</p>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow p-6">
        <form method="post" action="{{ route('pedidos.pago.checkout', ['id' => $orderId]) }}" class="space-y-4">
            @csrf
            <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                Continuar al pago seguro (Stripe)
            </button>
        </form>
    </div>

    <p class="text-xs text-slate-500 text-center space-y-1">
        <span class="block">Con <strong>claves de prueba</strong> (pk_test_ / sk_test_): en la pantalla de Stripe usa la tarjeta 4242 4242 4242 4242, cualquier CVC y fecha futura.</span>
        <span class="block">Con <strong>claves en vivo</strong> no se pueden usar tarjetas de prueba; el cobro debe ser real.</span>
        <span class="block">En MXN, Stripe suele exigir un mínimo de <strong>$10.00 MXN</strong> por cargo.</span>
    </p>
</div>
@endsection
