<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Wedding Insiders Network</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css', 'resources/js/app.js'])
        @include('components.fonts')
    </head>
    <body class="vd-page m-0 antialiased overflow-x-hidden">
        @if(Auth::guard('vendor')->check())
            @include('layouts.vendor_navigation')
            <main class="relative transition-all duration-200 ease-in-out">
                @include('layouts.dashboard_topbar', ['role' => 'vendor'])
                <div class="min-h-[60vh] flex flex-col justify-center items-center">
                    {{ $slot }}
                </div>
                <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
            </main>
        @elseif(Auth::guard('web')->check())
            @include('layouts.couple_sidebar')
            <main class="relative transition-all duration-200 ease-in-out">
                @include('layouts.dashboard_topbar', ['role' => 'couple'])
                <div class="min-h-[60vh] flex flex-col justify-center items-center">
                    {{ $slot }}
                </div>
                <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
            </main>
        @else
            @include('layouts.guest_navigation')
            <div class="min-h-[75vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <div class="w-full mt-6 px-6 py-4 bg-white overflow-hidden">
                    {{ $slot }}
                </div>
            </div>
            <p class="text-center text-xs text-gray-400 pb-4">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
        @endif
    </body>
</html>
