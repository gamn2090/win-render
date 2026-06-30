<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Search Vendors</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-grey-50 text-slate-500 overflow-x-hidden min-h-screen">
    @if(Auth::guard('vendor')->check())
    @include('layouts.vendor_navigation')
    @else
    @include('layouts.navigation')
    @endif
    <main class="h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
      <div class="border-b border-gray-200 hidden md:block">
        <nav class="flex flex-wrap space-x-2 justify-center" aria-label="Tabs" role="tablist">
          @php($included_roles = [])
          @if(Auth::guard('vendor')->check())
          @foreach($requestedTypes as $type)
            @if(!in_array($type, $included_roles))
            <a href="/search/vendors?type={{ $type->id }}" class="
            @if($selected_type->id == $type->id) active @endif hs-tab-active:font-bold hs-tab-active:border-win-purple hs-tab-active:text-win-purple font-semibold py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-black hover:text-win-purple focus:outline-none focus:text-win-purple disabled:opacity-50 disabled:pointer-events-none" id="tab-label-{{ $type->id }}" data-hs-tab="#tab-{{ $type->id }}" aria-controls="tab-{{ $type->id }}" role="tab">
              <img src="{{ $type->icon }}" class="h-auto max-h-6 inline" alt="Icon">
              {{ $type->type }}
            </a>
            @php(array_push($included_roles, $type))
            @endif
          @endforeach
          @else
          @foreach($requestedTypes as $type)
            @if(!in_array($type, $included_roles))
            <a href="/search/vendors?type={{ $type->id }}" class="
            @if($selected_type->id == $type->id) active @endif hs-tab-active:font-bold hs-tab-active:border-win-purple hs-tab-active:text-win-purple font-semibold py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-black hover:text-win-purple focus:outline-none focus:text-win-purple disabled:opacity-50 disabled:pointer-events-none" id="tab-label-{{ $type->id }}" data-hs-tab="#tab-{{ $type->id }}" aria-controls="tab-{{ $type->id }}" role="tab">
              <img src="{{ $type->icon }}" class="h-auto max-h-6 inline" alt="Icon">
              {{ $type->type }}
            </a>
            @php(array_push($included_roles, $type))
            @endif
          @endforeach
          @endif
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
            <div class="bg-grey-50 rounded-lg p-4 lg:w-50% md:col-span-1">
              <div class="flex align-middle content-center">
                <h1 class="headline-small inline-flex">Top Rated {{ $selected_type->type }} </h1>
              </div>
              <p>Find top-ranked vendors in your area!</p>
              
              <div class="flex align-middle content-center">
                <label for="vendor-type-select" class="block subheading my-2">Category</label>
                <span class="hs-tooltip items-center text-base font-regular items-center inline-flex self-center">
                  <div class="hs-tooltip-toggle items-center h-full align-middle -mt-1">
                    <svg class="align-middle ml-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10" />
                      <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                      <path d="M12 17h.01" />
                    </svg>
                    <span class="mx-2 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black font-medium text-white rounded shadow-sm max-w-[75vw]" role="tooltip">
                      To search for vendors in additional categories, you’ll need to update your profile to include
                      those vendor types. Simply add the desired categories to your list of vendors, and you’ll unlock
                      access to search for professionals who fit your needs.
                      This ensures your search is tailored to your preferences, making it easier to find the perfect
                      vendors for your special day!
                    </span>
                  </div>
                </span>
              </div>
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
              <h3 class="mx-auto headline-small text-2xl mb-4 md:mb-6">Top Ranked {{ $selected_type->type }}:</h3>
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
                  <x-vendor-profile-card :vendor="$vendor" />
                  @endforeach
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
      @include('layouts.footer')
      <!-- end cards -->
    </main>
  </body>
  <script>
    @if(Auth::guard('vendor')->check())
    
    $('#vendor-type-select-btn').on('click', ()=>{
      window.location = '/vendor/search/vendors?type=' + $('#vendor-type-select').val();
    });
    @else
    $('#vendor-type-select-btn').on('click', ()=>{
      window.location = '/search/vendors?type=' + $('#vendor-type-select').val();
    });
    @endif
  </script>
    <!-- messages-->
    <script>
      $(".messageVendorButton").on("click", (el) => {
        console.log("triggered convo " + el.currentTarget.val);
        let id = el.currentTarget.id;
        console.log(el.currentTarget);
        $.ajax({
            type: "GET",
            headers: {
            },
            @if(Auth::guard('vendor')->check())
            url: "/vendor/message/" + el.currentTarget.id,
            @else
            url: "/client/message/" + el.currentTarget.id,
            @endif
            success: function (data) {
              @if(Auth::guard('vendor')->check())
              window.location = '/inbox/conversation/' + data;
              @else
              console.log('redirect client');
              window.location = '/client/conversation/' + data;
              @endif
            }
          });
      });
    </script>
</html>
