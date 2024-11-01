
sscControllers.controller('sites.mainCtl', function( $scope , sites) {
	$scope.sites = sites;
});


sscControllers.controller('sites.allCtl', function( $scope) {
	
	
});


sscControllers.controller('sites.importAccountsCtl', function( $scope) {
		
});


sscControllers.controller('sites.site.mainCtl', function( $scope , $global, site) {
	$scope.site = site;	
	
});


sscControllers.controller('sites.site.accountsCtl', function( $scope , site, $state, $stateParams , $global) {
	$global.routes.current().setBreadCrumb('Accounts / ' + site.name + ' / All Accounts');
	if($stateParams.added){
		$global.alerts.success.show("Account authorised successfully.");
	}

	$scope.authoriseLabel = function(id){
		if($scope.site.accounts[id].authority == 1){
			return "Remove authority";
		}
		else{
			return "Make authority";
		}
	}

	$scope.delete = function(id) {
		$global.loading.show();
		$scope.site.deleteAccount(id)
		.then(function(response){
			$global.loading.hide();
			$global.alerts.success.show('Account successfully deleted.');
			//$state.reload();
		});
	}
	$scope.authorise = function(id) {
		$global.loading.show();
		$scope.site.changeAccountAuthority(id)
		.then(function(response){
			$global.loading.hide();
			$global.alerts.success.show('Account authority has changed');
		});
	}
	
});

sscControllers.controller('sites.site.addCtl', function( $scope, $state, $stateParams, apiSettings, $global, site, factory) {
		
	if($stateParams.addError){
		$global.alerts.error.show($stateParams.addErrorMsg, 4000);
		$state.transitionTo('sites.site.add', { slug: site.slug });
	}
	
	$global.routes.current().setBreadCrumb('Accounts / ' + site.name + ' / Add New');
	$scope.site = accountAdder(site.slug);


	function accountAdder(slug){
		var adder = {};
		
		
		/***************************
		//Diigo Adder
		****************************/
		adder.diigo = function (){
			var $this = adder.makeFormAdder();
			return $this;
		}

		/***************************
		//Friendfeed Adder
		****************************/
		adder.friendfeed = function (){
			var $this = adder.makeFormAdder();
			$this.usernameLabel = 'Nickname';
			$this.passwordLabel = 'Remote key';
			return $this;
		}

		/***************************
		//Delicious Adder
		****************************/
		adder.delicious = function (){
			var $this = adder.makeFormAdder();
			return $this
		}

		/***************************
		//Live Journal Adder
		****************************/
		adder.lj = function (){
			var $this = adder.makeFormAdder();
			return $this
		}

		adder.facebook = function(){
			var $this = adder.makeAuthoriseAdder();
			adder.setSiteContentTemplate($this);
			$this.fbRedirectUrl = $global.urls.admin;
			return $this;
		}

		adder.wordpress = function(){
			var $this = adder.makeAuthoriseAdder();
			adder.setSiteContentTemplate($this);
			$this.wpRedirectUrl = $global.urls.admin + '?page=sscsettings&sscwordpress=wordpress';
			return $this;
		}

		adder.tumblr = function(){
			var $this = adder.makeAuthoriseAdder();
			return $this;
		}

		adder.plurk = function(){
			var $this = adder.makeAuthoriseAdder();
			return $this;
		}

		adder.twitter = function(){
			var $this = adder.makeAuthoriseAdder();
			return $this;
		}

		adder.linkedin = function(){
			var $this = adder.makeAuthoriseAdder();
			return $this;
		}


		adder.getSite = function(slug) {
			return adder[slug]();
		}

		adder.makeAuthoriseAdder = function(){
			var $this = site;
			if(!$this.should_verify)
				return adder.makeFormAdder();

			$this.template = $global.urls.template('sites.authorise');

			$this.contentTemplate = '';
			$this.redirectUrl = $global.urls.admin + '?page=sscsettings&sscredon=' + $this.slug;
			adder.checkSiteApiSettings($this);
			return $this;
		}

		adder.checkSiteApiSettings = function($this) {
			if($this.api_required && !apiSettings.isOk()){
				$this.apiIsOk = false;
			}
			else{
				$this.apiIsOk = true;
			}
		} 

		adder.setSiteContentTemplate = function($this){
			$this.contentTemplate =  $global.urls.template('sites.' + $this.slug + '.add');
			return $this;
		}

		adder.makeFormAdder = function(){
			var $this = site;
			$this.usernameLabel = 'Username';
			$this.usernameInput = 'text';
			$this.passwordLabel = 'Password';
			$this.passwordInput = 'password';

			$this.newAccount = {};
			
			$this.invalidErrorMsg = function(){
				return $this.usernameLabel + " and " + $this.passwordLabel + " are required.";
			} 
			$this.addErrorMsg = "Account add error. Try again.";
			$this.successMsg = "Account successfully added.";
			$this.template = $global.urls.template('sites.add');

			$this.verify = function() {
				adder.formVerify($this);
			}

			return $this;
		}
		adder.formVerify = function($this) {
			$global.loading.show();
			if($this.newAccountFrm.$invalid)
			{
				$global.alerts.error.show($this.invalidErrorMsg());
				$global.loading.hide();
				return;
			}
			$this.createAccount({ 
							social: $this.slug,
							username: $this.newAccount.username,
							password: $this.newAccount.password })
				.then(function(response){
					$global.loading.hide();
					$global.alerts.saveResponse( response.data, { success : $this.successMsg, error : $this.addErrorMsg }, 4000 );
					console.log('response', response.data);
					if(response.data.success)
						$this.newAccount = {};
				});
		}

		
		return adder.getSite(slug);

	}

});
