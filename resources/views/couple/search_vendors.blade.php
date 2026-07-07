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

    <div class="vd-topbar">
      <a href="{{ route('search.vendors') }}" class="vd-topbar__btn" aria-label="Search vendors">🔍</a>
      <a href="{{ route('client.inbox') }}" class="vd-topbar__btn" aria-label="Notifications">🔔</a>
      <a href="{{ route('user.account.settings') }}" class="vd-topbar__btn" aria-label="Settings">⚙️</a>
    </div>

    <section class="vd-hero">
      <div class="vd-hero__inner">
        <h1 class="vd-hero__title">Find Vendors</h1>
        <p class="vd-hero__sub">Choose one or more categories to find matching vendors</p>
      </div>
    </section>

    <div class="vd-results-header">
      <div>
        <h2 class="vd-results-header__title">Top Ranked {{ $selected_type->type ?? 'Vendors' }}</h2>
        <p class="vd-results-header__meta">Sorted by rating &middot; Showing <span class="vd-results-header__count">{{ $vendors->total() }} results</span></p>
      </div>

      <form method="GET" action="{{ route('search.vendors') }}" class="vd-filter-bar">
        <div class="hs-dropdown [--auto-close:inside] relative inline-flex">
          <button type="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white hover:bg-gray-50" aria-haspopup="menu" aria-expanded="false">
            Vendor Type
            <svg class="hs-dropdown-open:rotate-180 size-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
          <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden bg-white shadow-md rounded-lg mt-2 z-[100] max-h-72 overflow-y-auto" role="menu">
            <div class="p-1 space-y-0.5">
              @foreach($vendor_types as $type)
                <div class="flex items-center gap-x-2 py-2 px-3 rounded-lg hover:bg-gray-100 hover:cursor-pointer">
                  <input id="filter-type-{{ $type->id }}" name="type[]" type="checkbox" value="{{ $type->id }}" @checked(in_array($type->id, $selected_type_ids ?? [])) class="shrink-0 rounded-sm text-win-purple focus:ring-win-purple checked:border-win-purple disabled:opacity-50 disabled:pointer-events-none">
                  <label for="filter-type-{{ $type->id }}" class="grow cursor-pointer">
                    <span class="block text-sm">{{ $type->type }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>
        </div>

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

        <a href="{{ route('search.vendors', ['type' => $selected_type_ids ?? []]) }}" class="vd-filter-bar__clear">Clear Filters</a>
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
            data-favorited="{{ $favorited ? '1' : '0' }}"
            aria-label="Toggle favorite"
          >{{ $favorited ? '♥' : '♡' }}</button>

          <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-vendor-card__image-link" tabindex="-1" aria-hidden="true">
            <img class="vd-vendor-card__image" src="{{ \App\Support\ProfileImageStorage::url($vendor->image) }}" alt="" />
          </a>

          <div class="vd-vendor-card__body">
            <h3 class="vd-vendor-card__name">{{ $vendor->business_name }}</h3>

            @if(($vendor->discount ?? 0) > 0 && $user->isInNetwork())
              <span class="vd-vendor-card__discount">${{ $vendor->discount }} discount</span>
            @endif

            <div class="vd-vendor-card__meta">
              @if($vendorType)
                <span class="vd-vendor-card__type">
                  <img src="{{ asset($vendorType->icon) }}" alt="" class="vd-vendor-card__type-icon" width="18" height="18" />
                  <span class="vd-vendor-card__type-label">{{ $vendorType->type }}</span>
                </span>
              @endif
              <span class="vd-vendor-card__rating">
                <span class="vd-vendor-card__rating-num">★ {{ $vendor->googleRating() > 0 ? number_format($vendor->googleRating(), 1) : 'None' }}</span>
              </span>
            </div>

            <p class="vd-vendor-card__location">{{ $vendor->location }}{{ $vendor->service_radius ? ' · ' . $vendor->service_radius . ' mi' : '' }}</p>

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
    }).finally(function () {
      btn.disabled = false;
    });
  });
</script>
</body>
</html>
