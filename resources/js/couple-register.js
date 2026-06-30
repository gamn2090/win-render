$(document).ready(function() {
    if (typeof flatpickr !== 'undefined' && document.getElementById('client-wedding-date')) {
        flatpickr('#client-wedding-date', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'm/d/Y',
            allowInput: true,
        });
    }

    //pw toggle
    const passwordToggle = document.querySelector('#show-client-register-password-toggle');
    if (passwordToggle) {
    passwordToggle.addEventListener('click', function() {
      const password = document.querySelector('#password_first');

      if (password.type === 'password') {
        password.type = 'text';
      } else {
        password.type = 'password';
      }

      password.focus();
    });
    }

    const passwordCToggle = document.querySelector('#show-password-confirmation-toggle');
    if (passwordCToggle) {
    passwordCToggle.addEventListener('click', function() {
      const passwordC = document.querySelector('#password_confirmation');

      if (passwordC.type === 'password') {
        passwordC.type = 'text';
      } else {
        passwordC.type = 'password';
      }

      passwordC.focus();
    });
    }

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

    function handleCoupleRegisterSuccess(data) {
        const res = typeof data === 'string' ? JSON.parse(data) : data;
        const redirect = res.redirect || (res.role === 'vendor' ? '/vendor/dashboard' : '/dashboard');
        window.location.href = redirect;
    }

    function showCoupleRegisterErrors(err) {
        page -= 1;
        $('#btn-back').trigger('click');
        $('#btn-back').trigger('click');
        $('#formErrors').html('');
        try {
            const payload = err.responseJSON || JSON.parse(err.responseText);
            const messages = payload.errors || { error: payload.message };
            for (const [, value] of Object.entries(messages)) {
                const text = Array.isArray(value) ? value.join(' ') : value;
                $('#formErrors').append('<li class="px-2 py-2">' + text + '</li>');
            }
        } catch (e) {
            $('#formErrors').append('<li class="px-2 py-2">Registration could not be completed. Please try again.</li>');
        }
    }

    function validateCoupleProfileStep() {
      const fields = [
        '#hs-first-name',
        '#hs-last-name',
        '#hs-fiance-first-name',
        '#hs-fiance-last-name',
        '#new-email',
      ];
      for (const sel of fields) {
        if (!$(sel).val().trim()) {
          Swal.fire({
            title: 'Oops!',
            text: 'Please complete all required fields to continue.',
            icon: 'error',
            confirmButtonText: 'Retry',
            confirmButtonColor: '#6432C8',
          });
          return false;
        }
      }
      const password = $('#password_first').val();
      const passwordConfirmation = $('#password_confirmation').val();
      if (!password || password !== passwordConfirmation) {
        Swal.fire({
          title: 'Oops!',
          text: 'Your passwords do not match or are empty. Please try again.',
          icon: 'error',
          confirmButtonText: 'Retry',
          confirmButtonColor: '#6432C8',
        });
        return false;
      }
      if (password.length < 8) {
        Swal.fire({
          title: 'Oops!',
          text: 'Your password must be at least 8 characters long.',
          icon: 'error',
          confirmButtonText: 'Retry',
          confirmButtonColor: '#6432C8',
        });
        return false;
      }
      return true;
    }

    $('.btn-couple-profile-next').on('click', function (e) {
      e.preventDefault();
      if (!validateCoupleProfileStep()) {
        return;
      }
      page = 2;
      $('#btn-next').trigger('click');
    });

    $(".btn-next").on("click", function() {
      if ($(this).hasClass('btn-couple-profile-next')) {
        return;
      }
      page += 1;
      console.log("next page");
      $("#btn-next").trigger("click");
    });

    $(".btn-back").on("click", function() {
      page -= 1;
      $("#btn-back").trigger("click");
    });
    $("#btn-back").on("click", function() {
      page -= 1;
    });

    $(document).on("keydown", function(e) {
      if (e.key !== "Enter" && e.which !== 13) {
        return;
      }
      if ($(e.target).is("textarea")) {
        return;
      }
      e.preventDefault();
      if (page === 1) {
        $(".btn-couple-profile-next").trigger("click");
        return;
      }
      if (page === 2) {
        $("#btn-finish-setup").trigger("click");
      }
    });

    $("#btn-finish-setup").on("click", function() {
      if (page !== 2) {
        return;
      }
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
          wedding_venue: $("#client-wedding-venue").val(),
          wedding_venue_location: $("#client-wedding-venue-location").val(),
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
          wedding_venue: $("#client-wedding-venue").val(),
          wedding_venue_location: $("#client-wedding-venue-location").val(),
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
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
          },
          url: "/user/register",
          data: formData,
          success: handleCoupleRegisterSuccess,
          error: showCoupleRegisterErrors,
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