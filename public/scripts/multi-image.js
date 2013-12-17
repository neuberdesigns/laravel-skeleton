var multiImage = {
	controlSpeed: 'fast',
	deleteCallback: null,
	addFieldCallback: null,
	
	init: function(){
		$('.bt-add-image-field').on('click', multiImage.addImageField);
		$('.bt-multi-image-delete').on('click', multiImage.deleteImage);
		$('.multi-image-inner').hover(multiImage.showControl, multiImage.hideControl);
	},
	
	showControl: function(){
		var $ctl = $(this).find('.image-control');		
		$ctl.animate( {top: '5px'}, multiImage.controlSpeed );
	},
	
	hideControl: function(){
		var $ctl = $(this).find('.image-control');		
		$ctl.animate( {top: '-20px'}, multiImage.controlSpeed );
	},
	
	addImageField: function(){
		if( typeof(multiImage.addFieldCallback)=='function'){
			multiImage.addFieldCallback.apply(this, [$(this)]);
		}
	},
	
	deleteImage: function(){
		if( typeof(multiImage.deleteCallback)=='function'){
			multiImage.deleteCallback.apply(this, [$(this)]);
		}
	}
}