<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Vendor Inquiries</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/css/vendor-dashboard.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-dark-grey-win text-slate-500 overflow-x-hidden">
    @include('layouts.vendor_navigation')
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
      @include('layouts.dashboard_topbar', ['role' => 'vendor'])
      <!-- Table Section -->
      <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto min-h-[75vh]">
        <!-- Card -->
        <div class="flex flex-col">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <!-- Table -->
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start">
                        <div class="flex items-center gap-x-2">
                          <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                            Role
                          </span>
                        </div>
                      </th>
      
                      <th scope="col" class="px-6 py-3 text-start">
                        <div class="flex items-center gap-x-2">
                          <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                            Name
                          </span>
                        </div>
                      </th>
      
                      <th scope="col" class="px-6 py-3 text-start">
                        <div class="flex items-center gap-x-2">
                          <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                            Latest Message
                          </span>
                        </div>
                      </th>
      
                      <th scope="col" class="px-6 py-3 text-start">
                        <div class="flex items-center gap-x-2">
                          <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                            Action
                          </span>
                        </div>
                      </th>
                    </tr>
                  </thead>
      
                  <tbody class="divide-y divide-gray-200">
                    @foreach($data["conversations"] as $convo)
                      @foreach($convo->conversation->participants as $participant)
                      @if($participant->messageable_type == 'App\Models\User')
                    <tr class="bg-white hover:bg-grey-50">
                      <td class="size-px whitespace-nowrap align-top">
                        <a class="block p-6">
                          <div class="flex items-center gap-x-4">
                            <div>
                              <span class="block text-sm font-semibold text-gray-800">Client</span>
                            </div>
                          </div>
                        </a>
                      </td>
                      <td class="size-px whitespace-nowrap align-top">
                        <a class="block p-6">
                          <div class="flex items-center gap-x-3">
                            <img class="inline-block size-[38px] rounded-full" src="{{asset('/storage/images/'. $participant->messageable->image)}}" alt="Image Description">
                            <div class="grow">
                              <span class="block text-sm text-gray-500">{{ $participant->messageable->first_name }}</span>
                            </div>
                          </div>
                        </a>
                      </td>
                      <td class="h-px w-72 min-w-72 align-top">
                        <a class="block p-6">
                          @php($unread = Chat::conversation($convo->conversation)->setParticipant(Auth::guard('vendor')->user())->unreadCount())
                          @if($unread > 0)
                          <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">{{ $unread }}</span>
                          @elseif($participant->messageable_type == 'App\Models\Vendor' && Auth::guard('vendor')->user()->hasRequestFrom($participant->messageable->id))
                          <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">1</span>
                          @endif
                          @if($convo->conversation->last_message == null)
                          <span class="text-sm text-gray-800">Start a conversation!</span>
                          @else
                          <span class="text-sm text-gray-800">{{ $convo->conversation->last_message->body }}</span>
                          @endif
                        </a>
                      </td>
                      <td class="size-px whitespace-nowrap align-top">
                        <a class="block p-6" href="/inbox/conversation/{{ $convo->conversation->id }}">
                          <span class="py-2 px-3 inline-flex items-center gap-x-1 text-sm border-2 border-win-purple font-medium text-win-purple rounded-full">
                            View
                          </span>
                        </a>
                      </td>
                    </tr>
                      @endif
                      @endforeach
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- End Card -->
      </div>
      <!-- End Table Section -->
      <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
      {{-- Site footer disabled per client request — uncomment to restore --}}
      {{-- @include('layouts.footer') --}}
      <!-- end cards -->
    </main>
  </body>
</html>
