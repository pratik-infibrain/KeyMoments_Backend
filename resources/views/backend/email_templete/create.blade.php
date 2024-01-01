@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Email Templete</h1>
               	<div class="right_box">
					<a href="{{route('list.email_templete')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($email_templete))
									Edit Email Templete
								@else
									Add Email Templete
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($email_templete)){{ route('edit.email_templete',array('id'=>$email_templete->id)) }} @else{{ route('add.email_templete') }}@endif" id="email_templete-form" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="website_id">Website<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('website_id')) bad @endif">
										<select id="example-dropUp" multiple="multiple" name="website_id[]" class="form-control">
											@foreach($website_list as $website_list_val) 
											<option value="{{$website_list_val->id}}"  
												@if(count(old('website_id')) != "")
													@if (in_array($website_list_val->id, Input::old('website_id')))
														selected
													@endif
												@elseif(in_array($website_list_val->id,$website_selecteds))  
													selected
												@endif
													>{{$website_list_val->name}} 
												</option>
											@endforeach 
									</select> 
									@if ($errors->has('website_id')) <div class="errors_msg" style="padding-top:5px;">{{ $errors->first('website_id') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="name">Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('name')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('name', isset($email_templete) ? $email_templete->name : null) }}}" name="name" id="name">
										@if ($errors->has('name')) <div class="errors_msg">{{ $errors->first('name') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="description">Description<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('Description')) bad @endif">
										<textarea  placeholder="" class="form-control ckeditor" name="description" id="description">{{{ Input::old('description', isset($email_templete) ? $email_templete->description : null) }}}</textarea>
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
