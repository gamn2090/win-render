<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Vendor Dashboard</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.js" integrity="sha512-wqcdhB5VcHuNzKcjnxN9wI5tB3nNorVX7Zz9NtKBxmofNskRC29uaQDnv71I/zhCDLZsNrg75oG8cJHuBvKWGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.css" integrity="sha512-qc0GepkUB5ugt8LevOF/K2h2lLGIloDBcWX8yawu/5V8FXSxZLn3NVMZskeEyOhlc6RxKiEj6QpSrlAoL1D3TA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <script src="https://preline.co/assets/js/hs-apexcharts-helpers.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>window.userID = {{ Auth::user()->id }};</script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/chat.js')
    @include('components.fonts')
    <style>
      .button-text{
        font-size: 1rem;
      }
      .small-title{
        font-family: "NeulisNeue-Bold", sans-serif;
        font-size:1rem;
      }
    </style>
    @if($data["first_login"])
    <script>
      window.newUser = true;
    </script>
    @endif
  </head>

  <body class="m-0 antialiased overflow-x-hidden">
    @include('layouts.vendor_navigation')
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
      <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl">
        <div class="w-full px-6 py-6 container mx-auto">
            <div class="w-full mb-4 mt-2 border-2 rounded-lg border-win-purple p-4 bg-white">
              <h3><i class="fa-solid fa-circle-exclamation text-win-purple"></i> <span class="font-bold">Announcement</span><span class="font-semibold">: New filter feature has beeen added for venues, hair & makeup, photographers, and videographers. Go to "Edit Profile" to select your unique services.</span></h3>
            </div>
          <div id="notification-bar" class="flex flex-wrap bg-white p-4 lg:px-8 lg:pt-8 lg:pb-6 rounded-3xl text-center">
            <div class="w-full mb-6">
              <h3 class="headline-small text-center">&#127881; Welcome, {{ Auth::user()->first_name }}!</h3>
              <button class="flex ml-auto text-right md:mt-[-2.5rem]" id="tutorial-btn">
                <svg class="align-middle ml-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10" />
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                  <path d="M12 17h.01" />
                </svg>
              </button>
            </div>
            <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/5">
              <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex-none max-w-full">
                      <div>
                        <h5 class="mb-0 subheading">{{ Auth::user()->storefront_views }}</h5>
                        <p class="mb-0 subheading uppercase" style="font-size:1rem;">Storefront Views</p>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/5">
              <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex-none max-w-full">
                      <div>
                        <h5 class="mb-0 subheading">{{ Auth::user()->unreadMessagesCount() }}</h5>
                        <p class="mb-0 subheading uppercase" style="font-size:1rem;">Unread Messages</p>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/5">
              <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex-none max-w-full">
                      <div>
                        <h5 class="mb-0 subheading">{{ Auth::user()->numberOfClients() }}</h5>
                        <p class="mb-0 subheading uppercase" style="font-size:1rem;">CURRENT CLIENTS</p>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="w-full max-w-full mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/5">
              <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex-none max-w-full">
                      <div>
                        <h5 class="mb-0 subheading">{{ Auth::user()->contact_credits }}</h5>
                        <p class="mb-0 subheading uppercase" style="font-size:1rem;">Contact Credits</p>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="w-full max-w-full mb-6 sm:flex-none xl:mb-0 xl:w-1/5">
              <div class="relative flex flex-col min-w-0 break-words rounded-3xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex-none max-w-full">
                      <div>
                        <p class="mb-0 subheading">#{{ $data["placement"] + 1 }} in {{ Auth::user()->getType()->type }}</p>
                        <p class="mb-0 subheading uppercase" style="font-size:1rem;">Category Ranking</p>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="grid lg:grid-cols-2 xl:grid-cols-4 mt-8 -mx-2">
            <div id="current-clients-card" class="rounded-3xl bg-white min-h-96 col-span-1 m-2">
              <div class="min-h-full">
                <div class="flex flex-col overflow-x-hidden">
                  <div class="">
                    <div class="min-w-full inline-block align-middle">
                      <div class="bg-white rounded-3xl min-h-full overflow-hidden">
                        <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                          <div>
                            <h2 class="subheading mb-2">
                              Current Clients
                            </h2>
                          </div>
                        </div>
                        <div class="flex justify-center w-[90%] mx-auto py-6 border rounded-xl border-win-light border-4">
                          <div class="flex inline-flex -space-x-2 lg:-space-x-4">
                            @forelse($data["clients"] as $client)
                            @break($loop->index > 2)
                            <div class="hs-tooltip inline-block">
                              <img class="hs-tooltip-toggle relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10" src="{{ asset('/storage/images/'.$client->image) }}" alt="Avatar">
                              <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-black text-xs text-white rounded-lg" role="tooltip">
                                {{ $client->first_name }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $client->fiance_first_name }}
                              </span>
                            </div>
                            @empty
                            <div class="hs-tooltip inline-block">
                              <img class="relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10 mx-auto" src="{{ asset('/storage/images/user.jpg') }}" alt="Avatar">
                            </div>
                            @endforelse
                          </div>
                          <div class="flex inline-flex px-2">
                            <p class="font-semibold">
                            {{ Auth::user()->numberOfClients() }} Clients
                            <br>
                            In Network
                            </p>
                          </div>
                          <div class="flex inline-flex px-2 my-2 text-center">
                            <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="/vendor/client/list">
                              See all
                            </a>
                          </div>
                        </div>
                        <table class="w-[90%] mx-auto border-win-light">
                          <tbody class="border-0">
                            @foreach($data["clients"] as $client)
                            <tr class="border-b-4 border-win-light">
                              <td class="whitespace-nowrap">
                                <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                  <div class="flex items-center gap-x-3">
                                    <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$client->image) }}" alt="Image Description">
                                    <div class="grow">
                                      <span class="block text-sm font-semibold text-gray-800">{{ $client->first_name }} <i class="fas fa-heart text-win-purple inline-flex"></i> {{ $client->fiance_first_name }}</span>
                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td class="whitespace-nowrap">
                                <div class="pl-2 py-1.5 text-center">
                                  <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="{{ route('vendor.couple.profile', ['id' => $client->uuid]) }}">
                                    View
                                  </a>
                                </div>
                              </td>
                              <td class="whitespace-nowrap">
                                <div class="ml-1 py-1.5">
                                  <button class="inline-flex items-center gap-x-1 text-sm bg-win-blue px-2 py-1.5 rounded-lg decoration-2 font-medium hover:cursor-pointer chat-window-btn" data-picture-url="{{ asset('/storage/images/'.$client->image) }}" data-name="{{ $client->first_name }} {{ $client->last_name }}" data-uuid="{{ $client->uuid }}" data-user-type="{{ $client->userType() }}">
                                    <i class="fas fa-comments text-white"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 
            </div>
            <div id="vendor-network-card" class="rounded-3xl bg-white min-h-96 col-span-1 m-2">
              <div class="min-h-full">
                <div class="flex flex-col">
                  <div class="overflow-x-hidden">
                    <div class="min-w-full inline-block align-middle">
                      <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
                        <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                          <div>
                            <h2 class="subheading mb-2">
                              My Vendor Network
                            </h2>
                          </div>
                        </div>
                        <div class="flex justify-center w-[90%] mx-auto py-6 border rounded-xl border-win-light border-4">
                          <div class="flex inline-flex -space-x-2 lg:-space-x-4">
                            @forelse(Auth::user()->connections(3)->get() as $client)
                            <div class="hs-tooltip inline-block">
                              <img class="hs-tooltip-toggle relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10" src="{{ asset('/storage/images/'.$client->image) }}" alt="Avatar">
                              <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-black text-xs text-white rounded-lg" role="tooltip">
                                {{ $client->first_name }} {{ $client->last_name }}
                              </span>
                            </div>
                            @empty
                            <div class="hs-tooltip inline-block">
                              <img class="relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10 mx-auto" src="{{ asset('/storage/images/user.jpg') }}" alt="Avatar">
                            </div>
                            @endforelse
                          </div>
                          <div class="flex inline-flex px-2">
                            <p class="font-semibold">
                            {{ Auth::user()->connections()->count() }} Vendor
                            <br>
                            Connections
                            </p>
                          </div>
                          <div class="flex inline-flex px-2 my-2 text-center">
                            <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="/vendor/list">
                              See all
                            </a>
                          </div>
                        </div>
                        <table class="w-[90%] mx-auto border-win-light" >
                          <tbody class="border-0 max-h-[50vh] lg:max-h-[75vh] overflow-auto">
                            @foreach($data["connections"] as $connection)
                            <tr class="border-b-4 border-win-light">
                              <td class="whitespace-nowrap">
                                <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                  <div class="flex items-center gap-x-3">
                                    <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$connection->image) }}" alt="Image Description">
                                    <div class="grow">
                                      <span class="block text-sm font-semibold text-gray-800">{{ $connection->first_name }} {{ $connection->last_name }}</span>
                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td class="whitespace-nowrap">
                                <div class="pl-2 py-1.5 text-center">
                                  <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="/vendor/profile/{{ $connection->uuid }}">
                                    View
                                  </a>
                                </div>
                              </td>
                              <td class="whitespace-nowrap">
                                <div class="ml-1 py-1.5">
                                  <button class="inline-flex items-center gap-x-1 text-sm bg-win-blue px-2 py-1.5 rounded-lg decoration-2 font-medium hover:cursor-pointer chat-window-btn" data-picture-url="{{ asset('/storage/images/'.$connection->image) }}" data-name="{{ $connection->first_name }} {{ $connection->last_name }}" data-uuid="{{ $connection->uuid }}" data-user-type="{{ $connection->userType() }}">
                                    <i class="fas fa-comments text-white"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="rounded-3xl bg-white min-h-96 col-span-1 m-2">
              <div class="min-h-full">
                <div class="flex flex-col">
                  <div class="overflow-x-hidden">
                    <div class="min-w-full inline-block align-middle">
                      <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
                        <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                          <div>
                            <h2 class="subheading mb-2">
                              Inbox
                            </h2>
                          </div>
                        </div>
                        <div class="flex justify-center w-[90%] mx-auto py-6 border rounded-xl border-win-light border-4">
                        <div class="flex inline-flex -space-x-2 lg:-space-x-4">
                            @forelse($data["recentConversations"] as $convo)
                              @foreach($convo->conversation->participants as $participant)
                                @if($participant->messageable->id != Auth::guard("vendor")->user()->id || $participant->messageable_type != 'App\Models\Vendor')
                            <div class="hs-tooltip inline-block">
                              <img class="hs-tooltip-toggle relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10" src="{{ asset('/storage/images/'.$participant->messageable->image) }}" alt="Avatar">
                              <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-black text-xs text-white rounded-lg" role="tooltip">
                                {{ $participant->messageable->first_name }} {{ $participant->messageable->last_name }}
                              </span>
                            </div>
                                @endif
                              @endforeach
                            @empty
                            <div class="hs-tooltip inline-block">
                              <img class="relative inline-block size-[38px] rounded-full ring-2 ring-white hover:z-10 mx-auto" src="{{ asset('/storage/images/user.jpg') }}" alt="Avatar">
                            </div>
                            @endforelse
                          </div>
                          <div class="flex inline-flex px-2">
                            <p class="font-semibold">
                            {{ Auth::user()->unreadMessagesCount() }} Unread
                            <br>
                            Messages
                            </p>
                          </div>
                          <div class="flex inline-flex px-2 my-2 text-center">
                            <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg" href="/vendor/inbox">
                              See all
                            </a>
                          </div>
                        </div>
                        <table class="w-[90%] mx-auto pb-4" >
                          <tbody class="border-0 max-h-[50vh] lg:max-h-[75vh] overflow-auto max-w-full">
                            @foreach($data["recentConversations"] as $convo)
                              @foreach($convo->conversation->participants as $participant)
                                @if($participant->messageable->id != Auth::guard("vendor")->user()->id || $participant->messageable_type != 'App\Models\Vendor')
                                  <tr class="border-b-4 border-win-light max-w-full">
                                    <td class="size-px">
                                      <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                        <div class="flex items-center gap-x-3">
                                          <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$participant->messageable->image) }}" alt="Image Description">
                                          <div class="grow">
                                            <span class="block text-sm font-semibold text-gray-800">{{ $participant->messageable->first_name }} {{ $participant->messageable->last_name }}</span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="pr-3 py-1 text-left max-w-full">
                                        <p class="max-w-full break-words line-clamp-3">{{ $convo->conversation->last_message->body ?? "Start a conversation!" }}</p>
                                      </div>
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
              </div>
            </div>
            <div class="rounded-3xl bg-white min-h-96 col-span-1 m-2">
              <div class="overflow-x-hidden min-h-full min-w-full">
                <div class="px-6 pt-6 grid gap-3 md:flex md:justify-between md:items-center">
                  <h2 class="subheading mb-2">
                    My Wedding Appointments
                  </h2>
                </div>
                <div class="flex justify-center w-[90%] mx-auto py-6 border rounded-xl border-win-light border-4">
                  <div class="flex inline-flex px-2">
                    <p class="font-semibold">
                    {{ Auth::user()->upcomingMeetings()->count() }} Meeting(s)
                    <br>
                    Upcoming
                    </p>
                  </div>
                  <div class="flex inline-flex px-2 my-2 text-center">
                    <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-3 py-[0.125rem] font-medium rounded-lg mr-1" href="/appointments">
                      See all
                    </a>
                    <a class="inline-flex items-center gap-x-1 text-sm text-white bg-win-blue px-2 py-[0.125rem] font-medium rounded-lg" href="/vendor/profile#edit-calendar">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                  </div>
                </div>
                
                <table class="w-[90%] mx-auto pb-4" >
                  <tbody class="border-0 max-h-[50vh] lg:max-h-[75vh] overflow-auto">
                    @foreach(Auth::user()->upcomingMeetings()->get() as $meeting)
                      @php
                        $meeting_client = $meeting->client()->first();
                      @endphp
                      <tr class="border-b-4 border-win-light">
                        <td class="size-px">
                          <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 pt-2 pb-1">
                            <div class="flex items-center gap-x-3">
                              <img class="inline-block size-[38px] rounded-full" src="{{ asset('/storage/images/'.$meeting_client->image) }}" alt="Image Description">
                              <div class="grow">
                                <span class="block text-sm font-semibold text-gray-800">{{ $meeting_client->first_name }}</span>
                              </div>
                            </div>
                          </div>
                          <div class="pr-2 py-1 text-left text-sm">
                            <p class="font-semibold">{{ ucfirst($meeting->type) }}</p>
                            <p>{{ $meeting->readableTime() }}</p>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="flex flex-wrap bg-white p-4 lg:p-8 rounded-3xl mt-4 md:mt-8">
            <div class="w-full mb-6">
              <h3><span class="headline-small align-middle">WINfluence Status:</span> <a href="/vendor/insights" class="px-2 py-1 bg-win-blue text-white text-sm rounded-lg sm:ml-1 lg:ml-2 align-middle">View Insights</a></h3>
            </div>
            <div class="xl:flex">
              <div class="grid md:grid-cols-2 lg:grid-cols-5 w-full md:w-3/4 mx-auto">
                <div class="mx-auto text-center">
                  <p class="font-semibold mb-1 lg:mb-2">My Badges</p>
                  <div class="relative size-36 text-win-purple mx-auto">
                    <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                      <!-- Background Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                      <!-- Progress Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['badges']) }}" stroke-linecap="round"></circle>
                    </svg>

                    <!-- Percentage Text -->
                    <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                      <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['badges']) }}%</span>
                    </div>
                  </div>
                </div>
                <div class="mx-auto text-center">
                  <p class="font-semibold mb-1 lg:mb-2">Endorsements</p>
                  <div class="relative size-36 text-win-purple mx-auto">
                    <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                      <!-- Background Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                      <!-- Progress Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['endorsements']) }}" stroke-linecap="round"></circle>
                    </svg>

                    <!-- Percentage Text -->
                    <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                      <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['endorsements']) }}%</span>
                    </div>
                  </div>
                </div>
                <div class="mx-auto text-center">
                  <p class="font-semibold mb-1 lg:mb-2">Vendor Community</p>
                  <div class="relative size-36 text-win-purple mx-auto">
                    <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                      <!-- Background Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                      <!-- Progress Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['vendor_community']) }}" stroke-linecap="round"></circle>
                    </svg>

                    <!-- Percentage Text -->
                    <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                      <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['vendor_community']) }}%</span>
                    </div>
                  </div>
                </div>
                <div class="mx-auto text-center">
                  <p class="font-semibold mb-1 lg:mb-2">Client Community</p>
                  <div class="relative size-36 text-win-purple mx-auto">
                    <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                      <!-- Background Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                      <!-- Progress Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['client_community']) }}" stroke-linecap="round"></circle>
                    </svg>

                    <!-- Percentage Text -->
                    <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                      <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['client_community']) }}%</span>
                    </div>
                  </div>
                </div>
                <div class="mx-auto text-center">
                  <p class="font-semibold mb-1 lg:mb-2">Reviews</p>
                  <div class="relative size-36 text-win-purple mx-auto">
                    <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                      <!-- Background Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                      <!-- Progress Circle -->
                      <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['reviews']) }}" stroke-linecap="round"></circle>
                    </svg>

                    <!-- Percentage Text -->
                    <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                      <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['reviews']) }}%</span>
                    </div>
                  </div>
                </div>
              </div>
              <img src="/assets/img/insights/winfluence-cake.PNG" class="object-scale-down w-3/4 md:w-1/2 xl:w-1/4 mx-auto">
            </div>
          </div>
          <div id="badges-section" class="flex flex-wrap mt-6 -mx-3">
            <div class="w-full max-w-full px-3 my-4 lg:flex-none lg:grid lg:grid-cols-2 lg:space-x-8">
              <div class="relative flex min-w-0 flex-col rounded-2xl border-0 bg-white bg-clip-border p-6">
                <h3 class="subheading text-center">Your Badges:</h3>
                <div class="mt-3 space-x-1 md:space-x-4 mx-auto">
                  @foreach(Auth::guard('vendor')->user()->badges() as $badge)
                  <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 border-win-purple rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                    <img src="/images/{{ $badge->icon }}" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                      {{ $badge->name }}
                    </span>
                  </a>
                  @endforeach
                </div>
              </div>
              <div class="relative flex min-w-0 flex-col rounded-2xl border-0 bg-white bg-clip-border p-6">
                <h3 class="subheading text-center">Locked Badges:</h3>
                <div class="mt-3 space-x-1 md:space-x-4 mx-auto">
                  @if(!Auth::guard('vendor')->user()->trendingBadge())
                  <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 border-gray rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                    <img src="/images/trending-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                      Trending: Top 15% in storefront views this month!
                    </span>
                  </a>
                  @endif
                  @if(!Auth::guard('vendor')->user()->earlyAdopterBadge())
                  <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 border-gray rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                    <img src="/images/early-adopter-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                      Early Adopter: Registered within 6 months of launch!
                    </span>
                  </a>
                  @endif
                  @if(!Auth::guard('vendor')->user()->fastResponderBadge())
                  <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 border-gray rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                    <img src="/images/fast-responder-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                      Fast Responder: Responds quickly to messages!
                    </span>
                  </a>
                  @endif
                  @if(!Auth::guard('vendor')->user()->communityBuilderBadge())
                  <a class="hs-tooltip inline-flex justify-center items-center size-16 md:size-16 border-2 border-gray rounded-lg disabled:opacity-50 disabled:pointer-events-none" href="#">
                    <img src="/images/community-builder-badge.png" class="w-12 h-12" style="filter: invert(21%) sepia(79%) saturate(2450%) hue-rotate(250deg) brightness(88%) contrast(99%);">
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black text-sm font-medium text-white rounded shadow-sm" role="tooltip">
                      Community Builder: This vendor frequently appears on other storefronts!
                    </span>
                  </a>
                  @endif
                </div>
              </div>
            </div>
            <div class="flex flex-col gap-y-3 lg:gap-y-5 p-4 md:p-5 mx-3 bg-white w-full rounded-xl mt-4 hidden">
              <div class="inline-flex justify-center items-center">
                <span class="size-2 inline-block bg-green rounded-full me-2"></span>
                <span class="body-copy font-semibold uppercase">Your Subscription 
                  @if(Auth::guard('vendor')->user()->isSubscribed()) <a href="/billing-portal" class="bg-win-blue text-white px-3 py-1 rounded-lg text-sm">Edit</a>@endif</span>
              </div>

              <div class="text-center">
                <h3 class="subheading" style="font-size: 2rem;">
                  Not Subscribed
                </h3>
              </div>
              <div class="hidden xl:block absolute right-0">
                  <img src="/assets/img/shapes/confetti-small-transparent.png" class="w-[60%] h-[60%]">
              </div>
            </div>
          </div>
        </div>
        <section id="community-vendors-section" class="mt-8 bg-win-light py-16">
          <h3 class="font-bold text-2xl text-center">Browse professionals in our network:</h3>
          <div class="mx-8 mt-3 justify-center">
            <div class="w-full">
              <div class="grid md:grid-cols-4 gap-4">
                @php($vendors = App\Models\Vendor::take(4)->get())
                @foreach($vendors as $vendor)
                <x-vendor-profile-card :vendor="$vendor" />
                @endforeach
              </div>
            </div>
          </div>
          <div id="search-vendors-section" class="w-full text-center text-white bg-win-purple py-4 lg:py-6">
              <p class="text-2xl lg:text-3xl font-semibold">View other vendors in your community</p>
              <p class=" lg:text-xl font-semibold">Connect, engage, refer & WIN</p>
              <a class="w-[25%] mt-4 py-3 px-4 inline-flex justify-center items-center font-medium rounded-full bg-win-purple border-2 border-white text-white shadow-sm disabled:opacity-50 disabled:pointer-events-none" href="/vendor/search/vendors">
                  View More
              </a>
          </div>
        </section>
      </div>
      @include('chat.window')
      @include('layouts.footer')
    </main>
    <div id="paymentModal" class="hs-overlay [--overlay-backdrop:static] bg-black/80 size-full fixed top-0 start-0 z-[100] overflow-x-hidden overflow-y-auto" hidden>
      <div class="mt-8 lg:mt-16">
        <div class="max-w-[95rem] px-4 py-10 sm:px-6 lg:px-8 mx-auto bg-white rounded-xl z-[110]">
          <div class="-mx-4 -mt-12 sm:-mx-6 lg:-mx-8 lg:-mt-16 bg-win-purple py-6 mb-4 md:mb-6 rounded-t-xl"></div>
          <!-- Comparison Table -->
<div class="relative">
  <div class="max-w-[85rem] px-4 py-6 sm:px-6 lg:px-8 mx-auto">
    <div class="max-w-2xl mx-auto text-center mb-8">
      <h2 class="text-2xl font-bold md:text-3xl md:leading-tight">Simple, transparent pricing</h2>
      <p class="mt-1 text-gray-600">Increase your teams productivity. Get things done in rapid time.</p>
    </div>

    <div class="relative after:absolute after:inset-x-0 after:bottom-0 after:z-10 after:w-full after:h-48 after:bg-linear-to-t after:from-white after:via-white/70">
      <!-- Header -->
      <div class="hidden lg:block sticky top-0 start-0 py-2 bg-white">
        <!-- Grid -->
        <div class="grid grid-cols-3 gap-6">
          <div class="col-span-1">
            <!-- Header -->
            <div class="h-full">

            </div>
            <!-- End Header -->
          </div>
          <!-- End Col -->

          <div class="col-span-1">
            <!-- Header -->
            <div class="h-full p-4 flex flex-col justify-between bg-white border border-gray-200 rounded-xl text-center">
              <div>
                <span class="subheading">
                  Signature Access
                </span>
                <p class="text-sm text-gray-500">
                  $59 per month: Pay-As-You-Go
                </p>
              </div>
              <div class="mt-2">
                <a class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:text-white hover:bg-win-lavender disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-50" href="/vendor/pay">
                  Get started
                </a>
              </div>
            </div>
            <!-- End Header -->
          </div>
          <!-- End Col -->

          <div class="col-span-1">
            <!-- Header -->
            <div class="h-full p-4 flex flex-col justify-between bg-white border border-gray-200 rounded-xl text-center">
              <div>
                <span class="subheading">
                  Elite Community Builder <span class="font-semibold text-xs"><span class="text-win-red">*</span>Limited-Time Only</span>
                </span>
                <p class="text-sm text-gray-500">
                  $40 per month for 6 months<br>($240 billed every 6 months)
                </p>
              </div>
              <div class="mt-2">
                <a class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-win-purple text-white hover:bg-win-lavender focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none" href="#">
                  Get started
                </a>
              </div>
            </div>
            <!-- End Header -->
          </div>
          <!-- End Col -->
        </div>
        <!-- End Grid -->
      </div>
      <!-- End Header -->

      <!-- Section -->
      <div class="space-y-4 lg:space-y-0 border-b-2 border-grey-100">
        <!-- List -->
        <ul class="grid lg:grid-cols-3 lg:gap-6">
          <!-- Item -->
          <li class="lg:col-span-1 lg:py-3 px-2">
            <span class="text-lg font-semibold text-gray-800">
              General
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="hidden lg:block lg:col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center">
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="hidden lg:block lg:col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center">
          </li>
          <!-- End Item -->
        </ul>
        <!-- End List -->

        <!-- List -->
        <ul class="grid lg:grid-cols-3 lg:gap-6 px-2">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
               Earn discounts on your next billing cycle with our Premium Community Vendors Program (See terms of eligibility)
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 bg-grey-100 px-2 rounded-xl">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
               Gain access to our shared community of vendors
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 px-2">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
               Contact Credits for our Find Couples Features
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                15/month
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                25/month
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 bg-grey-100 px-2 rounded-xl">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
                Feature in Search Vendors categories & earn merit ranking points
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 px-2">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
                Unlock exclusive access to all Warm Leads
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 bg-grey-100 px-2 rounded-xl">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
              Unlimited FREE vendors linked to your storefront
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 px-2">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
              Unlimited vendor storefront connections
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 bg-grey-100 px-2 rounded-xl">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
              Unlimited client inquiries & connections
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <ul class="grid lg:grid-cols-3 lg:gap-6 px-2">
          <!-- Item -->
          <li class="lg:col-span-1 pb-1.5 lg:py-3">
            <span class="text-sm text-gray-800">
              Access to networking events & special benefits for WIN Elite members during events
            </span>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Signature Access
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->

          <!-- Item -->
          <li class="col-span-1 py-1.5 lg:py-3 px-4 lg:px-0 lg:text-center bg-gray-100">
            <div class="grid grid-cols-6 lg:block">
              <span class="lg:hidden col-span-2 font-semibold text-sm text-gray-800">
                Elite Community Builder
              </span>
              <span class="text-sm text-gray-800">
                <svg class="shrink-0 lg:mx-auto size-5 text-win-purple" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
          </li>
          <!-- End Item -->
        </ul>
        <!-- End List -->
      </div>
        <p class="px-2 text-sm mt-6">Terms of eligibility for Elite Community Builders program: <br><br>
        To qualify for the 25% discount through the Elite Community Vendors Program, you must successfully sign up a minimum of 10 unique couples within your current billing cycle. All couples must be verified accounts linked to your referral URL or unique invite code. Accounts established outside of your referral code are not eligible. Discounts will be applied to the next billing cycle following verification and do not carry over if unused. Program terms are subject to change at any time without notice.</p>
      <!-- End Section -->
    </div>
  </div>
</div>
<!-- End Comparison Table -->
          <div class="mx-auto max-w-2xl mb-8 text-center hidden">
            <h2 class="headline-small">
              You're signing up for your free trial! No charge today. Your subscription will begin after the trial period.
            </h2>
          </div>
          <div class="relative xl:w-10/12 xl:mx-auto hidden">
            <div class="grid grid-cols-1 gap-6 lg:gap-8">
              <div>
                <div class="p-4 relative z-10 min-h-full bg-white border border-win-purple border-4 rounded-xl md:p-10">
                  <h3 class="text-xl font-bold text-gray-800">All Access Vendor Free Trial</h3>
                  <div class="text-sm text-gray-500">Access to Wedding Insiders Network</div>

                  <div class="mt-5">
                    <span class="text-6xl font-bold text-gray-800">$0</span>
                    <span class="text-lg font-bold text-gray-800">.00</span>
                    <span class="ms-3 text-gray-500">USD / 60 days</span>
                  </div>
                  <div>
                      <p>Only $59/month after 60 days. No credit card required.</p>
                  </div>

                  <div class="mt-5 grid sm:grid-cols-2 gap-y-2 py-4 first:pt-0 last:pb-0 sm:gap-x-6 sm:gap-y-0">
                    <ul class="space-y-2 text-sm sm:text-base">
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                          Online storefront
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                        Complimentary “Find Couples” feature
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                        Merit-based ranking system
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                        Access to network of vendor referrals
                        </span>
                      </li>
                    </ul>
                    <ul class="space-y-2 text-sm sm:text-base">
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                        SEO back links &amp; analytics
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                        60 Day Free Trial
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                        No commitment. Cancel anytime.
                        </span>
                      </li>
                      <li class="flex space-x-3">
                        <span class="mt-0.5 size-5 flex justify-center items-center rounded-full">
                          <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="text-gray-800">
                        Unlimited access to vendor & couple connections
                        </span>
                      </li>
                    </ul>
                  </div>

                  <div class="mt-5 grid grid-cols-2 gap-x-4 py-4 first:pt-0 last:pb-0">
                    <div>
                      <p class="text-sm text-gray-500">Cancel anytime.</p>
                      <p class="text-sm text-gray-500">No card required.</p>
                    </div>

                    <div class="flex justify-end">
                      <a href="/vendor/pay" class="py-2 px-4 inline-flex hover:cursor-pointer items-center gap-x-2 button-text rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">Start subscription</a>
                    </div>
                    <div class="mx-auto w-full col-span-2">
                      <div
                          class="mx-auto w-full flex items-center uppercase before:flex-[1_1_0%] before:border-t before:me-6 after:flex-[1_1_0%] after:border-t after:ms-6 py-4">
                          Or</div>
                    </div>
                    <div class="col-span-2 w-full text-center">
                      <form method="POST" action="/vendor/logout">
                        @csrf
                        <button type="submit" class="underline gap-x-3.5 py-2 px-3 rounded-lg text-gray-800 hover:cursor-pointer focus:ring-2 focus:ring-blue-500">
                          Logout
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  @if(false)
  <script>
    $( document ).ready(function() {
      $('#paymentModal').attr("hidden", false);
      $('#startSubscriptionButton').on('click', () => {
        $.ajax({
          type: "GET",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/vendor/pay",
          success: function (data) {
            if(data["url"]){
              window.open(data["url"], '_blank'); 
            }
          }
        });
      });
    });
  </script>
  @endif
</html>
