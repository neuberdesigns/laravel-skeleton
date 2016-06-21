@if( isset($field) && !empty($field) )
	@if( !empty($model->$field) )
		<div class="preview-image-container">
			<div class="preview-image col-xs-12 col-sm-3">	
				<div class="preview-image-inner">
					<div class="image-control pull-right">
						<a href="" class="bt-preview-image-delete" data-field="{{$field}}" data-id="{{$model->getKey()}}">Remover</a>
					</div>
					{{FileUpload::make($model->$field)->getTim(400, 300, null, array('class'=>'img-responsive center-block img-polaroid', 'width'=>'100%'), false )}}
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	@endif 
@endif
