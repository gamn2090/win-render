@php($pending_connections = Auth::guard('vendor')->user()->pendingConnectionsWhereAffiliate)
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
@vite('resources/js/vendor-utils.js')
<!-- Navbar -->
<header class="flex flex-wrap sm:justify-center sm:flex-nowrap w-full bg-white text-sm py-4">
    <nav class="w-full 2xl:max-w-[85vw] mx-auto px-4 flex flex-wrap basis-full items-center justify-center" aria-label="Global">
      <div class="sm:hidden flex items-center gap-x-2">
        <button type="button" class="sm:hidden hs-collapse-toggle p-2.5 inline-flex justify-center items-center gap-x-2 rounded-lg border border-win-light bg-win-blue text-win-light hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-collapse="#navbar-alignment" aria-controls="navbar-alignment" aria-label="Toggle navigation">
          <svg class="hs-collapse-open:hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
          <svg class="hs-collapse-open:block hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      <a class="sm:order-1 flex-none text-xl py-6 mx-6 xl:mx-16" href="/vendor/dashboard"><img src="/assets/img/WIN-Primary-Logo-BLUE.png" class="w-[10vh]"></a>
      @php($notifications = Auth::guard('vendor')->user()->getUnreadMessagesCount())
      <div id="navbar-alignment" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:basis-auto sm:block sm:order-2">
        <div class="flex flex-col gap-4 xl:gap-8 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5 text-win-charcoal">
          <a class="font-semibold body-copy @if($page == 'dashboard')text-win-blue border-b-2 border-win-blue @endif" href="/vendor/dashboard">Home</a>
          <a class="font-semibold body-copy @if($page == 'insights')text-win-blue border-b-2 border-win-blue @endif" id="insights-tab" href="/vendor/insights">Insights</a>
          <a class="font-semibold body-copy @if($page == 'storefront')text-win-blue border-b-2 border-win-blue @endif" href="/vendor/profile/{{ Auth::guard('vendor')->user()->uuid }}" id="storefront-tab">Storefront</a>
          <a class="font-semibold body-copy @if($page == 'inbox')text-win-blue border-b-2 border-win-blue @endif" href="/vendor/inbox" id="inbox-tab">Inbox @if(count($notifications["vendor_notifs"]) > 0)<span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">{{ count($notifications["vendor_notifs"]) }}</span>@endif</a>
          <a class="font-semibold body-copy sm:hidden @if($page == 'client_invite')text-win-blue border-b-2 border-win-blue @endif" href="/vendor/create/client">Invite Clients</a>
          <a class="font-semibold body-copy sm:hidden @if($page == 'vendor_invite')text-win-blue border-b-2 border-win-blue @endif" href="/vendor/create/vendors">Refer Vendors</a>
          <a class="font-semibold body-copy @if($page == 'find_couples')text-win-blue border-b-2 border-win-blue @endif" href="/vendor/couples" id="find-couples-tab">Find Couples</a>
          <a class="font-semibold body-copy @if($page == 'search_vendors')text-win-blue border-b-2 border-win-blue @endif" href="/vendor/search/vendors" id="search-vendors-tab">Search Vendors</a>
          <a class="font-semibold body-copy text-win-charcoal @if($page == 'search_vendors')text-win-purple border-b-2 border-win-purple @endif sm:hidden" href="/vendor/profile">Edit Profile</a>
          <form method="POST" action="/vendor/logout" class="sm:hidden">
            @csrf
            <button type="submit" class="flex items-center w-full rounded-lg font-semibold body-copy text-win-charcoal hover:cursor-pointer">
              Logout
            </button>
          </form>
          <div class="hs-dropdown [--strategy:fixed] lg:[--trigger:hover] inline-flex relative z-50 max-sm:hidden">
            <button id="hs-dropdown-hover-event" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 font-semibold body-copy @if($page == 'vendor_list' || $page == 'client_list')text-win-blue border-b-2 border-win-blue @endif" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
              Lists
              <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-hover-event">
              <div class="p-1 space-y-0.5">
                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm font-semibold hover:bg-win-lavender focus:outline-none focus:bg-win-lavender" href="/vendor/client/list">
                  Your Clients
                </a>
                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm font-semibold hover:bg-win-lavender focus:outline-none focus:bg-win-lavender" href="/vendor/list">
                  Your Vendors
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    <div class="ps-4 xl:ps-6 md:mr-8 my-auto sm:order-3 flex sm:flex-row sm:items-center sm:space-x-4 xl:space-x-8">
      <div class="hs-dropdown [--strategy:fixed] lg:[--trigger:hover] inline-flex relative z-50 max-sm:hidden">
        <button id="hs-dropdown-hover-refer" type="button" class="hs-dropdown-toggle py-3 inline-flex items-center gap-x-2 font-semibold body-copy text-win-blue hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none @if($page == 'client_invite' || $page == 'vendor_invite')text-win-blue border-b-2 border-win-blue @endif" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
          Refer <span><i class="fas fa-exchange-alt"></i></span>
          <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-hover-refer">
          <div class="p-1 space-y-0.5">
            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-win-lavender focus:outline-none focus:bg-win-lavender" href="/vendor/create/vendors">
              Refer a vendor
            </a>
            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-win-lavender focus:outline-none focus:bg-win-lavender" href="/vendor/create/client">
              Refer a client
            </a>
          </div>
        </div>
      </div>
      <div class="hs-dropdown [--strategy:fixed] [--adaptive:none] lg:[--trigger:hover] md:py-1 hidden sm:block">
        <button id="profile-hover" type="button" class="flex items-center w-full text-gray-500 hover:text-gray-400 font-medium">
          <div class="bg-grey-100 size-8 lg:size-14 rounded-full">
            <img class="inline-block flex-shrink-0 rounded-full ring-2 ring-white" src="{{asset('/storage/images/'.Auth::guard('vendor')->user()->image)}}" alt="Profile Picture">
          </div>
          <svg class="ms-2 size-4 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="hs-dropdown-menu transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 bg-white md:shadow-md rounded-lg p-2 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
          <a type="button" class="flex w-full items-center gap-x-2 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-grey-faint focus:ring-2 focus:ring-blue-500 hover:cursor-pointer" href="/vendor/profile">
            Edit Profile <i class="far fa-user-circle"></i>
          </a>
          <form method="POST" action="/vendor/logout">
            @csrf
            <button type="submit" class="flex items-center w-full gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-red hover:text-white hover:cursor-pointer focus:ring-2 focus:ring-blue-500">
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>
    </nav>
</header>
<!-- Offcanvas -->
<div id="navbar-secondary-content" class="hs-overlay hs-overlay-open:translate-x-0 hidden -translate-x-full fixed top-0 start-0 transition-all duration-300 transform h-full max-w-xs md:max-w-[30vw] w-full z-[60] bg-white border-e" tabindex="-1">
  <div class="flex justify-between items-center py-3 px-4 border-b">
    <h3 class="font-bold">
      Notifications
    </h3>
    <button type="button" class="inline-flex flex-shrink-0 justify-center items-center size-8 rounded-lg text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white text-sm" data-hs-overlay="#navbar-secondary-content">
      <span class="sr-only">Close</span>
      <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
  </div>
  <div class="p-4">
    @foreach($pending_connections as $connection)
    <div id="conn-noti-{{ $connection->id }}" class="bg-white rounded-xl shadow-lg mb-2" role="alert">
      <div class="flex p-4">
        <div class="flex-shrink-0">
          <svg class="flex-shrink-0 size-4 text-blue mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
          </svg>
        </div>
        <div class="ms-3">
          <p class="text-sm text-gray-700">
            <span class="font-bold">{{ $connection->first_name }} {{ $connection->last_name }}</span> would like you to join their community! 
          </p>
          <div class="mt-1">
            <button value="{{ $connection->id }}" response="false" type="button" class="connection-btn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-gray-light text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
              Decline
            </button>
            <button value="{{ $connection->id }}" response="true" type="button" class="connection-btn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue text-white disabled:opacity-50 disabled:pointer-events-none">
              Allow
            </button>
            <a type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white disabled:opacity-50 disabled:pointer-events-none" href="/vendor/profile/{{ $connection->id }}">
              View Profile
            </a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <script>
    $(".connection-btn").on("click", (el) => {
      console.log("triggered connection btn");
      console.log($(el.target).attr("response"));
      $(el.target).attr('disabled', true);
      let formData = {
        host_id: $(el.target).val(),
        response: $(el.target).attr("response")
      };
      console.log("connection: " + $(el.target).val());
      $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/vendor/connection/answer",
        data: formData,
        success: function (data) {
          if(data["show_msg"]){
            if(data['status'] == false){
              Swal.fire({
                title: 'Oops!',
                text: data['msg'],
                icon:  'error',
                confirmButtonText: 'Ok'
              });
              $(this).attr('disabled', false);
            } else{
                Swal.fire({
                  title: 'Nice!',
                  text: data['msg'],
                  icon:  'success',
                  confirmButtonText: 'Ok'
              });
              $("#conn-noti-" + $(el.target).val()).attr("hidden", true);
            }
          } else{
            $("#conn-noti-" + $(el.target).val()).attr("hidden", true);
          }
        }
      });
    });
  </script>
</div>
<!-- End Offcanvas -->

