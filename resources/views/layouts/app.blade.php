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
                <nav class="flex flex-wrap gap-4">
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
