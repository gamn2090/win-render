$(document).ready(function() {
    $('#booking_val').on('change', function() {
        let discountVal = "X";
        console.log($('#booking_val').val());
        switch($('#booking_val').val()){
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
    //password view
    const passwordToggle = document.querySelector('#show-password-toggle');

    passwordToggle.addEventListener('click', function() {
        const password = document.querySelector('#password');

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
    //registration flow
    function triggerConfetti(){
        console.log("starting confetti");
        const end = Date.now() + 8 * 1000;

        const colors = ["#6432C8", "#5A7EFF", "#F85705", "#EDE9F5"];

        (function frame() {
        confetti({
            particleCount: 2,
            angle: 60,
            spread: 55,
            origin: { x: 0 },
            colors: colors,
        });

        confetti({
            particleCount: 2,
            angle: 120,
            spread: 55,
            origin: { x: 1 },
            colors: colors,
        });

        if (Date.now() < end) {
            requestAnimationFrame(frame);
        }
        })();
    }
    $("#register-btn").on("click", function () {
        $("#register-btn").attr("disabled", true);
        function uploadImage(imgData){
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/vendor/upload/image",
                enctype: 'multipart/form-data',
                contentType: false,
                data: imgData,
                processData: false,
                success: function (data) {
                    console.log("preregistered");
                }
            });
        }
        let formData = {
            first_name: $("#first_name").val(),
            last_name: $("#last_name").val(),
            business_name: $("#business_name").val(),
            password: $("#password").val(),
            password_confirmation: $("#password_confirmation").val(),
            email: $("#vendor_email").val(),
            role: $("#role_select").val(),
            offered_discount: $('input[name="discount-val"]:checked').val(),
            avg_price: $("#booking_val").val(),
            location: $("#location").val(),
            service_radius: $("#service_radius").val()
        };
        let imgData = new FormData();
        imgData.append('image', $("#imageUpload").prop('files')[0]);
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/vendor/register",
            data: formData,
            success: function (data) {
                uploadImage(imgData);
                triggerConfetti();
                $('html, body').animate({
                    scrollTop: $("#registrationSection").offset().top
                }, 100);
                $("#registrationFormSection").fadeOut("slow", function() {
                    $("#congratsSection").fadeIn("slow");
                });
            },
            error: function(err){
                let messages = JSON.parse(err.responseText)["errors"];
                $("#formErrors").html('');
                for (const [key, value] of Object.entries(messages)) {
                    $("#formErrors").append('<li class="px-2 py-2">' + value + "</li>");
                }
                $('html, body').animate({
                    scrollTop: $("#registrationSection").offset().top
                }, 100);
                $("#register-btn").attr("disabled", false);
            }
        });
    });
    $("#uploadImageButton").on("click", () => {
        $("#imageUpload").trigger("click");
    });
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
            const croppedFile = new File([blob], originalFileName, { type: "image/png", lastModified: Date.now() });
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
});