<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Dashboard</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
    <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
    @if($first_login)
    <script>
      window.newUser = true;
    </script>
    @endif
  </head>
  
  @include('layouts.navigation')

  <body class="m-0 overflow-x-hidden bg-white">

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
      <div class="bg-[#EDE9F5] lg:mx-8 rounded-3xl pb-8">
          <div class="w-full px-6 py-6 container mx-auto">
            <div id="notification-bar" class="flex flex-wrap bg-white p-4 lg:px-8 lg:pt-8 lg:pb-6 rounded-3xl text-center">
              <div class="w-full mb-6">
                <h3 class="headline-small text-center">&#127881; Welcome, {{ Auth::user()->first_name }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ Auth::user()->fiance_first_name }}!</h3>
                <button class="flex ml-auto text-right md:mt-[-2.5rem] text-white bg-win-blue px-2 py-1 font-medium rounded-lg" id="tutorial-btn">
                  <span class="underline ml-1">Don't know where to start? Click here!</span>
                </button>
              </div>
              <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                  <div class="flex-auto p-4">
                      <div class="flex-none max-w-full">
                        <div>
                          <h5 class="mb-0 subheading">{{ Auth::user()->daysUntilWedding() }}</h5>
                          <p class="mb-0 subheading uppercase" style="font-size:1rem;">Days Until Wedding</p>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
              <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                  <div class="flex-auto p-4">
                      <div class="flex-none max-w-full">
                        <div>
                          <h5 class="mb-0 subheading">{{ Auth::user()->unreadMessagesCount() }}</h5>
                          <p class="mb-0 subheading uppercase" style="font-size:1rem;">Unread Messages</p>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
              <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                  <div class="flex-auto p-4">
                      <div class="flex-none max-w-full">
                        <div>
                          <h5 class="mb-0 subheading">{{ Auth::user()->bookedVendorsCount() }}</h5>
                          <p class="mb-0 subheading uppercase" style="font-size:1rem;">Vendors Booked</p>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
              <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div id="savings-card" class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                  <div class="flex-auto p-4">
                      <div class="flex-none max-w-full">
                        @if(Auth::guard('web')->user()->moneySaved(Auth::user()->bookedVendors()) > 0)
                        <div>
                          <p class="mb-0 subheading">${{ Auth::guard('web')->user()->moneySaved(Auth::user()->bookedVendors()) }}</p>
                          <p class="mb-0 subheading uppercase" style="font-size:1rem;">Saved With WIN</p>
                        </div>
                        @else
                        <span class="py-1 px-2 inline-flex items-center gap-x-1 font-semibold bg-purple-win text-win-purple rounded-lg text-sm">
                          
                            <div class="hs-tooltip inline-block">
                              <span class="hs-tooltip-toggle relative inline-block hover:z-10"><svg class="flex-shrink-0 size-3 md:size-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg></span>
                              <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-black text-white rounded-lg" role="tooltip">
                                Most vendors offer exclusive discounts to couples who book through WIN. <br>Start searching for your dream team and save!
                              </span>
                            </div>
                          
                          Start your savings by booking vendors!
                        </span>
                        @endif
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="grid lg:grid-cols-2 xl:grid-cols-4 mt-8 -mx-2">
              <div class="rounded-3xl bg-white min-h-96 col-span-1 m-2 hidden">
                <div class="overflow-x-hidden min-h-full min-w-full">
                  <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                    <h2 class="subheading mb-2">
                      Our Wedding Day
                      <button type="button" class="inline-flex items-center gap-x-1 text-white bg-win-blue px-2 py-1 font-medium rounded-lg ml-2" aria-haspopup="dialog" aria-expanded="false" aria-controls="edit-notes-modal" data-hs-overlay="#edit-notes-modal">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </button>
                      <x-large-modal id="edit-notes">
                        @include('modals.edit_notes')
                      </x-large-modal>
                    </h2>
                  </div>
                  @if($profile->notes == null)
                  <div class="px-6 mx-auto body-copy space-y-2 mt-2 lg:mt-4">
                    <p class="text-base border-b-4 border-win-light line-clamp-[15]">
                      7:30 AM – Wake up & prepare for the arrival of hair & makeup ⛅ <br>
                      8:00 AM – Take 30 minutes to relax & review the plan for your BIG day! 💕 <br>
                      9:00 AM – Hair & makeup arrives 💄 <br>
                      12:00 PM – Day-of coordinator/wedding planner check-in ✅ <br>
                      1:00 PM – Hair & makeup complete, final touch-ups ☑️ <br>
                      1:30 PM – Photographer/Videographer arrives 📷🎥 <br>
                      2:30 PM – Get dressed (including wedding party) ✨👗 <br>
                      3:00 PM – Final getting ready detail pictures & portraits 📸 <br>
                      3:30 PM – First look with your significant other (if you choose) 💕 <br>
                      3:45 PM – Private vow reading (if you choose) 📖 <br>
                      3:45 PM – Couple’s portraits ❤️ <br>
                      4:15 PM – Wedding party & family formals 📷🎥 <br>
                      5:30 PM – Ceremony begins 💍✨ <br>
                      6:00 PM – Cocktail hour begins 🍸🥂 <br>
                      7:15 PM – Wedding party introductions 🎉 <br>
                      7:30 PM – Couple’s first dance & parent dances 🎵 <br>
                      7:45 PM – Speeches & toasts 🗒️🥂 <br>
                      8:00 PM – Dinner is served 🍴✨ <br>
                      9:00 PM – Cake cutting & dance floor opens 🎂💃🕺 <br>
                      11:00 PM – Wedding ends, but the memories last forever! 🎉💕</p>
                  </div>
                  @else
                  <div class="px-6 mx-auto body-copy space-y-2 mt-2">
                    <p class="text-base border-b-4 border-win-light line-clamp-[15]">{!! nl2br($profile->notes) !!}</p>
                  </div>
                  @endif
                  <button type="button" class="text-white bg-win-blue px-2 py-1 font-medium rounded-lg mx-auto block my-2 text-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="edit-notes-modal" data-hs-overlay="#edit-notes-modal">
                    View All
                  </button>
                </div>
              </div>
              <div id="vendor-status-card" class="rounded-3xl bg-white min-h-96 col-span-2 m-2">
                <div class="overflow-x-hidden min-h-full min-w-full">
                  <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                    <h2 class="subheading mb-2">
                      Vendor Status
                    </h2>
                  </div>
                  <div class="flex justify-center w-[90%] mx-auto py-6 border rounded-xl border-win-light border-4">
                    <div class="flex inline-flex -space-x-2 lg:-space-x-4">
                      @forelse($pairings as $vendor)
                      @break($loop->index > 2)
                      <div class="hs-tooltip inline-block">
                        <img class="hs-tooltip-toggle relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10" src="{{ asset('/storage/images/'.$vendor->vendor->image) }}" alt="Avatar">
                        <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-black text-xs text-white rounded-lg" role="tooltip">
                          {{ $vendor->vendor->business_name }}
                        </span>
                      </div>
                      @empty
                      <div class="hs-tooltip inline-block">
                        <img class="relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10 mx-auto" src="{{ asset('/storage/images/user.jpg') }}" alt="Avatar">
                      </div>
                      @endforelse
                    </div>
                    <div class="flex inline-flex px-2">
                      <p class="font-semibold">
                      {{ Auth::user()->vendorsCount() }} Vendor
                      <br>
                      Connections
                      </p>
                    </div>
                    <div class="flex inline-flex px-2 my-2 text-center">
                      <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="/vendors/list">
                        See all
                      </a>
                    </div>
                  </div>
                  <table class="w-[90%] mx-auto border-win-light">
                    <tbody class="border-0">
                      @foreach($requestedVendorTypes as $vendorType)
                      <tr class="border-b-4 border-win-light">
                        <td class="size-px whitespace-nowrap">
                          <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                            <div class="flex items-center gap-x-3">
                              <img class="inline-block size-[38px]" src="{{ asset($vendorType->icon) }}" alt="Image Description">
                              <div class="grow">
                                <span class="block text-sm font-semibold">{{ $vendorType->type }}</span>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="size-px whitespace-nowrap">
                          <div class="px-3 py-1.5 text-center">
                            <button type="button" class="px-3 py-[0.125rem] inline-flex items-center gap-x-2 text-sm rounded-lg bg-win-blue text-white focus:outline-none disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="vendor-{{ $vendorType->id }}-status-modal" data-hs-overlay="#vendor-{{ $vendorType->id }}-status-modal">
                              View
                            </button>

                            <div id="vendor-{{ $vendorType->id }}-status-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="vendor-{{ $vendorType->id }}-status-modal-label">
                              <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-4xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
                                <div class="w-full flex flex-col bg-white shadow-sm rounded-xl pointer-events-auto">
                                  <div class="flex justify-between items-center py-3 px-4 border-b">
                                    <h3 id="vendor-{{ $vendorType->id }}-status-modal-label" class="subheading">
                                      {{ $vendorType->type }} Status
                                    </h3>
                                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#vendor-{{ $vendorType->id }}-status-modal">
                                      <span class="sr-only">Close</span>
                                      <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 6 6 18"></path>
                                        <path d="m6 6 12 12"></path>
                                      </svg>
                                    </button>
                                  </div>
                                  <div class="p-4 overflow-y-auto">
                                    @php
                                      $vendorsOfType = $pairings->filter(function($v) use ($vendorType){
                                        return $v->vendor->type == $vendorType->id;
                                      });
                                      $vendorsOfTypeCount = $vendorsOfType->count();
                                    @endphp
                                    @if($vendorsOfTypeCount > 0)
                                      <x-carousel :slidesCount="$vendorsOfTypeCount">
                                        <x-slot name="slides">
                                          @foreach($vendorsOfType as $vendor)
                                          <div class="hs-carousel-slide px-1">
                                              <x-vendor-status-card :vendor="$vendor->vendor" :status="$vendor->status" :favorited="Auth::user()->hasFavorite($vendor->vendor->id)" />
                                          </div>
                                          @endforeach
                                        </x-slot>
                                      </x-carousel>
                                    @else
                                      <div class="p-4 my-4 h-48 flex flex-col justify-center items-center border border-dashed border-black rounded-xl text-center md:col-span-2 lg:col-span-5">
                                        <h3 class="subheading">
                                          You don't have any vendors of this type yet!
                                        </h3>
                                        <a href="/search/vendors" class="py-1 px-4 text-sm font-medium rounded-lg bg-win-blue text-white">Search for Vendors</a>
                                      </div>
                                    @endif
                                  </div>
                                  <div class="flex justify-end gap-x-2 py-3 px-4">
                                    <button type="button" class="py-1 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-win-red text-white focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#vendor-{{ $vendorType->id }}-status-modal">
                                      Close
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div id="messages-section" class="rounded-3xl bg-white min-h-96 col-span-1 m-2">
                <div class="overflow-x-hidden min-h-full min-w-full">
                  <div class="w-full inline-block align-middle">
                    <div class="bg-white rounded-3xl overflow-hidden">
                      <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                        <h2 class="subheading mb-2">
                          Inbox
                        </h2>
                      </div>
                      <div class="flex justify-center w-[90%] mx-auto py-6 border rounded-xl border-win-light border-4">
                        <div class="flex inline-flex -space-x-2 lg:-space-x-4">
                            @forelse($recentConversations as $convo)
                              @foreach($convo->conversation->participants as $participant)
                                @if($participant->messageable->id != Auth::user()->id || $participant->messageable_type == 'App\Models\Vendor')
                            <div class="hs-tooltip inline-block">
                              <img class="hs-tooltip-toggle relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10" src="{{ asset('/storage/images/'.$participant->messageable->image) }}" alt="Avatar">
                              <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-black text-xs text-white rounded-lg" role="tooltip">
                                {{ $participant->messageable->first_name }} {{ $participant->messageable->last_name }}
                              </span>
                            </div>
                                @endif
                              @endforeach
                            @empty
                            <div class="hs-tooltip inline-block">
                              <img class="relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10 mx-auto" src="{{ asset('/storage/images/user.jpg') }}" alt="Avatar">
                            </div>
                            @endforelse
                          </div>
                          <div class="flex inline-flex px-2">
                            <p class="font-semibold">
                            {{ Auth::user()->unreadMessagesCount() }} Unread
                            <br>
                            Messages
                            </p>
                          </div>
                          <div class="flex inline-flex px-2 my-2 text-center">
                            <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="/client/inbox">
                              See all
                            </a>
                          </div>
                        </div>
                        <div class="w-[90%] block mx-auto pb-4 table-fixed" >
                          <div class="border-0 max-h-[50vh] lg:max-h-[75vh] max-w-full">
                            @foreach($recentConversations as $convo)
                              @foreach($convo->conversation->participants as $participant)
                                @if($participant->messageable->id != Auth::user()->id || $participant->messageable_type == 'App\Models\Vendor')
                                  <div class="border-b-4 border-win-light max-w-full">
                                    <div class="">
                                      <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                        <div class="flex items-center gap-x-3">
                                          <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$participant->messageable->image) }}" alt="Image Description">
                                          <div class="grow">
                                            <span class="block text-sm font-semibold text-gray-800">{{ $participant->messageable->first_name }} {{ $participant->messageable->last_name }}</span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="pr-3 py-1 text-left max-w-full">
                                        <p class="max-w-full break-words line-clamp-3">
                                        @if(Chat::conversation($convo->conversation)->setParticipant(Auth::user())->unreadCount() > 0)
                                        <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">!</span>
                                        @endif
                                          {{ $convo->conversation->last_message->body ?? "Start a conversation!" }}</p>
                                      </div>
                                    </div>
                                    </div>
                                  @endif
                                @endforeach
                            @endforeach
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="appointments-section" class="rounded-3xl bg-white min-h-96 col-span-1 m-2">
                <div class="overflow-x-hidden min-h-full min-w-full">
                  <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                    <h2 class="subheading mb-2">
                      My Wedding Appointments
                    </h2>
                  </div>
                  <div class="flex justify-center w-[90%] mx-auto py-6 border rounded-xl border-win-light border-4">
                    <div class="flex inline-flex px-2">
                      <p class="font-semibold">
                      {{ Auth::user()->upcomingMeetings()->where('approved', 1)->count() }} Meeting(s)
                      <br>
                      Upcoming
                      </p>
                    </div>
                    <div class="flex inline-flex px-2 my-2 text-center">
                      <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="/appointments">
                        See all
                      </a>
                    </div>
                  </div>
                  
                  <table class="w-[90%] mx-auto pb-4" >
                    <tbody class="border-0 max-h-[50vh] lg:max-h-[75vh] overflow-auto">
                      @foreach(Auth::user()->upcomingMeetings()->where('approved', 1)->get() as $meeting)
                        @php
                          $meeting_vendor = $meeting->vendor()->first();
                        @endphp
                        <tr class="border-b-4 border-win-light">
                          <td class="size-px">
                            <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 pt-2 pb-1">
                              <div class="flex items-center gap-x-3">
                                <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$meeting_vendor->image) }}" alt="Image Description">
                                <div class="grow">
                                  <span class="block text-sm font-semibold text-gray-800">{{ $meeting_vendor->business_name }}</span>
                                </div>
                              </div>
                            </div>
                            <div class="pr-2 py-1 text-left text-sm">
                              <p class="font-semibold">{{ ucfirst($meeting->type) }}</p>
                              <p>{{ $meeting->readableTime() }}</p>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="bg-white p-4 lg:p-8 rounded-3xl mt-4 md:mt-8">
              <div class="w-full mb-4">
                <h3><span class="subheading">Vendor Search Status:</span> <a href="/search/vendors" class="px-2 py-1 bg-win-blue text-white text-sm rounded-lg sm:ml-1 lg:ml-2">Find Your Vendors</a></h3>
                <p class="font-medium">All of your conversations with prospective vendors live here, so nothing gets lost.</p>
              </div>
              <x-carousel :slidesCount="$pairings->count()">
                <x-slot name="slides">
                  @foreach($pairings as $vendor)
                  <div class="hs-carousel-slide px-1">
                      <x-vendor-status-card :vendor="$vendor->vendor" :status="$vendor->status" :favorited="Auth::user()->hasFavorite($vendor->vendor->id)" />
                  </div>
                  @endforeach
                </x-slot>
              </x-carousel>
            </div>
            <div class="bg-white p-4 lg:p-8 rounded-3xl mt-4 md:mt-8">
              <div class="w-full mb-4">
                <h3 class="subheading">Favorited Vendors:</h3>
              </div>
              @if(count($favoritedVendors) == 0)
                <div class="p-4 my-4 h-48 flex flex-col justify-center items-center border border-dashed border-black rounded-xl text-center md:col-span-2 lg:col-span-5">
                  <h3 class="subheading">
                    Find Your Favorite Vendors Here!
                  </h3>
                  <a href="/search/vendors" class="py-1 px-4 text-sm font-medium rounded-lg bg-win-blue text-white">Search for Vendors</a>
                </div>
              @else
              <x-carousel :slidesCount="$favoritedVendors->count()">
                <x-slot name="slides">
                  @foreach($favoritedVendors as $vendor)
                  <div class="hs-carousel-slide px-1">
                      <x-vendor-status-card :vendor="$vendor" :status="Auth::user()->statusWith($vendor->id)" :favorited="1" />
                  </div>
                  @endforeach
                </x-slot>
              </x-carousel>
              @endif
            </div>
            <div class="bg-white p-4 lg:p-8 rounded-3xl mt-4 md:mt-8">
              @php
                $bookedVendors = $pairings->filter(function($p){
                  return $p->status > 2;
                });
                $bookedVendorsCount = $bookedVendors->count();
              @endphp
              <div class="w-full mb-4">
                  <div class="grid gap-3 md:flex md:justify-between md:items-center">
                    <h2 class="subheading mb-1">
                      Our Wedding Day
                      <button type="button" class="inline-flex items-center gap-x-1 text-white bg-win-blue px-2 py-1 font-medium rounded-lg ml-2" aria-haspopup="dialog" aria-expanded="false" aria-controls="edit-notes-modal" data-hs-overlay="#edit-notes-modal">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </button>
                      <x-large-modal id="edit-notes">
                        @include('modals.edit_notes')
                      </x-large-modal>
                    </h2>
                  </div>
                  <div>
                    <h5 class="mb-0 font-medium uppercase">{{ Auth::user()->daysUntilWedding() }} Days Until Our Wedding</h5>
                  </div>
              </div>
              <div class="hidden">
              <x-carousel :slidesCount="$bookedVendorsCount">
                <x-slot name="slides">
                  @foreach($bookedVendors as $pairing)
                  <div class="hs-carousel-slide px-1">
                      <x-vendor-status-card :vendor="$pairing->vendor" :status="$pairing->status" :favorited="Auth::user()->hasFavorite($vendor->id)" />
                  </div>
                  @endforeach
                </x-slot>
              </x-carousel>
              </div>
              
                <div class="overflow-x-hidden min-h-full min-w-full">
                  @if($profile->notes == null)
                  <div class="mx-auto body-copy space-y-2 mt-2 lg:mt-4">
                    <p class="text-base border-b-4 border-win-light line-clamp-[15]">
                      7:30 AM – Wake up & prepare for the arrival of hair & makeup ⛅ <br>
                      8:00 AM – Take 30 minutes to relax & review the plan for your BIG day! 💕 <br>
                      9:00 AM – Hair & makeup arrives 💄 <br>
                      12:00 PM – Day-of coordinator/wedding planner check-in ✅ <br>
                      1:00 PM – Hair & makeup complete, final touch-ups ☑️ <br>
                      1:30 PM – Photographer/Videographer arrives 📷🎥 <br>
                      2:30 PM – Get dressed (including wedding party) ✨👗 <br>
                      3:00 PM – Final getting ready detail pictures & portraits 📸 <br>
                      3:30 PM – First look with your significant other (if you choose) 💕 <br>
                      3:45 PM – Private vow reading (if you choose) 📖 <br>
                      3:45 PM – Couple’s portraits ❤️ <br>
                      4:15 PM – Wedding party & family formals 📷🎥 <br>
                      5:30 PM – Ceremony begins 💍✨ <br>
                      6:00 PM – Cocktail hour begins 🍸🥂 <br>
                      7:15 PM – Wedding party introductions 🎉 <br>
                      7:30 PM – Couple’s first dance & parent dances 🎵 <br>
                      7:45 PM – Speeches & toasts 🗒️🥂 <br>
                      8:00 PM – Dinner is served 🍴✨ <br>
                      9:00 PM – Cake cutting & dance floor opens 🎂💃🕺 <br>
                      11:00 PM – Wedding ends, but the memories last forever! 🎉💕</p>
                  </div>
                  @else
                  <div class="px-6 mx-auto body-copy space-y-2 mt-2">
                    <p class="text-base border-b-4 border-win-light line-clamp-[15]">{!! nl2br($profile->notes) !!}</p>
                  </div>
                  @endif
                  <button type="button" class="text-white bg-win-blue px-2 py-1 font-medium rounded-lg mx-auto block my-2 text-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="edit-notes-modal" data-hs-overlay="#edit-notes-modal">
                    View All
                  </button>
                </div>
            </div>
          </div>
      </div>
      @if(Auth::user()->getMainVendor() != null)
      <div class="w-full text-center text-white bg-win-purple py-4 lg:py-6">
          <p class="text-2xl lg:text-3xl font-semibold">Looking for more vendors?</p>
          <p class="lg:text-xl font-semibold">Check out your primary vendor's list for access to exclusive discounts!</p>
          <a class="w-[25%] mt-4 py-1 px-4 inline-flex justify-center items-center font-medium rounded-lg bg-win-purple border-2 border-white text-white" href="/vendor/profile/{{ Auth::user()->getMainVendor()->uuid }}#aff-vendors-section">
              View More
          </a>
      </div>
      @endif
      @include('layouts.footer')
    </main>
  </body>
  <script>
    $(".messageVendorButton").on("click", (el) => {
      let id = el.currentTarget.id;
      $.ajax({
          type: "GET",
          headers: {
          },
          url: "/client/message/" + el.currentTarget.id,
          success: function (data) {
            window.location = '/client/conversation/' + data;
          }
        });
    });
  </script>
</html>
