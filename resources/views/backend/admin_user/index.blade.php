@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Admin User</h1>
               	<div class="right_box">
					<a href="{{route('add.admin_user')}}" class="btn btn-success" title="{{trans('common.add_new_btn')}}"><i class="fa fa-plus"></i></a>
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
									<th>{{trans('Firstname')}} </th>
									<th>{{trans('Lastname')}} </th>
									<th> {{trans('Email')}} </th>
									<th> {{trans('Phone')}} </th>
									<th width="15%">{{trans('common.action')}} </th>
		                        </tr>
		                      </thead>
		                      <tbody>
							  {{--*/ $total_row_counter = 1 /*--}}
								  @foreach($admin_user_list as $item)
									<tr>
										<td>{{$total_row_counter}}</td>
										<td>@if($item->firstname !=""){{$item->firstname}}@else-@endif</td>
										<td>@if($item->lastname !=""){{$item->lastname}}@else-@endif</td>
										<td>@if($item->email !=""){{$item->email}}@else-@endif</td>
										<td>@if($item->phone !=""){{$item->phone}}@else-@endif</td>
										<td class="td-actions">
											<a href="{{ route('edit.admin_user',array('id'=>$item->id)) }}" rel="tooltip" title="{{trans('common.edit_label')}}" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
											<a href="{{ route('view.admin_user',array('id'=>$item->id)) }}" rel="tooltip" title="{{trans('common.view_label')}}" class="btn btn-warning btn-xs"><i class="fas fa-eye"></i></a>
											@if(Auth::user()->id != $item->id)
												<a href="{{ route('delete.admin_user',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.delete_confirm_message')}}');" rel="tooltip" title="{{trans('common.delete_label')}}" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></a>
											@if($item->status==1) 
	                                    	<a href="{{ route('inactive.admin_user',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.inactive_confirm_message')}}');" rel="tooltip" title="{{trans('common.active_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-success"></i></a>
	                                        @else 
	                                        <a href="{{ route('active.admin_user',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.active_confirm_message')}}');" rel="tooltip" title="{{trans('common.inactive_label')}}" class="btn btn-default btn-xs"><i class="fa fa-circle text-danger"></i></a>
										   @endif
										@endif
									</td>
								</tr>
								{{--*/ $total_row_counter++ /*--}}
								@endforeach
							  </tbody>
			                </table>
			              </div>
			              <!--<div class="card-footer clearfix row">
			               <div class="col-md-4">
	                            &nbsp;&nbsp;&nbsp;{{trans('common.showing_label')}} {{{ (($admin_user_list->currentPage() - 1)*$admin_user_list->perPage()) + 1 }}} to @if(($admin_user_list->currentPage()*$admin_user_list->perPage()) >= $admin_user_list->total()) {{{$admin_user_list->total() }}} @else  {{{ $admin_user_list->currentPage()*$admin_user_list->perPage() }}}  @endif of {{{ $admin_user_list->total() }}} {{trans('common.items_label')}}
	                        </div>
	                        <div class="col-md-8">
                        		{!! $admin_user_list->render() !!}
                        	</div>	
                          </div>-->
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
