<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: My Appointments</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@if($isVendor)
  @include('layouts.vendor_navigation')
@else
  @include('layouts.couple_sidebar', ['page' => 'appointments'])
@endif

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => $isVendor ? 'vendor' : 'couple', 'unreadCount' => $unreadCount])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">My Appointments</h1>
      <p class="vd-page-header__sub">
        {{ $isVendor ? 'All your scheduled consultations and wedding bookings' : 'All your scheduled consultations and wedding appointments' }}
      </p>
    </header>

    <article class="vd-card" style="margin-top:24px;">
      <div class="vd-card__body">
        @forelse($data['appointments'] as $appointment)
          @php
            $meetingWith = $appointment->otherParticipant($viewer)->first();
          @endphp
          @if($meetingWith)
            @php
              $name = $meetingWith instanceof \App\Models\Vendor
                ? ($meetingWith->business_name ?: trim($meetingWith->first_name . ' ' . $meetingWith->last_name))
                : trim($meetingWith->first_name . ' & ' . ($meetingWith->fiance_first_name ?? ''));
            @endphp
            <div class="vd-list-row">
              <x-avatar :model="$meetingWith" class="vd-list-row__avatar" />
              <div class="vd-list-row__text">
                <p class="vd-list-row__name">{{ $name }}</p>
                <p class="vd-list-row__preview">{{ ucfirst($appointment->type) }} &middot; {{ $appointment->readableTime() }}</p>
              </div>
              <div class="vd-list-row__actions">
                <a href="{{ $meetingWith->profileURL() }}" class="vd-btn-view">VIEW</a>
              </div>
            </div>
          @endif
        @empty
          <p class="vd-list-row__preview" style="padding:24px 0;text-align:center;">No appointments yet.</p>
        @endforelse
      </div>
    </article>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>
</main>
</body>
</html>
