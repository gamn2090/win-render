<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Dashboard</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();
  $bookedVendors = $pairings->filter(fn ($p) => $p->status == 3 && $p->vendor);
  $vendorMatchesCount = $requestedVendorTypes->count();
  $unreadCount = $user->unreadMessagesCount();
  $hour = (int) now()->format('G');
  $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
  $randomVendors = \App\Models\Vendor::where('visible', 1)->whereNotNull('image')->inRandomOrder()->limit(5)->get();
@endphp

@include('layouts.couple_sidebar', ['page' => 'dashboard'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple', 'unreadCount' => $unreadCount])

    <div class="vd-announcement" id="vd-announcement" role="region" aria-label="Announcement">
      <p>
        🎉 <strong>New:</strong>
        Filter features added for venues, hair &amp; makeup, photographers, and videographers. Go to
        <a href="{{ route('search.vendors') }}">Find Vendors</a> to search by your preferred style.
      </p>
      <button type="button" class="vd-announcement__close" id="vd-announcement-close" aria-label="Dismiss">&times;</button>
    </div>

    <header class="vd-greeting">
      <h1 class="vd-greeting__title">
        {{ $greeting }}, {{ ucfirst(strtolower($user->first_name)) }} &amp; {{ ucfirst(strtolower($user->fiance_first_name)) }} 💍
      </h1>

      @if($user->moneySaved($user->bookedVendors()) > 0)
        <div class="vd-savings-banner">
          You've saved ${{ $user->moneySaved($user->bookedVendors()) }} with WIN vendor discounts!
        </div>
      @else
        <a href="{{ route('search.vendors') }}" class="vd-savings-banner">Start Saving by Booking Vendors</a>
      @endif

      <p class="vd-greeting__sub">Here's what's happening with your Wedding Day Planner</p>
    </header>

    <section class="vd-stats" aria-label="Key metrics">
      <article class="vd-stat-card" style="--vd-stat-accent: #6432c8;">
        <div class="vd-stat-card__top">
          <span class="vd-stat-card__icon-wrap" style="background:#f0ebf9;">📅</span>
        </div>
        <p class="vd-stat-card__value">{{ $user->daysUntilWedding() }}</p>
        <p class="vd-stat-card__label">Days Until Wedding</p>
      </article>

      <article class="vd-stat-card" style="--vd-stat-accent: #fb962f;">
        <div class="vd-stat-card__top">
          <span class="vd-stat-card__icon-wrap" style="background:#fef0e6;">💬</span>
          @if($unreadCount > 0)
            <span class="vd-stat-card__badge vd-stat-card__badge--up">↑ +{{ $unreadCount }} new</span>
          @endif
        </div>
        <p class="vd-stat-card__value">{{ $unreadCount }}</p>
        <p class="vd-stat-card__label">Unread Messages</p>
      </article>

      <article class="vd-stat-card" style="--vd-stat-accent: #22c55e;">
        <div class="vd-stat-card__top">
          <span class="vd-stat-card__icon-wrap" style="background:#e6f7f1;">✅</span>
          <span class="vd-stat-card__badge vd-stat-card__badge--muted">This month</span>
        </div>
        <p class="vd-stat-card__value">{{ $user->bookedVendorsCount() }}</p>
        <p class="vd-stat-card__label">Vendors Booked</p>
      </article>

      <article class="vd-stat-card" style="--vd-stat-accent: #c9930a;">
        <div class="vd-stat-card__top">
          <span class="vd-stat-card__icon-wrap" style="background:#fdf6e3;">⚡</span>
          <span class="vd-stat-card__badge vd-stat-card__badge--muted">Available</span>
        </div>
        <p class="vd-stat-card__value">{{ $vendorMatchesCount }}</p>
        <div class="vd-stat-card__footer">
          <p class="vd-stat-card__label">Vendor Matches</p>
          <a href="{{ route('search.vendors') }}" class="vd-stat-card__cta">Find Vendors</a>
        </div>
      </article>
    </section>

    <section class="vd-duo" aria-label="Messages and appointments">
      <article class="vd-card vd-card--feed">
        <div class="vd-card__head">
          <h2 class="vd-card__title">Messages</h2>
          <a href="{{ route('client.inbox') }}" class="vd-card__link">View all →</a>
        </div>
        <div class="vd-card__body">
          <div class="vd-meetings-banner">Unread messages <strong>{{ $unreadCount }}</strong></div>
          <div class="vd-card__scroll">
            @forelse($recentConversations as $convo)
              @php
                $participant = collect($convo->conversation->participants)
                  ->first(fn ($p) => $p->messageable_type === \App\Models\Vendor::class);
              @endphp
              @if($participant)
                <div class="vd-list-row">
                  <img class="vd-list-row__avatar" src="{{ \App\Support\ProfileImageStorage::url($participant->messageable->image) }}" alt="" width="36" height="36" />
                  <div class="vd-list-row__text">
                    <p class="vd-list-row__name">{{ $participant->messageable->business_name ?? trim($participant->messageable->first_name . ' ' . $participant->messageable->last_name) }}</p>
                    <p class="vd-list-row__preview">{{ Str::limit($convo->conversation->last_message->body ?? 'Start a conversation!', 48) }}</p>
                  </div>
                  <div class="vd-list-row__actions">
                    <a href="{{ route('get.client.conversation', $convo->conversation->id) }}" class="vd-btn-view">VIEW</a>
                    <a href="{{ route('get.client.conversation', $convo->conversation->id) }}" class="vd-btn-chat" aria-label="Open chat">💬</a>
                  </div>
                </div>
              @endif
            @empty
              <p class="vd-list-row__preview" style="padding:12px 0;">No conversations yet.</p>
            @endforelse
          </div>
          <div class="vd-card__footer">
            <a href="{{ route('client.inbox') }}" class="vd-btn-pill vd-btn-pill--purple">View All Messages</a>
          </div>
        </div>
      </article>

      <article class="vd-card vd-card--feed">
        <div class="vd-card__head">
          <h2 class="vd-card__title">My Wedding Appointments</h2>
          <a href="{{ route('appointments.list') }}" class="vd-card__link">Set my availability →</a>
        </div>
        <div class="vd-card__body">
          <div class="vd-meetings-banner">Meeting(S) upcoming! <strong>{{ $upcomingMeetings->count() }}</strong></div>
          <div class="vd-card__scroll">
            @forelse($upcomingMeetings as $meeting)
              @php $meetingVendor = $meeting->vendor; @endphp
              @if($meetingVendor)
                <div class="vd-list-row vd-list-row--appointment">
                  <img class="vd-list-row__avatar" src="{{ \App\Support\ProfileImageStorage::url($meetingVendor->image) }}" alt="" width="36" height="36" />
                  <div class="vd-list-row__text">
                    <p class="vd-list-row__name">{{ $meetingVendor->business_name }}</p>
                    <p class="vd-list-row__preview">{{ ucfirst($meeting->type) }} &middot; {{ $meeting->readableTime() }}</p>
                  </div>
                  <div class="vd-list-row__actions">
                    <a href="{{ route('appointments.list') }}" class="vd-btn-view">VIEW</a>
                    <a href="{{ route('user.vendor.message', $meetingVendor->id) }}" class="vd-btn-chat" aria-label="Message vendor">💬</a>
                  </div>
                </div>
              @endif
            @empty
              <p class="vd-list-row__preview" style="padding:8px 0;">No upcoming appointments.</p>
            @endforelse
          </div>
          <div class="vd-card__footer">
            <a href="{{ route('appointments.list') }}" class="vd-btn-pill vd-btn-pill--purple">View All Appointments</a>
          </div>
        </div>
      </article>
    </section>

    <section class="vd-duo" aria-label="Planning and vendor status">
      <div style="display:flex;flex-direction:column;gap:16px;">
        <h2 class="vd-tool-card__title" style="font-size:20px;">Your Wedding Planning Starts Here</h2>

        <article class="vd-tool-card vd-tool-card--investment">
          <div class="vd-tool-card__icon-wrap vd-tool-card__icon-wrap--peach" aria-hidden="true">
            <img class="vd-tool-card__icon" src="{{ asset('assets/img/vendor-home/new_icons/money.png') }}" alt="" />
          </div>
          <div class="vd-tool-card__body">
            <span class="vd-tool-card__eyebrow vd-tool-card__eyebrow--orange">Start Your Budget Planning</span>
            <h3 class="vd-tool-card__title">WIN Wedding Investment Planner</h3>
            <p class="vd-tool-card__desc">Get a clear picture of your wedding costs and set your priorities. Your budget is the first step to finding the right vendors.</p>
            <a href="{{ route('couple.investment_planner') }}" class="vd-tool-card__btn vd-tool-card__btn--orange">Create Your Budget</a>
          </div>
        </article>

        <article class="vd-tool-card vd-tool-card--timeline">
          <div class="vd-tool-card__icon-wrap vd-tool-card__icon-wrap--purple" aria-hidden="true">📋</div>
          <div class="vd-tool-card__body">
            <div class="vd-tool-card__labels">
              <span class="vd-tool-card__eyebrow vd-tool-card__eyebrow--purple">New Tool</span>
              <span class="vd-tool-card__badge">NOW AVAILABLE</span>
            </div>
            <h3 class="vd-tool-card__title">WIN Wedding Timeline Planner</h3>
            <p class="vd-tool-card__desc">Build, share &amp; collaborate on day-of timelines with your vendors. Save progress between sessions.</p>
            <a href="{{ route('couple.timeline') }}" class="vd-tool-card__btn">Open Timeline Planner</a>
          </div>
        </article>
      </div>

      <article class="vd-card vd-card--feed">
        <div class="vd-card__head">
          <h2 class="vd-card__title">Vendor Status</h2>
        </div>
        <div class="vd-card__body">
          <div class="vd-meetings-banner vd-meetings-banner--booked">
            <span class="vd-meetings-banner__count">{{ $bookedVendors->count() }}</span>
            <span class="vd-meetings-banner__label">Booked!</span>
          </div>
          <div class="vd-card__scroll">
            <div class="vd-status-list">
              @forelse($requestedVendorTypes as $vendorType)
                @php
                  $matchingPairings = $pairings->filter(fn ($p) => $p->vendor && $p->vendor->type == $vendorType->id);
                  $isBooked = $matchingPairings->contains(fn ($p) => $p->status == 3);
                  $isSearching = !$isBooked && $matchingPairings->isNotEmpty();
                @endphp
                <div class="vd-status-row">
                  <img class="vd-status-row__icon" src="{{ asset($vendorType->icon) }}" alt="" />
                  <span class="vd-status-row__label">{{ $vendorType->type }}</span>
                  <div class="vd-status-row__actions">
                    @if($isBooked)
                      <span class="vd-tag vd-tag--booked">Booked</span>
                    @elseif($isSearching)
                      <span class="vd-tag vd-tag--searching">Searching</span>
                    @else
                      <span class="vd-tag vd-tag--needed">Needed</span>
                    @endif
                    <a href="{{ route('search.vendor.type', $vendorType->id) }}" class="vd-btn-search">Search</a>
                  </div>
                </div>
              @empty
                <p class="vd-list-row__preview" style="padding:12px 0;">No vendor categories requested yet.</p>
              @endforelse
            </div>
          </div>
          <div class="vd-card__footer">
            <a href="{{ route('search.vendors') }}" class="vd-btn-pill vd-btn-pill--purple">Search</a>
          </div>
        </div>
      </article>
    </section>

    <section class="vd-browse">
      <h2 class="vd-browse__title">Your Wedding Team</h2>
      <div class="vd-browse__divider" aria-hidden="true"></div>
      <div class="vd-browse__grid">
        @forelse($bookedVendors as $pairing)
          @php $vendor = $pairing->vendor; @endphp
          <article class="vd-vendor-card">
            <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-vendor-card__image-link" tabindex="-1" aria-hidden="true">
              <img class="vd-vendor-card__image" src="{{ \App\Support\ProfileImageStorage::url($vendor->image) }}" alt="" />
            </a>
            <div class="vd-vendor-card__body">
              <h3 class="vd-vendor-card__name">{{ $vendor->business_name }}</h3>
              <div class="vd-vendor-card__meta">
                @if($vendor->getType())
                  <span class="vd-vendor-card__type">
                    <img src="{{ asset($vendor->getType()->icon) }}" alt="" class="vd-vendor-card__type-icon" width="18" height="18" />
                    <span class="vd-vendor-card__type-label">{{ $vendor->getType()->type }}</span>
                  </span>
                @endif
                <span class="vd-vendor-card__rating">
                  <span class="vd-vendor-card__rating-num">★ {{ number_format($vendor->googleRating(), 1) }}</span>
                </span>
              </div>
              <p class="vd-vendor-card__location">{{ $vendor->location }}</p>
              <div class="vd-vendor-card__actions">
                <a href="{{ route('user.vendor.message', $vendor->id) }}" class="vd-vendor-card__btn vd-vendor-card__btn--message">Message</a>
                <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-vendor-card__btn vd-vendor-card__btn--storefront">Storefront</a>
              </div>
            </div>
          </article>
        @empty
          <p style="grid-column:1/-1;text-align:center;color:#7a7a7a;">You haven't booked any vendors yet.</p>
        @endforelse
      </div>
      <p style="text-align:center;margin-top:16px;">
        <a href="{{ route('client.vendor.list') }}" class="vd-promo__btn vd-promo__btn--purple" style="display:inline-flex;">View More Vendors</a>
      </p>
    </section>

    <section class="vd-promo-row" aria-label="Vendor CTA">
      <div class="vd-promo vd-promo--refer">
        <h3 class="vd-promo__title">Connect with top vendors tailored to your needs</h3>
        <div class="vd-promo__actions">
          <a href="{{ route('search.vendors') }}" class="vd-promo__btn vd-promo__btn--white">Get Matched with Vendors</a>
        </div>
      </div>

      <div class="vd-promo" style="background:#fff;border:1px solid #eae7f0;">
        <div class="vd-network-summary" style="padding:0;">
          <div class="vd-network-summary__avatars">
            @foreach($randomVendors as $vendor)
              <img src="{{ \App\Support\ProfileImageStorage::url($vendor->image) }}" alt="" />
            @endforeach
          </div>
        </div>
        <div>
          <p class="vd-network-summary__text" style="font-weight:400;margin-bottom:10px;">Find trusted vendors for your wedding</p>
          <a href="{{ route('search.vendors') }}" class="vd-vendor-card__btn vd-vendor-card__btn--storefront" style="display:inline-flex;padding-left:24px;padding-right:24px;">Explore Vendors</a>
        </div>
      </div>
    </section>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

<script>
  document.getElementById('vd-announcement-close')?.addEventListener('click', function () {
    document.getElementById('vd-announcement')?.remove();
  });
</script>
</body>
</html>
