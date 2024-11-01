sscServices.factory('settingsFactory', [ '$q', 'dashApi', '$global', function($q, dashApi, $global){
	var factory = {};
	factory.dashApi = dashApi;
	factory.promise;
	factory.categories;
	factory.indexMap = {};


	factory.init = function() {
		 if(!factory.promise) {
          factory.promise = dashApi.get('ssc_get_all_settings')
                        .then( function(response) {
                            return response.data;                            
                        }, function(response){
                             return $q.reject(response.data);
                        });
       
		}
	}
	factory.get = function() {
		return factory.getCategories().then( function(categories){
			return factory;
		});
	}


	factory.makeStack = function($stack){
		var stack = $stack;
		stack.options.forEach(function(option, index){
			if(option.data_type && option.data_type == 'bool'){
				stack.options[index].display_value = function(){
					return option.value ? 'Yes' : 'No';
				} 
			}
			else{
				stack.options[index].display_value = function(){
					var cutPoint = option.value.length > 6 ? 6 :  option.value.length - 3;
					cutPoint = cutPoint < 1 ? option.value.length : cutPoint; 
					return cutPoint > 0 
									? option.value.substring(0, 6) + '****'
									: option.value;
				} 
			}

		});

		stack.isOk = function(){
			return stack.is_ok;
		}

		if(stack.slug == 'license')
			$global.app.setVars({'licenseOk' : stack.is_ok });

		return stack;
	}

	factory.makeCategory = function($category){
		var category = $category;
		var indexMap = {};

		
		function makeStacks(category){
			category.indexMap = {};
			category.stacks.forEach(function(stack, index){
				category.indexMap[stack.slug] = index;
				category.stacks[index] = factory.makeStack( stack );
			});
			return category.stacks;
		}

		category.stacks = makeStacks(category);
		
		category.getIndex = function(slug){
			return category.indexMap[slug];
		}

		category.find = function(slug){
			return category.stacks[category.getIndex(slug)];
		}
		return category;
	}


	factory.setCategories = function(categories){
		factory.categories = [];
		factory.indexMap = {};
		categories.forEach(function(category, index){
			factory.categories[index] = factory.makeCategory(category);
			factory.indexMap[category.slug] = index;
		});
		return factory.categories;
	}

	factory.all =function(){
		return factory.getCategories().then( function(categories){
			if(!factory.categories)
				return	factory.setCategories(categories);
			return factory.categories;
		});
	}

	factory.getCategories = function() {
		if(!factory.promise){
			factory.init();
		}
		return factory.promise;
	}
	
	factory.find = function(slug){
		return factory.getCategories().then( function(categories){
			factory.all();
			return factory.categories[factory.getIndex(slug)];
		})
	}

	

	factory.getIndex = function(slug){
		return factory.indexMap[slug];
	}



	factory.saveStack = function(stack) {

		return dashApi.post('ssc_save_stack_options', stack)
				.success(function(response){
					if(response.success){
						factory.categories[stack._category][stack._index] = factory.makeStack(response.stack);
					}
					return response;
				})
				.error(	function(response){
				 	return response;
                });
	}

	factory.prepareOptions = function(stackOptions) {
		var options = {};
		stackOptions.forEach(function(option){
			options[option.name] = option.value;
		});
		return options;
	}

	factory.init();
	return factory;


}]);