<div class="max-w-sm rounded overflow-y-visible mb-2 flex flex-col">
    <div class="w-full px-4">
        @auth('web')
        @php
            $favorited = Auth::user()->hasFavorite($vendor->id);
        @endphp
        <div id="heart" data-vendor-id="{{ $vendor->uuid }}" @class(['heart', 'align-top', 'relative', 'text-right', 'z-20', 'hover:cursor-pointer', 'is-active' => ($favorited)])>
            @if(!$favorited)
            <i class="far fa-heart text-3xl ml-auto"></i>
            @else
            <i class="fas fa-heart text-3xl ml-auto" style="color: #6432C8;"></i>
            @endif
        </div>
        @endauth
        @if($vendor->coverImageUrl())
          <img alt="..." src="{{ $vendor->coverImageUrl() }}" class="rounded-full h-16 w-16 md:h-32 md:w-32 object-cover align-middle border-none mx-auto relative z-10">
        @else
          <div class="rounded-full h-16 w-16 md:h-32 md:w-32 mx-auto relative z-10 win-cover-placeholder"></div>
        @endif
    </div>
    <div class="bg-win-light rounded-t-2xl mt-[-15%] pt-[15%] relative z-0 text-center px-4 flex-grow">
        <div class="text-center mt-2">
            <div class="md:grid md:grid-cols-2">
                <div>
                    <p>{{ $vendor->location }}</p>
                    <p class="mt-1">@isset($vendor->service_radius) {{ $vendor->service_radius }} mi @endisset</p>
                </div>
                <div>
                    <span class="font-semibold">
                        <svg class="flex-shrink-0 inline size-5 text-yellow-400 -mt-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                        </svg> @empty($vendor->googleRating()) None @endempty {{ $vendor->googleRating() }}
                    </span>
                    <div class="my-1 space-x-.5">
                        
                        @foreach($vendor->badges() as $badge)
                        <a class="hs-tooltip inline-flex justify-center items-center size-6 border-2 border-win-purple rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                            <img src="/images/{{ $badge->icon }}" class="size-3 md:size-4" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                            <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                            {{ $badge->name }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="headline-small my-.5">{{ $vendor->business_name }}</div>
    </div>
    <div class="px-6 pt-auto w-full text-center self-end text-bottom pb-6 bg-win-light rounded-b-2xl">
        @if(Auth::guard('web')->check() && Auth::user()->isInNetwork())
            <div class="py-1 px-2 mx-auto mt-2 inline-flex items-center justify-center text-sm font-medium bg-teal-100 text-teal-800 rounded-full">
                <svg class="flex-shrink-0 size-4 mr-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                <span> ${{ $vendor->discount }} Discount</span>
            </div>
        @endif
        <a class="button-text messageVendorBtn uppercase mt-2 py-2 w-[90%] md:w-[75%] inline-flex justify-center items-center rounded-lg bg-win-orange text-white disabled:opacity-50 disabled:pointer-events-none hover:cursor-pointer">
            Message
        </button>
        <a class="button-text messageVendorBtn uppercase mt-2 py-2 w-[90%] md:w-[75%] inline-flex justify-center items-center rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">
            View Storefront
        </a>
    </div>
</div>