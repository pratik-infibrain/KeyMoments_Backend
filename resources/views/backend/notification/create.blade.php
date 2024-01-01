@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Notification</h1>
               	<div class="right_box">
					<a href="{{route('list.notification')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($notification))
									Edit notification
								@else
									Add notification
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($notification)){{ route('edit.notification',array('id'=>$notification->id)) }} @else{{ route('add.notification') }}@endif" id="role-form" enctype="multipart/form-data" onsubmit="return notificationvalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="notification_title">Notification Title<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('notification_title')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('notification_name', isset($notification) ? $notification->notification_title : null) }}}" name="notification_title" id="notification_title">	
										
										<span class="error" id="errornotificationtitle"></span>
										@if ($errors->has('notification_title')) <div class="errors_msg">{{ $errors->first('notification_title') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="notification_content">	Notification Content<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('notification_content')) bad @endif">
										<textarea id="notification_content" name="notification_content" rows="4" cols="50" class="form-control">{{{ Input::old('notification_content', isset($notification) ? $notification->notification_content : null) }}}</textarea>

										<span class="error" id="errornotificationcontent"></span>
										@if ($errors->has('notification_content')) <div class="errors_msg">{{ $errors->first('notification_content') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="notification_img">	Notification Image</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('notification_img')) bad @endif">
										<input type="file"id="notification_img" name="notification_img"accept="image/png, image/jpeg" class="form-control">
										<input type="hidden" name="hiddenimage" id="hiddenimage" value="@if(isset($notification) && $notification->notification_img !=""){{{Yes}}}@else{{{No}}}@endif">
										
										<span class="error" id="errornotificationimage"></span>
										@if ($errors->has('notification_img')) <div class="errors_msg">{{ $errors->first('notification_img') }}</div>@endif
										@if(isset($notification)  && $notification->notification_img !="")
											<br><img src="{{url('/')}}/public/uploads/notification/{{$notification->notification_img}}" width="80px">
										@endif
									</div>
								</div>
								
								
								
															
							</div>
							<div class="card-footer row">
								<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for=""></label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input type="hidden" name="myaction" id="myaction" value="@if(isset($notification)){{{edit}}}@endif">
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
