<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Singgalang Jaya Travel'))</title>
        <meta name="description" content="@yield('meta_description', 'Layanan jasa transportasi travel antar kota Padang Panjang - Pekanbaru PP dengan sistem door-to-door.')">

        <!-- Google Fonts: Poppins -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @livewireStyles
    </head>
    <body class="font-poppins antialiased min-h-screen bg-slate-50 flex flex-col">
        <!-- Public Navbar -->
        @include('layouts.partials.public-navbar')

        <!-- Page Content -->
        <main class="flex-1 flex flex-col">
            @yield('content')
        </main>

        <!-- Public Footer -->
        @include('layouts.partials.public-footer')

        @livewireScripts
    </body>
</html>
