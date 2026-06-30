<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WIN: Vendor List</title>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.fonts')
  </head>

  <body class="m-0 overflow-x-hidden">
    @include('layouts.navigation')

    <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl py-4">
      <main class="h-full rounded-xl min-h-[75vh]">
        <div class="bg-white rounded-xl overflow-hidden mx-4 md:mx-6 lg:mx-8 mt-4 lg:mt-8">
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
            <div>
              <h2 class="subheading">Vendors</h2>
              <p class="text-sm text-gray-600">Your connected vendors</p>
            </div>
          </div>

          <table class="min-w-full divide-y divide-gray-200 mx-6">
            <thead class="bg-gray-50 px-4">
              <tr>
                <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">Type</th>
                <th scope="col" class="px-6 py-3 text-start">Name</th>
                <th scope="col" class="px-6 py-3 text-start">Status</th>
                <th scope="col" class="px-6 py-3 text-start">Location</th>
                <th scope="col" class="px-6 py-3 text-start">Action</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              @foreach($data['vendors'] as $vendor)
                @php
                  $typeModel = $vendor->getType();
                  $typeLabel = $typeModel?->type ?? 'Vendor';
                  $businessName = trim((string) ($vendor->business_name ?? ''));
                  if ($businessName === '') {
                      $businessName = trim((string) ($vendor->first_name ?? '') . ' ' . (string) ($vendor->last_name ?? ''));
                  }
                  $pairing = Auth::user()->pairingWith($vendor->id);
                @endphp
                <tr>
                  <td class="size-px whitespace-nowrap">
                    <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                      <span class="block text-sm font-semibold">{{ $typeLabel }}</span>
                    </div>
                  </td>
                  <td class="size-px whitespace-nowrap">
                    <div class="px-6 py-3">
                      <div class="flex items-center gap-x-3">
                        <img
                          class="inline-block size-[38px] rounded-full"
                          src="{{ \App\Support\ProfileImageStorage::url($vendor->image) }}"
                          alt=""
                        />
                        <div class="grow">
                          <span class="block text-sm font-semibold">{{ $businessName ?: '—' }}</span>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="size-px whitespace-nowrap">
                    <div class="px-6 py-3">
                      <span class="text-sm">{{ $pairing ? $pairing->statusText() : '—' }}</span>
                    </div>
                  </td>
                  <td class="size-px whitespace-nowrap">
                    <div class="px-6 py-3">
                      <span class="text-sm">{{ $vendor->location ?: '—' }}</span>
                    </div>
                  </td>
                  <td class="size-px whitespace-nowrap">
                    <div class="px-6 py-1.5">
                      <a
                        class="inline-flex items-center gap-x-1 text-sm bg-win-blue rounded-lg py-1 px-4 text-white"
                        href="{{ route('profile.vendor', ['id' => $vendor->uuid]) }}"
                      >
                        View
                      </a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200">
            <div>
              <p class="text-sm text-gray-600">
                <span class="font-semibold text-gray-800">{{ count($data['vendors']) }}</span> result(s)
              </p>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div class="mt-auto">
      @include('layouts.footer')
    </div>
  </body>
</html>
