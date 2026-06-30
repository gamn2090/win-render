<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Vendors</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-dark-grey-win text-slate-500 overflow-x-hidden">
    @include('layouts.vendor_navigation')
    <div class="absolute w-full bg-pink-win min-h-[18.75rem]"></div>
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
      <div class="w-full px-6 py-6 mx-auto">
        @foreach($vendors as $vendor)
        <div class="flex flex-col rounded-xl p-4 md:p-6 bg-white border border-gray-200">
            <div class="flex items-center gap-x-4">
              <img class="rounded-full size-20" src="{{asset('/storage/images/'.$vendor->image)}}" alt="Image Description">
              <div class="grow">
                <h3 class="font-medium text-gray-800">
                  {{ $vendor->first_name }} {{ $vendor->last_name }}
                </h3>
                <p class="text-xs uppercase text-gray-500">
                  {{ $vendor->getType()->type }}
                </p>
              </div>
            </div>
            <a href="/vendor/profile/{{ $vendor->uuid }}" class="underline">View profile</a>
            <!-- End Social Brands -->
          </div>
          <!-- End Col -->
        @endforeach
      </div>
      @include('layouts.footer')
      <!-- end cards -->
    </main>
  </body>
</html>
