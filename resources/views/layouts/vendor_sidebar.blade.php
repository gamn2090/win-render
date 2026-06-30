@php
  $vendor = Auth::guard('vendor')->user();
  $currentPage = $page ?? '';
  $notifications = $vendor->getUnreadMessagesCount();
  $unreadCount = count($notifications['vendor_notifs'] ?? []);
  $displayName = $vendor->business_name ?: trim($vendor->first_name . ' ' . $vendor->last_name);
  $isTimelineActive = request()->routeIs('vendor.timeline') || $currentPage === 'planning_tools';
@endphp

<aside id="vendor-sidebar" class="vendor-sidebar" aria-label="Vendor navigation">
  <div class="vendor-sidebar__brand">
    <a href="{{ url('/vendor/dashboard') }}">
      <img class="vendor-sidebar__logo" src="{{ asset('assets/img/vendor-home/logo_orange.png') }}" alt="WIN" width="50" height="50" />
    </a>
    <p class="vendor-sidebar__subtitle">Vendor Dashboard</p>
  </div>

  <div class="vendor-sidebar__profile">
    <img
      id="vendor-sidebar-avatar"
      class="vendor-sidebar__avatar"
      src="{{ \App\Support\ProfileImageStorage::url($vendor->image) }}"
      alt=""
    />
    <div class="vendor-sidebar__profile-text">
      <p class="vendor-sidebar__profile-name" title="{{ $displayName }}">{{ $displayName }}</p>
      <a href="{{ url('/vendor/profile') }}" class="vendor-sidebar__profile-photo">
        <span class="vendor-sidebar__profile-camera" aria-hidden="true">📷</span>
        Update photo
      </a>
    </div>
  </div>

  <div class="vendor-sidebar__section">
    <p class="vendor-sidebar__section-label">MAIN</p>
    <ul class="vendor-sidebar__nav">
      <li>
        <a href="{{ url('/vendor/dashboard') }}" class="vendor-sidebar__link @if($currentPage === 'dashboard') vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--dashboard" aria-hidden="true">⊞</span>
          <span class="vendor-sidebar__label">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ url('/vendor/inbox') }}" id="vendor-sidebar-messages-link" class="vendor-sidebar__link @if($currentPage === 'inbox') vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--messages" aria-hidden="true">✉️</span>
          <span class="vendor-sidebar__label">Messages</span>
          @if($unreadCount > 0)
            <span class="vendor-sidebar__badge" aria-label="{{ $unreadCount }} unread">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
          @endif
        </a>
      </li>
      <li>
        <a href="{{ url('/vendor/couples') }}" class="vendor-sidebar__link @if($currentPage === 'find_couples') vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--find" aria-hidden="true">⚡</span>
          <span class="vendor-sidebar__label">Find Couples</span>
        </a>
      </li>
      <li>
        <a href="{{ url('/vendor/client/list') }}" class="vendor-sidebar__link @if($currentPage === 'client_list') vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--clients" aria-hidden="true">✨</span>
          <span class="vendor-sidebar__label">Current Clients</span>
        </a>
      </li>
      <li>
        <a href="{{ route('vendor.list') }}" class="vendor-sidebar__link @if($currentPage === 'vendor_list') vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--network" aria-hidden="true">🔗</span>
          <span class="vendor-sidebar__label">Vendor Network</span>
        </a>
      </li>
      <li>
        <a href="{{ route('vendor.storefront') }}" class="vendor-sidebar__link @if($currentPage === 'storefront') vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--storefront" aria-hidden="true">🏪</span>
          <span class="vendor-sidebar__label">Storefront</span>
        </a>
      </li>
      <li>
        <a href="{{ url('/vendor/insights') }}" class="vendor-sidebar__link @if($currentPage === 'insights') vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--insights" aria-hidden="true">📊</span>
          <span class="vendor-sidebar__label">Insights</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="vendor-sidebar__section">
    <p class="vendor-sidebar__section-label">VENDOR TOOLS</p>
    <ul class="vendor-sidebar__nav">
      <li>
        <a href="{{ route('vendor.timeline') }}" class="vendor-sidebar__link @if($isTimelineActive) vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--timeline" aria-hidden="true">🗓️</span>
          <span class="vendor-sidebar__label">Timeline Planner</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="vendor-sidebar__section">
    <p class="vendor-sidebar__section-label">Couple Tools</p>
    <ul class="vendor-sidebar__nav">
      <li>
        <a href="{{ url('/vendor/planning-tools') }}" class="vendor-sidebar__link @if($currentPage === 'planning_tools' && !request()->routeIs('vendor.timeline')) vendor-sidebar__link--active @endif">
          <span class="vendor-sidebar__emoji vendor-sidebar__emoji--budget" aria-hidden="true">💰</span>
          <span class="vendor-sidebar__label">Budget Planner</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="vendor-sidebar__footer">
    <form method="POST" action="{{ route('logout.vendor') }}" class="vendor-sidebar__logout-form">
      @csrf
      <button type="submit" class="vendor-sidebar__logout">
        <span class="vendor-sidebar__emoji vendor-sidebar__emoji--logout" aria-hidden="true">↪</span>
        <span class="vendor-sidebar__label">Log out</span>
      </button>
    </form>
  </div>
</aside>
