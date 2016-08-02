$('document').ready(function(){
	var ajaxRequest = AjaxRequest();
	ajaxRequest.setBaseUrl(baseUrlAdmin);
	
	if( $.inputmask ){
		var placeholder = '_';
		$.inputmask.defaults.aliases.datetime = {mask: '99/99/9999 99:99', placeholder: placeholder}
		$.inputmask.defaults.aliases.date = {mask: '99/99/9999', placeholder: placeholder}
		$.inputmask.defaults.aliases.phone = {mask: '99999-9999', placeholder: placeholder}
		$.inputmask.defaults.aliases.phonearea = {mask: '(99) 99999-9999', placeholder: placeholder}
		$.inputmask.defaults.aliases.zip = {mask: '99999-999', placeholder: placeholder}
		$.inputmask.defaults.aliases.phone_cc_br = {mask: '+99 (99) 99999-9999', placeholder: placeholder}
		$.inputmask.defaults.aliases.phone_cc_en = {mask: '+99 99 999 9999-9[9]', placeholder: placeholder}
		
		$('.mask-datetime').inputmask('datetime');
		$('.mask-date').inputmask('date');
		$('.mask-zip-br').inputmask('zip');
		$('.mask-phone').inputmask('phone');
		$('.mask-phonearea').inputmask('phonearea');
		$('.mask-phone-cc-br').inputmask('phone_cc_br');
		$('.mask-phone-cc-en').inputmask('phone_cc_en');
		
		//$(':input').inputmask();
	}
	
	if(jQuery.ui && $('.sortable').length>0 ){
		$('.sortable').sortable();
	}
	
	if(jQuery.ui && $('.inlist-sortable').length>0){
		var $inlistSortable = $( ".inlist-sortable" ).sortable({
			placeholder: "ui-state-highlight",
			forcePlaceholderSize: true,
			disabled: true,
			update: function(event, ui){
				var data = {
					order: $inlistSortable.sortable('toArray', {attribute:'data-sortable-id'}),
				}
				ajaxRequest.endpoint(getController()+'/ajax-update-order').data(data).request();
			}
		});
		
		$('.inlist-sortable-toogle').on('mouseenter', function(){
			$inlistSortable.sortable("enable");
			$(this).css('cursor', 'move');
		});
		
		$('.inlist-sortable-toogle').on('mouseleave', function(){
			$inlistSortable.sortable("disable");
			$(this).css('cursor', 'default');
		});
	}
	
	$('.bt-save-order').on('click', function(){
		var order = [];
		var post = {};
		var segs = window.location.href.split('/');
		var controllerName = segs[segs.length-2];
		
		$('.organize-itens li').each(function(){
			order.push( $(this).attr('data-id') );
		});
		post = {'list':order};
		
		console.log(post, order);
		ajaxRequest.endpoint(controllerName+'/ajax-organize').data(post).request();
	});
	
	$('#pagination-itens-perpage').on('change', function(){
		var qs = location.search;
		var url = location.protocol+'//'+location.hostname+location.pathname;
		var result = [];
		var perpage = 'perpage=';
		
		if(qs==''){
			qs = '?'+perpage;
		}else{
			result = qs.match(/^(\?|&)perpage=(\d+)/);
			if(result[0]){
				qs = result[1]+perpage+qs.replace(result[0], '');
			}else{
				qs += '&'+perpage;
			}
		}
		
		qs += $(this).val();
		console.log(url+qs);
		//window.location = url+qs;
		location.replace(url+qs);
	});
	
	$('.bt-delete-row').on('click', function(e){
		var remove = confirm('Deseja realmente remover este item?\nEsta ação não pode ser desfeita.');
		if(!remove){
			e.preventDefault();
		}
	});
	
	$('.bt-seo-save').on('click', function(){
		var $form = $(this).closest('form');
		var $modal = $('#seoModal');
		
		ajaxRequest.endpoint(getSegment(3)+'/ajax-seo-save').data($form.serialize()).request('.seo-save-loader').then(function(r){
			if( r.status==200 ){
				$modal.find('form [name="title"]').val('');
				$modal.find('form [name="keywords"]').val('');
				$modal.find('form [name="description"]').val('');
				$modal.find('form [name="object_id"]').val('');
				
				$modal.modal('hide');
			}
		});
	});
	
	$('.bt-seo-load').on('click', function(){
		var id = parseInt( $(this).attr('data-id') );
		var $modal = $('#seoModal');
		
		ajaxRequest.endpoint(getSegment(3)+'/ajax-seo-load').data({'id': id}).request('.seo-load-loader').then(function(r){
			if( r.status==200 ){
				var seo = r.data;
				$modal.find('form [name="title"]').val( seo.title);
				$modal.find('form [name="keywords"]').val( seo.keywords);
				$modal.find('form [name="description"]').val( seo.description);
				$modal.find('form [name="object_id"]').val( seo.object_id);
				
				$modal.modal();
			}
		});
	});
	
	if( $('.preview-image .preview-image-inner').length>0 ){
		$('.preview-image-container').on('mouseenter', '.preview-image-inner', function(){
			$(this).find('.image-control').animate({'top':'0px'});
		});
		
		$('.preview-image-container').on('mouseleave', '.preview-image-inner', function(){
			$(this).find('.image-control').animate({'top':'-20px'});
		});
		
		$('.bt-preview-image-delete').click(function(e){
			e.preventDefault();
			var $self = $(this);
			var post = {
				'id': $(this).attr('data-id'),
				'field': $(this).attr('data-field'),
			}
			ajaxRequest.endpoint(getSegment(3)+'/ajax-delete-image').data(post).request().then(function(r){
				$self.closest('.preview-image').css('background', '#dd4b39').fadeOut(900, function(){
					$(this).remove();
				});
			}, function(errors){
				alert(jsonErrorsToList(errors).join("\n"));
			});
		});
	}
	
	if( jQuery.ui ){
		$( ".datepicker" ).datepicker({
			gotoCurrent: false,
			autoSize: true,
			dateFormat: 'yy-mm-dd',
			dayNames: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
			dayNamesShort: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'],
			dayNamesMin: ['Se', 'Te', 'Qu', 'Qu', 'Se', 'Sa', 'Do'],
			monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
			monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
			nextText: 'Próximo',
			prevText: 'Anterior',
			closeText: 'Fechar',
			currentText: 'Hoje'
		});
	}
	
});

function getSegment(backIndex){
	if( typeof(backIndex)=='undefined')
		backIndex = 2;
	
	var segs = window.location.href.split('/');
	var admIndex = segs.indexOf('admin');
	//var controller = segs[(segs.length) - (backIndex)];
	var controller = segs[admIndex+1];
	
	return controller;
}

function getController(addIndex){
	if( typeof(addIndex)=='undefined')
		addIndex = 1;
	
	var segs = stripQueryString(window.location.href).split('/');
	var admIndex = segs.indexOf('admin');
	var controller = segs[(admIndex+addIndex)];
	
	return controller;
}

function stripQueryString(url){
	var questionMarkPos = url.indexOf('?');
	var ampersandPos = url.indexOf('&');
	var index = null;
	
	if( (ampersandPos>-1) && ampersandPos < questionMarkPos )
		index = ampersandPos;
	else
		index = questionMarkPos;
	
	if(index==-1)
		index = url.length;
	
	return url.substr(0, index);
}

function jsonErrorsToList(){
	var errorsList = [];
	var errors = [];
	for( var field in resp.errors ){
		errors = resp.errors[field];
		
		for(var i in errors){
			errorsList.push(errors[i]);
		}
	}
	
	return errorsList;
}
