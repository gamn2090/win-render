<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Search{{ $query !== '' ? ' — ' . $query : '' }}</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();
@endphp

@include('layouts.couple_sidebar', ['page' => 'search_results'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple'])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">Search</h1>
      <p class="vd-page-header__sub">
        @if($query !== '')
          Results for &ldquo;{{ $query }}&rdquo;
        @else
          Type something in the search box above
        @endif
      </p>
    </header>

    <div class="vd-search-results" style="margin-top:24px;">
      @forelse($results as $result)
        <a href="{{ $result['url'] }}" class="vd-search-result">
          <span class="vd-search-result__source">{{ $result['source'] }}</span>
          <h3 class="vd-search-result__title">{{ $result['title'] }}</h3>
          @if($result['snippet'])
            <p class="vd-search-result__snippet">{{ $result['snippet'] }}</p>
          @endif
        </a>
      @empty
        @if($query !== '')
          <p style="color:#7a7a7a;">No results found for &ldquo;{{ $query }}&rdquo;.</p>
        @endif
      @endforelse
    </div>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
