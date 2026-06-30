document.addEventListener('DOMContentLoaded', () => {
  const pricingRoot = document.querySelector('[data-vep-pricing]');
  if (pricingRoot) {
    const hidden = pricingRoot.querySelector('input[type="hidden"]');
    const buttons = pricingRoot.querySelectorAll('[data-pricing-value]');

    buttons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const value = btn.getAttribute('data-pricing-value');
        if (hidden) hidden.value = value;
        buttons.forEach((b) => b.classList.remove('is-active'));
        btn.classList.add('is-active');
      });
    });
  }

  document.querySelectorAll('[data-copy-ref]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const text = btn.getAttribute('data-copy-ref') || '';
      try {
        await navigator.clipboard.writeText(text);
        const original = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(() => {
          btn.textContent = original;
        }, 2000);
      } catch {
        window.prompt('Copy this link:', text);
      }
    });
  });
});
