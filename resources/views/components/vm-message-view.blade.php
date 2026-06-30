@php
  $fcAssets = asset('assets/img/vendor-home/find_couple');
@endphp

<div id="vm-message-view" class="vm-message-view" hidden aria-hidden="true">
  <div class="vm-message-view__grid">
    <aside class="vm-message-view__sidebar" aria-label="Conversation details">
      <img
        id="vm-view-avatar"
        class="vm-message-view__avatar"
        src=""
        alt=""
        width="120"
        height="120"
      />

      <h2 id="vm-view-names" class="vm-message-view__names">
        <span id="vm-view-partner-one"></span>
        <img
          id="vm-view-heart"
          src="{{ $fcAssets }}/mdi_heart.png"
          alt=""
          class="vm-message-view__heart"
          width="18"
          height="18"
        />
        <span id="vm-view-partner-two"></span>
      </h2>

      <div class="vm-message-view__block">
        <h3 id="vm-view-info-heading" class="vm-message-view__label">Wedding Information:</h3>
        <p class="vm-message-view__detail">
          <svg class="vm-message-view__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M8 2v3M16 2v3M3.5 9.09h17M21 8.5V17a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8.5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span id="vm-view-info-primary"></span>
        </p>
        <p class="vm-message-view__detail">
          <svg class="vm-message-view__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 21s7-4.35 7-10a7 7 0 1 0-14 0c0 5.65 7 10 7 10Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.75"/>
          </svg>
          <span id="vm-view-info-secondary"></span>
        </p>
      </div>

      <div class="vm-message-view__block">
        <h3 class="vm-message-view__label">More Info</h3>
        <p class="vm-message-view__detail">
          <svg class="vm-message-view__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.75"/>
            <path d="M5.5 20c0-3.59 2.91-6.5 6.5-6.5s6.5 2.91 6.5 6.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
          </svg>
          <span id="vm-view-more-one"></span>
        </p>
        <p id="vm-view-more-two-row" class="vm-message-view__detail">
          <svg class="vm-message-view__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.75"/>
            <path d="M5.5 20c0-3.59 2.91-6.5 6.5-6.5s6.5 2.91 6.5 6.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
          </svg>
          <span id="vm-view-more-two"></span>
        </p>
      </div>

      <a id="vm-view-profile-link" href="#" class="vm-message-view__profile-btn">Visit Profile</a>
    </aside>

    <section class="vm-message-view__chat" aria-label="Conversation">
      <button type="button" class="vm-message-view__back" id="vm-view-back" aria-label="Back to messages">
        <span aria-hidden="true">&larr;</span> Back
      </button>

      <div id="vm-view-messages" class="vm-message-view__messages" role="log" aria-live="polite"></div>

      <form id="vm-view-composer" class="vm-message-view__composer" autocomplete="off">
        <input
          type="text"
          id="vm-view-input"
          class="vm-message-view__input"
          placeholder="Write a message…"
          maxlength="5000"
        />
        <button type="submit" class="vm-message-view__send" aria-label="Send message">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="m22 2-7 20-4-9-9-4 20-7Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/>
            <path d="M22 2 11 13" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
          </svg>
        </button>
      </form>
    </section>
  </div>
</div>
