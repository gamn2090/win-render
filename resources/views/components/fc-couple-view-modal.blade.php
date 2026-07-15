@props([
  'couple',
  'vendorTypes',
  'fcAssets',
])

@php
  $vendorProgress = $couple->requestedVendorProgress();
  $inquired = [];
  $consulted = [];
  $booked = [];

  foreach ($couple->inquiries as $vendorTypeInquiry) {
    $vendorTypeModel = $vendorTypes->firstWhere('id', $vendorTypeInquiry->vendor_type);
    if (! $vendorTypeModel) {
      continue;
    }

    $status = 0;
    foreach ($vendorProgress as $prog) {
      $progVendorType = data_get($prog, 'vendor_type');
      $progStatus = (int) data_get($prog, 'status', 0);
      if ((int) $progVendorType === (int) $vendorTypeModel->id) {
        $status = max($status, $progStatus);
      }
    }

    $entry = ['type' => $vendorTypeModel];

    if ($status >= 3) {
      $booked[] = $entry;
    } elseif ($status >= 2) {
      $consulted[] = $entry;
    } else {
      $inquired[] = $entry;
    }
  }

  $iconUrl = static function ($vendorType) {
    $icon = $vendorType->icon ?? '';
    if ($icon === '') {
      return '';
    }
    if (str_starts_with($icon, 'http://') || str_starts_with($icon, 'https://')) {
      return $icon;
    }

    return asset(ltrim($icon, '/'));
  };
@endphp

<div
  id="fc-view-modal-{{ $couple->id }}"
  class="fc-view-modal hs-overlay hs-overlay-open:pointer-events-auto hidden fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
  role="dialog"
  aria-modal="true"
  aria-labelledby="fc-view-modal-title-{{ $couple->id }}"
  tabindex="-1"
>
  <div
    class="fc-view-modal__backdrop hs-overlay-backdrop-open:opacity-100 opacity-0 transition-opacity duration-300"
    data-hs-overlay="#fc-view-modal-{{ $couple->id }}"
    aria-hidden="true"
  ></div>

  <div class="fc-view-modal__wrap hs-overlay-open:mt-0 hs-overlay-open:opacity-100 mt-6 opacity-0 transition-all duration-300 flex min-h-full items-start justify-center px-4 py-8 sm:py-10">
    <div class="fc-view-modal__panel pointer-events-auto relative w-full max-w-[620px]">
      <button
        type="button"
        class="fc-view-modal__close"
        aria-label="Close"
        data-hs-overlay="#fc-view-modal-{{ $couple->id }}"
      >
        <span aria-hidden="true">&times;</span>
      </button>

      <div class="fc-view-modal__body">
        <x-avatar :model="$couple" class="fc-view-modal__avatar" />

        <h2 id="fc-view-modal-title-{{ $couple->id }}" class="fc-view-modal__names">
          <span>{{ $couple->first_name }}</span>
          <img src="{{ $fcAssets }}/mdi_heart.png" alt="" class="fc-view-modal__heart" width="18" height="18" />
          <span>{{ $couple->fiance_first_name }}</span>
        </h2>

        <div class="fc-view-modal__actions">
          <button
            type="button"
            class="fc-view-modal__btn fc-view-modal__btn--message inquireClientButton"
            data-client-uuid="{{ $couple->uuid }}"
          >
            Message Couple
          </button>
          <a href="{{ route('vendor.couple.profile', ['id' => $couple->uuid]) }}" class="fc-view-modal__btn fc-view-modal__btn--profile">
            View Profile
          </a>
        </div>

        @if($couple->bio)
          <p class="fc-view-modal__bio">{{ $couple->bio }}</p>
        @else
          <p class="fc-view-modal__bio fc-view-modal__bio--empty">
            {{ $couple->first_name }} &amp; {{ $couple->fiance_first_name }} Haven't Written A Bio Yet!
          </p>
        @endif

        <p class="fc-view-modal__vendors-title">Vendors they are searching for:</p>

        @if(count($inquired) > 0)
          <section class="fc-view-modal__section">
            <h3 class="fc-view-modal__section-title">Inquired Vendors</h3>
            <ul class="fc-view-modal__vendor-grid">
              @foreach($inquired as $item)
                <li class="fc-view-modal__vendor-item">
                  <div class="fc-view-modal__vendor-head">
                    @if($iconUrl($item['type']))
                      <img src="{{ $iconUrl($item['type']) }}" alt="" class="fc-view-modal__vendor-icon" />
                    @endif
                    <span class="fc-view-modal__vendor-name">{{ $item['type']->type }}</span>
                  </div>
                  <div class="fc-stage-bar fc-stage-bar--single" aria-label="Inquiry">
                    <span class="fc-stage-bar__pill fc-stage-bar__pill--inquiry">Inquiry</span>
                  </div>
                </li>
              @endforeach
            </ul>
          </section>
        @endif

        @if(count($consulted) > 0)
          <section class="fc-view-modal__section">
            <h3 class="fc-view-modal__section-title">Consulted Vendors</h3>
            <ul class="fc-view-modal__vendor-grid">
              @foreach($consulted as $item)
                <li class="fc-view-modal__vendor-item">
                  <div class="fc-view-modal__vendor-head">
                    @if($iconUrl($item['type']))
                      <img src="{{ $iconUrl($item['type']) }}" alt="" class="fc-view-modal__vendor-icon" />
                    @endif
                    <span class="fc-view-modal__vendor-name">{{ $item['type']->type }}</span>
                  </div>
                  <div class="fc-stage-bar fc-stage-bar--double" aria-label="Inquiry and consultation">
                    <span class="fc-stage-bar__pill fc-stage-bar__pill--inquiry">Inquiry</span>
                    <span class="fc-stage-bar__pill fc-stage-bar__pill--consult">consultation</span>
                  </div>
                </li>
              @endforeach
            </ul>
          </section>
        @endif

        @if(count($booked) > 0)
          <section class="fc-view-modal__section">
            <h3 class="fc-view-modal__section-title">Booked Vendors</h3>
            <ul class="fc-view-modal__vendor-grid">
              @foreach($booked as $item)
                <li class="fc-view-modal__vendor-item">
                  <div class="fc-view-modal__vendor-head">
                    @if($iconUrl($item['type']))
                      <img src="{{ $iconUrl($item['type']) }}" alt="" class="fc-view-modal__vendor-icon" />
                    @endif
                    <span class="fc-view-modal__vendor-name">{{ $item['type']->type }}</span>
                  </div>
                  <div class="fc-stage-bar fc-stage-bar--triple" aria-label="Inquiry, consultation and booked">
                    <span class="fc-stage-bar__pill fc-stage-bar__pill--inquiry">Inquiry</span>
                    <span class="fc-stage-bar__pill fc-stage-bar__pill--consult">consultation</span>
                    <span class="fc-stage-bar__pill fc-stage-bar__pill--booked">Booked</span>
                  </div>
                </li>
              @endforeach
            </ul>
          </section>
        @endif
      </div>
    </div>
  </div>
</div>
