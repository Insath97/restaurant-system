  <nav class="sidebar">
      <div class="sidebar-header text-center">
          <img src="img/logo.png" alt="Colombo Spice Logo" width="120" class="mb-3">
          <h3>Colombo Spice</h3>
          <p>Admin Dashboard</p>
      </div>
      <ul class="sidebar-menu">
          <li class="active">
              <a href="{{ route('admin.dashboard.index') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
          </li>
          <li>
              <a href="{{ route('admin.menus.index') }}"><i class="fas fa-utensils"></i> Menu Management</a>
          </li>
          <li>
              <a href="{{ route('admin.categories.index') }}"><i class="fas fa-tags"></i> Category Management</a>
          </li>
           <li>
              <a href="{{ route('admin.tables.index') }}"><i class="fas fa-table"></i> Table Management</a>
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
