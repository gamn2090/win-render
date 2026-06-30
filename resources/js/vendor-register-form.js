$(document).ready(function () {
    const passwordToggle = document.querySelector('#show-password-toggle');
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function () {
            const password = document.querySelector('#password_first');
            if (!password) return;
            password.type = password.type === 'password' ? 'text' : 'password';
            password.focus();
        });
    }

    const passwordCToggle = document.querySelector('#show-password-confirmation-toggle');
    if (passwordCToggle) {
        passwordCToggle.addEventListener('click', function () {
            const passwordC = document.querySelector('#password_confirmation');
            if (!passwordC) return;
            passwordC.type = passwordC.type === 'password' ? 'text' : 'password';
            passwordC.focus();
        });
    }

    $("#uploadImageButton").on("click", () => {
        $("#imageUpload").trigger("click");
    });

    function syncOfferedDiscountFromRadios() {
        const checked = $('input[name="discount-val"]:checked').val();
        if (checked !== undefined && checked !== '') {
            $('#offered_discount').val(checked);
        }
    }

    syncOfferedDiscountFromRadios();
    $('input[name="discount-val"]').on('change', syncOfferedDiscountFromRadios);

    function getOfferedDiscount() {
        syncOfferedDiscountFromRadios();
        const checked = $('input[name="discount-val"]:checked').val();
        if (checked !== undefined && checked !== '') {
            return checked;
        }
        const hidden = $('#offered_discount').val();
        return hidden !== undefined && hidden !== '' ? hidden : null;
    }

    function showFormErrors(messages) {
        $("#formErrors").html('');
        if (!messages) {
            return;
        }
        for (const [, value] of Object.entries(messages)) {
            const text = Array.isArray(value) ? value.join(' ') : String(value);
            $("#formErrors").append('<li class="px-2 py-2">' + text + '</li>');
        }
    }

    function validateAccountStep() {
        const firstName = $('#hs-first-name').val().trim();
        const lastName = $('#hs-last-name').val().trim();
        const email = $('#new-email').val().trim();
        const password = $('#password_first').val();
        const passwordConfirmation = $('#password_confirmation').val();

        if (!firstName || !lastName || !email) {
            Swal.fire({
                title: 'Oops!',
                text: 'Please enter your name and email address to continue.',
                icon: 'error',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#6432C8',
            });
            return false;
        }

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

    function firstValidationMessage(messages) {
        if (!messages) {
            return null;
        }
        if (typeof messages === 'string') {
            return messages;
        }
        for (const value of Object.values(messages)) {
            if (Array.isArray(value) && value.length) {
                return value[0];
            }
            if (typeof value === 'string' && value) {
                return value;
            }
        }
        return null;
    }

    function validateBusinessStep() {
        const businessName = $('#business-name').val().trim();
        const location = $('#location').val().trim();
        const role = $('#role_select').val();
        const offeredDiscount = getOfferedDiscount();

        if (!businessName || !location) {
            Swal.fire({
                title: 'Oops!',
                text: 'Please enter your business name and location.',
                icon: 'error',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#6432C8',
            });
            return false;
        }

        if (!role) {
            Swal.fire({
                title: 'Oops!',
                text: 'Please select your service type (role).',
                icon: 'error',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#6432C8',
            });
            return false;
        }

        if (offeredDiscount === null) {
            Swal.fire({
                title: 'Oops!',
                text: 'Please select a Preferred Pricing amount.',
                icon: 'error',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#6432C8',
            });
            return false;
        }

        if (!$('#hs-checkbox-tos').is(':checked') || !$('#hs-checkbox-eligibility').is(':checked')) {
            Swal.fire({
                title: 'Oops!',
                text: 'Please read and accept our Terms of Service and vendor eligibility guidelines before continuing.',
                icon: 'error',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#6432C8',
            });
            return false;
        }

        return true;
    }

    let page = 1;

    $('.btn-vendor-account-next').on('click', function () {
        if (!validateAccountStep()) {
            return;
        }
        page = 2;
        $('#btn-next').trigger('click');
    });

    $('.btn-back').on('click', function () {
        page = 1;
        $('#btn-back').trigger('click');
    });

    $(document).on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            if (page === 1) {
                $('.btn-vendor-account-next').trigger('click');
            } else {
                $('#btn-finish-setup').trigger('click');
            }
        }
    });

    $('#btn-finish-setup').on('click', function () {
        if (!validateAccountStep()) {
            page = 1;
            $('#btn-back').trigger('click');
            return;
        }

        if (!validateBusinessStep()) {
            return;
        }

        let ref_by = null;
        if ($('#ref_by').length) {
            ref_by = $('#ref_by').val();
        }

        const offeredDiscount = getOfferedDiscount();

        $('#submit-spinner').css('display', 'inline');
        $('#btn-finish-setup').attr('disabled', true);
        $('#formErrors').html('');

        const avgPriceVal = $('#avg-price').val();
        const formData = {
            first_name: $('#hs-first-name').val().trim(),
            last_name: $('#hs-last-name').val().trim(),
            business_name: $('#business-name').val().trim(),
            password: $('#password_first').val(),
            password_confirmation: $('#password_confirmation').val(),
            email: $('#new-email').val().trim().toLowerCase(),
            role: $('#role_select').val(),
            offered_discount: offeredDiscount,
            location: $('#location').val().trim(),
            bio: $('#user-bio').val(),
            captcha_response: '',
        };
        if (avgPriceVal) {
            formData.avg_price = avgPriceVal;
        }
        if (ref_by) {
            formData.ref_by = ref_by;
        }
        const eventVal = $('#event').val();
        if (eventVal) {
            formData.event = eventVal;
        }

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                Accept: 'application/json',
            },
            url: '/vendor/register',
            data: formData,
            success: function (data) {
                const redirect = data.redirect || '/vendor/dashboard';
                window.location.href = redirect;
            },
            error: function (err) {
                $('#submit-spinner').css('display', 'none');
                $('#btn-finish-setup').attr('disabled', false);

                let messages = null;
                try {
                    const payload = err.responseJSON || JSON.parse(err.responseText);
                    messages = payload.errors || payload.message;
                } catch (e) {
                    messages = { error: 'Registration could not be completed. Please try again.' };
                }

                if (typeof messages === 'string') {
                    messages = { error: messages };
                }

                showFormErrors(messages);

                const summary = firstValidationMessage(messages);
                if (summary) {
                    Swal.fire({
                        title: 'Oops!',
                        text: summary,
                        icon: 'error',
                        confirmButtonText: 'Retry',
                        confirmButtonColor: '#6432C8',
                    });
                }
            },
        });
    });

    $('#avg-price').on('change', function () {
        let discountVal = 'X';
        switch ($('#avg-price').val()) {
            case '1':
                $('#discount-50').prop('checked', true);
                discountVal = 50;
                break;
            case '2':
                $('#discount-50').prop('checked', true);
                discountVal = 50;
                break;
            case '3':
                $('#discount-75').prop('checked', true);
                discountVal = 75;
                break;
            case '4':
                $('#discount-100').prop('checked', true);
                discountVal = 100;
                break;
            case '5':
                $('#discount-150').prop('checked', true);
                discountVal = 150;
                break;
            case '6':
                $('#discount-200').prop('checked', true);
                discountVal = 200;
                break;
            case '7':
                $('#discount-250').prop('checked', true);
                discountVal = 250;
                break;
            default:
                break;
        }
        syncOfferedDiscountFromRadios();
        Swal.fire({
            title: 'Nice!',
            text:
                'Based on your average booking value and what other vendors in your role are offering, we recommend you offer a $' +
                discountVal +
                ' discount to our in-network clients. In network discounts encourage clients to stay in-network, book more vendors, and provides vendors with merit badges to help with your on-site rankings.',
            icon: 'success',
            confirmButtonText: 'Continue',
            confirmButtonColor: '#6432C8',
        });
    });
});
