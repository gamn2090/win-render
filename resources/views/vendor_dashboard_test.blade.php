<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Vendor Dashboard Test</title>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.js" integrity="sha512-wqcdhB5VcHuNzKcjnxN9wI5tB3nNorVX7Zz9NtKBxmofNskRC29uaQDnv71I/zhCDLZsNrg75oG8cJHuBvKWGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.css" integrity="sha512-qc0GepkUB5ugt8LevOF/K2h2lLGIloDBcWX8yawu/5V8FXSxZLn3NVMZskeEyOhlc6RxKiEj6QpSrlAoL1D3TA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <script src="https://preline.co/assets/js/hs-apexcharts-helpers.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>window.userID = {{ Auth::user()->id }};</script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/chat.js')
    @include('components.fonts')
    <style>
      .button-text{
        font-size: 1rem;
      }
      .small-title{
        font-family: "NeulisNeue-Bold", sans-serif;
        font-size:1rem;
      }
    </style>
  </head>

  <body class="m-0 antialiased bg-win-charcoal overflow-x-hidden">
    @include('layouts.vendor_navigation')
    <div class="absolute w-full bg-win-light min-h-[4rem]"></div>
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
      <div class="w-full px-6 py-6 container mx-auto">
        <div class="flex flex-wrap -mx-6 bg-win-lavender p-4 lg:p-12 rounded-3xl text-center">
          <div class="w-full mb-6">
            <h3 class="headline-small text-center">&#127881; Good Morning, {{ Auth::user()->first_name }}!</h3>
          </div>
          <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-win-lavender rounded-3xl bg-clip-border">
              <div class="flex-auto p-4">
                  <div class="flex-none max-w-full">
                    <div>
                      <p class="mb-0 subheading uppercase" style="font-size:1rem;">CURRENT CLIENTS</p>
                      <h5 class="mb-0">{{ count($data["clients"]) }}</h5>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-win-lavender rounded-3xl bg-clip-border">
              <div class="flex-auto p-4">
                  <div class="flex-none max-w-full">
                    <div>
                      <p class="mb-0 subheading uppercase" style="font-size:1rem;">Category Ranking</p>
                      <h5 class="mb-0">#{{ $data["placement"] + 1 }} in {{ Auth::user()->getType()->type }}</h5>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-win-lavender rounded-3xl bg-clip-border">
              <div class="flex-auto p-4">
                  <div class="flex-none max-w-full">
                    <div>
                      <p class="mb-0 subheading uppercase" style="font-size:1rem;">Storefront Views</p>
                      <h5 class="mb-0">{{ Auth::user()->storefront_views }}</h5>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-win-lavender rounded-3xl bg-clip-border">
              <div class="flex-auto p-4">
                  <div class="flex-none max-w-full">
                    <div>
                      <p class="mb-0 subheading uppercase" style="font-size:1rem;">Vendor Partnerships</p>
                      <h5 class="mb-0">{{ Auth::user()->vendorPartnershipsCount() }}</h5>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full max-w-full px-3 my-4 lg:flex-none">
            <div class="">
              <!-- Card -->
              <div class="flex flex-col">
                <div class="overflow-x-hidden">
                  <div class="min-w-full inline-block align-middle">
                    <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
                      <!-- Header -->
                      <div class="px-6 py-6 grid gap-3 md:flex md:justify-between md:items-center">
                        <div>
                          <h2 class="subheading mb-2">
                            Clients
                          </h2>
                          <p class="">
                            Your current clients
                          </p>
                        </div>
                        <div>
                          <div class="inline-flex gap-x-2">
                            <a href="/vendor/booked" class="mx-auto py-2 px-10 font-semibold button-text rounded-full bg-win-light text-win-blue disabled:opacity-50 disabled:pointer-events-none">
                              VIEW ALL
                            </a>
                            <a href="/vendor/create/client" class="mx-auto py-2 px-10 font-semibold button-text rounded-full bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none"> 
                              ADD CLIENT
                            </a>
                          </div>
                        </div>
                      </div>
                      <!-- End Header -->

                      <!-- Table -->
                      <table class="w-[90%] mx-auto border-t-4 border-win-light" >
                        <thead class="bg-white px-4">
                          <tr>
                            <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Name
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Wedding Location
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Status
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Vendors
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Wedding Date
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-end">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Profile
                                </span>
                              </div></th>

                              <th scope="col" class="px-6 py-3 text-end">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Archive
                                </span>
                              </div></th>
                          </tr>
                        </thead>

                        <tbody class="border-0">
                          @foreach($data["clients"] as $client)
                          <tr class="border-t-4 border-win-light">
                            <td class="size-px whitespace-nowrap">
                              <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                <div class="flex items-center gap-x-3">
                                  <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$client->image) }}" alt="Image Description">
                                  <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800">{{ $client->first_name }} {{ $client->last_name }}</span>
                                    <span class="block text-sm text-gray-500">{{ $client->email }}</span>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="size-px w-64 whitespace-nowrap">
                              <div class="px-6 py-3">
                                <span class="block text-sm font-semibold text-gray-800">{{ $client->wedding_location }}</span>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-3">
                                <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                  <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                  </svg>
                                  Active
                                </span>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-3">
                                <div class="flex items-center gap-x-3">
                                  <span class="text-xs text-gray-500">{{ $client->vendorsCount() }}/{{ $client->getRequestedVendorCount() }}</span>
                                </div>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-3">
                                <span class="text-sm text-gray-500">{{ $client->wedding_date }}</span>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-1.5">
                                <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-purple px-3 py-2 font-medium rounded-full" href="/client/profile/{{ $client->uuid }}">
                                  View
                                </a>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-1.5">
                                <a class="inline-flex items-center gap-x-1 text-sm text-win-red font-medium py-2 px-3 rounded-full align-middle" href="/vendor/archive/{{ $client->id }}/{{ Auth::user()->id }}">
                                  <i class="fas fa-times"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                      <!-- End Table -->
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card -->
            </div>
          </div>
          <div class="w-full max-w-full px-3 my-4 lg:flex-none">
            <div class="">
              <!-- Card -->
              <div class="flex flex-col">
                <div class="overflow-x-hidden">
                  <div class="min-w-full inline-block align-middle">
                    <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
                      <!-- Header -->
                      <div class="px-6 py-6 grid gap-3 md:flex md:justify-between md:items-center">
                        <div>
                          <h2 class="subheading mb-2">
                            Preferred Vendors
                          </h2>
                          <p class="">
                            Your preferred vendors
                          </p>
                        </div>
                        <div>
                          <div class="inline-flex gap-x-2">
                            <a href="/vendor/create/vendors" class="mx-auto py-2 px-10 font-semibold button-text rounded-full bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none"> 
                              REFER VENDOR
                            </a>
                          </div>
                        </div>
                      </div>
                      <!-- End Header -->

                      <!-- Table -->
                      <table class="w-[90%] mx-auto border-t-4 border-win-light" >
                        <thead class="bg-white px-4">
                          <tr>
                            <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Name
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Location
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Status
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Profile
                                </span>
                              </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                              <div class="flex items-center gap-x-2">
                                <span class="small-title uppercase tracking-wide">
                                  Message
                                </span>
                              </div>
                            </th>
                          </tr>
                        </thead>

                        <tbody class="border-0 max-h-[50vh] lg:max-h-[75vh] overflow-auto">
                          @foreach($data["connections"] as $client)
                          <tr class="border-t-4 border-win-light">
                            <td class="size-px whitespace-nowrap">
                              <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                <div class="flex items-center gap-x-3">
                                  <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$client->image) }}" alt="Image Description">
                                  <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800">{{ $client->first_name }} {{ $client->last_name }}</span>
                                    <span class="block text-sm text-gray-500">{{ $client->email }}</span>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="size-px w-64 whitespace-nowrap">
                              <div class="px-6 py-3">
                                <span class="block text-sm font-semibold text-gray-800">{{ $client->location }}</span>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-3">
                                <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                  <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                  </svg>
                                  Active
                                </span>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-1.5">
                                <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-purple px-3 py-2 font-medium rounded-full" href="/vendor/profile/{{ $client->id }}">
                                  View
                                </a>
                              </div>
                            </td>
                            <td class="size-px whitespace-nowrap">
                              <div class="px-6 py-1.5">
                                <button class="inline-flex items-center gap-x-1 text-sm bg-win-purple p-3 rounded-full decoration-2 font-medium hover:cursor-pointer chat-window-btn" data-picture-url="{{ asset('/storage/images/'.$client->image) }}" data-vendor-name="{{ $client->first_name }} {{ $client->last_name }}" data-vendor-id="{{ $client->id }}">
                                  <i class="fas fa-comment-alt text-white"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                      <!-- End Table -->
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card -->
            </div>
          </div>
          <div class="w-full max-w-full px-3 my-4 lg:flex-none">
            <div class="relative flex min-w-0 flex-col rounded-2xl border-0 bg-white bg-clip-border p-6">
              <div class="p-4 pb-0 rounded-t-4">
                <h6 class="mb-0 subheading">Vendor Ranking</h6>
                @php
                  $clientRank = Auth::guard('vendor')->user()->clientCommunityRankValue();
                  $vcRank = Auth::guard('vendor')->user()->vendorCommunityRankValue();
                  $maxVCRank = $vcRank["max"];
                  $currVCRank = $vcRank["value"];
                  $starRating = Auth::guard('vendor')->user()->googleRating();
                  $vendorReferrals = Auth::guard('vendor')->user()->vendorReferrals();
                @endphp
              </div>

              <div id="chart" class="font-semibold w-[90%] mx-auto"></div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 my-4 lg:flex-none lg:grid lg:grid-cols-2">
            <div class="relative flex min-w-0 flex-col rounded-2xl border-0 bg-white bg-clip-border p-6">
              <h3 class="subheading text-center">Your Badges:</h3>
              <div class="mt-3 space-x-1 md:space-x-4 mx-auto">
                @if(Auth::guard('vendor')->user()->trendingBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->trendingBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/trending-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Trending: Top 15% in storefront views this month!
                  </span>
                </a>
                @endif
                @if(Auth::guard('vendor')->user()->earlyAdopterBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->earlyAdopterBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/early-adopter-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Early Adopter: Registered within 6 months of launch!
                  </span>
                </a>
                @endif
                @if(Auth::guard('vendor')->user()->fastResponderBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->fastResponderBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/fast-responder-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Fast Responder: Responds quickly to messages!
                  </span>
                </a>
                @endif
                @if(Auth::guard('vendor')->user()->communityBuilderBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->communityBuilderBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/community-builder-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Community Builder: This vendor frequently appears on other storefronts!
                  </span>
                </a>
                @endif
              </div>
            </div>
            <div class="relative flex min-w-0 flex-col rounded-2xl border-0 bg-white bg-clip-border p-6">
              <h3 class="subheading text-center">Locked Badges:</h3>
              <div class="mt-3 space-x-1 md:space-x-4 mx-auto">
                @if(!Auth::guard('vendor')->user()->trendingBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->trendingBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/trending-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Trending: Top 15% in storefront views this month!
                  </span>
                </a>
                @endif
                @if(!Auth::guard('vendor')->user()->earlyAdopterBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->earlyAdopterBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/early-adopter-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Early Adopter: Registered within 6 months of launch!
                  </span>
                </a>
                @endif
                @if(!Auth::guard('vendor')->user()->fastResponderBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->fastResponderBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/fast-responder-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Fast Responder: Responds quickly to messages!
                  </span>
                </a>
                @endif
                @if(!Auth::guard('vendor')->user()->communityBuilderBadge())
                <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 @if(Auth::guard('vendor')->user()->communityBuilderBadge()) border-win-purple @else border-gray @endif rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <img src="/images/community-builder-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                  <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                    Community Builder: This vendor frequently appears on other storefronts!
                  </span>
                </a>
                @endif
              </div>
            </div>
          </div>
            <div class="flex flex-col gap-y-3 lg:gap-y-5 p-4 md:p-5 mx-3 bg-white w-full border shadow-sm rounded-xl mt-4">
              <div class="inline-flex justify-center items-center">
                <span class="size-2 inline-block bg-green rounded-full me-2"></span>
                <span class="body-copy font-semibold uppercase">Your Subscription 
                  @if(Auth::guard('vendor')->user()->isSubscribed()) <a href="/billing-portal" class="bg-win-purple text-white px-3 py-1 rounded-full">Edit</a>@endif</span>
              </div>

              <div class="text-center">
                <h3 class="subheading" style="font-size: 2rem;">
                  @if(Auth::guard('vendor')->user()->isSubscribed())
                    {{ \Carbon\Carbon::parse(Auth::guard('vendor')->user()->subscriptions()->active()->first()->updated_at)->addMonths(1)->format('Y-m-d') }}
                    <dl class="flex justify-center items-center divide-x divide-gray-200 body-copy">
                      <p>Renewal Date</p>
                    </dl>
                  @else
                  Not Subscribed
                  @endif
                </h3>
              </div>
            <div class="absolute right-0">
                <img src="/assets/img/shapes/confetti-small-transparent.png" class="w-[60%] h-[60%]">
            </div>
            </div>
        </div>
      </div>
        <section id="community-vendors-section" class="mt-8 bg-win-light py-16">
            <h3 class="font-bold text-2xl text-center">Browse professionals in our network:</h3>
            <div class="mx-8 mt-3 justify-center">
              <div class="w-full">
                <div class="grid md:grid-cols-4 gap-4">
                  @php($vendors = App\Models\Vendor::take(4)->get())
                  @foreach($vendors as $vendor)
                  <div class="w-full p-4">
                    <div class="w-full mx-auto flex items-center justify-center">
                      <div class="flex flex-col">
                          <div class="w-[10rem] h-[10rem] rounded-xl mb-4 md:mb-6 mx-auto">
                              <img src="{{asset('/storage/images/'. $vendor->image)}}" alt="Profile" class="min-w-full max-w-full rounded-xl relative">
                          </div>
                          <div class="subheading text-center font-semibold">{{ $vendor->getType()->type }}</div>
                          <div class="headline-small text-center font-semibold mt-4">{{ $vendor->business_name }}</div>
                      </div>
                    </div>
                    <div class="px-6 pt-4 pb-2 flex">
                      <a class="mx-auto text-center bg-win-purple mt-2 py-2 px-8 inline-flex justify-center items-center font-semibold rounded-full text-white disabled:opacity-50 disabled:pointer-events-none" href="/vendor/profile/{{ $vendor->id }}">
                        VIEW PROFILE
                      </a>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
        <div class="w-full text-center text-white bg-win-purple py-4 lg:py-6">
            <p class="text-2xl lg:text-3xl font-semibold">View other vendors in your community</p>
            <p class=" lg:text-xl font-semibold">Connect, engage, refer & WIN</p>
            <a class="w-[25%] mt-4 py-3 px-4 inline-flex justify-center items-center font-medium rounded-full bg-win-purple border-2 border-white text-white shadow-sm disabled:opacity-50 disabled:pointer-events-none" href="/vendor/search/vendors">
                View More
            </a>
        </div>
        </section>
      <!-- end cards -->
    @include('chat.window')
    @include('layouts.footer')
    </main>
    
    

    <div id="paymentModal" class="hs-overlay size-full fixed top-5 start-0 z-[100] overflow-x-hidden overflow-y-auto" hidden>
      <!-- Features -->
      <div class="overflow-hidden">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto bg-dark-grey-win rounded-lg">
          <!-- Title -->
          <div class="mx-auto max-w-2xl mb-8 lg:mb-14 text-center">
            <h2 class="text-3xl lg:text-4xl text-white font-bold">
              Please subscribe to unlock vendor features.
            </h2>
          </div>
          <!-- End Title -->

          <div class="relative xl:w-10/12 xl:mx-auto">
            <!-- Grid -->
            <div class="grid grid-cols-1 gap-6 lg:gap-8">

              <div>
                <!-- Card -->
                <div class="p-4 relative z-10 min-h-full bg-white border border-dark-purple-win border-4 rounded-xl md:p-10">
                  <h3 class="text-xl font-bold text-gray-800">Vendor Access</h3>
                  <div class="text-sm text-gray-500">Access to Wedding Insiders Network</div>

                  <div class="mt-5">
                    <span class="text-6xl font-bold text-gray-800">$49</span>
                    <span class="text-lg font-bold text-gray-800">.99</span>
                    <span class="ms-3 text-gray-500">USD / monthly</span>
                  </div>

                  <div class="mt-5 grid sm:grid-cols-2 gap-y-2 py-4 first:pt-0 last:pb-0 sm:gap-x-6 sm:gap-y-0">
                    <!-- List -->
                    <ul class="space-y-2 text-sm sm:text-base">
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                          Feature placeholder
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                          Feature placeholder
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                          Feature placeholder
                        </span>
                      </li>
                    </ul>
                    <!-- End List -->

                    <!-- List -->
                    <ul class="space-y-2 text-sm sm:text-base">
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                          Feature placeholder
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                          Feature placeholder
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                          Feature placeholder
                        </span>
                      </li>
                    </ul>
                    <!-- End List -->
                  </div>

                  <div class="mt-5 grid grid-cols-2 gap-x-4 py-4 first:pt-0 last:pb-0">
                    <div>
                      <p class="text-sm text-gray-500">Cancel anytime.</p>
                      <p class="text-sm text-gray-500">No card required.</p>
                    </div>

                    <div class="flex justify-end">
                      <a href="/vendor/pay" class="py-3 px-4 inline-flex hover:cursor-pointer items-center gap-x-2 button-text rounded-full border border-transparent bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">Start subscription</a>
                    </div>
                    <div class="mx-auto w-full col-span-2">
                      <div
                          class="mx-auto w-full flex items-center uppercase before:flex-[1_1_0%] before:border-t before:me-6 after:flex-[1_1_0%] after:border-t after:ms-6 py-4">
                          Or</div>
                    </div>
                    <div class="col-span-2 w-full text-center">
                        <button id="continueGuest" type="button" class="underline gap-x-3.5 py-2 px-3 rounded-lg text-gray-800 hover:cursor-pointer focus:ring-2 focus:ring-blue-500">
                          Continue as guest
                        </button>
                    </div>
                    <div class="col-span-2 w-full text-center">
                      <form method="POST" action="/vendor/logout">
                        @csrf
                        <button type="submit" class="underline gap-x-3.5 py-2 px-3 rounded-lg text-gray-800 hover:cursor-pointer focus:ring-2 focus:ring-blue-500">
                          Logout
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End Card -->
              </div>
            </div>
            <!-- End Grid -->
          </div>

          <div class="mt-7 text-center">
            <p class="text-xs text-white">
              Prices in USD. Taxes may apply.
            </p>
          </div>
        </div>
      </div>
      <!-- End Features -->
    </div>
  </body>
    <script>
      $("#continueGuest").on('click', () => {
        $('#paymentModal').attr("hidden", true); 
        $('#paymentModal').toggleClass('hidden');
      });
    </script>
  @if(!Auth::guard('vendor')->user()->isSubscribed())
  <script>
    $( document ).ready(function() {
      console.log('hello');
      $('#paymentModal').attr("hidden", false); 
      $('#startSubscriptionButton').on('click', () => {
        console.log('paying...');
        $.ajax({
          type: "GET",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/vendor/pay",
          success: function (data) {
            if(data["url"]){
              window.open(data["url"], '_blank'); 
            }
          }
        });
      });
    });
  </script>
  @endif
  <script>
    var options = {
        series: [{
          name: 'Current Value',
        data: [0, {{ ceil(($vendorReferrals/2) * 15)}}, {{ ceil(($starRating/5) * 20)}}, {{ ceil(($currVCRank / $maxVCRank) * 25) }}, {{ ceil(($clientRank["value"] / $clientRank["max"]) * 30) }}]
        }, {
          name: 'Max Value',
          data: [10, 15, 20, 25, 30]
        }],
          chart: {
          type: 'bar',
          height: 430
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              position: 'top',
            },
          }
        },
        colors:['#D5C6E7', '#6432C8'],
        dataLabels: {
          enabled: true,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
        },
        tooltip: {
          shared: true,
          intersect: false
        },
        xaxis: {
          categories: ["BADGES", "VENDOR REFERRAL", "REVIEWS", "VENDOR COMMUNITY", "CLIENT COMMUNITY"],
          labels: {
            style: {
                fontWeight: 'bold',
                cssClass: 'small-title',
            },
          },
        },
        yaxis: {
          labels: {
            style: {
                fontWeight: 'bold',
                fontSize: '12px',
                maxWidth: '10%',
            },
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
  </script>
</html>
