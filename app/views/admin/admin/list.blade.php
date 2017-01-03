	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">{{trans('admin.list')}}</h3>

			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">

			<div class="row">
				<div class="col-sm-6">
					@include('admin.partial.components.pagination-size-picker')
				</div>
				<div class="col-sm-6">
					@include('admin.partial.components.search')
				</div>
			</div>

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>{{OrderLink::make(trans('project.name'), 'name')}}</th>
						<th>{{OrderLink::make(trans('project.email'), 'email')}}</th>
						<th>{{OrderLink::make(trans('project.enabled'), 'enabled')}}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				@if(empty($list))
					<tr>
						<td colspan="99" class="text-left">{{trans('admin.no_results_available')}}</td>
					</tr>
				@else
					@foreach( $list as $k=>$row )
					<tr>
						<td>{{$row->name}}</td>
						<td>{{$row->email}}</td>
						<td>{{$row->enabled}}</td>
						<td>
							@include('admin.partial.components.list-actions')
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
					<tr>
						<th>{{OrderLink::make(trans('project.name'), 'name')}}</th>
						<th>{{OrderLink::make(trans('project.email'), 'email')}}</th>
						<th>{{OrderLink::make(trans('project.enabled'), 'enabled')}}</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<div class="row">
				<div class="col-sm-5">
					<div class="dataTables_info" role="status" aria-live="polite">
						{{trans('admin.showing')}} <b>{{$paginator->getFrom()}} {{trans('admin.to')}} {{$paginator->getTo()}}</b> {{trans('admin.of')}} {{$paginator->getTotal()}} {{trans_choice('admin.entry', $paginator->getTotal())}}
					</div>
				</div>
				<div class="col-sm-7">
					<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
						{{$pagination}}
					</div>
				</div>
			</div>
		</div>
		<!-- /.box-footer-->
	</div>
	<!-- /.box -->

