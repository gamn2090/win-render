<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Wedding Insiders Network</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @include('components.fonts')
    </head>
    <body class="font-sans antialiased">
        @if(Auth::guard('vendor')->check())
        @include('layouts.vendor_navigation')
        @elseif(Auth::guard('web')->check())
        @include('layouts.navigation')
        @else
        @include('layouts.guest_navigation')
        @endif
        <div class="min-h-[75vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full mt-6 px-6 py-4 bg-white overflow-hidden">
                {{ $slot }}
            </div>
        </div>
        {{-- Site footer disabled per client request — uncomment to restore --}}
        {{-- @include('layouts.footer') --}}
        <p class="text-center text-xs text-gray-400 pb-4">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
    </body>
</html>