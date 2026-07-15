<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: {{ $vendor->business_name }}</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();

  $locationLine = trim($vendor->location ?? '');
  if ($vendor->service_radius) {
      $locationLine .= ($locationLine ? ' · ' : '') . $vendor->service_radius . ' mi';
  }

  $rankLabel = '#' . $rank . ' in ' . ($vendor_type->type ?? 'Vendor');
  if ($vendor->location) {
      $rankLabel .= ' · ' . trim(explode(',', $vendor->location)[0]);
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
      if ($type && !in_array($type->id, $includedRoleIds, true)) {
          $includedRoleIds[] = $type->id;
          $includedRoles[] = $type;
      }
  }

  $favorited = $user->hasFavorite($vendor->id);
  $hasPairing = $user->pairingWith($vendor->id) !== null;
@endphp

@include('layouts.couple_sidebar', ['page' => 'find_vendors'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple'])

    <section class="vd-storefront-header">
      <x-avatar :model="$vendor" class="vd-storefront-avatar" />
      <div class="vd-storefront-info">
        <div>
          <h1 class="vd-storefront-business">{{ $vendor->business_name }}</h1>
          <p class="vd-storefront-name">{{ $vendor->first_name }} {{ $vendor->last_name }}</p>
          @if($locationLine)
            <p class="vd-storefront-location">{{ $locationLine }}</p>
          @endif
          <span class="vd-storefront-rank">{{ $rankLabel }}</span>
        </div>

        <div class="vd-storefront-meta">
          @if($vendor_type)
            <span class="vd-storefront-type-pill">
              <img src="{{ asset(ltrim($vendor_type->icon ?? '', '/')) }}" alt="" />
              <span>{{ $vendor_type->type }}</span>
            </span>
          @endif
          @if(($vendor->discount ?? 0) > 0)
            <span class="vd-storefront-pricing-pill">${{ $vendor->discount }} Preferred Pricing</span>
          @endif
          @foreach($vendor->badges() as $badge)
            <span class="vd-vendor-card__badge" title="{{ $badge->name }}">
              <img src="{{ asset('images/' . $badge->icon) }}" alt="" />
            </span>
          @endforeach
        </div>

        <div class="vd-storefront-actions">
          <button
            type="button"
            class="vd-storefront-favorite favorite-toggle-btn"
            data-vendor-id="{{ $vendor->uuid }}"
            data-favorited="{{ $favorited ? '1' : '0' }}"
            aria-label="Toggle favorite"
          >{{ $favorited ? '♥' : '♡' }}</button>
          @unless($hasPairing)
            <button type="button" id="send-inquiry-btn" class="vd-storefront-btn vd-storefront-btn--inquiry" data-vendor-id="{{ $vendor->id }}">Send Inquiry</button>
          @endunless
          <a href="{{ route('user.vendor.message', $vendor->id) }}" class="vd-storefront-btn vd-storefront-btn--message">Message Vendor</a>
          <button type="button" class="vd-storefront-btn vd-storefront-btn--consultation open-schedule-modal" data-vendor-id="{{ $vendor->id }}">★ Request Consultation</button>
        </div>
      </div>
    </section>

    <nav class="vd-storefront-nav" aria-label="Storefront sections">
      @if($coverImage)
        <a href="#vd-sf-photos" class="vd-storefront-nav__link">Photos</a>
      @endif
      <a href="#vd-sf-about" class="vd-storefront-nav__link">About</a>
      <a href="#vd-sf-preferred" class="vd-storefront-nav__link">Preferred Vendors</a>
      <a href="#vd-sf-pricing" class="vd-storefront-nav__link">Pricing</a>
      @if($googleRatingDisplay)
        <a href="#vd-sf-reviews" class="vd-storefront-nav__link">Reviews</a>
      @endif
    </nav>

    <div class="vd-profile-info">
      <article class="vd-profile-info__card">
        <h3 class="vd-profile-info__title">Social Brands:</h3>
        <div class="vd-storefront-social">
          @if($profile->instagram_link)
            <a href="{{ $profile->getLink('instagram') }}" target="_blank" rel="noopener" class="vd-storefront-social__icon" aria-label="Instagram">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
          @endif
          @if($profile->facebook_link)
            <a href="{{ $profile->getLink('facebook') }}" target="_blank" rel="noopener" class="vd-storefront-social__icon" aria-label="Facebook">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
          @endif
          @if($profile->linkedin_link)
            <a href="{{ $profile->getLink('linkedin') }}" target="_blank" rel="noopener" class="vd-storefront-social__icon" aria-label="LinkedIn">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
          @endif
          @if($profile->business_link)
            <a href="//{{ $profile->business_link }}" target="_blank" rel="noopener" class="vd-storefront-social__website">Visit Website</a>
          @endif
          @if($googleRatingDisplay)
            <span class="vd-storefront-social__rating">Google Ratings <strong>★ {{ $googleRatingDisplay }}</strong></span>
          @endif
        </div>
      </article>

      <article id="vd-sf-about" class="vd-profile-info__card">
        <h3 class="vd-profile-info__title">About {{ $vendor->business_name }}:</h3>
        <p class="vd-profile-info__body">
          @if($profile->bio)
            {!! nl2br(e($profile->bio)) !!}
          @else
            {{ $vendor->first_name }} hasn't added a bio yet.
          @endif
        </p>
      </article>

      @if($coverImage)
        <article id="vd-sf-photos" class="vd-profile-info__card">
          <h3 class="vd-profile-info__title">See my work:</h3>
          <p class="vd-gallery__label">*Cover Photo</p>
          <div class="vd-gallery">
            <div class="vd-gallery__hero vd-gallery__hero--cover">
              <img class="vd-lightbox-trigger" src="{{ \App\Support\ProfileImageStorage::url($coverImage) }}" alt="" loading="lazy" />
            </div>
            @foreach($galleryThumbs as $index => $image)
              <div class="vd-gallery__thumb vd-gallery__thumb--{{ $index + 1 }}">
                <img class="vd-lightbox-trigger" src="{{ \App\Support\ProfileImageStorage::url($image) }}" alt="" loading="lazy" />
              </div>
            @endforeach
          </div>
          @if(count($portfolioImages) > 0)
            <button type="button" id="vd-view-all-photos" class="vd-gallery__view-all">View All</button>
          @endif
        </article>
      @endif
    </div>

    <section id="vd-sf-preferred" style="margin-top:40px;width:100%;">
      <h3 class="vd-profile-section-title">Preferred Vendors:</h3>
      @if(count($includedRoles) > 0)
        <div class="vd-tab-pills" role="tablist">
          @foreach($includedRoles as $index => $role)
            <button type="button" class="vd-tab-pill vd-storefront-tab-trigger @if($index === 0) vd-tab-pill--active @endif" data-vd-tab="vd-sf-tab-{{ $role->id }}" role="tab" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
              <img src="{{ asset(ltrim($role->icon ?? '', '/')) }}" alt="" />
              <span>{{ $role->type }}</span>
            </button>
          @endforeach
        </div>
        @foreach($includedRoles as $index => $role)
          <div id="vd-sf-tab-{{ $role->id }}" class="vd-tab-panel @if($index === 0) is-active @endif">
            <div class="vd-myvendors-grid">
              @foreach($vendor->connections()->where('aff_vendor_type', $role->id)->get() as $affVendor)
                <x-couple-vendor-card :vendor="$affVendor" :show-status="false" :show-badges="true" :show-consultation="false" />
              @endforeach
            </div>
          </div>
        @endforeach
      @else
        <p style="color:#7a7a7a;">This vendor doesn&rsquo;t have any preferred vendors added to their storefront yet.</p>
      @endif
    </section>

    <section style="margin-top:40px;width:100%;">
      <h3 class="vd-profile-section-title">Endorsements:</h3>
      <p style="color:#231f20;text-transform:uppercase;letter-spacing:1.2px;font-size:14px;margin:0 0 16px;">
        {{ $vendor->endorsements()->distinct()->count('endorser') }} vendors find {{ $vendor->business_name }} to be:
      </p>
      @if($endorsements->count() > 0)
        <div class="vd-endorsements-grid">
          @foreach($endorsements as $endorsement)
            <div class="vd-endorsement">
              <span class="vd-endorsement__label"><span class="vd-endorsement__star">★</span> {{ $endorsement->type }}</span>
              <div class="vd-endorsement__avatars">
                @forelse($vendor->endorsements()->where('type', $endorsement->typeNum)->select('endorser')->distinct()->take(4)->get() as $endorserRow)
                  @php $endorserVendor = $endorserRow->endorserPicture()[0] ?? null; @endphp
                  @if($endorserVendor)
                    <x-avatar :model="$endorserVendor" class="vd-endorsement__avatar" />
                  @endif
                @empty
                @endforelse
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p style="color:#7a7a7a;">No endorsements yet.</p>
      @endif
    </section>

    <section id="vd-sf-pricing" style="margin-top:40px;width:100%;">
      <div class="vd-price-cards">
        <article class="vd-profile-info__card vd-price-card">
          <h3 class="vd-profile-info__title">Average Price</h3>
          <p class="vd-price-card__value">{{ $vendor->preferredPricing() }}</p>
        </article>
        <article class="vd-profile-info__card vd-price-card">
          <h3 class="vd-profile-info__title">Preferred Pricing Offer:</h3>
          <p class="vd-price-card__value">${{ $vendor->discount }}</p>
        </article>
      </div>
    </section>

    @if($googleRatingDisplay)
      <section id="vd-sf-reviews" style="margin-top:40px;width:100%;">
        <h3 class="vd-profile-section-title">★ {{ $googleRatingDisplay }} Rating @if($profile->google_reviews_count > 0)({{ number_format($profile->google_reviews_count) }})@endif</h3>
        <div class="vd-review-grid">
          @foreach($vendor->getReviews() as $review)
            <article class="vd-review-card">
              <h4 class="vd-review-card__author">{{ $review->author }}</h4>
              <p class="vd-review-card__stars" aria-label="{{ $review->rating }} stars">
                @for($s = 0; $s < min(5, (int) $review->rating); $s++)★@endfor
              </p>
              <p class="vd-review-card__text is-clamped" data-vd-review-text>{{ $review->body }}</p>
              <button type="button" class="vd-review-card__toggle" data-vd-review-toggle hidden>Show more ⌄</button>
            </article>
          @endforeach
        </div>
      </section>
    @endif

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

@include('couple.partials.schedule-consultation-modal')

<div id="vd-lightbox" class="vd-lightbox">
  <button type="button" class="vd-lightbox__close" data-modal-close aria-label="Close">&times;</button>
  <img src="" alt="" />
</div>

<div id="vd-lightbox-all" class="vd-lightbox-all">
  <button type="button" class="vd-lightbox__close" data-modal-close aria-label="Close">&times;</button>
  <div class="vd-lightbox-all__grid">
    @foreach($portfolioImages as $image)
      <img src="{{ \App\Support\ProfileImageStorage::url($image) }}" alt="" loading="lazy" />
    @endforeach
  </div>
</div>

<script>
  (function () {
    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    function openModal(modal) { modal.classList.add('is-open'); }
    function closeModal(modal) { if (modal) modal.classList.remove('is-open'); }

    document.addEventListener('click', function (e) {
      var favBtn = e.target.closest('.favorite-toggle-btn');
      if (favBtn) {
        var active = favBtn.dataset.favorited === '1';
        var next = !active;
        favBtn.disabled = true;
        favBtn.dataset.favorited = next ? '1' : '0';
        favBtn.textContent = next ? '♥' : '♡';
        fetch('{{ route('toggle.favorite') }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: JSON.stringify({ vendor_uuid: favBtn.dataset.vendorId, active: next }),
        }).then(function (r) { return r.json(); }).then(function (data) {
          var name = favBtn.dataset.vendorName || 'Vendor';
          if (data.status) {
            WinToast.show('Vendor ' + name + (next ? ' added to your favorites.' : ' removed from your favorites.'), 'success');
          } else {
            WinToast.show(data.message || 'Something went wrong, please try again.', 'error');
          }
        }).finally(function () { favBtn.disabled = false; });
        return;
      }

      var inquiryBtn = e.target.closest('#send-inquiry-btn');
      if (inquiryBtn) {
        inquiryBtn.disabled = true;
        inquiryBtn.textContent = 'Sending…';
        fetch('{{ route('user.send.inquiry') }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: JSON.stringify({ vendor_id: inquiryBtn.dataset.vendorId }),
        })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.status) {
              inquiryBtn.textContent = 'Inquiry Sent ✓';
            } else {
              inquiryBtn.textContent = 'Send Inquiry';
              inquiryBtn.disabled = false;
            }
          })
          .catch(function () {
            inquiryBtn.textContent = 'Send Inquiry';
            inquiryBtn.disabled = false;
          });
        return;
      }

      var tabBtn = e.target.closest('.vd-storefront-tab-trigger');
      if (tabBtn) {
        document.querySelectorAll('.vd-storefront-tab-trigger').forEach(function (btn) {
          btn.classList.remove('vd-tab-pill--active');
          btn.setAttribute('aria-selected', 'false');
        });
        tabBtn.classList.add('vd-tab-pill--active');
        tabBtn.setAttribute('aria-selected', 'true');
        document.querySelectorAll('.vd-tab-panel').forEach(function (panel) { panel.classList.remove('is-active'); });
        var panel = document.getElementById(tabBtn.dataset.vdTab);
        if (panel) panel.classList.add('is-active');
        return;
      }

      var lightboxTrigger = e.target.closest('.vd-lightbox-trigger');
      if (lightboxTrigger) {
        document.querySelector('#vd-lightbox img').src = lightboxTrigger.src;
        openModal(document.getElementById('vd-lightbox'));
        return;
      }

      var viewAllBtn = e.target.closest('#vd-view-all-photos');
      if (viewAllBtn) {
        openModal(document.getElementById('vd-lightbox-all'));
        return;
      }

      var scheduleBtn = e.target.closest('.open-schedule-modal');
      if (scheduleBtn) {
        pendingScheduleVendorId = scheduleBtn.dataset.vendorId;
        selectedDate = null;
        calendarViewDate = new Date();
        scheduleConfirmBtn.disabled = true;
        renderCalendar();
        openModal(scheduleModal);
        return;
      }

      var closeTrigger = e.target.closest('[data-modal-close]');
      if (closeTrigger) {
        closeModal(closeTrigger.closest('.vd-modal-overlay, .vd-lightbox, .vd-lightbox-all'));
        return;
      }

      if (e.target.classList && (e.target.classList.contains('vd-modal-overlay') || e.target.id === 'vd-lightbox' || e.target.id === 'vd-lightbox-all')) {
        closeModal(e.target);
      }
    });

    document.querySelectorAll('[data-vd-review-text]').forEach(function (textEl) {
      var toggle = textEl.nextElementSibling;
      if (textEl.scrollHeight > textEl.clientHeight + 2) {
        toggle.hidden = false;
        toggle.addEventListener('click', function () {
          var expanded = !textEl.classList.contains('is-clamped');
          textEl.classList.toggle('is-clamped');
          toggle.textContent = expanded ? 'Show more ⌄' : 'Show less ⌃';
        });
      }
    });

    // ——— Schedule a Consultation modal ———
    var scheduleModal = document.getElementById('vd-schedule-modal');
    var scheduleConfirmBtn = document.getElementById('vd-schedule-confirm');
    var calendarGrid = document.getElementById('vd-calendar-grid');
    var calendarMonthLabel = document.getElementById('vd-calendar-month');
    var pendingScheduleVendorId = null;
    var calendarViewDate = new Date();
    var selectedDate = null;
    var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    function renderCalendar() {
      var year = calendarViewDate.getFullYear();
      var month = calendarViewDate.getMonth();
      calendarMonthLabel.textContent = monthNames[month] + ' ' + year;
      calendarGrid.innerHTML = '';

      var firstDay = new Date(year, month, 1).getDay();
      var daysInMonth = new Date(year, month + 1, 0).getDate();
      var today = new Date();
      today.setHours(0, 0, 0, 0);

      for (var i = 0; i < firstDay; i++) {
        calendarGrid.appendChild(document.createElement('span'));
      }

      var _loop = function (d) {
        var dayDate = new Date(year, month, d);
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'vd-calendar__day';
        btn.textContent = d;

        if (dayDate < today) {
          btn.classList.add('vd-calendar__day--disabled');
        } else {
          btn.addEventListener('click', function () {
            var prev = calendarGrid.querySelector('.vd-calendar__day--selected');
            if (prev) prev.classList.remove('vd-calendar__day--selected');
            btn.classList.add('vd-calendar__day--selected');
            selectedDate = dayDate;
            scheduleConfirmBtn.disabled = false;
          });
        }

        if (selectedDate && dayDate.getTime() === selectedDate.getTime()) {
          btn.classList.add('vd-calendar__day--selected');
        }

        calendarGrid.appendChild(btn);
      };

      for (var d = 1; d <= daysInMonth; d++) {
        _loop(d);
      }
    }

    document.getElementById('vd-calendar-prev').addEventListener('click', function () {
      calendarViewDate = new Date(calendarViewDate.getFullYear(), calendarViewDate.getMonth() - 1, 1);
      renderCalendar();
    });

    document.getElementById('vd-calendar-next').addEventListener('click', function () {
      calendarViewDate = new Date(calendarViewDate.getFullYear(), calendarViewDate.getMonth() + 1, 1);
      renderCalendar();
    });

    scheduleConfirmBtn.addEventListener('click', function () {
      if (!selectedDate || !pendingScheduleVendorId) return;

      var pad = function (n) { return String(n).padStart(2, '0'); };
      var hour = parseInt(document.getElementById('vd-time-hour').value, 10);
      var minute = document.getElementById('vd-time-minute').value;
      var ampm = document.getElementById('vd-time-ampm').value;
      var hour24 = hour % 12;
      if (ampm === 'PM') hour24 += 12;

      var dateStr = selectedDate.getFullYear() + '-' + pad(selectedDate.getMonth() + 1) + '-' + pad(selectedDate.getDate()) + ' ' + pad(hour24) + ':' + minute;

      scheduleConfirmBtn.disabled = true;
      fetch('{{ route('user.request.meeting') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ vendor_id: pendingScheduleVendorId, date: dateStr }),
      }).then(function () {
        window.location.reload();
      }).finally(function () {
        scheduleConfirmBtn.disabled = false;
      });
    });
  })();
</script>
</body>
</html>
