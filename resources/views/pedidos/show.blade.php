@extends('layouts.app')

@section('title', 'Detalle de pedido')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('pedidos.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">← Volver a mis pedidos</a>
        @if(($order['estado'] ?? '') !== 'cancelado' && !empty($order['id']))
            <form action="{{ route('pedidos.cancel', ['id' => $order['id']]) }}" method="POST" onsubmit="return confirm('¿Deseas cancelar este pedido?')">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                    Cancelar pedido
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-4">Pedido {{ $order['numero'] ?? 'N/A' }}</h1>

        <div class="grid sm:grid-cols-3 gap-4 text-sm">
            <p><span class="font-semibold text-slate-700">Fecha:</span> {{ $order['fecha'] ?? '-' }}</p>
            <p><span class="font-semibold text-slate-700">Estado:</span> {{ ucfirst($order['estado'] ?? 'desconocido') }}</p>
            <p><span class="font-semibold text-slate-700">Total:</span> <span class="text-indigo-600 font-semibold">${{ number_format((float) ($order['total'] ?? 0), 2) }}</span></p>
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
