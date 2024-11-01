sscControllers.controller('helpCtl', [ '$scope', function( $scope) {

}]);

sscControllers.controller('settings.mainCtl', [ '$scope', 'categories',  function( $scope, categories ) {
	 $scope.categories = categories;
	
}]);

sscControllers.controller('settings.category.mainCtl', [ '$scope', 'category', function($scope, category){
	$scope.category = category;
	$scope.getValueDisplayClass = function(option){
		if(option.data_type && option.data_type == 'bool'){
			return option.value ? 'label label-success ssc-text-large' : 'label label-danger ssc-text-large';
		}
		return '';
	} 
}]);

sscControllers.controller('settings.category.allCtl', [ '$scope', '$global',  function($scope, $global){
	$global.routes.current().setBreadCrumb('Settings / ' + $scope.category.title + ' / All Settings');
}]);


sscControllers.controller('settings.category.stack.mainCtl', [ '$scope', 'stack', function($scope, stack){
	$scope.stack = stack;
}]);

sscControllers.controller('settings.category.stack.viewCtl', [ '$scope', '$global', function($scope, $global){
	$global.routes.current().setBreadCrumb('Settings / ' + $scope.category.title + ' / ' + $scope.stack.title );
}]);

sscControllers.controller('settings.category.stack.editCtl', [ '$scope', 'factory', '$global', '$state', function($scope, factory, $global, $state){
	$global.routes.current().setBreadCrumb('Settings / ' + $scope.category.title + ' / ' + $scope.stack.title + ' / Edit');
	$scope.optionInputTemplate = function(option) {
		var templatesUrl = $global.urls.partials + 'shared/';
		if(typeof option.type == 'object' ){
			return $global.urls.template('shared.inputs.' + option.type.input);
			
		}
		else{
			return $global.urls.template('shared.inputs.' + option.type);
		}
	}

	 $scope.saveOptions = function() {
		$global.loading.show();
		factory.saveStack($scope.stack)
			.then(function(response){
				$global.loading.hide();
				var messages = { 
								success : $scope.stack.title + ' settings saved successfully.',
								error	: 'There was an error saving ' + $scope.stack.title + ' settings. Relaod and try again.'

							};
				$global.alerts.saveResponse( response.data, messages, 4000 );

				if(response.data.success){
					$state.transitionTo('settings.category.stack.view', { category: $scope.category.slug, stack: $scope.stack.slug }, { reload : true });
				}
			});
	}
}]);