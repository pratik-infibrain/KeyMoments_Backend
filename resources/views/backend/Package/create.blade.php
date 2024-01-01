@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Package</h1>
               	<div class="right_box">
					<a href="{{route('list.Package')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($package))
									Edit Package
								@else
									Add Package
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($package)){{ route('edit.Package',array('id'=>$package->id)) }} @else{{ route('add.Package') }}@endif" id="role-form" enctype="multipart/form-data" onsubmit="return Packagevalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="package_name">Package Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('package_name')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('package_name', isset($package) ? $package->package_name : null) }}}" name="package_name" id="package_name">
										<span class="error" id="errorPackagename"></span>
										@if ($errors->has('package_name')) <div class="errors_msg">{{ $errors->first('package_name') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="package_price">Package Price<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('package_price')) bad @endif">
										<input type="number" placeholder="" class="form-control" value="{{{ Input::old('package_price', isset($package) ? $package->package_price : null) }}}" name="package_price" id="package_price" step="0.01" max="99999999" pattern='[0-9]+(\\.[0-9][0-9]?)?'>
										<span class="error" id="errorPackageprice"></span>
										@if ($errors->has('package_price')) <div class="errors_msg">{{ $errors->first('package_price') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="number_of_notes">Number of Notes<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('number_of_notes')) bad @endif">
										<input type="number" placeholder="" class="form-control" value="{{{ Input::old('number_of_notes', isset($package) ? $package->number_of_notes : null) }}}" name="number_of_notes" id="number_of_notes" step="1" min="1"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;">
										<span class="error" id="errorPackagenotes"></span>
										@if ($errors->has('number_of_notes')) <div class="errors_msg">{{ $errors->first('number_of_notes') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="number_of_photos">Number of Photos<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12 @if($errors->has('number_of_photos')) bad @endif">
										<input type="number" placeholder="" class="form-control" value="{{{ Input::old('number_of_photos', isset($package) ? $package->number_of_photos : null) }}}" name="number_of_photos" id="number_of_photos" step="1" min="1"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;">
										<span class="error" id="errorPackagephotos"></span>
										@if ($errors->has('number_of_photos')) <div class="errors_msg">{{ $errors->first('number_of_photos') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="number_of_videos">Number of Videos<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12 @if($errors->has('number_of_videos')) bad @endif">
										<input type="number" placeholder="" class="form-control" value="{{{ Input::old('number_of_videos', isset($package) ? $package->number_of_videos : null) }}}" name="number_of_videos" id="number_of_videos" step="1" min="1"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;">
										<span class="error" id="errorPackagevideos"></span>
										@if ($errors->has('number_of_videos')) <div class="errors_msg">{{ $errors->first('number_of_videos') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="data_limit">Data Limit<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('data_limit')) bad @endif">
										<input type="text" placeholder="" class="form-control" value="{{{ Input::old('data_limit', isset($package) ? $package->data_limit : null) }}}" name="data_limit" id="data_limit" maxlength="11">
										<span class="error" id="errorPackagelimit"></span>
										@if ($errors->has('data_limit')) <div class="errors_msg">{{ $errors->first('data_limit') }}</div>@endif
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
