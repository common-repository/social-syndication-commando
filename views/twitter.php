<?php
	if( isset( $_POST['addtwt'] ) ){
		$query = $_POST['twtparam'];
		$response = $this->stc->stcpost->twitter_searcher( $query );
		//var_dump( $response->statuses );
		if( is_object( $response ) && count( $response->statuses ) ){
			foreach( $response->statuses as $statuses ){

				$this->stc->stcdb->insert_twitter_data(
					array( 
						'username' 	=> $statuses->user->screen_name,
						'twitter_id'	=> $statuses->user->id_str,
						'description'	=> $statuses->user->description,
						'profile_image'	=> $statuses->user->profile_image_url,
						'is_follower'	=> 0,
						'status'	=> 0 )
				);
			}
		}
	}

	require_once "{$this->stc->plugin_dir}oo/STCTwitter.php";
	$twitter_table = new STCTwitter( $this->stc->stcdb->fetch_all_twitter( ) );
	$twitter_table->prepare_items();

	$is_twitter_page = 'active';
	$is_post_queue_page = '';
	include 'static-header.php';
	?>
	<div class="page-header">
        <h1>
          <i class="glyphicon glyphicon-transfer"></i>
          <span> Twitter Queue </span>
        </h1>
      </div>
     <?php
     
	$key 		= $this->stc->get_option('twitter_api_key');
	$secret 	= $this->stc->get_option('twitter_api_secret');
	if( !strlen( $key ) && !strlen( $secret )){
		echo '<p style="color:red">Please add a twitter API Key and API Secret then reauthorise '.
			'your twitter accounts to use this facility</p><p><a target="_blank" '.
			'href="https://dev.twitter.com/apps/new">'.
			'Click here</a> to sign up for new '.
			'API Key and Secret</p>';
	}
	echo '<form method="post" id="dtc-form" action=""><input type="text" name="twtparam">'.
		'<input class="button-primary" type="submit" name="addtwt" value="Search & Add new Tweeters">';
	echo '<a style="float:right" class="button-secondary" href="admin.php?page=stctwt&stcprocesstwitter=1">'.
			'Process Next Queue Item Now</a></form>';
	echo '<form method="post" id="dtc-form" action="">';
	$twitter_table->display();
	echo '</form>';	

?>

</div>
</div>
</div>