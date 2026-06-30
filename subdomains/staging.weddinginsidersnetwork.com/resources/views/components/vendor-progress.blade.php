<div class="relative size-40 rotate-[274deg]">
    <svg class="size-full absolute z-50" style="overflow: visible" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
        <circle cx="25" cy="25" r="21" fill="none" stroke="currentColor" class="stroke-grey-50 text-white" stroke-width="6"></circle>

        <circle id="inquiry" @class(['stroke-current', 'text-win-blue', 'hidden' => ($stage < 1)]) cx="25" cy="25" r="21" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="41 100" stroke-dashoffset="0"></circle>
        <circle @class(['stroke-current', 'text-win-blue', 'hidden' => ($stage < 2)]) cx="25" cy="25" r="21" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="41 100" stroke-dashoffset="-44"></circle>
        <circle @class(['stroke-current', 'text-win-blue', 'hidden' => ($stage < 3)]) cx="25" cy="25" r="21" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="41 100" stroke-dashoffset="-88"></circle>
        <g  style="transform: rotate(210deg); transform-origin: 50% 50%;">
        <path id="circlePath-{{ $vendorType->id }}-{{ $uid }}" fill="none" stroke-width="4" d="
            M 6, 25
            a 19,19 0 1,1 38,0
            a 19,19 0 1,1 -38,0
            "/>
        <path id="c-circlePath-{{ $vendorType->id }}-{{ $uid }}" fill="none" stroke-width="6" d="
        M 2, 25
        a 23,23 0 1,0 46,0
        a 23,23 0 1,0 -46,0
        "/>
        <text id="text" font-family="Poppins" font-size="5" font-weight="bold" @class(['hidden' => ($stage < 1)])>
            <textPath href="#circlePath-{{ $vendorType->id }}-{{ $uid }}" startOffset="0">Inquiry</textPath>
        </text>
        <text id="text" font-family="Poppins" font-size="5" font-weight="bold" letter-spacing=".45" @class(['hidden' => ($stage < 2)])>
            <textPath href="#c-circlePath-{{ $vendorType->id }}-{{ $uid }}" startOffset="66">Consultation</textPath>
        </text>
        <text id="text" font-family="Poppins" font-size="5" font-weight="bold" @class(['hidden' => ($stage < 3)])>
            <textPath href="#circlePath-{{ $vendorType->id }}-{{ $uid }}" startOffset="78">Booked</textPath>
        </text>
        </g>

    </svg>
    <div class="absolute top-1/2 start-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center rotate-[-274deg] p-2 max-w-28 lg:max-w-40">
        <p class="font-bold text-balance break-normal text-sm">{{ $vendorType->type }}</p>
    </div>
</div>