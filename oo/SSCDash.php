<?php

add_action( 'init', 'init_sessions');

if (!function_exists('init_sessions')){
	function init_sessions() {
		if (!session_id()) {
			session_start();
		}
	}
}
class SSCDash{
	private $ssc;
	private $menu_capability = 'manage_options';
	private $account_authorise_error = 'Something went wrong. Reload the page and try again.';
	private $js_alerts = array('error' => array(), 'success' => array());
	public function __construct( $ssc ){
		global $wpdb;
		$this->ssc = $ssc;
		$this->wpdb = $wpdb;

		add_action('admin_menu', array($this, 'add_admin_menus' ) );
		if(isset($_GET['ssc_export_links']) && $_GET['ssc_export_links'] ){
			$this->export_links();
		}
		if(isset($_GET['ssc_export_accounts']) && $_GET['ssc_export_accounts'] ){
			$this->export_accounts();
		}
	}

	public function add_admin_menus() {
		$menu_page = add_menu_page(
				'Social Syndication Commando',
				'Social Syndication Commando',
				$this->menu_capability,
				'sscsettings',
				array( $this, 'print_settings'),
				$this->ssc->plugin_url.'/views/images/icon.ico' 
		);		
		$queue_sub_menu = add_submenu_page(
				'sscsettings', 
				'Post Queue',
				'Post Queue',
				$this->menu_capability,
				'sscqueue', 
				array( $this, 'print_queue' )
		);
		$import_sub_menu = add_submenu_page(
				'sscsettings', 
				'Import Accounts',
				'Import Accounts',
				$this->menu_capability,
				'sscimport', 
				array( $this, 'print_import' )
		);


	        add_action("admin_print_styles-$menu_page",  array( $this, 'load_styles' ));
	        add_action("admin_print_scripts-$menu_page", array( $this, 'load_scripts' ));

	        add_action("admin_print_styles-$import_sub_menu",  array( $this, 'load_styles' ));
	        add_action("admin_print_styles-$api_sub_menu",  array( $this, 'load_styles' ));
	        add_action("admin_print_styles-$queue_sub_menu",  array( $this, 'load_styles' ));


		if(isset($_GET['sscredon']) && $_GET['sscredon'] == 'facebook'){
			$this->facebook_verifier();
		}
		if(isset($_GET['sscfacebook']) && $_GET['sscfacebook'] = 'facebook' ){
			$this->facebook_register();
		}
		if(isset($_GET['sscredon']) && $_GET['sscredon'] == 'wordpress'){
			$this->wordpress_verifier();
		}
		if(isset($_GET['sscwordpress']) && $_GET['sscwordpress'] = 'wordpress' ){
			$this->wordpress_register();
		}
		if(isset($_GET['sscredon']) && $_GET['sscredon'] == 'tumblr'){
			$this->tumblr_verifier();
		}
		if(isset($_GET['ssctumblr']) && $_GET['ssctumblr'] = 'tumblr'){
			$this->tumblr_register();
		}
		if(isset($_GET['sscredon']) && $_GET['sscredon'] == 'plurk'){
			$this->plurk_verifier();
		}
		if(isset($_GET['sscplurk']) && $_GET['sscplurk'] = 'plurk'){
			$this->plurk_register();
		}
		if(isset($_GET['sscredon']) && $_GET['sscredon'] == 'twitter'){
			$this->twitter_verifier();
		}
		if(isset($_GET['ssctwitter']) && $_GET['ssctwitter'] = 'twitter'){
			$this->twitter_register();
		}
		if(isset($_GET['sscredon']) && $_GET['sscredon'] == 'linkedin'){
			$this->linkedin_verifier();
		}	
		if(isset($_GET['ssclinkedin']) && $_GET['ssclinkedin'] = 'linkedin'){
			$this->linkedin_register();
		}
		if(isset($_GET['sscpostnow']) ){
			$this->ssc->sscpost->process_queue( $_GET['sscpostnow'] );
			$redirect_url = "admin.php?page=sscqueue";
			wp_redirect( $redirect_url );
		}
		if(isset($_GET['sscprocessqueue']) ){
			$this->ssc->sscpost->process_queue(  );
			$redirect_url = "admin.php?page=sscqueue";
			wp_redirect( $redirect_url );
		}
		if(isset($_GET['sscprocesstwitter']) ){
			$this->ssc->sscpost->process_twitter_queue( );
			$redirect_url = "admin.php?page=ssctwt";
			wp_redirect( $redirect_url );
		}
		if(isset($_GET['ssctwitterfollow']) ){
			$this->ssc->sscpost->process_twitter_queue( $_GET['ssctwitterfollow'] );
			$redirect_url = "admin.php?page=ssctwt";
			wp_redirect( $redirect_url );
		}
		if(isset($_GET['ssctwitterdelete']) ){
			$this->ssc->sscdb->delete_twitter_item( $_GET['ssctwitterdelete'] );
			$redirect_url = "admin.php?page=ssctwt";
			wp_redirect( $redirect_url );
		}
		if(isset($_GET['sscdeletequeue']) ){
			$this->ssc->sscdb->delete_queue_item( $_GET['sscdeletequeue'] );
			$redirect_url = "admin.php?page=sscqueue";
			wp_redirect( $redirect_url );
		}
		if(isset($_GET['sscaccountdelete']) ){
			$this->ssc->sscdb->delete_user_data( array( 'id' => $_GET['sscaccountdelete'] ) );
			$redirect_url = "admin.php?page=sscaccount";
			wp_redirect( $redirect_url );
		}
		if(isset($_GET['sscauthority']) ){
			$this->ssc->sscdb->change_authority( $_GET['sscauthority'], $_GET['sscsocial'], $_GET['sscval'] );
			$redirect_url = "admin.php?page=sscaccount";
			wp_redirect( $redirect_url );
		}
	}
	public function facebook_verifier(){	
    		$app_id 	= $this->ssc->get_option('facebook_api_key');
		$url 		= $this->ssc->get_url();
		$callback_url 	= urlencode( add_query_arg( 
					array('sscredon' => NULL, 'sscfacebook' => 'facebook'), $url) );
		$redirect_url  	= "https://www.facebook.com/dialog/oauth?".
					"client_id={$app_id}&redirect_uri={$callback_url}&".
					"response_type=code&". 						"scope=publish_actions,publish_stream,offline_access,".
						"read_stream,email,user_groups,manage_pages";

		$this->ssc->add_option('facebook_redirect_url', $callback_url);
		wp_redirect($redirect_url);
	}
	public function facebook_register(){
		if(isset($_GET['code'])){
			$app_id	= $this->ssc->get_option('facebook_api_key');
			$app_secret = $this->ssc->get_option('facebook_api_secret');
			$callback_url = $this->ssc->get_option('facebook_redirect_url');
			$code 	= $_GET['code'];
			$request_url  	= "https://graph.facebook.com/oauth/access_token?".
 						"client_id={$app_id}".
						"&redirect_uri={$callback_url}".
						"&client_secret={$app_secret}".
						"&code={$code}";
			$response = file_get_contents( $request_url );

			if( strpos( $response, "&expires=" ) ){
				$response = explode("&expires=", $response );
				$token_str = $response[0];
				$access_token = str_replace("access_token=", "", $token_str );
				$query = urlencode("SELECT uid, name FROM user WHERE uid=me()");
				$query_url = "https://graph.facebook.com/me?fields=id,name".
					"&access_token=" . urlencode($access_token);
				$response = file_get_contents($query_url);
				$response = json_decode($response, true );
				if( count( $response ) ){
					$uid = $response['id'];
					$username = $response['name'];
					$token['social'] = 'facebook';
					$token['username'] = trim( $username );
					$token['access_key'] = $app_id;
					$token['access_secret'] = $app_secret;
					$token['access_token'] = trim($access_token);
					$token['access_token_secret'] = trim($uid);
					$id = $this->ssc->sscdb->insert_user_data( $token );
					$url 		= $this->ssc->get_url();
					$redirect_url 	= add_query_arg( array(	'sscfacebook' => NULL, 
										'code' => NULL,
										'facebook_display' => 1), $url);
			    		wp_redirect( $redirect_url );
				}
			}
		}
	}
	public function linkedin_verifier(){
	
		$key 		= $this->ssc->get_option('linkedin_api_key');
		$secret 	= $this->ssc->get_option('linkedin_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'ps07ex1gehnn';
		$api_secret    	= (strlen($secret) > 5)? $secret : '6TaG5UfQRYb7tcEJ';

		$API_CONFIG = array(
			'appKey'       => $api_key,
			'appSecret'    => $api_secret,
			'callbackUrl'  => NULL 
		);
		define('CONNECTION_COUNT', 20);
		define('PORT_HTTP', '80');
		define('PORT_HTTP_SSL', '443');
		define('UPDATE_COUNT', 10);

		$_REQUEST[LINKEDIN::_GET_TYPE] = (isset($_REQUEST[LINKEDIN::_GET_TYPE])) ? 
						$_REQUEST[LINKEDIN::_GET_TYPE] : '';


		if($_SERVER['HTTPS'] == 'on') {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}
		
		$url = $protocol . '://' . $_SERVER['SERVER_NAME'] . 
					((($_SERVER['SERVER_PORT'] != PORT_HTTP) || 
					($_SERVER['SERVER_PORT'] != PORT_HTTP_SSL)) ? ':' .
					$_SERVER['SERVER_PORT'] : '') . 
					$_SERVER['PHP_SELF'] . '?' . 
					LINKEDIN::_GET_TYPE . '=initiate&' . 
					LINKEDIN::_GET_RESPONSE . '=1';
		$callback_url 	= add_query_arg(array('page' => $_REQUEST['page'], 
					'sscredon' => NULL, 'ssclinkedin' => 'linkedin'), $url);
		$API_CONFIG['callbackUrl'] = $callback_url;

		$OBJ_linkedin = new LinkedIn($API_CONFIG);

		$_GET[LINKEDIN::_GET_RESPONSE] = (isset($_GET[LINKEDIN::_GET_RESPONSE])) ? 
					$_GET[LINKEDIN::_GET_RESPONSE] : '';

		if(!$_GET[LINKEDIN::_GET_RESPONSE]) {
			$response = $OBJ_linkedin->retrieveTokenRequest();
     
			if($response['success'] === TRUE) {
		          $_SESSION['oauth']['linkedin']['request'] = $response['linkedin'];
          
		          wp_redirect( LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token']);
        		} else {
          			echo "Somethin went wrong";
			}
		}

	}
	public function linkedin_register(){
		$key 		= $this->ssc->get_option('linkedin_api_key');
		$secret 	= $this->ssc->get_option('linkedin_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'ps07ex1gehnn';
		$api_secret    	= (strlen($secret) > 5)? $secret : '6TaG5UfQRYb7tcEJ';

		$API_CONFIG = array(
			'appKey'       => $api_key,
			'appSecret'    => $api_secret,
			'callbackUrl'  => NULL 
		);

		$OBJ_linkedin = new LinkedIn($API_CONFIG);
		
 		$response = $OBJ_linkedin->retrieveTokenAccess(
					$_SESSION['oauth']['linkedin']['request']['oauth_token'], 
					$_SESSION['oauth']['linkedin']['request']['oauth_token_secret'],
					$_GET['oauth_verifier']);
        
		if( $response['success'] === TRUE) {

			$_SESSION['oauth']['linkedin']['access'] = $response['linkedin'];
          		$_SESSION['oauth']['linkedin']['authorized'] = TRUE;
			$oauth_verifier		= $_GET['oauth_verifier'];
			$access_token		= $_SESSION['oauth']['linkedin']['access']['oauth_token'];
			$access_token_secret  	= $_SESSION['oauth']['linkedin']['access']['oauth_token_secret'];
		
			$token = array();
			$token['social'] = 'linkedin';
			$token['username'] = trim( $access_token );
			$token['access_key'] = $api_key;
			$token['access_secret'] = $api_secret;
			$token['access_token'] = trim($access_token);
			$token['access_token_secret'] = trim($access_token_secret);
			if( strlen( $token['username'] ) ){
				$id = $this->ssc->sscdb->insert_user_data( $token );		
			}
			$url 		= $this->ssc->get_url();
			$redirect_url 	= add_query_arg( array(	'lType' => NULL,
								'lResponse' => NULL,
								'ssclinkedin' => NULL,  
								'oauth_verifier' => NULL,
								'oauth_token' => NULL,
								'linkedin_display' => 1), $url);
	    	//wp_redirect( $redirect_url );
	    	$this->account_authorised('linkedin');
        	} else {
          		echo "Somethin went wrong";
		}
	}
	public function tumblr_verifier(){

		$key 		= $this->ssc->get_option('tumblr_api_key');
		$secret 	= $this->ssc->get_option('tumblr_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'C7ZmSOWQ5gC7qR6672VqxRT5FVc7P1PgahktLqNHxTSD3I80TP';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'ofM0w1KpHqKdTtPiME8Tr2BWTAltkGZRSvv8BVX0vkTDPRQWvR';
		$tumblr = new Tumblr( $api_key, $api_secret );

		$url = $this->ssc->get_url();

		$callback_url = add_query_arg(array('sscredon' => NULL, 'ssctumblr' => 'tumblr'), $url);	
		$request_token = $tumblr->getRequestToken( $callback_url );

		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		if($tumblr->http_code==200){
	    		$temp_url = $tumblr->getAuthorizeURL($request_token['oauth_token']);
	    		wp_redirect( $temp_url );
		}else{
	    		die('Something wrong happened.');
		}
	}
	public function tumblr_register(){
		$key 		= $this->ssc->get_option('tumblr_api_key');
		$secret 	= $this->ssc->get_option('tumblr_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'C7ZmSOWQ5gC7qR6672VqxRT5FVc7P1PgahktLqNHxTSD3I80TP';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'ofM0w1KpHqKdTtPiME8Tr2BWTAltkGZRSvv8BVX0vkTDPRQWvR';
		$tumblr = new Tumblr( $api_key, $api_secret,
					$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		$access_token = $tumblr->getAccessToken($_GET['oauth_verifier']);
		$user_info = $tumblr->post('user/info'); 

		if(isset($user_info->error)){
			$this->tumblr_verifier();
		}else{
			$token = array();
			$token['social'] = 'tumblr';
			$token['username'] = trim( $user_info->response->user->name );
			$token['access_key'] = $api_key;
			$token['access_secret'] = $api_secret;
			$token['access_token'] = trim($access_token['oauth_token']);
			$token['access_token_secret'] = trim($access_token['oauth_token_secret']);
			if( strlen( $token['username'] ) ){
				$id = $this->ssc->sscdb->insert_user_data( $token );		
			}
			$url 		= $this->ssc->get_url();
			$redirect_url 	= add_query_arg( array(	'ssctumblr' => NULL, 
								'oauth_verifier' => NULL,
								'oauth_token' => NULL,
								'tumblr_display' => 1), $url);
	    	$this->account_authorised('tumblr');	
		}
	}
	public function plurk_verifier(){
		$key 		= $this->ssc->get_option('plurk_api_key');
		$secret 	= $this->ssc->get_option('plurk_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'CFYmuki6slva';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'zCd0lC1CVygMPE6PHBkUtUOW9355A3Wd';

		$plurk = new Plurk( $api_key, $api_secret );
		$url = $this->ssc->get_url();

		$callback_url = add_query_arg(array('sscredon' => NULL, 'sscplurk' => 'plurk'), $url);	
		$request_token = $plurk->getRequestToken( $callback_url );

		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		if($plurk->http_code==200){
	    		$temp_url = $plurk->getAuthorizeURL($request_token['oauth_token']); 
	    		wp_redirect( $temp_url );
		}else{
	    		die('Something wrong happened.');  
		}
	}
	public function plurk_register(){
		$key 		= $this->ssc->get_option('plurk_api_key');
		$secret 	= $this->ssc->get_option('plurk_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'CFYmuki6slva';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'zCd0lC1CVygMPE6PHBkUtUOW9355A3Wd';

		$plurk = new Plurk( $api_key, $api_secret,
				$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
		$access_token = $plurk->getAccessToken($_GET['oauth_verifier']);
		$user_info = $plurk->get('APP/Profile/getOwnProfile'); 

		if(isset($user_info->error)){
			$this->plurk_verifier();
		}else{
			$token = array();
			$token['social'] = 'plurk';
			$token['username'] = trim($user_info->user_info->nick_name);
			$token['access_key'] = $api_key;
			$token['access_secret'] = $api_secret;
			$token['access_token'] = trim($access_token['oauth_token']);
			$token['access_token_secret'] = trim($access_token['oauth_token_secret']);
			if( strlen( $token['username'] ) ){
				$id = $this->ssc->sscdb->insert_user_data( $token );		
			}
			$url 		= $this->ssc->get_url();
			$redirect_url 	= add_query_arg( array(	'sscplurk' => NULL, 
								'oauth_verifier' => NULL,
								'oauth_token' => NULL,
								'plurk_display' => 1), $url);
			$this->account_authorised('plurk');	
	    	//	wp_redirect( $redirect_url );
		}
	}
	public function friendfeed_verifier(){

		$key 		= $this->ssc->get_option('twitter_api_key');
		$secret 	= $this->ssc->get_option('twitter_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'wlne3127Fr7EbYCXy52zMA';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'lCOShmCQrPgGNQTM0HkT1T9TWwP8evxXOYIrI78Y';

		$twitter = new Twitter( $api_key, $api_secret );
		$url = $this->ssc->get_url();

		$callback_url = add_query_arg(array('sscredon' => NULL, 'ssctwitter' => 'twitter'), $url);	
		$request_token = $twitter->getRequestToken( $callback_url );

		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		if($twitter->http_code==200){
	    		$temp_url = $twitter->getAuthorizeURL($request_token['oauth_token']); 
	    		wp_redirect( $temp_url ); 
		} else {  
	    		die('Something wrong happened.');  
		}  
	}
	public function friendfeeed_register(){

		$key 		= $this->ssc->get_option('twitter_api_key');
		$secret 	= $this->ssc->get_option('twitter_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'wlne3127Fr7EbYCXy52zMA';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'lCOShmCQrPgGNQTM0HkT1T9TWwP8evxXOYIrI78Y';

		$twitter = new Twitter( $api_key, $api_secret, 
					$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		$access_token = $twitter->getAccessToken($_GET['oauth_verifier']);
		$user_info = $twitter->get('account/verify_credentials'); 

		if(isset($user_info->error)){
			$this->twitter_verifier();
		}else{
			$token = array();
			$token['social'] = 'twitter';
			$token['username'] = trim($user_info->screen_name);
			$token['access_key'] = $api_key;
			$token['access_secret'] = $api_secret;
			$token['access_token'] = trim($access_token['oauth_token']);
			$token['access_token_secret'] = trim($access_token['oauth_token_secret']);
			$id = $this->ssc->sscdb->insert_user_data( $token );
			$url 		= $this->ssc->get_url();
			$redirect_url 	= add_query_arg( array(	'ssctwitter' => NULL, 
								'oauth_verifier' => NULL,
								'oauth_token' => NULL,
								'twitter_display' => 1), $url);
			$this->account_authorised('friendfeed');	
	    	//	wp_redirect( $redirect_url );
		}
	}
	public function wordpress_verifier(){
		$client_id 	= $this->ssc->get_option('wordpress_api_key');
		$url 		= $this->ssc->get_url();
		$callback_url 	= urlencode( get_option("siteurl"). "/wp-admin/admin.php?page=sscsettings&sscwordpress=wordpress" );
		$redirect_url 	= "https://public-api.wordpress.com/oauth2/authorize?".
					"client_id=$client_id&redirect_uri=$callback_url&response_type=code";
	    	wp_redirect( $redirect_url );  
	}
	public function wordpress_register(){
		$client_id 	= $this->ssc->get_option('wordpress_api_key');
		$client_secret 	= $this->ssc->get_option('wordpress_api_secret');
		$callback_url 	=  get_option("siteurl") . "/wp-admin/admin.php?page=sscsettings&sscwordpress=wordpress";
		if( isset( $_GET['code']) ){
			$curl = curl_init( "https://public-api.wordpress.com/oauth2/token" );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
			    'client_id' => $client_id,
			    'redirect_uri' => $callback_url,
			    'client_secret' => $client_secret,
			    'code' => $_GET['code'],
			    'grant_type' => 'authorization_code'
			) );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
			$auth = curl_exec( $curl );
			$secret = json_decode($auth);
			$access_key = $secret->access_token;
			$blog_id = $secret->blog_id;
			$blog_url = $secret->blog_url;
			if( strlen( $access_key ) ){
				$token = array();
				$token['social'] = 'wordpress';
				$token['username'] = trim($blog_url);
				$token['access_key'] = $client_id;
				$token['access_secret'] = $client_secret;
				$token['access_token'] = trim($access_key);
				$token['access_token_secret'] = trim($blog_id);
				$id = $this->ssc->sscdb->insert_user_data( $token );
			}
		}
		$url 		= $this->ssc->get_url();
		$redirect_url 	= add_query_arg( array(	'sscwordpress' => NULL, 
							'code' => NULL,
							'state' => NULL,
							'display' => 'wordpress'), $url);
	    	wp_redirect( $redirect_url );  
	}
	public function twitter_verifier(){

		$key 		= $this->ssc->get_option('twitter_api_key');
		$secret 	= $this->ssc->get_option('twitter_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'wlne3127Fr7EbYCXy52zMA';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'lCOShmCQrPgGNQTM0HkT1T9TWwP8evxXOYIrI78Y';

		$twitter = new Twitter( $api_key, $api_secret );
		$url = $this->ssc->get_url();

		$callback_url = add_query_arg(array('sscredon' => NULL, 'ssctwitter' => 'twitter'), $url);	
		$request_token = $twitter->getRequestToken( $callback_url );

		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		if($twitter->http_code==200){
	    		$temp_url = $twitter->getAuthorizeURL($request_token['oauth_token']); 
	    		wp_redirect( $temp_url ); 
		} else {  
	    		$this->account_authorise_error('twitter');
		}  
	}
	public function twitter_register(){

		$key 		= $this->ssc->get_option('twitter_api_key');
		$secret 	= $this->ssc->get_option('twitter_api_secret');
	
		$api_key 	= (strlen($key) > 5)? $key : 'wlne3127Fr7EbYCXy52zMA';
		$api_secret    	= (strlen($secret) > 5)? $secret : 'lCOShmCQrPgGNQTM0HkT1T9TWwP8evxXOYIrI78Y';

		$twitter = new Twitter( $api_key, $api_secret, 
					$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		$access_token = $twitter->getAccessToken($_GET['oauth_verifier']);
		$user_info = $twitter->get('account/verify_credentials'); 

		if(isset($user_info->error)){
			$this->twitter_verifier();
		}else{
			$token = array();
			$token['social'] = 'twitter';
			$token['username'] = trim($user_info->screen_name);
			$token['access_key'] = $api_key;
			$token['access_secret'] = $api_secret;
			$token['access_token'] = trim($access_token['oauth_token']);
			$token['access_token_secret'] = trim($access_token['oauth_token_secret']);
			$id = $this->ssc->sscdb->insert_user_data( $token );
			$url 		= $this->ssc->get_url();
			$redirect_url 	= add_query_arg( array(	'ssctwitter' => NULL, 
								'oauth_verifier' => NULL,
								'oauth_token' => NULL,
								'twitter_display' => 1), $url);
	    	//wp_redirect( $redirect_url );
	    	$this->account_authorised('twitter');
		}
	}	

	public function account_authorised($site_slug){
		wp_redirect(get_option('siteurl') . '/wp-admin/admin.php?page=sscsettings#/sites/' . $site_slug . '/accounts/1' );
	}

	public function account_authorise_error($site_slug, $error = NULL){
		$error = is_null($error) ? urlencode($this->account_authorise_error) : urlencode($error);
		wp_redirect(get_option('siteurl') . '/wp-admin/admin.php?page=sscsettings#/sites/' . $site_slug . '/add/1/'. $error );
	}

	public function add_js_error_alert($message){
		$this->js_alerts['error'][] = $message;
	}

	public function add_js_success_alert($message){
		$this->js_alerts['success'][] = $message;
	}
	public function load_styles(){

		wp_enqueue_style('thickbox');
		wp_enqueue_style("ssc-bootstrap", 
				"{$this->ssc->plugin_url}views/css/bootstrap.min.css" );
		wp_enqueue_style("ssc-bootstrap-flat", 
				"{$this->ssc->plugin_url}views/css/bootstrap-flat.min.css" );

		wp_enqueue_style("ssc-settings-style", 
				"{$this->ssc->plugin_url}views/css/ssc-settings.css" );
	}

	public function get_jsapp_vars(){
		return array(
					'isDebug' 		=>  $this->ssc->is_debug ? 1 : 0,
					'adminUrl'		=>  get_option('siteurl') . '/wp-admin/admin.php',
					'viewsUrl'		=>  $this->ssc->plugin_url . 'views/',
					'pushedAlerts'	=> 	$this->js_alerts,
					'pluginName'	=>	$this->ssc->plugin_name,
					'blogName'		=>  get_option('blogname')
			);
	}

	public function print_js_vars() {
		?>
		<script type="text/javascript">
			var sscAppVars = <?php echo json_encode($this->get_jsapp_vars());  ?>;
		</script>

		<?php
	}

	public function load_scripts(){ 
		$this->print_js_vars();
		$scripts = $this->ssc->get_config('ng-ssc');
		$js_url = $this->ssc->plugin_url . 'views/js/';
		foreach ($scripts as $key => $script) {
			wp_enqueue_script(
							$key, 
							$js_url . $script['path'] , 
							$script['deps'], $script['ver'], $script['footer']);
		}
		
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
	}

	public function print_settings(){
		include( "{$this->ssc->plugin_dir}views/index.php" );
	}
	public function print_queue(){
		include( "{$this->ssc->plugin_dir}views/queue.php" );
	}
	public function print_import(){
		include( "{$this->ssc->plugin_dir}views/import.php" );
	}
	public function print_twitter(){
		include( "{$this->ssc->plugin_dir}views/twitter.php" );
	}
	public function export_accounts(){
		$tmp_name = md5(
				time() . 
				rand( 0, 2147483647) .
				rand( 0, 2147483647) .
				rand( 0, 2147483647) 
		);
		header("Content-Type: application/csv");
		header("Content-Disposition: attachment; filename=\"ssc-accounts-$tmp_name.csv\"");
		$accounts = $this->ssc->sscdb->fetch_accounts( );
		$data = "Site,".
			"Username,".
			"Password,".
			"API Key/Access Key,".
			"Access Secret,".
			"Access Token,".
			"Access Token Secret,".
			"Exported\r\n";
		foreach( $accounts as $account ){
			$social = ucwords($account['social']);
			$username = $account['username'];
			$password = $account['password'];
			$access_key = $account['access_key'];
			$access_secret = $account['access_secret'];
			$access_token = $account['access_token'];
			$access_token_secret = $account['access_token_secret'];
			$data .= "$social,".
				"$username,".
				"$password,".
				"$access_key,".
				"$access_secret,".
				"$access_token,".
				"$access_token_secret,".
				"YES\r\n";
		}
		if( $data ){
			echo $data;
		}
		exit();
	}
	public function export_links(){
		$tmp_name = md5(
				time() . 
				rand( 0, 2147483647) .
				rand( 0, 2147483647) .
				rand( 0, 2147483647) 
		);
		header("Content-Type: application/csv");
		header("Content-Disposition: attachment; filename=\"ssc-links-$tmp_name.csv\"");
		$queues = $this->ssc->sscdb->fetch_all_queue_items( );
		$data = "Site,".
			"Action,".
			"Link Origin,".
			"Link Destination,".
			"Date Posted\r\n";
		foreach( $queues as $queue ){
			$social = ucwords( $queue['social'] );
			$action = ucwords( $queue['action'] );
			$link = trim( $queue['link'] );
			$url = trim( $queue['url'] );
			if( $queue['posted_timestamp'] > 0 ){ 
				$time = date("D M j G:i:s T Y", $queue['posted_timestamp'] );
			} else {
				$time = 'Future';
			}
			$data .= "$social,".
				"$action,".
				"$link,".
				"$url,".
				"$time\r\n";
		}
		if( $data ){
			echo $data;
		}
		exit();
	}
	public function import_accounts(){
		$count = 0;
		if(isset($_POST['stc_import'])){
			$filename = $_FILES['uploadfile']['tmp_name'];
			if( mb_strlen( $filename ) ){
				$count = $this->ssc->csv_add( $filename );
				if($count > 0)
				{
					$this->add_js_success_alert( $count . ' accounts add.');
				}
				else{
					$this->add_js_error_alert('Error: Make sure accounts in your CSV file are in correct format.');
				}
				
			}
			else{
				$this->add_js_error_alert('Error: Upload a valid CSV file and try again.');
			}

		}
		return $count;
	}
}
