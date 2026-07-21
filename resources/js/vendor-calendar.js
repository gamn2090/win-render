document.addEventListener('DOMContentLoaded', function () {
  const cfg = window.__WIN_VENDOR_CALENDAR__;
  if (!cfg) return;

  const modal = document.getElementById('vcal-modal');
  const form = document.getElementById('vcal-form');
  const titleEl = document.getElementById('vcal-modal-title');
  const eventIdInput = document.getElementById('vcal-event-id');
  const clientSelect = document.getElementById('vcal-client-select');
  const dateInput = document.getElementById('vcal-date');
  const startInput = document.getElementById('vcal-start-time');
  const endInput = document.getElementById('vcal-end-time');
  const notesInput = document.getElementById('vcal-notes');
  const deleteBtn = document.getElementById('vcal-delete-btn');
  const errorEl = document.getElementById('vcal-form-error');

  function showError(message) {
    errorEl.textContent = message;
    errorEl.hidden = false;
  }

  function clearError() {
    errorEl.hidden = true;
    errorEl.textContent = '';
  }

  function openModal() {
    modal.hidden = false;
  }

  function closeModal() {
    modal.hidden = true;
    clearError();
  }

  function pad(n) {
    return String(n).padStart(2, '0');
  }

  function openForCreate(dateStr, hour) {
    form.reset();
    eventIdInput.value = '';
    titleEl.textContent = 'Add Event';
    deleteBtn.hidden = true;
    dateInput.value = dateStr;
    const startHour = typeof hour === 'number' ? hour : 10;
    startInput.value = pad(startHour) + ':00';
    endInput.value = pad(Math.min(startHour + 1, 23)) + ':00';
    clearError();
    openModal();
  }

  function openForEdit(ev) {
    form.reset();
    eventIdInput.value = ev.id;
    titleEl.textContent = 'Edit Event';
    deleteBtn.hidden = false;
    clientSelect.value = ev.client_id;
    const start = new Date(ev.startsAt);
    const end = new Date(ev.endsAt);
    dateInput.value = start.getFullYear() + '-' + pad(start.getMonth() + 1) + '-' + pad(start.getDate());
    startInput.value = pad(start.getHours()) + ':' + pad(start.getMinutes());
    endInput.value = pad(end.getHours()) + ':' + pad(end.getMinutes());
    notesInput.value = ev.notes || '';
    clearError();
    openModal();
  }

  document.querySelectorAll('[data-vcal-close]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  document.getElementById('vcal-add-btn').addEventListener('click', () => {
    const today = new Date();
    openForCreate(today.getFullYear() + '-' + pad(today.getMonth() + 1) + '-' + pad(today.getDate()));
  });

  document.querySelectorAll('[data-vcal-cell]').forEach((cell) => {
    cell.addEventListener('click', (e) => {
      if (e.target.closest('[data-vcal-event]')) return;
      openForCreate(cell.dataset.date);
    });
  });

  document.querySelectorAll('[data-vcal-slot]').forEach((slot) => {
    slot.addEventListener('click', () => {
      const [dateStr, timeStr] = slot.dataset.datetime.split('T');
      const hour = parseInt(timeStr.split(':')[0], 10);
      openForCreate(dateStr, hour);
    });
  });

  document.querySelectorAll('[data-vcal-event]').forEach((el) => {
    el.addEventListener('click', (e) => {
      e.stopPropagation();
      const ev = JSON.parse(el.dataset.vcalEvent);
      openForEdit(ev);
    });
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    clearError();

    const clientId = clientSelect.value;
    const date = dateInput.value;
    const start = startInput.value;
    const end = endInput.value;
    if (!clientId || !date || !start || !end) {
      showError('Please fill out every field.');
      return;
    }
    if (end <= start) {
      showError('End time must be after the start time.');
      return;
    }

    const payload = {
      client_id: clientId,
      starts_at: date + ' ' + start + ':00',
      ends_at: date + ' ' + end + ':00',
      notes: notesInput.value,
    };

    const eventId = eventIdInput.value;
    const url = eventId ? cfg.updateUrlBase.replace('__ID__', eventId) : cfg.storeUrl;
    const method = eventId ? 'PATCH' : 'POST';

    fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': cfg.csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
      credentials: 'same-origin',
      body: JSON.stringify(payload),
    })
      .then((res) => {
        if (!res.ok) throw new Error('Request failed');
        return res.json();
      })
      .then(() => {
        window.location.href = cfg.reloadUrl;
      })
      .catch(() => {
        showError('Something went wrong saving that event. Please try again.');
      });
  });

  function ordinal(n) {
    const s = ['th', 'st', 'nd', 'rd'];
    const v = n % 100;
    return n + (s[(v - 20) % 10] || s[v] || s[0]);
  }

  function formatTime12h(date) {
    let h = date.getHours();
    const m = date.getMinutes();
    const suffix = h >= 12 ? 'PM' : 'AM';
    h = h % 12;
    if (h === 0) h = 12;
    return h + ':' + pad(m) + suffix.toLowerCase();
  }

  document.getElementById('vcal-export-btn').addEventListener('click', function () {
    const events = Array.from(document.querySelectorAll('[data-vcal-event]')).map((el) => JSON.parse(el.dataset.vcalEvent));

    const byDate = {};
    events.forEach((ev) => {
      const start = new Date(ev.startsAt);
      const key = start.getFullYear() + '-' + pad(start.getMonth() + 1) + '-' + pad(start.getDate());
      if (!byDate[key]) byDate[key] = [];
      byDate[key].push(ev);
    });

    const dateKeys = Object.keys(byDate).sort();
    const weekdayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    let bodyHtml = '';
    if (!dateKeys.length) {
      bodyHtml = '<p class="empty">No events scheduled for this period.</p>';
    } else {
      dateKeys.forEach((key) => {
        const dayEvents = byDate[key].slice().sort((a, b) => new Date(a.startsAt) - new Date(b.startsAt));
        const [y, m, d] = key.split('-').map(Number);
        const dateObj = new Date(y, m - 1, d);
        const heading = weekdayNames[dateObj.getDay()] + ', ' + monthNames[dateObj.getMonth()] + ' ' + ordinal(d) + ':';

        const items = dayEvents.map((ev) => {
          const start = new Date(ev.startsAt);
          const end = new Date(ev.endsAt);
          return '<li>' + escapeHtml(ev.coupleName) + ' from ' + formatTime12h(start) + ' to ' + formatTime12h(end) + '</li>';
        }).join('');

        bodyHtml += '<h2>' + escapeHtml(heading) + '</h2><ul>' + items + '</ul>';
      });
    }

    const w = window.open('', '_blank');
    if (!w) return;

    w.document.open();
    w.document.write(
      '<!doctype html><html><head><meta charset="utf-8"/>' +
      '<title>' + escapeHtml(cfg.vendorName || 'My') + ' Calendar - ' + escapeHtml(cfg.rangeLabel || '') + '</title>' +
      '<style>' +
      'body{font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:#151515;padding:32px;max-width:720px;margin:0 auto}' +
      'h1{font-size:22px;margin:0 0 4px}' +
      '.subtitle{color:rgba(21,21,21,.6);margin:0 0 24px;font-size:14px}' +
      'h2{font-size:15px;margin:20px 0 6px;border-bottom:1px solid rgba(21,21,21,.14);padding-bottom:4px}' +
      'ul{margin:0 0 8px;padding-left:22px}' +
      'li{font-size:14px;margin-bottom:4px}' +
      '.empty{color:rgba(21,21,21,.6)}' +
      '.actions{margin-bottom:24px}' +
      '.actions button{cursor:pointer;border-radius:999px;border:1px solid rgba(21,21,21,.18);background:#fff;padding:9px 16px;font-weight:700;font-size:13px;margin-right:8px}' +
      '.actions .primary{background:#6432c8;border-color:#6432c8;color:#fff}' +
      '@media print{.actions{display:none !important}}' +
      '</style></head><body>' +
      '<div class="actions"><button onclick="window.close()">Close</button><button class="primary" onclick="window.print()">Print or Save PDF</button></div>' +
      '<h1>' + escapeHtml(cfg.vendorName || 'My') + ' Calendar</h1>' +
      '<p class="subtitle">' + escapeHtml(cfg.rangeLabel || '') + '</p>' +
      bodyHtml +
      '</body></html>'
    );
    w.document.close();
    setTimeout(function () {
      try { w.focus(); w.print(); } catch (e) {}
    }, 350);
  });

  function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = String(str ?? '');
    return div.innerHTML;
  }

  deleteBtn.addEventListener('click', function () {
    const eventId = eventIdInput.value;
    if (!eventId) return;
    if (!confirm('Delete this event?')) return;

    fetch(cfg.destroyUrlBase.replace('__ID__', eventId), {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': cfg.csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
      credentials: 'same-origin',
    })
      .then((res) => {
        if (!res.ok) throw new Error('Request failed');
        return res.json();
      })
      .then(() => {
        window.location.href = cfg.reloadUrl;
      })
      .catch(() => {
        showError('Something went wrong deleting that event. Please try again.');
      });
  });
});
