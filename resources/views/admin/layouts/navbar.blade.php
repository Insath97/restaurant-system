 <nav class="navbar navbar-expand navbar-custom">
     <div class="container-fluid">
         <button class="btn btn-sm btn-primary-custom d-lg-none" id="sidebarToggle">
             <i class="fas fa-bars"></i>
         </button>
         <div class="navbar-collapse">
             <ul class="navbar-nav ms-auto">
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                         data-bs-toggle="dropdown">
                         <div class="user-profile">
                             <img src="img/user-avatar.jpg" alt="User Avatar">
                             <div class="user-profile-info">
                                 <h6>{{ Auth::guard('admin')->user()->name }}</h6>
                                 <p>Super Admin</p>
                             </div>
                         </div>
                     </a>
                     <ul class="dropdown-menu dropdown-menu-end">
                         <li><a class="dropdown-item" href="#profile"><i class="fas fa-user me-2"></i>
                                 Profile</a></li>
                         <li><a class="dropdown-item" href="#settings"><i class="fas fa-cog me-2"></i>
                                 Settings</a></li>
                         <li>
                             <hr class="dropdown-divider">
                         </li>
                         <li>
                             <form action="{{ route('admin.logout') }}" method="post">
                                 @csrf
                                 <a class="dropdown-item" href="#logout"
                                     onclick="event.preventDefault(); this.closest('form').submit();">
                                     <i class="fas fa-sign-out-alt me-2"></i>
                                     Logout
                                 </a>
                             </form>
                         </li>
                     </ul>
                 </li>
             </ul>
         </div>
     </div>
 </nav>
