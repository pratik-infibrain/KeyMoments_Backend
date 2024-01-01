@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Extra</h1>
               	<div class="right_box">
					<a href="{{route('list.extra')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($extra))
									Edit Extra
								@else
									Add Extra
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($extra)){{ route('edit.extra',array('id'=>$extra->id)) }} @else{{ route('add.extra') }}@endif" id="extra-form" enctype="multipart/form-data">
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
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('name', isset($extra) ? $extra->name : null) }}}" name="name" id="name">
										@if ($errors->has('name')) <div class="errors_msg">{{ $errors->first('name') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="description">Description</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('Description')) bad @endif">
										<textarea  placeholder="" class="form-control" name="description" id="description">{{{ Input::old('description', isset($extra) ? $extra->description : null) }}}</textarea>
										@if ($errors->has('description')) <div class="errors_msg">{{ $errors->first('description') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="more_info">More Info</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('more_info')) bad @endif">
										<textarea  placeholder="" class="ckeditor form-control" name="more_info" id="more_info">{{{ Input::old('more_info', isset($extra) ? $extra->more_info : null) }}}</textarea>
										@if ($errors->has('more_info')) <div class="errors_msg">{{ $errors->first('more_info') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="image">Image<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('image')) bad @endif">
										<input type="file" class="form-control" name="image" id="image">
										@if ($errors->has('image')) <div class="errors_msg">{{ $errors->first('image') }}</div>@endif
										<p>Image should be 125px W X 150px H. Only .jpeg, .jpg, .bmp, .png or .gif files are allowed.</p>
										@if(isset($extra))
											@if($extra->image!='')
	                                            {{--*/ $extra_image = $extra_attachment.$extra->image /*--}}
	                                            @if(file_exists($extra_image))
												<p><img src="{{url('/') .'/'.$extra_image}}" alt="" height="80" width="120"></p>
	                                            @endif
											@endif
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="icon">Icon<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('icon')) bad @endif">
										<input type="file" class="form-control" name="icon" id="icon">
										@if ($errors->has('icon')) <div class="errors_msg">{{ $errors->first('icon') }}</div>@endif
										<p>Only .jpeg, .jpg, .bmp, .png or .gif files are allowed.</p>
										@if(isset($extra))
											@if($extra->icon!='')
	                                            {{--*/ $extra_icon = $extra_attachment.$extra->icon /*--}}
	                                            @if(file_exists($extra_icon))
												<p><img src="{{url('/') .'/'.$extra_icon}}" alt="" height="80" width="120"></p>
	                                            @endif
											@endif
										@endif
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
