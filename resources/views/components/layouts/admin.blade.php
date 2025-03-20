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
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js" data-navigate-once></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
 
        {{-- NAVBAR mobile only --}}
        <x-nav sticky class="lg:hidden">
            <x-slot:brand>
                <div class="flex justify-center items-center gap-3 select-none">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="w-auto h-12" />
                    {{-- <span class="font-bold text-lg text-red-800 dark:text-red-700 drop-shadow-sm" style="--tw-drop-shadow: drop-shadow(0 1px 1px);" x-show="!collapsed">{{ config('app.name') }}</span> --}}
                </div>
            </x-slot:brand>
            <x-slot:actions>
                <label for="main-drawer" class="lg:hidden mr-3">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>
            </x-slot:actions>
        </x-nav>
     
        {{-- MAIN --}}
        <x-main full-width>
            {{-- SIDEBAR --}}
            <x-layouts.admin.sidebar />
     
            {{-- The `$slot` goes here --}}
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>
        </x-main>
     
        {{-- Toast --}}
        <x-toast />
        {{-- Dialog --}}
        <x-mary.dialog />
        
        @if(auth()->check() && auth()->user()->isAdmin())
            <livewire:admin.artisan-panel />
        @endif
    </body>
</html>
