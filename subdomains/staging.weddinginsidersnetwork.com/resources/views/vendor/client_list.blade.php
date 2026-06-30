<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WIN: Client List</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 overflow-x-hidden">
    @include('layouts.vendor_navigation')

    <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl py-4">
      <main class="h-full rounded-xl min-h-[75vh]">
        <div class="bg-white rounded-xl overflow-hidden mx-4 md:mx-6 lg:mx-8 mt-4 lg:mt-8 text-center py-6">
          <h2 class="subheading mb-4">&#127881; Take a moment to celebrate your WINS!</h2>
          <p>Below you can view your past clients, weddings, and export your client list.</p>
        </div>
        <div class="bg-white rounded-xl overflow-hidden mx-4 md:mx-6 lg:mx-8 mt-4 lg:mt-8">
          <!-- Header -->
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
            <div>
              <h2 class="subheading">
                Clients
              </h2>
            </div>

            <div>
              <div class="inline-flex gap-x-2">
                <a class="py-1 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-win-blue text-white" href="/vendor/create/client">
                  <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Add Client
                </a>
              </div>
            </div>
          </div>
          <!-- End Header -->

          <!-- Table -->
          <table class="min-w-full divide-y divide-gray-200 mx-4" >
            <thead class="bg-gray-50 px-4">
              <tr>
                <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="">
                      Name
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="">
                      Wedding Location
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="">
                      Wedding Date
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="">
                      Status
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">Profile</th>
                <th scope="col" class="px-6 py-3 text-start">Remove</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              @foreach($data["clients"] as $client)
              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$client->image) }}" alt="Image Description">
                      <div class="grow">
                        <span class="block text-sm font-semibold text-gray-800">{{ $client->first_name }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $client->fiance_first_name }}</span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px w-64 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="block text-sm font-semibold text-gray-800">{{ $client->wedding_location }}</span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500">{{ $client->wedding_date }}</span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                      <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                      </svg>
                      Active
                    </span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a class="inline-flex items-center gap-x-1 text-sm bg-win-blue rounded-lg py-1 px-4 text-white" href="/client/profile/{{ $client->uuid }}">
                      View
                    </a>
                  </div>
                </td>
                <td class="whitespace-nowrap">
                  <div class="hover:cursor-pointer rm-pairing ml-1 mt-1.5" data-vendor-id="{{ $client->uuid }}">
                      <i class="fa-solid fa-xmark text-3xl text-win-red"></i>
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
                <span class="font-semibold text-gray-800">{{ count($data["clients"]) }}</span> result(s)
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
