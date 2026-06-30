$( document ).ready(function() {
    var convoID = window.convoID;
    var userType = window.userType;
    var attachmentUploadUrl = window.attachmentUploadUrl;
    var pendingAttachment = null;

    function escapeHtml(value){
        return String(value === null || value === undefined ? "" : value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    }

    function humanFileSize(bytes){
        var n = Number(bytes) || 0;
        if(n < 1024) return n + " B";
        if(n < 1024 * 1024) return (n / 1024).toFixed(1) + " KB";
        return (n / (1024 * 1024)).toFixed(1) + " MB";
    }

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

    function createAttachmentBubble(opts){
        var isSender = opts.isSender ? 1 : 0;
        var body = escapeHtml(opts.body || "");
        var name = escapeHtml(opts.name || "document.pdf");
        var size = escapeHtml(humanFileSize(opts.size || 0));
        var url = opts.downloadUrl || "#";
        var attrs = url === "#" ? "" : (' href="' + url + '" target="_blank" rel="noopener"');
        var bubbleColor = isSender ? "bg-win-purple" : "bg-white border border-gray-200";
        var textColor = isSender ? "text-white" : "text-black";
        var subColor = isSender ? "text-white/80" : "text-gray-500";
        var alignWrap = isSender
            ? '<li class="max-w-lg xl:max-w-xl ms-auto flex justify-end gap-x-2 sm:gap-x-4 mx-4 text-wrap break-words">'
            : '<li class="max-w-lg xl:max-w-xl flex gap-x-2 sm:gap-x-4 mx-4 text-wrap break-words">';
        var iconColor = isSender ? "text-white" : "text-win-purple";
        var btnClasses = isSender
            ? "bg-white/15 hover:bg-white/25 text-white"
            : "bg-win-purple/10 hover:bg-win-purple/20 text-win-purple";

        $("#conversationList").append(
            alignWrap +
                '<div class="grow space-y-2 max-w-full ' + (isSender ? 'text-end' : 'text-start') + '">' +
                    '<div class="inline-block rounded-lg px-4 py-3 max-w-full text-left ' + bubbleColor + '">' +
                        (body ? '<p class="' + textColor + ' mb-2">' + body + '</p>' : '') +
                        '<div class="flex items-center gap-3">' +
                            '<svg class="w-7 h-7 ' + iconColor + ' flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">' +
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>' +
                            '</svg>' +
                            '<div class="flex flex-col items-start min-w-0">' +
                                '<span class="font-semibold ' + textColor + ' truncate max-w-[220px]">' + name + '</span>' +
                                '<span class="text-xs ' + subColor + '">PDF · ' + size + '</span>' +
                            '</div>' +
                            '<a' + attrs + ' class="ml-3 inline-flex items-center text-xs font-semibold rounded-full px-3 py-1 ' + btnClasses + '">Download</a>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</li>'
        );
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
                        case 'attachment':
                            createAttachmentBubble({
                                isSender: message.is_sender == 1,
                                body: message.body,
                                name: (message.data && message.data.attachment_name) ? message.data.attachment_name : "document.pdf",
                                size: (message.data && message.data.attachment_size) ? message.data.attachment_size : 0,
                                downloadUrl: (message.data && message.data.download_url) ? message.data.download_url : null
                            });
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

    function clearAttachment(){
        pendingAttachment = null;
        const $input = $("#attachmentInput");
        if($input.length){ $input.val(""); }
        $("#attachmentPreview").addClass("hidden").removeClass("flex");
        $("#attachmentPreviewName").text("");
        $("#attachmentPreviewSize").text("");
    }

    $("#attachmentBtn").on("click", () => {
        $("#attachmentInput").trigger("click");
    });

    $("#attachmentInput").on("change", (e) => {
        const file = e.target.files && e.target.files[0];
        if(!file) return;
        if(file.type !== "application/pdf" && !/\.pdf$/i.test(file.name)){
            Swal.fire({
                title: "Unsupported file type",
                text: "Only PDF files are allowed.",
                icon: "warning",
                confirmButtonColor: "#6432C8"
            });
            clearAttachment();
            return;
        }
        if(file.size > 10 * 1024 * 1024){
            Swal.fire({
                title: "File too large",
                text: "Maximum file size is 10 MB.",
                icon: "warning",
                confirmButtonColor: "#6432C8"
            });
            clearAttachment();
            return;
        }
        pendingAttachment = file;
        $("#attachmentPreviewName").text(file.name);
        $("#attachmentPreviewSize").text(humanFileSize(file.size));
        $("#attachmentPreview").removeClass("hidden").addClass("flex");
    });

    $("#attachmentRemoveBtn").on("click", () => {
        clearAttachment();
    });

    $("#sendMessageButton").on("click", () => {
        const msg = $("#sendMsgText").val();
        const $btn = $("#sendMessageButton");

        if(pendingAttachment){
            const fd = new FormData();
            fd.append("conversation", window.convoID);
            fd.append("file", pendingAttachment);
            if(msg && msg.trim() !== ""){ fd.append("message", msg); }

            $btn.prop("disabled", true).addClass("opacity-60");

            $.ajax({
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                url: attachmentUploadUrl,
                data: fd,
                processData: false,
                contentType: false,
                success: function (data) {
                    if(typeof data === "string"){
                        try { data = JSON.parse(data); } catch(e){ data = {}; }
                    }
                    const att = (data && data.attachment) || {};
                    createAttachmentBubble({
                        isSender: true,
                        body: msg && msg.trim() !== "" ? msg : "",
                        name: att.original_name,
                        size: pendingAttachment ? pendingAttachment.size : 0,
                        downloadUrl: att.download_url
                    });
                    $("#sendMsgText").val("");
                    clearAttachment();
                    $('#chatContent').scrollTop($('#conversationList').height());
                },
                error: function (xhr) {
                    let errMsg = "Could not send the file.";
                    if(xhr && xhr.responseJSON){
                        if(xhr.responseJSON.message){ errMsg = xhr.responseJSON.message; }
                        else if(xhr.responseJSON.errors){
                            const firstKey = Object.keys(xhr.responseJSON.errors)[0];
                            if(firstKey){ errMsg = xhr.responseJSON.errors[firstKey][0]; }
                        }
                    }
                    Swal.fire({
                        title: "Could not send",
                        text: errMsg,
                        icon: "error",
                        confirmButtonColor: "#6432C8"
                    });
                },
                complete: function () {
                    $btn.prop("disabled", false).removeClass("opacity-60");
                }
            });
            return;
        }

        if(msg != ""){
            const formData = {
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