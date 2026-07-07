<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: My Profile</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();
@endphp

@include('layouts.couple_sidebar', ['page' => 'edit_profile'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    <div class="vd-topbar">
      <a href="{{ route('search.vendors') }}" class="vd-topbar__btn" aria-label="Search vendors">🔍</a>
      <a href="{{ route('client.inbox') }}" class="vd-topbar__btn" aria-label="Notifications">🔔</a>
      <a href="{{ route('user.account.settings') }}" class="vd-topbar__btn" aria-label="Settings">⚙️</a>
    </div>

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">My Profile</h1>
      <a href="{{ route('user.profile.edit') }}" class="vd-page-header__edit-link">✏️ Edit my Profile</a>
    </header>

    @if(session('status') === 'profile-updated')
      <div class="vd-edit-success-banner">Updated profile!</div>
    @endif

    <section class="vd-profile-banner">
      <img class="vd-profile-banner__avatar" src="{{ \App\Support\ProfileImageStorage::url($user->image) }}" alt="" />
      <div class="vd-profile-banner__main">
        <h2 class="vd-profile-banner__names">
          <span>{{ $user->first_name }}</span>
          <span class="vd-profile-banner__heart" aria-hidden="true">♥</span>
          <span>{{ $user->fiance_first_name }}</span>
        </h2>
        <div class="vd-profile-banner__grid">
          <p class="vd-profile-banner__item">Wedding Date: <strong>{{ $weddingDateDisplay }}</strong></p>
          <p class="vd-profile-banner__item">We are looking to book our vendors within: <strong>{{ $bookingWindow }}</strong></p>
          <p class="vd-profile-banner__item">Wedding venue Location: <strong>{{ $venueLocation }}</strong></p>
          <p class="vd-profile-banner__item">Wedding venue Name: <strong>{{ $venueName }}</strong></p>
        </div>
      </div>
    </section>

    <div class="vd-profile-info">
      <article class="vd-profile-info__card">
        <h3 class="vd-profile-info__title">A little bit about <strong>{{ $user->first_name }} &amp; {{ $user->fiance_first_name }}</strong></h3>
        @if($bioWithoutVenue !== '')
          <p class="vd-profile-info__body">{{ $bioWithoutVenue }}</p>
        @else
          <p class="vd-profile-info__body vd-profile-info__body--placeholder">{{ $user->first_name }} &amp; {{ $user->fiance_first_name }} haven't written a bio yet.</p>
        @endif
      </article>

      <article class="vd-profile-info__card">
        <h3 class="vd-profile-info__title">Describe your dream wedding in three words.</h3>
        @if($answers[0])
          <p class="vd-profile-info__body">{{ $answers[0] }}</p>
        @else
          <p class="vd-profile-info__body vd-profile-info__body--placeholder">one - two - three</p>
        @endif
      </article>

      <article class="vd-profile-info__card">
        <h3 class="vd-profile-info__title">What are you most looking forward to about your wedding?</h3>
        @if($answers[1])
          <p class="vd-profile-info__body">{{ $answers[1] }}</p>
        @else
          <p class="vd-profile-info__body vd-profile-info__body--placeholder">—</p>
        @endif
      </article>

      <article class="vd-profile-info__card">
        <h3 class="vd-profile-info__title">Are there any specific traditions that are important for you to include, or avoid?</h3>
        @if($answers[2])
          <p class="vd-profile-info__body">{{ $answers[2] }}</p>
        @else
          <p class="vd-profile-info__body vd-profile-info__body--placeholder">—</p>
        @endif
      </article>

      <article class="vd-profile-info__card">
        <h3 class="vd-profile-info__title">Is there anything else you&rsquo;d like your wedding vendors to know before working together?</h3>
        @if($answers[3])
          <p class="vd-profile-info__body">{{ $answers[3] }}</p>
        @else
          <p class="vd-profile-info__body vd-profile-info__body--placeholder">—</p>
        @endif
      </article>
    </div>

    <section style="margin-top:40px;width:100%;">
      <h3 class="vd-profile-section-title">We are looking to connect with:</h3>
      <div class="vd-profile-pills">
        @php $hasSearchingFor = false; @endphp
        @foreach($vendor_types as $vendorType)
          @if($searching_for->contains($vendorType->id))
            @php $hasSearchingFor = true; @endphp
            <span class="vd-profile-pill">
              <img src="{{ asset($vendorType->icon) }}" alt="" />
              <span>{{ $vendorType->type }}</span>
            </span>
          @endif
        @endforeach
        @if(!$hasSearchingFor)
          <p style="color:#7a7a7a;">No vendor categories selected yet.</p>
        @endif
      </div>
    </section>

    <section style="margin-top:40px;width:100%;">
      <h3 class="vd-profile-section-title">Booked vendors:</h3>
      <div class="vd-myvendors-grid">
        @forelse($bookedVendors as $pairing)
          <x-couple-vendor-card :vendor="$pairing->vendor" :show-status="false" :show-badges="true" :show-consultation="false" />
        @empty
          <p style="grid-column:1/-1;color:#7a7a7a;">No booked vendors yet.</p>
        @endforelse
      </div>
    </section>

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
