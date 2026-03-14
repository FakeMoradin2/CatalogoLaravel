@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="max-w-4xl mx-auto text-center py-12">
    <h1 class="text-4xl font-bold text-slate-800 mb-4">Bienvenido a Mi Catálogo</h1>
    <p class="text-lg text-slate-600 mb-8">
        Explora nuestra selección de productos y descubre las mejores opciones para ti.
    </p>
    <a href="{{ route('productos.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow">
        Ver catálogo de productos
    </a>
</div>
@endsection
