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

  function formatMeetingDate(value) {
    if (!value) {
      return '';
    }
    const date = new Date(value.includes('T') ? value : value.replace(' ', 'T'));
    if (Number.isNaN(date.getTime())) {
      return value;
    }
    return date.toLocaleString('en-US', {
      month: 'long',
      day: 'numeric',
      year: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  }

  function populateSidebar(meta) {
    const avatar = document.getElementById('vm-view-avatar');
    const avatarFallback = document.getElementById('vm-view-avatar-fallback');
    const partnerOne = document.getElementById('vm-view-partner-one');
    const partnerTwo = document.getElementById('vm-view-partner-two');
    const heart = document.getElementById('vm-view-heart');
    const infoHeading = document.getElementById('vm-view-info-heading');
    const infoPrimary = document.getElementById('vm-view-info-primary');
    const infoPrimaryRow = document.getElementById('vm-view-info-primary-row');
    const infoSecondary = document.getElementById('vm-view-info-secondary');
    const moreOne = document.getElementById('vm-view-more-one');
    const moreTwo = document.getElementById('vm-view-more-two');
    const moreTwoRow = document.getElementById('vm-view-more-two-row');
    const profileLink = document.getElementById('vm-view-profile-link');

    if (avatar && avatarFallback) {
      const label = `${meta.partner_one || ''} ${meta.partner_two || ''}`.trim();
      if (meta.avatar_has_photo) {
        avatar.src = meta.avatar || '';
        avatar.alt = label;
        avatar.hidden = false;
        avatarFallback.hidden = true;
      } else {
        avatar.hidden = true;
        avatarFallback.hidden = false;
        avatarFallback.textContent = meta.avatar_initials || '?';
        const colors = meta.avatar_colors || ['#6432C8', '#FFFFFF'];
        avatarFallback.style.background = colors[0];
        avatarFallback.style.color = colors[1];
      }
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
    if (infoPrimaryRow) {
      infoPrimaryRow.hidden = !meta.info_primary;
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

  function renderSystemMessage(message, resolvedMeetings) {
    if (message.type === 'inquiry' && message.data) {
      const data = message.data;
      const inquiryConflictHtml = data.has_conflict
        ? '<div class="vd-chat__consult-conflict">⚠️ You already have something on your calendar that day.</div>'
        : '';
      return `<div class="vm-message-view__system">
        <strong>${escapeHtml(data.first_name)} ♥ ${escapeHtml(data.fiance_first_name)}</strong>
        are interested in your services for their wedding on:
        <strong>${escapeHtml(data.wedding_date || '')}</strong>
        ${inquiryConflictHtml}
      </div>`;
    }

    if (message.type === 'booked' && message.data) {
      const data = message.data;
      return `<div class="vm-message-view__system">
        🎉 <strong>${escapeHtml(data.first_name)} ♥ ${escapeHtml(data.fiance_first_name)}</strong>
        marked you as booked for their wedding on <strong>${escapeHtml(formatMeetingDate(data.wedding_date) || data.wedding_date || '')}</strong>!
      </div>`;
    }

    if (message.type === 'endorsement' && message.data) {
      const data = message.data;
      const types = Array.isArray(data.types) ? data.types.map(escapeHtml).join(', ') : '';
      return `<div class="vm-message-view__system">
        🌟 Congratulations! <strong>${escapeHtml(data.endorser_name)}</strong> endorsed you${types ? ` for: <strong>${types}</strong>` : ''}.
      </div>`;
    }

    if (message.type === 'consultation-request' && message.data) {
      const data = message.data;
      const resolution = data.meeting_uuid ? resolvedMeetings?.[data.meeting_uuid] : undefined;

      let actionsHtml;
      if (resolution === true) {
        actionsHtml = '<div class="vd-chat__consult-status vd-chat__consult-status--accepted">You accepted this consultation.</div>';
      } else if (resolution === false) {
        actionsHtml = '<div class="vd-chat__consult-status vd-chat__consult-status--declined">You declined this consultation.</div>';
      } else if (data.meeting_uuid) {
        actionsHtml = `<div class="vd-chat__consult-actions">
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--reject" data-consult-answer="-1" data-consult-meeting="${escapeHtml(data.meeting_uuid)}">Reject</button>
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--suggest" data-consult-suggest-meeting="${escapeHtml(data.meeting_uuid)}">Suggest New Time</button>
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--accept" data-consult-answer="1" data-consult-meeting="${escapeHtml(data.meeting_uuid)}">Accept</button>
        </div>
        <div class="vd-chat__consult-suggest-form" data-consult-suggest-form="${escapeHtml(data.meeting_uuid)}" hidden>
          <input type="datetime-local" class="vd-chat__consult-suggest-input" />
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--accept" data-consult-suggest-send="${escapeHtml(data.meeting_uuid)}">Send</button>
        </div>`;
      } else {
        actionsHtml = '';
      }

      const when = formatMeetingDate(data.meeting_date);
      const conflictHtml = data.has_conflict
        ? '<div class="vd-chat__consult-conflict">⚠️ You already have something on your calendar that day.</div>'
        : '';

      return `<div class="vm-message-view__system">
        The client <strong>${escapeHtml(data.first_name)} ♥ ${escapeHtml(data.fiance_first_name)}</strong> is requesting a consultation${when ? ` on <strong>${escapeHtml(when)}</strong>` : ''}, would you accept?
        ${conflictHtml}
        ${actionsHtml}
      </div>`;
    }

    if (message.type === 'consultation-response' && message.data) {
      const data = message.data;
      const statusClass = data.accepted ? 'vd-chat__consult-status--accepted' : 'vd-chat__consult-status--declined';
      const statusText = data.accepted ? 'You accepted this consultation.' : 'You declined this consultation.';
      const suggestHtml = (!data.accepted && data.meeting_uuid) ? `<div class="vd-chat__consult-actions">
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--suggest" data-consult-suggest-meeting="${escapeHtml(data.meeting_uuid)}">Suggest New Time</button>
        </div>
        <div class="vd-chat__consult-suggest-form" data-consult-suggest-form="${escapeHtml(data.meeting_uuid)}" hidden>
          <input type="datetime-local" class="vd-chat__consult-suggest-input" />
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--accept" data-consult-suggest-send="${escapeHtml(data.meeting_uuid)}">Send</button>
        </div>` : '';
      return `<div class="vm-message-view__system">
        <div class="vd-chat__consult-status ${statusClass}">${statusText}</div>
        ${suggestHtml}
      </div>`;
    }

    if (message.type === 'consultation-suggestion' && message.data) {
      const data = message.data;
      const resolution = data.meeting_uuid ? resolvedMeetings?.[data.meeting_uuid] : undefined;
      const when = formatMeetingDate(data.meeting_date);
      let statusHtml;
      if (resolution === true) {
        statusHtml = '<div class="vd-chat__consult-status vd-chat__consult-status--accepted">The couple accepted this time! 🎉</div>';
      } else if (resolution === false) {
        statusHtml = '<div class="vd-chat__consult-status vd-chat__consult-status--declined">The couple declined this time.</div>';
      } else {
        statusHtml = '<div class="vd-chat__consult-status">Waiting on the couple to respond…</div>';
      }
      return `<div class="vm-message-view__system">
        You suggested a new consultation time${when ? `: <strong>${escapeHtml(when)}</strong>` : ''}.
        ${statusHtml}
      </div>`;
    }

    if (message.type === 'attachment') {
      const name = message.data?.attachment_name || 'document.pdf';
      const url = message.data?.download_url || '#';
      const isSender = message.is_sender == 1;
      const rowClass = isSender ? 'vd-chat__row vd-chat__row--mine' : 'vd-chat__row vd-chat__row--vendor';
      const initials = isSender ? vendorMeta.initials : (activeMeta?.other_initials || '?');
      const time = formatTime(message.created_at);
      const attachBubbleHtml = `<div class="vd-chat__bubble">
          ${message.body ? `<p>${escapeHtml(message.body)}</p>` : ''}
          <a href="${escapeHtml(url)}" target="_blank" rel="noopener" style="color:inherit;font-weight:600;">${escapeHtml(name)}</a>
        </div>`;
      const attachMetaHtml = `<div class="vd-chat__meta">
          <span class="vd-chat__avatar-sm">${escapeHtml(initials)}</span>
          <span class="vd-chat__time">${escapeHtml(time)}</span>
        </div>`;

      return `<div class="${rowClass}">${attachBubbleHtml}${attachMetaHtml}</div>`;
    }

    return '';
  }

  function renderTextBubble(message) {
    const isSender = message.is_sender == 1;
    const rowClass = isSender ? 'vd-chat__row vd-chat__row--mine' : 'vd-chat__row vd-chat__row--vendor';
    const initials = isSender ? vendorMeta.initials : (activeMeta?.other_initials || '?');
    const time = formatTime(message.created_at);
    const bubbleHtml = `<div class="vd-chat__bubble">${escapeHtml(message.body || '')}</div>`;
    const metaHtml = `<div class="vd-chat__meta">
        <span class="vd-chat__avatar-sm">${escapeHtml(initials)}</span>
        <span class="vd-chat__time">${escapeHtml(time)}</span>
      </div>`;

    return `<div class="${rowClass}">${bubbleHtml}${metaHtml}</div>`;
  }

  function renderMessages(messages) {
    if (!messagesEl) {
      return;
    }

    if (!messages || messages.length === 0) {
      messagesEl.innerHTML = '<p class="vd-chat__empty">No messages yet. Say hello!</p>';
      return;
    }

    const resolvedMeetings = {};
    messages.forEach((message) => {
      if (
        (message.type === 'consultation-response' || message.type === 'consultation-suggestion-response')
        && message.data?.meeting_uuid
      ) {
        resolvedMeetings[message.data.meeting_uuid] = !!message.data.accepted;
      }
    });

    let html = '';
    let lastDay = '';

    messages.forEach((message) => {
      const day = message.created_at ? formatDayLabel(message.created_at) : '';
      if (day && day !== lastDay) {
        html += `<div class="vd-chat__date-divider"><span>${escapeHtml(day)}</span></div>`;
        lastDay = day;
      }

      if (message.type === 'text') {
        html += renderTextBubble(message);
      } else {
        html += renderSystemMessage(message, resolvedMeetings);
      }
    });

    messagesEl.innerHTML = html;
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  async function answerConsultation(meetingUuid, answer, button) {
    const actionsRow = button.closest('.vd-chat__consult-actions');
    actionsRow?.querySelectorAll('button').forEach((btn) => { btn.disabled = true; });

    try {
      const body = new URLSearchParams({
        meeting_id: meetingUuid,
        answer: String(answer),
      });

      const response = await fetch('/meeting/answer', {
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
        throw new Error('Failed to answer consultation');
      }

      if (activeConvoId) {
        await loadMessages(activeConvoId);
      }
    } catch {
      actionsRow?.querySelectorAll('button').forEach((btn) => { btn.disabled = false; });
    }
  }

  messagesEl?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-consult-answer]');
    if (!button) {
      return;
    }
    const meetingUuid = button.getAttribute('data-consult-meeting');
    const answer = button.getAttribute('data-consult-answer');
    if (meetingUuid && answer) {
      answerConsultation(meetingUuid, answer, button);
    }
  });

  async function suggestNewTime(meetingUuid, newDate, button) {
    const form = button.closest('.vd-chat__consult-suggest-form');
    form?.querySelectorAll('button, input').forEach((el) => { el.disabled = true; });

    try {
      const body = new URLSearchParams({ meeting_id: meetingUuid, new_date: newDate });
      const response = await fetch('/meeting/suggest-new-time', {
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
        throw new Error('Failed to suggest new time');
      }
      if (activeConvoId) {
        await loadMessages(activeConvoId);
      }
    } catch {
      form?.querySelectorAll('button, input').forEach((el) => { el.disabled = false; });
    }
  }

  messagesEl?.addEventListener('click', (event) => {
    const toggleBtn = event.target.closest('[data-consult-suggest-meeting]');
    if (toggleBtn) {
      const meetingUuid = toggleBtn.getAttribute('data-consult-suggest-meeting');
      const form = messagesEl.querySelector(`[data-consult-suggest-form="${meetingUuid}"]`);
      if (form) form.hidden = !form.hidden;
      return;
    }

    const sendBtn = event.target.closest('[data-consult-suggest-send]');
    if (sendBtn) {
      const meetingUuid = sendBtn.getAttribute('data-consult-suggest-send');
      const form = sendBtn.closest('.vd-chat__consult-suggest-form');
      const input = form?.querySelector('.vd-chat__consult-suggest-input');
      if (meetingUuid && input?.value) {
        suggestNewTime(meetingUuid, input.value, sendBtn);
      }
    }
  });

  async function loadMessages(convoId) {
    if (!messagesEl) {
      return;
    }

    messagesEl.innerHTML = '<p class="vd-chat__empty">Loading messages…</p>';

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
      messagesEl.innerHTML = '<p class="vd-chat__empty">Could not load messages. Please try again.</p>';
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

    const sendBtn = composerEl.querySelector('.vd-chat__send');
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

  const openConversation = window.vmOpenConversation;
  if (openConversation && openConversation.conversation_id && openConversation.view_meta) {
    openView(openConversation.conversation_id, openConversation.view_meta);
  }
});
