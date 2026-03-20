@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
@php
    $userData = old() ? array_merge($user ?? [], old()) : ($user ?? []);
    $avatarUrl = $userData['avatar'] ?? $userData['image'] ?? null;
@endphp

<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-slate-800">Mi perfil</h1>

    <div class="bg-white border border-slate-200 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Información actual</h2>
        <div class="grid sm:grid-cols-2 gap-4 text-sm">
            <p><span class="font-semibold text-slate-700">Nombre:</span> {{ $user['name'] ?? 'Sin definir' }}</p>
            <p><span class="font-semibold text-slate-700">Correo:</span> {{ $user['email'] ?? 'Sin definir' }}</p>
            <p><span class="font-semibold text-slate-700">Teléfono:</span> {{ $user['phone'] ?? 'Sin definir' }}</p>
            <p><span class="font-semibold text-slate-700">ID:</span> {{ $user['id'] ?? 'N/A' }}</p>
        </div>

        @if($avatarUrl)
            <div class="mt-4">
                <p class="font-semibold text-slate-700 mb-2">Imagen de perfil</p>
                <img src="{{ $avatarUrl }}" alt="Avatar" class="w-28 h-28 object-cover rounded-full border border-slate-200">
            </div>
        @endif
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Actualizar datos generales</h2>
        <form action="{{ route('profile.update.info') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $userData['name'] ?? '') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $userData['email'] ?? '') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $userData['phone'] ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                Guardar datos
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Actualizar imagen de perfil</h2>
        <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="avatar" class="block text-sm font-medium text-slate-700 mb-1">Selecciona una imagen</label>
                <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/*" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-xs text-slate-500">Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 2MB.</p>
            </div>

            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                Subir imagen
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Actualizar contraseña</h2>
        <form action="{{ route('profile.update.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña actual</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Nueva contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-xs text-slate-500">Mínimo 8 caracteres, con mayúscula, minúscula y número.</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar nueva contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                Cambiar contraseña
            </button>
        </form>
    </div>
</div>
@endsection
