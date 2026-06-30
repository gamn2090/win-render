<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Vendor Inbox</title>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="overflow-x-hidden">
    @include('layouts.vendor_navigation')
    <main>
      <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl">
        <div class="max-w-[85rem] px-4 pt-10 sm:px-6 lg:px-8 lg:pt-14 mx-auto">
          <h3 class="headline-small mb-2 lg:mb-4">New Inquiries</h3>
          <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto bg-white rounded-2xl overflow-x-scroll lg:overflow-hidden">
              <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-start max-sm:hidden">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Role
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Name
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start max-w-[60%]">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Latest Message
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start max-sm:hidden">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Action
                            </span>
                          </div>
                        </th>
                      </tr>
                    </thead>
        
                    <tbody class="divide-y divide-gray-200">
                      @foreach($data["conversations"] as $convo)
                        @if($convo->conversation->messages()->count() > 0)
                          @foreach($convo->conversation->getParticipants() as $participant)
                          @if($participant->getMorphClass() == 'App\Models\User' && count($convo->conversation->unReadNotifications(Auth::user())) > 0)
                          <tr class="bg-white hover:bg-grey-50 anchor-target" data-link="/inbox/conversation/{{ $convo->conversation->id }}">
                            <td class="size-px whitespace-nowrap align-top max-sm:hidden">
                              <a class="block p-6">
                                <div class="flex items-center gap-x-4">
                                  <div>
                                    <span class="block text-sm font-semibold text-white bg-win-orange rounded-xl px-3 py-1">Client</span>
                                  </div>
                                </div>
                              </a>
                            </td>
                            <td class="size-px whitespace-nowrap align-top">
                              <a class="block p-3 md:p-6">
                                <div class="sm:flex items-center gap-x-3 max-sm:text-center">
                                  <img class="inline-block size-[38px] rounded-full max-sm:mx-auto" src="{{asset('/storage/images/'. $participant->image)}}" alt="Image Description">
                                  <div class="grow">
                                    <span class="block text-sm font-semibold">{{ $participant->first_name }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $participant->fiance_first_name }}</span>
                                  </div>
                                </div>
                              </a>
                            </td>
                            <td class="max-w-[60%] align-top">
                              <p class="px-6 pt-4 line-clamp-3 break-words text-sm">
                                @if(Chat::conversation($convo->conversation)->setParticipant(Auth::guard('vendor')->user())->unreadCount() > 0)
                                <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">!</span>
                                @endif
                                @if($convo->conversation->last_message == null)
                                Start a conversation!
                                @else
                                {{ $convo->conversation->last_message->body }}
                                @endif
                              </p>
                            </td>
                            <td class="size-px whitespace-nowrap align-top max-sm:hidden">
                              <a class="block p-6" href="/inbox/conversation/{{ $convo->conversation->id }}">
                                <span class="py-[0.125rem] px-3 inline-flex items-center gap-x-1 text-sm bg-win-blue text-white rounded-lg">
                                  View
                                </span>
                              </a>
                            </td>
                          </tr>
                            @php
                              unset($data["conversations"][$loop->parent->index])
                            @endphp
                          @endif
                          @endforeach
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 mx-auto min-h-[75vh]">
          <h3 class="headline-small mb-2 lg:mb-4">Inbox</h3>
          <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto bg-white rounded-2xl overflow-x-scroll lg:overflow-hidden">
              <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-start max-sm:hidden">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Role
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Name
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Latest Message
                            </span>
                          </div>
                        </th>
        
                        <th scope="col" class="px-6 py-3 text-start max-sm:hidden">
                          <div class="flex items-center gap-x-2">
                            <span class="font-semibold uppercase tracking-wide">
                              Action
                            </span>
                          </div>
                        </th>
                      </tr>
                    </thead>
        
                    <tbody class="divide-y divide-gray-200">
                      @foreach($data["conversations"] as $convo)
                        @if($convo->conversation->messages()->count() > 0)
                          @foreach($convo->conversation->getParticipants() as $participant)
                          @if($participant->id != Auth::user()->id && $participant->getMorphClass() == 'App\Models\Vendor')
                          <tr class="bg-white hover:bg-grey-50 anchor-target" data-link="/inbox/conversation/{{ $convo->conversation->id }}">
                            <td class="size-px whitespace-nowrap align-top max-sm:hidden">
                              <a class="block p-6">
                                <div class="flex items-center gap-x-4">
                                  <div>
                                    <span class="block text-sm font-semibold text-white bg-win-purple rounded-xl px-3 py-1">Vendor</span>
                                  </div>
                                </div>
                              </a>
                            </td>
                            <td class="size-px whitespace-nowrap align-top">
                              <a class="block p-3 md:p-6">
                                <div class="sm:flex items-center gap-x-3 max-sm:text-center">
                                  <img class="inline-block size-[38px] rounded-full max-sm:mx-auto" src="{{asset('/storage/images/'. $participant->image)}}" alt="Image Description">
                                  <div class="grow">
                                    <span class="block text-sm font-semibold">{{ $participant->business_name }}</span>
                                    <span class="block text-sm text-gray-500">{{ $participant->first_name }}</span>
                                  </div>
                                </div>
                              </a>
                            </td>
                            <td class="h-px w-72 min-w-72 align-top">
                              <a class="block p-6">
                                @if(Auth::guard('vendor')->user()->hasRequestFrom($participant->id) || Chat::conversation($convo->conversation)->setParticipant(Auth::user())->unreadCount() > 0)
                                <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">New</span>
                                @endif
                                @if($convo->conversation->last_message == null)
                                <span class="text-sm">Start a conversation!</span>
                                @else
                                <span class="text-sm">{{ $convo->conversation->last_message->body }}</span>
                                @endif
                              </a>
                            </td>
                            <td class="size-px whitespace-nowrap align-top max-sm:hidden">
                              <a class="block p-6" href="/inbox/conversation/{{ $convo->conversation->id }}">
                                <span class="py-[0.125rem] px-3 inline-flex items-center gap-x-1 text-sm bg-win-blue text-white rounded-lg">
                                  View
                                </span>
                              </a>
                            </td>
                          </tr>
                          @elseif($participant->getMorphClass() == 'App\Models\User')
                          <tr class="bg-white hover:bg-grey-50 anchor-target" data-link="/inbox/conversation/{{ $convo->conversation->id }}">
                            <td class="size-px whitespace-nowrap align-top max-sm:hidden">
                              <a class="block p-6">
                                <div class="flex items-center gap-x-4">
                                  <div>
                                    <span class="block text-sm font-semibold text-white bg-win-orange rounded-xl px-3 py-1">Client</span>
                                  </div>
                                </div>
                              </a>
                            </td>
                            <td class="size-px whitespace-nowrap align-top">
                              <a class="block p-3 md:p-6">
                                <div class="sm:flex items-center gap-x-3 max-sm:text-center">
                                  <img class="inline-block size-[38px] rounded-full max-sm:mx-auto" src="{{asset('/storage/images/'. $participant->image)}}" alt="Image Description">
                                  <div class="grow">
                                    <span class="block text-sm font-semibold">{{ $participant->first_name }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $participant->fiance_first_name }}</span>
                                  </div>
                                </div>
                              </a>
                            </td>
                            <td class="h-px w-72 min-w-72 align-top">
                              <a class="block p-6">
                                @if(Chat::conversation($convo->conversation)->setParticipant(Auth::user())->unreadCount() > 0)
                                <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red text-white">!</span>
                                @endif
                                @if($convo->conversation->last_message == null)
                                <span class="text-sm">Start a conversation!</span>
                                @else
                                <span class="text-sm">{{ $convo->conversation->last_message->body }}</span>
                                @endif
                              </a>
                            </td>
                            <td class="size-px whitespace-nowrap align-top max-sm:hidden">
                              <a class="block p-6" href="/inbox/conversation/{{ $convo->conversation->id }}">
                                <span class="py-[0.125rem] px-3 inline-flex items-center gap-x-1 text-sm bg-win-blue text-white rounded-lg">
                                  View
                                </span>
                              </a>
                            </td>
                          </tr>
                          @endif
                          @endforeach
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('layouts.footer')
      <script>
        $(document).ready(function() {
          $('.anchor-target').click(function() {
            window.location.href = $(this).data('link');
          });
        });
      </script>
    </main>
  </body>
</html>
