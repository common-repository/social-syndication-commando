<?php

class SSC_SiteDiigo extends SSC_Site {

	protected function get_access_key() {
		return strlen( $this->ssc->get_option( 'diigo_api_key' ) ) > 5 ? 
						trim( $this->ssc->get_option( 'diigo_api_key' ) ) :
						'd24acea948316353';
	}

	public function check_user_token($account) {
		$check_func = $this->slug. '_check';
		$account->access_key = $this->get_access_key();
		return $this->ssc->sscpost->$check_func( $account->access_key, $account->username , $account->password );
		
	}

	public function db_insert_account($flag, $account) {
		if(count($flag['http_code'] == 200 ))
			return $this->ssc->sscdb->insert_user_data( $account->to_array(true) );
			
		return Null;

	}


}