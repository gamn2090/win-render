<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wedding Insiders Network: Coming Soon...</title>
    <meta name="description" content="Start booking your wedding vendors with an exclusive list — we’ve cut out paid placements to give you top-shelf wedding vendors. Create your free profile!" />

    <!-- Styles -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
    <style>
        body {
            min-width: fit-content;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden bg-win-light w-screen h-screen text-center">
    <div class="mt-[40vh]">
        <img src="/assets/img/WIN-Primary-Logo-BLUE.png" class="mx-auto w-48 py-4">
        <h1 class="headline-large">Coming soon...</h1>
    </div>
</body>
</html>