<?php

class SSC_SitesFactory {
	protected $ssc;
	protected $sites = array();
	protected $default_site_class = 'SSC_Site';
	protected $default_verified_site_class = 'SSC_SiteVerifier';
	public $account_count = 0;
	public function __construct( $ssc, $sites){
		$this->ssc = $ssc;
		$this->set_sites($sites);
	}

	public function set_sites($sites) {
		$accounts = $this->ssc->sscdb->fetch_accounts( );
		$this->account_count = count($accounts);
		if(!is_array($sites))
			return;
		$this->sites = array();
		foreach ($sites as $key => $site) {
			$class_name = !isset($site['class_name']) || isset($site['class_name']) && empty($site['class_name']) ? 
							$this->default_site_class : 
							$site['class_name'];
			$site['api_required'] = isset($site['api_required']) ? $site['api_required'] : false;
			if(isset($site['class_name']))
				unset($site['class_name']);
			$this->sites[$key] = $this->new_site($class_name, $site, $accounts);
		}
	}
	
	public function all() {
		return $this->sites;
	}

	public function find($slug) {
		return isset($this->sites[$slug]) ? $this->sites[$slug] : Null;
	}

	public function new_site($class_name, $attributes = array(), $accounts = array()) {
		return new $class_name( $this->ssc, $attributes, $accounts);
	}

	public function to_array($with_hidden = false) {
		$sites = array();
		foreach ($this->all() as $slug => $site) {
			$sites[$slug] = $site->to_array($with_hidden); 
		}
		return $sites;
	}

}
