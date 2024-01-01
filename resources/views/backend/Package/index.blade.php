@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Package</h1>
               	<div class="right_box">
					<a href="{{route('add.Package')}}" class="btn btn-success" title="{{trans('common.add_new_btn')}}"><i class="fa fa-plus"></i></a>
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
									<th>{{trans('Package Name')}} </th>
									<th>{{trans('Package Price')}} </th>
									<th>{{trans('Number of Notes')}} </th>
									<th>{{trans('Number of Photos')}} </th>
									<th>{{trans('Number of Videos')}} </th>
									<th>{{trans('Data Limit')}} </th>
									<th width="15%">{{trans('common.action')}} </th>
		                        </tr>
		                      </thead>
		                      <tbody>
							  {{--*/ $total_row_counter = 1 /*--}}
								  @foreach($Package_list as $item)
									<tr>
										<td>{{$total_row_counter}}</td>
										<td>{{$item->package_name}}</td>
										<td>{{$item->package_price}}</td>
										<td>{{$item->number_of_notes}}</td>
										<td>{{$item->number_of_photos}}</td>
										<td>{{$item->number_of_videos}}</td>
										<td>{{$item->data_limit}}</td>
										
										<td class="td-actions">
											<a href="{{ route('edit.Package',array('id'=>$item->id)) }}" rel="tooltip" title="{{trans('common.edit_label')}}" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
											@if($item->id !='1')
											<a href="{{ route('delete.Package',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.delete_confirm_message')}}');" rel="tooltip" title="{{trans('common.delete_label')}}" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></a>
											@endif
											
											@if($item->status==1) 
	                                    		<a href="{{ route('inactive.Package',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.inactive_confirm_message')}}');" rel="tooltip" title="{{trans('common.active_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-success"></i></a>
	                                        @else 
	                                        	<a href="{{ route('active.Package',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.active_confirm_message')}}');" rel="tooltip" title="{{trans('common.inactive_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-danger"></i></a>
										   @endif
										</td>
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
