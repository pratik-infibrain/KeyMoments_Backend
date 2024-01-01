@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Settings</h1>
               	<div class="right_box">
					
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
								Edit Settings
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('edit.setting') }}" id="setting-form" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="mail_server">Mail Server<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('mail_server')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('mail_server', isset($setting) ? $setting->mail_server : null) }}}" name="mail_server" id="mail_server">
										@if ($errors->has('mail_server')) <div class="errors_msg">{{ $errors->first('mail_server') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="authentication_email">Authentication Email<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('authentication_email')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('authentication_email', isset($setting) ? $setting->authentication_email : null) }}}" name="authentication_email" id="authentication_email">
										@if ($errors->has('authentication_email')) <div class="errors_msg">{{ $errors->first('authentication_email') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="authentication_password">Authentication Password<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('authentication_password')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('authentication_password', isset($setting) ? $setting->authentication_password : null) }}}" name="authentication_password" id="authentication_password">
										@if ($errors->has('authentication_password')) <div class="errors_msg">{{ $errors->first('authentication_password') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="port">Port<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('port')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('port', isset($setting) ? $setting->port : null) }}}" name="port" id="port">
										@if ($errors->has('port')) <div class="errors_msg">{{ $errors->first('port') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="amendment_price ">Amendment Price<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('amendment_price ')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('amendment_price ', isset($setting) ? $setting->amendment_price  : null) }}}" name="amendment_price " id="amendment_price ">
										@if ($errors->has('amendment_price ')) <div class="errors_msg">{{ $errors->first('amendment_price ') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="outside_car_valet">Outside Car Valet<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('outside_car_valet')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('outside_car_valet', isset($setting) ? $setting->outside_car_valet : null) }}}" name="outside_car_valet" id="outside_car_valet">
										@if ($errors->has('outside_car_valet')) <div class="errors_msg">{{ $errors->first('outside_car_valet') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="outside_car_valet_4_x_4">Outside Car Valet (4 X 4)<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('outside_car_valet_4_x_4')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('outside_car_valet_4_x_4', isset($setting) ? $setting->outside_car_valet_4_x_4 : null) }}}" name="outside_car_valet_4_x_4" id="outside_car_valet_4_x_4">
										@if ($errors->has('outside_car_valet_4_x_4')) <div class="errors_msg">{{ $errors->first('outside_car_valet_4_x_4') }}</div>@endif
									</div>
								</div>
							</div>
							<div class="card-footer row">
								<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for=""></label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input class="btn btn-sm btn-primary submit" name="save_exit" type="submit" value="{{trans('common.save_exit')}}">
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
