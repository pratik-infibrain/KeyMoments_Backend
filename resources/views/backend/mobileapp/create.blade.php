@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Mobile App User</h1>
               	<div class="right_box">
					<a href="{{route('list.mobileapp')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
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
								@if(isset($mobileapp))
									Edit Mobile App User
								@else
									Add Mobile App User
								@endif
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="@if(isset($mobileapp)){{ route('edit.mobileapp',array('id'=>$mobileapp->id)) }} @else{{ route('add.mobileapp') }}@endif" id="role-form" enctype="multipart/form-data" onsubmit="return mobileappvalidation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="email">Email<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('email')) bad @endif">
										<input type="email" placeholder="" maxlength="255" class="form-control" {{ isset($mobileapp) ? 'readonly' : '' }} value="{{{ Input::old('email', isset($mobileapp) ? $mobileapp->email : null) }}}" name="email" id="email">
										<span class="error" id="errormobileappemail"></span>
										@if ($errors->has('email')) <div class="errors_msg">{{ $errors->first('email') }}</div>@endif
									</div>
								</div>
								@if(!isset($mobileapp))
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="password">Password<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('password')) bad @endif">
										<input type="password" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('cpassword', isset($mobileapp) ? $mobileapp->password : null)}}}" name="password" id="password" step="0.01">
										<span class="error" id="errormobileapppass"></span>
										@if ($errors->has('password')) <div class="errors_msg">{{ $errors->first('password') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="cpassword">Confirm Password<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('cpassword')) bad @endif">
										<input type="password" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('cpassword', isset($mobileapp) ? $mobileapp->password : null)}}}" name="cpassword" id="cpassword" step="0.01">
										<span class="error" id="errormobileappcpass"></span>
										@if ($errors->has('cpassword')) <div class="errors_msg">{{ $errors->first('cpassword') }}</div>@endif
									</div>
								</div>
								@endif
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="full_name">Full Name<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('full_name')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('full_name', isset($mobileapp) ? $mobileapp->full_name : null) }}}" name="full_name" id="full_name" step="0.01">
										<span class="error" id="errormobileappfullname"></span>
										@if ($errors->has('full_name')) <div class="errors_msg">{{ $errors->first('full_name') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="	mobile_number">Mobile Number<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('	mobile_number')) bad @endif">
										<input type="number" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('mobile_number', isset($mobileapp) ? $mobileapp->mobile_number : null) }}}" name="mobile_number" id="mobile_number" step="0.01">
										<span class="error" id="errormobileappmno"></span>
										@if ($errors->has('mobile_number')) <div class="errors_msg">{{ $errors->first('mobile_number') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="	gender">Gender<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('	gender')) bad @endif">
									<input type="radio" id="male" name="gender" value="male" @if($mobileapp->gender=='male'){{{checked}}}@elseif(Input::old('gender') == 'male'){{{checked}}}@endif>
									<label for="male">Male</label>&nbsp;&nbsp;
									<input type="radio" id="female" name="gender" value="female" @if($mobileapp->gender=='female'){{{checked}}}@elseif(Input::old('gender') == 'female'){{{checked}}}@endif>
									<label for="female">Female</label><br>
										<span class="error" id="errormobileappgender"></span>
										@if ($errors->has('gender')) <div class="errors_msg">{{ $errors->first('gender') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="age">Age<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('age')) bad @endif">
									<input type="number" placeholder=""  class="form-control" value="{{{ Input::old('age', isset($mobileapp) ? $mobileapp->age : null) }}}" name="age" id="age" step="1" min="1" max="150" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" >
										<span class="error" id="errormobileappage"></span>
										@if ($errors->has('age')) <div class="errors_msg">{{ $errors->first('age') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="marital_status">Marital Status<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('marital_status')) bad @endif">
									<input type="radio" id="married" name="marital_status" value="Married" @if($mobileapp->marital_status=='Married'){{{checked}}}@elseif(Input::old('marital_status') == 'Married'){{{checked}}}@endif>
									<label for="married">Married</label>&nbsp;&nbsp;
									<input type="radio" id="Single" name="marital_status" value="Single" @if($mobileapp->marital_status=='Single'){{{checked}}}@elseif(Input::old('marital_status') == 'Single'){{{checked}}}@endif>
									<label for="Single">Single</label>&nbsp;&nbsp;
									<input type="radio" id="divorced" name="marital_status" value="Divorced" @if($mobileapp->marital_status=='Divorced'){{{checked}}}@elseif(Input::old('marital_status') == 'Divorced'){{{checked}}}@endif>
									<label for="divorced">Divorced</label><br>
										<span class="error" id="errormobileappmaritals"></span>
										@if ($errors->has('marital_status')) <div class="errors_msg">{{ $errors->first('marital_status') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="children">Children</label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('children')) bad @endif">
										<input type="number" placeholder="" class="form-control" value="{{{ Input::old('children', isset($mobileapp) ? $mobileapp->children : null) }}}" name="children" id="children" step="1" min="1" max="20" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;">
										<span class="error" id="errormobileappchildren"></span>
										@if ($errors->has('children')) <div class="errors_msg">{{ $errors->first('children') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="education">	Education<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('education')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('education', isset($mobileapp) ? $mobileapp->education : null) }}}" name="education" id="education" step="0.01">
										<span class="error" id="errormobileappeducation"></span>
										@if ($errors->has('education')) <div class="errors_msg">{{ $errors->first('education') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="	military_status">Military Status<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('military_status')) bad @endif">

									<input type="radio" id="yes" name="military_status" value="Yes" @if($mobileapp->military_status=='Yes'){{{checked}}}@elseif(Input::old('military_status') == 'Yes'){{{checked}}}@endif>
									<label for="yes">Yes</label>&nbsp;&nbsp;
									<input type="radio" id="no" name="military_status" value="No" @if($mobileapp->military_status=='No'){{{checked}}}@elseif(Input::old('military_status') == 'No'){{{checked}}}@endif>
									<label for="no">No</label><br>
										<span class="error" id="errormobileappmilitary"></span>
										@if ($errors->has('military_status')) <div class="errors_msg">{{ $errors->first('military_status') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="employment">Employment<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('employment')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('employment', isset($mobileapp) ? $mobileapp->employment : null) }}}" name="employment" id="employment" step="0.01">
										<span class="error" id="errormobileappemployment"></span>
										@if ($errors->has('employment')) <div class="errors_msg">{{ $errors->first('employment')}}</div>@endif
									</div>
								</div>
								{{-- */$i=1;/* --}}
								{{-- */$k=0;/* --}}
								@foreach ($userexicuterarray as $val)
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="list_of_executors">List of Executors {{$i}} </label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('list_of_executors')) bad @endif">
										<input type="text" placeholder="" maxlength="255" class="form-control" value="@if($val->name){{$val->name}}@elseif(Input::old('list_of_executors'))<?php echo Input::old('list_of_executors')[$k];?>@endif" name="list_of_executors[{{$val->id}}]" id="list_of_executors[{{$val->id}}]" step="0.01">
									</div>
								</div>
								{{-- */$i++;/* --}}
								{{-- */$k++;/* --}}
								@endforeach

								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="package">Package<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12 @if($errors->has('package')) bad @endif">
										<select name="package" id="package" class="form-control" onchange="return getparentvalue(this.value);">
											<option value="" data-values="0.00">Select Package</option>
											@if($Packagenamelist)
												{{-- */ $select = "";/* --}}
												@foreach($Packagenamelist as $pkglst)
													@if(isset($mobileapp) && ($mobileapp->package == $pkglst->id))
													{{-- */ $select = " selected";/* --}}
													@elseif(Input::old('package') == $pkglst->id)
													{{-- */ $select = " selected";/* --}}
													@endif

													<option value="{{$pkglst->id}}" {{$select}} data-values="{{$pkglst->package_name}}">{{ $pkglst->package_name}}</option>

												@endforeach;
											@endif;

										</select>
										<span class="error" id="errormobileapppname"></span>
										@if ($errors->has('package')) <div class="errors_msg">{{ $errors->first('package') }}</div>@endif
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
							<input type="hidden" id="act" name="act" value="{{ isset($mobileapp) ? 'edit' : 'create' }}">
						</form>
					</div>
					@if(isset($mobileapp) && $mobileapp->loginstatus == 0)
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">
									Change Password
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('edit.mobileapp.pass',array('id'=>$mobileapp->id)) }}" id="role-form" enctype="multipart/form-data" >
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
							<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="password">Password<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('password')) bad @endif">
										<input type="password" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('cpassword', isset($mobileapp) ? $mobileapp->password : null)}}}" name="password" id="password" step="0.01">
										<span class="error" id="errormobileapppass"></span>
										@if ($errors->has('password')) <div class="errors_msg">{{ $errors->first('password') }}</div>@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-2 col-sm-2 col-xs-12 col-form-label" for="cpassword">Confirm Password<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('cpassword')) bad @endif">
										<input type="password" placeholder="" maxlength="255" class="form-control" value="{{{ Input::old('cpassword', isset($mobileapp) ? $mobileapp->password : null)}}}" name="cpassword" id="cpassword" step="0.01">
										<span class="error" id="errormobileappcpass"></span>
										@if ($errors->has('cpassword')) <div class="errors_msg">{{ $errors->first('cpassword') }}</div>@endif
									</div>
								</div>
							</div>

							<div class="card-footer row">
								<label class="col-form-label col-md-2 col-sm-2 col-xs-12" for=""></label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<input class="btn btn-sm btn-primary submit" name="save" type="submit" value="{{trans('common.save')}}">
								</div>
							</div>
						</form>
					</div>
					@endif



				</div>
			</div>
		</div>
	</section>
</div>
@endsection
