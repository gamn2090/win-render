<script src="https://code.jquery.com/jquery-3.7.1.min.js"
integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite('resources/js/client-utils.js')
<!-- Navbar -->
<header class="flex flex-wrap sm:justify-center sm:flex-nowrap w-full bg-white text-sm py-4">
    <nav class="max-w-[85rem] w-full mx-auto px-4 flex flex-wrap basis-full items-center justify-between" aria-label="Global">
    <a class="sm:order-1 flex-none text-xl py-6" href="/vendor/dashboard"><img src="/assets/img/WIN-Primary-Logo-PURPLE.png" class="w-[10vh]"></a>
      
      <div class="sm:order-3 flex items-center gap-x-2">
        <button type="button" class="sm:hidden hs-collapse-toggle p-2.5 inline-flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-collapse="#navbar-alignment" aria-controls="navbar-alignment" aria-label="Toggle navigation">
          <svg class="hs-collapse-open:hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
          <svg class="hs-collapse-open:block hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      <div id="navbar-alignment" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2">
        <div class="flex flex-col gap-5 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5">
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'dashboard')text-win-purple border-b-2 border-win-purple @endif" href="/dashboard" aria-current="page" id="dashboard-tab">Dashboard</a>
          @php
            $mainVendor = false;
            $main = Auth::guard('web')->user()->getMainVendor();
            if($main != null){
              $mainVendor = true;
            }
            $notifications = Auth::guard('web')->user()->getUnreadMessagesCount();
          @endphp
          @if($mainVendor)
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'storefront')text-win-purple border-b-2 border-win-purple @endif" href="/vendor/profile/{{ $main->uuid }}">Your Primary Vendor</a>
          @endif
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'inbox')text-win-purple border-b-2 border-win-purple @endif" href="/client/inbox" id="inbox-tab">Inbox @if(count($notifications["vendor_notifs"]) > 0)<span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">{{ count($notifications["vendor_notifs"]) }}</span>@endif</a>
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'client_storefront')text-win-purple border-b-2 border-win-purple @endif" href="/client/profile/{{ Auth::guard('web')->user()->uuid }}" id="profile-tab">Profile</a>
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'planning_tools')text-win-purple border-b-2 border-win-purple @endif" href="/planning-tools" id="planning-tools-tab">Planning Tools</a>
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'search_vendors')text-win-purple border-b-2 border-win-purple @endif" href="/search/vendors" id="search-vendors-tab">Search Vendors</a>
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'search_vendors')text-win-purple border-b-2 border-win-purple @endif sm:hidden" href="/profile/edit">Edit Profile</a>
          <form method="POST" action="/logout" class="sm:hidden">
            @csrf
            <button type="submit" class="flex items-center w-full rounded-lg font-semibold body-copy text-win-charcoal hover:cursor-pointer">
              Logout
            </button>
          </form>
        </div>
      </div>
    </nav>
    <div class="ps-3 sm:ps-6 sm:ms-0 sm:border-s sm:border-white md:mr-8 my-auto hidden sm:block">
      <div class="hs-dropdown [--strategy:static] md:[--strategy:fixed] [--adaptive:none] md:[--trigger:hover] md:py-1">
        <button id="profile-hover" type="button" class="flex items-center w-full text-gray-500 hover:text-gray-400 font-medium">
          <div class="bg-grey-100 size-8 lg:size-14 rounded-full">
            <img class="inline-block h-full w-full rounded-full object-cover" src="{{ \App\Support\ProfileImageStorage::url(Auth::guard('web')->user()->image) }}" alt="Profile Picture">
          </div>
          <svg class="ms-2 size-4 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
  
        <div class="hs-dropdown-menu transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 bg-white md:shadow-md rounded-lg p-2 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
          <a type="button" class="flex w-full items-center gap-x-2 py-2 px-3 rounded-lg text-sm text-gray-dark hover:bg-grey-faint focus:ring-2 focus:ring-blue-500 hover:cursor-pointer" href="/profile/edit">
            Edit Profile <i class="far fa-user-circle"></i>
          </a>
          <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="flex items-center w-full gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-dark hover:bg-red hover:text-white hover:cursor-pointer focus:ring-2 focus:ring-blue-500">
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>
</header>