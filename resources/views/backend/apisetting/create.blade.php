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
						<form class="form-horizontal" method="POST" action="{{ route('edit.apisetting') }}" id="setting-form" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="mail_server">Gift Soldier Video<span class="required"></span></label>
									<div class="col-md-8 col-sm-8 col-xs-12 ">
										<input type="file" id="giftsoldier_video" name="giftsoldier_video" accept="video/mp4,video/x-m4v,video/*">
										<span class="error" id="errorgiftsoldier_video"></span>
										@if ($errors->has('giftsoldier_video')) <div class="errors_msg">{{ $errors->first('giftsoldier_video') }}</div>@endif
										@if(isset($setting)  && $setting->giftsoldier_video != "")										
											<br><a id="pdf-file-view" href="@if(isset($setting) && $setting->giftsoldier_video != '' ){{url().$setting->giftsoldier_video}} @endif" target="_blank"> Video <i class="fa fa-view"></i></a>
										@endif
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
