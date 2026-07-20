<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>WIN: Vendor Registration</title>

  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
  <script src="https://unpkg.com/cropperjs"></script>
  <script src="/assets/js/confetti.min.js"></script>

  @include('partials.google_places_autocomplete')
  <script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&loading=async&callback=initWinPlacesAutocomplete"></script>

  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  @include('components.fonts')
</head>
@include('layouts.guest_navigation')

<body class="overflow-x-hidden join-us-vendor-body">
  @include('auth.partials.join_us_register_styles')
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
  @include('auth.partials.register_vendor_stepper')

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/gh/BossBele/cropzee@latest/dist/cropzee.min.js" defer></script>

  @vite('resources/js/vendor-register-form.js')
</body>
<script>
  $("#client-wedding-date").flatpickr({});
</script>

        
<script>
  @isset($ref_user)
    $("#hs-first-name").val("{!! $ref_user->first_name !!}");
    $("#hs-last-name").val("{!! $ref_user->last_name !!}");
    $("#new-email").val("{!! $ref_user->email !!}");
  @endisset
  @if (!empty($google_prefill))
    $("#hs-first-name").val(@json($google_prefill['first_name'] ?? ''));
    $("#hs-last-name").val(@json($google_prefill['last_name'] ?? ''));
    $("#new-email").val(@json($google_prefill['email'] ?? ''));
  @endif
</script>

</html>