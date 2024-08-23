<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p> 
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('admin/category') }}"
                class="nav-link {{ (request()->is('admin/category')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Category</p> 
            </a>
        </li>
         <!-- Admision Management -->
      
        <!-- Carrer Management -->
       
        <!-- Master Module Management -->
      
        <!-- Content Management -->
        
       
       
       
        <li class="nav-item">
            <a class="nav-link" href="javascript:void(0)"
                onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Logout
            </a>
        </li>
    </ul>
</nav>
