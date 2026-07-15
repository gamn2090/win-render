$(document).ready(function () {
  $('.vsf-pill').on('click', function () {
    const tabId = $(this).data('vsf-tab');
    $('.vsf-pill').removeClass('vsf-pill--active').attr('aria-selected', 'false');
    $(this).addClass('vsf-pill--active').attr('aria-selected', 'true');
    $('.vsf-tab-panel').removeClass('is-active');
    $('#' + tabId).addClass('is-active');
  });

  $('.vsf-lightbox-trigger').on('click', function () {
    const src = $(this).attr('src');
    $('#vsf-lightbox img').attr('src', src);
    $('#vsf-lightbox').addClass('is-open').attr('aria-hidden', 'false');
  });

  $('#vsf-lightbox, #vsf-lightbox-close').on('click', function (event) {
    if (event.target !== this && event.target.id !== 'vsf-lightbox-close') {
      return;
    }
    $('#vsf-lightbox').removeClass('is-open').attr('aria-hidden', 'true');
  });

  $('#viewAllPortfolioImages').on('click', function () {
    $('#vsf-lightbox-all').addClass('is-open').attr('aria-hidden', 'false');
    $('html, body').css('overflow', 'hidden');
  });

  $('#vsf-lightbox-all-close, #vsf-lightbox-all').on('click', function (event) {
    if (event.target !== this && event.target.id !== 'vsf-lightbox-all-close') {
      return;
    }
    $('#vsf-lightbox-all').removeClass('is-open').attr('aria-hidden', 'true');
    $('html, body').css('overflow', 'auto');
  });

  $('[data-vsf-review-text]').each(function () {
    const $text = $(this);
    const $toggle = $text.siblings('[data-vsf-review-toggle]');
    if (this.scrollHeight > this.clientHeight + 2) {
      $toggle.prop('hidden', false);
    }
  });

  $('[data-vsf-review-toggle]').on('click', function () {
    const $text = $(this).siblings('[data-vsf-review-text]');
    const expanded = $text.toggleClass('is-clamped').hasClass('is-clamped');
    $(this).html(expanded ? 'Show more <span aria-hidden="true">⌄</span>' : 'Show less <span aria-hidden="true">⌃</span>');
  });

  $('#connectBtn').on('click', function () {
    $('#connectBtn').attr('disabled', true);
    $.ajax({
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      url: '/vendor/request/connection',
      data: { aff_id: window.vendorID },
      success: function (data) {
        if (data.status === false) {
          window.WinToast && window.WinToast.show(data.msg || 'Something went wrong, please try again.', 'error');
          $('#connectBtn').attr('disabled', false);
        } else {
          window.WinToast && window.WinToast.show('Vendor ' + (window.vendorName || '') + ' added to your preferred.', 'success');
          $('#connectBtn').html('<span class="vsf-profile__cta-icon" aria-hidden="true">✓</span> Connected');
        }
      },
      error: function () {
        window.WinToast && window.WinToast.show('Something went wrong, please try again.', 'error');
        $('#connectBtn').attr('disabled', false);
      },
    });
  });

  $('.set-cover-btn').on('click', function (event) {
    event.stopPropagation();
    const $btn = $(this);
    const imageName = $btn.val();
    $btn.prop('disabled', true);
    $.ajax({
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      url: '/vendor/portfolio/cover',
      data: { image_name: imageName },
      success: function () {
        window.location.reload();
      },
      error: function () {
        window.WinToast && window.WinToast.show('Something went wrong, please try again.', 'error');
        $btn.prop('disabled', false);
      },
    });
  });

  $('#messageVendorButton').on('click', function () {
    const url = $(this).data('message-url');
    const prefix = $(this).data('conversation-prefix');
    if (!url) {
      return;
    }
    $.ajax({
      type: 'GET',
      url: url,
      success: function (data) {
        window.location = prefix + data;
      },
    });
  });
});
