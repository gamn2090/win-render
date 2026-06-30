$( document ).ready(function() {
    var convoID = window.convoID;
    var userType = window.userType;

    function createChatBubble(bodyText, isSender){
        if(isSender == 1){
            $("#conversationList").append(`
                <li class="max-w-lg xl:max-w-xl ms-auto flex justify-end gap-x-2 sm:gap-x-4 mx-4 text-wrap break-words">
                    <div class="grow text-end space-y-3 max-w-full">
                    <div class="inline-block bg-win-purple rounded-lg px-4 py-3 max-w-full">
                        <p class="text-white">
                            ${bodyText}
                        </p>
                    </div>
                    </div>
                </li>`);
        } else{
            $("#conversationList").append(`
            <li class="max-w-lg xl:max-w-xl flex gap-x-2 sm:gap-x-4 mx-4 text-wrap break-words">
                <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3 max-w-full">
                    <div class="space-y-3">
                        <p class="text-black">
                            ${bodyText}
                        </p>
                    </div>
                </div>
            </li>`);
        }
    }

    function createInquiryBubble(message, isSender){
        $("#conversationList").append(`
              <li class="bg-win-light p-4 rounded-lg text-center mx-2 lg:mx-4">
                <p class="subheading">${message.first_name}  <i class="fas fa-heart text-win-purple inline-flex"></i> ${message.fiance_first_name}</p>
                <p>are interested in your services for their wedding on:</p>
                <p class="font-semibold">${message.wedding_date}</p>
              </li>`);
    }

    function createConsultationRequestBubble(message, isSender){
        let date = new Date(message.meeting_date);
        let options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            timeZoneName: 'short',
            hour12: true
        };
        let timeString = date.toLocaleString('en-US', options);
        let bubble = 
            `<li class="bg-win-light p-4 rounded-lg text-center mx-2 lg:mx-4">
                <p class="subheading">${message.first_name}  <i class="fas fa-heart text-win-purple inline-flex"></i> ${message.fiance_first_name}</p>
                <p>are interested in your services! They would like to schedule a consultation on:</p>
                <p class="font-semibold">${timeString}</p>`;
        $("#conversationList").append(`${bubble}</li>`);
    }

    function getConvoMessages(){
        $("#loadingChat").toggleClass("hidden");
        //$("#inviteSectionDisplay").html("");
        $("#conversationList").hide();
        $.ajax({
            type: "GET",
            headers: {
            },
            url: `/${userType}/messages/${convoID}`,
            success: function (data) {
                let d = JSON.parse(data);
                convoID = d.convoID;
                $("#conversationList").html("");
                for(let i = 0; i < d.messages.data.length; ++i){
                    let message = d.messages.data[i];
                    switch (message.type){
                        case 'text':
                            createChatBubble(message.body, message.is_sender);
                            break;
                        case 'inquiry':
                            createInquiryBubble(message.data, message.is_sender);
                            break;
                        case 'consultation-request':
                            createConsultationRequestBubble(message.data, message.is_sender);
                            break;
                        default:
                            break;
                    }
                }
                $("#loadingChat").toggleClass("hidden");
                $("#conversationList").show();
                
                $('#chatContent').scrollTop($('#conversationList').height());
            }
        });
    }

    getConvoMessages();

    $("#sendMessageButton").on("click", () => {
        let msg = $("#sendMsgText").val();
        if(msg != ""){
        let formData = {
            message: msg,
            conversation: window.convoID
        };
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `/${userType}/send/message`,
            data: formData,
            success: function (data) {
                createChatBubble(msg, 1);
                $("#sendMsgText").val("");
            }
        });
        }
    });

    $(document).on("click",".consultation-response-btn", function (event) {
        let formData = {
          meeting_id: $(this).data("meeting-id"),
          answer: $(this).data("response")
        };
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/meeting/answer",
            data: formData,
            success: function (data) {
                if(formData.answer == 1){
                    Swal.fire({
                        title: 'Congratulations!',
                        text: "You have accepted a consultation with this client.",
                        icon:  'success',
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#6432C8'
                    });
                } else {
                    Swal.fire({
                        title: 'Consultation Declined',
                        text: "You have declined a consultation with this client. Please message them to provide an explanation and reschedule if necessary.",
                        icon:  'info',
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#6432C8'
                    });
                }
                $(event.target).parent().remove();
            }
        });
    });
});