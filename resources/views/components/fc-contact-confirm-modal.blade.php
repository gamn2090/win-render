<div
  id="fc-contact-confirm-modal"
  class="fc-dialog-modal hs-overlay hs-overlay-open:pointer-events-auto hidden fixed inset-0 z-[90] overflow-x-hidden overflow-y-auto pointer-events-none"
  role="dialog"
  aria-modal="true"
  aria-labelledby="fc-contact-confirm-modal-title"
  tabindex="-1"
>
  <div
    class="fc-dialog-modal__backdrop hs-overlay-backdrop-open:opacity-100 opacity-0 transition-opacity duration-300"
    data-hs-overlay="#fc-contact-confirm-modal"
    aria-hidden="true"
  ></div>

  <div class="fc-dialog-modal__wrap hs-overlay-open:mt-0 hs-overlay-open:opacity-100 mt-6 opacity-0 transition-all duration-300 flex min-h-full items-center justify-center px-4 py-8">
    <div class="fc-dialog-modal__panel pointer-events-auto relative w-full max-w-[420px]">
      <button
        type="button"
        class="fc-dialog-modal__close"
        aria-label="Close"
        data-hs-overlay="#fc-contact-confirm-modal"
      >
        <span aria-hidden="true">&times;</span>
      </button>

      <div class="fc-dialog-modal__body">
        <div class="fc-dialog-modal__icon fc-dialog-modal__icon--question" aria-hidden="true">
          <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="28" cy="28" r="28" fill="#F85705"/>
            <path d="M27.2 40.5H30.4V37.3H27.2V40.5ZM28 17.5C23.4 17.5 20 20.4 20 24.4H23.4C23.4 22.3 25.2 20.7 28 20.7C30.6 20.7 32.6 22.1 32.6 24.5C32.6 26.7 30.9 27.8 29.2 29.2C27.2 30.8 26.4 32.4 26.5 35.1H29.6C29.5 33.2 30.1 32.2 31.8 30.8C33.8 29.2 36 27.4 36 24.3C36 20.1 32.4 17.5 28 17.5Z" fill="#fff"/>
          </svg>
        </div>

        <h2 id="fc-contact-confirm-modal-title" class="fc-dialog-modal__title">Are you sure?</h2>

        <p class="fc-dialog-modal__lead">
          Would You Like To Contact This Client?
        </p>
        <p class="fc-dialog-modal__lead fc-dialog-modal__lead--strong">
          This Will Use 1 Contact Credit.
        </p>

        <div class="fc-dialog-modal__actions">
          <button
            type="button"
            class="fc-dialog-modal__btn fc-dialog-modal__btn--cancel"
            data-hs-overlay="#fc-contact-confirm-modal"
          >
            Cancel
          </button>
          <button
            type="button"
            id="fc-contact-confirm-btn"
            class="fc-dialog-modal__btn fc-dialog-modal__btn--primary"
          >
            Contact Couple
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
