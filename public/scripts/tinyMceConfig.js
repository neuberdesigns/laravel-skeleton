tinyMCE.init({
	mode 					: 'textareas',
	selector				: '.tinymce',
	//content_css			: 'css/content.css',
	verify_html				: false,
	skin					: 'lightgray',
	language				: 'pt_BR',
	theme					: 'modern',
	//dialog_type 			: 'modal',
	relative_urls 			: true,
	convert_urls 			: false,
	remove_script_host		: false,
	advlink_styles			: 'Lighbox=mxs_lightbox',
	//document_base_url 		: urlPath.getBase()+'js/tiny_mce/',
	plugins					: 'advlist, charmap, code, fullscreen, hr, image, insertdatetime, link, lists, media, paste, preview, print, searchreplace, table, textcolor, wordcount, responsivefilemanager',
	//image_list 				: urlPath.getAdmin()+'imagem/lista',
	image_advtab			: true,
	menubar					: "format edit insert view table tools",
	toolbar1				: 'bold,italic,strikethrough,|,bullist,numlist,|,blockquote,|,alignleft,aligncenter,alignright,|,|,superscript,subscript,|,link,unlink,anchor,|,searchreplace,|,preview,|,fullscreen',
	toolbar2				: 'fontsizeselect,|,underline,|,alignjustify,|,|,forecolor,backcolor,|,charmap,|,outdent,indent,|,undo,redo,|,hr,|,image,|,media,|,print',
	
	external_filemanager_path:baseUrl+"scripts/tinymce/plugins/responsivefilemanager/filemanager/",
	external_plugins: { "filemanager" : "plugins/responsivefilemanager/filemanager/plugin.min.js"},
	
	filemanager_title: "Gerenciador de imagens",
	filemanager_sort_by: 'date',
	filemanager_descending: false,

});

function open_popup(url)
{
        var w = 900;
        var h = 600;
        var l = Math.floor((screen.width-w)/2);
        var t = Math.floor((screen.height-h)/2);
        var win = window.open(url, 'Filemanager4tinyMCE', "width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
        return false;
}
