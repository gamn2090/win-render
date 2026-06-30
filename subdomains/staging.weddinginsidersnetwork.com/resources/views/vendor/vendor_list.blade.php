<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WIN: Vendor List</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 overflow-x-hidden">
    @if(Auth::guard('vendor')->check())
    @include('layouts.vendor_navigation')
    @else
    @include('layouts.navigation')
    @endif

    <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl py-4">
      <main class="h-full rounded-xl min-h-[75vh]">
        <div class="bg-white rounded-xl overflow-hidden mx-4 md:mx-6 lg:mx-8 mt-4 lg:mt-8">
          <!-- Header -->
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
            <div>
              <h2 class="subheading">
                Vendors
              </h2>
              <p class="text-sm text-gray-600">
                Your connected vendors
              </p>
            </div>
            @if(Auth::guard('vendor')->check())
            <div>
              <div class="inline-flex gap-x-2">
                <a class="py-1 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-win-blue text-white" href="/vendor/create/vendors">
                  <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Add Vendor
                </a>
              </div>
            </div>
            @endif
          </div>
          <!-- End Header -->

          <!-- Table -->
          <table class="min-w-full divide-y divide-gray-200 mx-6" >
            <thead class="bg-gray-50 px-4">
              <tr>
                <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <p class="">
                      Type
                    </p>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <p class="">
                      Name
                    </p>
                  </div>
                </th>
                @if(Auth::guard('web')->check())
                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <p class="">
                      Status
                    </p>
                  </div>
                </th>
                @endif

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="">
                      Location
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">Action</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              @foreach($data["vendors"] as $vendor)
              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <span class="block text-sm font-semibold">{{ $vendor->getType()->type }}</span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$vendor->image) }}" alt="Image Description">
                      <div class="grow">
                        <span class="block text-sm font-semibold">{{ $vendor->business_name }}</span>
                      </div>
                    </div>
                  </div>
                </td>
                
                @if(Auth::guard('web')->check())
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm">{{ Auth::user()->pairingWith($vendor->id)->statusText() }}</span>
                  </div>
                </td>
                @endif
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm">{{ $vendor->location }}</span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a class="inline-flex items-center gap-x-1 text-sm bg-win-blue rounded-lg py-1 px-4 text-white" href="/vendor/profile/{{ $vendor->uuid }}">
                      View
                    </a>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <!-- End Table -->
          <!-- Footer -->
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200">
            <div>
              <p class="text-sm text-gray-600">
                <span class="font-semibold text-gray-800">{{ count($data["vendors"]) }}</span> result(s)
              </p>
            </div>
          </div>
          <!-- End Footer -->
        </div>
      </main>
    </div>
    <div class="mt-auto">
    @include('layouts.footer')
    </div>
  </body>
  <script>
    $("#client-wedding-date").flatpickr({});
  </script>
</html>
