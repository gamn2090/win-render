@php
  $role = $role ?? 'couple';
  $isVendor = $role === 'vendor';
  $inboxUrl = $isVendor ? route('vendor.inbox') : route('client.inbox');
  $settingsUrl = $isVendor ? route('vendor.account.settings') : route('user.account.settings');
  $unread = $unreadCount ?? null;
@endphp

<div class="vd-topbar" aria-label="Page tools">
  <form class="vd-search-widget" action="{{ route('search.results') }}" method="GET" role="search" autocomplete="off">
    <button type="button" class="vd-topbar__btn vd-search-toggle" aria-label="Search" aria-expanded="false">🔍</button>
    <input type="search" name="q" class="vd-search-widget__input" placeholder="Search…" autocomplete="off" aria-label="Search this site" />
    <div class="vd-search-suggestions" hidden></div>
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
    (function () {
      var debounceTimer = null;
      var activeController = null;

      function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str == null ? '' : String(str);
        return div.innerHTML;
      }

      function closeSuggestions(panel) {
        if (!panel) return;
        panel.hidden = true;
        panel.innerHTML = '';
      }

      function renderSuggestions(panel, items) {
        if (!items || items.length === 0) {
          panel.innerHTML = '<p class="vd-search-suggestions__empty">No results found.</p>';
          panel.hidden = false;
          return;
        }

        panel.innerHTML = items.map(function (item) {
          if (item.is_action) {
            var variantClass = item.variant === 'danger' ? 'vd-danger-btn' : 'vd-edit-save-btn';
            return '<a class="vd-search-suggestion vd-search-suggestion--action" href="' + escapeHtml(item.url) + '">' +
              '<span class="vd-search-suggestion__source">' + escapeHtml(item.source) + '</span>' +
              (item.snippet ? '<span class="vd-search-suggestion__snippet">' + escapeHtml(item.snippet) + '</span>' : '') +
              '<span class="' + variantClass + ' vd-search-suggestion__btn">' + escapeHtml(item.button_label) + '</span>' +
              '</a>';
          }
          return '<a class="vd-search-suggestion" href="' + escapeHtml(item.url) + '">' +
            '<span class="vd-search-suggestion__source">' + escapeHtml(item.source) + '</span>' +
            '<span class="vd-search-suggestion__title">' + escapeHtml(item.title) + '</span>' +
            (item.snippet ? '<span class="vd-search-suggestion__snippet">' + escapeHtml(item.snippet) + '</span>' : '') +
            '</a>';
        }).join('');
        panel.hidden = false;
      }

      function fetchSuggestions(widget, query) {
        var panel = widget.querySelector('.vd-search-suggestions');
        if (!panel) return;

        if (query.trim().length < 2) {
          closeSuggestions(panel);
          return;
        }

        if (activeController) {
          activeController.abort();
        }
        activeController = new AbortController();

        fetch(widget.getAttribute('action') + '?q=' + encodeURIComponent(query), {
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
          signal: activeController.signal,
        })
          .then(function (r) { return r.json(); })
          .then(function (data) { renderSuggestions(panel, data.results || []); })
          .catch(function (err) {
            if (err.name !== 'AbortError') closeSuggestions(panel);
          });
      }

      document.addEventListener('input', function (e) {
        var input = e.target.closest('.vd-search-widget__input');
        if (!input) return;
        var widget = input.closest('.vd-search-widget');
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
          fetchSuggestions(widget, input.value);
        }, 200);
      });

      document.addEventListener('click', function (e) {
        var toggle = e.target.closest('.vd-search-toggle');
        if (toggle) {
          var widget = toggle.closest('.vd-search-widget');
          var input = widget.querySelector('.vd-search-widget__input');
          var isOpen = widget.classList.contains('is-open');
          if (isOpen && input.value.trim() === '') {
            widget.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            closeSuggestions(widget.querySelector('.vd-search-suggestions'));
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
          closeSuggestions(openWidget.querySelector('.vd-search-suggestions'));
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
          closeSuggestions(openWidget.querySelector('.vd-search-suggestions'));
        }
      });
    })();
  </script>
@endonce
