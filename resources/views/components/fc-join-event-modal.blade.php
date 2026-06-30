<div
  id="join-event-modal"
  class="fc-dialog-modal hs-overlay hs-overlay-open:pointer-events-auto hidden fixed inset-0 z-[85] overflow-x-hidden overflow-y-auto pointer-events-none"
  role="dialog"
  aria-modal="true"
  aria-labelledby="join-event-modal-title"
  tabindex="-1"
>
  <div
    class="fc-dialog-modal__backdrop hs-overlay-backdrop-open:opacity-100 opacity-0 transition-opacity duration-300"
    data-hs-overlay="#join-event-modal"
    aria-hidden="true"
  ></div>

  <div class="fc-dialog-modal__wrap hs-overlay-open:mt-0 hs-overlay-open:opacity-100 mt-6 opacity-0 transition-all duration-300 flex min-h-full items-center justify-center px-4 py-8">
    <div class="fc-dialog-modal__panel pointer-events-auto relative w-full max-w-[420px]">
      <button
        type="button"
        class="fc-dialog-modal__close"
        aria-label="Close"
        data-hs-overlay="#join-event-modal"
      >
        <span aria-hidden="true">&times;</span>
      </button>

      <div class="fc-dialog-modal__body">
        <div class="fc-dialog-modal__icon fc-dialog-modal__icon--calendar" aria-hidden="true">
          <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="14" width="36" height="34" rx="5" fill="#F85705"/>
            <rect x="10" y="22" width="36" height="26" rx="2" fill="#F85705"/>
            <rect x="16" y="8" width="5" height="12" rx="2.5" fill="#F85705"/>
            <rect x="35" y="8" width="5" height="12" rx="2.5" fill="#F85705"/>
            <path d="M28 38.5C23.5 34.5 20 31.5 20 28.2C20 25.4 22.2 23.5 24.6 23.5C26.2 23.5 27.6 24.3 28.5 25.5C29.4 24.3 30.8 23.5 32.4 23.5C34.8 23.5 37 25.4 37 28.2C37 31.5 33.5 34.5 28 38.5Z" fill="#fff"/>
          </svg>
        </div>

        <h2 id="join-event-modal-title" class="fc-dialog-modal__title">Join Event</h2>

        <p class="fc-dialog-modal__lead">
          Input An Event Code Below To Unlock Couples Who Attended That Event:
        </p>

        <label for="event-code" class="sr-only">Event code</label>
        <input
          type="text"
          id="event-code"
          class="fc-dialog-modal__input"
          placeholder="Add Event Code"
          autocomplete="off"
        />

        <div class="fc-dialog-modal__actions">
          <button
            type="button"
            class="fc-dialog-modal__btn fc-dialog-modal__btn--cancel"
            data-hs-overlay="#join-event-modal"
          >
            Cancel
          </button>
          <button
            type="button"
            id="join-event-btn"
            class="fc-dialog-modal__btn fc-dialog-modal__btn--primary"
          >
            Join Event
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
