<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Find Vendors</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $vendor = Auth::guard('vendor')->user();
  $connectedVendorIds = $connectedVendorIds ?? [];
@endphp

@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'vendor'])

    <section class="vd-hero">
      <div class="vd-hero__inner">
        <h1 class="vd-hero__title">Find Vendors</h1>
        <p class="vd-hero__sub">Choose a category to find matching vendors</p>
        <form method="GET" action="{{ route('vendor.search.vendors') }}" class="vd-hero__form">
          <select name="type" class="vd-hero__select">
            <option value="" @selected(!$selected_type)>All Types</option>
            @foreach($vendor_types as $type)
              <option value="{{ $type->id }}" @selected($selected_type && $selected_type->id == $type->id)>{{ $type->type }}</option>
            @endforeach
          </select>
          <button type="submit" class="vd-hero__btn">Search</button>
        </form>
      </div>
    </section>

    <div class="vd-results-header">
      <div>
        <h2 class="vd-results-header__title">Top Ranked {{ $selected_type->type ?? 'Vendors' }}</h2>
        <p class="vd-results-header__meta">Sorted by rating &middot; Showing <span class="vd-results-header__count">{{ $vendors->total() }} results</span></p>
      </div>

      <form method="GET" action="{{ route('vendor.search.vendors') }}" class="vd-filter-bar">
        <input type="hidden" name="type" value="{{ $selected_type->id ?? '' }}" />
        @foreach($allowedFilters as $filter)
          @if($filter->search_type == 'checkbox')
            <x-filter-checkbox :filter="$filter" />
          @elseif($filter->search_type == 'select')
            <x-filter-select :filter="$filter" :selected="null" />
          @endif
        @endforeach

        <a href="{{ route('vendor.search.vendors', ['type' => $selected_type->id ?? null]) }}" class="vd-filter-bar__clear">Clear Filters</a>
        <button type="submit" class="vd-filter-bar__apply">Apply Filters</button>
      </form>
    </div>

    <div class="vd-connect-grid">
      @forelse($vendors as $card)
        @php
          $cardType = $card->getType();
          $isConnected = in_array($card->id, $connectedVendorIds);
        @endphp
        <article class="vd-vendor-card">
          <div class="vd-vendor-card__media">
            <button
              type="button"
              class="vd-vendor-card__preferred preferred-toggle-btn"
              data-vendor-id="{{ $card->id }}"
              data-connected="{{ $isConnected ? '1' : '0' }}"
              aria-label="Toggle preferred vendor"
              title="Add to my preferred vendors"
            >
              <img src="{{ asset('ico/Group.svg') }}" alt="" class="vd-vendor-card__preferred-icon" />
              <span class="vd-vendor-card__preferred-check" aria-hidden="true">✓</span>
            </button>

            <a href="{{ route('profile.vendor', $card->uuid) }}" class="vd-vendor-card__image-link" tabindex="-1" aria-hidden="true">
              <img class="vd-vendor-card__image" src="{{ \App\Support\ProfileImageStorage::url($card->image) }}" alt="" />
            </a>
          </div>

          <div class="vd-vendor-card__body">
            <h3 class="vd-vendor-card__name">{{ $card->business_name }}</h3>

            @if(($card->discount ?? 0) > 0)
              <span class="vd-vendor-card__discount">${{ $card->discount }} discount</span>
            @endif

            <div class="vd-vendor-card__meta">
              @if($cardType)
                <span class="vd-vendor-card__type">
                  <img src="{{ asset($cardType->icon) }}" alt="" class="vd-vendor-card__type-icon" width="18" height="18" />
                  <span class="vd-vendor-card__type-label">{{ $cardType->type }}</span>
                </span>
              @endif
              <span class="vd-vendor-card__rating">
                <span class="vd-vendor-card__rating-num">★ {{ $card->googleRating() > 0 ? number_format($card->googleRating(), 1) : 'None' }}</span>
              </span>
            </div>

            <p class="vd-vendor-card__location">{{ $card->location ? $card->location . ($card->service_radius ? ' · ' . $card->service_radius . ' mi' : '') : '-' }}</p>

            @if($card->badges()->count() > 0)
              <div class="vd-vendor-card__badges">
                @foreach($card->badges() as $badge)
                  <span class="vd-vendor-card__badge">
                    <img src="{{ asset('images/' . $badge->icon) }}" alt="" />
                    <span class="vd-vendor-card__badge-tip">{{ $badge->name }}</span>
                  </span>
                @endforeach
              </div>
            @endif

            <div class="vd-vendor-card__actions">
              <a href="{{ route('vendor.message', $card->id) }}" class="vd-vendor-card__btn vd-vendor-card__btn--message">Message</a>
              <a href="{{ route('profile.vendor', $card->uuid) }}" class="vd-vendor-card__btn vd-vendor-card__btn--storefront">Storefront</a>
            </div>
          </div>
        </article>
      @empty
        <p style="grid-column:1/-1;text-align:center;color:#7a7a7a;">We couldn't find any vendors of the specified type.</p>
      @endforelse
    </div>

    @if($vendors->hasPages())
      <div class="vd-pagination">
        <a href="{{ $vendors->previousPageUrl() ?? '#' }}" class="vd-pagination__link {{ $vendors->onFirstPage() ? 'vd-pagination__link--disabled' : '' }}">&larr; Previous</a>
        <a href="{{ $vendors->nextPageUrl() ?? '#' }}" class="vd-pagination__link {{ $vendors->hasMorePages() ? '' : 'vd-pagination__link--disabled' }}">Next &rarr;</a>
      </div>
    @endif

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

<script>
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.preferred-toggle-btn');
    if (!btn) return;
    var connected = btn.dataset.connected === '1';
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    btn.disabled = true;

    if (connected) {
      fetch('{{ route('vendor.connection.remove') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ aff_vendor: btn.dataset.vendorId }),
      }).then(function () {
        btn.dataset.connected = '0';
      }).finally(function () {
        btn.disabled = false;
      });
      return;
    }

    fetch('{{ route('create.connect.request') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
      },
      body: JSON.stringify({ aff_id: btn.dataset.vendorId }),
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.status) {
          btn.dataset.connected = '1';
        }
      })
      .finally(function () {
        btn.disabled = false;
      });
  });
</script>
</body>
</html>
