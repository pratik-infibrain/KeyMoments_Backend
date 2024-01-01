@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Content Management</h1>
               	<div class="right_box">
					<a href="{{route('list.page')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($page))
									Edit Page Content
								@else
									Add Page Content
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($page)){{ route('edit.page',array('id'=>$page->id)) }} @else{{ route('add.page') }}@endif" id="page-form" enctype="multipart/form-data" onsubmit="return pagevlidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="page_title">Page Title<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('page_title')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('page_title', isset($page) ? $page->page_title : null) }}}" name="page_title" id="page_title">
										@if ($errors->has('page_title')) <div class="errors_msg">{{ $errors->first('page_title') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="menu_title">Menu Title<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('menu_title')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('menu_title', isset($page) ? $page->menu_title : null) }}}" name="menu_title" id="menu_title">
										@if ($errors->has('menu_title')) <div class="errors_msg">{{ $errors->first('menu_title') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="description">Description<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('description')) bad @endif">
										<textarea  placeholder="" class="ckeditor form-control" name="description" id="description">{{{ Input::old('description', isset($page) ? $page->description : null) }}}</textarea>
										@if ($errors->has('description')) <div class="errors_msg">{{ $errors->first('description') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="meta_keyword">Meta Keyword</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('meta_keyword')) bad @endif">
										<textarea  placeholder="" class="form-control" maxlength="255" name="meta_keyword" id="meta_keyword">{{{ Input::old('meta_keyword', isset($page) ? $page->meta_keyword : null) }}}</textarea>
										@if ($errors->has('meta_keyword')) <div class="errors_msg">{{ $errors->first('meta_keyword') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for="meta_description">Meta Description</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('meta_description')) bad @endif">
										<textarea  placeholder="" class="form-control" name="meta_description" id="meta_description">{{{ Input::old('meta_description', isset($page) ? $page->meta_description : null) }}}</textarea>
										@if ($errors->has('meta_description')) <div class="errors_msg">{{ $errors->first('meta_description') }}</div>@endif
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
