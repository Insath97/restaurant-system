  <nav class="sidebar">
      <div class="sidebar-header text-center">
          <img src="{{ asset('default/Untitled design.png') }}" alt="DFR Logo" width="200">
          <h3>D F R </h3>
          <p>Admin Dashboard</p>
      </div>
      <ul class="sidebar-menu">
          <li class="{{ setSidebarActive(['admin.dashboard.*']) }}">
              <a href="{{ route('admin.dashboard.index') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
          </li>
          <li class="{{ setSidebarActive(['admin.menus.*']) }}">
              <a href="{{ route('admin.menus.index') }}"><i class="fas fa-utensils"></i> Menu Management</a>
          </li>
          <li class="{{ setSidebarActive(['admin.categories.*']) }}">
              <a href="{{ route('admin.categories.index') }}"><i class="fas fa-tags"></i> Category Management</a>
          </li>
          <li class="{{ setSidebarActive(['admin.tables.*']) }}">
              <a href="{{ route('admin.tables.index') }}"><i class="fas fa-table"></i> Table Management</a>
          </li>
          <li class="{{ setSidebarActive(['admin.permissions.*']) }}">
              <a href="{{ route('admin.permissions.index') }}"><i class="fas fa-key"></i> Permission Management</a>
          </li>
          <li class="{{ setSidebarActive(['admin.roles.*']) }}">
              <a href="{{ route('admin.roles.index') }}"><i class="fas fa-user-shield"></i> Roles Management</a>
          </li>

          <li class="{{ setSidebarActive(['admin.users.*']) }}">
              <a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> User Management</a>
          </li>

          {{-- ################################## --}}
          <li>
              <a href="#reservations"><i class="fas fa-calendar-check"></i> Reservations</a>
          </li>
          <li>
              <a href="#specials"><i class="fas fa-star"></i> Chef Specials</a>
          </li>
          <li>
              <a href="#orders"><i class="fas fa-receipt"></i> Orders</a>
          </li>
          <li>
              <a href="#gallery"><i class="fas fa-images"></i> Gallery</a>
          </li>
          <li>
              <a href="#events"><i class="fas fa-calendar-alt"></i> Events</a>
          </li>
          <li>
              <a href="#reviews"><i class="fas fa-comments"></i> Reviews</a>
          </li>
          <li>
              <a href="#staff"><i class="fas fa-users"></i> Staff</a>
          </li>
          <li>
              <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
          </li>
      </ul>
  </nav>
