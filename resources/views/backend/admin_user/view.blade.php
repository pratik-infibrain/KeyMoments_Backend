@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Admin User Details</h1>
               	<div class="right_box">
					<a href="{{route('list.admin_user')}}" class="btn btn-success" title="{{trans('common.back')}}"><i class="fa fa-arrow-left"></i></a>
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
						<div class="card-body table-responsive">
			                <table class="table table-hover table-bordered">
			                	
			                  	<tr>
									<th>{{trans('Firstname')}}</th>
									<td>@if($admin_user->firstname !=""){{$admin_user->firstname}}@else-@endif</td>
								</tr>
								<tr>	
									<th>{{trans('Lastname')}}</th>
									<td>@if($admin_user->lastname !=""){{$admin_user->lastname}}@else-@endif</td>
								</tr>
								<tr>	
									<th>{{trans('Email')}}</th>
									<td>@if($admin_user->email !=""){{$admin_user->email}}@else-@endif</td>
								</tr>
								<tr>	
									<th>{{trans('Phone')}}</th>
									<td>@if($admin_user->phone !=""){{$admin_user->phone}}@else-@endif</td>
								</tr>
								<tr>	
									<th>{{trans('Role')}}</th>
									<td>@if($admin_user->role_id !=""){{$admin_user->roledetails->name}}@else-@endif</td>
								</tr>
							</table>
			            </div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
