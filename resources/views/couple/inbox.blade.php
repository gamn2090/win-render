<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Messages</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();
  $unreadConversations = $user->unreadConversationsCount();
@endphp

@include('layouts.couple_sidebar', ['page' => 'inbox'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple', 'unreadCount' => $unreadConversations])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">Messages</h1>
      <p class="vd-page-header__sub">All your messages in one place</p>
    </header>

    <div class="vd-results-header">
      <div>
        <h2 class="vd-results-header__title">{{ $unreadConversations }} New {{ Str::plural('Message', $unreadConversations) }}</h2>
      </div>
    </div>

    <div class="vd-card">
      <div class="vd-msg-table__head">
        <span>Name</span>
        <span>Latest message</span>
        <span>Action</span>
      </div>
      <div class="vd-msg-list">
        @forelse($conversations as $convo)
          @php
            $participant = collect($convo->conversation->participants)
              ->first(fn ($p) => $p->messageable_type === \App\Models\Vendor::class);
          @endphp
          @continue(!$participant)
          @php
            $vendor = $participant->messageable;
            $isUnread = Chat::conversation($convo->conversation)->setParticipant($user)->unreadCount() > 0;
            $lastMessage = $convo->conversation->last_message;
          @endphp
          <a href="{{ route('get.client.conversation', $convo->conversation->id) }}" class="vd-msg-row {{ $isUnread ? 'vd-msg-row--unread' : '' }}">
            <div class="vd-msg-row__who">
              <x-avatar :model="$vendor" class="vd-msg-row__avatar" />
              <div class="vd-msg-row__who-text">
                <p class="vd-msg-row__name">{{ trim($vendor->first_name . ' ' . $vendor->last_name) }}</p>
                <p class="vd-msg-row__business">{{ $vendor->business_name }}</p>
              </div>
            </div>
            <div class="vd-msg-row__latest">
              <span class="vd-msg-row__time">
                {{ $lastMessage?->created_at ? $lastMessage->created_at->format('m/d/Y \a\t g:iA') : '' }}
              </span>
              <span class="vd-msg-row__preview-wrap">
                @if($isUnread)
                  <span class="vd-msg-row__dot"></span>
                @endif
                <span class="vd-msg-row__preview">{{ $lastMessage->body ?? 'Start a conversation!' }}</span>
              </span>
            </div>
            <span class="vd-msg-row__action vd-btn-pill vd-btn-pill--purple" style="padding:6px 24px;">View</span>
          </a>
        @empty
          <p style="padding:40px 24px;text-align:center;color:#7a7a7a;">You don't have any conversations yet.</p>
        @endforelse
      </div>
    </div>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
