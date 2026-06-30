<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WIN: My Appointments</title>
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
                Appointments
              </h2>
              <p class="text-sm text-gray-600">
                Your appointments
              </p>
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
                      Appointment Type
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="">
                      Date
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">Action</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              @foreach($data["appointments"] as $appointment)
              @php
                $meetingWith = $appointment->otherParticipant(Auth::user())->first();
              @endphp
              <tr>
                <td class="size-px whitespace-nowrap">
                  <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                    <div class="flex items-center gap-x-3">
                      <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'. $meetingWith->image) }}" alt="Image Description">
                      <div class="grow">
                        @if($meetingWith->userType() == "client")
                        <span class="block text-sm font-semibold text-gray-800">{{ $meetingWith->first_name }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $meetingWith->fiance_first_name }}</span>
                        @elseif($meetingWith->userType() == "vendor")
                        <span class="block text-sm font-semibold text-gray-800">{{ $meetingWith->business_name }}</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </td>
                <td class="size-px w-64 whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="block text-sm font-semibold text-gray-800">{{ ucfirst($appointment->type) }}</span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-3">
                    <span class="text-sm text-gray-500">{{ $appointment->readableTime() }}</span>
                  </div>
                </td>
                <td class="size-px whitespace-nowrap">
                  <div class="px-6 py-1.5">
                    <a class="inline-flex items-center gap-x-1 text-sm bg-win-blue rounded-lg py-1 px-4 text-white" href="{{ $meetingWith->profileURL() }}">
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
                <span class="font-semibold text-gray-800">{{ count($data["appointments"]) }}</span> result(s)
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
</html>
