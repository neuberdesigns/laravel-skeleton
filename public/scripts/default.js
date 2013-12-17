$('document').ready(function(){
	var placeholder = {placeholder:'_'};
	$('.mask-datetime').mask('99/99/9999 99:99', placeholder);
	$('.mask-phone').mask('9?999-99999', placeholder);
	$('.mask-phonearea').mask('(99) 9?9999-9999', placeholder);
	$('.mask-zip-br').mask('99999-999', placeholder);
});