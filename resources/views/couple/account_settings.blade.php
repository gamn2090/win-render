<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Account Settings</title>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">

@include('layouts.couple_sidebar', ['page' => 'account_settings'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    @include('layouts.dashboard_topbar', ['role' => 'couple'])

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">Account Settings</h1>
      <p class="vd-page-header__sub">Manage your password and account.</p>
    </header>

    <section class="vd-edit-card vd-settings-section">
      <h2 class="vd-settings-section__title">Update Password</h2>
      <p class="vd-settings-section__desc">Ensure your account is using a long, random password to stay secure.</p>

      @if (session('status') === 'password-updated')
        <div class="vd-edit-success-banner">Password updated!</div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" class="vd-edit-form">
        @csrf
        @method('put')

        <div class="vd-edit-field">
          <label class="vd-edit-field__label" for="current_password">Current Password</label>
          <input id="current_password" type="password" name="current_password" autocomplete="current-password" />
          @error('current_password', 'updatePassword')
            <span class="vd-field-error">{{ $message }}</span>
          @enderror
        </div>

        <div class="vd-edit-row">
          <div class="vd-edit-field">
            <label class="vd-edit-field__label" for="password">New Password</label>
            <input id="password" type="password" name="password" autocomplete="new-password" />
            @error('password', 'updatePassword')
              <span class="vd-field-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="vd-edit-field">
            <label class="vd-edit-field__label" for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
              <span class="vd-field-error">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <button type="submit" class="vd-edit-save-btn">Save Password</button>
      </form>
    </section>

    <section class="vd-edit-card vd-settings-section vd-settings-section--danger">
      <h2 class="vd-settings-section__title">Delete Account</h2>
      <p class="vd-settings-section__desc">
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Before deleting your account, please download any data or information that you wish to retain.
      </p>

      <button type="button" id="vd-open-delete-account" class="vd-danger-btn">Delete Account</button>
    </section>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>
</main>

<div id="vd-delete-account-modal" class="vd-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="vd-delete-account-title">
  <div class="vd-modal">
    <button type="button" class="vd-modal__close" data-modal-close aria-label="Close">&times;</button>
    <form method="POST" action="{{ route('profile.destroy') }}" class="vd-modal__body" style="width: 100%;">
      @csrf
      @method('delete')

      <h2 id="vd-delete-account-title" class="vd-modal__title">Are you sure?</h2>
      <p class="vd-modal__subtitle">This will permanently delete your account.<br />Enter your password to confirm.</p>

      <div class="vd-edit-field" style="text-align: left;">
        <input id="delete_password" type="password" name="password" placeholder="Password" autocomplete="current-password" />
        @error('password', 'userDeletion')
          <span class="vd-field-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="vd-modal__actions">
        <button type="button" class="vd-modal__btn vd-modal__btn--cancel" data-modal-close>Cancel</button>
        <button type="submit" class="vd-modal__btn vd-modal__btn--danger">Delete Account</button>
      </div>
    </form>
  </div>
</div>

<script>
  (function () {
    var modal = document.getElementById('vd-delete-account-modal');

    document.getElementById('vd-open-delete-account').addEventListener('click', function () {
      modal.classList.add('is-open');
    });

    modal.addEventListener('click', function (e) {
      if (e.target.closest('[data-modal-close]') || e.target === modal) {
        modal.classList.remove('is-open');
      }
    });

    @if ($errors->userDeletion->isNotEmpty())
      modal.classList.add('is-open');
    @endif
  })();
</script>
</body>
</html>
