var sscApp = angular.module('sscApp', ['ui.router', 'ngSanitize' ,'sscControllers', 'sscServices']);

sscApp.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
	
	var templatesDir = window.sscAppVars.viewsUrl + 'partials/';
    var currentModuleDir = templatesDir + 'sites/';
    //Sites States
    $stateProvider
    		//Parent
		    .state('sites', {
		            url         : '/sites',
		            abstract	: true,
		            controller  : 'sites.mainCtl',
					templateUrl : currentModuleDir + 'index.html',
					resolve     : {
				                    factory		: function(sitesFactory) {
					                       				return sitesFactory.get();
							                       },
							        sites 		: function(factory) {
							        					return factory.all();
							        				}
			                      },
			        data		: {
			        				displayData 	: { breadCrumb : 'Accounts', icon : 'glyphicon glyphicon-user'}
			        				
			        			}
				})

			//Account List State
			.state('sites.all', {
					parent		: 'sites',
		            url         : '/all',
		            controller  : 'sites.allCtl',
					templateUrl : currentModuleDir + 'all.html',
			        data		: {
			        				displayData 	: { breadCrumb : 'Accounts / All Sites', icon : 'glyphicon glyphicon-user'}
			        			}
				})

			//Import Accounts State
			.state('sites.import', {
					parent		: 'sites',
		            url         : '/import-accounts',
		            controller  : 'sites.importAccountsCtl',
					templateUrl : currentModuleDir + 'import.html',
			        data		: {
			        				displayData 	: { breadCrumb : 'Accounts / Import', icon : 'glyphicon glyphicon-user'}
			        			}
				})
			//Account List State
			.state('sites.site', {
					parent		: 'sites',
					abstract	: true,
		            url         : '/:slug',
		            controller  : 'sites.site.mainCtl',
					templateUrl : currentModuleDir + 'site/index.html',
					resolve     : {
									
				                    site			: function(factory, $stateParams) {
				                       					return factory.find($stateParams.slug);
						                       		  },

					                apiFactory	: function(settingsFactory) {
						                       				return settingsFactory.get();
								                       },
								    allApiSettings : function(apiFactory){
								    						return apiFactory.all();
								    				},
				                    apiCategory	: function(apiFactory) {
				                    					return apiFactory.find('apis');

					                       		  },
					                apiSettings : function(apiCategory, $stateParams){
					                					return apiCategory.find($stateParams.slug);
					                				}


			                      },
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-user'}
			        				
			        			}
				})

			//Add account
			.state('sites.site.add', {
					parent		: 'sites.site',
		            url         : '/add',
		            controller  : 'sites.site.addCtl',
					templateUrl : currentModuleDir + 'site/add-account.html',
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-user'}
			        				
			        			}
			    })
			//Select account
			.state('sites.site.accounts', {
					parent		: 'sites.site',
		            url         : '/accounts',
		            controller  : 'sites.site.accountsCtl',
					templateUrl : currentModuleDir + 'site/accounts.html',
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-user'}
			        				
			        			}
				});


	var currentModuleDir = templatesDir + 'settings/';
    //Config States
    $stateProvider
		//Parent Config state
		.state('settings', {
				abstract	: true,
	            url         : '/settings',
	            controller  : 'settings.mainCtl',
				templateUrl : currentModuleDir + 'index.html',
				resolve     : {
			                    factory		: function(settingsFactory) {
				                       				return settingsFactory.get();
						                       },
						        categories 	: function(factory) {
						        					return factory.all();
						        				}
		                      },
		        data		: {
		        				displayData 	: { breadCrumb : 'Settings', icon : 'glyphicon glyphicon-tasks'}
		        			}
			})
		.state('settings.category', {
					abstract	: true,
					parent		: 'settings',
		            url         : '/:category',
	            	controller  : 'settings.category.mainCtl',
					templateUrl : currentModuleDir + 'category/index.html',
					resolve     : {
							        category 	: function(factory, $stateParams) {
			                       					return factory.find($stateParams.category);
					                       		  }
			                      },
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-tasks'}
			        			}
				})
		.state('settings.category.all', {
					parent		: 'settings.category',
		            url         : '/',
		            controller  : 'settings.category.allCtl',
					templateUrl : currentModuleDir + 'category/all.html',
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-tasks'}
			        			}
				})

		.state('settings.category.stack', {
					abstract	: true,
					parent		: 'settings.category',
		            url         : '/:stack',
		            controller  : 'settings.category.stack.mainCtl',
					templateUrl : currentModuleDir + 'category/stack/index.html',
					resolve     : {
									category 	: function(factory, $stateParams) {
			                       					return factory.find($stateParams.category);
					                       		  },
							        stack 	: function(category, $stateParams) {
			                       					return category.find($stateParams.stack);
					                       		  }
		                      },
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-tasks'}
			        			}
				})
		.state('settings.category.stack.view', {
					parent		: 'settings.category.stack',
		            url         : '/',
		            controller  : 'settings.category.stack.viewCtl',
					templateUrl : currentModuleDir + 'category/stack/view.html',
					resolve     : {
							        stack 	: function(category, $stateParams) {
			                       					return category.find($stateParams.stack);
					                       		  }
		                      },
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-tasks'}
			        			}
					
				})
		.state('settings.category.stack.edit', {
					parent		: 'settings.category.stack',
		            url         : '/edit',
		            controller  : 'settings.category.stack.editCtl',
					templateUrl : currentModuleDir + 'category/stack/edit.html',
					resolve     : {
							        stack 	: function(category, $stateParams) {
			                       					return category.find($stateParams.stack);
					                       		  }
		                      },
			        data		: {
			        				displayData 	: { breadCrumb : '', icon : 'glyphicon glyphicon-tasks'}
			        			}
					
				})

	var currentModuleDir = templatesDir + 'help/';
    //Config States
    $stateProvider
		//Help state
		.state('help', {
	            url         : '/help',            
	            controller  : 'helpCtl',
				templateUrl : currentModuleDir + 'index.html',
			        data		: {
			        				displayData 	: { breadCrumb : 'Help', icon : 'glyphicon glyphicon-question-sign'}
			        			}
			})
	
	 $urlRouterProvider.otherwise('/sites/all');
}]);


sscApp.run(['$rootScope', '$stateParams', '$state', '$global', function ($rootScope, $stateParams, $state, $global) {
	$rootScope.$state = $state;
	$rootScope.$global = $global;
    $rootScope.theStateParams = $stateParams;
   

}]);

sscApp.run(['$rootScope', '$global', function($rootScope, $global) {
	   $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
	   		$global.loading.show();
	   });
	   $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
	     	$global.loading.hide();
	    });
	}]);


sscApp.run(['$rootScope','$templateCache', '$global', function($rootScope, $templateCache, $global) {
	   $rootScope.$on('$stateChangeStart', function() {
		   	if($global.app.isDebug) {
		      $templateCache.removeAll();
		   	}
	   });
	}]);

angular.element(document).ready(function() {
  angular.bootstrap(document, ['sscApp']);
});

var sscServices = angular.module('sscServices', []);
var sscControllers = angular.module('sscControllers', []);