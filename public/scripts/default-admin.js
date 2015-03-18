$('document').ready(function(){
	var placeholder = {placeholder:'_'};
	$('.mask-datetime').mask('99/99/9999 99:99', placeholder);
	$('.mask-date').mask('99/99/9999', placeholder);
	$('.mask-phone').mask('9999-9999?9', placeholder);
	$('.mask-phonearea').mask('(99) 9999-9999?9', placeholder);
	$('.mask-zip-br').mask('99999-999', placeholder);
});
