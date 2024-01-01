@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Role Privilege</h1>
               	<div class="right_box">
					<!--<a href="{{route('list.enum')}}" title="{{trans('common.back')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>-->
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
								Role Privilege
							</h3>
						</div>
						<form class="form-horizontal" method="POST" action="{{ route('add.roleprivileges') }}" id="role-privilege-form" enctype="multipart/form-data" onsubmit="return permissionvaliadation();">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="card-body">
								<div class="form-group row">
									<div class="col-md-8 col-sm-8 col-xs-12  @if($errors->has('role')) bad @endif">
										<select name="role" id="role" class="form-control" onchange="return getpermission(this.value);">
											<option value="" data-values="0.00">Select Role</option>
											<?php
											if($rolelist):
												foreach($rolelist as $roles):
													?>
													<option value="<?php echo $roles->id;?>" <?php echo $select;?> data-values="<?php echo $roles->name;?>"><?php echo $roles->name;?></option>
													<?php
												endforeach;	
											endif;	
											?>
										</select>	
										<span class="error" id="errorrole"></span>
									</div>
								</div>
								<div class="form-group row" id="tablecheck">

								</div>	
							</div>
							<div class="card-footer row">
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
