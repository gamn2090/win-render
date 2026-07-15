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
  $user = Auth::guard('web')->user();
@endphp

@include('layouts.couple_sidebar', ['page' => 'find_vendors'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple'])

    <section class="vd-hero">
      <div class="vd-hero__inner">
        <h1 class="vd-hero__title">Find Vendors</h1>
        <p class="vd-hero__sub">Choose a category to find matching vendors</p>
        <form method="GET" action="{{ route('search.vendors') }}" class="vd-hero__form">
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

      <form method="GET" action="{{ route('search.vendors') }}" class="vd-filter-bar">
        <input type="hidden" name="type" value="{{ $selected_type->id ?? '' }}" />
        @foreach($allowedFilters as $filter)
          @if($filter->search_type == 'checkbox')
            <x-filter-checkbox :filter="$filter" />
          @elseif($filter->search_type == 'select')
            <x-filter-select :filter="$filter" :selected="null" />
          @endif
        @endforeach
        @if($user->event != null)
          <x-filter-select :filter="new \App\Models\TagType(['name' => 'Event', 'search_type' => 'select', 'allowed_values' => '[' . $user->event . ']'])" />
        @endif

        <a href="{{ route('search.vendors', ['type' => $selected_type->id ?? null]) }}" class="vd-filter-bar__clear">Clear Filters</a>
        <button type="submit" class="vd-filter-bar__apply">Apply Filters</button>
      </form>
    </div>

    <div class="vd-search-grid">
      @forelse($vendors as $vendor)
        @php
          $vendorType = $vendor->getType();
          $favorited = $user->hasFavorite($vendor->id);
        @endphp
        <article class="vd-vendor-card">
          <button
            type="button"
            class="vd-vendor-card__favorite favorite-toggle-btn"
            data-vendor-id="{{ $vendor->uuid }}"
            data-vendor-name="{{ $vendor->business_name }}"
            data-favorited="{{ $favorited ? '1' : '0' }}"
            aria-label="Toggle favorite"
          >{{ $favorited ? '♥' : '♡' }}</button>

          <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-vendor-card__image-link" tabindex="-1" aria-hidden="true">
            @if($vendor->coverImageUrl())
              <img class="vd-vendor-card__image" src="{{ $vendor->coverImageUrl() }}" alt="" />
            @else
              <div class="vd-vendor-card__image win-cover-placeholder"></div>
            @endif
          </a>

          <div class="vd-vendor-card__body">
            <h3 class="vd-vendor-card__name">{{ $vendor->business_name }}</h3>

            @if(($vendor->discount ?? 0) > 0 && $user->isInNetwork())
              <span class="vd-vendor-card__discount">${{ $vendor->discount }} discount</span>
            @endif

            <div class="vd-vendor-card__meta">
              @if($vendorType)
                <span class="vd-vendor-card__type">
                  <span class="vd-vendor-card__type-icon vd-vendor-card__type-icon--solid" style="--icon-url: url('{{ asset($vendorType->icon) }}');" role="img" aria-label=""></span>
                  <span class="vd-vendor-card__type-label">{{ $vendorType->type }}</span>
                </span>
              @endif
              <span class="vd-vendor-card__rating">
                <span class="vd-vendor-card__rating-num">★ {{ $vendor->googleRating() > 0 ? number_format($vendor->googleRating(), 1) : 'None' }}</span>
              </span>
            </div>

            <p class="vd-vendor-card__location">{{ $vendor->location ? $vendor->location . ($vendor->service_radius ? ' · ' . $vendor->service_radius . ' mi' : '') : '-' }}</p>

            @if($vendor->badges()->count() > 0)
              <div class="vd-vendor-card__badges">
                @foreach($vendor->badges() as $badge)
                  <span class="vd-vendor-card__badge">
                    <img src="{{ asset('images/' . $badge->icon) }}" alt="" />
                    <span class="vd-vendor-card__badge-tip">{{ $badge->name }}</span>
                  </span>
                @endforeach
              </div>
            @endif

            <div class="vd-vendor-card__actions">
              <a href="{{ route('chat.with.vendor', $vendor->uuid) }}" class="vd-vendor-card__btn vd-vendor-card__btn--message">Message</a>
              <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-vendor-card__btn vd-vendor-card__btn--storefront">Storefront</a>
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
