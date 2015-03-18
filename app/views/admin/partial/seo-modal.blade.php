	<div class="modal fade" id="seoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">SEO <span class="glyphicon glyphicon-pencil"></span></h4>
				</div>
				
				{{Form::open(array('class'=>'form-horizontal') )}}
				<div class="modal-body">
					@include('admin.partial.seo-fields')
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-lg bt-seo-save">
						Salvar SEO
						<span class="seo-save-loader"></span>
					</button>
				</div>
				{{Form::close()}}
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
