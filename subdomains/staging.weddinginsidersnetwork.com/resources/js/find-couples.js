$( document ).ready(function() {
    $('#filter-btn').on('click', function() {
        let wedding_date = $('#search-wedding-date').is(':checked');
        let url = '/vendor/couples?wedding_date=' + wedding_date;
        window.location.href = url;
    });
    $('#join-event-btn').on('click', function() {
        let formData = {
          event: $("#event-code").val(),
        };
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/vendor/event/join",
            data: formData,
            success: function (data) {
                Swal.fire({
                    title: 'Congratulations!',
                    text: "You have joined this event!",
                    icon:  'success',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#6432C8'
                }).then((result) => { 
                    let url = '/vendor/couples';
                    window.location.href = url;
                });
            },
            error: function (data) {
                Swal.fire({
                    title: 'Oops!',
                    text: "We couldn't find this event. Make sure you have the right join code!",
                    icon:  'error',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#6432C8'
                });
            }
        });
    });
});