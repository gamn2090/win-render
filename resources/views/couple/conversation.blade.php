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
  $vendorType = $vendor->getType();
  $favorited = $user->hasFavorite($vendor->id);
  $senderName = trim(($user->first_name ?? '') . ' & ' . ($user->fiance_first_name ?? ''));
  $vendorName = trim($vendor->first_name . ' ' . $vendor->last_name);
@endphp

@include('layouts.couple_sidebar', ['page' => 'inbox'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple', 'unreadCount' => $unreadConversations])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">Messages</h1>
      <p class="vd-page-header__sub">All your vendor messages in one place</p>
    </header>

    <div class="vd-chat-layout" style="margin-top:24px;">
      <aside class="vd-chat-profile">
        <img class="vd-chat-profile__avatar" src="{{ \App\Support\ProfileImageStorage::url($vendor->image) }}" alt="" />
        <div>
          <h2 class="vd-chat-profile__name">{{ $vendorName }}</h2>
          <div class="vd-chat-profile__meta">
            @if($vendorType)
              <span class="vd-vendor-card__type">
                <img src="{{ asset($vendorType->icon) }}" alt="" class="vd-vendor-card__type-icon" width="18" height="18" />
                <span class="vd-vendor-card__type-label">{{ $vendorType->type }}</span>
              </span>
            @endif
            <span class="vd-vendor-card__rating-num">★ {{ number_format($vendor->googleRating(), 1) }}</span>
          </div>
          <p class="vd-chat-profile__business">{{ $vendor->business_name }}</p>
        </div>
        <div class="vd-chat-profile__actions">
          <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-chat-profile__btn">Visit Storefront</a>
          <button
            type="button"
            id="vd-favorite-btn"
            class="vd-chat-profile__btn favorite-toggle-btn"
            data-vendor-id="{{ $vendor->uuid }}"
            data-favorited="{{ $favorited ? '1' : '0' }}"
          >{{ $favorited ? 'Favorited ♥' : 'Add as Favorite' }}</button>
          <a href="{{ route('profile.vendor', $vendor->uuid) }}" class="vd-chat-profile__btn">Schedule Consultation</a>
        </div>
      </aside>

      <section class="vd-chat">
        <div id="vd-chat-messages" class="vd-chat__messages">
          <p class="vd-chat__empty">Loading messages…</p>
        </div>
        <form id="vd-chat-form" class="vd-chat__composer">
          <input id="vd-chat-input" type="text" class="vd-chat__input" placeholder="Write a message to {{ $vendorName }}…" autocomplete="off" />
          <button type="submit" class="vd-chat__send" aria-label="Send">➤</button>
        </form>
      </section>
    </div>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

<script>
  (function () {
    var convoId = {{ (int) $chat_id }};
    var messagesEl = document.getElementById('vd-chat-messages');
    var form = document.getElementById('vd-chat-form');
    var input = document.getElementById('vd-chat-input');
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    var mySenderName = @json($senderName);
    var vendorSenderName = @json($vendorName);

    function escapeHtml(str) {
      var div = document.createElement('div');
      div.textContent = str || '';
      return div.innerHTML;
    }

    function formatTime(dateStr) {
      var d = new Date(dateStr);
      if (isNaN(d.getTime())) return '';
      return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    }

    function sameDay(a, b) {
      return a.toDateString() === b.toDateString();
    }

    function formatDayLabel(dateStr) {
      var d = new Date(dateStr);
      if (isNaN(d.getTime())) return '';
      var today = new Date();
      var yesterday = new Date();
      yesterday.setDate(today.getDate() - 1);
      if (sameDay(d, today)) return 'Today, ' + d.toLocaleDateString([], { month: 'long', day: 'numeric' });
      if (sameDay(d, yesterday)) return 'Yesterday, ' + d.toLocaleDateString([], { month: 'long', day: 'numeric' });
      return d.toLocaleDateString([], { month: 'long', day: 'numeric', year: 'numeric' });
    }

    function initials(name) {
      return (name || '').split(' ').filter(Boolean).map(function (p) { return p[0]; }).join('').slice(0, 2).toUpperCase();
    }

    function render(messages) {
      messagesEl.innerHTML = '';
      if (!messages.length) {
        messagesEl.innerHTML = '<p class="vd-chat__empty">Start the conversation!</p>';
        return;
      }
      var lastDay = null;
      messages.forEach(function (m) {
        var day = formatDayLabel(m.created_at);
        if (day !== lastDay) {
          var divider = document.createElement('div');
          divider.className = 'vd-chat__date-divider';
          divider.innerHTML = '<span>' + escapeHtml(day) + '</span>';
          messagesEl.appendChild(divider);
          lastDay = day;
        }
        var isMine = !!m.is_sender;
        var row = document.createElement('div');
        row.className = 'vd-chat__row ' + (isMine ? 'vd-chat__row--mine' : 'vd-chat__row--vendor');
        row.innerHTML =
          '<div class="vd-chat__bubble">' + escapeHtml(m.body) + '</div>' +
          '<div class="vd-chat__meta">' +
            '<span class="vd-chat__avatar-sm">' + initials(isMine ? mySenderName : vendorSenderName) + '</span>' +
            '<span class="vd-chat__time">' + formatTime(m.created_at) + '</span>' +
          '</div>';
        messagesEl.appendChild(row);
      });
      messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function load() {
      fetch('/client/messages/' + convoId, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (d) { render((d.messages && d.messages.data) || []); })
        .catch(function () {
          messagesEl.innerHTML = '<p class="vd-chat__empty">Could not load messages.</p>';
        });
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var value = input.value.trim();
      if (!value) return;
      input.value = '';
      fetch('/client/send/message', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: new URLSearchParams({ message: value, conversation: String(convoId) }),
      }).then(load);
    });

    var favBtn = document.getElementById('vd-favorite-btn');
    favBtn.addEventListener('click', function () {
      var active = favBtn.dataset.favorited === '1';
      var next = !active;
      favBtn.disabled = true;
      favBtn.dataset.favorited = next ? '1' : '0';
      favBtn.textContent = next ? 'Favorited ♥' : 'Add as Favorite';
      fetch('/toggle/favorite', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ vendor_uuid: favBtn.dataset.vendorId, active: next }),
      }).finally(function () {
        favBtn.disabled = false;
      });
    });

    load();
  })();
</script>
</body>
</html>
