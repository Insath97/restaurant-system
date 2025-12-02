  <nav class="sidebar">
      <div class="sidebar-header text-center">
          <img src="{{ asset('default/Untitled design.png') }}" alt="DFR Logo" width="200">
          <h3>D F R </h3>
          <p>Admin Dashboard</p>
      </div>
      <ul class="sidebar-menu">

          @if (canAccess(['Dashboard Access']))
              <li class="{{ setSidebarActive(['admin.dashboard.*']) }}">
                  <a href="{{ route('admin.dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
              </li>
          @endif

          @if (canAccess(['Reservation Index', 'Reservation View', 'Reservation Update']))
              <li class="{{ setSidebarActive(['admin.reservations.*']) }}">
                  <a href="{{ route('admin.reservations.index') }}"><i class="fas fa-calendar-alt"></i> Reservation
                      Management</a>
              </li>
          @endif

          <!-- Order Management -->
          @if (canAccess(['Order Index', 'Order View', 'Order Update']))
              <li class="{{ setSidebarActive(['admin.orders.*']) }}">
                  <a href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-bag"></i> Order Management</a>
              </li>
          @endif

          @if (canAccess(['Customer Index', 'Customer View']))
              <li class="{{ setSidebarActive(['admin.customers.*']) }}">
                  <a href="{{ route('admin.customers.index') }}"><i class="fas fa-users"></i> Customer Management</a>
              </li>
          @endif

          @if (canAccess(['Food Index', 'Food Create', 'Food Update', 'Food Delete']))
              <li class="{{ setSidebarActive(['admin.menus.*']) }}">
                  <a href="{{ route('admin.menus.index') }}"><i class="fas fa-utensils"></i> Food Management</a>
              </li>
          @endif

          @if (canAccess(['Category Index', 'Category Create', 'Category Update', 'Category Delete']))
              <li class="{{ setSidebarActive(['admin.categories.*']) }}">
                  <a href="{{ route('admin.categories.index') }}"><i class="fas fa-list"></i> Category Management</a>
              </li>
          @endif

          <!-- Table Management -->
          @if (canAccess(['Table Index', 'Table Create', 'Table Update', 'Table Delete']))
              <li class="{{ setSidebarActive(['admin.tables.*']) }}">
                  <a href="{{ route('admin.tables.index') }}"><i class="fas fa-chair"></i> Table Management</a>
              </li>
          @endif

          @if (canAccess(['Permission Index', 'Permission Create', 'Permission Update', 'Permission Delete']))
              <li class="{{ setSidebarActive(['admin.permissions.*']) }}">
                  <a href="{{ route('admin.permissions.index') }}"><i class="fas fa-key"></i> Permission Management</a>
              </li>
          @endif

          @if (canAccess(['Role Index', 'Role Create', 'Role Update', 'Role Delete']))
              <li class="{{ setSidebarActive(['admin.roles.*']) }}">
                  <a href="{{ route('admin.roles.index') }}"><i class="fas fa-user-shield"></i> Roles Management</a>
              </li>
          @endif

          @if (canAccess(['User Index', 'User Create', 'User Update', 'User Delete']))
              <li class="{{ setSidebarActive(['admin.users.*']) }}">
                  <a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> User Management</a>
              </li>
          @endif

          @if (canAccess(['Review Index']))
              <li class="{{ setSidebarActive(['admin.reviews.*']) }}">
                  <a href="{{ route('admin.reviews.index') }}"><i class="fas fa-star"></i> Review Management</a>
              </li>
          @endif
      </ul>
  </nav>
