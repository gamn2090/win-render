document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('win-chat-modal');
  if (!modalEl) {
    return;
  }

  const messagesEl = document.getElementById('win-chat-modal-messages');
  const composerEl = document.getElementById('win-chat-modal-composer');
  const inputEl = document.getElementById('win-chat-modal-input');
  const closeBtn = document.getElementById('win-chat-modal-close');
  const nameEl = document.getElementById('win-chat-modal-title');
  const avatarEl = document.getElementById('win-chat-modal-avatar');
  const myMeta = window.winChatModalMeta || { initials: 'ME' };

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  let activeConvoId = null;
  let activeRole = null;
  let activeOtherInitials = '?';

  function escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function formatTime(iso) {
    const date = new Date(iso);
    if (Number.isNaN(date.getTime())) {
      return '';
    }
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
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

    return date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
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

  function renderBubble(message) {
    const isSender = message.is_sender == 1;
    const rowClass = isSender ? 'vd-chat__row vd-chat__row--mine' : 'vd-chat__row vd-chat__row--vendor';
    const initials = isSender ? (myMeta.initials || 'ME') : activeOtherInitials;
    const time = formatTime(message.created_at);
    const bubbleHtml = `<div class="vd-chat__bubble">${escapeHtml(message.body || '')}</div>`;
    const metaHtml = `<div class="vd-chat__meta">
        <span class="vd-chat__avatar-sm">${escapeHtml(initials)}</span>
        <span class="vd-chat__time">${escapeHtml(time)}</span>
      </div>`;

    return `<div class="${rowClass}">${bubbleHtml}${metaHtml}</div>`;
  }

  function renderSystemMessage(message, resolvedMeetings) {
    if (message.type === 'inquiry' && message.data) {
      const data = message.data;
      return `<div class="vd-chat__system">
        <strong>${escapeHtml(data.first_name)} ♥ ${escapeHtml(data.fiance_first_name)}</strong>
        are interested in your services for their wedding on:
        <strong>${escapeHtml(data.wedding_date || '')}</strong>
      </div>`;
    }

    if (message.type === 'consultation-request' && message.data) {
      const data = message.data;
      const resolution = data.meeting_uuid ? resolvedMeetings?.[data.meeting_uuid] : undefined;
      const canAnswer = activeRole === 'vendor';

      let actionsHtml;
      if (resolution === true) {
        actionsHtml = '<div class="vd-chat__consult-status vd-chat__consult-status--accepted">You accepted this consultation.</div>';
      } else if (resolution === false) {
        actionsHtml = '<div class="vd-chat__consult-status vd-chat__consult-status--declined">You declined this consultation.</div>';
      } else if (canAnswer && data.meeting_uuid) {
        actionsHtml = `<div class="vd-chat__consult-actions">
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--reject" data-consult-answer="-1" data-consult-meeting="${escapeHtml(data.meeting_uuid)}">Reject</button>
          <button type="button" class="vd-chat__consult-btn vd-chat__consult-btn--accept" data-consult-answer="1" data-consult-meeting="${escapeHtml(data.meeting_uuid)}">Accept</button>
        </div>`;
      } else {
        actionsHtml = '';
      }

      const when = formatMeetingDate(data.meeting_date);

      return `<div class="vd-chat__system">
        The client <strong>${escapeHtml(data.first_name)} ♥ ${escapeHtml(data.fiance_first_name)}</strong> is requesting a consultation${when ? ` on <strong>${escapeHtml(when)}</strong>` : ''}, would you accept?
        ${actionsHtml}
      </div>`;
    }

    if (message.type === 'consultation-response' && message.data) {
      const data = message.data;
      const statusClass = data.accepted ? 'vd-chat__consult-status--accepted' : 'vd-chat__consult-status--declined';
      const isVendorView = activeRole === 'vendor';
      const statusText = data.accepted
        ? (isVendorView ? 'You accepted this consultation.' : 'The vendor accepted your consultation request! 🎉')
        : (isVendorView ? 'You declined this consultation.' : 'The vendor declined your consultation request.');
      return `<div class="vd-chat__system">
        <div class="vd-chat__consult-status ${statusClass}">${statusText}</div>
      </div>`;
    }

    if (message.type === 'attachment') {
      const name = message.data?.attachment_name || 'document.pdf';
      const url = message.data?.download_url || '#';
      const isSender = message.is_sender == 1;
      const rowClass = isSender ? 'vd-chat__row vd-chat__row--mine' : 'vd-chat__row vd-chat__row--vendor';
      const initials = isSender ? (myMeta.initials || 'ME') : activeOtherInitials;
      const time = formatTime(message.created_at);
      const bubbleHtml = `<div class="vd-chat__bubble">
          ${message.body ? `<p>${escapeHtml(message.body)}</p>` : ''}
          <a href="${escapeHtml(url)}" target="_blank" rel="noopener" style="color:inherit;font-weight:600;">${escapeHtml(name)}</a>
        </div>`;
      const metaHtml = `<div class="vd-chat__meta">
          <span class="vd-chat__avatar-sm">${escapeHtml(initials)}</span>
          <span class="vd-chat__time">${escapeHtml(time)}</span>
        </div>`;

      return `<div class="${rowClass}">${bubbleHtml}${metaHtml}</div>`;
    }

    return '';
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
      if (message.type === 'consultation-response' && message.data?.meeting_uuid) {
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

      if (message.type === 'text' || !message.type) {
        html += renderBubble(message);
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

      if (activeConvoId && activeRole) {
        await loadMessages(activeConvoId, activeRole);
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

  async function loadMessages(convoId, role) {
    if (!messagesEl) {
      return;
    }

    messagesEl.innerHTML = '<p class="vd-chat__empty">Loading messages…</p>';

    const base = role === 'vendor' ? '/vendor/messages/' : '/client/messages/';

    try {
      const response = await fetch(`${base}${convoId}`, {
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

  function openModal(trigger) {
    const convoId = trigger.getAttribute('data-chat-modal-convo');
    const role = trigger.getAttribute('data-chat-modal-role');
    const name = trigger.getAttribute('data-chat-modal-name') || '';
    const otherInitials = trigger.getAttribute('data-chat-modal-other-initials') || '?';
    const avatarPhoto = trigger.getAttribute('data-chat-modal-avatar-photo') || '';
    const avatarBg = trigger.getAttribute('data-chat-modal-avatar-bg') || '#6432C8';
    const avatarFg = trigger.getAttribute('data-chat-modal-avatar-fg') || '#FFFFFF';

    if (!convoId || !role) {
      return;
    }

    activeConvoId = convoId;
    activeRole = role;
    activeOtherInitials = otherInitials;

    if (nameEl) {
      nameEl.textContent = name;
    }

    if (avatarEl) {
      if (avatarPhoto) {
        avatarEl.innerHTML = `<img src="${escapeHtml(avatarPhoto)}" alt="" class="win-avatar-img" style="width:100%;height:100%;border-radius:50%;object-fit:cover;" />`;
      } else {
        avatarEl.innerHTML = '';
        avatarEl.textContent = otherInitials;
        avatarEl.classList.add('win-avatar-fallback');
        avatarEl.style.background = avatarBg;
        avatarEl.style.color = avatarFg;
        avatarEl.style.display = 'flex';
        avatarEl.style.alignItems = 'center';
        avatarEl.style.justifyContent = 'center';
      }
    }

    modalEl.classList.add('is-open');
    loadMessages(convoId, role);
    inputEl?.focus();
  }

  function closeModal() {
    activeConvoId = null;
    activeRole = null;
    modalEl.classList.remove('is-open');
    if (inputEl) {
      inputEl.value = '';
    }
  }

  document.querySelectorAll('[data-chat-modal-trigger]').forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      openModal(trigger);
    });
  });

  closeBtn?.addEventListener('click', closeModal);

  modalEl.addEventListener('click', (event) => {
    if (event.target === modalEl) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modalEl.classList.contains('is-open')) {
      closeModal();
    }
  });

  composerEl?.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (!activeConvoId || !activeRole || !inputEl) {
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

    const endpoint = activeRole === 'vendor' ? '/vendor/send/message' : '/client/send/message';

    try {
      const body = new URLSearchParams({
        message,
        conversation: activeConvoId,
      });

      const response = await fetch(endpoint, {
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
      await loadMessages(activeConvoId, activeRole);
    } catch {
      inputEl.focus();
    } finally {
      if (sendBtn) {
        sendBtn.disabled = false;
      }
    }
  });
});
