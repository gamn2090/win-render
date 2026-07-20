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
