@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Keymoment</h1>
               	<div class="right_box">
					<a href="{{route('list.keymoment')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($keymoment))
									Edit keymoment
								@else
									Add keymoment
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($keymoment)){{ route('edit.keymoment',array('id'=>$keymoment->id)) }} @else{{ route('add.keymoment') }}@endif" id="role-form" enctype="multipart/form-data" onsubmit="return keymomentvalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="title">Keymoment Title<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('title')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('title', isset($keymoment) ? $keymoment->title : null) }}}" name="title" id="title">	
										<span class="error" id="errorkeymomentcode"></span>
										@if ($errors->has('title')) <div class="errors_msg">{{ $errors->first('title') }}</div>@endif
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
