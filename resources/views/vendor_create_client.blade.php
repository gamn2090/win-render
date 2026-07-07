<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Refer a Client</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-current-clients.css', 'resources/js/app.js', 'resources/js/vendor-refer-client.js'])
  @include('components.fonts')
</head>
<body class="cc-page m-0 antialiased overflow-x-hidden">
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
    <div class="cc-hero__inner cc-hero__inner--solo">
      <div class="cc-hero__text">
        <h1 class="cc-hero__title">Refer a Client</h1>
        <p class="cc-hero__subtitle">
          Generate a new client invitation — invite a couple to join WIN and grow your client community.
        </p>
      </div>
    </div>
  </header>

  <div class="cc-content">
    <x-cc-refer-client-form />
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
