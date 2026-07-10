<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Favorites</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();
@endphp

@include('layouts.couple_sidebar', ['page' => 'favorites'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple'])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">Favorite Vendors</h1>
      <p class="vd-page-header__sub">Your saved vendors, all in one place</p>
    </header>

    <div class="vd-results-header">
      <div>
        <h2 class="vd-results-header__title">Your top picks, ready when you are</h2>
        <p class="vd-results-header__meta">Showing <span class="vd-results-header__count">{{ $vendors->total() }}</span> vendors you love</p>
      </div>

      <form method="GET" action="{{ route('client.favorites') }}" class="vd-filter-bar">
        <div class="hs-dropdown [--auto-close:inside] relative inline-flex">
          <button type="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white hover:bg-gray-50" aria-haspopup="menu" aria-expanded="false">
            Vendor Type
            <svg class="hs-dropdown-open:rotate-180 size-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
          <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden bg-white shadow-md rounded-lg mt-2 z-[100] max-h-72 overflow-y-auto" role="menu">
            <div class="p-1 space-y-0.5">
              @foreach($vendor_types as $type)
                <div class="flex items-center gap-x-2 py-2 px-3 rounded-lg hover:bg-gray-100 hover:cursor-pointer">
                  <input id="fav-filter-type-{{ $type->id }}" name="type[]" type="checkbox" value="{{ $type->id }}" @checked(in_array($type->id, $selected_type_ids ?? [])) class="shrink-0 rounded-sm text-win-purple focus:ring-win-purple checked:border-win-purple">
                  <label for="fav-filter-type-{{ $type->id }}" class="grow cursor-pointer">
                    <span class="block text-sm">{{ $type->type }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <a href="{{ route('client.favorites') }}" class="vd-filter-bar__clear">Clear Filters</a>
        <button type="submit" class="vd-filter-bar__apply">Apply Filters</button>
      </form>
    </div>

    <div id="vd-favorites-grid" class="vd-favorites-grid">
      @include('couple.partials.vendor-cards', ['vendors' => $vendors])
    </div>

    <div id="vd-favorites-sentinel" class="vd-favorites-sentinel"></div>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

@include('couple.partials.mark-booked-modal')
@include('couple.partials.schedule-consultation-modal')

<script>
  (function () {
    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('click', function (e) {
      var favBtn = e.target.closest('.favorite-toggle-btn');
      if (favBtn) {
        var card = favBtn.closest('.vd-vendor-card');
        favBtn.disabled = true;
        fetch('{{ route('toggle.favorite') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify({ vendor_uuid: favBtn.dataset.vendorId, active: false }),
        }).then(function () {
          if (card) {
            card.style.transition = 'opacity .2s ease';
            card.style.opacity = '0';
            setTimeout(function () { card.remove(); }, 200);
          }
        });
        return;
      }

      var bookBtn = e.target.closest('.mark-booked-btn');
      if (bookBtn) {
        pendingBookVendorUuid = bookBtn.dataset.vendorUuid;
        openModal(markBookedModal);
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
        closeModal(closeTrigger.closest('.vd-modal-overlay'));
        return;
      }

      if (e.target.classList && e.target.classList.contains('vd-modal-overlay')) {
        closeModal(e.target);
      }
    });

    // ——— Modal helpers ———
    function openModal(modal) {
      modal.classList.add('is-open');
    }

    function closeModal(modal) {
      if (modal) modal.classList.remove('is-open');
    }

    // ——— Mark as Booked modal ———
    var markBookedModal = document.getElementById('vd-mark-booked-modal');
    var markBookedConfirmBtn = document.getElementById('vd-mark-booked-confirm');
    var pendingBookVendorUuid = null;

    markBookedConfirmBtn.addEventListener('click', function () {
      if (!pendingBookVendorUuid) return;
      markBookedConfirmBtn.disabled = true;
      fetch('{{ route('user.book.vendor') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ vendor_uuid: pendingBookVendorUuid }),
      }).then(function () {
        window.location.reload();
      }).finally(function () {
        markBookedConfirmBtn.disabled = false;
      });
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
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ vendor_id: pendingScheduleVendorId, date: dateStr }),
      }).then(function () {
        window.location.reload();
      }).finally(function () {
        scheduleConfirmBtn.disabled = false;
      });
    });

    // Infinite scroll
    var grid = document.getElementById('vd-favorites-grid');
    var sentinel = document.getElementById('vd-favorites-sentinel');
    var loading = false;
    var nextPage = {{ $vendors->currentPage() + 1 }};
    var hasMore = {{ $vendors->hasMorePages() ? 'true' : 'false' }};
    var typeParams = @json($selected_type_ids ?? []);

    function buildUrl(page) {
      var params = new URLSearchParams();
      typeParams.forEach(function (t) { params.append('type[]', t); });
      params.append('page', page);
      return '{{ route('client.favorites') }}?' + params.toString();
    }

    function loadMore() {
      if (loading || !hasMore) return;
      loading = true;
      sentinel.textContent = 'Loading more…';
      fetch(buildUrl(nextPage), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          grid.insertAdjacentHTML('beforeend', data.html);
          hasMore = data.has_more;
          nextPage = data.next_page;
          sentinel.textContent = hasMore ? '' : (grid.children.length ? "You've reached the end." : '');
          loading = false;
        })
        .catch(function () {
          loading = false;
          sentinel.textContent = 'Could not load more vendors.';
        });
    }

    if ('IntersectionObserver' in window && sentinel) {
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) loadMore();
        });
      });
      observer.observe(sentinel);
    }
  })();
</script>
</body>
</html>
