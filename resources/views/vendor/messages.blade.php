<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Messages</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-messages.css', 'resources/js/app.js', 'resources/js/vendor-messages.js'])
  @include('components.fonts')
</head>
<body class="vm-page m-0 antialiased overflow-x-hidden">
@php
  $vendor = Auth::guard('vendor')->user();
  $vmVendorMeta = [
    'initials' => strtoupper(mb_substr($vendor->first_name ?? '', 0, 1) . mb_substr($vendor->last_name ?? '', 0, 1)),
    'first_name' => $vendor->first_name ?? '',
  ];
@endphp

<script>
  window.vmVendorMeta = @json($vmVendorMeta);
</script>

@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  <div class="fc-toolbar" aria-label="Page tools">
    <div class="fc-toolbar__icons">
      <button type="button" class="fc-toolbar__icon" aria-label="Search">🔍</button>
      <button type="button" class="fc-toolbar__icon" aria-label="Notifications">🔔</button>
      <a href="{{ url('/vendor/profile') }}" class="fc-toolbar__icon" aria-label="Settings">⚙️</a>
    </div>
  </div>

  <header class="vm-hero">
    <div class="vm-hero__content">
      <h1 class="vm-hero__title">Messages</h1>
      <p class="vm-hero__subtitle">All your messages in one place</p>
    </div>
  </header>

  <div class="vm-content">
    <div id="vm-messages-list">
      <section class="vm-section" aria-labelledby="vm-new-inquiries-title">
        <h2 id="vm-new-inquiries-title" class="vm-section__title">New Inquiries</h2>
        <x-vendor-messages-table
          :rows="$newInquiries"
          empty-message="No new inquiries right now."
          :show-role-column="true"
        />
      </section>

      <section class="vm-section" aria-labelledby="vm-inbox-title">
        <h2 id="vm-inbox-title" class="vm-section__title">Inbox</h2>
        <x-vendor-messages-table
          :rows="$inbox"
          empty-message="Your inbox is empty."
          :show-role-column="true"
        />
      </section>
    </div>

    <x-vm-message-view />
  </div>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
</main>
</body>
</html>
