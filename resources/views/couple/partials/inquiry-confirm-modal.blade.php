<div id="vd-inquiry-modal" class="vd-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="vd-inquiry-title">
  <div class="vd-modal">
    <button type="button" class="vd-modal__close" data-modal-close aria-label="Close">&times;</button>

    <div class="vd-modal__body" data-inquiry-step="1">
      <div class="vd-modal__icon" id="vd-inquiry-availability-icon" aria-hidden="true">?</div>
      <h2 id="vd-inquiry-title" class="vd-modal__title">Check your wedding date</h2>
      <p class="vd-modal__subtitle" id="vd-inquiry-availability-text">Checking this vendor's availability&hellip;</p>
    </div>

    <div class="vd-modal__body" data-inquiry-step="2" hidden>
      <div class="vd-modal__icon" aria-hidden="true">$</div>
      <h2 class="vd-modal__title">Vendor pricing</h2>
      <p class="vd-modal__subtitle">This vendor's average package price is<br /><strong>{{ $vendor->preferredPricing() }}</strong><br />Would you like to continue?</p>
    </div>

    <div class="vd-modal__body" data-inquiry-step="3" hidden>
      <div class="vd-modal__icon" aria-hidden="true">&#10003;</div>
      <h2 class="vd-modal__title">Ready to send?</h2>
      <p class="vd-modal__subtitle">We'll let {{ $vendor->business_name }} know you're interested in their services for your wedding.</p>
    </div>

    <div class="vd-modal__actions">
      <button type="button" class="vd-modal__btn vd-modal__btn--cancel" id="vd-inquiry-back" hidden>Back</button>
      <button type="button" class="vd-modal__btn vd-modal__btn--cancel" data-modal-close>Cancel</button>
      <button type="button" id="vd-inquiry-next" class="vd-modal__btn vd-modal__btn--confirm">Continue</button>
    </div>
  </div>
</div>
