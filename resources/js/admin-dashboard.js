$(document).ready(function () {
  const csrfToken = $('meta[name="csrf-token"]').attr('content');

  // "Select all" checkbox toggles every (non-disabled) row checkbox in its table.
  $(document).on('change', '.admin-select-all', function () {
    const checked = $(this).prop('checked');
    $('#' + $(this).data('target')).find('.admin-row-checkbox:not(:disabled)').prop('checked', checked);
  });

  function selectedIds($table) {
    return $table.find('.admin-row-checkbox:checked').map(function () { return $(this).val(); }).get();
  }

  function deleteSelected(tableId, url, itemLabel, onSuccess) {
    const $table = $('#' + tableId);
    const ids = selectedIds($table);

    if (ids.length === 0) {
      Swal.fire({
        title: 'Nothing selected',
        text: `Select at least one ${itemLabel} first.`,
        icon: 'warning',
        confirmButtonColor: '#6432C8',
      });
      return;
    }

    Swal.fire({
      title: 'Are you absolutely sure?',
      text: `This will permanently delete ${ids.length} ${itemLabel}(s) and everything tied to their account — messages, bookings, profiles, everything. This cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete forever',
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6432C8',
    }).then(function (result) {
      if (!result.isConfirmed) return;

      $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        url: url,
        data: { ids: ids },
        success: function (data) {
          onSuccess(data);
        },
        error: function (xhr) {
          const msg = xhr.responseJSON?.message || 'Something went wrong, please try again.';
          Swal.fire({ title: 'Error', text: msg, icon: 'error', confirmButtonColor: '#6432C8' });
        },
      });
    });
  }

  $('#admin-delete-couples-btn').on('click', function () {
    deleteSelected('admin-couples-table', '/admin/couples/delete', 'couple', function (data) {
      Swal.fire({
        title: 'Deleted',
        text: data.message,
        icon: 'success',
        confirmButtonColor: '#6432C8',
      }).then(function () { window.location.reload(); });
    });
  });

  $('#admin-delete-vendors-btn').on('click', function () {
    deleteSelected('admin-vendors-table', '/admin/vendors/delete', 'vendor', function (data) {
      let text = `${data.deleted_count} vendor(s) deleted.`;
      if (data.blocked && data.blocked.length > 0) {
        text += ` The following vendor(s) were NOT deleted because they have an active subscription: ${data.blocked.join(', ')}.`;
      }
      Swal.fire({
        title: data.blocked && data.blocked.length > 0 ? 'Partially completed' : 'Deleted',
        text: text,
        icon: data.blocked && data.blocked.length > 0 ? 'warning' : 'success',
        confirmButtonColor: '#6432C8',
      }).then(function () { window.location.reload(); });
    });
  });

  $('#create-admin-form').on('submit', function (e) {
    e.preventDefault();
    const $form = $(this);
    $.ajax({
      type: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      url: '/admin/create',
      data: $form.serialize(),
      success: function (data) {
        $('#create-admin-modal-close-btn').trigger('click');
        $form.trigger('reset');
        Swal.fire({ title: 'Success!', text: data.message, icon: 'success', confirmButtonColor: '#6432C8' });
      },
      error: function (xhr) {
        const errors = xhr.responseJSON?.errors;
        const msg = errors ? Object.values(errors).flat().join(' ') : (xhr.responseJSON?.message || 'Could not create admin.');
        Swal.fire({ title: 'Error', text: msg, icon: 'error', confirmButtonColor: '#6432C8' });
      },
    });
  });

  $('#change-password-form').on('submit', function (e) {
    e.preventDefault();
    const $form = $(this);
    $.ajax({
      type: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      url: '/admin/password/update',
      data: $form.serialize(),
      success: function (data) {
        $('#change-password-modal-close-btn').trigger('click');
        $form.trigger('reset');
        Swal.fire({ title: 'Success!', text: data.message, icon: 'success', confirmButtonColor: '#6432C8' });
      },
      error: function (xhr) {
        const errors = xhr.responseJSON?.errors;
        const msg = errors ? Object.values(errors).flat().join(' ') : (xhr.responseJSON?.message || 'Could not update password.');
        Swal.fire({ title: 'Error', text: msg, icon: 'error', confirmButtonColor: '#6432C8' });
      },
    });
  });
});
