<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Inbox</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="overflow-x-hidden">
    @include('layouts.navigation')
    <main><!-- Table Section -->
      
      <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto min-h-[75vh]">
          <!-- Card -->
          <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
              <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="bg-white rounded-xl overflow-hidden">
                  <!-- Table -->
                  <table class="table-fixed w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
        
                        <th scope="col" class="px-6 py-3 text-start w-[15%]">
                          <div class="flex items-center gap-x-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                              Name
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start w-[70%]">
                          <div class="flex items-center gap-x-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                              Latest Message
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start max-sm:hidden w-[15%]">
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
                        @if($participant->messageable_type != 'App\Models\User')
                      <tr class="bg-white hover:bg-grey-50 anchor-target" data-link="/client/conversation/{{ $convo->conversation->id }}">
                        <td class="size-px whitespace-nowrap align-top">
                          <a class="block p-6">
                            
                            @if(Chat::conversation($convo->conversation)->setParticipant(Auth::user())->unreadCount() > 0)
                            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">!</span>
                            @endif
                            <div class="sm:flex items-center gap-x-3 max-sm:text-center">
                              <img class="inline-block size-[38px] rounded-full max-sm:mx-auto" src="{{asset('/storage/images/'. $participant->messageable->image)}}" alt="Image Description">
                              <div class="grow">
                                <span class="block text-sm font-semibold text-gray-800">{{ $participant->messageable->business_name }}</span>
                                <span class="block text-sm text-gray-500">{{ $participant->messageable->first_name }}</span>
                              </div>
                            </div>
                          </a>
                        </td>
                        <td class="w-[70%] align-top">
                          <p class="px-6 pt-4 line-clamp-3 break-words text-sm">
                            @if($convo->conversation->last_message == null)
                            Start a conversation!
                            @else
                            {{ $convo->conversation->last_message->body }}
                            @endif
                          </p>
                        </td>
                        <td class="size-px whitespace-nowrap align-top max-sm:hidden">
                          <a class="block p-6" href="/client/conversation/{{ $convo->conversation->id }}">
                            <span class="py-1 px-4 inline-flex items-center gap-x-1 text-sm bg-win-blue font-medium text-white rounded-lg">
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
      </div>
      <!-- End Table Section -->
      @include('layouts.footer')
      <script>
        $(document).ready(function() {
          $('.anchor-target').click(function() {
            window.location.href = $(this).data('link');
          });
        });
      </script>
      <!-- end cards -->
    </main>
  </body>
</html>
