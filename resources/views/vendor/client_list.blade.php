<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>WIN: Current Clients</title>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  @vite(['resources/css/app.css', 'resources/css/vendor-current-clients.css', 'resources/css/vendor-dashboard.css', 'resources/js/app.js'])
  @include('components.fonts')
</head>
<body class="cc-page m-0 antialiased overflow-x-hidden">
@include('layouts.vendor_navigation')

<main class="relative transition-all duration-200 ease-in-out">
  @include('layouts.dashboard_topbar', ['role' => 'vendor'])

  <header class="cc-hero">
    <div class="cc-hero__inner">
      <div class="cc-hero__text">
        <h1 class="cc-hero__title">Take a moment to celebrate your WINS!</h1>
        <p class="cc-hero__subtitle">
          Below you can view your past clients, weddings, and export your client list.
        </p>
      </div>
      <a href="{{ route('vendor.create.client') }}" class="cc-hero__cta">
        <span class="cc-hero__cta-icon" aria-hidden="true">+</span>
        Add Client
      </a>
    </div>
  </header>

    <div class="cc-content">
    @if(session('client_invite_success'))
      <div class="cc-flash cc-flash--success" role="status">
        Success! You have invited a new client.
      </div>
    @endif

    <div class="cc-stack">
      <div class="cc-list-toolbar">
        <p class="cc-list-toolbar__count">
          <span id="cc-count-active" class="cc-list-toolbar__count-num">{{ count($activeClients) }}</span>
          <span id="cc-count-archived" class="cc-list-toolbar__count-num" hidden>{{ count($archivedClients) }}</span>
          <span id="cc-count-label">{{ count($activeClients) === 1 ? 'client' : 'clients' }}</span>
        </p>

        <label class="vd-edit-toggle">
          <span>Active</span>
          <input type="checkbox" id="cc-archived-toggle" class="vd-edit-toggle__input" />
          <span class="vd-edit-toggle__track"><span class="vd-edit-toggle__thumb"></span></span>
          <span>Archived</span>
        </label>

        <a href="{{ route('vendor.client.export') }}" class="cc-export-btn">
          Export CSV
        </a>
      </div>

      <div id="cc-table-active">
        <x-vendor-clients-table
          :rows="$activeClients"
          empty-message="No current clients yet. Use Add Client to invite your first couple."
        />
      </div>
      <div id="cc-table-archived" hidden>
        <x-vendor-clients-table
          :rows="$archivedClients"
          empty-message="No archived clients."
        />
      </div>
    </div>
  </div>

  <p class="vd-copyright">&copy; {{ date('Y') }} Wedding Insiders Network.</p>

  {{-- Site footer disabled per client request — uncomment to restore --}}
  {{-- @include('layouts.footer') --}}
  <script>
    (function () {
      var toggle = document.getElementById('cc-archived-toggle');
      var tableActive = document.getElementById('cc-table-active');
      var tableArchived = document.getElementById('cc-table-archived');
      var countActive = document.getElementById('cc-count-active');
      var countArchived = document.getElementById('cc-count-archived');
      var countLabel = document.getElementById('cc-count-label');
      var activeCount = {{ count($activeClients) }};
      var archivedCount = {{ count($archivedClients) }};

      toggle.addEventListener('change', function () {
        var showArchived = toggle.checked;
        tableActive.hidden = showArchived;
        tableArchived.hidden = !showArchived;
        countActive.hidden = showArchived;
        countArchived.hidden = !showArchived;
        var count = showArchived ? archivedCount : activeCount;
        countLabel.textContent = count === 1 ? 'client' : 'clients';
      });
    })();
  </script>
</main>
</body>
</html>
