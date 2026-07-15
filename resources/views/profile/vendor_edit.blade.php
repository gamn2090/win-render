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
    @vite(['resources/css/app.css', 'resources/css/vendor-messages.css', 'resources/css/vendor-edit-profile.css', 'resources/css/vendor-dashboard.css', 'resources/js/app.js', 'resources/js/vendor-edit-profile.js'])
    @include('components.fonts')
    <style>
      :target {
        scroll-margin-top: 2rem;
      }
    </style>
  </head>
  <body class="vep-page antialiased overflow-x-hidden">
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

    <main class="relative transition-all duration-200 ease-in-out">
      @include('layouts.dashboard_topbar', ['role' => 'vendor'])

      <header class="vm-hero">
        <div class="vm-hero__content">
          <h1 class="vm-hero__title">Edit Profile</h1>
          <p class="vm-hero__subtitle">Manage your name, password and account settings.</p>
        </div>
      </header>

      <div class="vep-content">
        <div class="vep-card">
          @if (session('status') === 'profile-updated')
            <div class="vep-alert vep-alert--success" role="alert">Updated profile!</div>
          @endif

          @php
            $user = Auth::guard('vendor')->user();
            $locationTagNames = ['state', 'city'];
            $locationTags = $tag_types->filter(fn ($t) => in_array(strtolower($t->name), $locationTagNames));
            $stateTag = $tag_types->first(fn ($t) => strcasecmp($t->name, 'State') === 0);
            $cityTag = $tag_types->first(fn ($t) => strcasecmp($t->name, 'City') === 0);
            $otherTags = $tag_types->filter(fn ($t) => $t->input_type != 'account' && ! in_array(strtolower($t->name), $locationTagNames) && strcasecmp($t->name, 'State') !== 0 && strcasecmp($t->name, 'City') !== 0 && strcasecmp($t->name, 'Location') !== 0);
            $vendorRefUrl = 'https://weddinginsidersnetwork.com/ref/v/' . urlencode($user->business_name);
            $clientRefUrl = 'https://weddinginsidersnetwork.com/ref/c/' . urlencode($user->business_name);
            $pricingOptions = [0, 50, 75, 100, 150, 200, 250];
          @endphp

          <form method="POST" action="{{ route('vendor.profile.update') }}" class="vep-form">
            @csrf
            @method('patch')

            <div class="vep-photo-row">
              <img id="profileImagePreview" class="vep-photo-row__avatar" src="{{ \App\Support\ProfileImageStorage::url($user->image) }}" alt="Profile Picture" width="80" height="80" />
              <button id="uploadImageButton" type="button" class="vep-btn-upload">
                <img src="{{ asset('assets/img/vendor-home/profile/upload.png') }}" alt="" class="vep-btn-upload__icon" width="18" height="18" />
                Upload Profile Photo
              </button>
              <input id="imageUpload" type="file" accept="image/*" hidden />
            </div>

            <div id="cropperModal">
              <div class="modal-content">
                <div class="my-4">
                  <img id="image" style="max-width: 100%; min-height:100%" alt="" />
                </div>
                <button type="button" id="cropButton" class="bg-win-purple mx-3 my-1 py-2 px-3 text-white rounded-lg">Upload</button>
                <button type="button" id="cancelButton">Cancel</button>
              </div>
            </div>
            <canvas id="croppedCanvas" style="display: none;"></canvas>

            <div class="vep-alert vep-alert--error" role="alert" id="badImageAlert" hidden>
              Unsupported file type. Please upload .png, .jpg, or .jpeg
            </div>
            <div class="vep-alert vep-alert--success" role="alert" id="goodImageAlert" hidden>
              Uploaded: <span id="imageUploadName"></span>
            </div>

            <div class="vep-name-row">
              <div class="vep-field vep-field--inline">
                <label class="vep-field__label vep-field__label--required" for="af-account-first-name">Full name</label>
                <input value="{{ $user->first_name }}" name="first_name" id="af-account-first-name" type="text" class="vep-input" placeholder="First Name" />
              </div>
              <div class="vep-field vep-field--inline">
                <label class="vep-field__label" for="af-account-last-name">Last name</label>
                <input value="{{ $user->last_name }}" name="last_name" id="af-account-last-name" type="text" class="vep-input" placeholder="Last Name" />
              </div>
              <div class="vep-field vep-field--inline">
                <label class="vep-field__label vep-field__label--required" for="af-business-name">Business name</label>
                <input value="{{ $user->business_name }}" name="business_name" id="af-business-name" type="text" class="vep-input" placeholder="Business Name" />
              </div>
            </div>

            <div class="vep-field">
              <label class="vep-field__label vep-field__label--required" for="af-account-email">Email</label>
              <input value="{{ $user->email }}" name="email" id="af-account-email" type="email" class="vep-input" placeholder="email@example.com" />
            </div>

            <div class="vep-field">
              <label class="vep-field__label" for="af-account-bio">Bio</label>
              <textarea name="bio" id="af-account-bio" class="vep-textarea" rows="5" placeholder="Tell couples what makes your business unique? Describe your style, experience, and what sets you apart. This will appear on your profile.">{{ $user->profile->bio }}</textarea>
            </div>

            <div class="vep-pricing-group">
              <div class="vep-field vep-field--pricing">
                <label class="vep-field__label">
                  Preferred pricing
                  <img src="{{ asset('assets/img/vendor-home/profile/interrogacion.png') }}" alt="" class="vep-field__hint-icon" width="18" height="18" title="Select your preferred pricing discount" />
                </label>
                <div class="vep-pricing" data-vep-pricing>
                  <input type="hidden" name="discount" id="discount" value="{{ $user->discount }}" />
                  @foreach ($pricingOptions as $price)
                    <button type="button" class="vep-pricing__btn {{ (int) $user->discount === $price ? 'is-active' : '' }}" data-pricing-value="{{ $price }}">${{ $price }}</button>
                  @endforeach
                </div>
              </div>

              <div class="vep-field vep-field--avg-price">
                <label class="vep-field__label" for="avg_price">What is your average package price range?</label>
                <div class="vep-select-wrap">
                  <select name="avg_price" id="avg_price" class="vep-select vep-select--icon">
                    <option value="" disabled @selected(empty($user->avg_price))>Choose a value. This helps us match you with the right couples.</option>
                    <option value="1" @selected($user->avg_price == 1)>$500 or less</option>
                    <option value="2" @selected($user->avg_price == 2)>$500-$2,000</option>
                    <option value="3" @selected($user->avg_price == 3)>$2,000-$3,000</option>
                    <option value="4" @selected($user->avg_price == 4)>$3,000-$5,000</option>
                    <option value="5" @selected($user->avg_price == 5)>$5,000-$8,000</option>
                    <option value="6" @selected($user->avg_price == 6)>$8,000-$10,000</option>
                    <option value="7" @selected($user->avg_price == 7)>$12,000 or more</option>
                  </select>
                  <img src="{{ asset('assets/img/vendor-home/profile/abrir.png') }}" alt="" class="vep-select-wrap__icon" width="12" height="12" />
                </div>
              </div>
            </div>

            <div class="vep-field">
              <label class="vep-field__label">Portfolio</label>
              <button type="button" id="openModalButton" class="vep-btn-orange">Edit Portfolio</button>
            </div>

            <div class="vep-field" id="edit-calendar">
              <label class="vep-field__label" for="availability">Calendar</label>
              <div class="vep-select-wrap vep-select-wrap--input vep-select-wrap--calendar">
                <input id="availability" name="availability" class="vep-input vep-input--icon" placeholder="Change Availability" />
                <img src="{{ asset('assets/img/vendor-home/profile/abrir.png') }}" alt="" class="vep-select-wrap__icon" width="12" height="12" />
              </div>
            </div>

            <div class="vep-field">
              <label class="vep-field__label">Referral links</label>
              <div class="vep-referral-row">
                <button type="button" class="vep-btn-orange" data-copy-ref="{{ $vendorRefUrl }}">Copy Vendor Referral Link</button>
                <button type="button" class="vep-btn-orange" data-copy-ref="{{ $clientRefUrl }}">Copy Client Referral Link</button>
              </div>
            </div>

            <div class="vep-field vep-field--location">
              <p class="vep-field__label vep-field__label--section">Your Business location</p>
              <div class="vep-grid-2 vep-location-grid">
                <div class="vep-field vep-field--inline">
                  <label for="vep-state" class="vep-field__label vep-field__label--upper">State</label>
                  <div class="vep-select-wrap">
                    @if ($stateTag && $stateTag->input_type === 'select')
                      @php $user_state = $user->tags->where('name', $stateTag->name)->first(); @endphp
                      <select name="tag[{{ $stateTag->name }}]" id="vep-state" class="vep-select vep-select--icon">
                        <option value="null" @selected($user_state == null)>State</option>
                        @foreach (json_decode($stateTag->allowed_values, true) as $value)
                          <option value="{{ $value }}" @selected($user_state != null && $user_state->value == $value)>{{ $value }}</option>
                        @endforeach
                      </select>
                    @else
                      <select id="vep-state" class="vep-select vep-select--icon" aria-label="State">
                        <option value="" selected disabled>State</option>
                      </select>
                    @endif
                    <img src="{{ asset('assets/img/vendor-home/profile/abrir.png') }}" alt="" class="vep-select-wrap__icon" width="12" height="12" />
                  </div>
                </div>
                <div class="vep-field vep-field--inline">
                  <label for="vep-city" class="vep-field__label vep-field__label--upper">City</label>
                  <div class="vep-select-wrap">
                    @if ($cityTag && $cityTag->input_type === 'select')
                      @php $user_city = $user->tags->where('name', $cityTag->name)->first(); @endphp
                      <select name="tag[{{ $cityTag->name }}]" id="vep-city" class="vep-select vep-select--icon">
                        <option value="null" @selected($user_city == null)>City</option>
                        @foreach (json_decode($cityTag->allowed_values, true) as $value)
                          <option value="{{ $value }}" @selected($user_city != null && $user_city->value == $value)>{{ $value }}</option>
                        @endforeach
                      </select>
                    @else
                      <select id="vep-city" class="vep-select vep-select--icon" aria-label="City">
                        <option value="" selected disabled>City</option>
                      </select>
                    @endif
                    <img src="{{ asset('assets/img/vendor-home/profile/abrir.png') }}" alt="" class="vep-select-wrap__icon" width="12" height="12" />
                  </div>
                </div>
              </div>
            </div>

            <div class="vep-field">
              <div class="vep-grid-2 vep-input-row--social">
                <div class="vep-field--social">
                  <label for="af-business-website" class="vep-field__label">Business website</label>
                  <input value="{{ filled($user->profile->business_link) ? $user->profile->business_link : '' }}" name="business_link" id="af-business-website" type="text" class="vep-input" placeholder="your Business website Link" />
                </div>
                <div class="vep-field--social">
                  <label for="af-business-facebook" class="vep-field__label">Facebook link</label>
                  <input value="{{ filled($user->profile->facebook_link) ? $user->profile->getLink('facebook') : '' }}" name="facebook_link" id="af-business-facebook" type="text" class="vep-input" placeholder="your business facebook Link" />
                </div>
                <div class="vep-field--social">
                  <label for="af-business-linkedin" class="vep-field__label">LinkedIn link</label>
                  <input value="{{ filled($user->profile->linkedin_link) ? $user->profile->getLink('linkedin') : '' }}" name="linkedin_link" id="af-business-linkedin" type="text" class="vep-input" placeholder="your business linkedin Link" />
                </div>
                <div class="vep-field--social">
                  <label for="af-business-instagram" class="vep-field__label">Instagram link</label>
                  <input value="{{ filled($user->profile->instagram_link) ? $user->profile->getLink('instagram') : '' }}" name="instagram_link" id="af-business-instagram" type="text" class="vep-input" placeholder="your business instagram Link" />
                </div>
              </div>
            </div>

            <div class="vep-field">
              <label class="vep-field__label">Google reviews</label>
              @if ($user->google_place_id == null)
                <button class="vep-btn-orange" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="link-google-place-modal" data-hs-overlay="#link-google-place-modal">
                  Link Business
                </button>
                <x-large-modal id="link-google-place">
                  @include('modals.link_place')
                </x-large-modal>
              @else
                <button class="vep-btn-orange vep-btn-orange--danger" type="button" id="unlink-place-btn">
                  Remove Linked Business
                </button>
              @endif
            </div>

            <div class="vep-field">
              <label for="af-service-radius" class="vep-field__label">Service radius (miles)</label>
              <input value="{{ $user->service_radius }}" name="service_radius" id="af-service-radius" type="text" class="vep-input" placeholder="Add Miles Here" />
            </div>

            @foreach ($otherTags as $tag_type)
              <div class="vep-field">
                @if ($tag_type->input_type == 'select')
                  <label for="tag-{{ Str::slug($tag_type->name) }}" class="vep-field__label">{{ $tag_type->name }}</label>
                  @php $user_tag = $user->tags->where('name', $tag_type->name)->first(); @endphp
                  <select name="tag[{{ $tag_type->name }}]" id="tag-{{ Str::slug($tag_type->name) }}" class="vep-select">
                    <option value="null" @selected($user_tag == null)>Select One</option>
                    @foreach (json_decode($tag_type->allowed_values, true) as $value)
                      <option value="{{ $value }}" @selected($user_tag != null && $user_tag->value == $value)>{{ $value }}</option>
                    @endforeach
                  </select>
                @elseif ($tag_type->input_type == 'checkbox')
                  <label class="vep-field__label">{{ $tag_type->name }}</label>
                  @php $user_tags = $user->tags->where('name', $tag_type->name)->pluck('value')->toArray(); @endphp
                  <div class="vep-tags-dropdown mt-1 hs-dropdown [--auto-close:inside] relative inline-flex w-full z-20">
                    <button id="hs-dropdown-{{ Str::slug($tag_type->name) }}" type="button" class="hs-dropdown-toggle" aria-haspopup="menu" aria-expanded="false">
                      {{ $tag_type->name }}
                      <svg class="hs-dropdown-open:rotate-180 size-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    </button>
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden bg-white shadow-md rounded-lg mt-2 w-full" role="menu">
                      <div class="p-1 space-y-0.5">
                        @foreach (json_decode($tag_type->allowed_values, true) as $option)
                          <div class="flex items-center gap-x-2 py-2 px-3 rounded-lg hover:bg-gray-100 hover:cursor-pointer">
                            <input id="tag-{{ Str::slug($tag_type->name) }}-{{ Str::slug($option) }}" name="tag[{{ $tag_type->name }}][]" value="{{ $option }}" type="checkbox" @checked(in_array($option, $user_tags)) class="shrink-0 rounded-sm text-win-purple focus:ring-win-purple checked:border-win-purple">
                            <label for="tag-{{ Str::slug($tag_type->name) }}-{{ Str::slug($option) }}" class="grow cursor-pointer text-sm">{{ $option }}</label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            @endforeach

            <div class="vep-save-wrap">
              <button type="submit" class="vep-btn-save">Save changes</button>
            </div>
          </form>
        </div>
      </div>

      <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
      {{-- Site footer disabled per client request — uncomment to restore --}}
      {{-- @include('layouts.footer') --}}
    </main>
    <!-- Modal -->
    <div id="imageModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen">
        <!-- Modal overlay -->
        <div class="fixed inset-0 bg-black opacity-25 z-10"></div>

        <!-- Modal content -->
        <div class="piu-modal z-20">
          <button type="button" id="closeModalButton" class="closeModalButton piu-close" aria-label="Close">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
              <path d="M5 5l10 10M15 5L5 15" />
            </svg>
          </button>

          <div class="piu-icon" aria-hidden="true">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5">
              <rect x="6" y="6" width="36" height="36" rx="9" />
              <circle cx="18" cy="18" r="3.5" />
              <path d="M42 31l-10.5-10.5-9 9-5-5L6 36" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <h1 class="piu-title" id="modal-title">Image Uploader</h1>

          <div class="piu-upload-row">
            <button id="uploadPortfolioImageButton" type="button" class="piu-upload-btn">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="17 8 12 3 7 8" />
                <line x1="12" x2="12" y1="3" y2="15" />
              </svg>
              Upload Pictures <i id="imageUploadSpinner" class="fas fa-circle-notch animate-spin text-lg hidden"></i>
            </button>
            <input id="portfolioImageUpload" type="file" accept="image/*" hidden multiple/>
          </div>

          <!-- Image display and sorting section -->
          <h3 class="piu-label">Upload Images:</h3>
          <div id="imageContainer" class="piu-grid"></div>

          <div class="piu-actions">
            <button type="button" class="closeModalButton piu-btn piu-btn--cancel">Cancel</button>
            <button type="button" class="closeModalButton piu-btn piu-btn--save">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    <script src="https://unpkg.com/jcrop"></script>
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
      $("#uploadImageButton").on("click", () => {
        $("#imageUpload").trigger("click");
      });
      function uploadImage(){
        const file = document.getElementById('imageUpload')?.files?.[0];
        const preview = document.getElementById('profileImagePreview');
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        fetch('{{ route('vendor.upload.image') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
          },
          body: formData,
          credentials: 'same-origin',
        })
          .then(function (response) {
            return response.json().then(function (data) {
              if (!response.ok) throw new Error(data.message || 'Upload failed');
              return data;
            });
          })
          .then(function (data) {
            const cacheBust = '?t=' + Date.now();
            if (preview && data.image_url) {
              preview.src = data.image_url + cacheBust;
            }
            const sidebarAvatar = document.getElementById('vendor-sidebar-avatar');
            if (sidebarAvatar && data.image_url) {
              sidebarAvatar.src = data.image_url + cacheBust;
            }
            const goodAlert = document.getElementById('goodImageAlert');
            const nameEl = document.getElementById('imageUploadName');
            if (goodAlert) goodAlert.hidden = false;
            if (nameEl) nameEl.textContent = data.filename || file.name;
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                title: 'Photo updated',
                text: 'Your profile photo was saved. No need to click Save unless you changed other fields.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6432C8',
              });
            }
          })
          .catch(function (err) {
            const message = err?.message || 'Could not upload profile photo. Please try again.';
            if (typeof Swal !== 'undefined') {
              Swal.fire({ title: 'Upload failed', text: message, icon: 'error', confirmButtonColor: '#6432C8' });
            } else {
              alert(message);
            }
          });
      }
    </script>
    <script>
      let portfolioImages = @json($user->profile->portfolioImages());
      const portfolioCsrfToken = $('meta[name="csrf-token"]').attr('content');

      function renderPortfolioGrid() {
        const container = document.getElementById('imageContainer');
        container.innerHTML = '';
        portfolioImages.forEach((im) => {
          const item = document.createElement('div');
          item.id = 'rm' + im.replaceAll('.', '');
          item.className = 'piu-item';
          item.innerHTML = `
            <img class="piu-item__img" src="/storage/images/${im}" alt="Portfolio Image">
            <button type="button" value="${im}" class="rm-image piu-item__remove" aria-label="Remove image">&times;</button>
          `;
          container.appendChild(item);
        });
      }

      renderPortfolioGrid();

      $(document).on('click', '.rm-image', (el) => {
        const imageName = $(el.target).val();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': portfolioCsrfToken
            },
            url: "/vendor/remove/portfolio",
            data: { image_name: imageName },
            success: function (data) {
              Swal.fire({
                title: 'Success!',
                text: `You have removed this image from your portfolio.`,
                icon: 'success',
                confirmButtonText: 'Continue',
                confirmButtonColor: '#6432C8'
              });
              portfolioImages = portfolioImages.filter((im) => im !== imageName);
              renderPortfolioGrid();
            }
          });
      });

      $("#uploadPortfolioImageButton").on("click", () => {
        $("#portfolioImageUpload").trigger("click");
      });
      const openModalButton = document.getElementById('openModalButton');
      const imageModal = document.getElementById('imageModal');
      const imageUpload = document.getElementById('portfolioImageUpload');

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
                    'X-CSRF-TOKEN': portfolioCsrfToken
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
                  portfolioImages = portfolioImages.concat(data);
                  renderPortfolioGrid();
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
