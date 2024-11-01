<?php
	$is_twitter_page = '';
	$is_post_queue_page = 'active';
	include 'static-header.php';
?>
<div class="page-header">
        <h1>
          <i class="glyphicon glyphicon-th-list"></i>
          <span>Queued Post Items </span>
        </h1>
      </div>

<div class="row">
  <div class="col-xs-6 col-md-9">
<?php
	$queue_table = new SSCQueue( $this->ssc->sscdb->fetch_all_queue_items( ) );
	$queue_table->prepare_items();

	echo '<form method="post" id="dtc-form" action="">';
	echo '<a class="button-secondary" href="admin.php?page=sscqueue&sscprocessqueue=1">Process Next Queue Item Now</a>';
	echo '<a class="button-secondary" style="float:right" href="'. add_query_arg( array( 'ssc_export_links' => TRUE ) ) .'">Export Links</a>';
	$queue_table->display();
	echo '</form>';	

?>
  </div>
  <div class="col-xs-6 col-md-3">
    <a target="_blank" href="http://anthonyhayes.me/social-account-commando/" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/sac.png"; ?>" alt="Social account creator">Social account creator
    </a><br><br>
    <a target="_blank" href="http://anthonyhayes.me/products/web-2-0-commando/" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/web.png"; ?>" alt="Web 2.0 Commando">Web 2.0 Commando
    </a><br><br>
    <a target="_blank" href="http://anthonyhayes.me/marverickseoweeklygiveaways" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/mav.png"; ?>" alt="Maverick SEO Podcast">Maverick SEO Podcast
    </a><br><br>
    <a target="_blank" href="http://massmediaseo.com" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/mas-media.png"; ?>" alt="Massmedia seo press release">Massmedia seo press release
    </a>
  </div>
</div>
  </div>
  </div>
</div>
