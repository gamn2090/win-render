<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Find Clients</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/find-couples.js')
    @include('components.fonts')
  </head>

  <body class="m-0antialiased overflow-x-hidden">
    @include('layouts.vendor_navigation')
    
    <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl">
      <main class="relative h-full min-h-[75vh] transition-all duration-200 ease-in-out rounded-xl">
        <div class="text-center flex justify-center">
          <div class="p-6 lg:w-[48rem] bg-white rounded-xl my-4 lg:my-6">
            <h1 class="headline-small relative z-10">&#127881; Client Finder</h1>
            <p class="font-semibold">Your Contact Credits: {{ Auth::user()->contact_credits }}</p>
            <span class="absolute z-0"><img src="/assets/img/shapes/confetti-small-transparent.png"></span>
          </div>
        </div>
        <div class="w-full container px-6 lg:px-16 py-4 lg:py-8 mx-auto bg-white rounded-xl">
          <div class="md:flex justify-between">
            <p class="subheading">Find your next dream client!</p>
            <button class="bg-win-blue max-sm:mx-auto max-sm:block text-white px-4 py-1 rounded-lg outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="join-event-modal" data-hs-overlay="#join-event-modal">
              Join Event <i class="fa-solid fa-calendar-check ml-1"></i>
            </button>
            <x-large-modal id="join-event">
              @include('modals.join_event')
            </x-large-modal>
          </div>
            
          <div class="hs-dropdown [--strategy:fixed] [--trigger:click] [--auto-close:false] inline-flex relative z-50 max-sm:hidden">
            <button id="hs-dropdown-hover-filter" type="button" class="hs-dropdown-toggle py-1 mb-2 inline-flex items-center gap-x-2 font-semibold body-copy text-win-blue hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none @if($page == 'client_invite' || $page == 'vendor_invite')text-win-blue border-b-2 border-win-blue @endif" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
              Filter <span><i class="fa-solid fa-filter"></i></span>
              <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-hover-filter">
              <div class="p-4 space-y-2">
                <div class="flex items-center gap-x-3">
                  <label for="search-wedding-date" class="relative inline-block w-11 h-6 cursor-pointer">
                    <input type="checkbox" id="search-wedding-date" class="peer sr-only" checked="">
                    <span class="absolute inset-0 bg-grey-100 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-win-blue"></span>
                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 size-5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full"></span>
                  </label>
                  <label for="search-wedding-date" class="text-sm text-gray-500">Show clients with no wedding date</label>
                </div>
                <button id="filter-btn" type="button" class="flex items-center gap-x-3.5 py-1 px-3 rounded-lg text-sm text-white bg-win-purple focus:outline-none focus:bg-win-lavender ml-auto">
                  Search
                </a>
              </div>
            </div>
          </div>
          <div class="space-y-4 lg:space-y-10">
            @foreach($data["clients"] as $inquiry)
            <div>
              <!-- Couple info Popup -->  
              <div id="hs-full-screen-modal-{{ $inquiry->id }}" class="hs-overlay hidden fixed mx-auto w-full h-full top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none max-lg:min-h-screen lg:max-h-[90vh] lg:mt-6 rounded-xl max-w-screen">
                <div class="hs-overlay-open:mt-0 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-10 opacity-0 transition-all max-w-full">
                  <div class="flex flex-col bg-white pointer-events-auto md:max-w-[80vw] lg:max-w-[60vw] mx-auto h-full rounded-xl">
                    <div class="bg-win-blue py-2 px-4 rounded-t-xl">
                      <div class="ml-auto">
                        <button type="button" class="flex justify-center items-center size-7 font-semibold rounded-lg border border-white border-2 text-white disabled:opacity-50 disabled:pointer-events-none  ml-auto" data-hs-overlay="#hs-full-screen-modal-{{ $inquiry->id }}">
                          <span class="sr-only">Close</span>
                          <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                          </svg>
                        </button>
                      </div>
                    </div>
                    <div class="p-4 mx-auto">
                      <img src="{{ asset('/storage/images/'.$inquiry['image']) }}" class="rounded-full max-h-[3rem] md:max-h-[4rem] lg:max-h-[10rem] align-middle mx-auto">
                      <p class="subheading text-center mt-4">{{ $inquiry["first_name"] }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $inquiry["fiance_first_name"] }}</p>

                      <p class="md:text-lg text-center">{{ $inquiry["wedding_location"] }}</p>
                      <p class="md:text-lg text-center">{{ $inquiry["wedding_date"] }}</p>
                      <div class="text-center mb-2">
                        <button class="inquireClientButton bg-win-blue text-white px-4 py-1 rounded-lg outline-none sm:mr-2" type="button" data-client-uuid="{{ $inquiry->uuid }}">
                          Message Couple <i class="fas fa-comment-dots h-2"></i>
                        </button>
                        <a href="/client/profile/{{ $inquiry->uuid }}" class="button-text bg-win-blue text-white px-4 py-1 rounded-lg outline-none">
                          View Profile <i class="fa-solid fa-user h-2"></i>
                        </a>
                      </div>
                      @if($inquiry["bio"])
                      <p class="subheading text-center">Our Bio:</p>
                      <p class="text-center">{{ $inquiry["bio"] }}</p>
                      @else
                      <p class="text-center">{{ $inquiry["first_name"] }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $inquiry["fiance_first_name"] }} haven't written a bio yet!</p>
                      @endif
                      <br>
                      @php
                        $answers = json_decode($inquiry['questions'])
                      @endphp
                      @if($answers[0] != null)
                      <div class="mt-4">
                        <h3 class="subheading">Describe your dream wedding in three words.</h3>
                      </div>
                      <div class="mt-4 py-6 border-t border-blueGray-200">
                        <div class="flex flex-wrap">
                          <div class="w-full lg:w-9/12">
                            <p class="mb-4 text-lg">
                              {{ $answers[0] }}
                            </p>
                          </div>
                        </div>
                      </div>
                      @endif
                      @if($answers[1] != null)
                      <div class="mt-4">
                        <h3 class="subheading">What are you most looking forward to about your wedding?</h3>
                      </div>
                      <div class="mt-4 py-6 border-t border-blueGray-200">
                        <div class="flex flex-wrap">
                          <div class="w-full lg:w-9/12">
                            <p class="mb-4 text-lg">
                              {{ $answers[1] }}
                            </p>
                          </div>
                        </div>
                      </div>
                      @endif
                      @if($answers[2] != null)
                      <div class="mt-4">
                        <h3 class="subheading">Are there any specific traditions that are important for you to include, or avoid?</h3>
                      </div>
                      <div class="mt-4 py-6 border-t border-blueGray-200">
                        <div class="flex flex-wrap">
                          <div class="w-full lg:w-9/12">
                            <p class="mb-4 text-lg">
                              {{ $answers[2] }}
                            </p>
                          </div>
                        </div>
                      </div>
                      @endif
                      @if($answers[3] != null)
                      <div class="mt-4">
                        <h3 class="subheading">Is there anything else you'd like your wedding vendors to know before working together?</h3>
                      </div>
                      <div class="mt-4 py-6 border-t border-blueGray-200">
                        <div class="flex flex-wrap">
                          <div class="w-full lg:w-9/12">
                            <p class="mb-4 text-lg">
                              {{ $answers[3] }}
                            </p>
                          </div>
                        </div>
                      </div>
                      @endif
                      <p class="subheading">Vendors they are searching for:</p>
                      <div class="mt-4 py-6 border-t border-blueGray-200 text-center">
                        <div class="flex flex-wrap justify-center">
                          <div class="w-full px-4 grid grid-cols-2 sm:gap-4 lg:grid-cols-3 xl:grid-cols-4">
                            @php
                              $searching_for = $inquiry->inquiries;
                              $vendorProgress = $inquiry->requestedVendorProgress();
                            @endphp
                            @foreach($searching_for as $vendorTypeInquiry)
                              @php
                                $vendorTypeModel = $vendor_types->where('id', $vendorTypeInquiry->vendor_type)->first();
                                $status = 0;
                                foreach($vendorProgress as $prog){
                                  if($prog["vendor_type"] == $vendorTypeModel->id){
                                    $status = $prog["status"];
                                  }
                                }
                              @endphp
                              <div class="col-span-1 mx-auto">
                                <x-vendor-progress :stage="$status" :vendorType="$vendorTypeModel" :uid="$inquiry['uuid']" />
                              </div>
                            @endforeach
                          </div>
                        </div>
                    </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 mt-auto border-t">
                      <button type="button" class="py-1 px-6 button-text inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-win-red text-white" data-hs-overlay="#hs-full-screen-modal-{{ $inquiry->id }}">
                        Close
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Couple list display -->
              <div class="bg-grey-100 rounded-xl mx-auto relative z-20 my-4 py-4 hover:scale-105 transition duration-250">
                <div class="lg:grid lg:grid-cols-5 pr-4">
                  <div class="col-span-2 flex max-lg:justify-center">
                    <div class="my-auto">
                      <img src="{{ asset('/storage/images/'.$inquiry['image']) }}" class="rounded-full max-h-[3rem] md:max-h-[4rem] lg:max-h-[10rem] lg:-my-6 lg:-ml-8  align-middle">
                    </div>
                    <div class="ml-2 lg:ml-4 my-auto">
                      <p class="subheading">{{ $inquiry["first_name"] }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $inquiry["fiance_first_name"] }}</p>
                      <p class="my-auto">{{ $inquiry["wedding_location"] }}</p>
                      <p class="my-auto">{{ $inquiry["wedding_date"] }}</p>
                    </div>
                  </div>
                  <div class="col-span-2 flex max-lg:justify-center">
                    <div class="relative size-20 lg:size-[6rem] lg:-my-2 max-md:my-1">
                      <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background Circle -->
                        <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-50" stroke-width="2"></circle>
                        <!-- Progress Circle -->
                        <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ 100 - (100 * ($inquiry->bookedVendorsCount() / $inquiry->getRequestedVendorCount())) }}" stroke-linecap="round"></circle>
                      </svg>

                      <!-- Percentage Text -->
                      <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                        <p class="text-center font-semibold text-xs">{{ $inquiry->bookedVendorsCount() }} of {{ $inquiry->getRequestedVendorCount()}} Booked Vendors</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-span-1 flex items-center justify-center">
                    <button type="button" class="rounded-lg text-white bg-win-purple py-1 px-4" data-hs-overlay="#hs-full-screen-modal-{{ $inquiry->id }}">View</button>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
            @if(count($data["clients"]) == 0)
            <div class="border border-dashed py-10 text-center">
              <p class="my-auto mx-auto">There are no couples to find right now. Please check back later!</p>
            </div>
          @endif
          </div>
          <div class="flex justify-end items-end my-4 space-x-4">
            @if(!$data['clients']->onFirstPage())
            <a href="{{ $data['clients']->previousPageUrl() }}" class="py-1 px-4 bg-win-blue rounded-lg text-white">Previous</a>
            @endif
            @if($data['clients']->hasMorePages())
            <a href="{{ $data['clients']->nextPageUrl() }}" class="py-1 px-4 bg-win-blue rounded-lg text-white">Next</a>
            @endif
          </div>
        </div>

        <!-- end cards -->
      </main>
    </div>
    @include('layouts.footer')
  </body>
</html>
