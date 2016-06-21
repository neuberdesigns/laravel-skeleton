	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Listagem</h3>

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
					<div class="dataTables_length">
						<label>Exibir
							<select id="pagination-itens-perpage" aria-controls="" class="form-control input-sm">
								<option value="10"	{{$perpage==10?'selected':''}}>10</option>
								<option value="25"	{{$perpage==25?'selected':''}}>25</option>
								<option value="50"	{{$perpage==50?'selected':''}}>50</option>
								<option value="100"	{{$perpage==100?'selected':''}}>100</option>
							</select> itens
						</label>
					</div>
				</div>
				<div class="col-sm-6">
					<div id="example1_filter" class="dataTables_filter">
						{{Form::open(array('method'=>'get', 'url'=>BaseAdminController::urlToSearch($controllerSegment)))}}
							<label>Busca:
								{{Form::input('search', 'term', Input::get('term'), array('class'=>'form-control input-sm', 'aria-controls'=>'search'))}}
							</label>
						{{Form::close()}}
					</div>
				</div>
			</div>

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>{{OrderLink::make('Name', 'name')}}</th>
						<th>{{OrderLink::make('Email', 'email')}}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				@if(empty($list))
					<tr>
						<td colspan="99" class="text-left">Nenhum resultado foi encontrado</td>
					</tr>
				@else
					@foreach( $list as $k=>$row )
					<tr>
						<td>{{$row->name}}</td>
						<td>{{$row->email}}</td>
						<td>
							@include('admin/partial/list-actions')
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
					<tr>
						<th>{{OrderLink::make('Name', 'name')}}</th>
						<th>{{OrderLink::make('Email', 'email')}}</th>
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
						Exibindo de <b>{{$paginator->getFrom()}} a {{$paginator->getTo()}}</b> de {{$paginator->getTotal()}} registros
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
