<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net"> --}}
        {{-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}
        {{-- <script src="https://unpkg.com/@phosphor-icons/web"></script> --}}

        <!-- Scripts -->
        {{--  Currency  --}}
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
        {{-- <x-theme-toggle class="hidden" /> --}}

        {{-- MAIN --}}
        {{-- <x-main full-width> --}}
            {{-- The `$slot` goes here --}}
            {{-- <x-slot:content> --}}
                {{ $slot }}
            {{-- </x-slot:content> --}}
        {{-- </x-main> --}}

        
        <x-change-theme class="my-3 mx-auto max-w-40" />

        {{-- Toast --}}
        <x-toast />
    </body>
</html>
