@php
  $role = $role ?? 'couple';
  $isVendor = $role === 'vendor';
  $inboxUrl = $isVendor ? route('vendor.inbox') : route('client.inbox');
  $settingsUrl = $isVendor ? route('vendor.account.settings') : route('user.account.settings');
  $unread = $unreadCount ?? null;
@endphp

<div class="vd-topbar" aria-label="Page tools">
  <form class="vd-search-widget" action="{{ route('search.results') }}" method="GET" role="search">
    <button type="button" class="vd-topbar__btn vd-search-toggle" aria-label="Search" aria-expanded="false">🔍</button>
    <input type="search" name="q" class="vd-search-widget__input" placeholder="Search…" autocomplete="off" aria-label="Search this site" />
  </form>

  <a href="{{ $inboxUrl }}" class="vd-topbar__btn" aria-label="Notifications">
    🔔
    @if($unread)
      <span class="vd-topbar__dot"></span>
    @endif
  </a>

  <a href="{{ $settingsUrl }}" class="vd-topbar__btn" aria-label="Settings">⚙️</a>
</div>

@once
  <script>
    document.addEventListener('click', function (e) {
      var toggle = e.target.closest('.vd-search-toggle');
      if (toggle) {
        var widget = toggle.closest('.vd-search-widget');
        var input = widget.querySelector('.vd-search-widget__input');
        var isOpen = widget.classList.contains('is-open');
        if (isOpen && input.value.trim() === '') {
          widget.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
        } else {
          widget.classList.add('is-open');
          toggle.setAttribute('aria-expanded', 'true');
          input.focus();
        }
        return;
      }

      var openWidget = document.querySelector('.vd-search-widget.is-open');
      if (openWidget && !openWidget.contains(e.target)) {
        var openInput = openWidget.querySelector('.vd-search-widget__input');
        if (openInput.value.trim() === '') {
          openWidget.classList.remove('is-open');
          openWidget.querySelector('.vd-search-toggle').setAttribute('aria-expanded', 'false');
        }
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      var openWidget = document.querySelector('.vd-search-widget.is-open');
      if (openWidget) {
        openWidget.classList.remove('is-open');
        openWidget.querySelector('.vd-search-toggle').setAttribute('aria-expanded', 'false');
        openWidget.querySelector('.vd-search-widget__input').value = '';
      }
    });
  </script>
@endonce
