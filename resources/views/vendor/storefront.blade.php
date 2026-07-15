<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: {{ $vendor->business_name }}</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  @vite([
    'resources/css/app.css',
    'resources/css/vendor-messages.css',
    'resources/css/vendor-storefront.css',
    'resources/css/vendor-dashboard.css',
    'resources/js/app.js',
    'resources/js/vendor-storefront.js',
  ])
  @include('components.fonts')
  <script>
    window.vendorID = {{ $vendor->id }};
    window.vendorName = @json($vendor->business_name ?: trim($vendor->first_name . ' ' . $vendor->last_name));
  </script>
</head>
<body class="vsf-page m-0 antialiased overflow-x-hidden">
@php
  $authVendor = Auth::guard('vendor')->user();
  $isOwnStorefront = $authVendor && $authVendor->id === $vendor->id;
  $isOtherVendor = $authVendor && $authVendor->id !== $vendor->id;
  $isCouple = Auth::guard('web')->check();

  $locationLine = trim($vendor->location ?? '');
  if ($vendor->service_radius) {
      $locationLine .= ($locationLine ? ' - ' : '') . $vendor->service_radius . ' mi';
  }

  $aboutTitle = trim($vendor->business_name ?? '') ?: trim($vendor->first_name . ' ' . $vendor->last_name);
  $endorsementCount = $vendor->endorsements()->distinct()->count('endorser');

  $rank = $rank ?? 1;
  $rankLabel = '#' . $rank . ' in ' . ($vendor_type->type ?? 'Vendor');
  if ($vendor->location) {
      $rankLabel .= ' - ' . trim(explode(',', $vendor->location)[0]);
  }

  $portfolioImages = $profile->portfolioImages();
  $coverImage = $portfolioImages[0] ?? null;
  $galleryThumbs = array_slice(
      array_values(array_filter($portfolioImages, static fn ($img) => $img !== $coverImage)),
      0,
      5
  );
  $googleRating = (float) $vendor->googleRating();
  $googleRatingDisplay = $googleRating > 0 ? number_format($googleRating, 1) : null;

  $includedRoles = [];
  $includedRoleIds = [];
  foreach ($connections as $connection) {
      $type = $connection->getType();
      if ($type && ! in_array($type->id, $includedRoleIds, true)) {
          $includedRoleIds[] = $type->id;
          $includedRoles[] = $type;
      }
  }

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

@if($authVendor)
  @include('layouts.vendor_navigation')
@else
  @include('layouts.navigation')
@endif

<main class="relative transition-all duration-200 ease-in-out">
  @if($authVendor)
    @include('layouts.dashboard_topbar', ['role' => 'vendor'])
  @endif

  <div class="@if($authVendor) vm-content @endif vsf-content">
    <section class="vsf-profile" aria-label="Vendor profile">
      <div class="vsf-profile__top">
        <x-avatar :model="$vendor" class="vsf-profile__avatar" />
        <div class="vsf-profile__info">
          <h2 class="vsf-profile__business">{{ $vendor->business_name }}</h2>
          <p class="vsf-profile__name">{{ $vendor->first_name }} {{ $vendor->last_name }}</p>
          @if($locationLine)
            <p class="vsf-profile__location">{{ $locationLine }}</p>
          @endif
          <p class="vsf-profile__rank">{{ $rankLabel }}</p>
          <div class="vsf-profile__meta">
            @if($vendor_type)
              <span class="vsf-profile__type-pill">
                <img src="{{ asset(ltrim($vendor_type->icon ?? '', '/')) }}" alt="" class="vsf-profile__type-icon" width="18" height="18" />
                <span>{{ $vendor_type->type }}</span>
              </span>
            @endif
            @if($vendor->discount > 0)
              <span class="vsf-profile__pricing-pill">${{ $vendor->discount }} Preferred Pricing</span>
            @endif
            @foreach($vendor->badges() as $badge)
              <span class="vsf-profile__badge" title="{{ $badge->name }}">
                <img src="/images/{{ $badge->icon }}" alt="" />
              </span>
            @endforeach
          </div>
        </div>
      </div>

      <div class="vsf-profile__actions">
        @if($isOwnStorefront)
          <a href="{{ url('/vendor/profile') }}" class="vsf-profile__cta vsf-profile__cta--message">Edit Profile</a>
        @elseif($isOtherVendor)
          @if($authVendor->isPendingWith($vendor->id))
            <button type="button" class="vsf-profile__cta vsf-profile__cta--connect" disabled>
              <span class="vsf-profile__cta-icon" aria-hidden="true">✓</span>
              Connected
            </button>
          @else
            <button type="button" id="connectBtn" class="vsf-profile__cta vsf-profile__cta--connect">
              <span class="vsf-profile__cta-icon" aria-hidden="true">♥</span>
              Invite to Connect
            </button>
          @endif
          <button
            type="button"
            id="messageVendorButton"
            class="vsf-profile__cta vsf-profile__cta--message"
            data-message-url="{{ url('/vendor/message/'.$vendor->id) }}"
            data-conversation-prefix="/inbox/conversation/"
          >
            <span class="vsf-profile__cta-icon" aria-hidden="true">💬</span>
            Message Vendor
          </button>
          <button
            type="button"
            id="endorse-button"
            class="vsf-profile__cta vsf-profile__cta--endorse"
            aria-haspopup="dialog"
            data-hs-overlay="#endorse-vendor-modal"
          >
            <span class="vsf-profile__cta-icon" aria-hidden="true">★</span>
            Endorse
          </button>
        @elseif($isCouple)
          @if(! Auth::user()->isAssociatedWith($vendor->id))
            <button type="button" id="modal-check-date-toggle" class="vsf-profile__cta vsf-profile__cta--connect modal-check-date-toggle">Request to Match</button>
          @else
            <button
              type="button"
              id="consultation-button"
              class="vsf-profile__cta vsf-profile__cta--message"
              data-hs-overlay="#request-consultation-modal"
            >
              Request Consultation
            </button>
          @endif
          <button
            type="button"
            id="messageVendorButton"
            class="vsf-profile__cta vsf-profile__cta--message"
            data-message-url="{{ url('/client/message/'.$vendor->id) }}"
            data-conversation-prefix="/client/conversation/"
          >
            <span class="vsf-profile__cta-icon" aria-hidden="true">💬</span>
            Message Vendor
          </button>
          <button
            type="button"
            id="heart"
            class="vsf-profile__cta vsf-profile__cta--favorite heart"
            data-vendor-id="{{ $vendor->uuid }}"
            aria-label="Favorite vendor"
          >
            <span class="vsf-profile__cta-icon" aria-hidden="true">♥</span>
            Favorite
          </button>
        @endif
      </div>
    </section>

    <nav class="vsf-nav" aria-label="Storefront sections">
      @if($coverImage)
        <a href="#vsf-photos" class="vsf-nav__link">Photos</a>
      @endif
      <a href="#vsf-about" class="vsf-nav__link">About</a>
      <a href="#vsf-preferred" class="vsf-nav__link">Preferred Vendors</a>
      <a href="#vsf-pricing" class="vsf-nav__link">Pricing</a>
      @if($googleRatingDisplay)
        <a href="#vsf-reviews" class="vsf-nav__link">Reviews</a>
      @endif
    </nav>

    <section class="vsf-card" aria-label="Social brands">
      <h3 class="vsf-card__title">Social Brands:</h3>
      <div class="vsf-social">
        @if($profile->instagram_link)
          <a href="{{ $profile->getLink('instagram') }}" target="_blank" rel="noopener" class="vsf-social__link" aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
          </a>
        @endif
        @if($profile->facebook_link)
          <a href="{{ $profile->getLink('facebook') }}" target="_blank" rel="noopener" class="vsf-social__link" aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
        @endif
        @if($profile->linkedin_link)
          <a href="{{ $profile->getLink('linkedin') }}" target="_blank" rel="noopener" class="vsf-social__link" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
        @endif
        @if($profile->business_link)
          <a href="//{{ $profile->business_link }}" target="_blank" rel="noopener" class="vsf-social__website">Visit Website</a>
        @endif
        @if($googleRatingDisplay)
          <span class="vsf-social__rating">
            Google Ratings <span class="vsf-social__rating-star">★ {{ $googleRatingDisplay }}</span>
          </span>
        @endif
      </div>
    </section>

    <section id="vsf-about" class="vsf-card">
      <h3 class="vsf-card__title">About {{ $aboutTitle }}:</h3>
      <div class="vsf-card__body">
        @if($profile->bio)
          {!! nl2br(e($profile->bio)) !!}
        @else
          {{ $vendor->first_name }} hasn't added a bio yet.
        @endif
      </div>
    </section>

    @if($coverImage)
      <section id="vsf-photos" class="vsf-card">
        <h3 class="vsf-card__title">See My Work:</h3>
        <p class="vsf-gallery__label">*Cover Photo</p>
        <div class="vsf-gallery">
          <div class="vsf-gallery__hero vsf-gallery__hero--cover">
            <img
              class="vsf-lightbox-trigger"
              src="{{ \App\Support\ProfileImageStorage::url($coverImage) }}"
              alt=""
              loading="lazy"
            />
          </div>
          @foreach($galleryThumbs as $index => $image)
            <div class="vsf-gallery__thumb vsf-gallery__thumb--{{ $index + 1 }}">
              <img
                class="vsf-lightbox-trigger"
                src="{{ \App\Support\ProfileImageStorage::url($image) }}"
                alt=""
                loading="lazy"
              />
              <button type="button" class="vsf-gallery__cover-btn set-cover-btn" value="{{ $image }}">Set as Cover</button>
            </div>
          @endforeach
        </div>
        <button type="button" id="viewAllPortfolioImages" class="vsf-gallery__view-all">View All</button>
      </section>
    @endif

    <section id="vsf-preferred" class="vsf-section">
      <h3 class="vsf-section__title vsf-section__title--blue">Preferred Vendors:</h3>
      @if(count($includedRoles) > 0)
        <div class="vsf-pills" role="tablist">
          @foreach($includedRoles as $index => $role)
            <button
              type="button"
              class="vsf-pill @if($index === 0) vsf-pill--active @endif"
              data-vsf-tab="vsf-tab-{{ $role->id }}"
              role="tab"
              aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
            >
              @if($iconUrl($role))
                <img src="{{ $iconUrl($role) }}" alt="" class="vsf-pill__icon" width="18" height="18" />
              @endif
              <span>{{ $role->type }}</span>
            </button>
          @endforeach
        </div>

        @foreach($includedRoles as $index => $role)
          <div
            id="vsf-tab-{{ $role->id }}"
            class="vsf-tab-panel @if($index === 0) is-active @endif"
            role="tabpanel"
          >
            <div class="vsf-vendors-grid">
              @foreach($vendor->connections()->where('aff_vendor_type', $role->id)->get() as $affVendor)
                @php
                  $affType = $affVendor->getType();
                  $affRating = (float) $affVendor->googleRating();
                  $affRatingDisplay = $affRating > 0 ? number_format($affRating, 1) : '—';
                  $affLocation = trim($affVendor->location ?? '');
                  if ($affVendor->service_radius) {
                      $affLocation .= ($affLocation ? ' - ' : '') . $affVendor->service_radius . ' mi';
                  }
                @endphp
                <article class="vsf-vendor-card">
                  <div class="vsf-vendor-card__image-wrap">
                    <span class="vsf-vendor-card__heart" aria-hidden="true">♥</span>
                    @if($affVendor->coverImageUrl())
                      <img class="vsf-vendor-card__image" src="{{ $affVendor->coverImageUrl() }}" alt="" />
                    @else
                      <div class="vsf-vendor-card__image win-cover-placeholder"></div>
                    @endif
                  </div>
                  <div class="vsf-vendor-card__body">
                    <h4 class="vsf-vendor-card__name">{{ $affVendor->business_name }}</h4>
                    <div class="vsf-vendor-card__meta">
                      @if($affType)
                        <span class="vsf-vendor-card__type">
                          @if($iconUrl($affType))
                            <img src="{{ $iconUrl($affType) }}" alt="" class="vsf-vendor-card__type-icon" />
                          @endif
                          <span>{{ $affType->type }}</span>
                        </span>
                      @endif
                      <span class="vsf-vendor-card__rating">★ {{ $affRatingDisplay }}</span>
                    </div>
                    @if($affLocation)
                      <p class="vsf-vendor-card__location">{{ $affLocation }}</p>
                    @endif
                    <div class="vsf-vendor-card__badges">
                      @foreach($affVendor->badges() as $badge)
                        <span class="vsf-vendor-card__badge" title="{{ $badge->name }}">
                          <img src="/images/{{ $badge->icon }}" alt="" />
                        </span>
                      @endforeach
                    </div>
                    <div class="vsf-vendor-card__actions">
                      @if($authVendor)
                        <a href="{{ url('/vendor/message/'.$affVendor->id) }}" class="vsf-vendor-card__btn vsf-vendor-card__btn--message">Message</a>
                      @elseif($isCouple)
                        <a href="{{ url('/client/message/'.$affVendor->id) }}" class="vsf-vendor-card__btn vsf-vendor-card__btn--message">Message</a>
                      @endif
                      <a href="{{ route('profile.vendor', ['id' => $affVendor->uuid]) }}" class="vsf-vendor-card__btn vsf-vendor-card__btn--storefront">Storefront</a>
                    </div>
                  </div>
                </article>
              @endforeach
            </div>
          </div>
        @endforeach
      @else
        <p class="vsf-empty">This vendor doesn't have any preferred vendors added to their storefront yet!</p>
      @endif
    </section>

    <section id="vsf-endorsements" class="vsf-section vsf-card">
      <div class="vsf-endorsements__head">
        <div class="vsf-endorsements__intro">
          <h3 class="vsf-card__title">Endorsements:</h3>
          <p class="vsf-endorsements__lead">
            {{ $endorsementCount }} vendors find {{ $vendor->business_name }} to be:
          </p>
        </div>
        @if($isOtherVendor)
          <button
            type="button"
            class="vsf-endorsements__cta"
            aria-haspopup="dialog"
            data-hs-overlay="#endorse-vendor-modal"
          >
            Endorse
          </button>
        @endif
      </div>
      @if($endorsementCount > 0)
        <div class="vsf-endorsements__grid">
          @foreach($endorsements as $endorsement)
            <div class="vsf-endorsement">
              <span class="vsf-endorsement__star" aria-hidden="true">★</span>
              <span class="vsf-endorsement__label">{{ $endorsement->type }}</span>
              <div class="vsf-endorsement__avatars">
                @forelse($vendor->endorsements()->where('type', $endorsement->typeNum)->select('endorser')->distinct()->take(4)->get() as $endorserRow)
                  @php $endorserVendor = $endorserRow->endorserPicture()[0] ?? null; @endphp
                  @if($endorserVendor)
                    <x-avatar :model="$endorserVendor" class="vsf-endorsement__avatar" />
                  @else
                    <img class="vsf-endorsement__avatar" src="{{ \App\Support\ProfileImageStorage::url(null) }}" alt="" />
                  @endif
                @empty
                  <img class="vsf-endorsement__avatar" src="{{ \App\Support\ProfileImageStorage::url(null) }}" alt="" />
                @endforelse
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="vsf-empty vsf-empty--inline">No endorsements yet.</p>
      @endif
    </section>

    <section id="vsf-pricing" class="vsf-pricing">
      <article class="vsf-pricing__card">
        <h3 class="vsf-pricing__label">Average Price</h3>
        <p class="vsf-pricing__value">{{ $vendor->preferredPricing() }}</p>
      </article>
      <article class="vsf-pricing__card">
        <h3 class="vsf-pricing__label">Preferred Pricing Offer:</h3>
        <p class="vsf-pricing__value">${{ $vendor->discount }}</p>
      </article>
    </section>

    @if($googleRatingDisplay)
      <section id="vsf-reviews" class="vsf-section">
        <div class="vsf-reviews__head">
          <h3 class="vsf-reviews__summary">
            <span class="vsf-reviews__star" aria-hidden="true">★</span>
            {{ $googleRatingDisplay }} Rating
            @if($profile->google_reviews_count > 0)
              ({{ number_format($profile->google_reviews_count) }})
            @endif
          </h3>
          @if($profile->google_place_link)
            <a href="{{ $profile->google_place_link }}" target="_blank" rel="noopener" class="vsf-reviews__see-all">See All</a>
          @endif
        </div>
        <div class="vsf-reviews__grid">
          @foreach($vendor->getReviews() as $review)
            <article class="vsf-review">
              <h4 class="vsf-review__author">{{ $review->author }}</h4>
              <p class="vsf-review__stars" aria-label="{{ $review->rating }} stars">
                @for($s = 0; $s < min(5, (int) $review->rating); $s++)★@endfor
              </p>
              <p class="vsf-review__text is-clamped" data-vsf-review-text>{{ $review->body }}</p>
              <button type="button" class="vsf-review__more" data-vsf-review-toggle hidden>
                Show more <span aria-hidden="true">⌄</span>
              </button>
            </article>
          @endforeach
        </div>
      </section>
    @endif
  </div>

  @if($authVendor)
    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  @else
    @include('layouts.footer')
  @endif
</main>

<div id="vsf-lightbox" class="vsf-lightbox" aria-hidden="true">
  <button type="button" id="vsf-lightbox-close" class="vsf-lightbox__close" aria-label="Close">&times;</button>
  <img src="" alt="" />
</div>

<div id="vsf-lightbox-all" class="vsf-lightbox-all" aria-hidden="true">
  <button type="button" id="vsf-lightbox-all-close" class="vsf-lightbox__close" aria-label="Close">&times;</button>
  <div class="vsf-lightbox-all__grid">
    @foreach($portfolioImages as $image)
      <img src="{{ \App\Support\ProfileImageStorage::url($image) }}" alt="" loading="lazy" />
    @endforeach
  </div>
</div>

@includeWhen($isCouple, 'modals.check_date')
<x-large-modal id="request-consultation">
  @includeWhen($isCouple, 'modals.request_consultation')
</x-large-modal>
<x-large-modal id="endorse-vendor">
  @includeWhen($isOtherVendor, 'modals.endorsement')
</x-large-modal>

@vite('resources/js/vendor.js')

@if($isCouple && Auth::user()->hasFavorite($vendor->id))
  <script>
    $("#heart").toggleClass("is-active");
    $("#heart").html('<i class="fas fa-heart text-3xl" style="color: #6432C8;"></i>');
  </script>
@endif
</body>
</html>
