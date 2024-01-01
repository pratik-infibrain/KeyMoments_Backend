@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Promotion</h1>
               	<div class="right_box">
					<a href="{{route('list.promotion')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($promotion))
									Edit promotion
								@else
									Add promotion
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($promotion)){{ route('edit.promotion',array('id'=>$promotion->id)) }} @else{{ route('add.promotion') }}@endif" id="role-form" enctype="multipart/form-data" onsubmit="return promotionvalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="promotion_code">Promotion Code<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('promotion_code')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('promotion_code', isset($promotion) ? $promotion->promotion_code : null) }}}" name="promotion_code" id="promotion_code">	
										<span class="error" id="errorpromotioncode"></span>
										@if ($errors->has('promotion_code')) <div class="errors_msg">{{ $errors->first('promotion_code') }}</div>@endif
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="valid_form_date">Valid From Date<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('valid_form_date')) bad @endif">
										<input type="text" placeholder="" class="form-control datepicker_field" value="{{{ Input::old('valid_form_date', isset($promotion) ? date('d/m/Y',strtotime($promotion->valid_form_date)) : null) }}}" name="valid_form_date" id="valid_form_date">	
										<span class="error" id="errorpromotionfromdate"></span>
										@if ($errors->has('valid_form_date')) <div class="errors_msg">{{ $errors->first('valid_form_date') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="valid_to_date">Valid To Date<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('valid_to_date')) bad @endif">
										<input type="text" placeholder="" class="form-control datepicker_field" value="{{{ Input::old('valid_to_date', isset($promotion) ? date('d/m/Y',strtotime($promotion->valid_to_date)) : null) }}}" name="valid_to_date" id="valid_to_date">	
										<span class="error" id="errorpromotiontodate"></span>
										@if ($errors->has('valid_to_date')) <div class="errors_msg">{{ $errors->first('valid_to_date') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="type">Type<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('type')) bad @endif">
									<input type="radio" id="percentage" name="type" value="Percentage" @if($promotion->type=='Percentage'){{{checked}}} @endif >
									<label for="percentage">Percentage</label>
									<input type="radio" id="amount" name="type" value="Amount" @if($promotion->type=='Amount'){{{checked}}} @endif>
									<label for="amount">Amount </label>
									<input type="radio" id="free_trial_days" name="type" value="Free Trial Days" @if($promotion->type=='Free Trial Days'){{{checked}}} @endif>
									<label for="free_trial_days">Free Trial Days</label><br>
										<span class="error" id="errorpromotiontype"></span>
										@if ($errors->has('type')) <div class="errors_msg">{{ $errors->first('type') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="value_percentage">Value<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('value_percentage')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('value_percentage', isset($promotion) ? $promotion->value : null) }}}" name="value_percentage" id="value_percentage">	
										<span class="error" id="errorpromotionpercentage"></span>
										@if ($errors->has('value_percentage')) <div class="errors_msg">{{ $errors->first('value_percentage') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="promotion_content">	Description</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('promotion_content')) bad @endif">
										<textarea id="promotion_content" name="promotion_content" rows="4" cols="50" class="form-control">{{{ Input::old('promotion_content', isset($promotion) ? $promotion->promotion_content : null) }}}</textarea>
										<span class="error" id="errorpromotioncontent"></span>
										@if ($errors->has('promotion_content')) <div class="errors_msg">{{ $errors->first('promotion_content') }}</div>@endif
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
