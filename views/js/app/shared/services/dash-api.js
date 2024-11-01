sscServices.factory('dashApi', ['$http', '$q', '$global', function($http, $q, $global){
	var api = {};
	api.url = $global.urls.api;
	api.token = 'ssc_api_call';

	api.get = function(action, data) {
		return api.request({ 
							method	: "GET",
	                        url 	: api.getUrl(action),
	                        data    : data
		                    });

	}
	api.post = function(action, data) {
		return api.request({ 
							method	: "POST",
	                        url 	: api.getUrl(action),
	                        data 	: data
					 });
	}

	api.getUrl = function(action) {
		var url = api.url + '?' + api.token + '=1';

		if(typeof action != 'undefined' || action.length > 0 )
		{
			return url + '&action='+ action;
		}
		else {
			return url;
		}
	}

	api.makeEncodedUrl = function(action ,data) {
		strData = api.stringfyData(data);
		if(strData.length > 0)
		{
			strData = '&' + strData;
		}
		var url = api.getUrl(action) + strData;
		console.log('Raw: ' + url);
		console.log('Encoded: ' + encodeURIComponent(url));
		return encodeURIComponent(url);
	}

	api.stringfyData = function(data) {
		var str = '';
		if(typeof data != 'undefined' || data.length == 0 ){
			return str;
		}
		var count = data.length - 1;
		data.forEach(function(item, key){
			str+= key + '=' + item;
			if(count > 0){
				str += '&';
			}
			count -=1;
		});
		return str;
	}

	api.request = function(params) {
		return $http(params);
	}

	return api;

}]);