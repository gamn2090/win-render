<div id="vd-schedule-modal" class="vd-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="vd-schedule-title">
  <div class="vd-modal vd-modal--wide">
    <button type="button" class="vd-modal__close" data-modal-close aria-label="Close">&times;</button>
    <div class="vd-modal__body">
      <h2 id="vd-schedule-title" class="vd-modal__title">Select a consultation date:</h2>

      <div class="vd-calendar">
        <div class="vd-calendar__header">
          <h3 class="vd-calendar__month" id="vd-calendar-month"></h3>
          <div class="vd-calendar__nav">
            <button type="button" class="vd-calendar__nav-btn" id="vd-calendar-prev" aria-label="Previous month">&#8249;</button>
            <button type="button" class="vd-calendar__nav-btn" id="vd-calendar-next" aria-label="Next month">&#8250;</button>
          </div>
        </div>
        <div class="vd-calendar__weekdays">
          <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
        </div>
        <div class="vd-calendar__grid" id="vd-calendar-grid"></div>
        <div class="vd-calendar__time">
          <span class="vd-calendar__time-label">Time</span>
          <div class="vd-calendar__time-selects">
            <select id="vd-time-hour" aria-label="Hour">
              @for($h = 1; $h <= 12; $h++)
                <option value="{{ $h }}" @selected($h == 9)>{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</option>
              @endfor
            </select>
            <span>:</span>
            <select id="vd-time-minute" aria-label="Minute">
              @foreach(['00', '15', '30', '45'] as $m)
                <option value="{{ $m }}">{{ $m }}</option>
              @endforeach
            </select>
            <select id="vd-time-ampm" aria-label="AM or PM">
              <option value="AM">AM</option>
              <option value="PM">PM</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="vd-modal__actions">
      <button type="button" id="vd-schedule-confirm" class="vd-modal__btn vd-modal__btn--confirm" disabled>Request Consultation</button>
    </div>
  </div>
</div>
