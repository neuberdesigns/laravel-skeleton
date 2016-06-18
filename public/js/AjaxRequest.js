var AjaxRequest = function(){
	var LOADER_TYPE_LOADING = 0;
	var LOADER_TYPE_SUCCESS = 1;
	var LOADER_TYPE_FAIL = 2;
	var METHOD_GET = 'GET';
	var METHOD_POST = 'POST';
	var AUTO_HIDE_TIMEOUT = 3000;
	
	var errorExtractorCallback;
	var loaderWinImg 	= 'ajax-win.png';
	var loaderFailImg 	= 'ajax-fail.png';
	var loaderImg 		= 'ajax-loader.gif';
	var loaderSelector	= '.ajax-loader';
	var loaderImageId	= 'ajax-request-loader';
	var $loader;
	
	var method 			= METHOD_POST;
	var responseType 	= 'json';
	var baseAjaxUrl 	= '';
	var params 			= {};
	var url 			= null;
		
	var addParam = function(name, value){
		if(name && value)
			params[name] = value;
		
		return this;
	};
	
	var setBaseUrl = function(url){
		baseAjaxUrl = url;
	};
	
	var setResponseType = function(type){
		responseType = type;
	};
	
	var post = function(){
		method = METHOD_POST;
		return this;
	};
	
	var isPost = function(){
		return method == METHOD_POST;
	};
	
	var get = function(){
		method = METHOD_GET;
		return this;
	};
	
	var isGet = function(){
		return method == METHOD_GET;
	};
	
	var setErrorExtractor = function(eeCb){
		if(eeCb && typeof eeCb=='function'){
			errorExtractorCallback = eeCb;
		}
	};
	
	var endpoint = function(endpoint){
		url = endpoint;
		return this;
	};
	
	var data = function(data){
		params = data;
		return this;
	};
	
	var extractError = function(errors){
		var errorsList = [];
		if( eeCb ){
			errorsList = errorExtractorCallback.apply(this, [errors]);
		}else{
			for( var key in errors){
				for(var i in errors[key]){
					errorsList.push(errors[key][i]);
				}
			}
		}
		
		return errorsList.join("\n");
	};
	
	var displayLoader = function(type){
		if($loader.length>0){
			$loader.each(function(){
				if($(this).find(getLoaderId()).length>0){
					changeLoaderImage(LOADER_TYPE_LOADING);
					$(this).find(getLoaderId()).show();
				}else{
					$(this).append(createLoaderImage(type));
				}
			});
		}
	};
	
	var hideLoader = function(){
		if($loader.length>0){
			$loader.each(function(){
				$(this).find(getLoaderId()).fadeOut('slow');
			});
		}
	};
	
	var changeLoaderImage = function(type){
		var src = getLoaderImageURL(type);
		$loader.each(function(){
			$(this).find(getLoaderId()).attr('src', src);
		});
	};
	
	var getLoaderId = function(withSelector){
		if(typeof withSelector=='undefined')
			withSelector = true;
		
		return (withSelector?'#':'')+loaderImageId;
	};
	
	var getLoaderImageURL = function(type){
		var imgPath = baseUrl+'images/';
		if(type==LOADER_TYPE_LOADING){
			imgPath += loaderImg;
			
		}else if(type==LOADER_TYPE_SUCCESS){
			imgPath += loaderWinImg;
			
		}else if(type==LOADER_TYPE_FAIL){
			imgPath += loaderFailImg;
		}
		
		return imgPath;
	};
	
	var createLoaderImage = function(type){
		var img = document.createElement('img');
		img.src = getLoaderImageURL(type);
		img.id = getLoaderId(false);
		img.width = 18;
		img.height = 18;
		img.style = 'margin-left: 5px; margin-right: 5px;';
		return img;
	};
	
	var exec = function(){
		var deferred = jQuery.Deferred();
		$loader = $(loaderSelector);
		displayLoader(LOADER_TYPE_LOADING);
		
		$.ajax(baseAjaxUrl+url, {
			method: method,
			dataType: responseType,
			data: params,
			complete: function(jqXHR, textStatus){
				var response;
				try {
					response = JSON.parse(jqXHR.responseText);
				}catch(ex){
					deferred.reject(ex);
				}
				/*console.log(jqXHR.status);
				console.log(jqXHR.responseText);
				console.log('textStatus', textStatus);*/
				
				if(jqXHR.status==200){
					changeLoaderImage(LOADER_TYPE_SUCCESS);
					deferred.resolve(response);
				}else{
					changeLoaderImage(LOADER_TYPE_FAIL);
					deferred.reject(response);
				}
				
				setTimeout(function(){
					hideLoader();
				}, AUTO_HIDE_TIMEOUT);
			},
		});
		
		return deferred.promise();
	};
	
	var request = function(loaderSl){
		if(loaderSl)
			loaderSelector = loaderSl;
		
		return exec();
	};
	
	return {
		request: request,
		setBaseUrl: setBaseUrl,
		param: addParam,
		data: data,
		endpoint: endpoint,
		post: post,
		isPost: isPost,
		get: get,
		isGet: isGet,
	};
};
