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

});
