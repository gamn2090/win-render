<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WIN: Archived Clients</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-win-charcoal text-slate-500 overflow-x-hidden">
    @include('layouts.vendor_navigation')
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <main class="relative h-full transition-all duration-200 ease-in-out rounded-xl min-h-[75vh]">
      <div class="flex flex-wrap mt-6 -mx-3">
        <div class="w-full max-w-full px-3 mt-0">
        <div class="">
          <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-hidden">
              <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mx-4 md:mx-6 lg:mx-8">
                  <!-- Header -->
                  <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
                    <div>
                      <h2 class="text-xl font-semibold text-gray-800">
                        Clients
                      </h2>
                      <p class="text-sm text-gray-600">
                        Your archived clients
                      </p>
                    </div>

                    <div>
                      <div class="inline-flex gap-x-2">
                        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-full bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none" href="/vendor/create/client">
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
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                              Name
                            </span>
                          </div>
                        </th>

                        <th scope="col" class="px-6 py-3 text-start">
                          <div class="flex items-center gap-x-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                              Wedding Location
                            </span>
                          </div>
                        </th>

                        <th scope="col" class="px-6 py-3 text-start">
                          <div class="flex items-center gap-x-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                              Status
                            </span>
                          </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-start">
                          <div class="flex items-center gap-x-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                              Wedding Date
                            </span>
                          </div>
                        </th>

                        <th scope="col" class="px-6 py-3 text-end"></th>
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
                                <span class="block text-sm font-semibold text-gray-800">{{ $client->first_name }} {{ $client->last_name }}</span>
                                <span class="block text-sm text-gray-500">{{ $client->email }}</span>
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
                            <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                              <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                              </svg>
                              Active
                            </span>
                          </div>
                        </td>
                        <td class="size-px whitespace-nowrap">
                          <div class="px-6 py-3">
                            <span class="text-sm text-gray-500">{{ $client->wedding_date }}</span>
                          </div>
                        </td>
                        <td class="size-px whitespace-nowrap">
                          <div class="px-6 py-1.5">
                            <a class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium" href="#">
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
                        <span class="font-semibold text-gray-800">{{ count($data["clients"]) }}</span> result(s)
                      </p>
                    </div>
                  </div>
                  <!-- End Footer -->
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
      <!---
      <x-table
      :columns='[
          [
            "name" => "Name",
            "field" => "first_name",
            "columnClasses" => "",    
            "rowClasses" => ""
          ],
          [
            "name" => "Wedding Date",
            "field" => "wedding_date",
            "columnClasses" => "",      
            "rowClasses" => ""
          ]
        ]'

        :rows='$data["clients"]'
      >
        <x-slot name="tableActions">
          <div class="flex flex-wrap space-x-4">
            <a :href="`users/${row.id}/show`" class="text-blue-500 underline">Edit</a>
            <a :href="`users/${row.id}/delete`" class="text-red-500 underline">Delete</a>
          </div>
        </x-slot>
      </x-table>-->
    </main>
    {{-- Site footer disabled per client request — uncomment to restore --}}
    {{-- <div class="mt-auto">
    @include('layouts.footer')
    </div> --}}
  </body>
  <script>
    $("#client-wedding-date").flatpickr({});
  </script>
</html>
