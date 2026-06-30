<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>WIN: User Registration</title>
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  @include('components.fonts')
</head>
@include('layouts.guest_navigation')

<body class="bg-win-light overflow-x-hidden">
  <style>
    #cropperModal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
    }

    #cropperModal .modal-content {
      margin: 2% auto;
      padding: 20px;
      background: white;
      width: 80%;
      max-width: 600px;
    }

    .cropper-container {
      height: auto;
      width: 100%;
    }
  </style>
  <!-- Stepper -->
  <div data-hs-stepper>
    <!-- Stepper Nav -->
    <div class="md:max-w-[80%] mx-auto">
      <div data-hs-stepper-content-item='{
              "index": 1
            }'>
        <div
          class="p-4 h-auto bg-gray-50 justify-center items-center">
          <div class="overflow-hidden">
            <!-- Hero -->
            <div class="relative overflow-hidden">
              <div class="md:grid md:grid-cols-2 md:gap-4">
                <div class="md:col">
                  <div class="hidden md:block text-center mb-2"><h1 class="headline-small">Find the small businesses in your community that will take your wedding day as personally as you do.</h1></div>
                  <div class="hidden md:block h-full rounded-lg bg-[url('/assets/img/couple-register/couple.jpg')] bg-no-repeat bg-center bg-cover"></div>
                </div>
                <div class="md:col">
                  <ul class="relative flex flex-row gap-x-2 items-center mt-4">
                    <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{"index": 1}'>
                      <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle mx-auto">
                        <span
                          class="size-7 flex justify-center items-center flex-shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 hs-stepper-active:bg-win-purple hs-stepper-active:text-white hs-stepper-success:bg-win-purple hs-stepper-success:text-white hs-stepper-completed:bg-win-purple hs-stepper-completed:group-focus:bg-pink-win">
                          <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">1</span>
                          <svg class="hidden flex-shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                          </svg>
                        </span>
                        <span class="ms-2 text-sm font-medium">
                          Your Profile
                        </span>
                      </span>
                    </li>

                    <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{"index": 2}'>
                      <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle mx-auto">
                        <span
                          class="size-7 flex justify-center items-center flex-shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 hs-stepper-active:bg-win-purple hs-stepper-active:text-white hs-stepper-success:bg-win-purple hs-stepper-success:text-white hs-stepper-completed:bg-win-purple hs-stepper-completed:group-focus:bg-pink-win">
                          <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">2</span>
                          <svg class="hidden flex-shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                          </svg>
                        </span>
                        <span class="ms-2 text-sm font-medium">
                          Vendors You Need
                        </span>
                      </span>
                    </li>
                  </ul>
                  <div class="mx-auto max-w-screen-md pr-4 md:max-w-screen-xl mt-4">
                    <div class="md:ps-8">
                      <div class="text-center">
                        <h1 class="headline-small">
                          Welcome to <span class="text-win-purple">Wedding Insiders Network</span>
                        </h1>
                        <p class="text-xl my-2">
                          You're just a few clicks away from finding the vendors your community raves about (and exclusive discounts, too)!
                        </p>
                        <p class="text-xl sm:text-2xl font-bold">Tell us about your wedding:</p>
                      </div>
                        <ul class="text-sm text-white bg-red rounded-lg space-y-1" id="formErrors">
                            
                        </ul>
                        @if($errors->any())
                        <ul class="text-sm text-white bg-red rounded-lg space-y-1">
                          @foreach ($errors->all() as $error)
                            <li class="px-2 py-2">{{ $error }}</li>
                          @endforeach
                        </ul>
                        @endif

                      <div class="md:grid md:grid-cols-7 md:gap-4 mt-4 md:mt-6">
                        <div class="text-center my-auto col-span-2">
                          <h2 class="text-center text-3xl font-bold">You <span
                                class="text-red subheading">*</span></h2>
                        </div>
                        <div
                          class="mx-auto max-w-2xl sm:flex sm:space-x-3 p-3 rounded-lg col-span-5">
                          <div class="pb-2 sm:pb-0 sm:flex-[1_0_0%]">
                            <label for="hs-first-name" class="block text-sm font-medium"><span
                                class="sr-only">First name</span></label>
                            <input type="text" id="hs-first-name"
                              class="py-3 px-4 block w-full border-0 rounded-full text-sm"
                              placeholder="First name">
                          </div>
                          <div
                            class="pt-2 sm:pt-0 sm:ps-3 sm:border-gray-200 sm:border-t-0 sm:border-s sm:flex-[1_0_0%]">
                            <label for="hs-last-name" class="block text-sm font-medium"><span
                                class="sr-only">Last name</span></label>
                            <input type="text" id="hs-last-name"
                              class="py-3 px-4 block w-full border-0 rounded-full text-sm"
                              placeholder="Last name">
                          </div>
                        </div>
                      </div>

                      <div
                        class="flex items-center text-xs uppercase before:flex-[1_1_0%] before:border-t before:me-6 after:flex-[1_1_0%] after:border-t after:ms-6 py-4">
                        &</div>
                      <div class="md:grid md:grid-cols-7 md:gap-4">
                        <div class="text-center my-auto col-span-2">
                          <h2 class="text-win-purple text-center text-3xl font-bold">Partner <span
                                class="text-red subheading">*</span></h2>
                        </div>
                        <div
                          class="mx-auto max-w-2xl sm:flex sm:space-x-3 p-3 col-span-5">
                          <div class="pb-2 sm:pb-0 sm:flex-[1_0_0%]">
                            <label for="hs-fiance-first-name" class="block text-sm font-medium"><span
                                class="sr-only">First name</span></label>
                            <input type="text" id="hs-fiance-first-name"
                              class="py-3 px-4 block w-full rounded-full text-sm border-0"
                              placeholder="First name">
                          </div>
                          <div
                            class="pt-2 sm:pt-0 sm:ps-3 sm:border-gray-200 sm:border-t-0 sm:border-s sm:flex-[1_0_0%]">
                            <label for="hs-fiance-last-name" class="block text-sm font-medium"><span
                                class="sr-only">Last name</span></label>
                            <input type="text" id="hs-fiance-last-name"
                              class="py-3 px-4 block w-full rounded-full border-0 text-sm"
                              placeholder="Last name">
                          </div>
                        </div>
                      </div>
                      <div class="space-y-2">
                        <div class="mt-4">
                          <div>
                            <label for="new-email" class="inline-block subheading">
                              Email <span class="text-red">*</span>
                            </label>
                          </div>
                          <div>
                            <input name="new-email" id="new-email" type="email" class="border-0 py-2 px-3 pe-11 mt-1 block w-full rounded-full disabled:opacity-50 disabled:pointer-events-none" placeholder="email@example.com">
                          </div>
                        </div>
                        <div class="mt-4 hidden">
                          <div>
                            <label for="phone" class="inline-block subheading">
                              Phone Number <span class="text-red">*</span>
                            </label>
                          </div>
                          <div>
                            <input name="phone" id="phone" type="number" class="border-0 py-2 px-3 pe-11 mt-1 block w-full rounded-full disabled:opacity-50 disabled:pointer-events-none" placeholder="1234567899">
                          </div>
                        </div>
                        <div class="mx-auto">
                          <div class="mt-4">
                            <label for="password_first" class="inline-block subheading">Password <span
                                class="text-red">*</span></label>
                            <button type="button" data-hs-toggle-password='{
                                                    "target": "#password_first"
                                                    }' id="show-client-register-password-toggle" class="inline-flex inline items-center z-20 px-2 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600 dark:text-neutral-600 dark:focus:text-blue-500">
                              <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                              </svg>
                            </button>
                            <input id="password_first" class="block mt-1 w-full rounded-full border-0 text-sm" type="password"
                              name="password" required autocomplete="new-password">
                          </div>
                          <div class="mt-4">
                            <label for="password_confirmation" class="inline-block subheading">Confirm Password <span
                                class="text-red">*</span></label>
                            <button type="button" data-hs-toggle-password='{
                                                    "target": "#password_confirmation"
                                                    }' id="show-password-confirmation-toggle" class="inline-flex inline items-center z-20 px-2 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600 dark:text-neutral-600 dark:focus:text-blue-500">
                              <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                              </svg>
                            </button>
                            <input id="password_confirmation" class="block mt-1 w-full rounded-full border-0 text-sm" type="password"
                              name="password_confirmation" required autocomplete="new-password">
                          </div>
                        </div>
                      </div>
                      <div class="mt-4 hidden">
                        <label for="user-bio"
                          class="inline-block subheading">
                          Tell us about you. Talk about anything from how you met, to what your day-to-day looks like! <span class="text-sm text-gray">(Optional)</span>
                        </label>
                        <textarea id="user-bio"
                          class="text-black py-2 px-3 block w-full border-0 rounded-lg disabled:opacity-50 disabled:pointer-events-none"
                          rows="3"
                          placeholder="Tell us about you and your wedding! This information will appear on your profile page."></textarea>
                      </div>
                      <div class="col-span-4 mt-4 hidden">
                        <div class="sm:col-span-4">
                          <label for="client-wedding-date"
                            class="inline-block subheading mb-2">
                            Wedding Date
                          </label>
                        </div>
                        <div class="sm:col-span-8">
                          <input id="client-wedding-date"
                            class="peer h-full w-full border-0 rounded-full rounded-[7px] px-3 py-3 font-sans text-sm font-normal outline outline-0 transition-all"
                            placeholder="Select a date" />
                        </div>
                        <div class="sm:col-span-4">
                          <label for="client-venue"
                            class="inline-block subheading mb-2 mt-2.5">
                            Wedding Location
                          </label>
                        </div>
                        <div class="sm:col-span-8">
                          <input id="client-venue" type="text"
                            class="py-3 px-3 pe-11 block w-full border-0 rounded-full text-sm disabled:opacity-50 disabled:pointer-events-none"
                            placeholder="City, State, Country and/or the Venue name if booked">
                        </div>
                      </div>
                      
                      @isset($ref_id)
                      <input id="ref_by" name="ref_by" type="text" value="{{ $ref_id }}" hidden>
                      @endisset
                      <input id="event" name="event" type="text" value="{{ $event }}" hidden>
                      <div class="mt-4 hidden">
                        <label for="booking_date"
                          class="inline-block subheading mb-2 mt-2.5">
                          How soon are you hoping to book most of your vendors?
                        </label>
                        <select id="booking_date" name="book_date" class="py-3 px-4 pe-9 block w-full border-0 text-sm rounded-full text-win-charcoal disabled:opacity-50 disabled:pointer-events-none">
                          <option value="0-3 months" selected>0-3 months</option>
                          <option value="3-6 months">3-6 months</option>
                          <option value="6 months - 1 year">6 months - 1 year</option>
                          <option value="1 year+">1 year+</option>
                        </select>
                      </div>
                      <a class="btn-next w-full mt-4 text-lg py-3 mx-auto inline-flex justify-center items-center gap-x-2 font-semibold rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none hover:cursor-pointer">
                        Next
                        <i class="fas fa-chevron-right text-sm"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-hs-stepper-content-item='{
              "index": 2
            }' style="display: none;">
        <div
          class="p-4 h-auto bg-gray-50 justify-center items-center">
          <div class="overflow-hidden">
            <!-- Hero -->
            <div class="relative overflow-hidden">
              <div class="md:grid md:grid-cols-2 md:gap-4">
                <div class="md:col">
                  <div class="hidden md:block md:absolute md:top-0 md:start-0 md:end-1/2 h-full rounded-lg bg-[url('/assets/img/couple-register/couple-2.jpg')] bg-no-repeat bg-center bg-cover"></div>
                </div>
                <div class="md:col">
                  <ul class="relative flex flex-row gap-x-2 items-center mt-4">
                    <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{"index": 1}'>
                      <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle mx-auto">
                        <span
                          class="size-7 flex justify-center items-center flex-shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200    hs-stepper-active:bg-win-purple hs-stepper-active:text-white hs-stepper-success:bg-win-purple hs-stepper-success:text-white hs-stepper-completed:bg-win-purple hs-stepper-completed:group-focus:bg-pink-win">
                          <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">1</span>
                          <svg class="hidden flex-shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                          </svg>
                        </span>
                        <span class="ms-2 text-sm font-medium">
                          Your Profile
                        </span>
                      </span>
                    </li>

                    <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{"index": 2}'>
                      <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle mx-auto">
                        <span
                          class="size-7 flex justify-center items-center flex-shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 hs-stepper-active:bg-win-purple hs-stepper-active:text-white hs-stepper-success:bg-win-purple hs-stepper-success:text-white hs-stepper-completed:bg-win-purple hs-stepper-completed:group-focus:bg-pink-win">
                          <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">2</span>
                          <svg class="hidden flex-shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                          </svg>
                        </span>
                        <span class="ms-2 text-sm font-medium">
                          Vendors You Need
                        </span>
                      </span>
                    </li>
                  </ul>
                  <div class="mx-auto max-w-screen-md pr-4 md:max-w-screen-xl mt-4">
                    <div class="md:ps-8">
                      <div class="">
                        <div class="h-auto bg-gray-50 justify-center items-center rounded-xl">
                          <div>
                            <div>
                              <h1 class="mb-2 text-center headline-small">What vendors are you looking to connect with?
                              </h1>
                              <h3 class="font-medium text-center">
                                Upon account creation we'll help match you with an in-network provider to gain access to exclusive vendor discounts!
                              </h3>
                            <div class="relative items-center mt-1 mx-auto text-center">
                              <div class="inline-flex items-center mt-2">
                                <input id="checkAll" name="select-all" type="checkbox" class="border-gray-200 rounded text-win-purple focus:ring-win-purple" aria-describedby="checkAll-description">
                              </div>
                              <label for="checkAll" class="ms-3 inline-flex -mt-1 font-semibold">
                                Select All
                              </label>
                            </div>
                              <div class="sm:flex justify-center items-center md:grid md:grid-cols-2 mt-4">
                                @php
                                $i = 0;
                                @endphp
                                @foreach($data["types"] as $vendorType)
                                @if($i == 0 || $i == 6)
                                <ul class="flex flex-col min-w-full">
                                  @endif
                                  <li
                                    class="sm:inline-flex list-none items-center gap-x-2 my-2 mx-2 py-3 px-4 text-sm font-medium bg-white rounded-full -mt-px">
                                    <div class="relative flex items-start w-full">
                                      <div class="flex items-center h-5">
                                        <input id="vt-{{ $vendorType->id }}" name="vt-{{ $vendorType->id }}" value="{{ $vendorType->id }}" type="checkbox"
                                          class="border-gray-200 text-pink-win accent-pink-win checked:border-pink-pin rounded disabled:opacity-50">
                                      </div>
                                      <label for="vt-{{ $vendorType->id }}" class="ms-3.5 block w-full text-gray-600 ">
                                        {{ $vendorType->type }} <span>
                                          <img src="{{ $vendorType->icon }}" class="h-auto max-h-6 inline text-win-purple ml-1" alt="Icon">
                                        </span>
                                      </label>
                                    </div>
                                  </li>
                                  @if($i == 5 || $i == 11)
                                </ul>
                                @endif

                                @php
                                $i += 1;
                                @endphp
                                @endforeach
                              </div>
                              <div class="max-w-1/2 text-center flex justify-center align-middle text-win-charcoal">
                                <label for="hs-meetups-near-you" class="flex px-4 md:px-5 pt-2">
                                  <span class="flex">
                                    <svg class="flex-shrink-0 mt-1 size-5" xmlns="http://www.w3.org/2000/svg" width="24"
                                      height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round">
                                      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                      <circle cx="9" cy="7" r="4" />
                                      <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                      <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span class="ms-5 block">Would you like our vendors to contact you directly?</span>
                                  </span>
                                  <div class="ml-4 flex justify-center items-center">
                                    <label for="hs-allow-contact" class="text-sm font-bold">
                                      No
                                    </label>
                                    <input type="checkbox" id="hs-allow-contact"
                                      class="relative w-[3.25rem] ml-4 h-7 text-pink-win accent-pink-win bg-gray-100 checked:bg-none checked:bg-blue-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-pink-win focus:ring-pink-win ring-offset-white focus:outline-none appearance-none
                                            before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:ring-0 before:transition before:ease-in-out before:duration-200" checked>
                                </label>
                                <label for="hs-allow-contact" class="text-sm font-bold ms-2">
                                  Yes
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="grid mt-4">
                          <p
                            class="inline-block subheading mb-2">
                            Policies
                          </p>
                          <div class="relative flex items-start">
                            <div class="flex items-center h-5 mt-1">
                              <input id="hs-checkbox-tos" name="hs-checkbox-tos" type="checkbox" class="border-gray-200 rounded text-win-purple focus:ring-win-purple" aria-describedby="hs-checkbox-tos-description">
                            </div>
                            <label for="hs-checkbox-tos" class="ms-3">
                              <span class="block text-sm font-semibold">Terms of Service</span>
                              <span id="hs-checkbox-tos-description" class="block text-sm text-win-blue underline"><a href="/policy/Wedding%20Insiders%20Network%20Terms%20of%20Use%20and%20Privacy%20Policy.pdf" target="_blank">View and accept our ToS</a></span>
                            </label>
                          </div>
                        </div>
                        <button id="btn-finish-setup" type="button" class="btn-next w-full mt-6 text-lg py-3 mx-auto inline-flex justify-center items-center gap-x-2 font-semibold rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">
                          Submit
                          <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                          </svg> <i id="submit-spinner" class="fas fa-circle-notch animate-spin text-lg" style="display: none;"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div hidden>
    <button id="btn-back" type="button"
      class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
      data-hs-stepper-back-btn>
      <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="m15 18-6-6 6-6" />
      </svg>
      Back
    </button>
    <button type="button" id="btn-next"
      class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
      data-hs-stepper-next-btn>
      Next
      <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="m9 18 6-6-6-6" />
      </svg>
    </button>
    <button type="button"
      class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
      style="display: none;">
      Finish
    </button>
    <button type="reset"
      class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
      data-hs-stepper-reset-btn style="display: none;">
      Reset
    </button>
  </div>
  </div>
  </div>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/gh/BossBele/cropzee@latest/dist/cropzee.min.js" defer></script>
  @vite('resources/js/couple-register.js')
  @isset($user)
  <script>
        $("#hs-first-name").val("{{ $user->first_name }}");
        $("#hs-last-name").val("{{ $user->last_name }}");
        $("#hs-fiance-first-name").val("{{ $user->fiance_first_name }}");
        $("#hs-fiance-last-name").val("{{ $user->fiance_last_name }}");
        $("#client-venue").val("{{ $user->wedding_location }}");
        $("#client-wedding-date").val("{{ $user->wedding_date }}");
        $("#new-email").val("{{ $user->email }}");
  </script>
  @endisset
  <script>
    $(document).ready(function() {
      $("#checkAll").on("click", function() {
        if ($(this).is(":checked")) {
          $("[id^=vt-]").each(function() {
            $(this).prop('checked', true);
          });
        } else {
          $("[id^=vt-]").each(function() {
            $(this).prop('checked', false);
          });
        }
      });

      //validation
      
      $(".btn-next").on("click", function() {
        if (($("#password_first").val() != $("#password_confirmation").val()) || $("#password_first").val() == "") {
          Swal.fire({
            title: 'Oops!',
            text: `Your passwords didn't match. Please try again!`,
            icon: 'error',
            confirmButtonText: 'Retry',
            confirmButtonColor: '#6432C8',
          });
          page -= 1;
          $("#btn-back").trigger("click");
          $("#btn-back").trigger("click");
          return;
        }
        if($("#new-email").val() == "" || $("#new-email").val().indexOf('@') == -1 || $("#new-email").val().indexOf('.') == -1) {
          Swal.fire({
            title: 'Oops!',
            text: `Please enter a valid email address!`,
            icon: 'error',
            confirmButtonText: 'Retry',
            confirmButtonColor: '#6432C8',
          });
          page -= 1;
          $("#btn-back").trigger("click");
          return;
        }
        if($("#hs-first-name").val() == "" || $("#hs-last-name").val() == "" || $("#hs-fiance-first-name").val() == "" || $("#hs-fiance-last-name").val() == "") {
          Swal.fire({
            title: 'Oops!',
            text: `Please fill out all required fields!`,
            icon: 'error',
            confirmButtonText: 'Retry',
            confirmButtonColor: '#6432C8',
          });
          page -= 1;
          $("#btn-back").trigger("click");
          return;
        }
      });
    });
  </script>

</html>