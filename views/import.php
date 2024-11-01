<?php 
	if(isset($_POST['stc_import'])){
		$filename = $_FILES['uploadfile']['tmp_name'];
		if( mb_strlen( $filename ) ){
			$count = $this->ssc->csv_add( $filename );			
			$redirect 	= "admin.php?page=sscsettings";
			echo "$count accounts added";
			echo '<script type="text/javascript">';
			echo "location.replace('$redirect');";
			echo '</script>';
			
		}
	}
	$is_twitter_page = '';
	$is_post_queue_page = '';
	$is_account_import_page = 'active';
	include 'static-header.php';
?>
<div class="page-header">
        <h1>
          <i class="glyphicon glyphicon-upload"></i>
          <span>Import Accounts </span>
        </h1>
      </div>
	<div class="callout callout-info">
		<h4> Upload a CSV file containing the accounts.</h4>
		<p>
			<strong> Format: <em class="text-danger">Social Site, Username, Password</em> </strong>
		</p>
	</div>
	<form name="uploadfile" method="POST" enctype="multipart/form-data" accept-charset="utf-8">

	

	       <div class="stc-field">
		<div class="stc-input-wrapper">
		 <input class="stc-input stc-margin" type="file" name="uploadfile"/>
		</div>
	       </div>
	<div style="clear:both">&nbsp;</div>
	<button name="stc_import" class="button-primary stc-right stc-margin"> Submit </button>
	<div style="clear:both">&nbsp;</div>
	</form>
  </div>
  </div>
</div>
