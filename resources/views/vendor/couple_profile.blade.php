<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Couple Profile</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-messages.css', 'resources/css/vendor-couple-profile.css', 'resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vcp-page m-0 antialiased overflow-x-hidden">
@php
  $fcAssets = asset('assets/img/vendor-home/find_couple');

  $weddingDateDisplay = '—';
  if ($client->wedding_date) {
      try {
          $weddingDateDisplay = \Carbon\Carbon::parse($client->wedding_date)->format('m-d-Y');
      } catch (\Exception $e) {
          $weddingDateDisplay = $client->wedding_date;
      }
  }

  $venueName = 'Name Of Venue';
  $venueLocation = 'City, State';
  $bioText = $client->bio ?? '';

  if (preg_match('/Wedding venue:\s*([^,\n]+)(?:,\s*([^\n]+))?/i', $bioText, $venueMatch)) {
      $venueName = trim($venueMatch[1]);
      if (! empty($venueMatch[2])) {
          $venueLocation = trim($venueMatch[2]);
      } elseif ($client->wedding_location) {
          $venueLocation = $client->wedding_location;
      }
  } elseif ($client->wedding_location) {
      $venueName = $client->wedding_location;
      $venueLocation = $client->wedding_location;
  }

  $bookingWindow = $client->booking_date ?: '0-3 Months';

  $answers = json_decode($client->questions ?? '[]');
  if (! is_array($answers)) {
      $answers = [null, null, null, null];
  }
  $answers = array_pad($answers, 4, null);

  $iconUrl = static function ($vendorType) {
      $icon = $vendorType->icon ?? '';
      if ($icon === '') {
          return '';
      }
      if (str_starts_with($icon, 'http://') || str_starts_with($icon, 'https://')) {
          return $icon;
      }

      return asset(ltrim($icon, '/'));
  };
@endphp

@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  <div class="fc-toolbar" aria-label="Page tools">
    <div class="fc-toolbar__icons">
      <button type="button" class="fc-toolbar__icon" aria-label="Search">🔍</button>
      <button type="button" class="fc-toolbar__icon" aria-label="Notifications">🔔</button>
      <a href="{{ url('/vendor/profile') }}" class="fc-toolbar__icon" aria-label="Settings">⚙️</a>
    </div>
  </div>

  <header class="vm-hero">
    <div class="vm-hero__content">
      <h1 class="vm-hero__title">Couple Profile</h1>
    </div>
  </header>

  <div class="vm-content vcp-content">
    <section class="vcp-banner" aria-label="Couple summary">
      <div class="vcp-banner__inner">
        <img
          class="vcp-banner__avatar"
          src="{{ asset('/storage/images/'.$client->image) }}"
          alt="{{ $client->first_name }} and {{ $client->fiance_first_name }}"
          width="120"
          height="120"
        />
        <div class="vcp-banner__main">
          <h2 class="vcp-banner__names">
            <span>{{ $client->first_name }}</span>
            <img src="{{ $fcAssets }}/mdi_heart.png" alt="" class="vcp-banner__heart" width="20" height="20" />
            <span>{{ $client->fiance_first_name }}</span>
          </h2>
          <div class="vcp-banner__grid">
            <p class="vcp-banner__item">
              <span class="vcp-banner__label">Wedding Date:</span>
              <span class="vcp-banner__value"> {{ $weddingDateDisplay }}</span>
            </p>
            <p class="vcp-banner__item">
              <span class="vcp-banner__label">Wedding Venue Name:</span>
              <span class="vcp-banner__value"> {{ $venueName }}</span>
            </p>
            <p class="vcp-banner__item">
              <span class="vcp-banner__label">We Are Looking To Book Our Vendors Within:</span>
              <span class="vcp-banner__value"> {{ $bookingWindow }}</span>
            </p>
            <p class="vcp-banner__item">
              <span class="vcp-banner__label">Wedding Venue Location:</span>
              <span class="vcp-banner__value"> {{ $venueLocation }}</span>
            </p>
          </div>
        </div>
      </div>
    </section>

    <div class="vcp-cards">
      <article class="vcp-card">
        <h3 class="vcp-card__title">
          A Little Bit About <strong>{{ $client->first_name }} &amp; {{ $client->fiance_first_name }}</strong>
        </h3>
        <p class="vcp-card__body">
          @if($client->bio && ! preg_match('/^Wedding venue:/im', trim($client->bio)))
            {{ $client->bio }}
          @elseif($client->bio)
            @php
              $bioWithoutVenue = preg_replace('/Wedding venue:.*$/im', '', $client->bio);
              $bioWithoutVenue = trim($bioWithoutVenue);
            @endphp
            @if($bioWithoutVenue !== '')
              {{ $bioWithoutVenue }}
            @else
              {{ $client->first_name }} &amp; {{ $client->fiance_first_name }} haven't written a bio yet!
            @endif
          @else
            {{ $client->first_name }} &amp; {{ $client->fiance_first_name }} haven't written a bio yet!
          @endif
        </p>
      </article>

      <article class="vcp-card">
        <h3 class="vcp-card__title">Describe Your Dream Wedding In Three Words.</h3>
        @if($answers[0] != null)
          <p class="vcp-card__body">{{ $answers[0] }}</p>
        @else
          <p class="vcp-card__body vcp-card__body--placeholder">One - Two - Three</p>
        @endif
      </article>

      <article class="vcp-card">
        <h3 class="vcp-card__title">What Are You Most Looking Forward To About Your Wedding?</h3>
        <p class="vcp-card__body">
          @if($answers[1] != null)
            {{ $answers[1] }}
          @else
            <span class="vcp-card__body--placeholder">—</span>
          @endif
        </p>
      </article>

      <article class="vcp-card">
        <h3 class="vcp-card__title">Are There Any Specific Traditions That Are Important For You To Include, Or Avoid?</h3>
        <p class="vcp-card__body">
          @if($answers[2] != null)
            {{ $answers[2] }}
          @else
            <span class="vcp-card__body--placeholder">—</span>
          @endif
        </p>
      </article>

      <article class="vcp-card">
        <h3 class="vcp-card__title">Is There Anything Else You'd Like Your Wedding Vendors To Know Before Working Together?</h3>
        <p class="vcp-card__body">
          @if($answers[3] != null)
            {{ $answers[3] }}
          @else
            <span class="vcp-card__body--placeholder">—</span>
          @endif
        </p>
      </article>
    </div>

    <section class="vcp-dark" aria-label="Vendor preferences and bookings">
      <div class="vcp-dark__section">
        <h3 class="vcp-dark__title">We Are Looking To Connect With:</h3>
        <div class="vcp-pills">
          @php $hasSearchingFor = false; @endphp
          @foreach($vendor_types as $vendorType)
            @if($searching_for->contains($vendorType->id))
              @php $hasSearchingFor = true; @endphp
              <span class="vcp-pill">
                @if($iconUrl($vendorType))
                  <img src="{{ $iconUrl($vendorType) }}" alt="" class="vcp-pill__icon" width="18" height="18" />
                @endif
                <span>{{ $vendorType->type }}</span>
              </span>
            @endif
          @endforeach
          @if(! $hasSearchingFor)
            <p class="vcp-empty">No vendor categories selected yet.</p>
          @endif
        </div>
      </div>

      <div class="vcp-dark__section">
        <h3 class="vcp-dark__title">Booked Vendors:</h3>
        @if($booked_vendors->count() > 0)
          <div class="vcp-booked__grid">
            @foreach($booked_vendors as $vendor)
              @php
                $vendorType = $vendor->getType();
                $rating = (float) $vendor->googleRating();
                $ratingDisplay = $rating > 0 ? number_format($rating, 1) : '—';
                $locationLine = trim($vendor->location ?? '');
                if ($vendor->service_radius) {
                    $locationLine .= ($locationLine ? ' · ' : '') . $vendor->service_radius . ' mi';
                }
              @endphp
              <article class="vcp-booked__card">
                <div class="vcp-booked__image-wrap">
                  <span class="vcp-booked__heart" aria-hidden="true">♥</span>
                  <img
                    class="vcp-booked__image"
                    src="{{ asset('/storage/images/'.$vendor->image) }}"
                    alt=""
                  />
                </div>
                <div class="vcp-booked__body">
                  <h4 class="vcp-booked__name">{{ $vendor->business_name }}</h4>
                  <div class="vcp-booked__meta">
                    @if($vendorType)
                      <span class="vcp-booked__type">
                        <img src="{{ asset($vendorType->icon) }}" alt="" class="vcp-booked__type-icon" width="18" height="18" />
                        <span>{{ $vendorType->type }}</span>
                      </span>
                    @endif
                    <span class="vcp-booked__rating">★ {{ $ratingDisplay }}</span>
                  </div>
                  @if($locationLine)
                    <p class="vcp-booked__location">{{ $locationLine }}</p>
                  @endif
                  @if($vendor->badges()->count() > 0)
                    <div class="vcp-booked__badges">
                      @foreach($vendor->badges() as $badge)
                        <span class="vcp-booked__badge" title="{{ $badge->name }}">
                          <img src="/images/{{ $badge->icon }}" alt="" />
                        </span>
                      @endforeach
                    </div>
                  @endif
                  <div class="vcp-booked__actions">
                    <a href="{{ url('/vendor/message/'.$vendor->id) }}" class="vcp-booked__btn vcp-booked__btn--message">Message</a>
                    <a href="{{ url('/vendor/profile/'.$vendor->uuid) }}" class="vcp-booked__btn vcp-booked__btn--storefront">Storefront</a>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        @else
          <p class="vcp-empty">No booked vendors yet.</p>
        @endif
      </div>
    </section>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
