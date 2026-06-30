(function () {
  const sidebar = document.querySelector('.vendor-sidebar');
  const toggle = document.querySelector('.vendor-sidebar-toggle');
  const backdrop = document.querySelector('.vendor-sidebar-backdrop');

  if (!sidebar || !toggle) {
    return;
  }

  const mq = window.matchMedia('(max-width: 1023px)');

  function setOpen(open) {
    sidebar.classList.toggle('is-open', open);
    toggle.classList.toggle('is-active', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');

    if (backdrop) {
      backdrop.classList.toggle('is-visible', open);
      backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
    }

    document.body.classList.toggle('vendor-sidebar-open', open);
  }

  function close() {
    setOpen(false);
  }

  function open() {
    if (mq.matches) {
      setOpen(true);
    }
  }

  toggle.addEventListener('click', () => {
    setOpen(!sidebar.classList.contains('is-open'));
  });

  backdrop?.addEventListener('click', close);

  sidebar.querySelectorAll('.vendor-sidebar__link, .vendor-sidebar__logout').forEach((el) => {
    el.addEventListener('click', () => {
      if (mq.matches) {
        close();
      }
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
      close();
    }
  });

  mq.addEventListener('change', (e) => {
    if (!e.matches) {
      close();
    }
  });

  window.addEventListener('resize', () => {
    if (!mq.matches) {
      close();
    }
  });
})();
