@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Role Privilege</h1>
               	<div class="right_box">
					<a href="{{route('add.roleprivileges')}}" class="btn btn-success" title="{{trans('common.add_new_btn')}}"><i class="fa fa-plus"></i></a>
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
			                <table class="table table-hover table-bordered" id="example1">
			                	<thead> 
			                  <tr>
		                          <th>{{trans('common.number')}}</th>
									<th>{{trans('Role')}} </th>
									<th>{{trans('Modules')}} </th>
		                        </tr>
		                      </thead>
		                      <tbody>
							  {{--*/ $total_row_counter = 1 /*--}}
								  @foreach($roleprivilege_list as $item)
									<tr>
										<td>{{$total_row_counter}}</td>
										<td>{{$item->roledetails->name}}</td>
										<td>{{$item->modulesdetails}}</td>
									</tr>
									{{--*/ $total_row_counter++ /*--}}
								  @endforeach
							  </tbody>
			                </table>
			              </div>
			             
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
