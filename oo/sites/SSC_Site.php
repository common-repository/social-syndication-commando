<?php

class SSC_Site { 
	protected $ssc;
	protected $slug = '';
	protected $name = '';
	protected $account_class = 'SSC_Account';
	protected $accounts = array();
	public $accounts_count = 0;
	protected $hidden = array('account_class');

	public function __construct($ssc, $attributes = array(), $accounts = array()) {
		$this->ssc = $ssc;
		$this->fill($attributes);
		$this->set_accounts($accounts);
	}

	protected function count_accounts(){
		$this->accounts_count = count($this->accounts);
	}

	public function set_accounts($accounts) {
		
		foreach ($accounts as $account) {
			if($account['social'] == $this->slug){
				$new_account = $this->new_account($account);
				$this->accounts[$new_account->id] = $new_account; 
			}
		}
		$this->count_accounts();
	}

	public function add_account($attributes) {
		$account = $this->new_account($attributes);

		$id = $this->db_insert_account($this->check_user_token($account), $account);
		if(!is_null($id)) {
			$account->id = $id;
			$this->accounts[$id] = $account;
			return $account->to_array();
		}
		$this->count_accounts();
		return $id;		
	}

	public function new_account($attributes) {
		$class_name = $this->account_class;
		return new $class_name($attributes);
	}

	public function check_user_token($account) {
		$check_func = $this->slug. '_check';
		return $this->ssc->sscpost->$check_func( $account->username, $account->password );
	}

	public function db_insert_account($flag, $account) {
		if( $flag )
			return $this->ssc->sscdb->insert_user_data( $account->to_array(true) );
		
		return Null;
		
	    	
	}

	public function fill($attributes) {
		foreach ($attributes as $key => $value) {
			$this->$key = isset($this->$key) && is_null($value) ? $this->$key : $value;
		}

	}

	public function delete_account($id) {
		$account = $this->new_account(array('id' => $id));
		$flag = $this->ssc->sscdb->delete_user_data($account->to_array());

		if($flag)
			return true;
		return false;
	}

	public function change_authority($id, $value){
		$this->ssc->sscdb->change_authority( $id, $this->slug, $value );
		return true;
	}

	protected function hide_property($property) {
		$this->hidden[] = $property;
	}

	public function to_array($with_hidden = false) {
		$properties = array();
		$non_props = array('hidden');
		foreach (get_object_vars($this) as $property => $value) {
			if($property == 'accounts'){
				$value = $this->accounts_to_array($with_hidden);
			}

			if(!$with_hidden && !in_array($property, $this->hidden) && !in_array($property, $non_props)) {
				$properties[$property] = $value;
			}
			elseif ($with_hidden && !in_array($property, $non_props)) {
				$properties[$property] = $value;
			}
		}
		return $properties;
	}

	public function accounts_to_array($with_hidden = false){
		$accounts = array();
		foreach ($this->accounts as $id => $account) {
			$accounts[$id] = $account->to_array($with_hidden);
		}
		return $accounts;
	}

}