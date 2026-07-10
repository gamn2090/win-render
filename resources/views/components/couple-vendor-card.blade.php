@props(['vendor', 'showStatus' => true, 'showBadges' => false, 'showConsultation' => true])

@php
  $user = Auth::guard('web')->user();
  $vendorType = $vendor->getType();
  $favorited = $user->hasFavorite($vendor->id);
  $pairing = $user->pairingWith($vendor->id);
@endphp

<article class="vd-vendor-card">
  <div class="vd-vendor-card__media">
    <button
      type="button"
      class="vd-vendor-card__favorite favorite-toggle-btn"
      data-vendor-id="{{ $vendor->uuid }}"
      data-favorited="{{ $favorited ? '1' : '0' }}"
      aria-label="Toggle favorite"
    >{{ $favorited ? '♥' : '♡' }}</button>

    @if($showStatus && $pairing)
      <div class="vd-vendor-card__status-track">
        @if($pairing->status >= 1)
          <span class="vd-vendor-card__status-step vd-vendor-card__status-step--inquiry">Inquiry</span>
        @endif
        @if($pairing->status >= 2)
          <span class="vd-vendor-card__status-step vd-vendor-card__status-step--consultation">Consultation</span>
        @endif
        @if($pairing->status >= 3)
          <span class="vd-vendor-card__status-step vd-vendor-card__status-step--booked">Booked</span>
        @endif
      </div>
    @endif

    <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-vendor-card__image-link" tabindex="-1" aria-hidden="true">
      <img class="vd-vendor-card__image" src="{{ \App\Support\ProfileImageStorage::url($vendor->image) }}" alt="" />
    </a>
  </div>

  <div class="vd-vendor-card__body">
    <h3 class="vd-vendor-card__name">{{ $vendor->business_name }}</h3>

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

    @if($showBadges && $vendor->badges()->count() > 0)
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
      @if($showStatus && $pairing && $pairing->status < 3)
        <button type="button" class="vd-vendor-card__btn vd-vendor-card__btn--message mark-booked-btn" data-vendor-uuid="{{ $vendor->uuid }}">Mark as Booked</button>
      @endif
      <a href="{{ route('user.vendor.message', $vendor->id) }}" class="vd-vendor-card__btn vd-vendor-card__btn--message">Message</a>
    </div>
    @if($showConsultation)
      <div class="vd-vendor-card__actions" style="margin-top:8px;">
        <button type="button" class="vd-vendor-card__btn vd-vendor-card__btn--storefront open-schedule-modal" data-vendor-id="{{ $vendor->id }}">Schedule a Consultation</button>
      </div>
    @endif
    <div class="vd-vendor-card__actions" style="margin-top:8px;">
      <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-vendor-card__btn vd-vendor-card__btn--storefront">Storefront</a>
    </div>
  </div>
</article>
