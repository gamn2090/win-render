<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Edit User Profile</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>
  <body class="m-0 font-sans antialiased font-normal overflow-x-hidden text-base leading-default bg-dark-grey-win">
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
          width: 100%;
          height: auto;
      }
  </style>
    @include('layouts.navigation')
    <div class="absolute w-full bg-win-light min-h-[18.75rem] -z-10"></div>
    <!-- Card Section -->
    <div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
      <!-- Card -->
      <div class="bg-white rounded-xl shadow px-4 pt-4 pb-2 sm:px-7 sm:pt-7 sm:pb-4">
        <div class="mb-8">
          <h2 class="text-xl font-bold text-gray-800">
            Edit Profile
          </h2>
          <p class="text-sm text-gray-600">
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
        @php($user = Auth::guard("web")->user())
        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('patch')
          <!-- Grid -->
          <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
            <div class="sm:col-span-3">
              <label class="inline-block text-sm text-gray-800 mt-2.5 font-semibold">
                Profile photo
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <div class="flex items-center gap-5">
                <div class="z-10 size-16 rounded-full overflow-x-hidden">
                  <img id="profileImagePreview" class="inline-block absolute h-[4rem] max-w-[4rem] object-cover overflow-x-hidden rounded-full ring-2 ring-white" src="{{ \App\Support\ProfileImageStorage::url($user->image) }}" alt="Profile Picture">
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
                      <input id="imageUpload" type="file" accept=".png,.jpg,.jpeg" hidden />
    
                      <div id="cropperModal">
                          <div class="modal-content">
                              <div>
                                  <img id="image" style="max-width: 100%; min-height:100%" />
                              </div>
                              <button type="button" id="cropButton" class="bg-win-purple mx-3 my-1 py-2 px-3 text-white rounded-lg">Crop</button>
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
            <!-- End Col -->
    
            <div class="sm:col-span-3">
              <label for="af-account-full-name" class="inline-block text-sm text-gray-800 mt-2.5 font-semibold">
                Full name
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <div class="sm:flex">
                <input value="{{ $user->first_name }}" name="first_name" id="af-account-full-name" type="text" class="py-2 px-3 pe-11 block w-full border-0 bg-win-light -mt-px -ms-px first:rounded-t-full last:rounded-b-full sm:first:rounded-s-full sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-full text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->first_name }}">
                <input value="{{ $user->last_name }}" name="last_name" type="text" class="py-2 px-3 pe-11 block w-full border-l-0 sm:border-l-2 border-t-0 border-b-0 border-r-0 bg-win-light -mt-px -ms-px first:rounded-t-full last:rounded-b-full sm:first:rounded-s-full sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-full text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->last_name }}">
              </div>
            </div>

            <div class="sm:col-span-3">
              <label for="af-account-full-name" class="inline-block text-sm text-gray-800 mt-2.5 font-semibold">
                Fiance name
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <div class="sm:flex">
                <input value="{{ $user->fiance_first_name }}" name="fiance_first_name" id="af-account-fiance-full-name" type="text" class="py-2 px-3 pe-11 block w-full border-0 bg-win-light -mt-px -ms-px first:rounded-t-full last:rounded-b-full sm:first:rounded-s-full sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-full text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->fiance_first_name }}">
                <input value="{{ $user->fiance_last_name }}" name="fiance_last_name" type="text" class="py-2 px-3 pe-11 block w-full border-l-0 sm:border-l-2 border-t-0 border-b-0 border-r-0 bg-win-light -mt-px -ms-px first:rounded-t-full last:rounded-b-full sm:first:rounded-s-full sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-full text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->fiance_last_name }}">
              </div>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-3">
              <label for="af-account-email" class="inline-block text-sm text-gray-800 mt-2.5 font-semibold">
                Email
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <input value="{{ $user->email }}" name="email" id="af-account-email" type="email" class="py-2 px-3 pe-11 block w-full border-0 text-sm rounded-full bg-win-light focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="{{ $user->email }}">
            </div>

            <div class="sm:col-span-3">
              <label for="wedding_date"
                class="inline-block text-sm text-gray-800 mt-2.5 font-semibold">
                Wedding Date
              </label>
            </div>
    
            <div class="sm:col-span-9">
              <input value="{{ $user->wedding_date }}" id="wedding_date" name="wedding_date"
              class="peer h-full w-full rounded-full border-0 bg-win-light px-3 py-2.5 font-sans text-sm font-normal outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:border-0"
              placeholder="Select a date" />
            </div>

            <div class="sm:col-span-3">
              <label for="wedding_location"
                class="inline-block text-sm text-gray-800 mt-2.5 font-semibold">
                Wedding Venue
              </label>
            </div>
    
            <div class="sm:col-span-9">
              <input value="{{ $user->wedding_location }}" id="wedding_location" name="wedding_location"
              class="peer h-full w-full rounded-full border-0 bg-win-light px-3 py-2.5 font-sans text-sm font-normal outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:border-0"
              placeholder="Where is your wedding?" />
            </div>
    
            <div class="sm:col-span-3">
              <label for="af-account-bio" class="inline-block text-sm text-gray-800 mt-2 font-semibold">
                Bio
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <textarea value="{{ $user->bio }}" name="bio" id="af-account-bio" class="py-2 px-3 block w-full border-0 bg-win-light rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none text-black" rows="6" placeholder="{{ $user->bio }}">{{ $user->bio }}</textarea>
            </div>

            <div class="sm:col-span-3">
              <label for="q1" class="inline-block text-sm text-gray-800 mt-2 font-semibold">
                Describe your dream wedding in three words. <span class="text-sm text-gray">(Optional)</span>
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <textarea value="{{ $user->questions[0] }}" name="q1" id="q1" class="py-2 px-3 block w-full border-0 bg-win-light rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none text-black" rows="2" placeholder="{{ $user->questions[0] }}">{{ $user->questions[0] }}</textarea>
            </div>

            <div class="sm:col-span-3">
              <label for="q2" class="inline-block text-sm text-gray-800 mt-2 font-semibold">
              What are you most looking forward to about your wedding? <span class="text-sm text-gray">(Optional)</span>
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <textarea value="{{ $user->questions[1] }}" name="q2" id="q2" class="py-2 px-3 block w-full border-0 bg-win-light rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none text-black" rows="5" placeholder="{{ $user->questions[1] }}">{{ $user->questions[1] }}</textarea>
            </div>

            <div class="sm:col-span-3">
              <label for="q3" class="inline-block text-sm text-gray-800 mt-2 font-semibold">
              Are there any specific tratitions that are important for you to include, or avoid? <span class="text-sm text-gray">(Optional)</span>
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <textarea value="{{ $user->questions[2] }}" name="q3" id="q3" class="py-2 px-3 block w-full border-0 bg-win-light rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none text-black" rows="5" placeholder="{{ $user->questions[2] }}">{{ $user->questions[2] }}</textarea>
            </div>

            <div class="sm:col-span-3">
              <label for="q4" class="inline-block text-sm text-gray-800 mt-2 font-semibold">
              Is there anything else you'd like your wedding vendors to know before working together? <span class="text-sm text-gray">(Optional)</span>
              </label>
            </div>
            <!-- End Col -->
    
            <div class="sm:col-span-9">
              <textarea value="{{ $user->questions[3] }}" name="q4" id="q4" class="py-2 px-3 block w-full border-0 bg-win-light rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none text-black" rows="5" placeholder="{{ $user->questions[3] }}">{{ $user->questions[3] }}</textarea>
            </div>
          </div>
          <!-- End Grid -->
          <div class="sm:flex justify-center items-center md:grid md:grid-cols-2 mt-4">
            @php($i = 0)
            @foreach($vendor_types as $vendorType)
            @if($i == 0 || $i == 6)
            <ul class="flex flex-col min-w-full">
            @endif
              <li
                class="sm:inline-flex list-none items-center gap-x-2 my-2 mx-2 py-3 px-4 text-sm font-medium bg-win-light rounded-full -mt-px">
                <div class="relative flex items-start w-full">
                  <div class="flex items-center h-5">
                    <input id="vt-{{ $vendorType->id }}" name="vt[]" value="{{ $vendorType->id }}" type="checkbox"
                      class="border-gray-200 text-pink-win accent-pink-win checked:border-pink-pin rounded disabled:opacity-50" @if($searching_for->contains($vendorType->id)) checked @endif>
                  </div>
                  <label for="vt-{{ $vendorType->id }}" class="ms-3.5 block w-full text-gray-600 ">
                    {{ $vendorType->type }} <span>
                      <img src="{{ $vendorType->icon }}" class="h-auto max-h-6 inline text-pink-win ml-1" alt="Icon">
                    </span>
                  </label>
                </div>
              </li>
              @if($i == 5 || $i == 11)
              </ul>
              @endif
              @php($i += 1)
              @endforeach
          </div>
    
          <div class="mt-5 flex justify-end gap-x-2">
            <button type="submit" class="py-2 px-8 inline-flex items-center gap-x-2 font-semibold rounded-lg border border-transparent bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">
              Save changes
            </button>
          </div>
        </form>
      </div>
      <!-- End Card -->
    </div>
    <!-- End Card Section -->
    @include('layouts.footer')
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
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
    if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
    });
    canvas.toBlob(function(blob) {
        if (!blob) return;
        const fileInput = document.getElementById('imageUpload');
        const croppedFile = new File([blob], originalFileName || 'profile.png', { type: 'image/png', lastModified: Date.now() });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        fileInput.files = dataTransfer.files;

        hideModal();
        cropper.destroy();
        cropper = null;

        uploadImage();
    }, 'image/png');
});

document.getElementById('cancelButton').addEventListener('click', function() {
    hideModal();
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

function hideModal() {
    const modal = document.getElementById('cropperModal');
    modal.style.display = 'none';
}

    </script>
    <script>
      document.getElementById('uploadImageButton')?.addEventListener('click', function () {
        document.getElementById('imageUpload')?.click();
      });

      function uploadImage() {
        const fileInput = document.getElementById('imageUpload');
        const file = fileInput?.files?.[0];
        const badAlert = document.getElementById('badImageAlert');
        const goodAlert = document.getElementById('goodImageAlert');
        const preview = document.getElementById('profileImagePreview');

        if (!file) {
          if (badAlert) badAlert.hidden = false;
          return;
        }

        const formData = new FormData();
        formData.append('image', file);

        fetch('{{ route('user.upload.image') }}', {
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
            if (preview && data.image_url) {
              preview.src = data.image_url + '?t=' + Date.now();
            }
            if (goodAlert) {
              goodAlert.hidden = false;
              const nameEl = document.getElementById('imageUploadName');
              if (nameEl) nameEl.textContent = data.filename || file.name;
            }
            if (badAlert) badAlert.hidden = true;
          })
          .catch(function () {
            if (badAlert) badAlert.hidden = false;
            if (goodAlert) goodAlert.hidden = true;
          });
      }
    </script>
    <script>
      $("#wedding_date").flatpickr({});
    </script>
  </body>
</html>
