@extends('layouts.app')

@section('title', $producto['title'] ?? 'Detalle del producto')

@section('content')
<div class="max-w-5xl mx-auto">
    <a href="{{ route('productos.index') }}" class="inline-block text-indigo-600 hover:text-indigo-800 mb-6 font-medium">← Volver al catálogo</a>

    <div class="bg-white rounded-lg shadow overflow-hidden border border-slate-200">
        <div class="grid md:grid-cols-2 gap-8 p-6 md:p-8">
            {{-- Imágenes del producto (las 3 primeras) --}}
            <div class="space-y-4">
                @php
                    $imagenes = $producto['images'] ?? [];
                    $imagenesMostrar = array_slice($imagenes, 0, 3);
                    if (empty($imagenesMostrar) && !empty($producto['thumbnail'])) {
                        $imagenesMostrar = [$producto['thumbnail']];
                    }
                @endphp
                @forelse($imagenesMostrar as $img)
                <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden">
                    <img src="{{ $img }}" alt="{{ $producto['title'] ?? 'Producto' }}" class="w-full h-full object-cover">
                </div>
                @empty
                <div class="aspect-video bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                    Sin imagen
                </div>
                @endforelse
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $producto['title'] ?? 'Sin nombre' }}</h1>
                <p class="text-3xl font-bold text-indigo-600 mt-2">${{ number_format($producto['price'] ?? 0, 2) }}</p>
                <p class="text-slate-600 mt-4 leading-relaxed">{{ $producto['description'] ?? 'Sin descripción.' }}</p>
                <div class="mt-6">
                    <span class="text-sm font-medium text-slate-500">Existencia:</span>
                    <span class="text-lg font-semibold text-slate-800">{{ $producto['stock'] ?? 0 }} unidades</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
