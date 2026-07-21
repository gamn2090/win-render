<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: My Calendar</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css', 'resources/css/vendor-calendar.css', 'resources/js/app.js', 'resources/js/vendor-calendar.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  @include('layouts.dashboard_topbar', ['role' => 'vendor'])

  <header class="vd-page-header">
    <h1 class="vd-page-header__title">My Calendar</h1>
  </header>

  <div class="vcal-content">
    <div class="vcal-toolbar">
      <div class="vcal-toolbar__nav">
        <a href="{{ route('vendor.calendar', ['view' => $view, 'date' => $prevDate]) }}" class="vcal-nav-btn" aria-label="Previous">&lsaquo;</a>
        <a href="{{ route('vendor.calendar', ['view' => $view, 'date' => $todayDate]) }}" class="vcal-nav-btn vcal-nav-btn--today">Today</a>
        <a href="{{ route('vendor.calendar', ['view' => $view, 'date' => $nextDate]) }}" class="vcal-nav-btn" aria-label="Next">&rsaquo;</a>
        <p class="vcal-toolbar__label">
          @if($view === 'month')
            {{ $date->format('F Y') }}
          @elseif($view === 'week')
            {{ $rangeStart->format('M j') }} &ndash; {{ $rangeEnd->format('M j, Y') }}
          @else
            {{ $date->format('l, F j, Y') }}
          @endif
        </p>
      </div>
      <div class="vcal-toolbar__views">
        <a href="{{ route('vendor.calendar', ['view' => 'month', 'date' => $date->toDateString()]) }}" class="vcal-view-btn {{ $view === 'month' ? 'vcal-view-btn--active' : '' }}">Month</a>
        <a href="{{ route('vendor.calendar', ['view' => 'week', 'date' => $date->toDateString()]) }}" class="vcal-view-btn {{ $view === 'week' ? 'vcal-view-btn--active' : '' }}">Week</a>
        <a href="{{ route('vendor.calendar', ['view' => 'day', 'date' => $date->toDateString()]) }}" class="vcal-view-btn {{ $view === 'day' ? 'vcal-view-btn--active' : '' }}">Day</a>
      </div>
      <button type="button" class="vcal-export-btn" id="vcal-export-btn">Export PDF</button>
      <button type="button" class="vcal-add-btn" id="vcal-add-btn">+ Add Event</button>
    </div>

    @if($bookedCouples->isEmpty())
      <div class="vcal-empty-notice">
        You don't have any booked couples yet — once a couple books you, they'll show up here so you can schedule events with them.
      </div>
    @endif

    @if($view === 'month')
      @php
        $weeks = [];
        $cursor = $rangeStart->copy();
        while ($cursor->lte($rangeEnd)) {
          $week = [];
          for ($i = 0; $i < 7; $i++) {
            $week[] = $cursor->copy();
            $cursor->addDay();
          }
          $weeks[] = $week;
        }
        $eventsByDate = $events->groupBy(fn ($e) => \Carbon\Carbon::parse($e['startsAt'])->toDateString());
      @endphp
      <div class="vcal-month">
        <div class="vcal-month__weekdays">
          @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $wd)
            <div class="vcal-month__weekday">{{ $wd }}</div>
          @endforeach
        </div>
        @foreach($weeks as $week)
          <div class="vcal-month__row">
            @foreach($week as $day)
              @php $dayEvents = $eventsByDate->get($day->toDateString(), collect()); @endphp
              <div class="vcal-month__cell {{ $day->month !== $date->month ? 'vcal-month__cell--muted' : '' }} {{ $day->isToday() ? 'vcal-month__cell--today' : '' }}"
                   data-vcal-cell
                   data-date="{{ $day->toDateString() }}">
                <span class="vcal-month__daynum">{{ $day->day }}</span>
                <div class="vcal-month__events">
                  @foreach($dayEvents as $ev)
                    @php $pillText = \Carbon\Carbon::parse($ev['startsAt'])->format('g:ia') . ' · ' . $ev['coupleName']; @endphp
                    <button type="button" class="vcal-event-pill" data-vcal-event='@json($ev)' title="{{ $pillText }}">
                      {{ $pillText }}
                    </button>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    @else
      @php
        $gridStartHour = 6;
        $gridEndHour = 23;
        $hourHeight = 60;
        $days = $view === 'week'
          ? collect(range(0, 6))->map(fn ($i) => $rangeStart->copy()->addDays($i))
          : collect([$date->copy()]);
        $eventsByDate = $events->groupBy(fn ($e) => \Carbon\Carbon::parse($e['startsAt'])->toDateString());
      @endphp
      <div class="vcal-grid vcal-grid--{{ $view }}">
        <div class="vcal-grid__hours">
          <div class="vcal-grid__corner"></div>
          @for($h = $gridStartHour; $h <= $gridEndHour; $h++)
            <div class="vcal-grid__hourlabel" style="height:{{ $hourHeight }}px">
              {{ \Carbon\Carbon::createFromTime($h, 0)->format('g A') }}
            </div>
          @endfor
        </div>
        @foreach($days as $day)
          @php $dayEvents = $eventsByDate->get($day->toDateString(), collect()); @endphp
          <div class="vcal-grid__day">
            <div class="vcal-grid__daylabel {{ $day->isToday() ? 'vcal-grid__daylabel--today' : '' }}">
              {{ $day->format('D j') }}
            </div>
            <div class="vcal-grid__body" style="height:{{ ($gridEndHour - $gridStartHour + 1) * $hourHeight }}px" data-vcal-daybody data-date="{{ $day->toDateString() }}">
              @for($h = $gridStartHour; $h <= $gridEndHour; $h++)
                <div class="vcal-grid__slot" style="height:{{ $hourHeight }}px" data-vcal-slot data-datetime="{{ $day->toDateString() }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00"></div>
              @endfor

              @foreach($dayEvents as $ev)
                @php
                  $start = \Carbon\Carbon::parse($ev['startsAt']);
                  $end = \Carbon\Carbon::parse($ev['endsAt']);
                  $gridStartMin = $gridStartHour * 60;
                  $startMin = max($start->hour * 60 + $start->minute, $gridStartMin);
                  $endMin = min($end->hour * 60 + $end->minute, ($gridEndHour + 1) * 60);
                  $top = ($startMin - $gridStartMin) / 60 * $hourHeight;
                  $height = max(($endMin - $startMin) / 60 * $hourHeight, 18);
                @endphp
                <button type="button" class="vcal-event-block" data-vcal-event='@json($ev)' style="top:{{ $top }}px; height:{{ $height }}px">
                  <span class="vcal-event-block__time">{{ $start->format('g:ia') }} &ndash; {{ $end->format('g:ia') }}</span>
                  <span class="vcal-event-block__name">{{ $ev['coupleName'] }}</span>
                </button>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
</main>

<div class="vcal-modal" id="vcal-modal" hidden>
  <div class="vcal-modal__backdrop" data-vcal-close></div>
  <div class="vcal-modal__panel" role="dialog" aria-modal="true" aria-labelledby="vcal-modal-title">
    <div class="vcal-modal__header">
      <h2 class="vcal-modal__title" id="vcal-modal-title">Add Event</h2>
      <button type="button" class="vcal-modal__close" data-vcal-close aria-label="Close">&times;</button>
    </div>
    <form id="vcal-form">
      <input type="hidden" id="vcal-event-id" value="" />
      <div class="vcal-field">
        <label for="vcal-client-select" class="vcal-field__label">Couple</label>
        <select id="vcal-client-select" class="vcal-field__input" required>
          <option value="" disabled selected>Select a booked couple&hellip;</option>
          @foreach($bookedCouples as $couple)
            @php
              $partnerOne = trim($couple->first_name . ' ' . ($couple->last_name ?? ''));
              $partnerTwo = trim(($couple->fiance_first_name ?? '') . ' ' . ($couple->fiance_last_name ?? ''));
              $label = $partnerTwo !== '' ? $partnerOne . ' ♥ ' . $partnerTwo : $partnerOne;
            @endphp
            <option value="{{ $couple->id }}">{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="vcal-field-row">
        <div class="vcal-field">
          <label for="vcal-date" class="vcal-field__label">Date</label>
          <input type="date" id="vcal-date" class="vcal-field__input" required />
        </div>
        <div class="vcal-field">
          <label for="vcal-start-time" class="vcal-field__label">Start</label>
          <input type="time" id="vcal-start-time" class="vcal-field__input" required />
        </div>
        <div class="vcal-field">
          <label for="vcal-end-time" class="vcal-field__label">End</label>
          <input type="time" id="vcal-end-time" class="vcal-field__input" required />
        </div>
      </div>
      <div class="vcal-field">
        <label for="vcal-notes" class="vcal-field__label">Notes</label>
        <textarea id="vcal-notes" class="vcal-field__input vcal-field__textarea" rows="3" placeholder="Any additional details for this event&hellip;" maxlength="5000"></textarea>
      </div>
      <p class="vcal-form-error" id="vcal-form-error" hidden></p>
      <div class="vcal-modal__actions">
        <button type="button" class="vcal-btn vcal-btn--danger" id="vcal-delete-btn" hidden>Delete</button>
        <div class="vcal-modal__actions-right">
          <button type="button" class="vcal-btn vcal-btn--ghost" data-vcal-close>Cancel</button>
          <button type="submit" class="vcal-btn vcal-btn--primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

@php
  $vendorAccount = \Illuminate\Support\Facades\Auth::guard('vendor')->user();
  $vendorDisplayName = trim($vendorAccount->business_name ?: trim($vendorAccount->first_name . ' ' . $vendorAccount->last_name));
  $rangeLabel = match($view) {
    'month' => $date->format('F Y'),
    'week' => $rangeStart->format('M j') . ' – ' . $rangeEnd->format('M j, Y'),
    default => $date->format('l, F j, Y'),
  };
@endphp
<script>
  window.__WIN_VENDOR_CALENDAR__ = {
    storeUrl: @json(route('vendor.calendar.events.store')),
    updateUrlBase: @json(route('vendor.calendar.events.update', ['event' => '__ID__'])),
    destroyUrlBase: @json(route('vendor.calendar.events.destroy', ['event' => '__ID__'])),
    csrfToken: @json(csrf_token()),
    reloadUrl: @json(route('vendor.calendar', ['view' => $view, 'date' => $date->toDateString()])),
    vendorName: @json($vendorDisplayName),
    rangeLabel: @json($rangeLabel),
  };
</script>
</body>
</html>
