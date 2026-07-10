document.addEventListener('DOMContentLoaded', () => {
  const listEl = document.getElementById('vm-messages-list');
  const viewEl = document.getElementById('vm-message-view');
  const messagesEl = document.getElementById('vm-view-messages');
  const composerEl = document.getElementById('vm-view-composer');
  const inputEl = document.getElementById('vm-view-input');
  const backBtn = document.getElementById('vm-view-back');
  const vendorMeta = window.vmVendorMeta || { initials: 'ME', first_name: '' };

  let activeConvoId = null;
  let activeMeta = null;

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  function escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function parseMeta(raw) {
    if (!raw) {
      return null;
    }
    try {
      return typeof raw === 'string' ? JSON.parse(raw) : raw;
    } catch {
      return null;
    }
  }

  function formatTime(iso) {
    const date = new Date(iso);
    if (Number.isNaN(date.getTime())) {
      return '';
    }
    return date.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  }

  function formatDayLabel(iso) {
    const date = new Date(iso);
    if (Number.isNaN(date.getTime())) {
      return '';
    }
    const today = new Date();
    const sameDay =
      date.getFullYear() === today.getFullYear() &&
      date.getMonth() === today.getMonth() &&
      date.getDate() === today.getDate();

    if (sameDay) {
      return `Today, ${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric' })}`;
    }

    const yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);
    const isYesterday =
      date.getFullYear() === yesterday.getFullYear() &&
      date.getMonth() === yesterday.getMonth() &&
      date.getDate() === yesterday.getDate();

    if (isYesterday) {
      return `Yesterday, ${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric' })}`;
    }

    return date.toLocaleDateString('en-US', {
      weekday: 'long',
      month: 'long',
      day: 'numeric',
    });
  }

  function populateSidebar(meta) {
    const avatar = document.getElementById('vm-view-avatar');
    const partnerOne = document.getElementById('vm-view-partner-one');
    const partnerTwo = document.getElementById('vm-view-partner-two');
    const heart = document.getElementById('vm-view-heart');
    const infoHeading = document.getElementById('vm-view-info-heading');
    const infoPrimary = document.getElementById('vm-view-info-primary');
    const infoSecondary = document.getElementById('vm-view-info-secondary');
    const moreOne = document.getElementById('vm-view-more-one');
    const moreTwo = document.getElementById('vm-view-more-two');
    const moreTwoRow = document.getElementById('vm-view-more-two-row');
    const profileLink = document.getElementById('vm-view-profile-link');

    if (avatar) {
      avatar.src = meta.avatar || '';
      avatar.alt = `${meta.partner_one || ''} ${meta.partner_two || ''}`.trim();
    }

    if (partnerOne) {
      partnerOne.textContent = meta.partner_one || '';
    }
    if (partnerTwo) {
      partnerTwo.textContent = meta.partner_two || '';
    }
    if (heart) {
      heart.hidden = meta.role !== 'client' || !meta.partner_two;
    }
    if (infoHeading) {
      infoHeading.textContent = meta.info_heading || 'Wedding Information:';
    }
    if (infoPrimary) {
      infoPrimary.textContent = meta.info_primary || '';
    }
    if (infoSecondary) {
      infoSecondary.textContent = meta.info_secondary || '';
    }
    if (moreOne) {
      moreOne.textContent = meta.partner_one_full || meta.partner_one || '';
    }
    if (moreTwo) {
      moreTwo.textContent = meta.partner_two_full || meta.partner_two || '';
    }
    if (moreTwoRow) {
      moreTwoRow.hidden = !(meta.partner_two_full || meta.partner_two);
    }
    if (profileLink) {
      if (meta.profile_url) {
        profileLink.href = meta.profile_url;
        profileLink.textContent = meta.profile_label || 'Visit Profile';
        profileLink.hidden = false;
      } else {
        profileLink.hidden = true;
      }
    }
    if (inputEl) {
      inputEl.placeholder = meta.composer_placeholder || 'Write a message…';
    }
  }

  function renderSystemMessage(message) {
    if (message.type === 'inquiry' && message.data) {
      const data = message.data;
      return `<div class="vm-message-view__system">
        <strong>${escapeHtml(data.first_name)} ♥ ${escapeHtml(data.fiance_first_name)}</strong>
        are interested in your services for their wedding on:
        <strong>${escapeHtml(data.wedding_date || '')}</strong>
      </div>`;
    }

    if (message.type === 'consultation-request' && message.data) {
      const data = message.data;
      const when = data.meeting_date ? new Date(data.meeting_date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
      }) : '';
      return `<div class="vm-message-view__system">
        <strong>${escapeHtml(data.first_name)} ♥ ${escapeHtml(data.fiance_first_name)}</strong>
        would like to schedule a consultation on:
        <strong>${escapeHtml(when)}</strong>
      </div>`;
    }

    if (message.type === 'attachment') {
      const name = message.data?.attachment_name || 'document.pdf';
      const url = message.data?.download_url || '#';
      const isSender = message.is_sender == 1;
      const rowClass = isSender ? 'vm-message-view__row vm-message-view__row--out' : 'vm-message-view__row vm-message-view__row--in';
      const initials = isSender ? vendorMeta.initials : (activeMeta?.other_initials || '?');
      const time = formatTime(message.created_at);
      const attachMetaHtml = `<div class="vm-message-view__meta">
          <span class="vm-message-view__time">${escapeHtml(time)}</span>
          <span class="vm-message-view__initials">${escapeHtml(initials)}</span>
        </div>`;
      const attachBubbleHtml = `<div class="vm-message-view__bubble">
          ${message.body ? `<p>${escapeHtml(message.body)}</p>` : ''}
          <a href="${escapeHtml(url)}" target="_blank" rel="noopener" style="color:inherit;font-weight:600;">${escapeHtml(name)}</a>
        </div>`;

      if (isSender) {
        return `<div class="${rowClass}">${attachMetaHtml}${attachBubbleHtml}</div>`;
      }

      return `<div class="${rowClass}">${attachBubbleHtml}${attachMetaHtml}</div>`;
    }

    return '';
  }

  function renderTextBubble(message) {
    const isSender = message.is_sender == 1;
    const rowClass = isSender ? 'vm-message-view__row vm-message-view__row--out' : 'vm-message-view__row vm-message-view__row--in';
    const initials = isSender ? vendorMeta.initials : (activeMeta?.other_initials || '?');
    const time = formatTime(message.created_at);
    const metaHtml = `<div class="vm-message-view__meta">
        <span class="vm-message-view__time">${escapeHtml(time)}</span>
        <span class="vm-message-view__initials">${escapeHtml(initials)}</span>
      </div>`;
    const bubbleHtml = `<div class="vm-message-view__bubble">${escapeHtml(message.body || '')}</div>`;

    if (isSender) {
      return `<div class="${rowClass}">${metaHtml}${bubbleHtml}</div>`;
    }

    return `<div class="${rowClass}">${bubbleHtml}${metaHtml}</div>`;
  }

  function renderMessages(messages) {
    if (!messagesEl) {
      return;
    }

    if (!messages || messages.length === 0) {
      messagesEl.innerHTML = '<p class="vm-message-view__empty">No messages yet. Say hello!</p>';
      return;
    }

    let html = '';
    let lastDay = '';

    messages.forEach((message) => {
      const day = message.created_at ? formatDayLabel(message.created_at) : '';
      if (day && day !== lastDay) {
        html += `<p class="vm-message-view__date">${escapeHtml(day)}</p>`;
        lastDay = day;
      }

      if (message.type === 'text') {
        html += renderTextBubble(message);
      } else {
        html += renderSystemMessage(message);
      }
    });

    messagesEl.innerHTML = html;
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  async function loadMessages(convoId) {
    if (!messagesEl) {
      return;
    }

    messagesEl.innerHTML = '<p class="vm-message-view__loading">Loading messages…</p>';

    try {
      const response = await fetch(`/vendor/messages/${convoId}`, {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
      });

      if (!response.ok) {
        throw new Error('Failed to load messages');
      }

      const payload = await response.json();
      const items = payload?.messages?.data || [];
      renderMessages(items);
    } catch {
      messagesEl.innerHTML = '<p class="vm-message-view__empty">Could not load messages. Please try again.</p>';
    }
  }

  function openView(convoId, meta) {
    if (!viewEl || !listEl) {
      return;
    }

    activeConvoId = convoId;
    activeMeta = meta;
    populateSidebar(meta);
    listEl.hidden = true;
    viewEl.hidden = false;
    viewEl.setAttribute('aria-hidden', 'false');
    loadMessages(convoId);
    inputEl?.focus();
  }

  function closeView() {
    if (!viewEl || !listEl) {
      return;
    }

    activeConvoId = null;
    activeMeta = null;
    viewEl.hidden = true;
    viewEl.setAttribute('aria-hidden', 'true');
    listEl.hidden = false;
    if (inputEl) {
      inputEl.value = '';
    }
  }

  function openFromRow(row) {
    const convoId = row.getAttribute('data-vm-convo');
    const meta = parseMeta(row.getAttribute('data-vm-meta'));
    if (convoId && meta) {
      openView(convoId, meta);
    }
  }

  document.querySelectorAll('[data-vm-view-btn]').forEach((btn) => {
    btn.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      const convoId = btn.getAttribute('data-vm-convo');
      const meta = parseMeta(btn.getAttribute('data-vm-meta'));
      if (convoId && meta) {
        openView(convoId, meta);
      }
    });
  });

  document.querySelectorAll('.vm-table__row[data-vm-convo]').forEach((row) => {
    row.addEventListener('click', (event) => {
      if (event.target.closest('button, a')) {
        return;
      }
      openFromRow(row);
    });

    row.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        openFromRow(row);
      }
    });
  });

  backBtn?.addEventListener('click', closeView);

  composerEl?.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (!activeConvoId || !inputEl) {
      return;
    }

    const message = inputEl.value.trim();
    if (message === '') {
      return;
    }

    const sendBtn = composerEl.querySelector('.vm-message-view__send');
    if (sendBtn) {
      sendBtn.disabled = true;
    }

    try {
      const body = new URLSearchParams({
        message,
        conversation: activeConvoId,
      });

      const response = await fetch('/vendor/send/message', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': csrfToken,
          Accept: 'application/json',
        },
        credentials: 'same-origin',
        body: body.toString(),
      });

      if (!response.ok) {
        throw new Error('Send failed');
      }

      inputEl.value = '';
      await loadMessages(activeConvoId);
    } catch {
      inputEl.focus();
    } finally {
      if (sendBtn) {
        sendBtn.disabled = false;
      }
    }
  });
});
