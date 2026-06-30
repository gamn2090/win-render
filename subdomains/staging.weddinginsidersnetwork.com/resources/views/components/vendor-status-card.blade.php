<div class="text-center max-w-xs vendor-status-card">
    <div class="absolute">
        <div id="heart" data-vendor-id="{{ $vendor->uuid }}" @class(['heart', 'align-top', 'hover:cursor-pointer', 'is-active' => ($favorited)])>
            @if(!$favorited)
            <i class="far fa-heart text-3xl"></i>
            @else
            <i class="fas fa-heart text-3xl" style="color: #6432C8;"></i>
            @endif
        </div>
        <div class="hover:cursor-pointer rm-pairing" data-vendor-id="{{ $vendor->uuid }}">
            <i class="fa-solid fa-xmark text-3xl"></i>
        </div>
        <div class="mv-booked hover:cursor-pointer" data-vendor-id="{{ $vendor->uuid }}" data-status="{{ $status }}">
            @if($status < 3)
            <span class="text-sm underline">Click here<br>when<br>booked</span>
            @else
            <i class="fa-solid fa-check text-2xl" style="color: #28a745;"></i>
            @endif
        </div>
    </div>
    <div class="relative size-40 rotate-[274deg] mx-auto">
        <svg class="size-full absolute z-50 pointer-events-none" style="overflow: visible" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
            <circle cx="25" cy="25" r="21" fill="none" stroke="currentColor" class="stroke-grey-50 text-white" stroke-width="6"></circle>

            <circle id="inquiry" @class(['stroke-current', 'text-win-blue', 'hidden' => ($status < 1)]) cx="25" cy="25" r="21" fill="none" stroke="currentColor" stroke-width="6" stroke-dasharray="41 100" stroke-dashoffset="0"></circle>
            <circle @class(['stroke-current', 'text-win-blue', 'hidden' => ($status < 2)]) cx="25" cy="25" r="21" fill="none" stroke="currentColor" stroke-width="6" stroke-dasharray="41 100" stroke-dashoffset="-44"></circle>
            <circle @class(['stroke-current', 'text-win-blue', 'hidden' => ($status < 3)]) cx="25" cy="25" r="21" fill="none" stroke="currentColor" stroke-width="6" stroke-dasharray="41 100" stroke-dashoffset="-88"></circle>
            <g  style="transform: rotate(210deg); transform-origin: 50% 50%;">
            <path id="circlePath-{{ $vendor->uuid}}" fill="none" stroke-width="4" d="
                M 6, 25
                a 19,19 0 1,1 38,0
                a 19,19 0 1,1 -38,0
                "/>
            <path id="c-circlePath-{{ $vendor->uuid}}" fill="none" stroke-width="6" d="
            M 3, 25
            a 22,22 0 1,0 44,0
            a 22,22 0 1,0 -44,0
            "/>
            <text id="text" font-family="Poppins" font-size="5" font-weight="bold" @class(['hidden' => ($status < 1)])>
                <textPath href="#circlePath-{{ $vendor->uuid}}" startOffset="0">Inquiry</textPath>
            </text>
            <text id="text" font-family="Poppins" font-size="5" font-weight="bold" letter-spacing=".45" @class(['hidden' => ($status < 2)])>
                <textPath href="#c-circlePath-{{ $vendor->uuid}}" startOffset="63">Consultation</textPath>
            </text>
            <text id="text" font-family="Poppins" font-size="5" font-weight="bold" @class(['hidden' => ($status < 3)])>
                <textPath href="#circlePath-{{ $vendor->uuid}}" startOffset="78">Booked</textPath>
            </text>
            </g>

        </svg>
        <div class="absolute rotate-[-274deg] size-full pointer-events-none">
            <a href="/vendor/profile/{{ $vendor->uuid }}" class="block pointer-events-auto">
                <img loading="lazy" src="{{ asset('/storage/images/'. $vendor->image) }}" class="absolute top-1/2 start-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full size-3/4 align-middle absolute">
            </a>
        </div>
    </div>
    <a href="/vendor/profile/{{ $vendor->uuid }}" class="font-semibold break-words text-balance">{{ $vendor->business_name }}</a>
</div>
