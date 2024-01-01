<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>{{Config::get('constants.SITE_NAME')}}</title>

  <link rel="stylesheet" href="{{ asset('/public/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/bower_components/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/bower_components/admin-lte/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/bower_components/admin-lte/style.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/admin/css/custom.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <div class="">
    <div class="content-header">
      <div class="container-fluid">
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body p-0">
 
                    <div class="login-box-body">
                      <img src="{{url('/') .'/' }}public/admin/img/logo.jpg" alt="Key Moments" class="brand-image loginlogo">

						@if (count($errors) > 0)
					        <ul class="errors">
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    @endif
                      <form action="{{ route('auth.login') }}" method="post" onsubmit="return fun_submit_login();">
                      	{!! csrf_field() !!}
                        <div class="form-group has-feedback">
                          <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                          <span id="email_error_msg" class="show_msg"></span>
                          <span id="erroremail" class="error"></span>
                        </div>
                        <div class="form-group has-feedback">
                          <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                          <span id="password_error_msg" class="show_msg"></span>
                          <span id="errorpassword" class="error"></span>
                        </div>
                        <div class="form-group has-feedback">
                          
                          <a href="{{ route('auth.resetpassword') }}">Forgot Password ?</a>
                        </div>
                        <div class="form-group has-feedback">
                        	 
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                      </form>

                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/dist/js/adminlte.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/dist/js/demo.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('/public/bower_components/admin-lte/dist/js/pages/dashboard2.js') }}"></script>
<style>
.login-box-body {
    margin: 20px;
}
.loginlogo {
    width: 300px;
    display: block;
    margin: 0px auto;
}
</style>
<script>
    function fun_submit_login(){
        $('.error').text('');
        var email = $('#email').val();
        var error = 'No';

        if(email.trim()=='')
        {
          $('#erroremail').text('Email is required.');
          $('#email').focus();
          var error = 'Yes';
        }
        else
        {
          var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/; 
          if(!regex.test(email)){
             $('#erroremail').text('Email is invalid.');
            $('#email').focus();
            var error = 'Yes';
          }
        }
        var password = $('#password').val();
        if(password.trim()=='')
        {
            $('#errorpassword').text('Password is required.');
            $('#password').focus();
            var error = 'Yes';
        }
        if(error == 'Yes'){
          return false;
        }
    }
</script>
</body>
</html>
