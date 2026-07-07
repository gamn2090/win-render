<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Vendor Network</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-current-clients.css', 'resources/css/vendor-vendor-network.css', 'resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="cc-page vn-page m-0 antialiased overflow-x-hidden">
@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  <div class="fc-toolbar" aria-label="Page tools">
    <div class="fc-toolbar__icons">
      <button type="button" class="fc-toolbar__icon" aria-label="Search">🔍</button>
      <button type="button" class="fc-toolbar__icon" aria-label="Notifications">🔔</button>
      <a href="{{ url('/vendor/profile') }}" class="fc-toolbar__icon" aria-label="Settings">⚙️</a>
    </div>
  </div>

  <header class="cc-hero">
    <div class="cc-hero__inner">
      <div class="cc-hero__text">
        <h1 class="cc-hero__title vn-hero__title">Vendors</h1>
        <p class="cc-hero__subtitle">Your connected vendors</p>
      </div>
      <a href="{{ route('vendor.create.vendors') }}" class="cc-hero__cta">
        <span class="cc-hero__cta-icon" aria-hidden="true">+</span>
        Add Vendor
      </a>
    </div>
  </header>

  <div class="cc-content">
    @if(session('vendor_network_removed'))
      <div class="cc-flash cc-flash--success" role="status">
        Connection removed successfully.
      </div>
    @endif

    <div class="cc-stack">
      <x-vendor-vendors-table
        :rows="$vendors"
        empty-message="No connected vendors yet. Use Add Vendor to invite your first peer."
      />
    </div>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
