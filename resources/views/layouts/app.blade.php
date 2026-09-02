<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel LMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    {{-- Le body prend directement la couleur beige et occupe tout l'écran --}}
    <body class="font-sans antialiased text-lms-dark bg-slate-50 min-h-screen flex flex-col">
        
        {{-- INCLUSION DE LA NAVIGATION --}}
        @include('layouts.navigation')

        {{-- CONTENU DYNAMIQUE --}}
        {{-- On ajoute un max-w-7xl et mx-auto pour que le contenu reste centré sur les très grands écrans --}}
        <main class="flex-1 w-full max-w-7xl mx-auto px-6 sm:px-10 py-8">
            <x-flash-messages />
            {{ $slot }}
        </main>
        
    </body>
</html>