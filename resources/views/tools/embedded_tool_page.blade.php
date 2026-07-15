<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: {{ $title }}</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@if($role === 'vendor')
  @include('layouts.vendor_navigation', ['page' => $page])
@else
  @include('layouts.couple_sidebar', ['page' => $page])
@endif

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => $role])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">{{ $title }}</h1>
    </header>

    <div class="vd-embedded-tool">
      <iframe src="{{ $iframeSrc }}" class="vd-embedded-tool__frame" title="{{ $title }}"></iframe>
    </div>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>
</main>
</body>
</html>
