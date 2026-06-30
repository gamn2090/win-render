$(document).ready(function() {
    //pw toggle
    const passwordToggle = document.querySelector('#show-client-register-password-toggle');

    passwordToggle.addEventListener('click', function() {
      const password = document.querySelector('#password_first');

      if (password.type === 'password') {
        password.type = 'text';
      } else {
        password.type = 'password';
      }

      password.focus();
    });

    const passwordCToggle = document.querySelector('#show-password-confirmation-toggle');

    passwordCToggle.addEventListener('click', function() {
      const passwordC = document.querySelector('#password_confirmation');

      if (passwordC.type === 'password') {
        passwordC.type = 'text';
      } else {
        passwordC.type = 'password';
      }

      passwordC.focus();
    });

    //profile image
    
    $("#uploadImageButton").on("click", () => {
        $("#imageUpload").trigger("click");
    });
    function checkValid() {
      let isValid = true;
      $('input').filter('[required]').each(function() {
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
    let page = 1;
    $(".btn-next").on("click", function() {
      page += 1;
      console.log("next page");
      $("#btn-next").trigger("click");
      if (($("#password_first").val() != $("#password_confirmation").val()) || $("#password_first").val() == "") {
        Swal.fire({
          title: 'Oops!',
          text: `Your passwords didn't match. Please try again!`,
          icon: 'error',
          confirmButtonText: 'Retry',
          confirmButtonColor: '#6432C8',
        });
        page -= 1;
        $("#btn-back").trigger("click");
        $("#btn-back").trigger("click");
        return;
      }
    });

    $(".btn-back").on("click", function() {
      page -= 1;
      $("#btn-back").trigger("click");
    });
    $("#btn-back").on("click", function() {
      page -= 1;
    });
    $(document).on("keypress", function(e) {
      if (e.which === 13) {
        $("#btn-finish-setup").trigger("click");
        console.log('form submitted');
      }
    });
    $("#btn-finish-setup").on("click", function() {
      if(!$("#hs-checkbox-tos").is(':checked')) {
          Swal.fire({
              title: 'Oops!',
              text: `Please read and accept our terms of service before continuing!`,
              icon: 'error',
              confirmButtonText: 'Retry',
              confirmButtonColor: '#6432C8',
          });
          return;
      }

      let vt = [];
      $("[id^=vt-]").each(function() {
        if ($(this).is(':checked')) {
          vt.push($(this).val());
        }
      });
      let questions = [];
      for (let n = 1; n < 5; ++n) {
        let answer = $("#q" + String(n)).val();
        questions.push(answer);
      }
      questions = JSON.stringify(questions);
      if (($("#password_first").val() != $("#password_confirmation").val()) || $("#password_first").val() == "") {
        Swal.fire({
          title: 'Oops!',
          text: `Your passwords didn't match. Please try again!`,
          icon: 'error',
          confirmButtonText: 'Retry',
          confirmButtonColor: '#6432C8',
        });
        page -= 1;
        $("#btn-back").trigger("click");
        $("#btn-back").trigger("click");
        return;
      }
      
      let ref_by = null;
      if($("#ref_by").length){
          ref_by = $("#ref_by").val()
      }
      //trigger the spinner
      $("#submit-spinner").css('display','inline');
      $("#btn-finish-setup").attr("disabled", true);

      function uploadImage(userData) {
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
          success: function(data) {
            window.location = "/dashboard";
          }
        });
      }
      if(window.refUser != null){
        let formData = {
          id: window.refUser.id,
          first_name: $("#hs-first-name").val(),
          last_name: $("#hs-last-name").val(),
          fiance_first_name: $("#hs-fiance-first-name").val(),
          fiance_last_name: $("#hs-fiance-last-name").val(),
          password: $("#password_first").val(),
          password_confirmation: $("#password_confirmation").val(),
          email: $("#new-email").val(),
          wedding_date: $("#client-wedding-date").val(),
          wedding_location: $("#client-venue").val(),
          can_request: +$("#hs-allow-contact").is(':checked'),
          vt: vt,
          bio: $("#user-bio").val(),
          booking_date: $("#booking_date").val(),
          questions: questions
        };
        let userData = new FormData();
        userData.append('image', $("#imageUpload").prop('files')[0]);
        $.ajax({
          type: "POST",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/ref/user/register",
          data: formData,
          success: function(data) {
            console.log("registered user");
            uploadImage(userData);
          },
          error: function(err){
            
            page -= 1;
            $("#btn-back").trigger("click");
            $("#btn-back").trigger("click");
            let messages = JSON.parse(err.responseText)["errors"];
            console.log(typeof messages);
            $("#formErrors").html('');
            for (const [key, value] of Object.entries(messages)) {
                $("#formErrors").append('<li class="px-2 py-2">' + value + "</li>");
            }
          }
        });
      }
      else {
        let formData = {
          first_name: $("#hs-first-name").val(),
          last_name: $("#hs-last-name").val(),
          fiance_first_name: $("#hs-fiance-first-name").val(),
          fiance_last_name: $("#hs-fiance-last-name").val(),
          password: $("#password_first").val(),
          password_confirmation: $("#password_confirmation").val(),
          email: $("#new-email").val(),
          wedding_date: $("#client-wedding-date").val(),
          wedding_location: $("#client-venue").val(),
          can_request: +$("#hs-allow-contact").is(':checked'),
          vt: vt,
          bio: $("#user-bio").val(),
          booking_date: $("#booking_date").val(),
          questions: questions,
          ref_by: ref_by,
          phone: $("#phone").val(),
          ref_source: $("#ref_source").val(),
          event: $("#event").val(),
        };
        $.ajax({
          type: "POST",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/user/register",
          data: formData,
          success: function(data) {
            console.log("registered user");
            window.location = "/dashboard";
          },
          error: function(err){
            
            page -= 1;
            $("#btn-back").trigger("click");
            $("#btn-back").trigger("click");
            let messages = JSON.parse(err.responseText)["errors"];
            console.log(typeof messages);
            $("#formErrors").html('');
            for (const [key, value] of Object.entries(messages)) {
                $("#formErrors").append('<li class="px-2 py-2">' + value + "</li>");
            }
          }
        });
      }
    });
    //if user obj exists from referral
    if(window.refUser != null){
        $("#hs-first-name").val(window.refUser.first_name);
        $("#hs-last-name").val(window.refUser.last_name);
        $("#hs-fiance-first-name").val(window.refUser.fiance_first_name)
        $("#hs-fiance-last-name").val(window.refUser.last_name);
        $("#client-venue").val(window.refUser.wedding_location);
        $("#client-wedding-date").val(window.refUser.wedding_date);
        $("#new-email").val(window.refUser.email);
    }

    //cropper
    
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
            const imgElement = document.getElementById('image');
            imgElement.src = e.target.result;
            showModal();
            };
            reader.readAsDataURL(file);
        }
    });

    let cropper;
    let originalFileName;

    function showModal() {
        const modal = document.getElementById('cropperModal');
        modal.style.display = 'block';
        const image = document.getElementById('image');
        originalFileName = document.getElementById('imageUpload').files[0].name;
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
        });
    }

    document.getElementById('cropButton').addEventListener('click', function() {
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 600,
        });
        canvas.toBlob(function(blob) {
            const fileInput = document.getElementById('imageUpload');
            const croppedFile = new File([blob], originalFileName, {
                type: "image/png",
                lastModified: Date.now()
            });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(croppedFile);
            fileInput.files = dataTransfer.files;

            hideModal();
            cropper.destroy();
            cropper = null;
            document.getElementById('profileImagePreview').src = URL.createObjectURL(croppedFile);

        }, 'image/png');
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        hideModal();
        cropper.destroy();
        cropper = null;
    });

    function hideModal() {
        const modal = document.getElementById('cropperModal');
        modal.style.display = 'none';
    }
    //flatpickr init
    $("#client-wedding-date").flatpickr({});
});