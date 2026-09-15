<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Admin Dashboard</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-dashboard.css'])
  @vite(['resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="vd-page m-0 antialiased overflow-x-hidden">

@include('layouts.admin_sidebar', ['page' => 'dashboard'])

<main class="relative transition-all duration-200 ease-in-out">
  <div class="vd-main">

    <header class="vd-page-header">
      <h1 class="vd-page-header__title">Admin Dashboard</h1>
      <p class="vd-page-header__sub">Manage couples and vendors on WIN.</p>
      <div style="display:flex; gap:10px; margin-top:12px; flex-wrap:wrap;">
        <button type="button" class="vd-edit-save-btn" data-hs-overlay="#create-admin-modal">+ Create Admin</button>
        <button type="button" class="vd-edit-save-btn" style="background:#231f20;" data-hs-overlay="#change-password-modal">Change My Password</button>
      </div>
    </header>

    <section class="vd-duo" aria-label="Couples and vendors" style="align-items:start;">

      {{-- Couples --}}
      <article class="vd-edit-card vd-settings-section" id="admin-couples-card">
        <h2 class="vd-settings-section__title">Couples ({{ $couples->total() }})</h2>
        <div style="overflow-x:auto;">
          <table class="admin-table" id="admin-couples-table">
            <thead>
              <tr>
                <th style="width:32px;"><input type="checkbox" class="admin-select-all" data-target="admin-couples-table" /></th>
                <th>Name</th>
                <th>Email</th>
                <th>Joined</th>
              </tr>
            </thead>
            <tbody>
              @forelse($couples as $couple)
                <tr>
                  <td><input type="checkbox" class="admin-row-checkbox" value="{{ $couple->id }}" /></td>
                  <td>{{ trim($couple->first_name . ' & ' . ($couple->fiance_first_name ?? '')) }}</td>
                  <td>{{ $couple->email }}</td>
                  <td>{{ $couple->created_at?->format('M j, Y') }}</td>
                </tr>
              @empty
                <tr><td colspan="4">No couples found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div style="margin-top:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
          {{ $couples->links() }}
          <button type="button" id="admin-delete-couples-btn" class="vd-danger-btn">Delete Selected</button>
        </div>
      </article>

      {{-- Vendors --}}
      <article class="vd-edit-card vd-settings-section" id="admin-vendors-card">
        <h2 class="vd-settings-section__title">Vendors ({{ $vendors->total() }})</h2>
        <div style="overflow-x:auto;">
          <table class="admin-table" id="admin-vendors-table">
            <thead>
              <tr>
                <th style="width:32px;"><input type="checkbox" class="admin-select-all" data-target="admin-vendors-table" /></th>
                <th>Business Name</th>
                <th>Email</th>
                <th>Joined</th>
              </tr>
            </thead>
            <tbody>
              @forelse($vendors as $vendor)
                @php $isActive = $vendor->isActiveMember(); @endphp
                <tr>
                  <td>
                    <input
                      type="checkbox"
                      class="admin-row-checkbox"
                      value="{{ $vendor->id }}"
                      @disabled($isActive)
                      title="{{ $isActive ? 'Has an active subscription — cannot be deleted from here.' : '' }}"
                    />
                  </td>
                  <td>
                    {{ $vendor->business_name ?: trim($vendor->first_name . ' ' . $vendor->last_name) }}
                    @if($isActive)
                      <span class="admin-badge admin-badge--active" title="Active subscription">Active subscription</span>
                    @endif
                  </td>
                  <td>{{ $vendor->email }}</td>
                  <td>{{ $vendor->created_at?->format('M j, Y') }}</td>
                </tr>
              @empty
                <tr><td colspan="4">No vendors found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div style="margin-top:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
          {{ $vendors->links() }}
          <button type="button" id="admin-delete-vendors-btn" class="vd-danger-btn">Delete Selected</button>
        </div>
      </article>

    </section>

    <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>
  </div>
</main>

{{-- Create Admin modal --}}
<x-large-modal id="create-admin">
  <h2 class="vd-settings-section__title">Create Admin</h2>
  <form id="create-admin-form" class="vd-edit-form">
    <div class="vd-edit-field">
      <label class="vd-edit-field__label" for="new-admin-username">Name</label>
      <input id="new-admin-username" type="text" name="username" required />
    </div>
    <div class="vd-edit-field">
      <label class="vd-edit-field__label" for="new-admin-email">Email</label>
      <input id="new-admin-email" type="email" name="email" required />
    </div>
    <div class="vd-edit-row">
      <div class="vd-edit-field">
        <label class="vd-edit-field__label" for="new-admin-password">Password</label>
        <input id="new-admin-password" type="password" name="password" required minlength="8" />
      </div>
      <div class="vd-edit-field">
        <label class="vd-edit-field__label" for="new-admin-password-confirmation">Confirm Password</label>
        <input id="new-admin-password-confirmation" type="password" name="password_confirmation" required minlength="8" />
      </div>
    </div>
    <button type="submit" class="vd-edit-save-btn">Create Admin</button>
  </form>
</x-large-modal>

{{-- Change Password modal --}}
<x-large-modal id="change-password">
  <h2 class="vd-settings-section__title">Change My Password</h2>
  <form id="change-password-form" class="vd-edit-form">
    <div class="vd-edit-field">
      <label class="vd-edit-field__label" for="admin-current-password">Current Password</label>
      <input id="admin-current-password" type="password" name="current_password" required />
    </div>
    <div class="vd-edit-row">
      <div class="vd-edit-field">
        <label class="vd-edit-field__label" for="admin-new-password">New Password</label>
        <input id="admin-new-password" type="password" name="password" required minlength="8" />
      </div>
      <div class="vd-edit-field">
        <label class="vd-edit-field__label" for="admin-new-password-confirmation">Confirm New Password</label>
        <input id="admin-new-password-confirmation" type="password" name="password_confirmation" required minlength="8" />
      </div>
    </div>
    <button type="submit" class="vd-edit-save-btn">Update Password</button>
  </form>
</x-large-modal>

<style>
  .admin-table { width:100%; border-collapse:collapse; font-size:13px; }
  .admin-table th, .admin-table td { text-align:left; padding:8px 10px; border-bottom:1px solid rgba(21,21,21,.08); }
  .admin-table th { font-size:11px; text-transform:uppercase; letter-spacing:.05em; color:rgba(21,21,21,.55); }
  .admin-badge { display:inline-block; margin-left:6px; padding:2px 8px; border-radius:999px; font-size:10px; font-weight:700; }
  .admin-badge--active { background:#e6f7f1; color:#1a7a4c; }
</style>

@vite(['resources/js/admin-dashboard.js'])
</body>
</html>
