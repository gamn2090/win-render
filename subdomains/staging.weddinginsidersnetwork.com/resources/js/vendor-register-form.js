$(document).ready(function () {
    const passwordToggle = document.querySelector('#show-password-toggle');

    passwordToggle.addEventListener('click', function () {
        const password = document.querySelector('#password');

        if (password.type === 'password') {
            password.type = 'text';
        } else {
            password.type = 'password';
        }

        password.focus();
    });

    const passwordCToggle = document.querySelector('#show-password-confirmation-toggle');

    passwordCToggle.addEventListener('click', function () {
        const passwordC = document.querySelector('#password_confirmation');

        if (passwordC.type === 'password') {
            passwordC.type = 'text';
        } else {
            passwordC.type = 'password';
        }

        passwordC.focus();
    });


    $("#uploadImageButton").on("click", () => {
        $("#imageUpload").trigger("click");
    });
    //let file = document.getElementById('imageUpload');
    /*file.onchange = function (e) {
        let ext = this.value.match(/\.([^\.]+)$/)[1].toLowerCase();
        switch (ext) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                $("#imageUploadName").html(e.target.files[0].name);
                $("#goodImageAlert").attr("hidden", false);
                let imagePreview = document.getElementById('profileImagePreview');
                imagePreview.src = URL.createObjectURL(event.target.files[0]);
                break;
            default:
                $("#badImageAlert").attr("hidden", false);
                this.value = '';
        }
    };*/



    function checkValid() {
        let isValid = true;
        $('input').filter('[required]').each(function () {
            if ($(this).val() === '') {
                console.log("Not valid: ");
                console.log($(this).attr('id'));
                $('#confirm').prop('disabled', true);
                isValid = false;
                return false;
            }
        });
        return isValid;
    }
    var page = 1;
    $(document).on("click", ".btn-next", function () {
        if (($("#password_first").val() != $("#password_confirmation").val()) || $("#password_first").val() == "") {
          Swal.fire({
            title: 'Oops!',
            text: `Your passwords didn't match. Please try again!`,
            icon: 'error',
            confirmButtonText: 'Retry',
            confirmButtonColor: '#6432C8',
          });
          $("#btn-back").trigger("click");
          $("#btn-back").trigger("click");
          console.log("passwords don't match");
          return;
        }
        else if($("#new-email").val() == "" || $("#new-email").val().indexOf('@') == -1 || $("#new-email").val().indexOf('.') == -1) {
          Swal.fire({
            title: 'Oops!',
            text: `Please enter a valid email address!`,
            icon: 'error',
            confirmButtonText: 'Retry',
            confirmButtonColor: '#6432C8',
          });
          $("#btn-back").trigger("click");
          return;
        }
        else if($("#hs-first-name").val() == "" || $("#hs-last-name").val() == "") {
          Swal.fire({
            title: 'Oops!',
            text: `Please fill out all required fields!`,
            icon: 'error',
            confirmButtonText: 'Retry',
            confirmButtonColor: '#6432C8',
          });
          $("#btn-back").trigger("click");
          return;
        }
        else {
            console.log("passwords match");
            $("#btn-next").trigger("click");
            page += 1;
            console.log("next page");
        }
    });

    $(".btn-back").on("click", function () {
        page -= 1;
        $("#btn-back").trigger("click");
    });
    $(document).on("keypress", function (e) {
        if (e.which === 13) {
            $("#btn-finish-setup").trigger("click");
            console.log('form submitted');
        }
    });
    $("#btn-finish-setup").on("click", function () {
        /*if(!checkValid()){
            Swal.fire({
              title: 'Oops!',
              text: `Please make sure all required fields are filled out and try again!`,
              icon: 'error',
              confirmButtonText: 'Retry'
            });
            $("#btn-back").trigger("click");
            $("#btn-back").trigger("click");
            return;
        }*/

        //let vt = [];
        //$("[id^=vt-]").each(function() {
        if ($(this).is(':checked')) {
            vt.push($(this).val());
        }
        //});
        //console.log(vt);

        if (($("#password_first").val() != $("#password_confirmation").val()) || $("#password_first").val() == "") {
            $("#btn-back").trigger("click");
            Swal.fire({
                title: 'Oops!',
                text: `Your passwords didn't match. Please try again!`,
                icon: 'error',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#6432C8',
            });
            page -= 1;
            console.log("pw don't match, going back");
            return;
        }

        if(!$("#hs-checkbox-tos").is(':checked') || !$("#hs-checkbox-eligibility").is(':checked')) {
            Swal.fire({
                title: 'Oops!',
                text: `Please read and accept our terms of service and vendor eligibility before continuing!`,
                icon: 'error',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#6432C8',
            });
            return;
        }

        function uploadImage() {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/user/upload/image",
                enctype: 'multipart/form-data',
                contentType: false,
                data: userData,
                processData: false,
                success: function (data) {
                    window.location = "/vendor/dashboard";
                }
            });
        }
        let ref_by = null;
        if($("#ref_by").length){
            ref_by = $("#ref_by").val()
        }
        
        $("#submit-spinner").css('display','inline');
        $("#btn-finish-setup").attr("disabled", true);
        let formData = {
            first_name: $("#hs-first-name").val(),
            last_name: $("#hs-last-name").val(),
            business_name: $("#business-name").val(),
            password: $("#password_first").val(),
            password_confirmation: $("#password_confirmation").val(),
            email: $("#new-email").val(),
            role: $("#role_select").val(),
            offered_discount: $('input[name="discount-val"]:checked').val(),
            avg_price: $("#avg-price").val(),
            location: $("#location").val(),
            bio: $("#user-bio").val(),
            captcha_response: turnstile.getResponse(),
            ref_by: ref_by,
            event: $("#event").val(),
        };

        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/vendor/register",
            data: formData,
            success: function (data) {
                console.log("registered user");
                window.location = "/vendor/dashboard";
                //uploadImage();
            },
            error: function (err) {
                page -= 1;
                $("#btn-back").trigger("click");
                let messages = JSON.parse(err.responseText)["errors"];
                console.log(typeof messages);
                $("#formErrors").html('');
                for (const [key, value] of Object.entries(messages)) {
                    $("#formErrors").append('<li class="px-2 py-2">' + value + "</li>");
                }
            }
        });
    });


    $('#avg-price').on('change', function () {
        let discountVal = "X";
        console.log($('#avg-price').val());
        switch ($('#avg-price').val()) {
            case "1":
                $('#discount-50').prop("checked", true);
                discountVal = 50;
                break;
            case "2":
                $('#discount-50').prop("checked", true);
                discountVal = 50;
                break;
            case "3":
                $('#discount-75').prop("checked", true);
                discountVal = 75;
                break;
            case "4":
                $('#discount-100').prop("checked", true);
                discountVal = 100;
                break;
            case "5":
                $('#discount-150').prop("checked", true);
                discountVal = 150;
                break;
            case "6":
                $('#discount-200').prop("checked", true);
                discountVal = 200;
                break;
            case "7":
                $('#discount-250').prop("checked", true);
                discountVal = 250;
                break;
            default:
                break;
        }
        Swal.fire({
            title: 'Nice!',
            text: `Based on your average booking value and what other vendors in your role are offering, we 
          recommend you offer a $` + discountVal + ` discount to our in-network clients.
            In network discounts encourage clients to stay in-network, 
            book more vendors, and provides vendors with merit badges to help with your on-site rankings.`,
            icon: 'success',
            confirmButtonText: 'Continue',
            confirmButtonColor: '#6432C8'
        });
        $('#offered_discount').val(discountVal);
    });

});