var multiImage = {
	controlSpeed: 'fast',
	deleteCallback: null,
	addFieldCallback: null,
	modalId: null,
	singleFileUploads: false,
	
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
	},
	
	jqUpload: function(sl, url, list, additionalData, cbAdd, cbDone, cbPogress){
		/*multiImage.setProgress(0);
		multiImage.uploadError('', true);*/
		
		$(sl).fileupload({
			url: baseUrlAdmin+url,
			paramName: null,
			formData: additionalData,
			dataType: 'json',
			singleFileUploads: false,
			
			add: function (e, data) {
				//data.context = $('<p/>').text('Uploading...').appendTo(document.body);
				//$(sl).fileupload('option', 'paramName', $(this).attr('name'));
				//multiImage.addListPreloader(list);
				
				if( typeof(cbAdd)=='function' ){
					cbAdd.apply(this, [e, data]);
				}else{
					$.each(data.files, function (index, file) {
						data.submit();
					});
				}
			},
			
			done: cbDone,
			
			progressall: function (e, data) {
				if( typeof(cbPogress)=='function' ){
					cbPogress.apply(this, [e, data]);
				}else{
					var progress = parseInt(data.loaded / data.total * 100, 10);
					multiImage.setProgress(progress);
				}
			}
		});
	},
	
	addListPreloader: function(list){
		var $listBox = $(list);
		
		$listBox.append( 
			$('<img />').attr({'src':baseUrl+'images/ajax_loader.gif', 'class':'preloader'})
		);
	},
	
	addListThumb: function(list, index, file, replacePreloader){
		var $listBox = $(list);
		console.log(file, !file.error);
		
		if( !file.error ){
			attributes = {'src':file.thumbnailUrl, 'class':'img-thumbnail'};
			
			if( replacePreloader==true ){
				$listBox.find('img.preloader').eq(index).attr(attributes);
			}else{
				$listBox.append( $('<img />').attr(attributes) );
			}
		}else{
			console.log(file.error);
			multiImage.uploadError(file.error, false);
		}
	},
	
	setProgress: function(pct){
		$('#progress .progress-bar').css(
			'width',
			pct + '%'
		);
	},
	
	uploadError: function(msg, hide){
		var $errBox = $('.upload-error');
		var $errMsg = $errBox.find('.error-message');
		
		$errMsg.html(msg);
		if( hide==true ){
			$errBox.fadeOut('fast');
		}else{
			$errBox.hide();
			$errBox.removeClass('hide');
			$errBox.fadeIn('fast');
		}
	},
}