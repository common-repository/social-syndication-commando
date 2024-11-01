<?php

class STC_SiteImporter {

	protected $stc;
	protected $factory;
	protected $upload_dir;
	protected $current_file;

	public function __construct( $stc ){
		$this->stc = $stc;
		$this->factory = $stc->get_sites();
		$this->upload_dir = $this->stc->plugin_dir . 'temp/';
	}


	public function upload($file){
		print_r($file);
	}

}