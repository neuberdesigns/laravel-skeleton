/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

function Uploader(selector) {
	var _self       = this;
	this.send       = null;
	this.start      = null;
	this.end        = undefined;
	this.instance   = $(selector).fileupload();
	this.context 	= $(selector);
	this.selector 	= selector;
	this.data 		= {};
	
	this.setOption = function(option, value){
		this.instance.fileupload('option', option, value);
	}
	
	this.getOption = function(option){
		return this.instance.fileupload('option', option);
	}
	
	this.setStart = function(callback){
		this.start = callback;
	}
	
	this.setEnd = function(callback){
		this.end = callback;
	}
	
	this.addData = function(prop, value){
		this.data[prop] = value;
	}
	
	this.create = function(win, error){
		/*_self.context.bind('fileuploadsubmit', function (e, dataSend) {
			dataSend.formData = _self.data;
			return true;
		});*/
		
	  	_self.instance.fileupload('option', {
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			//url: 'server/php/'
			singleFileUploads: false,
			disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
			//maxFileSize: 5000000,
			acceptFileTypes: /(\.|\/)(gif|jpe?g|png|swf|mp4|flv)$/i,
			formData: _self.data,
			
			done: function(e, data){
				console.log(data, 'data');
				console.log(data.result, 'result');
				console.log(data.result.files, 'files');
				var item;
				
				if( win || error ){
					for( var i=0; i<data.result.files.length; i++ ){
						item = data.result.files[i];
						console.log(item);
						if( item.error ){
							if( typeof(error)=='function' )
								error.apply(this, [e, data, item]);
						}else{
							if( typeof(win)=='function' )
								win.apply(this, [e, data, item]);
						}
					}
				}
				if( typeof(_self.end)=='function' )
					_self.end.apply(this, [e, data]);
			},
			
			
			submit: _self.start,
		});

		// Enable iframe cross-domain access via redirect option:
		_self.instance.fileupload(
			'option',
			'redirect',
			window.location.href.replace(
				/\/[^\/]*$/,
				'/cors/result.html?%s'
			)
		);
	}
}


