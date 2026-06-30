@php
    $coupleIconBase = '/images/new_view_Join_Us/join_as_couple';
    $typesByName = collect($data['types'])->keyBy('type');
    // Orden e iconos según icons_paso2_couple.png
    $orderedCoupleVendors = [
        ['icon' => 'venue.png', 'type' => 'Venue'],
        ['icon' => 'wedding_planer.png', 'type' => 'Wedding Planner'],
        ['icon' => 'photographer.png', 'type' => 'Photographer'],
        ['icon' => 'Bar Services.png', 'type' => 'Bar Services'],
        ['icon' => 'jewelers.png', 'type' => 'Jewelers'],
        ['icon' => 'Rentals.png', 'type' => 'Rentals & Decor'],
        ['icon' => 'Stationery.png', 'type' => 'Invitations / Stationery'],
        ['icon' => 'dj.png', 'type' => 'Live Bands'],
        ['icon' => 'Photo Booth.png', 'type' => 'Photo Booth'],
        ['icon' => 'officiant.png', 'type' => 'Officiant'],
        ['icon' => 'transportation.png', 'type' => 'Transportation'],
        ['icon' => 'hair 1.png', 'type' => 'Hair & Makeup'],
        ['icon' => 'dj.png', 'type' => 'DJ'],
        ['icon' => 'Videographer.png', 'type' => 'Videographer'],
        ['icon' => 'florist.png', 'type' => 'Florist'],
        ['icon' => 'bridal.png', 'type' => 'Bridal Shops / Tux Rental'],
        ['icon' => 'string.png', 'type' => 'String Ensembles'],
        ['icon' => 'Videographer.png', 'type' => 'Content Creators'],
        ['icon' => 'Painters.png', 'type' => 'Live Artists / Painters'],
        ['icon' => 'caterer.png', 'type' => 'Caterer'],
        ['icon' => 'Cake.png', 'type' => 'Bakery / Cake'],
        ['icon' => 'wedding_planer.png', 'type' => 'Other'],
    ];
@endphp
<div data-hs-stepper-content-item='{"index": 2}' style="display: none;">
    <div class="join-us-vendor-grid">
        <x-join-us-vendor-left variant="couple-step2" />
        <div class="join-us-vendor-form-col join-us-vendor-form-col--couple-step2">
            <div class="join-us-vendor-form-inner join-us-couple-form-inner join-us-vendor-form-inner--step2 join-us-couple-form-inner--step2">
                <x-join-us-couple-stepper-nav :active-step="2" />

                <h1 class="join-us-vendor-form-title">Select all the vendors you are interest to connect with</h1>
                <p class="join-us-vendor-form-subtitle">Get matched with an in-network provider to unlock exclusive vendor discounts.</p>

                <ul class="join-us-vendor-errors" id="formErrorsStep2"></ul>

                <div class="join-us-couple-select-all-row">
                    <div class="join-us-couple-select-all">
                        <input id="checkAll" name="select-all" type="checkbox" class="join-us-couple-vendor-checkbox" aria-describedby="checkAll-description">
                        <label for="checkAll" class="join-us-couple-select-all-label">Select All</label>
                    </div>
                    <p class="join-us-couple-vendor-hint" id="checkAll-description">You can update your vendor preferences anytime.</p>
                </div>

                <div class="join-us-couple-vendor-types">
                    @foreach($orderedCoupleVendors as $slot)
                        @php $vendorType = $typesByName->get($slot['type']); @endphp
                        @if($vendorType)
                            <label
                                for="vt-{{ $vendorType->id }}"
                                class="join-us-couple-vendor-card"
                            >
                                <input
                                    id="vt-{{ $vendorType->id }}"
                                    name="vt-{{ $vendorType->id }}"
                                    value="{{ $vendorType->id }}"
                                    type="checkbox"
                                    class="join-us-couple-vendor-card-input"
                                >
                                <img
                                    src="{{ $coupleIconBase }}/{{ rawurlencode($slot['icon']) }}"
                                    alt=""
                                    class="join-us-couple-vendor-card-icon"
                                >
                                <span class="join-us-couple-vendor-card-label">{{ $vendorType->type }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>

                <div class="join-us-couple-contact-toggle">
                    <span class="join-us-couple-contact-text">Would You Like Our Vendors To Contact You Directly?</span>
                    <div class="join-us-couple-contact-switch">
                        <label for="hs-allow-contact" class="join-us-couple-contact-switch-label">No</label>
                        <input type="checkbox" id="hs-allow-contact" class="join-us-couple-contact-switch-input" checked>
                        <label for="hs-allow-contact" class="join-us-couple-contact-switch-label">Yes</label>
                    </div>
                </div>

                <div class="join-us-vendor-field join-us-vendor-field--policies">
                    <div class="join-us-vendor-policy-row">
                        <input id="hs-checkbox-tos" name="hs-checkbox-tos" type="checkbox">
                        <label for="hs-checkbox-tos" class="join-us-vendor-policy-label">
                            <span class="join-us-vendor-policy-title">Terms Of Service</span>
                            <a href="/policy/Wedding%20Insiders%20Network%20Terms%20of%20Use%20and%20Privacy%20Policy.pdf" target="_blank" rel="noopener">View and accept our Terms of Service</a>
                        </label>
                    </div>
                </div>

                <button id="btn-finish-setup" type="button" class="join-us-vendor-btn-primary">
                    Submit
                    <i id="submit-spinner" class="fas fa-circle-notch animate-spin text-lg ms-2" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>
</div>
