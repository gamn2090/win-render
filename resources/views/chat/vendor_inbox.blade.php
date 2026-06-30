<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Vendor Inbox</title>
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>

  <body class="bg-dark-grey-win overflow-x-hidden">
    @include('layouts.vendor_navigation')

    <main>
      <div class="flex h-[85vh] antialiased text-gray-800">
          <div class="flex flex-row h-full w-full overflow-x-hidden">
            <div class="flex flex-col py-8 pl-6 pr-2 w-64 bg-white flex-shrink-0 rounded-lg">
              <div class="flex flex-row items-center justify-center h-12 w-full">
                <div
                  class="flex items-center justify-center rounded-2xl h-10 w-10" id="msgBox">
                  <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                    ></path>
                  </svg>
                </div>
                <div class="ml-2 font-bold text-2xl">Chat</div>
              </div>

              <!-- conversations -->
              <div class="flex flex-col mt-8">
                <div class="flex flex-row items-center justify-between text-xs">
                  <span class="font-bold">Conversations {{ $data["conversations"] }}</span>
                  <span
                    class="flex items-center justify-center bg-red text-white h-4 w-4 rounded-full"
                    >{{ count($data["conversations"]) }}</span
                  >
                </div>
                <div class="flex flex-col space-y-1 mt-4 -mx-2 h-48 overflow-y-auto">
                  @foreach($data["conversations"] as $convo)
                    @foreach($convo->conversation->participants as $participant)
                    @if($participant->messageable_type == 'App\Models\Vendor' && $participant->messageable->id != Auth::guard("vendor")->user()->id)
                    <button
                      id="{{ $convo->conversation->id }}" class="mx-1 flex flex-row items-center @if($data['conversations'][0]->conversation->id == $convo->conversation->id)bg-win-purple @endif hover:bg-gray-100 rounded-xl p-2 conversation-btn" value="{{ $convo->conversation->id }}"
                    >
                      <div
                        class="flex items-center justify-center h-8 w-8 bg-red rounded-full"
                      >
                        <img src="{{ asset('/storage/images/' . $participant->messageable->image) }}">
                      </div>
                      <div class="ml-2 text-sm font-semibold">{{ $participant->messageable->business_name }}</div>
                      <input name="convo-{{ $convo->conversation->id }}-vendor-id" value="{{ $participant->messageable->id }}" disabled hidden>
                    </button>
                    @endif
                    @endforeach
                  @endforeach
                </div>
              </div>
            </div>
            <!-- conversation body -->
            <div class="flex flex-col flex-auto min-h-full p-6">
              <div id="inviteSectionDisplay" class="absolute z-100"></div>
              <div
                class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full p-4"
              >
                <div class="flex flex-col h-full overflow-x-auto mb-4">
                  <div class="flex flex-col h-full">
                    <div id="loadingChat" class="min-h-60 flex flex-col bg-dark-grey-win border shadow-sm rounded-xl" hidden>
                      <div class="flex flex-auto flex-col justify-center items-center p-4 md:p-5">
                        <div class="flex justify-center">
                          <i class="fas fa-sync-alt fa-spin text-win-purple text-lg sm:text-xl"></i>
                        </div>
                      </div>
                    </div>
                    <ul id="conversationList" class="space-y-5">
                    </ul>
                  </div>
                </div>
                <div
                  class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4"
                >
                  <div class="flex grow ml-4">
                    <div class="relative w-full">
                      <input
                        id="sendMsgText" type="text"
                        class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" autocomplete="off"
                      />
                    </div>
                  </div>
                  <div class="ml-4">
                    <button
                      id="sendMessageButton" class="flex items-center justify-center bg-win-purple hover:bg-win-purple rounded-xl text-white px-4 py-1 flex-shrink-0"
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
        </div>
      @include('layouts.footer')
      <!-- end cards -->
    </main>
  </body>
  <script>
    var activeConversationID = {{ $data["conversations"][0]->conversation->id }};
    var currentVendorID = {{ Auth::guard("vendor")->user()->id }};
    function createPrimaryChatBubble(bodyText){
      let bubble = `
        <li class="max-w-lg ms-auto flex justify-end gap-x-2 sm:gap-x-4">
          <div class="grow text-end space-y-3">
            <div class="inline-block bg-win-purple rounded-2xl p-4 shadow-sm">
              <p class="text-white">
                ` + bodyText + `
              </p>
            </div>
          </div>
        </li>`;
        $("#conversationList").append(bubble);
    }
    function createSecondaryChatBubble(bodyText, sender){
      let bubble = "";
      if(bodyText == "{!invite}"){
        bubble = `
          <div id="conn-noti-` + sender.id + `" class="bg-white rounded-xl shadow-lg mb-2" role="alert">
            <div class="flex p-4">
              <div class="flex-shrink-0">
                <svg class="flex-shrink-0 size-4 text-blue mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </svg>
              </div>
              <div class="ms-3">
                <p class="text-sm text-gray-700">
                  <span class="font-bold">` + sender.first_name + ` ` + sender.last_name + `</span> would like you to join their community! 
                </p>
                <div class="mt-1">
                  <button value="` + sender.id + `" response="false" type="button" class="connection-btn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-gray-light text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    Decline
                  </button>
                  <button value="` + sender.id + `" response="true" type="button" class="connection-btn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                    Allow
                  </button>
                  <a type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white disabled:opacity-50 disabled:pointer-events-none" href="/vendor/profile/` + sender.id + `">
                    View Profile
                  </a>
                </div>
              </div>
            </div>
          </div>`;
        $("#inviteSectionDisplay").html(bubble);
        bubble = `
          <li class="max-w-lg flex gap-x-2 sm:gap-x-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-4 space-y-3">
              <div class="space-y-3">
                <p class="text-black">
                  ` + sender.first_name + ` invited you to their storefront!
                </p>
              </div>
            </div>
          </li>`;
      } else{
      bubble = `
        <li class="max-w-lg flex gap-x-2 sm:gap-x-4">
          <div class="bg-white border border-gray-200 rounded-2xl p-4 space-y-3">
            <div class="space-y-3">
              <p class="text-black">
                ` + bodyText + `
              </p>
            </div>
          </div>
        </li>`;
      }
      $("#conversationList").append(bubble);
    }
    function createVendorInvite(id){
      let bubble = `
        <div id="conn-noti-` + sender.id + `" class="bg-white rounded-xl shadow-lg mb-2" role="alert">
          <div class="flex p-4">
            <div class="flex-shrink-0">
              <svg class="flex-shrink-0 size-4 text-blue mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
              </svg>
            </div>
            <div class="ms-3">
              <p class="text-sm text-gray-700">
                <span class="font-bold">` + sender.first_name + ` ` + sender.last_name + `</span> would like you to join their community! 
              </p>
              <div class="mt-1">
                <button value="` + sender.id + `" response="false" type="button" class="connection-btn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-gray-light text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                  Decline
                </button>
                <button value="` + sender.id + `" response="true" type="button" class="connection-btn py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                  Allow
                </button>
                <a type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-pink-win text-white disabled:opacity-50 disabled:pointer-events-none" href="/vendor/profile/` + sender.id + `">
                  View Profile
                </a>
              </div>
            </div>
          </div>
        </div>`;
      $("#inviteSectionDisplay").html(bubble);
    }
    function getConvoMessages(convoID){
      $("#loadingChat").show();
      $("#inviteSectionDisplay").html("");
      $("#conversationList").hide();
        $.ajax({
            type: "GET",
            headers: {
            },
            url: "/vendor/messages/" + convoID,
            success: function (data) {
              let d = JSON.parse(data);
              console.log(d.data);
              $("#conversationList").html("");
              for(let i = 0; i < d.messages.data.length; ++i){
                if(d.messages.data[i].sender.id == currentVendorID){
                  createPrimaryChatBubble(d.messages.data[i].body);
                } else{
                  createSecondaryChatBubble(d.messages.data[i].body, d.messages.data[i].sender);
                }
              }
              $("#loadingChat").hide();
              $("#conversationList").show();
              $("#convo-" + activeConversationID +  "-vendor-id")
              let xH = document.getElementById("msgBox").scrollHeight; 
              document.getElementById("msgBox").scrollTop = xH;
            }
          });
    }
    getConvoMessages({{ $data["conversations"][0]->conversation->id }});
    $(".conversation-btn").on("click", (el) => {
      console.log("triggered convo " + el.currentTarget.val);
      let id = el.currentTarget.value;
      $("#" + el.currentTarget.id).toggleClass("bg-win-purple");
      $("#" + activeConversationID).toggleClass("bg-win-purple");
      activeConversationID = id;
      getConvoMessages(activeConversationID);
    });
    $("#sendMessageButton").on("click", () => {
      let msg = $("#sendMsgText").val();
      if(msg != ""){
        let formData = {
          message: msg,
          conversation: activeConversationID
        };
        $.ajax({
          type: "POST",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/vendor/send/message",
          data: formData,
          success: function (data) {
            console.log("sent message");
            createPrimaryChatBubble(msg);
            $("#sendMsgText").val("");
          }
        });
      }
    });
  </script>
  <script>
    $(document).on('click','.connection-btn', (el) => {
      console.log("triggered connection btn");
      console.log($(el.target).attr("response"));
      $(el.target).attr('disabled', true);
      let formData = {
        host_id: $(el.target).val(),
        connection_id: 0,
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
</html>
