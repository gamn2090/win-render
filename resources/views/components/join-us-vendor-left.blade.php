@props(['variant' => 'account'])

@php
    $assetBase = '/images/new_view_Join_Us';
    $innerVariant = in_array($variant, ['business', 'couple', 'couple-step2'], true) ? 'business' : $variant;
@endphp
<aside class="join-us-vendor-left join-us-vendor-left--{{ $variant }}">
    <div class="join-us-vendor-left-inner join-us-vendor-left-inner--{{ $innerVariant }}">
        <div class="join-us-vendor-hero-col">
            <img
                src="{{ $assetBase }}/couple.png"
                alt="Couple"
                class="join-us-vendor-hero-img"
            >
        </div>
        <div class="join-us-vendor-left-copy">
            @if ($variant === 'account')
                <div class="join-us-vendor-badge join-us-vendor-badge--mockup-tilt">Start Booking More Clients</div>
                <h2 class="join-us-vendor-headline">
                    <span class="join-us-vendor-headline-line1">Join hundreds of vendors</span><br>already connecting with<br>couples
                </h2>
                <div class="join-us-vendor-features-wrap" aria-hidden="false">
                    <div class="join-us-vendor-features-gutter" aria-hidden="true"></div>
                    <ul class="join-us-vendor-features">
                        <li>
                            <img src="{{ $assetBase }}/splash.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Get Discovered</p>
                                <p class="join-us-vendor-feature-desc">Connect with couples actively<br>planning their wedding</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/stars.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Get More Bookings</p>
                                <p class="join-us-vendor-feature-desc">Receive inquiries from the right<br>clients</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/coments.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Build Your Presence</p>
                                <p class="join-us-vendor-feature-desc">Create a profile that attracts the<br>right couples</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/Vector.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Link with Partners</p>
                                <p class="join-us-vendor-feature-desc">Connect with preferred vendor<br>friends to gain access to more<br>bookings</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="join-us-vendor-community">
                    <img src="{{ $assetBase }}/vsb.png" alt="" class="join-us-vendor-community-icons">
                    <p class="join-us-vendor-community-text">Join a growing community of<br>3,400+ couples on <strong>WIN</strong>, with<br>1,500+ vendors ready to make<br>every wedding vision happen.</p>
                </div>
            @elseif ($variant === 'couple-step2')
                <div class="join-us-vendor-badge join-us-vendor-badge--mockup-tilt">Free Every Step of the Way</div>
                <h2 class="join-us-vendor-headline join-us-vendor-headline--step2">
                    Start Building<br>Your Dream Team
                </h2>
                <div class="join-us-vendor-features-wrap">
                    <ul class="join-us-vendor-features">
                        <li>
                            <img src="{{ $assetBase }}/splash.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Smart Matching</p>
                                <p class="join-us-vendor-feature-desc">We'll prioritize vendors that best fit your</p>
                                <p class="join-us-vendor-feature-desc">preferences and availability.</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/doble.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Unlock exclusive vendor discounts</p>
                                <p class="join-us-vendor-feature-desc">and start connecting instantly.</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/start.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Ratings &amp; Reviews</p>
                                <p class="join-us-vendor-feature-desc">Browse vendors with verified ratings and</p>
                                <p class="join-us-vendor-feature-desc">reviews</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/coments.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Direct &amp; Easy Connection</p>
                                <p class="join-us-vendor-feature-desc">Connect and communicate with</p>
                                <p class="join-us-vendor-feature-desc">vendors in just a few clicks.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="join-us-vendor-community">
                    <img src="{{ $assetBase }}/vsb.png" alt="" class="join-us-vendor-community-icons">
                    <p class="join-us-vendor-community-text">200+ vetted and local wedding vendors you can connect with</p>
                </div>
            @elseif ($variant === 'couple')
                <div class="join-us-vendor-badge join-us-vendor-badge--mockup-tilt">Plan Your Wedding for Free</div>
                <h2 class="join-us-vendor-headline join-us-vendor-headline--step2">
                    Find wedding vendors<br>who will treat your day<br>like the only one that<br>matters.
                </h2>
                <div class="join-us-vendor-features-wrap">
                    <ul class="join-us-vendor-features">
                        <li>
                            <img src="{{ $assetBase }}/escudo.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Verified Reviews</p>
                                <p class="join-us-vendor-feature-desc">from real couples in your area</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/doble.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Exclusive discounts</p>
                                <p class="join-us-vendor-feature-desc">for WIN couples only</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/persons.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">2-minute signup</p>
                                <p class="join-us-vendor-feature-desc">Start finding vendors instantly</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="join-us-vendor-community">
                    <img src="{{ $assetBase }}/vsb.png" alt="" class="join-us-vendor-community-icons">
                    <p class="join-us-vendor-community-text">200+ vetted and local wedding vendors you can connect with</p>
                </div>
            @else
                <div class="join-us-vendor-badge join-us-vendor-badge--mockup-tilt">Turn Leads into Bookings</div>
                <h2 class="join-us-vendor-headline join-us-vendor-headline--step2">Get discovered by<br>couples actively planning<br>their wedding</h2>
                <div class="join-us-vendor-features-wrap">
                    <ul class="join-us-vendor-features">
                        <li>
                            <img src="{{ $assetBase }}/ri_diamond-line.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Stand Out</p>
                                <p class="join-us-vendor-feature-desc">Showcase what makes your<br>business unique</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/ri_target-fill.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Grow with Ease</p>
                                <p class="join-us-vendor-feature-desc">Manage inquiries and bookings in<br>one place</p>
                            </div>
                        </li>
                        <li>
                            <img src="{{ $assetBase }}/mdi_deal-outline.png" alt="" class="join-us-vendor-feature-icon">
                            <div>
                                <p class="join-us-vendor-feature-title">Match with Intent</p>
                                <p class="join-us-vendor-feature-desc">Get connected with couples that fit<br>your style, budget and availability.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="join-us-vendor-community">
                    <img src="{{ $assetBase }}/vsb.png" alt="" class="join-us-vendor-community-icons">
                    <p class="join-us-vendor-community-text">1,500+ vendors on <span class="join-us-vendor-win-mark">WIN</span> helps<br>couple to have their dream<br>wedding</p>
                </div>
            @endif
        </div>
    </div>
</aside>
