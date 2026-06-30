<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: {{ $vendor->business_name }}'s Profile</title>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJedaphwORrnJVSpwqHuYSzRrGSluQ8Ck&loading=async">
    </script>
    <!-- Main Styling -->
    @vite('resources/css/app.css')
    @include('components.fonts')
    <style>
      #lightbox {
            display: none;
        }
    </style>
    <script>
      window.vendorID = {{ $vendor->id }};
    </script>
  </head>
  <body class="m-0 antialiased max-w-screen overflow-x-hidden">
    @if(Auth::guard('vendor')->check())
    @include('layouts.vendor_navigation')
    @else
    @include('layouts.navigation')
    @endif
    
    <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl pt-4">
      <section class="mx-4 mt-4 md:mx-8 md:mt-8 py-8 bg-white rounded-3xl">
        <div class="container mx-auto px-4">
          <div class="absolute right-[20%]">
              <img src="/assets/img/shapes/confetti-small-transparent.png" class="w-full">
          </div>
          <div class="relative flex flex-col min-w-0 break-words w-full mb-4">
            <div class="px-6">
              <div class="flex flex-wrap justify-center pt-4 md:pt-8 min-h-16 md:min-h-32 lg:min-h-64">
                <div class="w-full lg:w-5/12 px-4 lg:order-2 flex justify-center">
                  <img alt="..." src="{{asset('/storage/images/'. $vendor->image)}}" class="rounded-full h-auto align-middle border-none absolute w-32 md:w-32 lg:w-64">
                </div>
                @php
                  $mainVendor = false;
                  if(Auth::guard('web')->check()){
                    $main = Auth::guard('web')->user()->getMainVendor();
                    if(($main != null) && $main->id == $vendor->id){
                      $mainVendor = true;
                    }
                  }
                @endphp
                <div class="w-full lg:w-3/12 px-4 lg:order-3 lg:text-right lg:self-center">
                  <div class="py-6 px-3 mt-32 sm:mt-0 max-sm:text-center">
                    @if(Auth::guard('vendor')->check())
                      @if($vendor->id != Auth::guard('vendor')->user()->id)
                        <button id="messageVendorButton" class="bg-win-blue uppercase text-white font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                          Message Vendor <i class="fas fa-comment-dots"></i>
                        </button>
                        @if(Auth::guard('vendor')->user()->isPendingWith($vendor->id))
                        <button class="bg-gray-light uppercase text-white max-sm:block max-sm:mx-auto font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button" disabled>
                          Pending
                        </button>
                        @else
                        <button id="connectBtn" class="bg-win-blue uppercase max-sm:block max-sm:mx-auto text-white font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                          Invite to Storefront <i class="fas fa-store"></i>
                        </button>
                        @endif
                        <button id="endorse-button" class="bg-win-blue uppercase max-sm:block max-sm:mx-auto text-white font-semibold px-4 py-1 rounded-lg sm:mr-2 mb-1 block ml-auto" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="endorse-vendor-modal" data-hs-overlay="#endorse-vendor-modal">
                          Endorse <i class="fa-regular fa-star"></i>
                        </button>
                      @elseif($vendor->id == Auth::guard('vendor')->user()->id)
                        <a class="bg-win-blue overflow-visible uppercase text-white max-sm:block max-sm:mx-auto font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" href="/vendor/profile">
                          Edit Profile <i class="fas fa-edit"></i>
                        </a>
                      @endif
                    @else
                      @if(!Auth::guard('web')->user()->isAssociatedWith($vendor->id))
                      <button id="modal-check-date-toggle" class="modal-check-date-toggle bg-win-blue max-sm:mx-auto max-sm:block uppercase text-white font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                        Request to Match <i class="fa-solid fa-heart"></i>
                      </button>
                      <button id="messageVendorButton" class="bg-win-blue max-sm:mx-auto max-sm:block uppercase text-white font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                        Message Vendor <i class="fas fa-comment-dots"></i>
                      </button>
                      @else
                      <button id="consultation-button" class="bg-win-blue uppercase text-white font-semibold px-4 py-1 rounded-lg sm:mr-2 mb-1" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="request-consultation-modal" data-hs-overlay="#request-consultation-modal">
                        Request Consultation <i class="fa-solid fa-calendar-week"></i>
                      </button>
                      <button id="messageVendorButton" class="bg-win-blue uppercase text-white font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                        Message Vendor <i class="fas fa-comment-dots"></i>
                      </button>
                      @endif
                      <x-large-modal id="request-consultation">
                        @includeWhen(Auth::guard('web')->check(), 'modals.request_consultation')
                      </x-large-modal>
                    @endif
                  </div>
                </div>
                <div class="w-full lg:w-3/12 px-4 lg:order-1 lg:self-center">
                </div>
              </div>
              <div class="md:mt-12 md:py-8">
                <h3 class="headline-small font-semibold leading-normal text-center">
                  {{ $vendor->business_name }}
                </h3>
                <h3 class="text-2xl font-semibold leading-normal text-center">
                  {{ $vendor->first_name }} {{ $vendor->last_name }}
                </h3>
                <div class="leading-normal mb-2 font-bold uppercase text-center">
                  <span>
                    <img src="{{ $vendor_type->icon }}" class="h-auto max-h-6 inline" alt="Icon">
                  </span> <span class="ml-1">{{ $vendor_type->type }}</span>
                </div>
                <div class="flex flex-wrap mt-8 lg:mt-16 border-b border-blueGray-200">
                  <a href="#portfolio" class="subheading mx-2 my-2 hover:text-win-purple">Photos</a>
                  <a href="#about-vendor" class="subheading mx-2 my-2 hover:text-win-purple">About</a>
                  <a href="#aff-vendors-section" class="subheading mx-2 my-2 hover:text-win-purple">Preferred Vendors</a>
                  <a href="#pricing-section" class="subheading mx-2 my-2 hover:text-win-purple">Pricing</a>
                  <a href="#reviews" class="subheading mx-2 my-2 hover:text-win-purple">Reviews</a>
                </div>
                <div class="md:flex mt-8 md:space-x-2 lg:space-x-4 align-middle">
                  @if($profile->google_review_score != null)
                  <h3 class="text-center subheading mt-3">{{ $profile->google_review_score }} <span>
                    <svg class="flex-shrink-0 inline size-5 text-yellow-400 -mt-1 lg:-mt-1.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg></span> Rating</h3>
                  @endif
                  <div class="max-sm:mx-auto max-sm:text-center">
                    <div class="mt-3 space-x-1">
                        
                        @foreach($vendor->badges() as $badge)
                        <a class="hs-tooltip inline-flex justify-center items-center size-8 md:size-10 border-2 border-win-purple rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                            <img src="/images/{{ $badge->icon }}" class="w-6 h-6" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                            <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                            {{ $badge->name }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                  </div>
                  @if($vendor->discount > 0)
                  <div class="max-sm:flex items-center justify-center">
                    <div class="py-2 px-3 mt-2 inline-flex items-center justify-center subheading bg-teal-100 text-teal-800 rounded-full max-sm:mx-auto">
                      <svg class="flex-shrink-0 size-6 mr-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                      <span> ${{ $vendor->discount }} Preferred Pricing</span>
                    </div>
                  </div>
                  @endif
                </div>
                
                @if(Auth::guard('vendor')->check())
                <div class="mt-4">
                  <h3 class="subheading text-left">Social Brands:</h3>
                  <div class="mt-2 space-x-2 text-left">
                    <a target="_blank" class="inline-flex justify-center items-center align-middle size-10 text-center font-semibold bg-win-lavender text-win-red hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-white transition" href="{{ $profile->getLink('instagram') }}">
                      <svg class="flex-shrink-0 size-6" fill="currentColor" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <defs> </defs>
                          <g id="Page-1" stroke="none" stroke-width="1" fill="currentColor" fill-rule="evenodd">
                            <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -7439.000000)" fill="currentColor">
                              <g id="icons" transform="translate(56.000000, 160.000000)">
                                <path d="M289.869652,7279.12273 C288.241769,7279.19618 286.830805,7279.5942 285.691486,7280.72871 C284.548187,7281.86918 284.155147,7283.28558 284.081514,7284.89653 C284.035742,7285.90201 283.768077,7293.49818 284.544207,7295.49028 C285.067597,7296.83422 286.098457,7297.86749 287.454694,7298.39256 C288.087538,7298.63872 288.809936,7298.80547 289.869652,7298.85411 C298.730467,7299.25511 302.015089,7299.03674 303.400182,7295.49028 C303.645956,7294.859 303.815113,7294.1374 303.86188,7293.08031 C304.26686,7284.19677 303.796207,7282.27117 302.251908,7280.72871 C301.027016,7279.50685 299.5862,7278.67508 289.869652,7279.12273 M289.951245,7297.06748 C288.981083,7297.0238 288.454707,7296.86201 288.103459,7296.72603 C287.219865,7296.3826 286.556174,7295.72155 286.214876,7294.84312 C285.623823,7293.32944 285.819846,7286.14023 285.872583,7284.97693 C285.924325,7283.83745 286.155174,7282.79624 286.959165,7281.99226 C287.954203,7280.99968 289.239792,7280.51332 297.993144,7280.90837 C299.135448,7280.95998 300.179243,7281.19026 300.985224,7281.99226 C301.980262,7282.98483 302.473801,7284.28014 302.071806,7292.99991 C302.028024,7293.96767 301.865833,7294.49274 301.729513,7294.84312 C300.829003,7297.15085 298.757333,7297.47145 289.951245,7297.06748 M298.089663,7283.68956 C298.089663,7284.34665 298.623998,7284.88065 299.283709,7284.88065 C299.943419,7284.88065 300.47875,7284.34665 300.47875,7283.68956 C300.47875,7283.03248 299.943419,7282.49847 299.283709,7282.49847 C298.623998,7282.49847 298.089663,7283.03248 298.089663,7283.68956 M288.862673,7288.98792 C288.862673,7291.80286 291.150266,7294.08479 293.972194,7294.08479 C296.794123,7294.08479 299.081716,7291.80286 299.081716,7288.98792 C299.081716,7286.17298 296.794123,7283.89205 293.972194,7283.89205 C291.150266,7283.89205 288.862673,7286.17298 288.862673,7288.98792 M290.655732,7288.98792 C290.655732,7287.16159 292.140329,7285.67967 293.972194,7285.67967 C295.80406,7285.67967 297.288657,7287.16159 297.288657,7288.98792 C297.288657,7290.81525 295.80406,7292.29716 293.972194,7292.29716 C292.140329,7292.29716 290.655732,7290.81525 290.655732,7288.98792" id="instagram-[#167]"> </path>
                              </g>
                            </g>
                          </g>
                        </g>
                      </svg>
                    </a>
                    <a target="_blank" class="inline-flex justify-center items-center align-middle size-10 text-center font-semibold bg-win-lavender text-win-red hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-white transition" href="{{ $profile->getLink('facebook') }}">
                      <svg class="flex-shrink-0 size-6" fill="currentColor" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <path d="M1168.737 487.897c44.672-41.401 113.824-36.889 118.9-36.663l289.354-.113 6.317-417.504L1539.65 22.9C1511.675 16.02 1426.053 0 1237.324 0 901.268 0 675.425 235.206 675.425 585.137v93.97H337v451.234h338.425V1920h451.234v-789.66h356.7l62.045-451.233H1126.66v-69.152c0-54.937 14.214-96.112 42.078-122.058" fill-rule="evenodd"></path>
                        </g>
                      </svg>
                    </a>
                    <a target="_blank" class="inline-flex justify-center items-center align-middle size-10 text-center font-semibold bg-win-lavender text-win-red hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-white transition" href="{{ $profile->getLink('linkedin') }}">
                      <svg class="flex-shrink-0 size-6" fill="currentColor" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 552.77 552.77" xml:space="preserve">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <g>
                            <g>
                              <path d="M17.95,528.854h71.861c9.914,0,17.95-8.037,17.95-17.951V196.8c0-9.915-8.036-17.95-17.95-17.95H17.95 C8.035,178.85,0,186.885,0,196.8v314.103C0,520.816,8.035,528.854,17.95,528.854z"></path>
                              <path d="M17.95,123.629h71.861c9.914,0,17.95-8.036,17.95-17.95V41.866c0-9.914-8.036-17.95-17.95-17.95H17.95 C8.035,23.916,0,31.952,0,41.866v63.813C0,115.593,8.035,123.629,17.95,123.629z"></path>
                              <path d="M525.732,215.282c-10.098-13.292-24.988-24.223-44.676-32.791c-19.688-8.562-41.42-12.846-65.197-12.846 c-48.268,0-89.168,18.421-122.699,55.27c-6.672,7.332-11.523,5.729-11.523-4.186V196.8c0-9.915-8.037-17.95-17.951-17.95h-64.192 c-9.915,0-17.95,8.035-17.95,17.95v314.103c0,9.914,8.036,17.951,17.95,17.951h71.861c9.915,0,17.95-8.037,17.95-17.951V401.666 c0-45.508,2.748-76.701,8.244-93.574c5.494-16.873,15.66-30.422,30.488-40.649c14.83-10.227,31.574-15.343,50.24-15.343 c14.572,0,27.037,3.58,37.393,10.741c10.355,7.16,17.834,17.19,22.436,30.104c4.604,12.912,6.904,41.354,6.904,85.33v132.627 c0,9.914,8.035,17.951,17.949,17.951h71.861c9.914,0,17.949-8.037,17.949-17.951V333.02c0-31.445-1.982-55.607-5.941-72.48 S535.836,228.581,525.732,215.282z"></path>
                            </g>
                          </g>
                        </g>
                      </svg>
                    </a>
                    <a href="//{{ $profile->business_link }}" target="_blank" class="button-text inline-flex items-center align-middle justify-center py-1 px-4 mx-auto inline-flex gap-x-2 font-semibold rounded-lg bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none hover:cursor-pointer">
                      VISIT WEBSITE
                      <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                  </div>
                </div>
                @endif
                <div class="pt-4">
                  
                @if(Auth::guard('vendor')->check() && $vendor->id != Auth::guard('vendor')->user()->id)
                  <button id="messageVendorButton" class="bg-win-blue uppercase text-white button-text font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                    Message Vendor <i class="fas fa-comment-dots"></i>
                  </button>
                @elseif(Auth::guard("web")->check())
                  <button id="messageVendorButton" class="relative top-0 align-top bg-win-blue uppercase text-white button-text font-semibold px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                    Message Vendor <i class="fas fa-comment-dots"></i>
                  </button>
                  <div class="inline-flex">
                      <div id="heart" class="heart align-top hover:cursor-pointer" data-vendor-id="{{ $vendor->uuid }}"><i class="far fa-heart text-3xl"></i></div>
                  </div>
                @endif
                </div>
              </div>
              <div class="mt-4 text-left" id="about-vendor">
                <h3 class="headline-small">About {{ $vendor->first_name }}:</h3>
              </div>
              <div class="mt-4 py-6 border-t border-blueGray-200">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full">
                    <p class="mb-4 text-lg leading-relaxed">
                      {!! nl2br($profile->bio) !!}
                    </p>
                  </div>
                </div>
              </div>
              @if(count($profile->portfolioImages()) > 0)
              <section id="portfolio">
                      
                <div class="absolute z-[-10] left-[-10vw] md:left-[-15vw]">
                    <img src="/assets/img/shapes/confetti-small-transparent.png" class="w-full mt-[10vh]">
                </div>
                <div class="mt-4 text-left border-b border-blueGray-200 mb-4 pb-4">
                  <h3 class="headline-small">See my work:</h3>
                </div>
                <div class="grid grid-cols-2 gap-4 lg:gap-8">
                  <div class="flex justify-center">
                      <img class="lightbox w-full md:w-full object-cover bg-gray-100 rounded-lg" loading="lazy" src="{{asset('/storage/images/'. $profile->portfolioImages()[0])}}">
                  </div>
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 grid-rows-3 lg:grid-rows-2">
                    @foreach($profile->portfolioImages(7) as $image)
                      @if($image != $profile->portfolioImages()[0])
                      <div class="flex justify-center">
                        <img class="lightbox h-auto object-cover bg-gray-100 rounded-lg hover:scale-125 transition duration-150" loading="lazy" src="{{asset('/storage/images/'. $image)}}">
                      </div>
                      @endif
                    @endforeach
                  </div>
                </div>
                
                <button id="viewAllPortfolioImages" class="button-text my-2 inline-flex items-center justify-center py-1 px-4 mr-auto inline-flex gap-x-2 font-semibold rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none hover:cursor-pointer">
                  VIEW ALL
                  <i class="fas fa-chevron-right text-sm"></i>
                </button>
              </section>
              @endif
            </div>
          </div>
        </div>
        <div id="lightbox" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 z-100">
            <img src="" alt="Lightbox Image" class="max-w-full max-h-full rounded">
            <button id="close" class="absolute top-4 right-4 text-white text-xl md:text-2xl">&times;</button>
        </div>
        <div id="lightboxAll" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 z-50 hidden">
          <div id="viewAllContainer" class="md:grid grid-cols-2 lg:grid-cols-3 container mx-auto overflow-y-scroll max-h-screen">
              @foreach($profile->portfolioImages() as $image)
                <div class="flex justify-center h-full">
                  <img class="h-auto object-cover bg-gray-100 rounded-lg" loading="lazy" src="{{asset('/storage/images/'. $image)}}">
                </div>
              @endforeach
          </div>
          <button id="closeAll" class="absolute top-4 right-4 px-4 text-white text-xl md:text-2xl lg:text-3xl bg-black rounded-2xl">&times;</button>
        </div>
      </section>
      <section class="px-12 container mx-auto mt-8">
        @if($vendor->endorsements()->distinct()->count('endorser') > 0)
        <section id="endorsements" class="">
          <div class="mt-4 text-left border-b border-blueGray-200 mb-4 pb-4">
            <h3 class="headline-small">Endorsements:</hdiv>
          </div>
          <div>
            <div class="inline-flex flex-col items-center">
              <img alt="vendor profile image" src="{{asset('/storage/images/'. $vendor->image)}}" class="rounded-full h-auto border-none w-16 md:w-32 item-center mb-4">
              <p class="subheading">{{ $vendor->endorsements()->distinct()->count('endorser') }} vendors find {{ $vendor->business_name }} to be:</p>
              @foreach($endorsements as $endorsement)
              <div class="bg-white rounded-lg p-4 my-2 inline-flex items-center">
                <p class="font-bold mr-2"> {{ $endorsement->type }}</p>
                <div class="flex inline-flex -space-x-2 lg:-space-x-4">
                  @forelse($vendor->endorsements()->where('type', $endorsement->typeNum)->select('endorser')->distinct()->take(3)->get() as $endorserTypePicture)
                  <div class="hs-tooltip inline-block">
                    <img class="relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10" src="{{ asset('/storage/images/'. $endorserTypePicture->endorserPicture()[0]->image) }}" alt="Avatar">
                  </div>
                  @empty
                  <div class="hs-tooltip inline-block">
                    <img class="relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10 mx-auto" src="{{ asset('/storage/images/user.jpg') }}" alt="Avatar">
                  </div>
                  @endforelse
                </div>
                <i class="fa-regular fa-star text-win-purple ml-2 text-xl"></i>
              </div>
              @endforeach
            </div>
          </div>
        </section>
        @endif
        <section id="location" class="hidden">
          <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 mx-auto">
            <h3 class="text-2xl font-bold text-center">Service area:</h3>
            <div class="w-full h-48 md:h-64 md:w-[80%] lg:h-72 mt-4 text-center mx-auto" id="map">

            </div>
          </div>
        </section>
        <section id="aff-vendors-section">
          
          <div class="mt-4 text-left border-b border-blueGray-200 mb-4 pb-4">
            <h3 class="headline-small">Preferred Vendors:</h3>
          </div>
          <div>
            <nav class="flex flex-wrap space-x-2 justify-center" aria-label="Tabs" role="tablist">
              @php
                $included_roles = [];
                $cnt = 0;
              @endphp
              @foreach($connections as $connection)
                @if(!in_array($connection->getType(), $included_roles))
                @php
                  array_push($included_roles, $connection->getType());
                  $cnt += 1;
                @endphp
                <button type="button" class="hs-tab-active:font-bold uppercase bg-win-lavender hs-tab-active:bg-win-purple rounded-full hs-tab-active:text-white font-semibold py-2 px-4 inline-flex items-center mx-3 whitespace-nowrap text-white hover:text-win-purple focus:outline-none focus:text-win-purple disabled:opacity-50 disabled:pointer-events-none @if($cnt == 1 )active @endif mb-4" id="tab-item-{{ $connection->getType()->id }}" data-hs-tab="#tab-{{ $connection->getType()->id }}" aria-controls="tab-{{ $connection->getType()->id }}" role="tab">
                  <img src="{{ $connection->getType()->icon }}" class="h-auto max-h-6 inline hidden" alt="Icon">
                  {{ $connection->getType()->type }}
                </button>
                @endif
              @endforeach
              @if($included_roles == [])
              <div class="p-4 my-4 h-48 flex justify-center items-center border border-dashed border-black rounded-xl">
                <h3 class="subheading">
                  This vendor doesn't have any preferred vendors added to their storefront yet!
                </h3>
              </div>
              @endif
            </nav>
          </div>
        
          <div class="mt-3 flex justify-center">
            @php($cnt = 0)
            @foreach($included_roles as $role)
            <div id="tab-{{ $role->id }}" class="w-full @if($cnt != 0) hidden @endif" role="tabpanel" aria-labelledby="tab-item-{{ $role->id }}">
              @php($cnt += 1)
              <div class="grid sm:grid-cols-2 lg:grid-cols-3">
              @foreach($vendor->connections()->where('aff_vendor_type', $role->id)->get() as $ven)
              <x-vendor-profile-card :vendor="$ven" />
                  <!-- End Card -->
              @endforeach
              </div>
            </div>
            @endforeach
          </div>
        </section>
        <section id="pricing-section">
          <div class="max-w-[85rem] py-10 lg:py-14 mx-auto">
            <!-- Grid -->
            <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
              <div class="flex flex-col bg-white rounded-2xl">
                <div class="p-4 md:p-5">
                  <div class="flex items-center gap-x-2">
                    <p class="subheading text-gray-500">
                      Average Price
                    </p>
                    <div class="hs-tooltip">
                      <div class="hs-tooltip-toggle">
                        <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                        <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black font-medium text-white rounded shadow-sm" role="tooltip">
                          This is the average package price this vendor charges for their services
                        </span>
                      </div>
                    </div>
                  </div>
          
                  <h3 class="mt-2 text-2xl sm:text-3xl lg:text-4xl text-gray-800">
                    <span class="font-semibold">{{ $vendor->preferredPricing() }}</span>
                  </h3>
                </div>
              </div>
              <div class="flex flex-col bg-white rounded-2xl">
                <div class="p-4 md:p-5">
                  <div class="flex items-center gap-x-2">
                    <p class="subheading text-gray-500">
                    Preferred Pricing Offer:
                    </p>
                    <div class="hs-tooltip">
                      <div class="hs-tooltip-toggle">
                        <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                        <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black font-medium text-white rounded shadow-sm" role="tooltip">
                          This is the discount you earn from this vendor for using WIN!
                        </span>
                      </div>
                    </div>
                  </div>
          
                  <h3 class="mt-2 text-2xl sm:text-3xl lg:text-4xl text-gray-800">
                    <span class="font-semibold">${{ $vendor->discount }}</span>
                  </h3>
                </div>
              </div>
            </div>
            <!-- End Grid -->
          </div>
        </section>
        
        @if($profile->google_review_score != null)
        <section id="reviews">
          <div class="max-w-[85rem] py-10 mx-auto">
            <div class="mt-4 text-left border-b border-blueGray-200 mb-4 pb-4 flex">
              <h3 class="headline-small">{{ $profile->google_review_score }} <span>
                <svg class="flex-shrink-0 inline size-5 text-yellow-400 -mt-1 lg:-mt-1.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg></span> Rating @if($profile->google_reviews_count > 0)({{ $profile->google_reviews_count }})@endif</h3>
                @if($profile->google_place_link != null)
                <a href="{{ $profile->google_place_link }}" target="_blank" class="px-4 py-1 bg-win-blue text-white button-text my-auto rounded-lg inline-flex align-center ml-2">See All <i class="fa-solid fa-arrow-right my-auto ml-1"></i></a>
                @endif
            </div>
            <div id="reviewsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              @foreach($vendor->getReviews() as $review)
              <div class="flex flex-col bg-white rounded-2xl">
                <div class="flex-auto p-4 md:p-6">
                  <p class="subheading inline">{{ $review->rating }}</p>
                  <span>
                    <svg class="flex-shrink-0 inline size-5 text-yellow-400 -mt-1 lg:-mt-1.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                  </span>
                  <p class="mt-3 sm:mt-6 text-base text-dark-grey-win md:text-lg"><em>
                    "{{ $review->body }}"
                  </em></p>
                </div>
                <div class="pb-4 rounded-b-xl px-4 md:px-6">
                  <h3 class="text-sm font-semibold text-dark-grey-win sm:text-base">
                    {{ $review->author }}
                  </h3>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </section>
        @endif
        @if(Auth::guard('web')->check())
        <section id="other-vendors-section text-white">
          <h3 class="font-bold text-2xl text-center">Not finding what you're looking for?</h3>
          <p class="font-semibold text-lg text-center">Browse other professionals in our network:</p>
          @foreach($related_vendors as $related_type)
          <div class="mt-3 justify-center">
            <h3 class="mx-auto font-semibold text-2xl mb-4 md:mb-6">Top Ranked {{ App\Models\VendorTypes::where('id', $related_type)->first()->type }}: <span class="underline text-lg"><a href="/search/vendors?type={{ $related_type }}">View More<i class="fas fa-arrow-right ml-1"></i></a></span></h3>
            <div class="w-full">
              <div class="grid md:grid-cols-3 gap-4">
                @php($vendors = App\Models\Vendor::where('type', $related_type)->take(3)->get())
                @if(count($vendors) == 0)
                <div class="p-4 mt-4 mb-8 h-24 flex justify-center items-center border border-dashed border-white rounded-xl col-span-2">
                  <h3 class="text-white">
                    We couldn't find any vendors of the specified type!
                  </h3>
                </div>
                @endif
                @foreach($vendors as $subvendor)
                <x-vendor-profile-card :vendor="$subvendor" />
                @endforeach
              </div>
            </div>
          </div>
          @endforeach
        </section>
        @endif
      </section>
    </div>
    @include('layouts.footer')
    @vite('resources/js/app.js')
    @includeWhen(Auth::guard('web')->check(), 'modals.check_date')
    
    <x-large-modal id="endorse-vendor">
      @includeWhen(Auth::guard('vendor')->check(), 'modals.endorsement')
    </x-large-modal>
    @vite('resources/js/vendor.js')
    @if(Auth::guard("web")->check() && Auth::user()->hasFavorite($vendor->id))
    <script>
        $("#heart").toggleClass("is-active");
        $("#heart").html('<i class="fas fa-heart text-3xl" style="color: #6432C8;"></i>');
    </script>
    @endif
    <script>
            let map;
            async function initServiceMap() {
                const { Map } = await google.maps.importLibrary("maps");
                map = new Map(document.getElementById("map"), {
                    center: { lat: 41.7658, lng: -72.6734 },
                    zoom: 6,
                    mapId: "6eaab94585b0841a"
                });
              findPlaces("{{ $vendor->location }}", {{ $vendor->service_radius }});
            }
            let circleRad = null;
            async function findPlaces(query, serviceRadius = 50) {
                const { Place } = await google.maps.importLibrary("places");
                //@ts-ignore
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                const request = {
                    textQuery: query,
                    fields: ["displayName", "location", "rating"],
                    language: "en-US",
                    maxResultCount: 1,
                    region: "us",
                    useStrictTypeFiltering: false,
                };
                //@ts-ignore
                const { places } = await Place.searchByText(request);
                if (places.length) {

                    const { LatLngBounds } = await google.maps.importLibrary("core");
                    const bounds = new LatLngBounds();

                    // Loop through and get all the results.
                    places.forEach((place) => {
                    const markerView = new AdvancedMarkerElement({
                        map,
                        position: place.location,
                        title: place.displayName,
                    });

                    bounds.extend(place.location);
                    if (circleRad && circleRad.setMap) {
                        circleRad.setMap(null);
                    }
                    circleRad = new google.maps.Circle({
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: "#FF0000",
                        fillOpacity: 0.35,
                        map,
                        center: place.location,
                        radius: serviceRadius * 1609.34,
                    });
                    });
                    map.setCenter(bounds.getCenter());
                } else {
                    console.log("No results");
                }
              }
            
            $(document).ready(function() {
              initServiceMap();
            });
    </script>
    <script>
      $("#connectBtn").on("click", () => {
        $("#connectBtn").attr('disabled', true);
        let formData = {
          aff_id: {{ $vendor->id }}
        };
        $.ajax({
          type: "POST",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/vendor/request/connection",
          data: formData,
          success: function (data) {
            if(data['status'] == false){
              Swal.fire({
                title: 'Oops!',
                text: data['msg'],
                icon:  'error',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#6432C8'
              });
              $("#connectBtn").attr('disabled', false);
            } else{
                Swal.fire({
                  title: 'Nice!',
                  text: data['msg'],
                  icon:  'success',
                  confirmButtonText: 'Ok',
                  confirmButtonColor: '#6432C8'
              });
              $("#connectBtn").html("Pending");
            }
          }
        });
      });
    </script>
    <!-- messages-->
    <script>
      $("#messageVendorButton").on("click", () => {
        $.ajax({
            type: "GET",
            headers: {
            },
            @if(Auth::guard('vendor')->check())
            url: "/vendor/message/{{ $vendor->id }}",
            @else
            url: "/client/message/{{ $vendor->id }}",
            @endif
            success: function (data) {
              @if(Auth::guard('vendor')->check())
              window.location = '/inbox/conversation/' + data;
              @else
              window.location = '/client/conversation/' + data;
              @endif
            }
          });
      });
    </script>
    <script>
      $(".messageVendorButton").on("click", (el) => {
        let vendor_uuid = $(this).data("vendor_uuid");
        $.ajax({
            type: "GET",
            headers: {
            },
            @if(Auth::guard('vendor')->check())
            url: "/vendor/message/" + el.currentTarget.id,
            @else
            url: "/client/message/" + el.currentTarget.id,
            @endif
            success: function (data) {
              @if(Auth::guard('vendor')->check())
              window.location = '/inbox/conversation/' + data;
              @else
              window.location = '/client/conversation/' + data;
              @endif
            }
          });
      });
    </script>
  </body>
</html>
