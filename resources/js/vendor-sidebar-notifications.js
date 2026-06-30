/**
 * Polls unread message notifications for the vendor sidebar badge.
 */
(function () {
  const messagesLink = document.getElementById('vendor-sidebar-messages-link');
  if (!messagesLink || !document.querySelector('.vendor-sidebar')) {
    return;
  }

  const POLL_MS = 5000;
  let lastCount = null;

  function renderBadge(count) {
    let badge = messagesLink.querySelector('.vendor-sidebar__badge');

    if (count <= 0) {
      badge?.remove();
      return;
    }

    const label = count > 99 ? '99+' : String(count);

    if (!badge) {
      badge = document.createElement('span');
      badge.className = 'vendor-sidebar__badge';
      messagesLink.appendChild(badge);
    }

    badge.textContent = label;
    badge.setAttribute('aria-label', count + ' unread');
  }

  function refreshUnreadCount() {
    return fetch('/vendor/notifications/unread-count', {
      method: 'GET',
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Unread count request failed');
        }
        return response.json();
      })
      .then(function (data) {
        const count = Number(data.count) || 0;
        if (lastCount === null || count !== lastCount) {
          renderBadge(count);
          lastCount = count;
        }
      })
      .catch(function () {
        /* silent — next poll retries */
      });
  }

  window.refreshVendorSidebarUnread = refreshUnreadCount;

  refreshUnreadCount();
  setInterval(refreshUnreadCount, POLL_MS);
})();
