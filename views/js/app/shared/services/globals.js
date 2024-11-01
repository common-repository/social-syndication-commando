sscServices.factory('$global', ['$rootScope', '$timeout', '$state', '$window', function( $rootScope, $timeout, $state, $window ){
	var appInit = '*';
	
	var factory = {
			app 		: app(),
			urls 		: urls(),
			alerts 		: alerts(),
			loading 	: loading(),
			routes		: routes()
	}



	function app(reset){
		reset = typeof reset == 'undefined' ? false : reset;
		if(typeof appInit == 'object' && !reset)
			return appInit;

		var $this = {
			isDebug 			: 0,
			partialsDirName 	: 'partials',
			apiUrl				: $window.ajaxurl,
			adminUrl 			: '',
			viewsUrl 			: '',
			pushedAlerts 		: { success:[], error:[] },
			licenseOk 			: true,

		};
		
		$this.setVars = function(vars){
			var keys = Object.keys(vars);
			keys.forEach(function(key){
				$this[key] = vars[key];
			});
		}
		$this.setVars(typeof $window.sscAppVars == 'undefined' ? {} : $window.sscAppVars);

		appInit = $this;
		return $this;
	}


	function routes(){
		var routes = {};

		routes.get = function(stateName){
			return makeRoute(stateName);
		}

		
		routes.currentRoute = changeCurrentRoute();
		
		routes.isActive = function(stateName, params){
			return $state.includes( stateName, params ) ? { 'active' : true } : { 'active' : false };
		}
		routes.current = function(){
			if(routes.currentRoute.uiState)
				routes.currentRoute.setPageTitle();

			if(routes.currentRoute && $state.current && routes.currentRoute.uiState && routes.currentRoute.uiState.name == $state.current.name)
				return routes.currentRoute;

			return changeCurrentRoute();
		}

	

		

		//Private Methodes
		function changeCurrentRoute(){
			routes.currentRoute = $state.current ? routes.get($state.current.name) : routes.get("");
			
			return routes.currentRoute;
		}

		function getParent(route){
			return typeof route.uiState.parent ? routes.get(route.uiState.parent) : null;
		}

		function getState(stateName){
			return typeof stateName == 'object' ? stateName : $state.get(stateName);
		}

		function makeRoute(stateName){
			var $this = {};
			$this.uiState = getState(stateName);
			$this.displayData = getStateData('displayData', { breadCrumb: '', icon : '' });

			$this.setBreadCrumb = function(value){
				$this.displayData.breadCrumb = value;
			}

			$this.setIcon = function(value){
				$this.displayData.icon = value;
			}

			function getStateData(key, defaultValue){

				if($this.uiState == null)
					return defaultValue;

				if(typeof $this.uiState.data == 'undefined')
					return defaultValue;
				if(typeof $this.uiState.data[key] == 'undefined')
					return defaultValue;
				return $this.uiState.data[key];
			}

			$this.title = function(){
				var title = $this.displayData.breadCrumb.split('/');
				return title.length > 0 ? title[0] : '';
			}

			$this.breadCrumb = function(){
				return $this.displayData.breadCrumb.replace($this.title(), '');
			}

			$this.icon = function(){
				return  $this.displayData.icon;
			}

			$this.setPageTitle = function(){
				var title = $this.title().length > 0 ? $this.title() + ': ' : '';
				title = title + app().pluginName + ' - ' + app().blogName;
				angular.element('title').html(title);
			}

			return $this;
		}





		return routes;
	}

	function urls(){
		var $this = {};
		$this.api = app().apiUrl;
		$this.admin = app().adminUrl;
	    $this.views = app().viewsUrl;	    
	    $this.partials = $this.views + 'partials/';
	    $this.templates = $this.partials + 'templates/';
	    $this.template = function(module){
	    	var tree = module.split('.');
	    	var path = $this.templates;
	    	for(var i = 0; i < tree.length - 1; i++){
	    		path = path + tree[i] + '/';
	    	}
	    	return path + tree[tree.length - 1] + '.html';
	    }
	    $this.icon = $this.views + 'images/icon.ico';
	    return $this;
	}
	function loading(){
		var $this = {};
		$this.active = false;
		$this.show	= function(){
			$this.active = true;
			return $this;
		}
		$this.hide = function(){
			$this.active = false;
			return $this;
		}
		$this.isActive = function(){
			return $this.active;
		}
		return $this;
	}

	function alerts(){
		var alerts = {};
		alerts._pushed =  { success:[], error:[] };
		
		alerts.pushed = function(){
			return alerts._pushed;
		}

		
		alerts.defaults = {};
		alerts.defaults.success = {
									default 	: 'Success!!.',
									save 		: 'Saved successfully.',
									};
		alerts.defaults.error =  {
									default 	: 'Error!!.',
									save 		:	'Error while Saving.',
									};

		
		alerts.getDefault = function( type, context ){
			var messages = alerts.defaults[type];
			return typeof messages[context] == 'undefined' ? messages.default : messages[context];
		}

		alerts.setDefaults = function( messages, context){
			messages = typeof messages == 'object' ? messages : {};
			defaultKeys = Object.keys(alerts.defaults);
			defaultKeys.forEach(function(type){
				messages[type] = typeof messages[type] == 'undefined' 
										? alerts.getDefault(type, context)
										: messages[type];
			});
			return messages;
		}

		alerts.makeAlert = function(){
			var $this = {};
			$this.active = false;
			$this._message = '';
			$this.message = function(){
				return $this._message;
			}
			$this.show = function(theMsg, hide){
				hide = typeof hide == 'undefined' ? 3500 : hide;
				$this._message = theMsg;
				$this.active = true;
				if(hide !== false)
					$this.hide(hide);
				return $this;
			}

			$this.hide = function(whenTo){
				whenTo = typeof whenTo == 'undefined' ? 0 : whenTo;
				$timeout(function(){
					$this._message = '';
					$this.active = false;
				}, whenTo);	
				return $this;
			}

			$this.isActive = function(){
				return $this.active;
			}
			return $this;
		}

		alerts.success = alerts.makeAlert();
		alerts.error = alerts.makeAlert();

		alerts.show = function(type, message, hide ){
			return alerts[type].show(message, hide);
		}

		alerts.showPushed = function(){
			var messages = '';
			alerts.pushed().success.forEach(function(message){
				messages += '<li>' + message + '</li>';
			});
			if(messages.length > 0)
				alerts.success.show('<ul>' + messages + '</ul>');
			messages = '';
			alerts.pushed().error.forEach(function(message){
				messages += '<li>' + message + '</li>';
				
			});
			if(messages.length > 0)
				alerts.error.show('<ul>' + messages + '</ul>');
		}

		alerts.setPushed = function(alerts){
			alerts._pushed = alerts;
		}

		alerts.setPushed(app().pushedAlerts);
		alerts.showPushed();
		appInit.pushedAlerts = { success:[], error:[] };


		alerts.serverResponse = function(responseData, messages, context, hide){
			console.log(responseData.alerts);
			var defaults = alerts.setDefaults( messages, context);
			messages = typeof responseData.alerts != 'undefined' 
								?  alerts.setDefaults({
											error 		: arrayToList(responseData.alerts.error),
											success 	: arrayToList(responseData.alerts.success)
											}	, context) 
								: defaults;
			messages.success = messages.success.length == 0 ? defaults.success : messages.success;
			messages.error = messages.error.length == 0 ? defaults.error : messages.error;

			if(responseData.success){
				alerts.success.show(messages.success, hide);
			}
			else{
				alerts.error.show(messages.error, hide);
			}
		}

		alerts.saveResponse = function(responseData, messages, hide){
			return alerts.serverResponse(responseData, messages, 'save', hide);
		}

		
		return alerts;

	}

		function arrayToList(arr){
			if(arr.length == 1)
				return arr[0];

			if(arr.length == 0)
				return '';

			var list = '';
			arr.forEach(function(item){
				list += '<li>' + item + '</li>';
			});
			return '<ul>' + list + '</ul>';
		}



	return factory;
}]);
