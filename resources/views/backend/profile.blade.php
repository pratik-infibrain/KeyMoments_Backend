@extends('layouts.adminapp')
@section('content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Profile</h3>
              </div>
            
              <form role="form" name="profilefrm" method="POST" action="{{ route('auth.profile') }}">
                  
                {!! csrf_field() !!}
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{ $user->name }}">
                    <?php if ($errors->has('name')) :?>
                    <span class="help-block">
                        <strong>{{$errors->first('name')}}</strong>
                    </span>
                    <?php endif;?>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="{{ $user->email }}">
                    <?php if ($errors->has('email')) :?>
                    <span class="help-block">
                        <strong>{{$errors->first('email')}}</strong>
                    </span>
                    <?php endif;?>
                  </div>
                </div>
              
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
@endsection
