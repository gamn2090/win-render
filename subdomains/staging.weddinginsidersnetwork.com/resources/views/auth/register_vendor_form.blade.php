<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>WIN: Vendor Registration</title>

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
  <script src="https://unpkg.com/cropperjs"></script>
  <script src="/assets/js/confetti.min.js"></script>
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js"></script>
  
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
      margin: 15% auto;
      padding: 20px;
      background: white;
      width: 80%;
      max-width: 600px;
    }

    .cropper-container {
      max-height: 400px;
      width: 100%;
    }
  </style>
  <!-- Stepper -->
  <div data-hs-stepper>
    <!-- Stepper Nav -->
    <div class="md:max-w-[75%] mx-auto">
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
                  <div class="hidden md:block text-center mb-2"><h1 class="headline-small">Where local couples find small businesses for their dream wedding.</h1></div>
                  <div class="hidden md:block h-full rounded-lg bg-no-repeat bg-center bg-cover" style="background-image: url('/assets/img/vendor-register/wedding-vendor-11.jpg');"></div>
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
                          Your Account
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
                          Your Business
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
                          The only site dedicated to connecting local couples with the highest-rated local vendors. WIN is 100% merit-based: no sponsored links, no high fees.
                          Join for free today.
                        </p>
                        <p class="text-xl sm:text-2xl font-bold">Let's set up your account:</p>
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


                      <div class="md:grid md:grid-cols-12 md:gap-4 mt-4 md:mt-6 mx-auto">
                        <div
                          class="mx-auto max-w-2xl sm:flex sm:space-x-3 p-3 rounded-lg col-span-12">
                          <div class="pb-2 sm:pb-0 sm:flex-[1_0_0%]">
                            <label for="hs-first-name" class="block text-sm font-medium"><span
                                class="sr-only">First name</span></label>
                            <input type="text" id="hs-first-name"
                              class="py-3 px-4 block w-full border-0 rounded-full text-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="First name*">
                          </div>
                          <div
                            class="pt-2 sm:pt-0 sm:ps-3 border-t border-gray-200 sm:border-t-0 sm:border-s sm:flex-[1_0_0%]">
                            <label for="hs-last-name" class="block text-sm font-medium"><span
                                class="sr-only">Last name</span></label>
                            <input type="text" id="hs-last-name"
                              class="py-3 px-4 block w-full border-0 rounded-full text-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Last name*">
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
                            <input name="new-email" id="new-email" type="email" class="border-0 py-2 px-3 pe-11 mt-1 block w-full rounded-full focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="email@example.com">
                          </div>
                        </div>
                        <div class="mx-auto">
                          <div class="mt-4">
                            <label for="password_first" class="inline-block subheading">Password <span
                                class="text-red">*</span></label>
                            <button type="button" data-hs-toggle-password='{
                                                    "target": "#password_first"
                                                    }' id="show-password-toggle" class="inline-flex inline items-center z-20 px-2 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600">
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
                                                    }' id="show-password-confirmation-toggle" class="inline-flex inline items-center z-20 px-2 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600">
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

                      <button type="button" class="btn-next w-full mt-4 text-lg py-3 mx-auto inline-flex justify-center items-center gap-x-2 font-semibold rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none hover:cursor-pointer">
                        Next
                        <i class="fas fa-chevron-right text-sm"></i>
                      </button>
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
                  <div class="hidden md:block md:absolute md:top-0 md:start-0 md:end-1/2 h-full rounded-lg bg-no-repeat bg-center bg-cover" style="background-image: url('/assets/img/vendor-register/vendor-register-2.jpg');"></div>
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
                          Your Account
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
                          Your Business
                        </span>
                      </span>
                    </li>
                  </ul>
                  <div class="mx-auto max-w-screen-md pr-4 md:max-w-screen-xl mt-4">
                    <div class="md:ps-8">
                        <div class="h-auto bg-gray-50 justify-center items-center rounded-xl">
                            <div>
                              <h1 class="mb-2 text-center headline-small">Tell us about your business
                              </h1>
                              <div class="col-span-4 mt-4">
                                <div class="sm:col-span-4">
                                  <label for="business-name"
                                    class="inline-block subheading mb-2">
                                    Business Name
                                  </label>
                                </div>
                                <div class="sm:col-span-8">
                                  <input id="business-name"
                                    class="h-full w-full border-0 rounded-full px-3 py-3 text-sm focus:border-gray-900 focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
                                    placeholder="" />
                                </div>
                                <div class="sm:col-span-4">
                                  <label for="location"
                                    class="inline-block subheading mb-2 mt-2.5">
                                    Location
                                  </label>
                                </div>
                                <div class="sm:col-span-8">
                                  <input id="location" type="text"
                                    class="py-3 px-3 pe-11 block w-full border-0 rounded-full text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    placeholder="City, State">
                                </div>
                              </div>
                              <div class="mt-4">
                                <label for="user-bio"
                                  class="inline-block subheading">
                                  Tell us about your business! <span class="text-sm text-gray">(Optional)</span>
                                </label>
                                <textarea id="user-bio"
                                  class="text-black py-2 px-3 block w-full border-0 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                  rows="3"
                                  placeholder="Tell us about you and your business! This information will appear on your profile page."></textarea>
                              </div>
                              <div class="mt-4">
                                <label for="avg-price"
                                  class="inline-block subheading mb-2 mt-2.5">
                                  What is your average package price?
                                </label>
                                <select id="avg-price" name="booking_val" class="py-3 px-4 pe-9 block w-full border-0 text-sm rounded-full bg-white text-win-charcoal disabled:opacity-50 disabled:pointer-events-none">
                                  <option selected>Choose a value</option>
                                  <option value="1">$500 or less</option>
                                  <option value="2">$500-$2,000</option>
                                  <option value="3">$2,000-$3,000</option>
                                  <option value="4">$3,000-$5,000</option>
                                  <option value="5">$5,000-$8,000</option>
                                  <option value="6">$8,000-$10,000</option>
                                  <option value="7">$12,000 or more</option>
                                </select>
                              </div>
                              
                              @isset($ref_id)
                              <input id="ref_by" name="ref_by" type="text" value="{{ $ref_id }}" hidden>
                              @endisset
                              <input id="event" name="event" type="text" value="{{ $event }}" hidden>
                              <input id="offered_discount" name="offered_discount" type="number" hidden>
                              <div class="mt-3">
                                <div class="flex mb-2 mt-2.5">
                                  <label for="discount-val"
                                    class="subheading">
                                    Preferred Pricing
                                  </label>
                                  <span class="hs-tooltip items-center">
                                    <div class="hs-tooltip-toggle items-center h-full align-middle">
                                      <svg class="align-middle ml-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                        <path d="M12 17h.01" />
                                      </svg>
                                      <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black font-medium text-white rounded shadow-sm max-w-[75vw]" role="tooltip">
                                        While you are not required to offer a discount, every vendor on WIN is encouraged to offer preferred pricing of up to $250 off. We keep our vendor fees lower than other sites to facilitate this.
                                        <br>
                                        No other platform passes on savings and exclusive offers to couples like we do, which keeps us ahead of the competition and ensures our vendors (like you!) stay busy with bookings.
                                      </span>
                                    </div>
                                  </span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-2 mt-2">
                                  <label for="discount-0" class="flex p-3 w-full bg-white border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                    <input type="radio" name="discount-val" value="0" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple ring-win-light focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" id="discount-0">
                                    <span class="text-sm text-win-charcoal ms-3">$0</span>
                                  </label>
                                  <label for="discount-50" class="flex p-3 w-full bg-white border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                    <input type="radio" name="discount-val" value="50" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" id="discount-50">
                                    <span class="text-sm text-win-charcoal ms-3">$50</span>
                                  </label>
                                  <label for="discount-75" class="flex p-3 w-full bg-white border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                    <input type="radio" name="discount-val" value="75" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" id="discount-75">
                                    <span class="text-sm text-win-charcoal ms-3">$75</span>
                                  </label>
                                  <label for="discount-100" class="flex p-3 w-full bg-white border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                    <input type="radio" name="discount-val" value="100" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" id="discount-100">
                                    <span class="text-sm text-win-charcoal ms-3">$100</span>
                                  </label>
                                  <label for="discount-150" class="flex p-3 w-full bg-white border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                    <input type="radio" name="discount-val" value="150" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" id="discount-150">
                                    <span class="text-sm text-win-charcoal ms-3">$150</span>
                                  </label>
                                  <label for="discount-200" class="flex p-3 w-full bg-white border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                    <input type="radio" name="discount-val" value="200" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" id="discount-200">
                                    <span class="text-sm text-win-charcoal ms-3">$200</span>
                                  </label>
                                  <label for="discount-250" class="flex p-3 w-full bg-white border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                    <input type="radio" name="discount-val" value="250" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" id="discount-250">
                                    <span class="text-sm text-win-charcoal ms-3">$250</span>
                                  </label>
                                </div>
                              </div>
                              <div class="mt-4">
                                <x-input-label class="subheading" for="role_select" :value="__('Business Type')" />
                                <select id="role_select" name="role" class="py-3 px-4 pe-9 block w-full border-0 text-sm rounded-full bg-white text-win-charcoal disabled:opacity-50 disabled:pointer-events-none">
                                  <option selected>Choose your business type</option>
                                  @foreach($vendor_types as $type)
                                  <option value="{{ $type->id }}">{{ $type->type }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="max-w-1/2 text-center flex justify-center align-middle text-win-charcoal hidden" hidden>
                                <label for="hs-meetups-near-you" class="flex px-4 md:px-5 pt-2">
                                  <span class="flex">
                                  </span>
                                  <div class="ml-4 flex justify-center items-center">
                                    <input type="checkbox" id="hs-allow-contact"
                                      class="relative w-[3.25rem] ml-4 h-7 text-pink-win accent-pink-win bg-gray-100 checked:bg-none checked:bg-blue-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-pink-win focus:ring-pink-win ring-offset-white focus:outline-none appearance-none
                                            before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:ring-0 before:transition before:ease-in-out before:duration-200" hidden>
                                </label>
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
                                  <span id="hs-checkbox-tos-description" class="block text-sm text-win-blue underline"><a href="/terms-of-service" target="_blank">View and accept our ToS</a></span>
                                </label>
                              </div>

                              <div class="relative flex items-start mt-2">
                                <div class="flex items-center h-5 mt-1">
                                  <input id="hs-checkbox-eligibility" name="hs-checkbox-eligibility" type="checkbox" class="border-gray-200 rounded text-win-purple focus:ring-win-purple" aria-describedby="hs-checkbox-eligibility-description">
                                </div>
                                <label for="hs-checkbox-eligibility" class="ms-3">
                                  <span class="block text-sm font-semibold">Vendor Eligibility</span>
                                  <span id="hs-checkbox-tos-description" class="block text-sm text-win-blue underline"><a href="/policy/Wedding%20Insiders%20Network%20Vendor%20Eligibility%20Policy.pdf" target="_blank">View and accept our Vendor Eligibility guidelines</a></span>
                                </label>
                              </div>
                            </div>
                        </div>
                        <div class="cf-turnstile mt-2" data-sitekey="0x4AAAAAAA50ldXvDRXieCCT"></div>
                          <div class="flex justify-center mt-2">
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

  @vite('resources/js/vendor-register-form.js')
</body>
<script>
  //$("#client-wedding-date").flatpickr({});
    $("#btn-next").on("click", function() {
        
      });
</script>

        
<script>
  @isset($ref_user)
    $("#hs-first-name").val("{!! $ref_user->first_name !!}");
    $("#hs-last-name").val("{!! $ref_user->last_name !!}");
    $("#new-email").val("{!! $ref_user->email !!}");
  @endisset
</script>

</html>