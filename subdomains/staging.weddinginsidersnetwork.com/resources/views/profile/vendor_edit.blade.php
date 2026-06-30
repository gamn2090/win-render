<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Edit Vendor Profile</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
    <style>
      :target {
        scroll-margin-top: 2rem;
      }
    </style>
  </head>
  <body class="antialiased overflow-x-hidden">
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
          margin: 10% auto;
          padding: 20px;
          background: white;
          width: 80%;
          max-width: 600px;
      }
      .cropper-container {
          height: 100%;
          width: 100%;
      }
    </style>
    @include('layouts.vendor_navigation')
    
    <main class="relative h-full min-h-screen transition-all duration-200 ease-in-out rounded-xl">
      <div class="bg-[#EDE9F5] rounded-t-3xl lg:mx-8 py-6">
        <div class="flex flex-wrap bg-white p-4 lg:px-8 lg:pt-8 lg:pb-6 rounded-3xl text-center container mx-auto">
          <div class="px-4 sm:px-6 lg:px-8 mx-auto">
            <!-- Card -->
            <div class="bg-white rounded-xl px-4 pt-4 pb-2 sm:px-7 sm:pt-7 sm:pb-4">
              <div class="mb-8">
                <h2 class="headline-small">
                  Profile
                </h2>
                <p class="">
                  Manage your name, password and account settings.
                </p>
              </div>
              @if (session('status') === 'profile-updated')
              <div class="bg-green text-sm text-white rounded-lg p-2 my-2" role="alert">
                <ul>
                  <li>Updated profile!</li>
                </ul>
              </div>
              @endif
              @php
                $user = Auth::guard("vendor")->user()
              @endphp
              <form method="POST" action="{{ route('vendor.profile.update') }}">
                @csrf
                @method('patch')
                <div class="grid sm:grid-cols-12 gap-2 sm:gap-6 text-left">
                  <div class="sm:col-span-3">
                    <label class="subheading mt-2 inline-block">
                      Profile photo
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <div class="flex items-center gap-2">
                      <div class="z-10 size-16 rounded-full overflow-x-hidden">
                        <img id="profileImagePreview" class="inline-block size-16 object-scale-down overflow-x-hidden rounded-full ring-2 ring-white" src="{{asset('/storage/images/'.Auth::user()->image)}}" alt="Profile Picture">
                      </div>
                      <div class="flex gap-x-2">
                        <div>
                          <div class="mt-4 sm:mt-auto sm:mb-1.5 flex justify-center sm:justify-start gap-2">
                            <button id="uploadImageButton" type="button"
                              class="py-2 px-3 inline-flex items-center gap-x-2 text-sm text-dark-grey-win font-medium rounded-lg border border-gray-200 bg-white shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                              <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" x2="12" y1="3" y2="15" />
                              </svg>
                              Upload Profile Picture
                            </button>
                            <input id="imageUpload" type="file" accept="image/*" hidden />
          
                            <div id="cropperModal">
                                <div class="modal-content">
                                    <div class="my-4">
                                        <img id="image" style="max-width: 100%; min-height:100%" />
                                    </div>
                                    <button type="button" id="cropButton" class="bg-win-purple mx-3 my-1 py-2 px-3 text-white rounded-lg">Upload</button>
                                    <button type="button" id="cancelButton">Cancel</button>
                                </div>
                            </div>
                            
                            <canvas id="croppedCanvas" style="display: none;"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="mt-4 bg-red text-sm text-white rounded-lg p-2 my-2" role="alert" id="badImageAlert"
                      hidden>
                      <ul>
                        <li>Unsupported file type. Please upload .png, .jpg, or .jpeg</li>
                      </ul>
                    </div>
                    <div class="mt-4 bg-green text-sm text-white rounded-lg p-2 my-2" role="alert" id="goodImageAlert"
                      hidden>
                      <ul>
                        <li>Uploaded: <span id="imageUploadName"></span></li>
                      </ul>
                    </div>
                  </div>

          
                  <div class="sm:col-span-3">
                    <label for="af-account-full-name" class="subheading mt-2 inline-block">
                      Full name
                    </label>
                  </div>

          
                  <div class="sm:col-span-9">
                    <div class="sm:flex">
                      <input value="{{ $user->first_name }}" name="first_name" id="af-account-full-name" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->first_name }}">
                      <input value="{{ $user->last_name }}" name="last_name" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->last_name }}">
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <label for="af-business-name" class="subheading mt-2 inline-block">
                      Business Name
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <input value="{{ $user->business_name }}" name="business_name" id="af-business-name" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->business_name }}">
                  </div>
          
                  <div class="sm:col-span-3">
                    <label for="af-account-email" class="subheading mt-2 inline-block">
                      Email
                    </label>
                  </div>

          
                  <div class="sm:col-span-9">
                    <input value="{{ $user->email }}" name="email" id="af-account-email" type="email" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->email }}">
                  </div>

          
                  <div class="sm:col-span-3">
                    <label for="af-account-bio" class="subheading mt-2 inline-block">
                      Bio
                    </label>
                  </div>

          
                  <div class="sm:col-span-9">
                    <textarea value="{{ $user->profile->bio }}" name="bio" id="af-account-bio" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none text-black" rows="6" placeholder="{{ $user->profile->bio }}">{{ $user->profile->bio }}</textarea>
                  </div>
                  <div class="sm:col-span-3">
                    <label for="discount" class="subheading mt-2 inline-block">
                      Preferred Pricing
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <select name="discount" id="discount" class="py-3 px-4 pe-9 block w-full border-1 text-sm rounded-lg bg-white">
                      <option value="0">$0</option>
                      <option value="50">$50</option>
                      <option value="75">$75</option>
                      <option value="100">$100</option>
                      <option value="150">$150</option>
                      <option value="200">$200</option>
                      <option value="250">$250</option>
                    </select>
                  </div>
                  <div class="sm:col-span-3">
                    <label for="avg_price" class="subheading mt-2 inline-block">
                      Average Package Price
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <select name="avg_price" id="avg_price" class="py-3 px-4 pe-9 block w-full border-1 text-sm rounded-lg bg-white">
                      <option value="1">$500 or less</option>
                      <option value="2">$500-$2,000</option>
                      <option value="3">$2,000-$3,000</option>
                      <option value="4">$3,000-$5,000</option>
                      <option value="5">$5,000-$8,000</option>
                      <option value="6">$8,000-$10,000</option>
                      <option value="7">$12,000 or more</option>
                    </select>
                  </div>

          
                  <div class="sm:col-span-3">
                    <label for="af-account-portfolio" class="subheading mt-2 inline-block">
                      Portfolio
                    </label>
                  </div>

          
                  <div class="sm:col-span-9">
                    <button type="button" id="openModalButton" class="bg-win-purple font-semibold text-white py-1 px-4 rounded-lg">
                      Edit Portfolio
                    </button>
                  </div>
          
                  <div class="sm:col-span-3" id="edit-calendar">
                    <label for="availability" class="subheading mt-2 inline-block">
                      Calendar
                    </label>
                    <p class="text-sm">
                      Select the dates you are unavailable or booked.
                    </p>
                  </div>

          
                  <div class="sm:col-span-9 flex">
                    <input id="availability" name="availability" class="py-1 px-4 rounded-lg my-auto flex self-center" placeholder="Change Availability" />
                  </div>
          
                  <div class="sm:col-span-3">
                    <label class="subheading mt-2 inline-block">
                      Referral Links
                    </label>
                  </div>

          
                  <div class="sm:col-span-9">
                    <div class="pb-4">
                      <p class="font-semibold">Vendor Referral</p>
                      <div class="flex">
                        <p class="select-all border border-2 border-win-blue rounded-lg py-2 px-4">https://weddinginsidersnetwork.com/ref/v/{{ urlencode(Auth::user()->business_name) }}</p>
                      </div>
                    </div>
                    <div>
                      <p class="font-semibold">Client Referral</p>
                      <div class="flex">
                        <p class="select-all border border-2 border-win-purple rounded-lg py-2 px-4">https://weddinginsidersnetwork.com/ref/c/{{ urlencode(Auth::user()->business_name) }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="mb-2 sm:col-span-12 mt-4 lg:mt-6">
                    <h2 class="headline-small text-center">
                      Your Business
                    </h2>
                    <p class="text-center">
                      Edit more specific details about your business.
                    </p>
                  </div>
                  @foreach($tag_types as $tag_type)
                  <div class="sm:col-span-3">
                    
                    @if($tag_type->input_type != 'account')
                    <label for="tag[{{ $tag_type->name }}]" class="subheading mt-2 inline-block">
                      {{ $tag_type->name }}
                    </label>
                    @endif
                  </div>
                  <div class="sm:col-span-9">
                    @if($tag_type->input_type == 'select')
                    <select name="tag[{{ $tag_type->name }}]" id="tag[{{ $tag_type->name }}]" class="py-3 px-4 pe-9 block w-full border-1 text-sm rounded-lg bg-white">
                      @php
                        $user_tag = $user->tags->where('name', $tag_type->name)->first();
                      @endphp
                      <option value="null" @if($user_tag == null) selected @endif>Select One</option>
                      @foreach(json_decode($tag_type->allowed_values, true) as $value)
                      <option value="{{ $value }}" @if($user_tag != null && $user_tag->value == $value) selected @endif>{{ $value }}</option>
                      @endforeach
                    </select>
                    @elseif($tag_type->input_type == 'checkbox')
                      @php
                        $user_tags = $user->tags->where('name', $tag_type->name)->pluck('value')->toArray();
                      @endphp
                        <div class="mt-1 hs-dropdown [--auto-close:inside] relative sm:inline-flex z-20">
                          <button id="hs-dropdown-auto-close-inside" type="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 w-full text-sm font-medium rounded-lg border border-[#6b7280] bg-white hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                            {{ $tag_type->name }}
                            <svg class="hs-dropdown-open:rotate-180 size-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                          </button>

                          <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden bg-white shadow-md rounded-lg mt-2" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-auto-close-inside">
                            <div class="p-1 space-y-0.5">
                              @foreach(json_decode($tag_type->allowed_values, true) as $option)
                              <div class="flex items-center gap-x-2 py-2 px-3 rounded-lg hover:bg-gray-100 hover:cursor-pointer">
                                <input id="tag[{{ $option }}]" name="tag[{{ $tag_type->name }}][]" value="{{ $option }}" type="checkbox" {{ in_array($option, $user_tags) ? 'checked' : '' }} class="shrink-0 rounded-sm text-win-purple focus:ring-win-purple checked:border-win-purple disabled:opacity-50 disabled:pointer-events-none">
                                <label for="tag[{{ $option }}]" class="grow cursor-pointer">
                                  <span class="block text-sm">{{ $option }}</span>
                                  
                                </label>
                              </div> 
                              @endforeach
                            </div>
                          </div>
                        </div>
                    @endif
                  </div>
                  @endforeach


                  <div class="mb-2 sm:col-span-12 mt-4 lg:mt-6">
                    <h2 class="headline-small text-center">
                      Linked Accounts
                    </h2>
                    <p class="text-center">
                      Add and manage external accounts linked with profile features.
                    </p>
                  </div>
                  <div class="sm:col-span-3">
                    <label for="af-business-website" class="subheading mt-2 inline-block">
                      Business Website
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <span>https://</span>
                    <input value="{{ $user->profile->business_link }}" name="business_link" id="af-business-website" type="text" class="py-2 px-3 pe-11 w-[65%] border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->profile->business_link }}">
                  </div>
                  <div class="sm:col-span-3">
                    <label for="af-business-facebook" class="subheading mt-2 inline-block">
                      Facebook
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <input value="{{ $user->profile->getLink('facebook') }}" name="facebook_link" id="af-business-facebook" type="text" class="py-2 px-3 pe-11 w-[65%] border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->profile->facebook_link }}">
                  </div>
                  <div class="sm:col-span-3">
                    <label for="af-business-instagram" class="subheading mt-2 inline-block">
                      Instagram
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <input value="{{ $user->profile->getLink('instagram') }}" name="instagram_link" id="af-business-instagram" type="text" class="py-2 px-3 pe-11 w-[65%] border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->profile->instagram_link }}">
                  </div>
                  <div class="sm:col-span-3">
                    <label for="af-business-linkedin" class="subheading mt-2 inline-block">
                      LinkedIn
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <input value="{{ $user->profile->getLink('linkedin') }}" name="linkedin_link" id="af-business-linkedin" type="text" class="py-2 px-3 pe-11 w-[65%] border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->profile->linkedin_link }}">
                  </div>
                  <div class="sm:col-span-3">
                    <div>
                      <label class="inline-block mt-1 subheading">
                        Google Reviews:
                      </label>
                      <span>
                        <div class="flex items-center mt-1">
                          @php($score = $user->profile->google_review_score)
                          @if($score != null)
                          @for($i = 0; $i < 5; ++$i)
                          @if($i <= round($score, 0, PHP_ROUND_HALF_UP))
                          <svg class="flex-shrink-0 size-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                          @else
                          <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                          @endif
                          @endfor
                          @else
                          <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                          <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                          <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                          <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                          <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                          @endif
                        </div>
                      </span>
                    </div>
                  </div>
                  <div class="sm:col-span-9">
                      @if($user->google_place_id == null)
                      <button class="bg-win-purple text-white px-4 py-1 font-semibold rounded-lg sm:mr-2 mb-1" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="link-google-place-modal" data-hs-overlay="#link-google-place-modal">
                        Link Business
                      </button>
                      <x-large-modal id="link-google-place">
                        @include('modals.link_place')
                      </x-large-modal>
                      @else
                        <button class="bg-win-red text-white px-4 py-1 rounded-lg sm:mr-2 mb-1" type="button" id="unlink-place-btn">
                          Remove Linked Business
                        </button>
                      @endif
                  </div>

                  <div class="sm:col-span-3">
                    <label for="af-service-radius" class="subheading mt-2 inline-block">
                      Service Radius (miles)
                    </label>
                  </div>
                  <div class="sm:col-span-9">
                    <input value="{{ $user->service_radius }}" name="service_radius" id="af-service-radius" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->service_radius }}">
                  </div>
                </div>
                <div class="mt-5 flex justify-end gap-x-2">
                  <button type="submit" class="py-1 px-4 inline-flex items-center gap-x-2 font-semibold rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">
                    Save changes
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Modal -->
    <div id="imageModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen">
        <!-- Modal overlay -->
        <div class="fixed inset-0 bg-black opacity-25 z-10"></div>
    
        <!-- Modal content -->
        <div class="bg-white rounded-lg p-8 mx-4 md:max-w-md w-full text-left z-20">
          <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Image Uploader</h1>
            <button type="button" id="closeModalButton" class="closeModalButton text-gray-500 hover:text-gray-700">
              <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.414 10l4.293-4.293a1 1 0 1 0-1.414-1.414L10 8.586l-4.293-4.293a1 1 0 0 0-1.414 1.414L8.586 10l-4.293 4.293a1 1 0 1 0 1.414 1.414L10 11.414l4.293 4.293a1 1 0 1 0 1.414-1.414L11.414 10z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>
          <div class="mt-2 sm:mt-auto sm:mb-2 flex justify-center sm:justify-start gap-2">
            <button id="uploadPortfolioImageButton" type="button"
              class="py-2 px-3 inline-flex items-center gap-x-2 text-sm text-win-purple font-medium rounded-lg border border-gray-200 bg-white shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
              <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="17 8 12 3 7 8" />
                <line x1="12" x2="12" y1="3" y2="15" />
              </svg>
              Upload Picture <i id="imageUploadSpinner" class="fas fa-circle-notch animate-spin text-lg hidden"></i>
            </button>
            <input id="portfolioImageUpload" type="file" accept="image/*" hidden multiple/>
          </div>
    
          <!-- Image display and sorting section -->
          <h3 class="text-xl font-bold mb-2">Uploaded Images:</h3>
          <div id="imageContainer" class="grid grid-cols-3 gap-4">
            @foreach($user->profile->portfolioImages() as $image)
            <div id="rm{{ str_replace('.', '', $image) }}" class="group relative block rounded-xl overflow-hidden">
              <div class="overflow-hidden">
                <img class="w-32 md:w-56 h-auto rounded-xl group-hover:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full" src="{{asset('/storage/images/'.$image)}}" alt="Portfolio Image">
              </div>
              <div class="absolute top-0 end-0 p-1">
                <button type="button" value="{{ $image }}" class="rm-image font-semibold text-red rounded-lg bg-white p-1 px-2">
                  X
                </button>
              </div>
            </div>
            @endforeach
          </div>
            <button type="button" class="closeModalButton rounded-lg bg-win-purple font-semibold text-white px-4 py-1 mt-4 text-white">
              Save Changes
            </button>
        </div>
      </div>
    </div>
    @include('layouts.footer')
    <script src="https://unpkg.com/jcrop"></script>
    <script>
      let avgPrice = {{ $user->avg_price }};
      $("#avg_price").val(avgPrice);
      $("#discount").val({{ $user->discount }});
    </script>
    <script>
      function cropToCircle(imageSource) {
          return new Promise((resolve, reject) => {
              // Create a new image element
              var img = new Image();

              // When the image has loaded
              img.onload = function() {
                  // Create a canvas element
                  var canvas = document.createElement('canvas');
                  var context = canvas.getContext('2d');

                  // Set canvas size to match image size
                  canvas.width = img.width;
                  canvas.height = img.height;

                  // Draw the image onto the canvas
                  context.drawImage(img, 0, 0, canvas.width, canvas.height);

                  // Create a new canvas for the rounded image
                  var roundedCanvas = document.createElement('canvas');
                  var roundedContext = roundedCanvas.getContext('2d');

                  // Set canvas size to match image size
                  roundedCanvas.width = canvas.width;
                  roundedCanvas.height = canvas.height;

                  // Draw a circle on the new canvas and use the image as a clipping mask
                  roundedContext.beginPath();
                  roundedContext.arc(canvas.width / 2, canvas.height / 2, Math.min(canvas.width, canvas.height) / 2, 0, 2 * Math.PI);
                  roundedContext.closePath();
                  roundedContext.clip();
                  roundedContext.drawImage(canvas, 0, 0, canvas.width, canvas.height);

                  // Convert the rounded canvas to a data URL
                  var roundedDataURL = roundedCanvas.toDataURL('image/png');

                  // Create a new image element with the rounded image
                  var roundedImage = new Image();
                  roundedImage.src = roundedDataURL;

                  // Resolve the promise with the rounded image
                  resolve(roundedImage);
              };

              // Set the image source
              img.src = imageSource;

              // If there's an error loading the image
              img.onerror = function() {
                  reject(new Error('Error loading image.'));
              };
          });
      }
    </script>
    <script>
      document.getElementById('imageUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.getElementById('image');
                imgElement.src = e.target.result;
                showModal();
            };
            reader.readAsDataURL(file);
        }
      });

      let cropper;
      let originalFileName;
      function showModal() {
          const modal = document.getElementById('cropperModal');
          modal.style.display = 'block';
          const image = document.getElementById('image');
          originalFileName = document.getElementById('imageUpload').files[0].name;
          cropper = new Cropper(image, {
              aspectRatio: 1,
              viewMode: 1,
          });
      }

      document.getElementById('cropButton').addEventListener('click', function() {
          const canvas = cropper.getCroppedCanvas({
              width: 300,
              height: 600,
          });
          canvas.toBlob(function(blob) {
              const fileInput = document.getElementById('imageUpload');
              const croppedFile = new File([blob], originalFileName, { type: "image/png", lastModified: Date.now() });
              const dataTransfer = new DataTransfer();
              dataTransfer.items.add(croppedFile);
              fileInput.files = dataTransfer.files;

              hideModal();
              cropper.destroy();
              cropper = null;

              // Call the uploadImage function
              uploadImage();
          }, 'image/png');
      });

      document.getElementById('cancelButton').addEventListener('click', function() {
          hideModal();
          cropper.destroy();
          cropper = null;
      });

      function hideModal() {
          const modal = document.getElementById('cropperModal');
          modal.style.display = 'none';
      }

    </script>
    <script>
      $(document).on('click','.rm-image', (el) => {
        console.log("removed image: " + $(el.target).val());
        let userData = {
          "image_name": $(el.target).val()
        }
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/vendor/remove/portfolio",
            data: userData,
            success: function (data) {
              Swal.fire({
                title: 'Success!',
                text: `You have removed this image from your portfolio.`,
                icon: 'success',
                confirmButtonText: 'Continue',
                confirmButtonColor: '#6432C8'
              });
              console.log(('#rm' + $(el.target).val()).replaceAll(".", ""));
              $(('#rm' + $(el.target).val()).replaceAll(".", "")).remove();
            }
          });
      });
      $("#uploadImageButton").on("click", () => {
        $("#imageUpload").trigger("click");
      });
      function uploadImage(){
        let userData = new FormData();
        userData.append('image', $("#imageUpload").prop('files')[0]);
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/vendor/upload/image",
            enctype: 'multipart/form-data',
            contentType: false,
            data: userData,
            processData: false,
            success: function (data) {
              location.reload(true);
            }
          });
      }
    </script>
    <script>
      $("#uploadPortfolioImageButton").on("click", () => {
        $("#portfolioImageUpload").trigger("click");
      });
      const openModalButton = document.getElementById('openModalButton');
      const imageModal = document.getElementById('imageModal');
      const imageUpload = document.getElementById('portfolioImageUpload');
      const imageContainer = document.getElementById('imageContainer');

      openModalButton.addEventListener('click', () => {
        imageModal.classList.remove('hidden');
      });

      $(".closeModalButton").on("click", () => {
        imageModal.classList.add('hidden');
      });

      imageUpload.addEventListener('change', (event) => {
        function resizeImages(files, maxWidth, maxHeight, callback) {
          const promises = [];
          Array.from(files).forEach(file => {
              const img = new Image();
              const reader = new FileReader();
              const promise = new Promise((resolve, reject) => {
                  reader.onload = function(event) {
                      img.src = event.target.result;
                      img.onload = function() {
                          let width = img.width;
                          let height = img.height;
                          if (width > maxWidth || height > maxHeight) {
                              const aspectRatio = width / height;
                              if (width > height) {
                                  width = maxWidth;
                                  height = maxWidth / aspectRatio;
                              } else {
                                  height = maxHeight;
                                  width = maxHeight * aspectRatio;
                              }
                          }
                          const canvas = document.createElement('canvas');
                          canvas.width = width;
                          canvas.height = height;
                          const ctx = canvas.getContext('2d');
                          ctx.drawImage(img, 0, 0, width, height);
                          canvas.toBlob(blob => {
                              resolve({ name: file.name, blob: blob });
                          }, 'image/jpeg', 1);
                      };
                      img.onerror = reject;
                  };
              });
              promises.push(promise);
              reader.readAsDataURL(file);
          });

          Promise.all(promises)
              .then(results => callback(results))
              .catch(error => console.error("Error resizing images:", error));
        }
        resizeImages($("#portfolioImageUpload").prop('files'), 1000, 1000, function(resizedImages) {
            let userData = new FormData();
            let iter = 0;
            resizedImages.forEach(({ name, blob }) => {
                userData.append('image' + String(iter), blob, name);
                iter +=1;
            });
            $("#imageUploadSpinner").css("display", "block");
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/vendor/upload/portfolio",
                enctype: 'multipart/form-data',
                contentType: false,
                data: userData,
                processData: false,
                success: function (data) {
                  
                  Swal.fire({
                    title: 'Success!',
                    text: `You have uploaded ${data.length} image(s) to your portfolio.`,
                    icon: 'success',
                    confirmButtonText: 'Continue',
                    confirmButtonColor: '#6432C8'
                  });
                  $("#imageUploadSpinner").css("display", "none");
                  data.forEach((im) => {
                  $('#imageContainer').append(`
                    <div id="rm` + im.replaceAll(".", "") + `" class="group relative block rounded-xl overflow-hidden">
                      <div class="overflow-hidden">
                        <img class="w-32 md:w-56 h-auto rounded-xl group-hover:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full" src="` + '/storage/images/' + im + `" alt="Portfolio Image">
                      </div>
                      <div class="absolute top-0 end-0 p-1">
                        <button type="button" value="` + im + `" class="rm-image font-semibold text-red rounded-lg bg-white p-1 px-2">
                          X
                        </button>
                      </div>
                    </div>
                  `);
                  });
                }
              });
            });
        });
    </script>
    <script>
      const fp = flatpickr("#availability", {
        minDate: "today",
        disable: {!! json_encode(Auth::user()->upcomingMeetings()->where('type', "!=", 'manual')->pluck('date'),true) !!},
        mode: "multiple",
        dateFormat: "Y-m-d",
        defaultDate: {!! json_encode(Auth::user()->upcomingMeetings()->where('type', 'manual')->pluck('date'),true) !!}
      });
    </script>
  </body>
</html>
