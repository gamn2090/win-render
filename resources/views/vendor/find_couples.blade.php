<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Find Couples</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-find-couples.css', 'resources/css/vendor-dashboard.css', 'resources/js/app.js', 'resources/js/find-couples.js'])
  @include('components.fonts')
</head>
<body class="fc-page m-0 antialiased overflow-x-hidden">
@php
  $vendor = Auth::guard('vendor')->user();
  $fcAssets = asset('assets/img/vendor-home/find_couple');
  $showNoDate = request()->query('wedding_date', 'true') !== 'false';
@endphp

@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  @include('layouts.dashboard_topbar', ['role' => 'vendor'])

  <header class="fc-hero">
    <img
      class="fc-hero__bg"
      src="{{ $fcAssets }}/find_couples.jpg"
      alt=""
      width="1200"
      height="400"
    />
    <div class="fc-hero__overlay" aria-hidden="true"></div>
    <div class="fc-hero__content">
      <h1 class="fc-hero__title">Find Couples</h1>
      <div class="fc-credits">
        <span class="fc-credits__num">{{ $vendor->contact_credits ?? 0 }}</span>
        <span class="fc-credits__label">CONTACT CREDITS</span>
      </div>
    </div>
  </header>

  <section class="fc-filters" aria-label="Filters">
    <p class="fc-filters__tagline">Find your next dream client!</p>

    <div class="fc-filters__cluster">
      <div class="fc-filters__toggle-row">
        <span class="fc-filters__toggle-label">Show clients with no wedding date</span>
        <div class="fc-yesno">
          <label for="search-wedding-date" class="fc-yesno__label">No</label>
          <input
            type="checkbox"
            id="search-wedding-date"
            class="fc-yesno__input"
            @checked($showNoDate)
          />
          <label for="search-wedding-date" class="fc-yesno__label">Yes</label>
        </div>
      </div>

      <div class="fc-filters__actions">
        <button type="button" id="fc-clear-filters" class="fc-btn fc-btn--clear">
          <img src="{{ $fcAssets }}/x.png" alt="" width="16" height="16" />
          Clear Filters
        </button>
        <button type="button" id="filter-btn" class="fc-btn fc-btn--apply">
          <img src="{{ $fcAssets }}/ok.png" alt="" width="16" height="16" />
          Apply Filters
        </button>
        <button
          type="button"
          class="fc-btn fc-btn--event"
          aria-haspopup="dialog"
          aria-expanded="false"
          aria-controls="join-event-modal"
          data-hs-overlay="#join-event-modal"
        >
          <img src="{{ $fcAssets }}/heart.png" alt="" width="16" height="16" />
          Join Event
        </button>
      </div>
    </div>
  </section>

  <x-fc-join-event-modal />
  <x-fc-contact-confirm-modal />

  <section class="fc-grid" aria-label="Couple listings">
    @forelse($data['clients'] as $inquiry)
      @php
        $booked = $inquiry->bookedVendorsCount();
        $total = max(1, $inquiry->getRequestedVendorCount());
        $progressPct = min(100, ($booked / $total) * 100);
        $weddingDateDisplay = $inquiry->wedding_date
          ? \Carbon\Carbon::parse($inquiry->wedding_date)->format('m-d-Y')
          : 'No date set';
      @endphp

      <article class="fc-card">
        <div class="fc-card__media">
          <x-avatar :model="$inquiry" class="fc-card__media-avatar" />
        </div>
        <div class="fc-card__body">
          <p class="fc-card__progress-label">{{ $booked }} of {{ $total }} Booked Vendors</p>
          <div class="fc-card__progress-track" role="progressbar" aria-valuenow="{{ $booked }}" aria-valuemin="0" aria-valuemax="{{ $total }}">
            <div class="fc-card__progress-fill" style="width: {{ $progressPct }}%;"></div>
          </div>
          <h2 class="fc-card__names">
            <span>{{ $inquiry->first_name }}</span>
            <img src="{{ $fcAssets }}/mdi_heart.png" alt="" class="fc-card__heart" width="18" height="18" />
            <span>{{ $inquiry->fiance_first_name }}</span>
          </h2>
          <p class="fc-card__date">{{ $weddingDateDisplay }}</p>
          <button
            type="button"
            class="fc-card__btn fc-card__btn--view"
            data-hs-overlay="#fc-view-modal-{{ $inquiry->id }}"
          >
            View
          </button>
          <button
            type="button"
            class="fc-card__btn fc-card__btn--message inquireClientButton"
            data-client-uuid="{{ $inquiry->uuid }}"
          >
            Message Couple
          </button>
        </div>
      </article>
    @empty
      <p class="fc-empty">There are no couples to find right now. Please check back later!</p>
    @endforelse
  </section>

  @foreach($data['clients'] as $inquiry)
    <x-fc-couple-view-modal
      :couple="$inquiry"
      :vendor-types="$vendor_types"
      :fc-assets="$fcAssets"
    />
  @endforeach

  @if($data['clients']->hasPages())
    <nav class="fc-pagination" aria-label="Pagination">
      @if(!$data['clients']->onFirstPage())
        <a href="{{ $data['clients']->previousPageUrl() }}" class="fc-pagination__link">Previous</a>
      @endif
      @if($data['clients']->hasMorePages())
        <a href="{{ $data['clients']->nextPageUrl() }}" class="fc-pagination__link">Next</a>
      @endif
    </nav>
  @endif

  <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
