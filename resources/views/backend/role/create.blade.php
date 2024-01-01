@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Role</h1>
               	<div class="right_box">
					<a href="{{route('list.role')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($role))
									Edit Role
								@else
									Add Role
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($role)){{ route('edit.role',array('id'=>$role->id)) }} @else{{ route('add.role') }}@endif" id="role-form" enctype="multipart/form-data" onsubmit="return rolevalidation();"> 
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="name">Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('name')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('name', isset($role) ? $role->name : null) }}}" name="name" id="name">
										<span class="error" id="errorname"></span>
										@if ($errors->has('name')) <div class="errors_msg">{{ $errors->first('name') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="description">Description</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('Description')) bad @endif">
										<textarea  placeholder="" class="form-control" name="description" id="description" maxlength="255" >{{{ Input::old('description', isset($role) ? $role->description : null) }}}</textarea>
										@if ($errors->has('description')) <div class="errors_msg">{{ $errors->first('description') }}</div>@endif
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
