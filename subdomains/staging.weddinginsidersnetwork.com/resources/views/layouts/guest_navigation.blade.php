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
<div id="modal-signin" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
  <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
      <div class="p-4 sm:p-7">
        <button class="float-right font-black text-lg -mt-2" data-hs-overlay="#modal-signin">X</button>
        <div class="text-center">
          <h2 class="block text-2xl font-bold text-gray-800">Sign in</h2>
          <p class="mt-2 text-sm text-gray-600">
            Don't have an account yet?
            <a class="text-blue decoration-2 underline font-medium" href="/user/register">
              Sign up here
            </a>
          <p class="text-sm">Or, <span>
              <a class="text-blue decoration-2 underline font-medium" href="/vendor/register">
                Sign up as a vendor
              </a></span></p>
          </p>
        </div>
        <hr class="mt-2 mb-4">
        <div id="error-alert" class="bg-red-50 border-s-4 border-red-500 p-4 rounded-sm my-1" role="alert" hidden>
          <div class="flex">
            <div class="flex-shrink-0">
              <!-- Icon -->
              <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-red-100 bg-red-200 text-red-800">
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 6 6 18" />
                  <path d="m6 6 12 12" />
                </svg>
              </span>
              <!-- End Icon -->
            </div>
            <div class="ms-3">
              <h3 class="text-gray-800 font-semibold">
                Error!
              </h3>
              <p id="error-text" class="text-sm text-gray-700">

              </p>
            </div>
          </div>
        </div>
        <div class="grid gap-y-4">
          <!-- Form Group -->
          <div>
            <label for="email" class="block text-sm mb-2">Email address</label>
            <div class="relative">
              <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="email-error">
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
              <label for="login-password" class="block text-sm mb-2">Password
              <button type="button"  data-hs-toggle-password='{
                  "target": "#login-password"
                  }' id="show-login-password-confirmation-toggle" class="inline-flex inline items-center z-20 px-2 cursor-pointer text-gray-400 rounded-e-md focus:outline-none">
              <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                  <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                  <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                  <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                  <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                  <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
              </svg>
              </button></label>
              <a class="text-sm decoration-2 hover:underline font-medium" href="/forgot-password">Forgot password?</a>
            </div>
            <div class="relative">
              <input type="password" id="login-password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="password-error">
              <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                </svg>
              </div>
            </div>
            <p class="hidden text-xs text-red-600 mt-2" id="password-error">8+ characters required</p>
          </div>
          <!-- End Form Group -->

          <button id="login-submit" type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-win-purple text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Sign in</button>
        </div>
        <!-- End Form -->
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
        <div id="vendor-error-alert" class="bg-red-50 border-s-4 border-red-500 p-4 rounded-sm my-1" role="alert" hidden>
          <div class="flex">
            <div class="flex-shrink-0">
              <!-- Icon -->
              <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-red-100 bg-red-200 text-red-800">
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 6 6 18" />
                  <path d="m6 6 12 12" />
                </svg>
              </span>
              <!-- End Icon -->
            </div>
            <div class="ms-3">
              <h3 class="text-gray-800 font-semibold">
                Error!
              </h3>
              <p id="vendor-error-text" class="text-sm text-gray-700">

              </p>
            </div>
          </div>
        </div>
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
              <a class="text-sm text-blue-600 decoration-2 hover:underline font-medium" href="../examples/html/modal-recover-account.html">Forgot password?</a>
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
<script>
  $("#vendor-login-submit").on("click", () => {
    let formData = {
      email: $("#vendor-email").val(),
      password: $("#vendor-password").val()
    };
    $.ajax({
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/vendor/login",
      data: formData,
      success: function(data) {
        if (JSON.parse(data)["status"]) {
          console.log('vendor redirecting!');
          window.location.href = '/vendor/dashboard';
        }
        console.log(data);
      },
      error: function(data) {
        $('#vendor-error-alert').attr('hidden', false);
        $('#vendor-error-text').html(data['responseJSON']["message"]);
      }
    });
  });
  $("#login-submit").on("click", () => {
    let formData = {
      email: $("#email").val(),
      password: $("#login-password").val()
    };
    $.ajax({
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/login",
      data: formData,
      success: function(data) {
        if (JSON.parse(data)["status"]) {
          console.log('redirecting!');
          window.location.href = '/';
        }
      },
      error: function(data) {
        $('#error-alert').attr('hidden', false);
        $('#error-text').html(data['responseJSON']["message"]);
      }
    });
  });
</script>
<script>
  const loginPasswordToggle = document.querySelector('#show-login-password-toggle');

  loginPasswordToggle.addEventListener('click', function() {
  const loginPassword = document.querySelector('#login-password');

  if (loginPassword.type === 'password') {
    loginPassword.type = 'text';
  } else {
    loginPassword.type = 'password';
  }

  loginPassword.focus();
  });
</script>