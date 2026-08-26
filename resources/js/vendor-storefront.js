const VSF_MAIN_THUMB_CAP = 6;

function vsfImgUrl(name) {
  const base = (window.vsfPortfolio && window.vsfPortfolio.storageBaseUrl) || '';
  return base + '/' + encodeURIComponent(name);
}

function vsfDragAttrs(name) {
  const isOwn = window.vsfPortfolio && window.vsfPortfolio.isOwnStorefront;
  return isOwn ? ` draggable="true" data-image="${name}"` : '';
}

function vsfThumbHtml(name, itemClass) {
  const isOwn = window.vsfPortfolio && window.vsfPortfolio.isOwnStorefront;
  return `
    <div class="${itemClass}"${vsfDragAttrs(name)}>
      <img class="vsf-lightbox-trigger" src="${vsfImgUrl(name)}" alt="" loading="lazy" draggable="false" />
      ${isOwn ? `<button type="button" class="vsf-gallery__cover-btn set-cover-btn" value="${name}">Set as Cover</button>` : ''}
    </div>
  `;
}

// Recomputes the thumbnail row height so each thumb is exactly half the
// cover photo's rendered height, no matter how the flexible hero resizes
// across breakpoints.
function vsfUpdateThumbHeightVar() {
  const gallery = document.getElementById('vsfPortfolioGallery');
  const hero = gallery && gallery.querySelector('.vsf-gallery__hero');
  if (!gallery || !hero) return;
  const h = hero.getBoundingClientRect().height;
  if (h > 0) gallery.style.setProperty('--vsf-thumb-h', (h / 2) + 'px');
}

function vsfRenderPortfolioGallery(images) {
  const gallery = document.getElementById('vsfPortfolioGallery');
  if (!gallery || !window.vsfPortfolio) return;

  const cover = images[0];
  const thumbs = images.slice(1, 1 + VSF_MAIN_THUMB_CAP);

  const heroHtml = `
    <div class="vsf-gallery__hero vsf-gallery__hero--cover"${vsfDragAttrs(cover)}>
      <img class="vsf-lightbox-trigger" src="${vsfImgUrl(cover)}" alt="" loading="lazy" draggable="false" />
    </div>
  `;

  const thumbsHtml = thumbs.map((name) => vsfThumbHtml(name, 'vsf-gallery__thumb')).join('');

  gallery.innerHTML = heroHtml + `<div class="vsf-gallery__thumbs">${thumbsHtml}</div>`;
  window.vsfPortfolio.images = images;
  requestAnimationFrame(vsfUpdateThumbHeightVar);
}

function vsfRenderOverlayGrid(images) {
  const grid = document.getElementById('vsfOverlayGrid');
  if (!grid || !window.vsfPortfolio) return;
  grid.innerHTML = images.map((name) => vsfThumbHtml(name, 'vsf-overlay-item')).join('');
}

function vsfRenderAll(images) {
  vsfRenderPortfolioGallery(images);
  vsfRenderOverlayGrid(images);
}

// Shared by both the capped main gallery and the "View All" overlay grid —
// dragging in the capped view only ever reorders the visible photos, so any
// photos hidden past the cap are appended back in their existing relative
// order rather than silently dropped from the saved order.
function vsfBindGalleryDragEvents(containerEl, dragItemSelector) {
  if (!containerEl) return;
  let dragImage = null;

  const clearDropTargets = function () {
    containerEl.querySelectorAll('.vsf-gallery--drop-target').forEach(function (el) {
      el.classList.remove('vsf-gallery--drop-target');
    });
  };

  containerEl.addEventListener('dragstart', function (e) {
    const item = e.target.closest(dragItemSelector);
    if (!item || !item.dataset.image) return;
    dragImage = item.dataset.image;
    item.classList.add('vsf-gallery--dragging');
    e.dataTransfer.effectAllowed = 'move';
  });

  containerEl.addEventListener('dragend', function (e) {
    const item = e.target.closest(dragItemSelector);
    if (item) item.classList.remove('vsf-gallery--dragging');
    clearDropTargets();
    dragImage = null;
  });

  containerEl.addEventListener('dragover', function (e) {
    if (!dragImage) return;
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    const item = e.target.closest(dragItemSelector);
    clearDropTargets();
    if (item && item.dataset.image && item.dataset.image !== dragImage) {
      item.classList.add('vsf-gallery--drop-target');
    }
  });

  containerEl.addEventListener('drop', function (e) {
    e.preventDefault();
    const targetItem = e.target.closest(dragItemSelector);
    clearDropTargets();
    if (!dragImage || !targetItem || !targetItem.dataset.image || targetItem.dataset.image === dragImage) return;

    const items = Array.from(containerEl.querySelectorAll(dragItemSelector));
    const visibleOrder = items.map(function (el) { return el.dataset.image; });
    const fromIdx = visibleOrder.indexOf(dragImage);
    const toIdx = visibleOrder.indexOf(targetItem.dataset.image);
    if (fromIdx === -1 || toIdx === -1) return;

    visibleOrder.splice(fromIdx, 1);
    visibleOrder.splice(toIdx, 0, dragImage);

    const allImages = (window.vsfPortfolio && window.vsfPortfolio.images) || [];
    const visibleSet = new Set(visibleOrder);
    const hidden = allImages.filter(function (name) { return !visibleSet.has(name); });
    const order = visibleOrder.concat(hidden);

    $.ajax({
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      url: '/vendor/portfolio/reorder',
      data: { order: order },
      success: function () {
        vsfRenderAll(order);
      },
      error: function () {
        window.WinToast && window.WinToast.show('Could not save the new photo order, please try again.', 'error');
      },
    });
  });
}

$(document).ready(function () {
  $('.vsf-pill').on('click', function () {
    const tabId = $(this).data('vsf-tab');
    $('.vsf-pill').removeClass('vsf-pill--active').attr('aria-selected', 'false');
    $(this).addClass('vsf-pill--active').attr('aria-selected', 'true');
    $('.vsf-tab-panel').removeClass('is-active');
    $('#' + tabId).addClass('is-active');
  });

  if (window.vsfPortfolio) {
    vsfRenderOverlayGrid(window.vsfPortfolio.images);
    requestAnimationFrame(vsfUpdateThumbHeightVar);
    window.addEventListener('resize', vsfUpdateThumbHeightVar);
  }

  // Delegated (not bound once at load) since the gallery re-renders in place
  // after a reorder/cover change — new elements need these handlers too.
  // The full image list is read from window.vsfPortfolio (set for every
  // visitor, not just the owner) so the carousel works the same whether the
  // photo was clicked in the capped main gallery or the "View All" overlay.
  let vsfLightboxImages = [];
  let vsfLightboxIndex = 0;

  const vsfOpenLightboxAt = function (index) {
    if (!vsfLightboxImages.length) return;
    vsfLightboxIndex = (index + vsfLightboxImages.length) % vsfLightboxImages.length;
    $('#vsf-lightbox img').attr('src', vsfLightboxImages[vsfLightboxIndex]);
  };

  $(document).on('click', '.vsf-lightbox-trigger', function () {
    const images = (window.vsfPortfolio && window.vsfPortfolio.images) || [];
    vsfLightboxImages = images.map(vsfImgUrl);
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
        vsfRenderAll(images);
      },
      error: function () {
        window.WinToast && window.WinToast.show('Something went wrong, please try again.', 'error');
        $btn.prop('disabled', false);
      },
    });
  });

  // ——— Portfolio drag-to-reorder (vendor viewing their own storefront) ———
  // Dragging any photo onto the hero slot makes it the new cover; dragging
  // among the thumbnails reorders them. Works both in the capped main
  // gallery and the "View All" overlay grid. No button press required, and
  // no full page reload — both views re-render from the server's response
  // once the new order is saved.
  vsfBindGalleryDragEvents(document.getElementById('vsfPortfolioGallery'), '.vsf-gallery__hero, .vsf-gallery__thumb');
  vsfBindGalleryDragEvents(document.getElementById('vsfOverlayGrid'), '.vsf-overlay-item');
});
