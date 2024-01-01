@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Tutorial</h1>
               	<div class="right_box">
					<a href="{{route('list.tutorial')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($tutorial))
									Edit Tutorial
								@else
									Add Tutorial
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($tutorial)){{ route('edit.tutorial',array('id'=>$tutorial->id)) }} @else{{ route('add.tutorial') }}@endif" id="tutorial-form" enctype="multipart/form-data" onsubmit="return tutorialvalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="tutorialname">Tutorial Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('tutorialname')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('tutorialname', isset($tutorial) ? $tutorial->tutorialname : null) }}}" name="tutorialname" id="tutorialname">
										<span class="error" id="errortutorialname"></span>
										@if ($errors->has('tutorialname')) <div class="errors_msg">{{ $errors->first('tutorialname') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="tutorialvideo">Tutorial Video<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('tutorialvideo')) bad @endif">
										<input type="file" name="tutorialvideo" id="tutorialvideo" class="form-control">
										<input type="hidden" name="tutorialvideohidden" id="tutorialvideohidden" value="@if(isset($tutorial)  && $tutorial->tutorialvideo !=''){{{Yes}}}@else{{{No}}}@endif">
										<span class="error" id="errortutorialvideo"></span>
									@if ($errors->has('tutorialvideo')) <div class="errors_msg">{{ $errors->first('tutorialvideo') }}</div>@endif
									@if(isset($tutorial)  && $tutorial->tutorialvideo !="")
									<a href="{{url('/')}}/public/uploads/tutorial/{{$tutorial->tutorialvideo}}" target="_blank">{{$tutorial->tutorialvideo}}</a>
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
