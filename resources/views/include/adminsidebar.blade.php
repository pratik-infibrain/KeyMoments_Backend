<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/backend') }}" class="brand-link">
      <img src="{{url('/') .'/' }}public/admin/img/logo.jpg" alt="Key Moments Logo" class="brand-image"
           style="opacity: .8">
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          {{--*/ $admin_profile_image = '' /*--}}
          @if(Auth::user()->image != '')
          {{--*/ $admin_profile_image = $admin_user_profile_image.Auth::user()->image /*--}}
          @endif
          @if(file_exists($admin_profile_image))
          <img src="{{url('/') .'/'.$admin_profile_image}}"  alt="">
          @else
          <img src="{{url('/') .'/' }}public/images/user_thunb.png" alt="">
          @endif
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ url('/backend') }}" class="nav-link  @if($page_condition == 'dashboard_page') {!! active !!} @endif">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          {{--*/ $modulelistmain = (new \App\Helpers\Helper)->getmodule() /*--}}
          {{--*/ $modulegetrolepermision = (new \App\Helpers\Helper)->getrolepermision() /*--}}
          
          @if($modulelistmain)
            @foreach($modulelistmain as $module)
              <li class="nav-item" @if(!in_array($module->id,$modulegetrolepermision))style="display:none;"@endif>
                <a href="{{ route($module->moduleurl)}}" class="nav-link @if($page_condition == $module->pagename) {!! active !!} @endif">
                  <i class="nav-icon far fa-image"></i><p>{{$module->name}}</p>
                </a>
              </li>
            @endforeach
          @endif
          <li class="nav-item">
                <a href="{{ route('list.keymoment')}}" class="nav-link @if($page_condition == 'keymoment_page') {!! active !!} @endif">
                  <i class="nav-icon far fa-image"></i><p>Manage Keymoments</p>
                </a>
              </li>
         <li class="nav-item">
                <a href="{{ route('edit.apisetting')}}" class="nav-link @if($page_condition == 'apisetting_page') {!! active !!} @endif">
                  <i class="nav-icon far fa-image"></i><p>APP Setting</p>
                </a>
              </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>