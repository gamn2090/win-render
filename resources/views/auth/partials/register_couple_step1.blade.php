@php
    $eyeIcon = '/images/new_view_Join_Us/eye.png';
@endphp
<div data-hs-stepper-content-item='{"index": 1}'>
    <div class="join-us-vendor-grid">
        <x-join-us-vendor-left variant="couple" />
        <div class="join-us-vendor-form-col">
            <div class="join-us-vendor-form-inner join-us-couple-form-inner">
                <x-join-us-couple-stepper-nav :active-step="1" />

                <h1 class="join-us-vendor-form-title">Welcome to Wedding Insiders Network</h1>
                <p class="join-us-vendor-form-subtitle">Let's set up your couple profile so we can match you with the right vendors.</p>

                <ul class="join-us-vendor-errors" id="formErrors"></ul>
                @if($errors->any())
                    <ul class="join-us-vendor-errors">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="join-us-couple-form-row">
                    <div class="join-us-couple-form-col">
                        <span class="join-us-vendor-label join-us-couple-label">You <span class="req">*</span></span>
                        <div class="join-us-vendor-name-row">
                            <input type="text" id="hs-first-name" class="join-us-vendor-input" placeholder="First Name" autocomplete="given-name">
                            <input type="text" id="hs-last-name" class="join-us-vendor-input" placeholder="Last Name" autocomplete="family-name">
                        </div>
                    </div>
                    <div class="join-us-couple-form-col">
                        <span class="join-us-vendor-label join-us-couple-label">Your Partner <span class="req">*</span></span>
                        <div class="join-us-vendor-name-row">
                            <input type="text" id="hs-fiance-first-name" class="join-us-vendor-input" placeholder="First Name" autocomplete="given-name">
                            <input type="text" id="hs-fiance-last-name" class="join-us-vendor-input" placeholder="Last Name" autocomplete="family-name">
                        </div>
                    </div>
                </div>

                <div class="join-us-couple-form-row">
                    <div class="join-us-couple-form-col">
                        <label for="client-wedding-date" class="join-us-vendor-label join-us-couple-label">Wedding Date</label>
                        <input id="client-wedding-date" type="text" class="join-us-vendor-input" placeholder="MM/DD/YYYY" autocomplete="off">
                    </div>
                    <div class="join-us-couple-form-col">
                        <label for="client-venue" class="join-us-vendor-label join-us-couple-label">Wedding Location</label>
                        <div class="join-us-vendor-field-control join-us-vendor-field-control--select">
                            <input id="client-venue" type="text" class="join-us-vendor-input" placeholder="City, State" autocomplete="address-level2">
                        </div>
                    </div>
                </div>

                <div class="join-us-couple-form-row">
                    <div class="join-us-couple-form-col">
                        <label for="client-wedding-venue" class="join-us-vendor-label join-us-couple-label">Wedding Venue</label>
                        <input id="client-wedding-venue" type="text" class="join-us-vendor-input" placeholder="E.G., Willowdale Estate">
                    </div>
                    <div class="join-us-couple-form-col">
                        <label for="client-wedding-venue-location" class="join-us-vendor-label join-us-couple-label">Wedding Venue Location</label>
                        <div class="join-us-vendor-field-control join-us-vendor-field-control--select">
                            <input id="client-wedding-venue-location" type="text" class="join-us-vendor-input" placeholder="City, State" autocomplete="address-level2">
                        </div>
                    </div>
                </div>

                <div class="join-us-couple-form-row join-us-couple-form-row--full">
                    <div class="join-us-couple-form-col">
                        <label for="new-email" class="join-us-vendor-label join-us-couple-label">Email <span class="req">*</span></label>
                        <input name="new-email" id="new-email" type="email" class="join-us-vendor-input" placeholder="email@example.com" autocomplete="email">
                    </div>
                </div>

                <div class="join-us-couple-form-row join-us-couple-password-row">
                    <div class="join-us-couple-form-col">
                        <label for="password_first" class="join-us-vendor-label join-us-couple-label join-us-vendor-label--with-eye">
                            Password <span class="req">*</span>
                            <button type="button" id="show-client-register-password-toggle" class="join-us-vendor-eye-btn" aria-label="Show password">
                                <img src="{{ $eyeIcon }}" alt="">
                            </button>
                        </label>
                        <input id="password_first" class="join-us-vendor-input" type="password" name="password" required autocomplete="new-password" placeholder="Create A Password">
                    </div>
                    <div class="join-us-couple-form-col">
                        <label for="password_confirmation" class="join-us-vendor-label join-us-couple-label join-us-vendor-label--with-eye">
                            Confirm Password <span class="req">*</span>
                            <button type="button" id="show-password-confirmation-toggle" class="join-us-vendor-eye-btn" aria-label="Show password">
                                <img src="{{ $eyeIcon }}" alt="">
                            </button>
                        </label>
                        <input id="password_confirmation" class="join-us-vendor-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat Password">
                    </div>
                </div>

                @isset($ref_id)
                    <input id="ref_by" name="ref_by" type="text" value="{{ $ref_id }}" hidden>
                @endisset
                <input id="event" name="event" type="text" value="{{ $event ?? '' }}" hidden>

                <button type="button" class="btn-couple-profile-next join-us-vendor-btn-primary">Sign up for free</button>

                <p class="join-us-vendor-signin-link">Already have an account? <a href="/" data-hs-overlay="#modal-signin">Sign in</a></p>
            </div>
        </div>
    </div>
</div>
