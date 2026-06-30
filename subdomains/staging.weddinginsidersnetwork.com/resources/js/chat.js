$( document ).ready(function() {
    function createPrimaryChatBubble(bodyText){
        let bubble = `
        <li class="max-w-lg ms-auto flex justify-end gap-x-2 sm:gap-x-4 mx-4">
            <div class="grow text-end space-y-3">
            <div class="inline-block bg-win-purple rounded-lg px-4 py-3 shadow-sm">
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
            <li class="max-w-lg flex gap-x-2 sm:gap-x-4 mx-4">
                <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3 max-w-full">
                <div class="space-y-3">
                    <p class="text-black">
                    ` + sender.first_name + ` invited you to their storefront!
                    </p>
                </div>
                </div>
            </li>`;
        } else{
        bubble = `
            <li class="max-w-lg flex gap-x-2 sm:gap-x-4 mx-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3 max-w-full">
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

    function getConvoMessages(otherID, userType){
        $("#loadingChat").toggleClass("hidden");
        //$("#inviteSectionDisplay").html("");
        $("#conversationList").hide();
        $.ajax({
            type: "GET",
            headers: {
            },
            url: "/vendor/messages/" + userType + "/" + otherID,
            success: function (data) {
                let d = JSON.parse(data);
                convoID = d.convoID;
                $("#conversationList").html("");
                for(let i = 0; i < d.messages.data.length; ++i){
                    if(d.messages.data[i].is_sender == 1){
                        createPrimaryChatBubble(d.messages.data[i].body);
                    } else{
                        createSecondaryChatBubble(d.messages.data[i].body, d.messages.data[i].sender);
                    }
                }
                $("#loadingChat").toggleClass("hidden");
                $("#conversationList").show();
                
                $('#chatContent').scrollTop($('#conversationList').height());
            }
        });
    }

    let isChatboxOpen = false;
    var convoID = null;
    $(document).on("click",".chat-window-btn", function (event) {
        if(isChatboxOpen == false){
            let btn = event.currentTarget;
            let venID = $(event.currentTarget).data("uuid");
            let userType = $(event.currentTarget).data("user-type");
            console.log("loading messages from: " + venID);
            $("#chat-name").html($(btn).data("name"));
            $("#chat-image").attr("src", $(btn).data("picture-url"));
            getConvoMessages(venID, userType);
            $("#chat-window").fadeIn(250, () => {$("#chat-window").toggleClass("hidden")});
        } else{
            $("#chat-window").fadeOut(250, () => {$("#chat-window").toggleClass("hidden")});
        }
        isChatboxOpen = !isChatboxOpen;
    });

    $("#sendMessageButton").on("click", () => {
        let msg = $("#sendMsgText").val();
        if(msg != ""){
        let formData = {
            message: msg,
            conversation: convoID
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
});