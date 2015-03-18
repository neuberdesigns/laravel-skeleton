@if( isset($field) && !empty($field) )
	@if( !empty($model->$field) )
		<div class="preview-image-container">
			<div class="preview-image col-xs-12 col-md-4">	
				<div class="preview-image-inner">
					<div class="image-control pull-right">
						<a href="javascript:void(0)" class="bt-preview-image-delete" data-field="{{$field}}" data-id="{{$model->getKey()}}">Remover</a>
					</div>
					{{FileUpload::getTim( $model->$field , 300, 100, array('class'=>'img-responsive center-block img-polaroid') )}}
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	@endif 
@endif
