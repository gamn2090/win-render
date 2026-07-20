<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: My Timelines</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-current-clients.css', 'resources/css/vendor-dashboard.css', 'resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="cc-page m-0 antialiased overflow-x-hidden">
@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  @include('layouts.dashboard_topbar', ['role' => 'vendor'])

  <header class="cc-hero">
    <div class="cc-hero__inner">
      <div class="cc-hero__text">
        <h1 class="cc-hero__title">My Timelines</h1>
        <p class="cc-hero__subtitle">
          Build a reusable wedding-day timeline for any client — even couples who aren't on WIN — and export a clean PDF to share.
        </p>
      </div>
    </div>
  </header>

  <div class="cc-content">
    <div class="cc-stack">
      <form action="{{ route('vendor.timelines.store') }}" method="POST" class="cc-actions__inline-form">
        @csrf
        <input type="text" name="name" placeholder="e.g. Sarah &amp; Mike" class="cc-actions__inline-input" required maxlength="190" />
        <button type="submit" class="cc-hero__cta">
          <span class="cc-hero__cta-icon" aria-hidden="true">+</span>
          Create Timeline
        </button>
      </form>

      <x-vendor-timelines-table
        :rows="$timelines"
        empty-message="No timelines yet. Create one above to get started."
      />
    </div>
  </div>

  <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
</main>
</body>
</html>
