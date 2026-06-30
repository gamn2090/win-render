<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Chat</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
    <script>
        window.convoID = {{ $chat_id }};
        window.userType = "{{ Auth::user()->userType() }}";
    </script>
    @vite('resources/js/message-page.js')
  </head>

  <body class="bg-grey-50 overflow-hidden h-screen">
    @include('layouts.navigation')
    <main class="h-full">
      <div class="md:flex w-[100vw] h-full">
      <!-- Sidebar -->
        <div id="application-sidebar" class="bg-white overflow-y-auto lg:block lg:w-[20%] md:min-h-full">
          <nav class="hs-accordion-group size-full flex flex-col text-center font-semibold" data-hs-accordion-always-open>
            <div class="flex items-center justify-between md:pt-4 md:pe-4 md:ps-7">
              <div class="w-full p-4 rounded-lg">
                <div class="w-full mx-auto flex items-center justify-center">
                  <div class="flex flex-col">
                      <div class="w-[7rem] h-[7rem] md:w-[10rem] md:h-[10rem] rounded-xl shadow-3xl mb-4 md:mb-6 mx-auto">
                          <img src="{{asset('/storage/images/'. $participant->messageable->image )}}" alt="Profile" class="min-w-full max-w-full rounded-xl relative">
                      </div>
                      <div class="text-2xl text-center font-semibold">{{ $participant->messageable->first_name }} {{ $participant->messageable->last_name }}</div>
                      <div class="text-lg text-center font-semibold">{{ $participant->messageable->business_name }}</div>
                      <div class="text-md my-1 font-semibold uppercase text-center">
                        <span>
                          <img src="{{ $participant->messageable->getType()->icon }}" class="h-auto max-h-6 inline" alt="Icon">
                        </span> <span class="ml-1">{{ $participant->messageable->getType()->type }}</span>
                      </div>
                      <div class="mx-auto my-1" id="starRating">

                      </div>
                      <a class="bg-win-blue md:w-full mt-2 py-1 px-3 inline-flex justify-center items-center font-semibold rounded-lg text-white shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" href="/vendor/profile/{{ $participant->messageable->uuid }}">
                        Visit Storefront
                      </a>
                      @if($vendor_invite)
                      <div id="conn-noti-{{ $participant->messageable->id }}" class="bg-white rounded-xl shadow-lg mb-2" role="alert">
                        <div class="p-4">
                          <div class="flex-shrink-0 w-full">
                            <svg class="flex-shrink-0 mx-auto size-4 text-blue mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                            </svg>
                          </div>
                          <div class="ms-3">
                            <p class="text-sm text-gray-700">
                              <span class="font-bold">{{ $participant->messageable->first_name }}</span> would like you to join their community! 
                            </p>
                            <div class="mt-1 text-center">
                              <button value="{{ $participant->messageable->id }}" response="false" type="button" class="w-full text-center connection-btn py-2 px-3 my-1 gap-x-2 text-sm font-medium rounded-lg bg-gray-light text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                Decline
                              </button>
                              <button value="{{ $participant->messageable->id }}" response="true" type="button" class="w-full text-center connection-btn py-2 px-3 mt-1 gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                                Allow
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endif
                  </div>
                </div>
              </div>
            </div>

            <div class="h-full">
            </div>
          </nav>
        </div>
        <!-- End Sidebar -->

        <!-- Content -->
        <div id="chatContent" class="relative w-full lg:ps-16 overflow-y-scroll h-[85%]">
          <div class="pb-10 lg:pb-24">
            <div id="loadingChat" class="min-h-60 flex flex-col rounded-xl hidden">
              <div class="flex flex-auto flex-col justify-center items-center p-4 md:p-5">
                <div class="flex justify-center">
                  <i class="fas fa-sync-alt fa-spin text-win-purple text-lg sm:text-xl"></i>
                </div>
              </div>
            </div>
            <ul id="conversationList" class="mt-16 space-y-4">
            </ul>
          </div>
          <!-- End Search -->

          <div class="fixed z-20 bottom-0 right-10 my-3">
            <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4 min-w-[75vw]">
              <div class="flex grow ml-4">
                <div class="relative w-full">
                  <input
                    id="sendMsgText" type="text"
                    class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" autocomplete="off" placeholder="Type your message..."
                  />
                </div>
              </div>
              <div class="ml-4">
                <button
                  id="sendMessageButton" class="flex items-center justify-center bg-win-blue rounded-lg text-white px-4 py-1 flex-shrink-0"
                >
                  <span>Send</span>
                  <span class="ml-2">
                    <svg
                      class="w-4 h-4 transform rotate-45 -mt-px"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                      ></path>
                    </svg>
                  </span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <script>
      $( document ).ready(function() {
        let starElem = createStarElement({{ $participant->messageable->profile->google_review_score }});
        $('#starRating').html(starElem);
      });
    </script>
    <script>
      
      $("#conversationList").append(`

      `);
    </script>
  </body>
</html>
