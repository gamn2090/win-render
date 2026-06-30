document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-vi-accordion-toggle]');
  const panel = document.querySelector('[data-vi-accordion-panel]');
  const section = document.querySelector('.vi-how');

  if (!toggle || !panel) {
    return;
  }

  toggle.addEventListener('click', () => {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    panel.hidden = isOpen;
    section?.classList.toggle('vi-how--open', !isOpen);
  });
});
