<div class="dataTables_length">
	<label>{{trans('admin.display')}}
		<select id="pagination-itens-perpage" aria-controls="" class="form-control input-sm">
			<option value="10"	{{$perpage==10?'selected':''}}>10</option>
			<option value="25"	{{$perpage==25?'selected':''}}>25</option>
			<option value="50"	{{$perpage==50?'selected':''}}>50</option>
			<option value="100"	{{$perpage==100?'selected':''}}>100</option>
		</select> {{trans_choice('admin.entry', 0)}}
	</label>
</div>
