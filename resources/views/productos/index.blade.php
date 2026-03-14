@extends('layouts.app')

@section('title', 'Catálogo de productos')

@section('content')
<h1 class="text-3xl font-bold text-slate-800 mb-8">Catálogo de productos</h1>

@if(empty($productos))
    <p class="text-slate-600">No hay productos disponibles en este momento.</p>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($productos as $producto)
        <a href="{{ route('productos.show', $producto['id']) }}" class="group bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden border border-slate-200">
            <div class="aspect-square bg-slate-100 overflow-hidden">
                <img src="{{ $producto['thumbnail'] ?? $producto['images'][0] ?? '' }}" alt="{{ $producto['title'] ?? 'Producto' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </div>
            <div class="p-4">
                <h2 class="font-semibold text-slate-800 group-hover:text-indigo-600 transition line-clamp-2">{{ $producto['title'] ?? 'Sin nombre' }}</h2>
                <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ $producto['description'] ?? '' }}</p>
                <p class="text-lg font-bold text-indigo-600 mt-2">${{ number_format($producto['price'] ?? 0, 2) }}</p>
                <span class="inline-block mt-2 text-indigo-600 text-sm font-medium group-hover:underline">Ver detalle →</span>
            </div>
        </a>
        @endforeach
    </div>
@endif
@endsection
