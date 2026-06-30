<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Search Vendors</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-white text-slate-500 overflow-x-hidden min-h-screen">
    @include('layouts.guest_navigation')
    <main class="h-full transition-all duration-200 ease-in-out rounded-xl min-h-[75vh]">
      <div class="border-b border-gray-200 hidden md:block">
        <nav class="flex flex-wrap space-x-2 justify-center" aria-label="Tabs" role="tablist">
          @php($included_roles = [])
          @foreach($requestedTypes as $type)
            @if(!in_array($type, $included_roles))
            <a href="/search?type={{ $type->id }}" class="
            @if($selected_type->id == $type->id) active @endif hs-tab-active:font-bold hs-tab-active:border-win-purple hs-tab-active:text-win-purple font-semibold py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-black hover:text-win-purple focus:outline-none focus:text-win-purple disabled:opacity-50 disabled:pointer-events-none" id="tab-label-{{ $type->id }}" data-hs-tab="#tab-{{ $type->id }}" aria-controls="tab-{{ $type->id }}" role="tab">
              <img src="{{ $type->icon }}" class="h-auto max-h-6 inline" alt="Icon">
              {{ $type->type }}
            </a>
            @php(array_push($included_roles, $type))
            @endif
          @endforeach
          @if($included_roles == [])
          <div class="p-4 my-4 h-48 flex justify-center items-center border border-dashed border-white rounded-xl">
            <h3 class="text-white">
              This vendor doesn't have any preferred vendors added to their storefront yet!
            </h3>
          </div>
          @endif
        </nav>
      </div>
      <div class="flex justify-center my-4 lg:my-6">
        <div class="rounded-xl min-h-32 p-6 md:min-h-64 lg:min-w-[70%]" style="background-image: url('/images/search_vendors.jpg'); background-position: center; background-repeat: no-repeat; background-size: cover;">
          <div class="grid md:grid-cols-3">
            <div class="bg-win-light rounded-lg p-4 lg:w-50% md:col-span-1">
              <h1 class="headline-small">Top-Rated {{ $selected_type->type }}(s)</h1>
              <p>Find top-ranked vendors in your area!</p>
              <label for="vendor-type-select" class="block subheading my-2">Category</label>
              <select id="vendor-type-select" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                <option value="{{ $selected_type->id }}" selected="">{{ $selected_type->type }}</option>
                @foreach($requestedTypes as $type)
                @if($type->id != $selected_type->id)
                <option value="{{ $type->id }}">{{ $type->type }}</option>
                @endif
                @endforeach
              </select>
              <button id="vendor-type-select-btn" type="button" class="mt-4 py-2 px-6 rounded-lg bg-win-purple text-white shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                Search
              </button>
            </div>
            <div class="lg:w-[50%]">
            </div>
          </div>
        </div>
      </div>
      <div class="w-full px-6 py-2 mx-auto">
        <div class="">
          <section id="aff-vendors-section">
          
            <div class="mt-3 justify-center">
              <h3 class="mx-auto headline-small mb-4 md:mb-6">Top Ranked {{ $selected_type->type }}(s):</h3>
              <div id="resultsSection" class="w-full">
                @if(count($vendors) == 0)
                <div class="p-4 mt-4 mb-8 h-48 flex justify-center items-center border border-dashed border-black rounded-xl col-span-2">
                  <h3 class="text-black">
                    We couldn't find any vendors of the specified type!
                  </h3>
                </div>
                @endif
                <div class="grid md:grid-cols-4 gap-4">
                  @foreach($vendors as $vendor)
                  <x-guest-vendor-profile-card :vendor="$vendor" />
                  @endforeach
                </div>
                
                <div class="flex justify-center items-center my-4 space-x-4">
                  @if(!$vendors->onFirstPage())
                  <a href="{{ $vendors->previousPageUrl() }}&type={{ $selected_type->id }}" class="py-1 px-4 bg-win-blue rounded-lg text-white button-text">Previous</a>
                  @endif
                  @if($vendors->hasMorePages())
                  <a href="{{ $vendors->nextPageUrl() }}&type={{ $selected_type->id }}" class="py-1 px-4 bg-win-blue rounded-lg text-white button-text">Next</a>
                  @endif
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
      <!-- end cards -->
    </main>
    @include('layouts.footer')
  </body>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
      $(".messageVendorBtn").on("click", (el) => {
        console.log("triggered convo " + el.currentTarget.val);
        let id = el.currentTarget.id;
        console.log(el.currentTarget);
        Swal.fire({
            title: 'Hold on!',
            text: "You need an account to contact vendors. Let's set one up!",
            icon:  'info',
            confirmButtonText: 'Continue',
            confirmButtonColor: '#6432C8'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location = '/user/register';
          }
        });
      });
      $('#vendor-type-select-btn').on('click', ()=>{
        window.location = '/search?type=' + $('#vendor-type-select').val();
      });
    </script>
</html>
