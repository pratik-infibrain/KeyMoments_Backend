@extends('layouts.adminapp')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               	<h1 class="m-0 text-dark left_box">Content Management</h1>
               	<div class="right_box">
					<a href="{{route('add.page')}}" class="btn btn-success" title="{{trans('common.add_new_btn')}}"><i class="fa fa-plus"></i></a>
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
									<th>{{trans('Menu Title')}} </th>
									<th>{{trans('Page Title')}} </th>
									<th>{{trans('Page URL')}} </th>
									<th width="15%">{{trans('common.action')}} </th>
		                        </tr>
		                      </thead>
		                      <tbody>
							  {{--*/ $total_row_counter = 1 /*--}}
								  @foreach($pagelist as $item)
									<tr>
										<td>{{$total_row_counter}}</td>
										<td>{{$item->menu_title}}</td>
										<td>{{$item->page_title}}</td>
										<td>{{$item->url_title}}</td>
										<td class="td-actions">
											<a href="{{ route('edit.page',array('id'=>$item->id)) }}" rel="tooltip" title="{{trans('common.edit_label')}}" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
											<a href="{{ route('delete.page',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.delete_confirm_message')}}');" rel="tooltip" title="{{trans('common.delete_label')}}" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></a>
											@if($item->status==1) 
	                                    		<a href="{{ route('inactive.page',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.inactive_confirm_message')}}');" rel="tooltip" title="{{trans('common.active_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-success"></i></a>
	                                        @else 
	                                        	<a href="{{ route('active.page',array('id'=>$item->id)) }}" onClick="return alertconfirm('{{trans('message.active_confirm_message')}}');" rel="tooltip" title="{{trans('common.inactive_label')}}" class="btn btn-default btn-xs "><i class="fa fa-circle text-danger"></i></a>
										   @endif
										</td>
									</tr>
									{{--*/ $total_row_counter++ /*--}}
								  @endforeach
							  </tbody>
			                </table>
			              </div>
						<!-- <div class="card-footer clearfix row">
			               <div class="col-md-4">
	                            &nbsp;&nbsp;&nbsp;{{trans('common.showing_label')}} {{{ (($pagelist->currentPage() - 1)*$pagelist->perPage()) + 1 }}} to @if(($pagelist->currentPage()*$pagelist->perPage()) >= $pagelist->total()) {{{$pagelist->total() }}} @else  {{{ $pagelist->currentPage()*$pagelist->perPage() }}}  @endif of {{{ $pagelist->total() }}} {{trans('common.items_label')}}
	                        </div>
	                        <div class="col-md-8">
                        		{!! $pagelist->render() !!}
                        	</div>	
                          </div>-->
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
