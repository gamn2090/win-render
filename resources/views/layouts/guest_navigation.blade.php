<header class="flex flex-wrap sm:justify-center sm:flex-nowrap w-full bg-win-light text-sm py-4 border-b-2 border-win-purple sm:border-none">
  <nav class="px-4 flex flex-wrap items-center justify-between max-sm:w-full" aria-label="Global">
    <button type="button" class="sm:hidden hs-collapse-toggle p-2.5 inline-flex justify-center items-center gap-x-2 rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none" data-hs-collapse="#navbar-alignment" aria-controls="navbar-alignment" aria-label="Toggle navigation">
      <svg class="hs-collapse-open:hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" x2="21" y1="6" y2="6" />
        <line x1="3" x2="21" y1="12" y2="12" />
        <line x1="3" x2="21" y1="18" y2="18" />
      </svg>
      <svg class="hs-collapse-open:block hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 6 6 18" />
        <path d="m6 6 12 12" />
      </svg>
    </button>
    <button class="sm:hidden py-2 px-10 ml-auto text-right inline-flex items-center gap-x-2 font-medium rounded-lg bg-win-orange text-white" data-hs-overlay="#modal-signin">
      Login
    </button>
    <div id="navbar-alignment" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2">
      <div class="flex flex-col mx-auto gap-5 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5 md:gap-16 text-win-charcoal button-text font-semibold">
        <a class="hover:text-gray-400" href="/">Home</a>
        <a class="hover:text-gray-400" href="/about">About</a>
        <a class="hover:text-gray-400" href="/for-couples">For Couples</a>
        <a class="hover:text-gray-400" href="/vendor/register">For Vendors</a>
        <a class="text-xl font-semibold text-white py-6 order-first sm:order-none" href="/"><img src="/assets/img/WIN-Primary-Logo-PURPLE.png" class="w-[10vh]"></a>

        <a class="hover:text-gray-400" href="/search">Search Vendors</a>
        <a class="hover:text-gray-400 hidden" href="/blog">Blog</a>
        <a class="hover:text-gray-400 sm:hidden" href="/vendor/register/form">Join as a Vendor</a>
        <a class="hover:text-gray-400 sm:hidden" href="/user/register">Join as a Couple</a>
        <div class="hs-dropdown [--strategy:fixed] lg:[--trigger:hover] inline-flex relative z-50 hidden sm:block">
          <button id="hs-dropdown-hover-event" type="button" class="hs-dropdown-toggle inline-flex items-center" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
            Join Us</span>
          </button>
          <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-hover-event">
            <div class="p-1 space-y-0.5">
              <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-win-lavender focus:outline-none focus:bg-win-lavender" href="/vendor/register/form">
                Join as a Vendor
              </a>
              <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-win-lavender focus:outline-none focus:bg-win-lavender" href="/user/register">
                Join as a Couple
              </a>
            </div>
          </div>
        </div>
        <div>
          <button type="button" data-hs-overlay="#modal-signin" class="hidden sm:block py-2 px-10 m-1 md:m-0 inline-flex items-center gap-x-2 font-medium rounded-lg bg-win-orange text-white disabled:opacity-50 disabled:pointer-events-none">
            Login
          </button>
        </div>
      </div>
    </div>
  </nav>
</header>
<style>
  #modal-signin .login-shell {
    background: #FCF6F3;
    border-radius: 24px;
    font-family: "Poppins", sans-serif;
  }

  #modal-signin .login-top {
    position: relative;
    background: #D5C6E7;
    border-radius: 24px 24px 0 0;
    padding: 12px 20px 10px;
    text-align: center;
  }

  #modal-signin .login-top h2 {
    color: #F85705;
    font-size: 24px;
    font-weight: 600;
    line-height: 1.2;
    margin: 0;
  }

  #modal-signin .login-top p {
    color: #F85705;
    font-size: 16px;
    font-weight: 400;
    margin: 2px 0 0;
    line-height: 1.35;
  }

  #modal-signin .close-login-btn {
    position: absolute;
    right: 10px;
    top: 10px;
    width: 24px;
    height: 24px;
    border: 0;
    background: transparent;
    padding: 0;
    cursor: pointer;
  }

  #modal-signin .join-box {
    background: linear-gradient(180deg, #F0EBF9 0%, #FEF0E6 100%);
    border-radius: 12px;
    padding: 14px 12px 12px;
    text-align: center;
  }

  #modal-signin .join-caption {
    color: #111111;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 10px;
  }

  #modal-signin .join-buttons {
    display: flex;
    gap: 12px;
  }

  #modal-signin .join-button,
  #modal-signin .signin-btn,
  #modal-signin .google-btn {
    position: relative;
    overflow: hidden;
    isolation: isolate;
    transition:
      background-color 0.4s ease,
      border-color 0.4s ease,
      box-shadow 0.4s ease,
      color 0.4s ease;
  }

  #modal-signin .join-button::before,
  #modal-signin .signin-btn::before,
  #modal-signin .google-btn::before {
    content: "";
    position: absolute;
    inset: -2px;
    background: linear-gradient(
      105deg,
      transparent 0%,
      transparent 38%,
      rgba(255, 255, 255, 0.5) 50%,
      transparent 62%,
      transparent 100%
    );
    transform: translateX(-130%) skewX(-10deg);
    transition: transform 0.65s ease;
    pointer-events: none;
    z-index: 1;
    mix-blend-mode: soft-light;
  }

  #modal-signin .join-button span,
  #modal-signin .signin-btn span,
  #modal-signin .google-btn span,
  #modal-signin .google-btn img {
    position: relative;
    z-index: 2;
  }

  #modal-signin .join-button:hover::before,
  #modal-signin .signin-btn:hover::before,
  #modal-signin .google-btn:hover::before {
    transform: translateX(130%) skewX(-10deg);
  }

  #modal-signin .join-button {
    flex: 1;
    background: #FB962F;
    color: #FCF6F3;
    border-radius: 800px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    padding: 9px 8px;
    text-align: center;
    z-index: 0;
  }

  #modal-signin .join-button:hover {
    background: #FCA85A;
    box-shadow:
      0 4px 12px rgba(251, 150, 47, 0.28),
      inset 0 1px 0 rgba(255, 255, 255, 0.45);
  }

  #modal-signin .field-label {
    color: #B0ABBC;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.08em;
    margin-bottom: 6px;
    text-transform: uppercase;
  }

  #modal-signin .input-shell {
    position: relative;
  }

  #modal-signin .input-shell input {
    width: 100%;
    height: 38px;
    border-radius: 0;
    border: none;
    background: #FFFFFF;
    color: #000000;
    font-size: 14px;
    padding: 8px 12px;
    outline: none;
  }

  #modal-signin .input-shell input::placeholder {
    color: #B0ABBC;
    font-size: 12px;
    font-weight: 500;
  }

  #modal-signin .show-password-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: #5A7EFF;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    padding: 0;
  }

  #modal-signin .forgot-link {
    text-align: right;
    margin-top: 10px;
  }

  #modal-signin .forgot-link a {
    color: #6432C8;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
  }

  #modal-signin .signin-btn {
    width: 100%;
    border: 0;
    border-radius: 80px;
    background: #6432C8;
    color: #FCF6F3;
    font-size: 16px;
    font-weight: 500;
    line-height: 1;
    padding: 14px 12px;
    margin-top: 10px;
    cursor: pointer;
    z-index: 0;
  }

  #modal-signin .signin-btn:hover {
    background: #7244D6;
    box-shadow:
      0 4px 14px rgba(100, 50, 200, 0.3),
      inset 0 1px 0 rgba(255, 255, 255, 0.35);
  }

  #modal-signin .or-label {
    color: #B0ABBC;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    margin: 8px 0;
  }

  #modal-signin .google-btn {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border: 1px solid #6432C8;
    border-radius: 80px;
    background: #FFFFFF;
    color: #6432C8;
    font-size: 16px;
    font-weight: 500;
    line-height: 1;
    padding: 12px;
    text-decoration: none;
    z-index: 0;
  }

  #modal-signin .google-btn:hover {
    background: linear-gradient(180deg, #FFFFFF 0%, #F3EDFC 100%);
    border-color: #7244D6;
    color: #7244D6;
    box-shadow:
      0 4px 12px rgba(100, 50, 200, 0.14),
      inset 0 1px 0 rgba(255, 255, 255, 0.9);
  }

  #modal-signin .google-btn img {
    width: 24px;
    height: 24px;
    object-fit: contain;
  }

  #modal-signin .google-stack {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  #modal-signin .google-role-hint {
    color: #6B7280;
    font-size: 12px;
    font-weight: 500;
    line-height: 1.3;
    margin: 0 0 2px;
    text-align: center;
  }

  #modal-signin .login-modal-wrap {
    width: 100%;
    max-width: 36rem;
    margin: 0.75rem auto;
    box-sizing: border-box;
  }

  /* Tablet */
  @media (max-width: 1023px) {
    #modal-signin .login-modal-wrap {
      max-width: min(32rem, calc(100vw - 2rem));
      margin-top: 1.25rem;
    }

    #modal-signin .login-top h2 {
      font-size: 22px;
    }

    #modal-signin .login-top p {
      font-size: 15px;
    }
  }

  /* Mobile */
  @media (max-width: 767px) {
    #modal-signin .login-modal-wrap {
      max-width: calc(100vw - 1.25rem);
      margin: 0.625rem auto;
    }

    #modal-signin .login-shell {
      border-radius: 20px;
    }

    #modal-signin .login-top {
      border-radius: 20px 20px 0 0;
      padding: 14px 2.75rem 12px 1rem;
    }

    #modal-signin .login-top h2 {
      font-size: 20px;
    }

    #modal-signin .login-top p {
      font-size: 14px;
      line-height: 1.4;
    }

    #modal-signin .close-login-btn {
      right: 8px;
      top: 8px;
    }

    #modal-signin .join-box {
      padding: 12px 10px;
    }

    #modal-signin .join-caption {
      font-size: 13px;
      margin-bottom: 8px;
    }

    #modal-signin .join-buttons {
      flex-direction: column;
      gap: 8px;
    }

    #modal-signin .join-button {
      font-size: 14px;
      padding: 11px 12px;
    }

    #modal-signin .field-label {
      font-size: 12px;
      margin-bottom: 5px;
    }

    #modal-signin .input-shell input {
      height: 40px;
      font-size: 14px;
    }

    #modal-signin .input-shell input::placeholder {
      font-size: 13px;
    }

    #modal-signin .show-password-btn {
      font-size: 13px;
    }

    #modal-signin .forgot-link a {
      font-size: 13px;
    }

    #modal-signin .signin-btn {
      font-size: 15px;
      padding: 13px 12px;
    }

    #modal-signin .or-label {
      font-size: 13px;
      margin: 10px 0;
    }

    #modal-signin .google-btn {
      font-size: 15px;
      padding: 11px 12px;
      gap: 8px;
    }

    #modal-signin .google-btn img {
      width: 22px;
      height: 22px;
    }
  }

  /* Mobile pequeño */
  @media (max-width: 399px) {
    #modal-signin .login-top h2 {
      font-size: 18px;
    }

    #modal-signin .login-top p {
      font-size: 13px;
    }

    #modal-signin .join-button,
    #modal-signin .signin-btn,
    #modal-signin .google-btn {
      font-size: 14px;
    }
  }

  .win-spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    margin-right: 8px;
    border: 2px solid rgba(255, 255, 255, 0.45);
    border-top-color: #fff;
    border-radius: 50%;
    vertical-align: middle;
    animation: win-spin 0.6s linear infinite;
  }

  @keyframes win-spin {
    to {
      transform: rotate(360deg);
    }
  }
</style>
<div id="modal-signin" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
  <div class="login-modal-wrap hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-xl sm:w-full m-3 sm:mx-auto">
    <div class="login-shell shadow-sm">
      <div class="login-top">
        <button class="close-login-btn" data-hs-overlay="#modal-signin" type="button" aria-label="Close login">
          <img src="/assets/img/icons/mi_close.png" alt="Close">
        </button>
        <h2>Welcome Back!</h2>
        <p>Sign in to your Wedding Insiders Network account</p>
      </div>
      <div class="p-4 sm:p-6">
        <div class="join-box mb-4">
          <p class="join-caption">No account yet?</p>
          <div class="join-buttons">
            <a class="join-button" href="{{ route('register') }}"><span>Sign Up as a Couple</span></a>
            <a class="join-button" href="{{ route('vendor.register.form') }}"><span>Sign Up as a Vendor</span></a>
          </div>
        </div>
        <div class="grid gap-y-3">
          <div>
            <label for="email" class="field-label block">Email address</label>
            <div class="input-shell">
              <input type="email" id="email" name="email" placeholder="Enter Your Email" required>
            </div>
          </div>
          <div>
            <label for="login-password" class="field-label block">Password</label>
            <div class="input-shell">
              <input type="password" id="login-password" name="password" placeholder="Enter Your Password" required>
              <button id="show-login-password-toggle" type="button" class="show-password-btn">Show</button>
            </div>
            <p class="forgot-link"><a href="/forgot-password">Forgot Password?</a></p>
          </div>
          <button id="login-submit" type="button" class="signin-btn"><span>Sign In</span></button>
          <p class="or-label">OR</p>
          <a href="{{ route('auth.google.redirect', ['role' => 'couple']) }}" class="google-btn">
            <img src="/assets/img/icons/Google.png" alt="Google">
            <span>Continue with Google</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="vendor-modal-signin" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
  <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
      <div class="p-4 sm:p-7">
        <button class="float-right font-black text-lg -mt-2" data-hs-overlay="#vendor-modal-signin">X</button>
        <div class="text-center">
          <h2 class="block text-2xl font-bold text-gray-800">Vendor Login</h2>
          <p class="mt-2 text-sm text-gray-600">
            Don't have an account yet?
            <a class="text-blue decoration-2 underline font-medium" href="/vendor/register">
              Sign up
            </a>
          </p>
        </div>
        <hr class="mt-2 mb-4">
        <div class="grid gap-y-4">
          <!-- Form Group -->
          <div>
            <label for="vendor-email" class="block text-sm mb-2">Email address</label>
            <div class="relative">
              <input type="email" id="vendor-email" name="vendor-email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="email-error">
              <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                </svg>
              </div>
            </div>
            <p class="hidden text-xs text-red-600 mt-2" id="email-error">Please include a valid email address so we can get back to you</p>
          </div>
          <!-- End Form Group -->

          <!-- Form Group -->
          <div>
            <div class="flex justify-between items-center">
              <label for="vendor-password" class="block text-sm mb-2">Password</label>
              <a class="text-sm text-blue-600 decoration-2 hover:underline font-medium" href="/vendor/forgot-password">Forgot password?</a>
            </div>
            <div class="relative">
              <input type="password" id="vendor-password" name="vendor-password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="password-error">
              <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                </svg>
              </div>
            </div>
            <p class="hidden text-xs text-red-600 mt-2" id="password-error">8+ characters required</p>
          </div>
          <!-- End Form Group -->
          <!-- Checkbox -->
          <div class="flex items-center">
            <div class="flex">
              <input id="remember-me" name="remember-me" type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500">
            </div>
            <div class="ms-3">
              <label for="remember-me" class="text-sm">Remember me</label>
            </div>
          </div>
          <!-- End Checkbox -->
          <button id="vendor-login-submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-win-purple text-white disabled:opacity-50 disabled:pointer-events-none">Sign in</button>
        </div>
        <!-- End Form -->
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/js/win-toast.js'])
<script>
  function credentialsMismatchMessage(xhr) {
    const payload = xhr.responseJSON || {};
    return payload.message
      || (payload.errors && Object.values(payload.errors).flat()[0])
      || 'These credentials do not match our records.';
  }

  function handleLoginError(xhr) {
    if (xhr.status === 422) {
      Swal.fire({
        title: 'Oops!',
        text: credentialsMismatchMessage(xhr),
        icon: 'error',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#6432C8',
      });
    } else {
      window.WinToast && window.WinToast.show('Something went wrong on our end, please try again.', 'error');
    }
  }

  $("#vendor-login-submit").on("click", function () {
    const $btn = $(this);
    const originalHtml = $btn.html();
    const $fields = $("#vendor-email, #vendor-password");
    $btn.prop('disabled', true).html('<span class="win-spinner"></span> Signing In...');
    $fields.prop('readonly', true);

    function restore() {
      $btn.prop('disabled', false).html(originalHtml);
      $fields.prop('readonly', false);
    }

    let formData = {
      email: $("#vendor-email").val(),
      password: $("#vendor-password").val()
    };
    $.ajax({
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json',
      },
      url: "/vendor/login",
      data: formData,
      success: function(data) {
        const res = typeof data === 'string' ? JSON.parse(data) : data;
        if (res.status) {
          window.location.href = res.role === 'couple' ? '/dashboard' : '/vendor/dashboard';
          return;
        }
        restore();
      },
      error: function(xhr) {
        restore();
        handleLoginError(xhr);
      }
    });
  });

  $("#login-submit").on("click", function () {
    const $btn = $(this);
    const originalHtml = $btn.html();
    const $fields = $("#email, #login-password");
    $btn.prop('disabled', true).html('<span class="win-spinner"></span> Signing In...');
    $fields.prop('readonly', true);

    function restore() {
      $btn.prop('disabled', false).html(originalHtml);
      $fields.prop('readonly', false);
    }

    let formData = {
      email: $("#email").val(),
      password: $("#login-password").val()
    };
    $.ajax({
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json',
      },
      url: "/login",
      data: formData,
      success: function(data) {
        const res = typeof data === 'string' ? JSON.parse(data) : data;
        if (res.status) {
          window.location.href = res.role === 'vendor' ? '/vendor/dashboard' : '/dashboard';
          return;
        }
        restore();
      },
      error: function(xhr) {
        restore();
        handleLoginError(xhr);
      }
    });
  });
</script>
<script>
  const loginPasswordToggle = document.querySelector('#show-login-password-toggle');
  if (loginPasswordToggle) {
    loginPasswordToggle.addEventListener('click', function() {
      const loginPassword = document.querySelector('#login-password');
      const showText = loginPassword.type === 'password';
      loginPassword.type = showText ? 'text' : 'password';
      loginPasswordToggle.textContent = showText ? 'Hide' : 'Show';
      loginPassword.focus();
    });
  }
</script>