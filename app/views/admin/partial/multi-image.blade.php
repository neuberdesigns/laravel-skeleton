<div class="multi-image-list">	
	<div class="multi-image-inner">
		<div class="image-control">
			<a href="javascript:void(0)" class="bt-multi-image-delete" data-id="{{$eventImage->id}}">Remover</a>
		</div>
		{{FileUpload::getTim( $eventImage->image , 200, 200, array('class'=>'img-polaroid') )}}
	</div>
</div>