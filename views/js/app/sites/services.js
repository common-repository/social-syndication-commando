sscServices.factory('sitesFactory', [ '$q', 'dashApi', function($q, dashApi){
	var factory = {};
	factory.sitesPromise;
	factory.sites;

	factory.init = function() {
		 if(!factory.sitesPromise) {
          factory.sitesPromise = dashApi.get('ssc_get_all_sites')
                        .then( function(response) {
                            return response.data;                            
                        }, function(response){
                             return $q.reject(response.data);
                        });
       
		}
	}
	factory.get = function() {
		return factory.getSites().then( function(sites){
			return factory;
		});
	}

	factory.all =function(){
		return factory.getSites().then( function(sites){
			if(!factory.sites){
				factory.sites = {};
				siteKeys = Object.keys(sites);
				siteKeys.forEach(function(slug){
					factory.sites[slug] = factory.makeSite(sites[slug]);
				});
			}
			return factory.sites;
		});
	}

	factory.getSites = function () {
		if(!factory.sitesPromise){
			factory.init();
		}
		return factory.sitesPromise;
	}
	
	factory.find = function (slug) {
		return factory.getSites().then( function(sites){
			factory.all();
			return factory.sites[slug];
		});
	}

	factory.makeSite = function(site) {

		site.getAccount = function(id) {
			return site[id];
		}

		site.addAccount = function(account) {
			site.accounts[account.id] = account;
			site.accounts_count += 1;
			//factory.sites[site.slug] = angularCopy(site);

		}

		site.createAccount = function(account) {
			return dashApi.post('ssc_add_account', account)
				.success(function(response){
					
						if(response.success)
							site.addAccount(response.account);
						return response;

				})
				.error(	function(response){
					        return response;
                        });
		}

		site.deleteAccount = function(id) {
			return dashApi.post('ssc_delete_account', {slug: site.slug, id : id})
			.success(function(response){
				console.log(response);
				if(response.success)
					site.removeAccount(id);
				return response;
			})
			.error(function(response){
				console.log(response);
				return response;
			});
		}

		site.removeAccount = function(id){
			delete site.accounts[id];	
			site.accounts_count -= 1;
			//factory.sites[site.slug] = angularCopy(site);
		}

		site.changeAccountAuthority = function(id){
			var value = site.accounts[id].authority == 1 ? 0 : 1;
			return dashApi.post('ssc_change_authority', {slug: site.slug, id: id, value:value})
			.success(function(response){
				if(response.success)
					site.accounts[id].authority = value;
				return response;
			})
			.error(function(response){
				return response;
			});
		}
		return site;
	}

	factory.init();
	return factory;


}]);