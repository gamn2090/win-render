<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Vendor Insights</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-insights.css', 'resources/css/vendor-dashboard.css', 'resources/js/app.js', 'resources/js/vendor-insights.js'])
  @include('components.fonts')
</head>
<body class="vi-page m-0 antialiased overflow-x-hidden">
@php
  $vendor = Auth::guard('vendor')->user();
  $rankingModel = $data['ranking'] ?? $vendor->vendor_ranking();
  $ranking = is_object($rankingModel) ? $rankingModel->toArray() : (array) $rankingModel;
  $placement = ($data['placement'] ?? 0) + 1;
  $typeLabel = $data['typeLabel'] ?? ($vendor->getType()?->type ?? 'Vendor');
  $location = $data['location'] ?? ($vendor->location ?: 'Your area');

  $pct = static fn (string $key): int => (int) round((float) ($ranking[$key] ?? 0));

  $metrics = [
    [
      'key' => 'client_community',
      'label' => 'Client Community',
      'legend' => 'Client Community',
      'color' => '#5E34C1',
      'nameColor' => '#5E34C1',
      'description' => 'The number of clients you invite to WIN and collaborate with.',
      'tip' => '3 new client invites & accounts established per month.',
      'weight' => '30% Total - Cycles Monthly.',
    ],
    [
      'key' => 'vendor_community',
      'label' => 'Vendor Community',
      'legend' => 'Vendor Community',
      'color' => '#627DF6',
      'nameColor' => '#627DF6',
      'description' => 'The growth of your vendor network, the number of vendors you invite to WIN resulting in increased community engagement & collaboration.',
      'tip' => '3 new vendor invites & accounts established. 1 Preferred Vendor added to your storefront from each category (21 total categories).',
      'weight' => '25% Total - Cycles Quarterly.',
    ],
    [
      'key' => 'endorsements',
      'label' => 'Endorsements',
      'legend' => 'Peer Endorsements',
      'color' => '#D2C6E4',
      'nameColor' => '#9B8BB8',
      'description' => 'The number of endorsements received from fellow vendors.',
      'tip' => '4 unique peer endorsements achieved.',
      'weight' => '15% Total - Cycles Weekly.',
    ],
    [
      'key' => 'badges',
      'label' => 'My Badges',
      'legend' => 'Badges',
      'color' => '#E6632B',
      'nameColor' => '#E6632B',
      'description' => 'Special achievements earned and displayed on your storefront.',
      'tip' => 'Achievement of 4 total badges (Community Builder, Early Adopter, Trending, and Fast Responder).',
      'weight' => '10% Total.',
    ],
    [
      'key' => 'reviews',
      'label' => 'Reviews',
      'legend' => 'Reviews',
      'color' => '#ED9A47',
      'nameColor' => '#ED9A47',
      'description' => 'The quantity and overall rankings of your reviews.',
      'tip' => 'Google reviews at 5 Stars.',
      'weight' => '20% Total.',
    ],
  ];

  $stats = [
    ['label' => 'Vendors Referred', 'value' => $data['vendorsReferred'] ?? $vendor->vendorReferrals()],
    ['label' => 'Clients Referred', 'value' => $data['clientsReferred'] ?? $vendor->clientReferrals()],
    ['label' => 'Storefront Views', 'value' => $data['storefrontViews'] ?? ($vendor->storefront_views ?? 0)],
    ['label' => 'Times Favorited', 'value' => $data['timesFavorited'] ?? $vendor->timesFavorited()],
  ];
@endphp

@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  @include('layouts.dashboard_topbar', ['role' => 'vendor'])

  <header class="vi-hero">
    <div class="vi-hero__content">
      <h1 class="vi-hero__title">Your Insights, Your Path to WIN!</h1>
      <p class="vi-hero__subtitle">You're on a journey to earn points and improve your ranking as a wedding vendor.</p>
      <p class="vi-hero__rank">#{{ $placement }} in {{ $typeLabel }} · {{ $location }}</p>
    </div>
  </header>

  <div class="vi-content">
    <div class="vi-stack">
      <section class="vi-card" aria-labelledby="vi-winfluence-title">
        <h2 id="vi-winfluence-title" class="vi-card__title">
          WINfluence Status: Unleash Your Expertise To WIN! You're On Your Way In 5 Categories!
        </h2>

        <div class="vi-gauges" role="list" aria-label="WINfluence category scores">
          @foreach($metrics as $metric)
          @php $value = $pct($metric['key']); @endphp
          <div class="vi-gauge" role="listitem">
            <p class="vi-gauge__label">{{ $metric['label'] }}</p>
            <div class="vi-gauge__ring" aria-label="{{ $metric['label'] }}: {{ $value }} percent">
              <svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle class="vi-gauge__ring-track" cx="18" cy="18" r="16"></circle>
                <circle
                  class="vi-gauge__ring-fill"
                  cx="18"
                  cy="18"
                  r="16"
                  style="stroke: {{ $metric['color'] }}; stroke-dashoffset: {{ max(0, 100 - $value) }};"
                ></circle>
              </svg>
              <span class="vi-gauge__value">{{ $value }}%</span>
            </div>
          </div>
          @endforeach
        </div>

        <div class="vi-bars" aria-label="Category progress bars">
          @foreach($metrics as $metric)
          @php $value = $pct($metric['key']); @endphp
          <div class="vi-bar-row">
            <div class="vi-bar-row__track" role="progressbar" aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $metric['label'] }}">
              <div class="vi-bar-row__fill" style="width: {{ $value }}%; background: {{ $metric['color'] }};"></div>
            </div>
          </div>
          @endforeach
          <div class="vi-bars__scale">
            <span>0%</span>
            <span>100%</span>
          </div>
        </div>

        <div class="vi-legend" aria-label="Category legend">
          @foreach($metrics as $metric)
          <span class="vi-legend__item">
            <span class="vi-legend__dot" style="background: {{ $metric['color'] }};"></span>
            {{ $metric['legend'] }}
          </span>
          @endforeach
        </div>
      </section>

      <section class="vi-card vi-how vi-how--open" aria-labelledby="vi-how-title">
        <button
          type="button"
          class="vi-how__toggle"
          data-vi-accordion-toggle
          aria-expanded="true"
          aria-controls="vi-how-panel"
        >
          <span id="vi-how-title" class="vi-how__toggle-title">How It Works:</span>
          <svg class="vi-how__chevron" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m6 9 6 6 6-6"/>
          </svg>
        </button>

        <div id="vi-how-panel" class="vi-how__panel" data-vi-accordion-panel>
          <ul class="vi-how__list">
            @foreach($metrics as $metric)
            <li class="vi-how__item">
              <div class="vi-how__item-head">
                <span class="vi-how__item-bar" style="background: {{ $metric['color'] }};"></span>
                <p class="vi-how__item-name" style="color: {{ $metric['nameColor'] }};">{{ strtoupper($metric['legend']) }}:</p>
              </div>
              <p class="vi-how__item-desc">{{ $metric['description'] }}</p>
              <p class="vi-how__tip">
                <span class="vi-how__tip-label">PERFECT SCORE PRO TIP:</span>
                <i class="fa-solid fa-check vi-how__check" aria-hidden="true"></i>
                {{ $metric['tip'] }}
              </p>
              <p class="vi-how__weight">
                <span class="vi-how__weight-label">Overall Score Weight &amp; Cycle:</span>
                <i class="fa-solid fa-award vi-how__award" style="color: {{ $metric['color'] }};" aria-hidden="true"></i>
                {{ $metric['weight'] }}
              </p>
            </li>
            @endforeach
          </ul>
        </div>
      </section>

      <section class="vi-stats" aria-label="Referral and engagement metrics">
        @foreach($stats as $stat)
        <article class="vi-stat-card">
          <p class="vi-stat-card__label">{{ $stat['label'] }}</p>
          <p class="vi-stat-card__value">{{ $stat['value'] }}</p>
        </article>
        @endforeach
      </section>
    </div>
  </div>

  <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
