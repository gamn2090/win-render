<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Vendor Dashboard</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script>window.userID = {{ Auth::guard('vendor')->id() }};</script>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js', 'resources/js/chat.js'])
  @include('components.fonts')
  @if(!empty($data['first_login']))
  <script>window.newUser = true;</script>
  @endif
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $vendor = Auth::guard('vendor')->user();
  $icons = asset('assets/img/vendor-home/new_icons');
  $score = (int) round($data['score'] ?? $vendor->score ?? 0);
  $placement = ($data['placement'] ?? 0) + 1;
  $vendorTypeModel = $vendor->getType();
  $typeLabel = $vendorTypeModel?->type ?? 'Vendor';
  $location = $vendor->location ?: 'Your area';
  $levelNum = match (true) {
    $score >= 100 => 5,
    $score >= 80 => 4,
    $score >= 60 => 3,
    $score >= 40 => 2,
    $score >= 20 => 1,
    default => 1,
  };
  $levelTitle = match ($levelNum) {
    5 => 'Elite Vendor',
    4 => 'Top Performer',
    3 => 'Rising Star',
    2 => 'Growing Presence',
    default => 'Getting Started',
  };
  $nextLevelThreshold = [1 => 20, 2 => 40, 3 => 60, 4 => 100];
  $ptsToNextLevel = $levelNum >= 5 ? 0 : max(0, ($nextLevelThreshold[$levelNum] ?? 100) - $score);
  $nextLevelNum = min(5, $levelNum + 1);
  $nextLevelTitle = match ($nextLevelNum) {
    5 => 'Elite Vendor',
    4 => 'Top Performer',
    3 => 'Rising Star',
    2 => 'Growing Presence',
    default => 'Getting Started',
  };
  $rankingModel = $data['ranking'] ?? $vendor->vendor_ranking();
  $ranking = is_object($rankingModel) ? $rankingModel->toArray() : (array) $rankingModel;
  $vendorCommunityPts = (int) ($vendor->vendorCommunityRankValue()['value'] ?? 0);
  /* Mockup dashboard_vendor.png: 4 anillos (no Client Community en UI; sí entra al score total en backend). */
  $metricRings = [
    ['label' => 'Badges', 'key' => 'badges', 'color' => '#8B6FBE', 'style' => 'solid', 'display' => 'percent'],
    ['label' => 'Endorse.', 'key' => 'endorsements', 'color' => '#F26B1D', 'style' => 'solid', 'display' => 'percent'],
    ['label' => 'Reviews', 'key' => 'reviews', 'color' => '#2DA771', 'style' => 'dashed', 'display' => 'percent'],
    ['label' => 'Vendor Community', 'key' => 'vendor_community', 'color' => '#C9930A', 'style' => 'dashed', 'display' => 'points', 'points' => $vendorCommunityPts],
  ];
  /* Mismos 4 badges del dashboard antiguo (PNG en /images + helpers del modelo). */
  $dashboardBadgeSlots = [
    ['icon' => 'trending-badge.png', 'label' => 'Trending', 'earned' => $vendor->trendingBadge()],
    ['icon' => 'early-adopter-badge.png', 'label' => 'Early Adopter', 'earned' => $vendor->earlyAdopterBadge()],
    ['icon' => 'fast-responder-badge.png', 'label' => 'Fast Responder', 'earned' => $vendor->fastResponderBadge()],
    ['icon' => 'community-builder-badge.png', 'label' => 'Community Builder', 'earned' => $vendor->communityBuilderBadge()],
  ];
  $upcomingMeetings = $vendor->upcomingMeetings()->get();
  $upcomingCount = $upcomingMeetings->count();
  $hour = (int) now()->format('G');
  $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'vendor', 'unreadCount' => $vendor->unreadMessagesCount()])

    <div class="vd-announcement" id="vd-announcement" role="region" aria-label="Announcement">
      <p>
        🎉 <strong>New:</strong>
        Filter features added for venues, hair &amp; makeup, photographers, and videographers. Go to
        <a href="{{ url('/vendor/profile') }}">Edit Profile</a>
        to select your unique services.
      </p>
      <button type="button" class="vd-announcement__close" id="vd-announcement-close" aria-label="Dismiss">&times;</button>
    </div>

    <header class="vd-greeting">
      <h1 class="vd-greeting__title">{{ $greeting }}, {{ $vendor->first_name }}</h1>
      <p class="vd-greeting__sub">Here's what's happening with your business today.</p>
    </header>

  <section class="vd-stats" aria-label="Key metrics">
    <article class="vd-stat-card" style="--vd-stat-accent: #6432c8;">
      <div class="vd-stat-card__top">
        <span class="vd-stat-card__icon-wrap" style="background:#f0ebf9;">👁️</span>
        <span class="vd-stat-card__badge vd-stat-card__badge--up">↑ +12%</span>
      </div>
      <p class="vd-stat-card__value">{{ $vendor->storefront_views ?? 0 }}</p>
      <p class="vd-stat-card__label">Storefront Views</p>
    </article>

    <article class="vd-stat-card" style="--vd-stat-accent: #fb962f;">
      <div class="vd-stat-card__top">
        <span class="vd-stat-card__icon-wrap" style="background:#fef0e6;">✨</span>
        <span class="vd-stat-card__badge vd-stat-card__badge--up">↑ +{{ $data['newLeadsToday'] ?? 0 }} today</span>
      </div>
      <p class="vd-stat-card__value">{{ $data['newLeadsCount'] ?? $vendor->unreadMessagesCount() }}</p>
      <p class="vd-stat-card__label">New Leads</p>
    </article>

    <article class="vd-stat-card" style="--vd-stat-accent: #22c55e;">
      <div class="vd-stat-card__top">
        <span class="vd-stat-card__icon-wrap" style="background:#e6f7f1;">📅</span>
        <span class="vd-stat-card__badge vd-stat-card__badge--muted">This month</span>
      </div>
      <p class="vd-stat-card__value">{{ $data['activeBookings'] ?? 0 }}</p>
      <p class="vd-stat-card__label">Active Bookings</p>
    </article>

    <article class="vd-stat-card" style="--vd-stat-accent: #c9930a;">
      <div class="vd-stat-card__top">
        <span class="vd-stat-card__icon-wrap" style="background:#fdf6e3;">✉️</span>
        <span class="vd-stat-card__badge vd-stat-card__badge--muted">available</span>
      </div>
      <p class="vd-stat-card__value">{{ $vendor->contact_credits ?? 0 }}</p>
      <p class="vd-stat-card__label">Contact Credits</p>
      <a href="{{ route('vendor.find.couples') }}" class="vd-stat-card__cta">Find Couples</a>
    </article>
  </section>

  <section class="vd-trio" aria-label="Overview cards">
    {{-- WINfluence --}}
    <article class="vd-card">
      <div class="vd-card__head">
        <h2 class="vd-card__title">WINfluence Status</h2>
        <a href="{{ url('/vendor/insights') }}" class="vd-card__link">View Insights →</a>
      </div>
      <div class="vd-card__body">
        <div class="vd-winfluence__rank">
          <strong>#{{ $placement }} in {{ $typeLabel }} ·</strong>
          <span>{{ $location }}</span>
        </div>
        <div class="vd-winfluence__score-row">
          <p class="vd-winfluence__score">
            <span class="vd-winfluence__score-num">{{ $score }}</span><span class="vd-winfluence__score-denom">/100</span>
          </p>
          <div class="vd-winfluence__level-col">
            <p class="vd-winfluence__level">
              <strong>Level {{ $levelNum }}:</strong> {{ $levelTitle }}
            </p>
            @if($levelNum < 5)
            <p class="vd-winfluence__level-next">
              {{ $ptsToNextLevel }} pts to level {{ $nextLevelNum }}: {{ $nextLevelTitle }} ✨
            </p>
            @else
            <p class="vd-winfluence__level-next">Max level reached ✨</p>
            @endif
          </div>
        </div>

        <div class="vd-winfluence__metrics">
          @foreach($metricRings as $metric)
          <div class="vd-metric">
            <div
              class="vd-metric__ring vd-metric__ring--{{ $metric['style'] }}"
              style="--vd-ring-color: {{ $metric['color'] }};"
            >
              @if(($metric['display'] ?? 'percent') === 'points')
                {{ $metric['points'] }}pts
              @else
                {{ round($ranking[$metric['key']] ?? 0) }}%
              @endif
            </div>
            <p class="vd-metric__label">{{ $metric['label'] }}</p>
          </div>
          @endforeach
        </div>

        <div class="vd-badges-section">
          <p class="vd-badges-title">Your Badges</p>
          <div class="vd-badges-row">
            @foreach($dashboardBadgeSlots as $slot)
            <span
              class="vd-badge-pill {{ $slot['earned'] ? 'vd-badge-pill--earned' : 'vd-badge-pill--locked' }}"
              title="{{ $slot['label'] }}{{ $slot['earned'] ? '' : ' (locked)' }}"
            >
              @if($slot['earned'])
              <img
                src="{{ asset('images/' . $slot['icon']) }}"
                alt="{{ $slot['label'] }}"
                class="vd-badge-pill__img"
                width="22"
                height="22"
              />
              @else
              <i class="fas fa-lock vd-badge-pill__lock" aria-hidden="true"></i>
              @endif
            </span>
            @endforeach
          </div>
        </div>

        <div class="vd-action-box">
          <span class="vd-action-box__icon" aria-hidden="true">
            <img src="{{ $icons }}/fire.png" alt="" width="27" height="27" />
          </span>
          <div class="vd-action-box__content">
            <p class="vd-action-box__title">Top Action to Level Up</p>
            <p class="vd-action-box__text">Build Client Community — reach out to 3 past clients to join WIN</p>
          </div>
        </div>
      </div>
    </article>

    {{-- Messages (replaces Inbox card on dashboard; nav still has Inbox) --}}
    <article class="vd-card vd-card--feed">
      <div class="vd-card__head">
        <h2 class="vd-card__title">Messages</h2>
        <button type="button" class="vd-card__link vd-card__link--btn" data-vd-coming-soon="The full messages view">View all →</button>
      </div>
      <div class="vd-card__body">
        <div class="vd-card__scroll">
        @forelse($data['recentConversations'] as $convo)
          @php
            $other = null;
            foreach ($convo->conversation->participants as $participant) {
              if ($participant->messageable_id != $vendor->id || $participant->messageable_type !== 'App\Models\Vendor') {
                $other = $participant->messageable;
                break;
              }
            }
          @endphp
          @if($other)
          <div class="vd-list-row">
            <img class="vd-list-row__avatar" src="{{ asset('/storage/images/' . ($other->image ?: 'user.jpg')) }}" alt="" width="36" height="36" />
            <div class="vd-list-row__text">
              <p class="vd-list-row__name">{{ $other->first_name }} {{ $other->last_name }}</p>
              <p class="vd-list-row__preview">{{ Str::limit($convo->conversation->last_message->body ?? 'Start a conversation!', 48) }}</p>
            </div>
            <div class="vd-list-row__actions">
              <button type="button" class="vd-btn-view vd-btn-view--coming-soon" data-vd-coming-soon="Opening a conversation from here">VIEW</button>
              <button type="button" class="vd-btn-chat chat-window-btn"
                data-picture-url="{{ asset('/storage/images/' . ($other->image ?: 'user.jpg')) }}"
                data-name="{{ $other->first_name }} {{ $other->last_name }}"
                data-uuid="{{ $other->uuid ?? '' }}"
                data-user-type="{{ method_exists($other, 'userType') ? $other->userType() : 'user' }}"
                aria-label="Chat">💬</button>
            </div>
          </div>
          @endif
        @empty
          <p class="vd-list-row__preview" style="padding:12px 0;">No recent messages yet.</p>
        @endforelse
        </div>
        <div class="vd-card__footer">
          <button type="button" class="vd-btn-pill vd-btn-pill--orange" data-vd-coming-soon="The full messages view">View All Messages</button>
        </div>
      </div>
    </article>

    {{-- Appointments --}}
    <article class="vd-card vd-card--feed">
      <div class="vd-card__head">
        <h2 class="vd-card__title">My Wedding Appointments</h2>
        @if($upcomingCount > 0)
        <button type="button" class="vd-card__link vd-card__link--calendar vd-card__link--btn" aria-label="View appointments calendar" data-vd-coming-soon="The full appointments view">
          <i class="fa-regular fa-calendar" aria-hidden="true"></i>
        </button>
        @endif
      </div>
      <div class="vd-card__body">
        <div class="vd-meetings-banner">
          Meeting(S) upcoming! <strong>{{ $upcomingCount }}</strong>
        </div>
        <div class="vd-card__scroll">
        @forelse($upcomingMeetings as $meeting)
          @php $meetingClient = $meeting->client()->first(); @endphp
          @if($meetingClient)
          <div class="vd-list-row vd-list-row--appointment">
            <img class="vd-list-row__avatar" src="{{ asset('/storage/images/' . ($meetingClient->image ?: 'user.jpg')) }}" alt="" width="36" height="36" />
            <div class="vd-list-row__text">
              <p class="vd-list-row__name">{{ $meetingClient->first_name }}{{ $meetingClient->last_name ? ' & ' . $meetingClient->last_name : '' }}</p>
              <p class="vd-list-row__preview">{{ ucfirst($meeting->type) }}</p>
            </div>
            <p class="vd-list-row__meta">
              {{ $meeting->date ? \Carbon\Carbon::parse($meeting->date)->format('M j') : '' }}
              <span>at</span>
              {{ method_exists($meeting, 'readableTime') ? $meeting->readableTime() : '' }}
            </p>
            <div class="vd-list-row__actions">
              <button type="button" class="vd-btn-view vd-btn-view--coming-soon" data-vd-coming-soon="Opening an appointment from here">VIEW</button>
            </div>
          </div>
          @endif
        @empty
          <p class="vd-list-row__preview" style="padding:8px 0;">No upcoming appointments.</p>
        @endforelse
        </div>
        <div class="vd-card__footer">
          <button type="button" class="vd-btn-pill vd-btn-pill--purple" data-vd-coming-soon="The full appointments view">View All Appointments</button>
        </div>
      </div>
    </article>
  </section>

  <section class="vd-promo-row" aria-label="Promotions">
    <div class="vd-promo vd-promo--refer">
      <h3 class="vd-promo__title">
        <span class="vd-promo__title-line">Refer vendors &amp; clients and</span>
        <span class="vd-promo__title-line">improve your ranking</span>
      </h3>
      <div class="vd-promo__actions">
        <a href="{{ route('vendor.create.vendors') }}" class="vd-promo__btn vd-promo__btn--refer-vendor">Refer Vendors</a>
        <a href="{{ route('vendor.create.client') }}" class="vd-promo__btn vd-promo__btn--refer-client">Refer a Client</a>
      </div>
    </div>
    <article class="vd-promo vd-promo--find-couples">
      <div class="vd-promo-find__copy">
        @if(($data['newLeadsCount'] ?? 0) > 0)
          <p class="vd-promo-find__eyebrow">{{ $data['newLeadsCount'] }} new inquiries match your availability</p>
        @endif
        <h3 class="vd-promo-find__title">Find Couples Looking for Your Services</h3>
        <p class="vd-promo-find__desc">See couples actively searching for {{ Str::lower($typeLabel) }}s in {{ $location }}.</p>
      </div>
      <a href="{{ route('vendor.find.couples') }}" class="vd-promo__btn vd-promo__btn--find-couple">Find Couple</a>
    </article>
  </section>

  <section class="vd-duo" aria-label="Clients and network">
    <article class="vd-card">
      <div class="vd-card__head">
        <h2 class="vd-card__title">Current Clients</h2>
        <a href="{{ url('/vendor/client/list') }}" class="vd-card__link">View all →</a>
      </div>
      <div class="vd-card__body">
        @php
          $clientIcons = [
            asset('assets/img/vendor-home/new_icons/melissa&luis_1.png'),
            asset('assets/img/vendor-home/new_icons/lindsay_3.png'),
            asset('assets/img/vendor-home/new_icons/amour_2.png'),
          ];
        @endphp
        @forelse($data['clients'] as $index => $client)
        <div class="vd-list-row">
          <img class="vd-list-row__avatar" src="{{ $clientIcons[$index % count($clientIcons)] }}" alt="" />
          <div class="vd-list-row__text">
            <p class="vd-list-row__name">{{ $client->first_name }} {{ $client->last_name }}</p>
            <p class="vd-list-row__preview">Active client</p>
          </div>
          <div class="vd-list-row__actions">
            <a href="{{ url('/vendor/profile/'.$client->uuid) }}" class="vd-btn-view">VIEW</a>
            <button type="button" class="vd-btn-chat chat-window-btn"
              data-picture-url="{{ asset('/storage/images/'.$client->image) }}"
              data-name="{{ $client->first_name }} {{ $client->last_name }}"
              data-uuid="{{ $client->uuid }}"
              data-user-type="{{ $client->userType() }}"
              aria-label="Chat">💬</button>
          </div>
        </div>
        @empty
        <div class="vd-list-row">
          <img class="vd-list-row__avatar" src="{{ $icons }}/clients.png" alt="" />
          <div class="vd-list-row__text">
            <p class="vd-list-row__name">No clients yet</p>
            <p class="vd-list-row__preview">Connect with couples to grow your business</p>
          </div>
        </div>
        @endforelse
      </div>
    </article>

    <article class="vd-card">
      <div class="vd-card__head">
        <h2 class="vd-card__title">Vendor Network</h2>
        <a href="{{ url('/vendor/list') }}" class="vd-card__link">Explore →</a>
      </div>
      <div class="vd-card__body">
        <div class="vd-network-summary">
          <div class="vd-network-summary__avatars">
            @php $netIcons = ['vendor_network_1.png', 'vendor_network_2.png', 'vendor_network_3.png']; @endphp
            @foreach($data['connections'] as $i => $connection)
              @if($i < 3)
              <img src="{{ $icons }}/{{ $netIcons[$i % 3] }}" alt="{{ $connection->first_name }}" />
              @endif
            @endforeach
          </div>
          <p class="vd-network-summary__text">{{ $vendor->connections()->count() }} Vendor Connections</p>
        </div>
        @foreach($data['connections'] as $connection)
        <div class="vd-list-row">
          <img class="vd-list-row__avatar" src="{{ $icons }}/{{ $netIcons[($loop->index) % 3] }}" alt="" />
          <div class="vd-list-row__text">
            <p class="vd-list-row__name">{{ $connection->first_name }} {{ $connection->last_name }}</p>
            <p class="vd-list-row__preview">{{ ucfirst($connection->type ?? 'Vendor') }}</p>
          </div>
          <div class="vd-list-row__actions">
            <a href="{{ url('/vendor/profile/'.$connection->uuid) }}" class="vd-btn-view">VIEW</a>
            <button type="button" class="vd-btn-chat chat-window-btn"
              data-picture-url="{{ asset('/storage/images/'.$connection->image) }}"
              data-name="{{ $connection->first_name }} {{ $connection->last_name }}"
              data-uuid="{{ $connection->uuid }}"
              data-user-type="{{ $connection->userType() }}"
              aria-label="Chat">💬</button>
          </div>
        </div>
        @endforeach
      </div>
    </article>
  </section>

  <section class="vd-browse" id="community-vendors-section">
    <h2 class="vd-browse__title">Browse &amp; Connect with Other Vendors</h2>
    <div class="vd-browse__divider" aria-hidden="true"></div>
    <div class="vd-browse__grid">
      @php $browseVendors = \App\Models\Vendor::where('id', '!=', $vendor->id)->take(5)->get(); @endphp
      @foreach($browseVendors as $browseVendor)
        <x-vendor-browse-card :vendor="$browseVendor" />
      @endforeach
    </div>
    <p style="text-align:center;margin-top:16px;">
      <a href="{{ url('/vendor/search/vendors') }}" class="vd-promo__btn vd-promo__btn--purple" style="display:inline-flex;">View More Vendors</a>
    </p>
  </section>

  <section class="vd-tools" aria-label="Planning tools">
    <article class="vd-tool-card vd-tool-card--timeline">
      <div class="vd-tool-card__icon-wrap vd-tool-card__icon-wrap--purple" aria-hidden="true">
        <img class="vd-tool-card__icon" src="{{ $icons }}/page.png" alt="" />
      </div>
      <div class="vd-tool-card__body">
        <div class="vd-tool-card__labels">
          <span class="vd-tool-card__eyebrow vd-tool-card__eyebrow--purple">New Tool</span>
          <span class="vd-tool-card__badge">NOW AVAILABLE</span>
        </div>
        <h3 class="vd-tool-card__title">WIN Wedding Timeline Planner</h3>
        <p class="vd-tool-card__desc">
          <span class="vd-tool-card__desc-line">Build, share &amp; collaborate on day-of timelines with your clients and fellow vendors. Save</span>
          <span class="vd-tool-card__desc-line">progress between sessions.</span>
        </p>
        <a href="{{ route('vendor.timeline') }}" class="vd-tool-card__btn">Open Timeline Planner</a>
      </div>
    </article>
    <article class="vd-tool-card vd-tool-card--investment">
      <div class="vd-tool-card__icon-wrap vd-tool-card__icon-wrap--peach" aria-hidden="true">
        <img class="vd-tool-card__icon" src="{{ $icons }}/money.png" alt="" />
      </div>
      <div class="vd-tool-card__body">
        <div class="vd-tool-card__top-row">
          <span class="vd-tool-card__eyebrow vd-tool-card__eyebrow--orange">For Your Couples</span>
          <p class="vd-tool-card__desc vd-tool-card__desc--side">
            <span class="vd-tool-card__desc-line">Share this budgeting tool with your clients to help them plan their wedding</span>
            <span class="vd-tool-card__desc-line">investment wisely from day one.</span>
          </p>
        </div>
        <h3 class="vd-tool-card__title">WIN Wedding Investment Planner</h3>
        <a href="{{ url('/vendor/profile') }}" class="vd-tool-card__btn vd-tool-card__btn--orange">Share with Clients</a>
      </div>
    </article>
  </section>

  <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  @include('chat.window')
  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

<div id="vd-toast" class="vd-toast" role="status" aria-live="polite" hidden></div>

<script>
  document.getElementById('vd-announcement-close')?.addEventListener('click', function () {
    document.getElementById('vd-announcement')?.remove();
  });

  (function () {
    var toast = document.getElementById('vd-toast');
    var hideTimer;
    function showComingSoon(featureLabel) {
      if (!toast) return;
      toast.textContent = (featureLabel || 'This feature') + ' is currently being implemented. Thank you for your patience.';
      toast.hidden = false;
      clearTimeout(hideTimer);
      hideTimer = setTimeout(function () { toast.hidden = true; }, 4500);
    }
    document.querySelectorAll('[data-vd-coming-soon]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        showComingSoon(el.getAttribute('data-vd-coming-soon'));
      });
    });
  })();
</script>
</body>
</html>
