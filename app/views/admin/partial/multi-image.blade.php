<div class="col-md-2 margin-top margin-bottom">
	<div class="multi-image-list preview-image">	
		<div class="multi-image-inner preview-image-inner">
			<div class="image-control">
				<a href="javascript:void(0)" class="bt-multi-image-delete" data-id="{{$modelImage->getKey()}}">Remover</a>
			</div>
			{{FileUpload::getTim( $modelImage->image, 200, 200, array('class'=>'img-reponsive img-polaroid', 'width'=>'100%'), 1 )}}
		</div>
	</div>
</div>
