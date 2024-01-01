@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Keymoment</h1>
               	<div class="right_box">
					<a href="{{route('add.keymoment')}}" class="btn btn-success" title="{{trans('common.add_new_btn')}}"><i class="fa fa-plus"></i></a>
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
									<th>{{trans('Keymoment Title')}} </th>
									<!--<th>{{trans('Description')}} </th>
									<th>{{trans('Valid From Date')}} </th>
									<th>{{trans('Valid To Date')}} </th>
									<th>{{trans('Type')}} </th>
									<th>{{trans('Percentage')}} </th>
									<th>{{trans('Amount')}} </th>
									<th>{{trans('Free Trial')}} </th>-->
									<th width="15%">{{trans('common.action')}} </th>
		                        </tr>
		                      </thead>
		                      <tbody>
							  {{--*/ $total_row_counter = 1 /*--}}
								  @foreach($keymoment_list as $item)
									<tr>
										<td>{{$total_row_counter}}</td>
										<td>{{$item->title}}</td>
										<!--<td>{{$item->keymoment_content}}</td>
										<td>{{$item->valid_form_date}}</td>
										<td>{{$item->valid_to_date}}</td>
										<td>{{$item->type}}</td>
										<td>{{$item->value_percentage}}</td>
										<td>{{$item->value_amount}}</td>
										<td>{{$item->value_free_trial}}</td>-->
										
										<td class="td-actions">
											<a href="{{ route('edit.keymoment',array('id'=>$item->id)) }}" rel="tooltip" title="{{trans('common.edit_label')}}" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
											<a href="{{ route('delete.keymoment',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.delete_confirm_message')}}');" rel="tooltip" title="{{trans('common.delete_label')}}" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></a>
											@if($item->status==1) 
	                                    		<a href="{{ route('inactive.keymoment',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.inactive_confirm_message')}}');" rel="tooltip" title="{{trans('common.active_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-success"></i></a>
	                                        @else 
	                                        	<a href="{{ route('active.keymoment',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.active_confirm_message')}}');" rel="tooltip" title="{{trans('common.inactive_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-danger"></i></a>
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
