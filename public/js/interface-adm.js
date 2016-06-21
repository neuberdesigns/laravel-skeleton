$('document').ready(function(){
	var ajaxRequest = AjaxRequest();
	ajaxRequest.setBaseUrl(baseUrlAdmin);
	
	var placeholder = {placeholder:'_'};
	$('.mask-datetime').mask('99/99/9999 99:99', placeholder);
	$('.mask-date').mask('99/99/9999', placeholder);
	$('.mask-phone').mask('9999-9999?9', placeholder);
	$('.mask-phonearea').mask('(99) 9999-9999?9', placeholder);
	$('.mask-zip-br').mask('99999-999', placeholder);
	
	if( $('.sortable').length>0 ){
		$('.sortable').sortable();
	}
	
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
				console.log(r, $self);
				if( r.status==200 ){
					$self.closest('.preview-image').css('background', '#F00').fadeOut(900, function(){
						$(this).remove();
					});
				}
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
	var controller = segs[(segs.length) - (backIndex)];
	
	return controller;
}
