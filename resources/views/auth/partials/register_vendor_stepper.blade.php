@php
    $eyeIcon = '/images/new_view_Join_Us/eye.png';
    $questionIcon = '/images/new_view_Join_Us/question.png';
@endphp
<!-- Stepper -->
<div data-hs-stepper class="join-us-vendor-register">
    {{-- Step 1: Your Account --}}
    <div data-hs-stepper-content-item='{"index": 1}'>
        <div class="join-us-vendor-grid">
            <x-join-us-vendor-left variant="account" />
            <div class="join-us-vendor-form-col">
                <div class="join-us-vendor-form-inner">
                <x-join-us-vendor-stepper-nav :active-step="1" />

                <h1 class="join-us-vendor-form-title">Welcome to Wedding Insiders Network</h1>
                <p class="join-us-vendor-form-subtitle join-us-vendor-form-subtitle--step1">Let's build your vendor profile so you can start connecting with couples looking for<br>your services.</p>

                <ul class="join-us-vendor-errors" id="formErrors"></ul>
                @if($errors->any())
                    <ul class="join-us-vendor-errors">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="join-us-vendor-field">
                    <span class="join-us-vendor-label">Your Name <span class="req">*</span></span>
                    <div class="join-us-vendor-name-row">
                        <input type="text" id="hs-first-name" class="join-us-vendor-input" placeholder="First Name" autocomplete="given-name">
                        <input type="text" id="hs-last-name" class="join-us-vendor-input" placeholder="Last Name" autocomplete="family-name">
                    </div>
                </div>

                <div class="join-us-vendor-field">
                    <label for="new-email" class="join-us-vendor-label">Email <span class="req">*</span></label>
                    <input name="new-email" id="new-email" type="email" class="join-us-vendor-input" placeholder="email@example.com" autocomplete="email">
                </div>

                <div class="join-us-vendor-password-row">
                    <div class="join-us-vendor-field">
                        <label for="password_first" class="join-us-vendor-label join-us-vendor-label--with-eye">
                            Password <span class="req">*</span>
                            <button type="button" id="show-password-toggle" class="join-us-vendor-eye-btn" aria-label="Show password">
                                <img src="{{ $eyeIcon }}" alt="">
                            </button>
                        </label>
                        <input id="password_first" class="join-us-vendor-input" type="password" name="password" required autocomplete="new-password" placeholder="Create A Password">
                    </div>
                    <div class="join-us-vendor-field">
                        <label for="password_confirmation" class="join-us-vendor-label join-us-vendor-label--with-eye">
                            Confirm Password <span class="req">*</span>
                            <button type="button" id="show-password-confirmation-toggle" class="join-us-vendor-eye-btn" aria-label="Show password">
                                <img src="{{ $eyeIcon }}" alt="">
                            </button>
                        </label>
                        <input id="password_confirmation" class="join-us-vendor-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat Password">
                    </div>
                </div>

                <button type="button" class="btn-vendor-account-next join-us-vendor-btn-primary">Sign Up</button>

                <p class="join-us-vendor-signin-link">Already have an account? <a href="/" data-hs-overlay="#modal-signin">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Step 2: Your Business --}}
    <div data-hs-stepper-content-item='{"index": 2}' style="display: none;">
        <div class="join-us-vendor-grid">
            <x-join-us-vendor-left variant="business" />
            <div class="join-us-vendor-form-col">
                <div class="join-us-vendor-form-inner join-us-vendor-form-inner--step2">
                <x-join-us-vendor-stepper-nav :active-step="2" />

                <h1 class="join-us-vendor-form-title">Let's set up your vendor profile</h1>
                <p class="join-us-vendor-form-subtitle">Start connecting with couples looking for your services.</p>

                <div class="join-us-vendor-field">
                    <label for="business-name" class="join-us-vendor-label">Business Name <span class="req">*</span></label>
                    <input id="business-name" class="join-us-vendor-input" type="text" placeholder="">
                </div>

                <div class="join-us-vendor-field">
                    <label for="location" class="join-us-vendor-label">Location <span class="req">*</span></label>
                    <div class="join-us-vendor-field-control join-us-vendor-field-control--select">
                        <input id="location" type="text" class="join-us-vendor-input" placeholder="city, state" autocomplete="address-level2">
                    </div>
                </div>

                <div class="join-us-vendor-field join-us-vendor-field--bio">
                    <label for="user-bio" class="join-us-vendor-label">Tell couples what makes your business unique?</label>
                    <textarea id="user-bio" class="join-us-vendor-textarea" rows="4" placeholder="Describe your style, experience, and what sets you apart. This will appear on your profile."></textarea>
                </div>

                <div class="join-us-vendor-field join-us-vendor-field--avg-price">
                    <label for="avg-price" class="join-us-vendor-label">What is your average package price range?</label>
                    <div class="join-us-vendor-field-control join-us-vendor-field-control--select">
                        <select id="avg-price" name="booking_val" class="join-us-vendor-select" required>
                            <option value="" selected disabled>Choose a value. This helps us match you with the right couples.</option>
                            <option value="1">$500 or less</option>
                            <option value="2">$500-$2,000</option>
                            <option value="3">$2,000-$3,000</option>
                            <option value="4">$3,000-$5,000</option>
                            <option value="5">$5,000-$8,000</option>
                            <option value="6">$8,000-$10,000</option>
                            <option value="7">$12,000 or more</option>
                        </select>
                    </div>
                </div>

                @isset($ref_id)
                    <input id="ref_by" name="ref_by" type="text" value="{{ $ref_id }}" hidden>
                @endisset
                <input id="event" name="event" type="text" value="{{ $event ?? '' }}" hidden>
                <input id="offered_discount" name="offered_discount" type="number" value="0" hidden>

                <div class="join-us-vendor-field">
                    <div class="join-us-vendor-label join-us-vendor-label--inline">
                        Preferred Pricing
                        <img src="{{ $questionIcon }}" alt="" class="join-us-vendor-help-icon" title="Discount offered to in-network couples">
                    </div>
                    <div class="join-us-vendor-pricing-grid">
                        @foreach([0, 50, 75, 100, 150, 200, 250] as $amount)
                            <label for="discount-{{ $amount }}" class="join-us-vendor-pricing-option">
                                <input type="radio" name="discount-val" value="{{ $amount }}" id="discount-{{ $amount }}" @checked($amount === 0)>
                                <span>${{ $amount }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="join-us-vendor-field">
                    <label for="role_select" class="join-us-vendor-label">Select Role <span class="req">*</span></label>
                    <div class="join-us-vendor-field-control join-us-vendor-field-control--select">
                        <select id="role_select" name="role" class="join-us-vendor-select">
                            <option value="" selected disabled>Choose your role</option>
                            @foreach($vendor_types as $type)
                                <option value="{{ $type->id }}">{{ $type->type }}</option>
                            @endforeach
                        </select>
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
                    <div class="join-us-vendor-policy-row">
                        <input id="hs-checkbox-eligibility" name="hs-checkbox-eligibility" type="checkbox">
                        <label for="hs-checkbox-eligibility" class="join-us-vendor-policy-label">
                            <span class="join-us-vendor-policy-title">Vendor Eligibility</span>
                            <a href="/policy/Wedding%20Insiders%20Network%20Vendor%20Eligibility%20Policy.pdf" target="_blank" rel="noopener">View and accept our vendor eligibility guidelines</a>
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

    <div hidden>
        <button id="btn-back" type="button" data-hs-stepper-back-btn>Back</button>
        <button type="button" id="btn-next" data-hs-stepper-next-btn>Next</button>
        <button type="reset" data-hs-stepper-reset-btn style="display: none;">Reset</button>
    </div>
</div>
