@php
  $role = $role ?? 'couple';
@endphp

@if($role === 'vendor')
  @php($pending_connections = Auth::guard('vendor')->user()->pendingConnectionsWhereAffiliate)
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endif

@vite(['resources/css/vendor-sidebar.css', 'resources/css/vendor-layout.css'])
@if($role === 'vendor')
  @vite(['resources/js/vendor-utils.js', 'resources/js/vendor-sidebar-notifications.js', 'resources/js/vendor-sidebar-mobile.js'])
@else
  @vite(['resources/js/vendor-sidebar-mobile.js'])
@endif

<button
  type="button"
  class="dashboard-sidebar-toggle"
  aria-controls="dashboard-sidebar"
  aria-expanded="false"
  aria-label="Open menu"
>
  <span class="dashboard-sidebar-toggle__bar" aria-hidden="true"></span>
  <span class="dashboard-sidebar-toggle__bar" aria-hidden="true"></span>
  <span class="dashboard-sidebar-toggle__bar" aria-hidden="true"></span>
</button>
<div class="dashboard-sidebar-backdrop" aria-hidden="true"></div>

@include('layouts.dashboard_sidebar', ['role' => $role, 'page' => $page ?? null])

@if($role === 'vendor')
<!-- Offcanvas: pending connection requests -->
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
      $(el.target).attr('disabled', true);
      let formData = {
        host_id: $(el.target).val(),
        response: $(el.target).attr("response")
      };
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
              $(el.target).attr('disabled', false);
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
@endif
