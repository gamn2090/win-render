(function () {
  var STYLE_ID = 'win-toast-styles';

  if (!document.getElementById(STYLE_ID)) {
    var style = document.createElement('style');
    style.id = STYLE_ID;
    style.textContent = [
      '.win-toast-stack{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:min(380px, calc(100vw - 32px));}',
      '.win-toast{display:flex;align-items:flex-start;gap:10px;padding:14px 18px;border-radius:12px;background:#231f20;color:#fff;font-family:"Poppins","Figtree",sans-serif;font-size:14px;font-weight:500;line-height:1.4;box-shadow:0 12px 32px rgba(35,31,32,.18);animation:win-toast-in .25s ease-out;}',
      '.win-toast--success{background:#1a7f4b;}',
      '.win-toast--error{background:#dc2626;}',
      '.win-toast.is-leaving{animation:win-toast-out .2s ease-in forwards;}',
      '@keyframes win-toast-in{from{opacity:0;transform:translateX(24px);}to{opacity:1;transform:translateX(0);}}',
      '@keyframes win-toast-out{from{opacity:1;transform:translateX(0);}to{opacity:0;transform:translateX(24px);}}',
      '@media (max-width:640px){.win-toast-stack{left:16px;right:16px;top:12px;max-width:none;}}',
    ].join('\n');
    document.head.appendChild(style);
  }

  function ensureStack() {
    var stack = document.getElementById('win-toast-stack');
    if (!stack) {
      stack = document.createElement('div');
      stack.id = 'win-toast-stack';
      stack.className = 'win-toast-stack';
      document.body.appendChild(stack);
    }
    return stack;
  }

  function show(message, type, duration) {
    if (!message) {
      return;
    }
    type = type === 'error' ? 'error' : 'success';
    duration = duration || 4500;

    var stack = ensureStack();
    var toast = document.createElement('div');
    toast.className = 'win-toast win-toast--' + type;
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-live', 'polite');
    toast.textContent = message;
    stack.appendChild(toast);

    setTimeout(function () {
      toast.classList.add('is-leaving');
      toast.addEventListener('animationend', function () {
        toast.remove();
      });
    }, duration);
  }

  window.WinToast = { show: show };

  document.addEventListener('DOMContentLoaded', function () {
    if (window.winToastFlash && window.winToastFlash.message) {
      show(window.winToastFlash.message, window.winToastFlash.type);
    }
  });
})();
