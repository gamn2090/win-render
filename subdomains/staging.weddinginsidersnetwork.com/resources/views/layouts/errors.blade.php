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
        <div class="min-h-[80vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        @include('layouts.footer')
    </body>
</html>