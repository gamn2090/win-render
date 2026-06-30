<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>WIN: User Registration</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

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
  <div data-hs-stepper class="join-us-vendor-register">
    @include('auth.partials.register_couple_step1')
    @include('auth.partials.register_couple_step2')
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
    });
  </script>

</html>