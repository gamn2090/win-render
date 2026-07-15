<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: My Vendors</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();
@endphp

@include('layouts.couple_sidebar', ['page' => 'vendor_list'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple'])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">My Vendors</h1>
      <p class="vd-page-header__sub">The ones who&rsquo;ll make your day magical</p>
    </header>

    <div class="vd-results-header">
      <div>
        <h2 class="vd-results-header__title">Your Wedding Team:</h2>
        <p class="vd-results-header__meta">Showing <span class="vd-results-header__count">{{ $bookedVendors->count() }}</span> Booked vendors</p>
      </div>
    </div>

    <div class="vd-myvendors-grid">
      @forelse($bookedVendors as $pairing)
        <x-couple-vendor-card :vendor="$pairing->vendor" :show-status="false" :show-badges="true" :show-consultation="false" />
      @empty
        <p style="grid-column:1/-1;text-align:center;color:#7a7a7a;">
          You haven't booked any vendors yet. Browse <a href="{{ route('search.vendors') }}" style="color:#6432c8;font-weight:600;">Find Vendors</a> to get started.
        </p>
      @endforelse
    </div>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

<script>
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.favorite-toggle-btn');
    if (!btn) return;
    var active = btn.dataset.favorited === '1';
    var next = !active;
    btn.disabled = true;
    btn.dataset.favorited = next ? '1' : '0';
    btn.textContent = next ? '♥' : '♡';
    fetch('{{ route('toggle.favorite') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({ vendor_uuid: btn.dataset.vendorId, active: next }),
    }).then(function (r) { return r.json(); }).then(function (data) {
      var name = btn.dataset.vendorName || 'Vendor';
      if (data.status) {
        WinToast.show('Vendor ' + name + (next ? ' added to your favorites.' : ' removed from your favorites.'), 'success');
      } else {
        WinToast.show(data.message || 'Something went wrong, please try again.', 'error');
      }
    }).finally(function () {
      btn.disabled = false;
    });
  });
</script>
</body>
</html>
