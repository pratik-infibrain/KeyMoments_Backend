@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Email Templete</h1>
               	<div class="right_box">
					<a href="{{route('add.email_templete')}}" class="btn btn-success" title="{{trans('common.add_new_btn')}}"><i class="fa fa-plus"></i></a>
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
						<div class="card-body table-responsive p-0">
			                <table class="table table-hover table-bordered text-nowrap" id="custom_table">
			                	<thead> 
			                  <tr>
		                          <th>{{trans('common.number')}}</th>
									<th>{{trans('Name')}} </th>
									<th width="15%">{{trans('common.action')}} </th>
		                        </tr>
		                      </thead>
		                      <tbody>
							  {{--*/ $total_row_counter = 1 /*--}}
								  @foreach($email_templete_list as $item)
									<tr>
										<td>{{$total_row_counter}}</td>
										<td>{{$item->name}}</td>
										<td class="td-actions">
											<a href="{{ route('edit.email_templete',array('id'=>$item->id)) }}" rel="tooltip" title="{{trans('common.edit_label')}}" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
											<a href="{{ route('delete.email_templete',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.delete_confirm_message')}}');" rel="tooltip" title="{{trans('common.delete_label')}}" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></a>
											@if($item->status==1) 
	                                    		<a href="{{ route('inactive.email_templete',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.inactive_confirm_message')}}');" rel="tooltip" title="{{trans('common.active_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-success"></i></a>
	                                        @else 
	                                        	<a href="{{ route('active.email_templete',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.active_confirm_message')}}');" rel="tooltip" title="{{trans('common.inactive_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-danger"></i></a>
										   @endif
										</td>
									</tr>
									{{--*/ $total_row_counter++ /*--}}
								  @endforeach
							  </tbody>
			                </table>
			              </div>
			              <div class="card-footer clearfix row">
			               <div class="col-md-4">
	                            &nbsp;&nbsp;&nbsp;{{trans('common.showing_label')}} {{{ (($email_templete_list->currentPage() - 1)*$email_templete_list->perPage()) + 1 }}} to @if(($email_templete_list->currentPage()*$email_templete_list->perPage()) >= $email_templete_list->total()) {{{$email_templete_list->total() }}} @else  {{{ $email_templete_list->currentPage()*$email_templete_list->perPage() }}}  @endif of {{{ $email_templete_list->total() }}} {{trans('common.items_label')}}
	                        </div>
	                        <div class="col-md-8">
                        		{!! $email_templete_list->render() !!}
                        	</div>	
                          </div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
