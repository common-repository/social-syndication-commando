<?php

class SSCSettings {
	protected $ssc;
	protected $settings;
	public $options = array();
	protected $messages = array('error' => array(), 'success' => array());

	public function __construct($ssc) {
		$this->ssc = $ssc;
		$this->init_settings();
	}

	protected function init_settings() {
		$settings = $this->ssc->get_config('settings');
		$this->settings = array();
		$this->options = array();
		foreach ($settings as $cat_key => $category) {
			$settings[$cat_key]['slug'] = !isset($settings[$cat_key]['slug']) 
								? $this->to_slug($category['title'])
								: $settings[$cat_key]['slug'];
			foreach ($category['stacks'] as $stack_key => $stack) {
				$stack['_index'] = $stack_key;
				$stack['_category'] = $cat_key;
				$settings[$cat_key]['stacks'][$stack_key]= $this->make_stack($stack);
			}
		}
		$this->settings = $settings;
		return $this;
	}
	protected function make_stack($stack){
		if(!isset($stack['slug']))
			$stack['slug'] = $this->to_slug($stack['title']);
		$empties = 0;
		foreach ($stack['options'] as $option_key => $option) {
			$option['data_type'] = isset($option['data_type']) ? $option['data_type'] : '';
			if($option['data_type'] == 'bool'){
				$option['value'] = (bool) $this->ssc->get_option($option['name']);
				$option['display_value'] = $option['value'] ? 'YES' : 'NO';
			}
			else{
				$option['value'] = $this->ssc->get_option($option['name']);
				$option['display_value'] = !empty($option['value']) && strlen($option['value']) > 5 
									? substr($option['value'], 0, 5).'.....'
									: $option['value'];
			}
			$empties += empty($option['value']) ? 1 : 0;
			$stack['options'][$option_key] = $option;
			if(!in_array($option['name'], $this->options))
				$this->options[] = $option['name'];
		}
		$stack['is_ok'] = $empties ? false : true;
		return $this->verify_stack($stack);
	}

	protected function verify_stack($stack){
		$method_name = 'verify_'. $stack['slug'];
		if(method_exists($this, $method_name))
			return $this->$method_name($stack);

		return $stack;
	}
	protected function to_slug($str){
		return strtolower(str_replace(' ', '_', $str));
	}

	public function all() {
		return $this->settings;
	}

	public function save_options($options) {
		foreach (get_object_vars($options) as $name => $value) {
			if(in_array($name, $this->options)){
				$value = is_bool($value)? (int) $value : trim($value);
				$this->ssc->add_option( $name, $value);
			}
		}
		$this->init_settings();
		return true;
	}
	public function save_option($option){
		if(in_array($option['name'], $this->options)){
				$value = is_bool($option['value'])? (int) $option['value'] : trim($option['value']);
				$this->ssc->add_option( $option['name'], $value);
		}
		return $option;
	}
	public function save_stack($stack){
		$new_stack = $stack;
		$new_stack['options'] = array();
		foreach ($stack['options'] as $key => $option) {
			$new_stack['options'][] =$this->save_option($option);
		}
		$eval_method = 'eval_'. $new_stack['slug'];
		if(method_exists($this, $eval_method))
			$eval = $this->$eval_method();

		return array( 
				'messages' 		=> $this->messages, 
				'has_error'		=> count($this->messages['error']),
				'stack' 		=>  $this->make_stack($new_stack));
		
	}
}
