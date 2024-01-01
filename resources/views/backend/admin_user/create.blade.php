@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Admin User</h1>
               	<div class="right_box">
					<a href="{{route('list.admin_user')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
				</div>
            </div>
         </div>
      </div>
   </div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">
								@if(isset($admin_user))
									Edit Profile
								@else
									Add Admin User
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($admin_user)){{ route('edit.admin_user',array('id'=>$admin_user->id)) }} @else{{ route('add.admin_user') }}@endif" id="admin_user-form" enctype="multipart/form-data" onsubmit="return uservlidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<!--<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="name">Username<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('name')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('name', isset($admin_user) ? $admin_user->name : null) }}}" name="name" id="name">
										<span class="error" id="errorname"></span>
										@if ($errors->has('name')) <div class="errors_msg">{{ $errors->first('name') }}</div>@endif
									</div>
								</div>-->

								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="firstname">First Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('firstname')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('firstname', isset($admin_user) ? $admin_user->firstname : null) }}}" name="firstname" id="firstname">
										<span class="error" id="errorfirstname"></span>
										@if ($errors->has('firstname')) <div class="errors_msg">{{ $errors->first('firstname') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="lastname">Last Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('lastname')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('lastname', isset($admin_user) ? $admin_user->lastname : null) }}}" name="lastname" id="lastname">
										<span class="error" id="errorlastname"></span>
										@if ($errors->has('lastname')) <div class="errors_msg">{{ $errors->first('lastname') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="phone">Phone<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('phone')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('phone', isset($admin_user) ? $admin_user->phone : null) }}}" name="phone" id="phone">
										<span class="error" id="errorphone"></span>
										<span class="error" id="errorphonenumeric" style="color: red; display: none">* Input digits (0 - 9)</span>
										@if ($errors->has('phone')) <div class="errors_msg">{{ $errors->first('phone') }}</div>@endif
									</div>
								</div>								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="email">Email<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('email')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('email', isset($admin_user) ? $admin_user->email : null) }}}" name="email" id="email" @if(isset($admin_user)) {{{readonly}}} @endif>
										<span class="error" id="erroremail"></span>
										@if ($errors->has('email')) <div class="errors_msg">{{ $errors->first('email') }}</div>@endif
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="role_id">Role<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('role_id')) bad @endif">
										<select id="role_id" name="role_id" class="form-control">
		                                    <option value="">Select Role</option>
		                                        @foreach($role_list as $role_list_val)
		                                        <option value="{{$role_list_val->id}}"
		                                            @if(Input::old('role_id')!="")
		                                                    @if($role_list_val->id==Input::old('role_id'))
		                                                    selected="selected" @endif
		                                                    @elseif(isset($admin_user))
		                                                    @if($role_list_val->id==$admin_user->role_id)
		                                                    selected="selected" @endif
		                                                    @endif
		                                            >{{strlen($role_list_val->name) > 50 ? substr($role_list_val->name,0,50)."..." : $role_list_val->name}}</option>
		                                        @endforeach
		                                </select> 
		                                <span class="error" id="errorrole_id"></span>
										@if ($errors->has('role_id')) <div class="errors_msg">{{ $errors->first('role_id') }}</div>@endif
									</div>
								</div>
								
								@if(!isset($admin_user))
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="password">Password<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('password')) bad @endif">
										<input type="password" placeholder="" maxlength="255" class="form-control" value="" name="password" id="password">
										 <span class="error" id="errorpassword"></span>
										@if ($errors->has('password')) <div class="errors_msg">{{ $errors->first('password') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="cpassword">Confirm Password<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('password')) bad @endif">
										<input type="password" placeholder="" maxlength="255" class="form-control" value="" name="cpassword" id="cpassword">
										 <span class="error" id="errorcpassword"></span>
										@if ($errors->has('cpassword')) <div class="errors_msg">{{ $errors->first('cpassword') }}</div>@endif
									</div>
								</div>
								@endif
								<!--<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="image">Image<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('image')) bad @endif">
										<input type="file" class="form-control" name="image" id="image">
										@if ($errors->has('image')) <div class="errors_msg">{{ $errors->first('image') }}</div>@endif
										<p>Only .png, .jpg, .jpeg files are allowed</p>
										@if(isset($admin_user))
											@if($admin_user->image!='')
	                                            {{--*/ $admin_user_image = $admin_user_attachment.$admin_user->image /*--}}
	                                            @if(file_exists($admin_user_image))
												<p><img src="{{url('/') .'/'.$admin_user_image}}" alt="" height="80" width="120"></p>
	                                            @endif
											@endif
										@endif
									</div>
								</div>-->
							</div>
							<div class="card-footer row">
								<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for=""></label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input class="btn btn-sm btn-primary submit" name="save_exit" type="submit" value="{{trans('common.save_exit')}}">
									<input type="hidden" name="myaction" id="myaction" value="@if(isset($admin_user)){{{edit}}}@endif">
									<input class="btn btn-sm btn-primary submit" name="save" type="submit" value="{{trans('common.save')}}">
									<a href="{{URL::full()}}" class="btn btn-sm btn-secondary cancel">{{trans('common.cancel')}}</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
