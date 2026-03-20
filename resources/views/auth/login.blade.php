@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
<div class="max-w-md mx-auto bg-white border border-slate-200 rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Iniciar sesión</h1>

    <form action="{{ route('auth.login') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
        </div>

        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
            Entrar
        </button>
    </form>

    <p class="mt-6 text-sm text-slate-600">
        ¿Aún no tienes cuenta?
        <a href="{{ route('auth.register.form') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Regístrate aquí</a>.
    </p>
</div>
@endsection
