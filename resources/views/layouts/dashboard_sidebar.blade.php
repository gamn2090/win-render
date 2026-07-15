@php
  $role = $role ?? 'couple';
  $currentPage = $page ?? '';

  if ($role === 'vendor') {
      $account = Auth::guard('vendor')->user();
      $displayName = $account->business_name ?: trim($account->first_name . ' ' . $account->last_name);
      $brandUrl = url('/vendor/dashboard');
      $brandCaption = 'Vendor Dashboard';
      $profileEditUrl = url('/vendor/profile');
      $logoutRoute = route('logout.vendor');
      $isTimelineActive = request()->routeIs('vendor.timeline') || $currentPage === 'planning_tools';
  } else {
      $account = Auth::guard('web')->user();
      $displayName = trim(($account->first_name ?? '') . ' & ' . ($account->fiance_first_name ?? ''));
      $brandUrl = url('/dashboard');
      $brandCaption = 'Couple Dashboard';
      $profileEditUrl = route('user.profile.edit');
      $logoutRoute = route('logout');
  }

  $unreadCount = $role === 'couple'
      ? $account->unreadConversationsCount()
      : count($account->getUnreadMessagesCount()['vendor_notifs'] ?? []);

  $navLinkClasses = function (string $key) use ($currentPage) {
      $active = $currentPage === $key;
      return 'dashboard-sidebar__link' . ($active ? ' dashboard-sidebar__link--active' : '');
  };
@endphp

<aside id="dashboard-sidebar" class="dashboard-sidebar" aria-label="{{ $role === 'vendor' ? 'Vendor navigation' : 'Couple navigation' }}">
  <div class="dashboard-sidebar__brand">
    <a href="{{ $brandUrl }}">
      <img class="dashboard-sidebar__logo" src="{{ asset('assets/img/vendor-home/logo_orange.png') }}" alt="WIN" width="50" height="50" />
    </a>
    <p class="dashboard-sidebar__subtitle">{{ $brandCaption }}</p>
  </div>

  <div class="dashboard-sidebar__profile">
    <x-avatar
      id="dashboard-sidebar-avatar"
      :model="$account"
      class="dashboard-sidebar__avatar"
    />
    <div class="dashboard-sidebar__profile-text">
      <p class="dashboard-sidebar__profile-name" title="{{ $displayName }}">{{ $displayName }}</p>
      <a href="{{ $profileEditUrl }}" class="dashboard-sidebar__profile-photo">
        <span class="dashboard-sidebar__profile-camera" aria-hidden="true">📷</span>
        Update photo
      </a>
    </div>
  </div>

  @if($role === 'vendor')
    <div class="dashboard-sidebar__section">
      <p class="dashboard-sidebar__section-label">MAIN</p>
      <ul class="dashboard-sidebar__nav">
        <li>
          <a href="{{ url('/vendor/dashboard') }}" class="{{ $navLinkClasses('dashboard') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--dashboard" aria-hidden="true">⊞</span>
            <span class="dashboard-sidebar__label">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/vendor/inbox') }}" id="dashboard-sidebar-messages-link" class="{{ $navLinkClasses('inbox') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--messages" aria-hidden="true">✉️</span>
            <span class="dashboard-sidebar__label">Messages</span>
            @if($unreadCount > 0)
              <span class="dashboard-sidebar__badge" aria-label="{{ $unreadCount }} unread">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
          </a>
        </li>
        <li>
          <a href="{{ route('vendor.search.vendors') }}" class="{{ $navLinkClasses('search_vendors') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--find" aria-hidden="true">🔍</span>
            <span class="dashboard-sidebar__label">Find Vendors</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/vendor/couples') }}" class="{{ $navLinkClasses('find_couples') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--find" aria-hidden="true">⚡</span>
            <span class="dashboard-sidebar__label">Find Couples</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/vendor/client/list') }}" class="{{ $navLinkClasses('client_list') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--clients" aria-hidden="true">✨</span>
            <span class="dashboard-sidebar__label">Current Clients</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vendor.list') }}" class="{{ $navLinkClasses('vendor_list') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--network" aria-hidden="true">🔗</span>
            <span class="dashboard-sidebar__label">Vendor Network</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vendor.storefront') }}" class="{{ $navLinkClasses('storefront') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--storefront" aria-hidden="true">🏪</span>
            <span class="dashboard-sidebar__label">Storefront</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/vendor/insights') }}" class="{{ $navLinkClasses('insights') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--insights" aria-hidden="true">📊</span>
            <span class="dashboard-sidebar__label">Insights</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="dashboard-sidebar__section">
      <p class="dashboard-sidebar__section-label">VENDOR TOOLS</p>
      <ul class="dashboard-sidebar__nav">
        <li>
          <a href="{{ route('vendor.timeline') }}" class="dashboard-sidebar__link @if($isTimelineActive) dashboard-sidebar__link--active @endif">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--timeline" aria-hidden="true">🗓️</span>
            <span class="dashboard-sidebar__label">Timeline Planner</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="dashboard-sidebar__section">
      <p class="dashboard-sidebar__section-label">Couple Tools</p>
      <ul class="dashboard-sidebar__nav">
        <li>
          <a href="{{ url('/vendor/planning-tools') }}" class="dashboard-sidebar__link @if($currentPage === 'planning_tools' && !request()->routeIs('vendor.timeline')) dashboard-sidebar__link--active @endif">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--budget" aria-hidden="true">💰</span>
            <span class="dashboard-sidebar__label">Budget Planner</span>
          </a>
        </li>
      </ul>
    </div>
  @else
    <div class="dashboard-sidebar__section">
      <p class="dashboard-sidebar__section-label">MAIN</p>
      <ul class="dashboard-sidebar__nav">
        <li>
          <a href="{{ url('/dashboard') }}" class="{{ $navLinkClasses('dashboard') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--dashboard" aria-hidden="true">⊞</span>
            <span class="dashboard-sidebar__label">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ route('search.vendors') }}" class="{{ $navLinkClasses('find_vendors') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--find" aria-hidden="true">⚡</span>
            <span class="dashboard-sidebar__label">Find Vendors</span>
          </a>
        </li>
        <li>
          <a href="{{ route('client.inbox') }}" id="dashboard-sidebar-messages-link" class="{{ $navLinkClasses('inbox') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--messages" aria-hidden="true">✉️</span>
            <span class="dashboard-sidebar__label">Messages</span>
            @if($unreadCount > 0)
              <span class="dashboard-sidebar__badge" aria-label="{{ $unreadCount }} unread">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
          </a>
        </li>
        <li>
          <a href="{{ route('client.favorites') }}" class="{{ $navLinkClasses('favorites') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--favorites" aria-hidden="true">❤️</span>
            <span class="dashboard-sidebar__label">Favorites</span>
          </a>
        </li>
        <li>
          <a href="{{ route('client.vendor.list') }}" class="{{ $navLinkClasses('vendor_list') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--my-vendors" aria-hidden="true">🏪</span>
            <span class="dashboard-sidebar__label">My Vendors</span>
          </a>
        </li>
        <li>
          <a href="{{ route('client.my_profile') }}" class="{{ $navLinkClasses('edit_profile') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--my-profile" aria-hidden="true">👤</span>
            <span class="dashboard-sidebar__label">My Profile</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="dashboard-sidebar__section">
      <p class="dashboard-sidebar__section-label">Couple Tools</p>
      <ul class="dashboard-sidebar__nav">
        <li>
          <a href="{{ route('couple.timeline') }}" class="{{ $navLinkClasses('couple_timeline') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--timeline" aria-hidden="true">🗓️</span>
            <span class="dashboard-sidebar__label">Timeline Planner</span>
          </a>
        </li>
        <li>
          <a href="{{ route('couple.investment_planner') }}" class="{{ $navLinkClasses('planning_tools') }}">
            <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--budget" aria-hidden="true">💰</span>
            <span class="dashboard-sidebar__label">Budget Planner</span>
          </a>
        </li>
      </ul>
    </div>
  @endif

  <div class="dashboard-sidebar__footer">
    <form method="POST" action="{{ $logoutRoute }}" class="dashboard-sidebar__logout-form">
      @csrf
      <button type="submit" class="dashboard-sidebar__logout">
        <span class="dashboard-sidebar__emoji dashboard-sidebar__emoji--logout" aria-hidden="true">↪</span>
        <span class="dashboard-sidebar__label">Log out</span>
      </button>
    </form>
  </div>
</aside>
