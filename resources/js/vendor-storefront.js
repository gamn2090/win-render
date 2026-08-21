function vsfRenderPortfolioGallery(images) {
  const gallery = document.getElementById('vsfPortfolioGallery');
  if (!gallery || !window.vsfPortfolio) return;

  const isOwn = window.vsfPortfolio.isOwnStorefront;
  const base = window.vsfPortfolio.storageBaseUrl;
  const imgUrl = (name) => base + '/' + encodeURIComponent(name);
  const dragAttrs = (name) => (isOwn ? ` draggable="true" data-image="${name}"` : '');

  const cover = images[0];
  const thumbs = images.slice(1);

  const heroHtml = `
    <div class="vsf-gallery__hero vsf-gallery__hero--cover"${dragAttrs(cover)}>
      <img class="vsf-lightbox-trigger" src="${imgUrl(cover)}" alt="" loading="lazy" draggable="false" />
    </div>
  `;

  const thumbsHtml = thumbs.map((name) => `
    <div class="vsf-gallery__thumb"${dragAttrs(name)}>
      <img class="vsf-lightbox-trigger" src="${imgUrl(name)}" alt="" loading="lazy" draggable="false" />
      ${isOwn ? `<button type="button" class="vsf-gallery__cover-btn set-cover-btn" value="${name}">Set as Cover</button>` : ''}
    </div>
  `).join('');

  gallery.innerHTML = heroHtml + `<div class="vsf-gallery__thumbs">${thumbsHtml}</div>`;
  window.vsfPortfolio.images = images;
}

$(document).ready(function () {
  $('.vsf-pill').on('click', function () {
    const tabId = $(this).data('vsf-tab');
    $('.vsf-pill').removeClass('vsf-pill--active').attr('aria-selected', 'false');
    $(this).addClass('vsf-pill--active').attr('aria-selected', 'true');
    $('.vsf-tab-panel').removeClass('is-active');
    $('#' + tabId).addClass('is-active');
  });

  // Delegated (not bound once at load) since the gallery re-renders in place
  // after a reorder/cover change — new elements need these handlers too.
  // The image list is read straight from the DOM at click time (not from
  // window.vsfPortfolio, which only exists for the owner) so the carousel
  // works for visitors browsing someone else's storefront too.
  let vsfLightboxImages = [];
  let vsfLightboxIndex = 0;

  const vsfOpenLightboxAt = function (index) {
    if (!vsfLightboxImages.length) return;
    vsfLightboxIndex = (index + vsfLightboxImages.length) % vsfLightboxImages.length;
    $('#vsf-lightbox img').attr('src', vsfLightboxImages[vsfLightboxIndex]);
  };

  $(document).on('click', '.vsf-lightbox-trigger', function () {
    vsfLightboxImages = $('#vsfPortfolioGallery .vsf-lightbox-trigger').map(function () {
      return $(this).attr('src');
    }).get();
    vsfOpenLightboxAt(vsfLightboxImages.indexOf($(this).attr('src')));
    $('#vsf-lightbox').addClass('is-open').attr('aria-hidden', 'false');
  });

  $(document).on('click', '#vsf-lightbox-prev', function (event) {
    event.stopPropagation();
    vsfOpenLightboxAt(vsfLightboxIndex - 1);
  });

  $(document).on('click', '#vsf-lightbox-next', function (event) {
    event.stopPropagation();
    vsfOpenLightboxAt(vsfLightboxIndex + 1);
  });

  $(document).on('keydown', function (event) {
    if (!$('#vsf-lightbox').hasClass('is-open')) return;
    if (event.key === 'ArrowLeft') vsfOpenLightboxAt(vsfLightboxIndex - 1);
    if (event.key === 'ArrowRight') vsfOpenLightboxAt(vsfLightboxIndex + 1);
    if (event.key === 'Escape') $('#vsf-lightbox').removeClass('is-open').attr('aria-hidden', 'true');
  });

  $('#vsf-lightbox, #vsf-lightbox-close').on('click', function (event) {
    if (event.target !== this && event.target.id !== 'vsf-lightbox-close') {
      return;
    }
    $('#vsf-lightbox').removeClass('is-open').attr('aria-hidden', 'true');
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

  $(document).on('click', '.set-cover-btn', function (event) {
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
        if (!window.vsfPortfolio) return;
        const images = window.vsfPortfolio.images.slice();
        const idx = images.indexOf(imageName);
        if (idx > -1) {
          images.splice(idx, 1);
          images.unshift(imageName);
        }
        vsfRenderPortfolioGallery(images);
      },
      error: function () {
        window.WinToast && window.WinToast.show('Something went wrong, please try again.', 'error');
        $btn.prop('disabled', false);
      },
    });
  });

  // ——— Portfolio drag-to-reorder (vendor viewing their own storefront) ———
  // Every photo (cover + all thumbnails) is shown directly in the gallery —
  // no separate "view all" step. Dragging any photo onto the hero slot makes
  // it the new cover; dragging among the thumbnails reorders them. No button
  // press required, and no full page reload — the section re-renders from
  // the server's response once the new order is saved.
  const gallery = document.getElementById('vsfPortfolioGallery');
  if (gallery) {
    let dragImage = null;
    const dragItemSelector = '.vsf-gallery__hero, .vsf-gallery__thumb';

    const clearDropTargets = function () {
      gallery.querySelectorAll('.vsf-gallery--drop-target').forEach(function (el) {
        el.classList.remove('vsf-gallery--drop-target');
      });
    };

    gallery.addEventListener('dragstart', function (e) {
      const item = e.target.closest(dragItemSelector);
      if (!item || !item.dataset.image) return;
      dragImage = item.dataset.image;
      item.classList.add('vsf-gallery--dragging');
      e.dataTransfer.effectAllowed = 'move';
    });

    gallery.addEventListener('dragend', function (e) {
      const item = e.target.closest(dragItemSelector);
      if (item) item.classList.remove('vsf-gallery--dragging');
      clearDropTargets();
      dragImage = null;
    });

    gallery.addEventListener('dragover', function (e) {
      if (!dragImage) return;
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
      const item = e.target.closest(dragItemSelector);
      clearDropTargets();
      if (item && item.dataset.image && item.dataset.image !== dragImage) {
        item.classList.add('vsf-gallery--drop-target');
      }
    });

    gallery.addEventListener('drop', function (e) {
      e.preventDefault();
      const targetItem = e.target.closest(dragItemSelector);
      clearDropTargets();
      if (!dragImage || !targetItem || !targetItem.dataset.image || targetItem.dataset.image === dragImage) return;

      const items = Array.from(gallery.querySelectorAll(dragItemSelector));
      const order = items.map(function (el) { return el.dataset.image; });
      const fromIdx = order.indexOf(dragImage);
      const toIdx = order.indexOf(targetItem.dataset.image);
      if (fromIdx === -1 || toIdx === -1) return;

      order.splice(fromIdx, 1);
      order.splice(toIdx, 0, dragImage);

      $.ajax({
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: '/vendor/portfolio/reorder',
        data: { order: order },
        success: function () {
          vsfRenderPortfolioGallery(order);
        },
        error: function () {
          window.WinToast && window.WinToast.show('Could not save the new photo order, please try again.', 'error');
        },
      });
    });
  }
});
