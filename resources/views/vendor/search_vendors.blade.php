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

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-white text-slate-500 overflow-x-hidden min-h-screen">
    @if(Auth::guard('vendor')->check())
    @include('layouts.vendor_navigation')
    @else
    @include('layouts.navigation')
    @endif
    <main class="h-full transition-all duration-200 ease-in-out rounded-xl min-h-[75vh]">
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
            <div class="bg-win-light rounded-lg p-4 lg:w-50% md:col-span-1">
              <h1 class="headline-small">Top Rated {{ $selected_type->type }}</h1>
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
              <button id="vendor-type-select-btn" type="button" class="mt-4 py-2 px-6 rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">
                Search
              </button>
            </div>
            <div class="lg:w-[50%]">
            </div>
          </div>
        </div>
      </div>
      <div class="w-full px-6 lg:px-12 py-2 mx-auto">
        <div class="">
          <section id="aff-vendors-section">
          
            <div class="mt-3 justify-center">
              <h3 class="mx-auto headline-small mb-4">Top Ranked {{ $selected_type->type }}:</h3>
              <section id="filters" class="mb-6">
                <form id="vendor-filter-form" method="GET" action="/search/vendors">
                  <input type="hidden" name="type" value="{{ $selected_type->id }}">
                  <div class="flex flex-wrap justify-start items-start space-x-2">
                    @foreach($allowedFilters as $filter)
                      @if($filter->search_type == 'checkbox')
                        <x-filter-checkbox :filter="$filter" />
                      @elseif($filter->search_type == 'select')
                        <x-filter-select :filter="$filter" :selected="null" />
                      @endif
                    @endforeach
                    @auth('web')
                      @if(Auth::user()->event != null)
                      <x-filter-select :filter="new \App\Models\TagType(['name' => 'Event', 'search_type' => 'select', 'allowed_values' => '[' . Auth::user()->event . ']'])" />
                      @endif
                    @endauth
                  </div>
                  @if($allowedFilters->count() > 0)
                  <a href="/search/vendors?type={{ request()->type }}" class="mt-4 py-1 px-2 text-sm font-semibold rounded-lg bg-white ring-win-red ring-1 text-win-red disabled:opacity-50 disabled:pointer-events-none">
                    Clear Filters <i class="fa-solid fa-ban ml-1"></i></a>
                  <button id="vendor-filter-btn" type="submit" class="mt-4 py-1 px-2 text-sm rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">
                    Apply Filters <i class="fa-solid fa-check ml-1"></i>
                  </button>
                  @endif
                </form>
              </section>
              <div id="resultsSection" class="w-full">
                @if(count($vendors) == 0)
                <div class="p-4 mt-4 mb-8 h-48 flex justify-center items-center border border-dashed border-black rounded-xl col-span-2">
                  <h3 class="text-black">
                    We couldn't find any vendors of the specified type!
                  </h3>
                </div>
                @endif
                <div class="grid md:grid-cols-4 gap-4 content-center">
                  @foreach($vendors as $vendor)
                    <x-vendor-profile-card :vendor="$vendor" />
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
  <script>
    $('#vendor-type-select-btn').on('click', ()=>{
      window.location = '/search/vendors?type=' + $('#vendor-type-select').val();
    });
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
