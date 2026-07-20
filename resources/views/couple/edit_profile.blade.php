<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Edit Profile</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

  @include('partials.google_places_autocomplete')
  <script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&loading=async&callback=initWinPlacesAutocomplete"></script>

  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">
@php
  $user = Auth::guard('web')->user();
@endphp

@include('layouts.couple_sidebar', ['page' => 'edit_profile'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple'])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">Edit Profile</h1>
      <p class="vd-page-header__sub">Manage your name, password and account settings.</p>
    </header>

    @if(session('status') === 'profile-updated')
      <div class="vd-edit-success-banner">Updated profile!</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="vd-edit-card">
      @csrf
      @method('patch')

      <div class="vd-edit-avatar-row">
        <img id="profileImagePreview" class="vd-edit-avatar" src="{{ \App\Support\ProfileImageStorage::url($user->image) }}" alt="Profile Picture" />
        <button id="uploadImageButton" type="button" class="vd-edit-upload-btn">⬆️ Upload Profile Photo</button>
        <input id="imageUpload" type="file" accept=".png,.jpg,.jpeg" hidden />
      </div>

      <div class="vd-edit-form">
        <div class="vd-edit-row">
          <div class="vd-edit-field">
            <label class="vd-edit-field__label">Full name <span class="vd-edit-field__required">*</span></label>
            <div class="vd-edit-row">
              <input type="text" name="first_name" value="{{ $user->first_name }}" placeholder="First Name" required />
              <input type="text" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name" required />
            </div>
          </div>
          <div class="vd-edit-field">
            <label class="vd-edit-field__label">Fiance name <span class="vd-edit-field__required">*</span></label>
            <div class="vd-edit-row">
              <input type="text" name="fiance_first_name" value="{{ $user->fiance_first_name }}" placeholder="First Name" />
              <input type="text" name="fiance_last_name" value="{{ $user->fiance_last_name }}" placeholder="Last Name" />
            </div>
          </div>
        </div>

        <div class="vd-edit-field">
          <label class="vd-edit-field__label">Email <span class="vd-edit-field__required">*</span></label>
          <input type="email" name="email" value="{{ $user->email }}" placeholder="email@example.com" required />
        </div>

        <div class="vd-edit-row">
          <div class="vd-edit-field">
            <label class="vd-edit-field__label">Wedding location — City</label>
            <input type="text" id="venue_city" name="venue_city" value="{{ $venueCity }}" placeholder="City" autocomplete="off" data-places-autocomplete data-places-types="(cities)" data-places-self="city" data-places-fill-state="venue_state" />
          </div>
          <div class="vd-edit-field">
            <label class="vd-edit-field__label">Wedding location — State</label>
            <input type="text" id="venue_state" name="venue_state" value="{{ $venueState }}" placeholder="State" readonly />
          </div>
        </div>

        <div class="vd-edit-row">
          <div class="vd-edit-field">
            <label class="vd-edit-field__label">Name of wedding venue</label>
            <input type="text" name="venue_name" value="{{ $venueName }}" placeholder="E.G. Evergreen Hall" />
          </div>
          <div class="vd-edit-field">
            <label class="vd-edit-field__label">Wedding date</label>
            <input type="text" id="wedding_date" name="wedding_date" value="{{ $user->wedding_date }}" placeholder="MM/DD/YYYY" />
          </div>
        </div>

        <div class="vd-edit-field">
          <label class="vd-edit-field__label">Bio</label>
          <textarea name="bio" class="vd-edit-field__bio" placeholder="Share a bit about you both">{{ $bioText }}</textarea>
        </div>

        <div class="vd-edit-field">
          <label class="vd-edit-field__label">Describe your dream wedding in three words. <span class="vd-edit-field__optional">(Optional)</span></label>
          <textarea name="q1" placeholder="e.g. Authentic, emotional, meaningful...">{{ $user->questions[0] ?? '' }}</textarea>
        </div>

        <div class="vd-edit-field">
          <label class="vd-edit-field__label">What are you most looking forward to about your wedding? <span class="vd-edit-field__optional">(Optional)</span></label>
          <textarea name="q2" placeholder="e.g. walking down the aisle, our first dance, and the food">{{ $user->questions[1] ?? '' }}</textarea>
        </div>

        <div class="vd-edit-field">
          <label class="vd-edit-field__label">Are there any specific traditions that are important for you to include, or avoid? <span class="vd-edit-field__optional">(Optional)</span></label>
          <textarea name="q3" placeholder="e.g. a church ceremony, cultural traditions, or skipping the bouquet toss">{{ $user->questions[2] ?? '' }}</textarea>
        </div>

        <div class="vd-edit-field">
          <label class="vd-edit-field__label">Is there anything else you&rsquo;d like your wedding vendors to know before working together? <span class="vd-edit-field__optional">(Optional)</span></label>
          <textarea name="q4" placeholder="e.g. we value flexibility, attention to detail, and clear communication">{{ $user->questions[3] ?? '' }}</textarea>
        </div>

        <h3 class="vd-edit-vendor-title">Select all the vendors you are interest to connect with</h3>
        <div class="vd-edit-select-all">
          <label class="vd-edit-select-all__checkbox">
            <input type="checkbox" id="vd-select-all-types" />
            <span>Select All</span>
          </label>
          <span class="vd-edit-hint--blue">You can update your vendor preferences anytime.</span>
        </div>
        <div class="vd-edit-pills">
          @foreach($vendor_types as $vendorType)
            <label class="vd-edit-pill">
              <input type="checkbox" name="vt[]" value="{{ $vendorType->id }}" class="vd-edit-vendor-type-checkbox" @checked($searching_for->contains($vendorType->id))>
              <img src="{{ asset($vendorType->icon) }}" alt="" />
              <span>{{ $vendorType->type }}</span>
            </label>
          @endforeach
        </div>

        <div class="vd-edit-toggle-row">
          <span>Would you like our vendors to contact you directly?</span>
          <label class="vd-edit-toggle">
            <span>No</span>
            <input type="checkbox" name="allow_vendor_contact" value="1" class="vd-edit-toggle__input" @checked($user->allow_vendor_contact)>
            <span class="vd-edit-toggle__track"><span class="vd-edit-toggle__thumb"></span></span>
            <span>Yes</span>
          </label>
        </div>

        <button type="submit" class="vd-edit-save-btn">Save changes</button>
      </div>
    </form>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>

<div id="cropperModal" style="display:none;position:fixed;z-index:999;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgba(0,0,0,0.5);">
  <div style="margin:15% auto;padding:20px;background:white;width:80%;max-width:600px;">
    <div><img id="image" style="max-width:100%;min-height:100%" /></div>
    <button type="button" id="cropButton" class="vd-edit-upload-btn" style="margin:12px 6px 0 0;">Crop</button>
    <button type="button" id="cancelButton" class="vd-modal__btn vd-modal__btn--cancel">Cancel</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.getElementById('vd-select-all-types').addEventListener('change', function (e) {
    document.querySelectorAll('.vd-edit-vendor-type-checkbox').forEach(function (cb) {
      cb.checked = e.target.checked;
    });
  });

  flatpickr('#wedding_date', {});

  document.getElementById('uploadImageButton').addEventListener('click', function () {
    document.getElementById('imageUpload').click();
  });

  let cropper;
  let originalFileName;

  document.getElementById('imageUpload').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
      const imgElement = document.getElementById('image');
      imgElement.src = e.target.result;
      originalFileName = file.name;
      document.getElementById('cropperModal').style.display = 'block';
      cropper = new Cropper(imgElement, { aspectRatio: 1, viewMode: 1 });
    };
    reader.readAsDataURL(file);
  });

  document.getElementById('cropButton').addEventListener('click', function () {
    if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    canvas.toBlob(function (blob) {
      if (!blob) return;
      const fileInput = document.getElementById('imageUpload');
      const croppedFile = new File([blob], originalFileName || 'profile.png', { type: 'image/png', lastModified: Date.now() });
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(croppedFile);
      fileInput.files = dataTransfer.files;

      hideCropperModal();
      cropper.destroy();
      cropper = null;

      uploadImage();
    }, 'image/png');
  });

  document.getElementById('cancelButton').addEventListener('click', function () {
    hideCropperModal();
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  });

  function hideCropperModal() {
    document.getElementById('cropperModal').style.display = 'none';
  }

  function uploadImage() {
    const fileInput = document.getElementById('imageUpload');
    const file = fileInput?.files?.[0];
    const preview = document.getElementById('profileImagePreview');
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);

    fetch('{{ route('user.upload.image') }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: formData,
      credentials: 'same-origin',
    })
      .then(function (response) { return response.json(); })
      .then(function (data) {
        if (preview && data.image_url) {
          preview.src = data.image_url + '?t=' + Date.now();
        }
      });
  }
</script>
</body>
</html>
