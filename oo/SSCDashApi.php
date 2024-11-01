<?php

class SSCDashApi {
	protected $ssc;
	protected $sites;
	protected $settings;

	public function __construct($ssc) {
		

		$this->ssc = $ssc;
		$this->disable_errors();
		$this->sites = $ssc->get_sites();
		$this->settings = $ssc->get_ssc_settings();
		$this->add_ajax_actions();
	}
	
	protected function disable_errors(){
		if(!isset($_REQUEST['ssc_api_call']))
			return;
		if(!$this->ssc->is_debug){
			ini_set('display_error', 'Off');
			error_reporting(0);
		}
		
	}

	public function add_ajax_actions() {
		$this->accounts_ajax_actions();
		$this->settings_ajax_actions();
		
	}

	public function accounts_ajax_actions() {
		add_action( 'wp_ajax_ssc_get_all_sites', array( $this, 'get_all_sites' ));
		add_action('wp_ajax_ssc_add_account', array($this, 'add_account'));
		add_action( 'wp_ajax_ssc_delete_account', array( $this, 'delete_account' ));
		add_action( 'wp_ajax_ssc_change_authority', array( $this, 'change_authority' ));
	}

	public function settings_ajax_actions() {
		add_action( 'wp_ajax_ssc_get_all_settings', array( $this, 'get_all_settings' ));
		add_action( 'wp_ajax_ssc_save_stack_options', array( $this, 'save_stack' ));
		
	}

	public function get_all_sites() {
		return $this->return_json($this->sites->to_array());
	}

	public function add_account() {
		if($this->ssc->acc_throt() && $this->sites->account_count >= $this->ssc->acc_throt())
			return $this->return_json(array( 
										'success' => 0, 
										'alerts' => array(
											'error' => array('You can\'t add more accounts. The maximum number is ' . $this->ssc->acc_throt() ))));


		$post_data = $this->ssc->get_json_posted_data();
		$site = $this->sites->find($post_data->social);
		$account = $site->add_account($post_data);
		return is_array($account) 
					? $this->return_json(array( 'success' => 1, 'account' => $account )) 
					: $this->return_json(array( 'success' => 0 ));

	}

	

	public function delete_account(){
		$post_data = $this->ssc->get_json_posted_data();
		$site = $this->sites->find($post_data->slug);
		$deleted = $site->delete_account($post_data->id);
		return $deleted ? $this->return_json(array( 'success' => 1)) :  $this->return_json(array( 'success' => 0 ));
	}

	public function change_authority(){
		$post_data = $this->ssc->get_json_posted_data();
		$site = $this->sites->find($post_data->slug);
		$changed = $site->change_authority($post_data->id, $post_data->value);
		return $changed ? $this->return_json(array( 'success' => 1)) :  $this->return_json(array( 'success' => 0 ));
	}

	public function get_all_settings() {
		return $this->return_json($this->settings->all());
	}

	public function save_stack_options() {

	}
	public function save_stack() {
		$post_data = $this->ssc->get_json_posted_data(true);
		$response = $this->settings->save_stack($post_data);
		return !$response['has_error'] 
					? $this->return_json(array( 
										'success' => 1, 
										'stack' => $response['stack'], 
										'alerts' =>  $response['messages'] )) 
					:  $this->return_json(array( 
										'success' => 0, 
										'stack' => $$response['stack'], 
										'alerts' =>  $response['messages'] ));
	}
	protected function return_json($response) {
		$response = json_encode($response);
		header( "Content-Type: application/json" );
		echo $response;
		exit();
		die();
	   
	 
	}
}
