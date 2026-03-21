@extends('layouts.app')

@section('title', 'Mis pedidos')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Mis pedidos</h1>
        <a href="{{ route('carrito.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Ir al carrito</a>
    </div>

    @if(empty($orders))
        <div class="bg-white rounded-lg border border-slate-200 shadow p-10 text-center">
            <p class="text-slate-600 mb-4">Aún no has creado pedidos.</p>
            <a href="{{ route('productos.index') }}" class="inline-block px-5 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                Explorar productos
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg border border-slate-200 shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-left text-sm font-medium text-slate-600">
                            <th class="px-4 py-3">Número</th>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/60">
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ $order['numero'] ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $order['fecha'] ?? '-' }}</td>
                            <td class="px-4 py-4">
                                @php $cancelado = ($order['estado'] ?? '') === 'cancelado'; @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $cancelado ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ ucfirst($order['estado'] ?? 'desconocido') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 font-semibold text-indigo-600">${{ number_format((float) ($order['total'] ?? 0), 2) }}</td>
                            <td class="px-4 py-4 text-right">
                                @if(!empty($order['id']))
                                    <a href="{{ route('pedidos.show', ['id' => $order['id']]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        Ver detalle
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
