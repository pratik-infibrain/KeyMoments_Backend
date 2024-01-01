@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Enum</h1>
               	<div class="right_box">
					<a href="{{route('list.enum')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($enum))
									Edit Enum
								@else
									Add Enum
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($enum)){{ route('edit.enum',array('id'=>$enum->id)) }} @else{{ route('add.enum') }}@endif" id="role-form" enctype="multipart/form-data" onsubmit="return enumvalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="enumname">Enum Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('enumname')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('enumname', isset($enum) ? $enum->enumname : null) }}}" name="enumname" id="enumname">
										<span class="error" id="errorenumname"></span>
										@if ($errors->has('enumname')) <div class="errors_msg">{{ $errors->first('enumname') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="enumvalue">Enum Value<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('enumvalue')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('enumvalue', isset($enum) ? $enum->enumvalue : null) }}}" name="enumvalue" id="enumvalue" >
										<span class="error" id="errorenumvalue"></span>
										@if ($errors->has('enumvalue')) <div class="errors_msg">{{ $errors->first('enumvalue') }}</div>@endif
									</div>
								</div>	
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="parentname">Parent Name</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('parentname')) bad @endif">

										<select name="parentname" id="parentname" class="form-control" onchange="return getparentvalue(this.value);">
											<option value="" data-values="0.00">Select Parent Name</option>
											<?php
											if($enumnamelist):
												foreach($enumnamelist as $enumlst):
													$select = '';
													if(isset($enum) && ($enum->parentname == $enumlst->id)):
														$select = ' selected';
												    elseif(Input::old('parentname') ==  $enumlst->id):
														$select = ' selected';
													endif;	
													?>
													<option value="<?php echo $enumlst->id;?>" <?php echo $select;?> data-values="<?php echo $enumlst->enumvalue;?>"><?php echo $enumlst->enumname;?></option>
													<?php
												endforeach;	
											endif;	
											?>
										</select>	

										<span class="error" id="errorparentname"></span>
										@if ($errors->has('parentname')) <div class="errors_msg">{{ $errors->first('parentname') }}</div>@endif
									</div>
								</div>		
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="parentvalue">Parent Value</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('parentvalue')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('parentvalue', isset($enum) ? $enum->parentvalue : null) }}}" name="parentvalue" id="parentvalue" >
										<span class="error" id="errorparentvalue"></span>
										@if ($errors->has('parentvalue')) <div class="errors_msg">{{ $errors->first('parentvalue') }}</div>@endif
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
