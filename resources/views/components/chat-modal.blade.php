<div id="win-chat-modal" class="vd-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="win-chat-modal-title">
  <div class="win-chat-modal">
    <div class="win-chat-modal__header">
      <div class="win-chat-modal__who">
        <span id="win-chat-modal-avatar" class="win-chat-modal__avatar"></span>
        <span id="win-chat-modal-title" class="win-chat-modal__name"></span>
      </div>
      <button type="button" class="win-chat-modal__close" id="win-chat-modal-close" aria-label="Close">&times;</button>
    </div>

    <div id="win-chat-modal-messages" class="vd-chat__messages" role="log" aria-live="polite">
      <p class="vd-chat__empty">Loading messages…</p>
    </div>

    <form id="win-chat-modal-composer" class="vd-chat__composer" autocomplete="off">
      <input type="text" id="win-chat-modal-input" class="vd-chat__input" placeholder="Write a message…" maxlength="5000" />
      <button type="submit" class="vd-chat__send" aria-label="Send message">➤</button>
    </form>
  </div>
</div>
