 <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
    
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          <span class="badge badge-warning navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        
          <div class="dropdown-divider"></div>
          <a href="{{ route('edit.admin_user',array('id'=>Auth::user()->id)) }}" class="dropdown-item">
            <i class="fas fa-user"></i> Edit Profile 
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{route('admin.changepassword')}}" class="dropdown-item"> 
            <i class="fas fa-unlock-alt"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ url('/logout') }}" class="dropdown-item">
            <i class="fas fa-sign-out"></i> Logout
          </a>
          <div class="dropdown-divider"></div>
          
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->