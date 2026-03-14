<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - Mi Catálogo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900">
    {{-- Encabezado --}}
    <header class="bg-white shadow-sm border-b border-slate-200">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('inicio') }}" class="text-xl font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Mi Catálogo
                </a>
                {{-- Menú de navegación --}}
                <nav class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('carrito.index') }}" class="flex items-center gap-2 text-slate-600 hover:text-indigo-600 font-medium transition {{ request()->routeIs('carrito.*') ? 'text-indigo-600' : '' }}" title="Carrito">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Carrito</span>
                        @php $carrito = session('carrito', []); $carritoCount = array_sum(array_column($carrito, 'quantity')); @endphp
                        @if($carritoCount > 0)
                            <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold text-white bg-indigo-600 rounded-full">{{ $carritoCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('inicio') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition {{ request()->routeIs('inicio') ? 'text-indigo-600' : '' }}">
                        Inicio
                    </a>
                    <a href="{{ route('nosotros') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition {{ request()->routeIs('nosotros') ? 'text-indigo-600' : '' }}">
                        Nosotros
                    </a>
                    <a href="{{ route('productos.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition {{ request()->routeIs('productos.*') ? 'text-indigo-600' : '' }}">
                        Catálogo
                    </a>
                    <a href="{{ route('contacto') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition {{ request()->routeIs('contacto') ? 'text-indigo-600' : '' }}">
                        Contacto
                    </a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Contenido principal --}}
    <main class="flex-1 container mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- Pie de página --}}
    <footer class="bg-slate-800 text-slate-300 py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm">© {{ date('Y') }} Mi Catálogo. Todos los derechos reservados.</p>
                <nav class="flex gap-6">
                    <a href="{{ route('inicio') }}" class="hover:text-white transition">Inicio</a>
                    <a href="{{ route('nosotros') }}" class="hover:text-white transition">Nosotros</a>
                    <a href="{{ route('productos.index') }}" class="hover:text-white transition">Catálogo</a>
                    <a href="{{ route('contacto') }}" class="hover:text-white transition">Contacto</a>
                </nav>
            </div>
        </div>
    </footer>
</body>
</html>
