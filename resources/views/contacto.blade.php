@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-800 mb-6">Contacto</h1>
    <p class="text-slate-600 mb-8">
        ¿Tienes preguntas? Completa el formulario y te responderemos lo antes posible.
    </p>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 border border-red-200">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contacto.enviar') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf

        <div>
            <label for="nombre" class="block text-sm font-medium text-slate-700 mb-2">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nombre') border-red-500 @enderror"
                placeholder="Tu nombre completo" required autofocus>
            @error('nombre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                placeholder="tu@email.com" required>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="asunto" class="block text-sm font-medium text-slate-700 mb-2">Asunto</label>
            <input type="text" name="asunto" id="asunto" value="{{ old('asunto') }}"
                class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('asunto') border-red-500 @enderror"
                placeholder="¿Sobre qué quieres consultar?" required>
            @error('asunto')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="mensaje" class="block text-sm font-medium text-slate-700 mb-2">Mensaje</label>
            <textarea name="mensaje" id="mensaje" rows="5" required
                class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('mensaje') border-red-500 @enderror"
                placeholder="Escribe tu mensaje aquí...">{{ old('mensaje') }}</textarea>
            @error('mensaje')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full sm:w-auto px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            Enviar mensaje
        </button>
    </form>

    <div class="mt-8 p-6 bg-slate-100 rounded-lg">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">O contáctanos directamente</h2>
        <div class="space-y-3">
            <p class="text-slate-600"><span class="font-medium text-slate-700">Email:</span> contacto@micatalogo.com</p>
            <p class="text-slate-600"><span class="font-medium text-slate-700">Teléfono:</span> +1 234 567 890</p>
            <p class="text-slate-600"><span class="font-medium text-slate-700">Dirección:</span> Av. Principal 123, Ciudad</p>
        </div>
    </div>
</div>
@endsection
