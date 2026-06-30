<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>Wedding Advertising For Vendors - Wedding Insider Network</title>
        <meta name="description" content="Wedding Insiders Network is a wedding advertising platform and community for vendors that promotes true community with authentic leads and transparent pricing."/>
          
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @include('components.fonts')
        
        <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
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
                height: auto;
                width: 100%;
            }
        </style>
        <script src="/assets/js/confetti-bundle.min.js"></script>
    </head>
    <body class="overflow-x-hidden flex flex-col min-h-screen">
        <div id="registrationSection" class="my-4 lg:my-12">
            <img src="/assets/img/logos/WIN-Secondary-Logo-PURPLE.png" class="w-[10vh] md:w-[15vh] lg:w-[22vh] mx-auto mb-2" />
            <h1 class="subheading text-center mb-4">VIP Vendor Pre-Registration Form</h1>
            <div class="flex flex-row items-center justify-center w-full">
                <div class="bg-[#EDE9F5] lg:mx-8 rounded-3xl pb-6 w-full pt-4 lg:pt-6">
                    <div class="w-full px-6 py-6 container mx-auto bg-white rounded-xl min-h-[50vh]">
                        <div id="registrationFormSection" class="">
                            <div class="mx-auto">
                                <label class="sr-only">
                                    Profile photo
                                </label>
                                <div class="sm:flex sm:items-center sm:justify-center sm:justify-items-center sm:gap-x-5 mx-auto">
                                    <div class="z-10 size-16 md:size-24 lg:size-32 rounded-full overflow-x-hidden">
                                        <img id="profileImagePreview" class="inline-block overflow-x-hidden rounded-full" src="/images/profile.jpg" alt="Profile Picture">
                                    </div>
                                    <div class="flex justify-center gap-2">
                                        <button id="uploadImageButton" type="button"
                                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm text-dark-grey-win font-medium rounded-lg border border-gray-200 bg-white">
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
                                                <button type="button" id="cropButton" class="bg-win-purple mx-3 my-1 py-1 px-4 text-white rounded-lg">Crop</button>
                                                <button type="button" id="cancelButton">Cancel</button>
                                            </div>
                                        </div>
                                        
                                        <canvas id="croppedCanvas" style="display: none;"></canvas>
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
                            <div id="vendorRegisterForm">
                                <ul class="text-sm text-white bg-red rounded-lg space-y-1" id="formErrors">
                                    
                                </ul>
                                <div>
                                    <div class="md:grid md:grid-cols-2 gap-4 mt-4 mx-4 lg:mx-16">
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="first_name" :value="__('First Name')" />
                                            <input id="first_name" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="first_name" :value="old('first_name')" required autocomplete="first_name" placeholder="First Name" />
                                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                        </div>
                                        
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="last_name" :value="__('Last Name')" />
                                            <input id="last_name" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" placeholder="Last Name" />
                                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                        </div> 
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="vendor_email" :value="__('Email')" />
                                            <input id="vendor_email" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="win@example.com"/>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="role_select" :value="__('Select Role')" />
                                            <select id="role_select" name="role" class="py-3 px-4 pe-9 block w-full border-0 text-sm rounded-full bg-win-light text-win-charcoal disabled:opacity-50 disabled:pointer-events-none">
                                                <option selected>Choose your role</option>
                                                @foreach($vendorTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold inline" for="password" :value="__('Password')" />
                                            <button type="button"  data-hs-toggle-password='{
                                                "target": "#password"
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
                                            <input id="password" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0"
                                                            type="password"
                                                            name="password"
                                                            required autocomplete="new-password" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold inline" for="password_confirmation" :value="__('Confirm Password')" />
                                            <button type="button"  data-hs-toggle-password='{
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
                
                                            <input id="password_confirmation" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0"
                                                            type="password"
                                                            name="password_confirmation" required autocomplete="new-password" />
                
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                    @isset($ref_id)
                                    <input name="ref_by" type="text" value="{{ $ref_id }}" hidden>
                                    @endisset
                                    <div>              
                                    </div>
                                </div>
                                <div class="md:grid md:grid-cols-2 gap-4 mx-4 lg:mx-16">
                                    <div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="business_name" :value="__('Business Name')" />
                                            <input id="business_name" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="business_name" :value="old('business_name')" required />
                                            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="weddings_num" :value="__('How many weddings do you service per year?')" />
                                            <input id="weddings_num" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="number" name="weddings_num" :value="old('weddings_num')" required/>
                                            <x-input-error :messages="$errors->get('weddings_num')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="business_website" :value="__('Business Website')" />
                                            <input id="business_website" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="business_website" :value="old('business_website')" required/>
                                            <x-input-error :messages="$errors->get('business_website')" class="mt-2" />
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="client_select" :value="__('What is your average client booking value?')" />
                                            <select id="booking_val" name="booking_val" class="py-3 px-4 pe-9 block w-full border-0 text-sm rounded-full bg-win-light text-win-charcoal disabled:opacity-50 disabled:pointer-events-none">
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
                                    </div>
                                </div>
                                <input id="offered_discount" name="offered_discount" type="number" hidden>
                                <div class="mt-3 mx-4 lg:mx-16">
                                    <x-input-label class="font-semibold" :value="__('Preferred pricing')" />
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-2 mt-2">
                                        <label for="discount-0" class="flex p-3 w-full bg-win-light border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                            <input type="radio" name="discount-val" value="0" class="shrink-0 mt-0.5 border-0 rounded-full text-win-blue focus:ring-win-blue disabled:opacity-50 disabled:pointer-events-none" id="discount-0">
                                            <span class="text-sm text-win-charcoal ms-3">$0</span>
                                        </label>
                                        <label for="discount-50" class="flex p-3 w-full bg-win-light border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                        <input type="radio" name="discount-val" value="50" class="shrink-0 mt-0.5 border-0 rounded-full text-win-blue focus:ring-win-blue disabled:opacity-50 disabled:pointer-events-none" id="discount-50">
                                        <span class="text-sm text-win-charcoal ms-3">$50</span>
                                        </label>
                                        <label for="discount-75" class="flex p-3 w-full bg-win-light border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                        <input type="radio" name="discount-val" value="75" class="shrink-0 mt-0.5 border-0 rounded-full text-win-blue focus:ring-win-blue disabled:opacity-50 disabled:pointer-events-none" id="discount-75">
                                        <span class="text-sm text-win-charcoal ms-3">$75</span>
                                        </label>
                                        <label for="discount-100" class="flex p-3 w-full bg-win-light border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                        <input type="radio" name="discount-val" value="100" class="shrink-0 mt-0.5 border-0 rounded-full text-win-blue focus:ring-win-blue disabled:opacity-50 disabled:pointer-events-none" id="discount-100" >
                                        <span class="text-sm text-win-charcoal ms-3">$100</span>
                                        </label>
                                        <label for="discount-150" class="flex p-3 w-full bg-win-light border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                        <input type="radio" name="discount-val" value="150" class="shrink-0 mt-0.5 border-0 rounded-full text-win-blue focus:ring-win-blue disabled:opacity-50 disabled:pointer-events-none" id="discount-150" >
                                        <span class="text-sm text-win-charcoal ms-3">$150</span>
                                        </label>
                                        <label for="discount-200" class="flex p-3 w-full bg-win-light border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                        <input type="radio" name="discount-val" value="200" class="shrink-0 mt-0.5 border-0 rounded-full text-win-blue focus:ring-win-blue disabled:opacity-50 disabled:pointer-events-none" id="discount-200" >
                                        <span class="text-sm text-win-charcoal ms-3">$200</span>
                                        </label>
                                        <label for="discount-250" class="flex p-3 w-full bg-win-light border-0 rounded-full text-sm focus:border-win-lavender focus:ring-win-lavender">
                                        <input type="radio" name="discount-val" value="250" class="shrink-0 mt-0.5 border-0 rounded-full text-win-blue focus:ring-win-blue disabled:opacity-50 disabled:pointer-events-none" id="discount-250" >
                                        <span class="text-sm text-win-charcoal ms-3">$250</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="md:grid md:grid-cols-2 gap-4 mx-4 lg:mx-16">
                                    <div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="location" :value="__('Location')" />
                                            <input id="location" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="location" :value="old('location')" required />
                                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="mt-4">
                                            <x-input-label class="font-semibold" for="service_radius" :value="__('Service Radius (miles)')" />
                                            <input id="service_radius" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="service_radius" :value="old('service_radius', 50)" required/>
                                            <x-input-error :messages="$errors->get('service_radius')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('offered_discount')" class="mt-2" />
                                <div class="flex items-center justify-end mt-4">
                                    <button id="register-btn" type="button" class="ms-4 py-1 px-4 font-semibold rounded-lg bg-win-blue text-white hover:scale-105 transition duration-150 ease-in-out disabled:opacity-50 disabled:pointer-events-none">REGISTER</button>

                                </div>
                            </div>
                        </div>
                        <div id="congratsSection" class="hidden">
                            <h3 class="headline-small text-center">Congratulations!</h3>
                            <p class="subheading mt-4 lg:max-w-2xl mx-auto text-center">Thank you so much for joining our VIP vendor early registration! Be on the lookout for an invite to our live launch event on January 14. We can't wait to help your business thrive in 2025! </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-auto">
            @include('layouts.footer')
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://unpkg.com/cropperjs"></script>
        @vite('resources/js/vendor-register.js')
    </body>
</html>

