<?php

class SSC_Account { 
	protected $id;
	protected $authority = 0;
	protected $social = '';
	protected $username = '';
	protected $password = '';
	protected $access_key = '';
	protected $access_secret = '';
	protected $access_token = '';
	protected $access_token_secret = '';

	protected $hidden = array(
		'password',
		'access_key',
		'access_secret',
		'access_token',
		'access_token_secret'
	);

	public function __construct($attributes = array()) {
		$this->fill($attributes);
	}

	public function __get($attribute) {
		return $this->$attribute;
	}

	public function __set($attribute, $value){
		$this->$attribute = $value;
	}

	public function fill($attributes) {
		if(is_object($attributes))
			$attributes = (array) $attributes;
		
		foreach ($attributes as $key => $value) {
			$this->$key = $value;
		}

	}

	protected function hide_property($property) {
		$this->hidden[] = $property;
	}

	public function to_array($with_hidden = false) {
		$properties = array();
		$non_props = array('hidden');
		foreach (get_object_vars($this) as $property => $value) {
			if(!$with_hidden && !in_array($property, $this->hidden) && !in_array($property, $non_props)) {
				$properties[$property] = $value;
			}
			elseif ($with_hidden && !in_array($property, $non_props)) {
				$properties[$property] = $value;
			}
		}
		return $properties;
	}

}
