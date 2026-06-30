<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WIN: Create Vendor</title>
    
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 antialiased overflow-x-hidden">
    @include('layouts.vendor_navigation')
    <div>
      <div class="bg-[#EDE9F5] flex justify-center lg:mx-8 rounded-t-3xl pt-4">
        <section class="mx-4 mt-4 md:mx-8 md:mt-8 py-8 bg-white rounded-t-3xl">
          <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
            <div class="max-w-4xl px-4 py-4 sm:px-6 lg:px-8 mx-auto min-h-[60vh]">
              <!-- Card -->
              <div class="bg-white rounded-xl p-4 sm:p-7">
                <div class="mb-8">
                  @isset($linkID)
                  <div class="bg-teal text-sm text-white rounded-lg p-2 mb-4" role="alert">
                    <span class="font-bold">Success!</span> You have invited a new vendor.
                  </div>
                  @endisset
                  @if ($errors->any())
                      <div class="bg-red text-sm text-white rounded-lg p-2 my-2" role="alert">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                  <h2 class="text-xl font-bold">
                    Invite a Vendor
                  </h2>
                  <p class="text-sm text-gray-600">
                    Generate a new vendor invitation
                  </p>
                </div>

                <form method="POST" action="{{ route('create.vendor.invite') }}">
                  @csrf
                  <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">

                    <div class="sm:col-span-3">
                      <label for="af-account-full-name" class="inline-block text-sm mt-2.5">
                        Name<p class="text-xs">(only first name required)</p>
                      </label>
                    </div>
                    <div class="sm:col-span-9">
                      <div class="sm:flex">
                        <input name="first_name" id="af-account-full-name" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="First">
                        <input name="last_name" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Last">
                      </div>
                    </div>
                    <!-- End Col -->

                    <div class="sm:col-span-3">
                      <label for="client-email" class="inline-block text-sm mt-2.5">
                        Email<span class="text-win-red">*</span>
                      </label>
                    </div>
                    <!-- End Col -->

                    <div class="sm:col-span-9">
                      <input name="email" id="client-email" type="email" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="email@example.com">
                    </div>
                    
                    <div class="sm:col-span-12">
                      <div class="flex justify-between items-center">
                        <label for="personal-note" class="block font-medium mb-2 text-sm">Personal Note <span class="text-xs">(optional)</span></label>
                        <span class="block mb-2 text-sm">Max 250 characters</span>
                      </div>
                      <textarea id="personal_note" name="personal_note" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-win-purple focus:ring-win-purple disabled:opacity-50 disabled:pointer-events-none" rows="4" placeholder="Write a custom message here..."></textarea>
                    </div>
                    <!-- End Col -->
                  </div>
                  <!-- End Grid -->

                  <div class="mt-5 flex justify-end gap-x-2">
                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">
                      Add Vendor
                    </button>
                  </div>
                </form>
              </div>
              <!-- End Card -->
            </div>
          </main>
        </section>
      </div>
    </div>
    @include('layouts.footer')
  </body>
  <script>
    $("#client-wedding-date").flatpickr({});
  </script>
</html>
