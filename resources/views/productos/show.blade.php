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

                <form action="{{ route('carrito.agregar') }}" method="POST" class="mt-8 flex flex-wrap items-center gap-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $producto['id'] ?? '' }}">
                    <input type="hidden" name="title" value="{{ $producto['title'] ?? '' }}">
                    <input type="hidden" name="price" value="{{ $producto['price'] ?? 0 }}">
                    <input type="hidden" name="image" value="{{ $producto['thumbnail'] ?? ($producto['images'][0] ?? '') }}">
                    <label for="quantity" class="text-sm font-medium text-slate-600">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ min(99, $producto['stock'] ?? 99) }}"
                        class="w-20 px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow">
                        Agregar al carrito
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
