@extends('layouts.app')

@section('title', 'Detalle de pedido')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    @if(request('paid'))
        <div class="p-4 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
            El pago se registró correctamente. Los datos de la transacción aparecen abajo.
        </div>
    @endif
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('pedidos.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">← Volver a mis pedidos</a>
        <div class="flex flex-wrap gap-3">
            @php
                $estadoPedido = $order['estado'] ?? '';
                $pagoOk = ($order['payment_status'] ?? '') === 'pagado' || $estadoPedido === 'pagado';
            @endphp
            @if($estadoPedido === 'creado' && !$pagoOk && !empty($order['id']))
                <a href="{{ route('pedidos.pagar', ['id' => $order['id']]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">
                    Pagar con tarjeta
                </a>
            @endif
            @if($estadoPedido !== 'cancelado' && !$pagoOk && !empty($order['id']))
                <form action="{{ route('pedidos.cancel', ['id' => $order['id']]) }}" method="POST" onsubmit="return confirm('¿Deseas cancelar este pedido?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                        Cancelar pedido
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-4">Pedido {{ $order['numero'] ?? 'N/A' }}</h1>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <p><span class="font-semibold text-slate-700">Fecha:</span> {{ $order['fecha'] ?? '-' }}</p>
            <p><span class="font-semibold text-slate-700">Estado del pedido:</span> {{ ucfirst($order['estado'] ?? 'desconocido') }}</p>
            @if(isset($order['subtotal']))
                <p><span class="font-semibold text-slate-700">Subtotal:</span> ${{ number_format((float) $order['subtotal'], 2) }}</p>
            @endif
            @if(!empty($order['cupon_codigo']) && (float) ($order['descuento'] ?? 0) > 0)
                <p><span class="font-semibold text-slate-700">Cupón:</span> <span class="font-mono text-slate-800">{{ $order['cupon_codigo'] }}</span></p>
                <p><span class="font-semibold text-slate-700">Descuento:</span> <span class="text-emerald-700 font-semibold">−${{ number_format((float) ($order['descuento'] ?? 0), 2) }}</span></p>
            @endif
            <p><span class="font-semibold text-slate-700">Total:</span> <span class="text-indigo-600 font-semibold">${{ number_format((float) ($order['total'] ?? 0), 2) }}</span></p>
            <p><span class="font-semibold text-slate-700">Estado del pago:</span>
                @php $ps = $order['payment_status'] ?? 'pendiente'; @endphp
                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold {{ $ps === 'pagado' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ ucfirst($ps) }}</span>
            </p>
            @if(!empty($order['transaccion_id']))
                <p class="sm:col-span-2"><span class="font-semibold text-slate-700">Código de transacción (Stripe):</span> <code class="text-xs bg-slate-100 px-1 rounded">{{ $order['transaccion_id'] }}</code></p>
            @endif
            @if(!empty($order['fecha_pago']))
                <p><span class="font-semibold text-slate-700">Fecha del pago:</span> {{ $order['fecha_pago'] }}</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-left text-sm font-medium text-slate-600">
                        <th class="px-4 py-3">Producto</th>
                        <th class="px-4 py-3">Precio unitario</th>
                        <th class="px-4 py-3">Cantidad</th>
                        <th class="px-4 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $items = $order['items'] ?? []; @endphp
                    @forelse($items as $item)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-4 font-medium text-slate-800">{{ $item['title'] ?? 'Producto' }}</td>
                        <td class="px-4 py-4 text-slate-700">${{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                        <td class="px-4 py-4 text-slate-700">{{ (int) ($item['quantity'] ?? 0) }}</td>
                        <td class="px-4 py-4 font-semibold text-indigo-600">${{ number_format((float) ($item['subtotal'] ?? 0), 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">Este pedido no tiene items para mostrar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
