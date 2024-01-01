@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Enum</h1>
               	<div class="right_box">
					<a href="{{route('add.enum')}}" class="btn btn-success" title="{{trans('common.add_new_btn')}}"><i class="fa fa-plus"></i></a>
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
									<th>{{trans('Enum Name')}} </th>
									<th>{{trans('Enum Value')}} </th>
									<th>{{trans('Parent Name')}} </th>
									<th>{{trans('Parent Value')}} </th>
									<th width="15%">{{trans('common.action')}} </th>
		                        </tr>
		                      </thead>
		                      <tbody>
							  {{--*/ $total_row_counter = 1 /*--}}
								  @foreach($enum_list as $item)
									<tr>
										<td>{{$total_row_counter}}</td>
										<td>{{$item->enumname}}</td>
										<td>{{$item->enumvalue}}</td>
										<td>@if($item->parentdetails->enumname){{$item->parentdetails->enumname}}@else - @endif</td>
										<td>{{$item->parentvalue}}</td>
										<td class="td-actions">
											<a href="{{ route('edit.enum',array('id'=>$item->id)) }}" rel="tooltip" title="{{trans('common.edit_label')}}" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
											<a href="{{ route('delete.enum',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.delete_confirm_message')}}');" rel="tooltip" title="{{trans('common.delete_label')}}" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></a>
											@if($item->status==1) 
	                                    		<a href="{{ route('inactive.enum',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.inactive_confirm_message')}}');" rel="tooltip" title="{{trans('common.active_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-success"></i></a>
	                                        @else 
	                                        	<a href="{{ route('active.enum',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.active_confirm_message')}}');" rel="tooltip" title="{{trans('common.inactive_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-danger"></i></a>
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
