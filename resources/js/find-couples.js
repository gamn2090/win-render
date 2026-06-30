$(document).ready(function () {
  let pendingClientUuid = null;

  function applyFilters() {
    const weddingDate = $('#search-wedding-date').is(':checked');
    window.location.href = '/vendor/couples?wedding_date=' + weddingDate;
  }

  function openOverlay(selector) {
    const el = document.querySelector(selector);
    if (el && window.HSOverlay) {
      window.HSOverlay.open(el);
    }
  }

  function closeOverlay(selector) {
    const el = document.querySelector(selector);
    if (el && window.HSOverlay) {
      window.HSOverlay.close(el);
    }
  }

  function contactClient(clientUuid) {
    $.ajax({
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      url: '/vendor/message/client',
      data: { client_uuid: clientUuid },
      success: function (data) {
        if (!data.status) {
          Swal.fire({
            title: 'Oops!',
            text: data.message,
            icon: 'error',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#6432C8',
          });
          return;
        }

        window.location = '/inbox/conversation/' + data.c_id;
      },
    });
  }

  $('#filter-btn').on('click', applyFilters);

  $('#fc-clear-filters').on('click', function () {
    $('#search-wedding-date').prop('checked', true);
    window.location.href = '/vendor/couples';
  });

  $('body').on('click', '.inquireClientButton', function (event) {
    event.preventDefault();
    pendingClientUuid = $(this).data('client-uuid');
    openOverlay('#fc-contact-confirm-modal');
  });

  $('#fc-contact-confirm-btn').on('click', function () {
    if (!pendingClientUuid) {
      return;
    }

    const clientUuid = pendingClientUuid;
    pendingClientUuid = null;
    closeOverlay('#fc-contact-confirm-modal');
    contactClient(clientUuid);
  });

  $('#join-event-btn').on('click', function () {
    const formData = {
      event: $('#event-code').val(),
    };

    $.ajax({
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      url: '/vendor/event/join',
      data: formData,
      success: function () {
        closeOverlay('#join-event-modal');
        Swal.fire({
          title: 'Congratulations!',
          text: 'You have joined this event!',
          icon: 'success',
          confirmButtonText: 'Ok',
          confirmButtonColor: '#6432C8',
        }).then(function () {
          window.location.href = '/vendor/couples';
        });
      },
      error: function () {
        Swal.fire({
          title: 'Oops!',
          text: "We couldn't find this event. Make sure you have the right join code!",
          icon: 'error',
          confirmButtonText: 'Ok',
          confirmButtonColor: '#6432C8',
        });
      },
    });
  });

  $('#event-code').on('keydown', function (event) {
    if (event.key === 'Enter') {
      event.preventDefault();
      $('#join-event-btn').trigger('click');
    }
  });
});
