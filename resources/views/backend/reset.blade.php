@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Change Password</h1>
            </div>
         </div>
      </div>
   </div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<form class="form-horizontal"  method="POST" action="{{ route('admin.changepassword') }}" onsubmit="return changepasswordvalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="">&nbsp;</label>
								<!--<div class="col-md-8 col-sm-8 col-xs-12">
									@if (count($errors) > 0)
									<div class="alert alert-danger">
										<strong>Whoops!</strong> There were some problems with your input.<br><br>
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
									@endif
								</div>-->
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-2 col-xs-12" for="oldpassword">Old Password<span class="required">*</span></label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="password" class="form-control" name="oldpassword" placeholder="" id="oldpassword">
									<span class="error" id="erroroldpassword"></span>
									@if ($errors->has('oldpassword')) <div class="errors_msg">{{ $errors->first('oldpassword') }}</div>@endif
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-2 col-xs-12" for="password">New Password<span class="required">*</span></label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="password" class="form-control" name="password" placeholder="" id="password">
									<span class="error" id="errorpassword"></span>
									@if ($errors->has('password')) <div class="errors_msg">{{ $errors->first('password') }}</div>@endif
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-2 col-xs-12" for="password_confirmation">Retype New Password<span class="required">*</span></label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="">
									<span class="error" id="errorpassword_confirmation"></span>
									@if ($errors->has('password_confirmation')) <div class="errors_msg">{{ $errors->first('password_confirmation') }}</div>@endif
								</div>
							</div>
							</div>
							<div class="card-footer">
								<div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
									<input class="btn btn-sm btn-primary submit" name="save" type="submit" value="{{trans('common.update')}}">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
						<br>
						
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							
							<div class="ln_solid"></div>
							<div class="form-group">
								<div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
									<button type="submit" class="btn btn-primary">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
