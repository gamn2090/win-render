@props(['vendor'])

@php
    $vendorType = $vendor->getType();
    $rating = (float) $vendor->googleRating();
    $ratingDisplay = $rating > 0 ? number_format($rating, 1) : '—';
    $locationLine = trim($vendor->location ?? '');
    if ($vendor->service_radius) {
        $locationLine .= ($locationLine ? ' · ' : '') . $vendor->service_radius . ' mi';
    }
@endphp

<article class="vd-vendor-card">
    <a href="{{ url('/vendor/profile/' . $vendor->uuid) }}" class="vd-vendor-card__image-link" tabindex="-1" aria-hidden="true">
        @if($vendor->coverImageUrl())
            <img class="vd-vendor-card__image" src="{{ $vendor->coverImageUrl() }}" alt="" />
        @else
            <div class="vd-vendor-card__image win-cover-placeholder"></div>
        @endif
    </a>
    <div class="vd-vendor-card__body">
        <h3 class="vd-vendor-card__name">{{ $vendor->business_name }}</h3>

        <div class="vd-vendor-card__meta">
            @if($vendorType)
            <span class="vd-vendor-card__type">
                <img
                    src="{{ asset($vendorType->icon) }}"
                    alt=""
                    class="vd-vendor-card__type-icon"
                    width="18"
                    height="18"
                />
                <span class="vd-vendor-card__type-label">{{ $vendorType->type }}</span>
            </span>
            @endif
            <span class="vd-vendor-card__rating">
                <svg class="vd-vendor-card__stars" width="39" height="21" viewBox="0 0 39 21" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M7.5 2.5L9.2 6.9L13.9 7.2L10.3 10.1L11.4 14.7L7.5 12.2L3.6 14.7L4.7 10.1L1.1 7.2L5.8 6.9L7.5 2.5Z" fill="#FB962F"/>
                    <path d="M19.5 2.5L21.2 6.9L25.9 7.2L22.3 10.1L23.4 14.7L19.5 12.2L15.6 14.7L16.7 10.1L13.1 7.2L17.8 6.9L19.5 2.5Z" fill="#FB962F"/>
                    <path d="M31.5 2.5L33.2 6.9L37.9 7.2L34.3 10.1L35.4 14.7L31.5 12.2L27.6 14.7L28.7 10.1L25.1 7.2L29.8 6.9L31.5 2.5Z" fill="#FB962F"/>
                </svg>
                <span class="vd-vendor-card__rating-num">{{ $ratingDisplay }}</span>
            </span>
        </div>

        @if($locationLine)
        <p class="vd-vendor-card__location">{{ $locationLine }}</p>
        @endif

        <div class="vd-vendor-card__actions">
            <button
                type="button"
                class="vd-vendor-card__btn vd-vendor-card__btn--message chat-window-btn"
                data-picture-url="{{ asset('/storage/images/' . $vendor->image) }}"
                data-name="{{ $vendor->business_name }}"
                data-uuid="{{ $vendor->uuid }}"
                data-user-type="{{ $vendor->userType() }}"
            >
                Message
            </button>
            <a href="{{ url('/vendor/profile/' . $vendor->uuid) }}" class="vd-vendor-card__btn vd-vendor-card__btn--storefront">
                Storefront
            </a>
        </div>
    </div>
</article>
