<div class="container-fluid clearfix" style="padding-right:15px; background: #f4f4f4; margin-top:10px">
  <div class="panel panel-primary main-container clearfix"  style="background-color: #fcfcfc ">
    <div class="panel-heading main-heading"> 
      <h3 class="main-heading-title"> 
        <img src="<?php echo $this->ssc->plugin_url.'/views/images/icon.ico' ?>">
      Social Syndication Commando</h3>
      
       <ul class="nav nav-tabs">
          <li>
            <a href="admin.php?page=sscsettings#/sites/all"> <i class="glyphicon glyphicon-user"></i> Accounts</a>
          </li>
          <li  class="<?php echo $is_account_import_page ?>">
            <a href="admin.php?page=sscimport"> <i class="glyphicon glyphicon-upload"></i> Import Accounts </a>
          </li>
          <li class="<?php echo $is_post_queue_page ?>">
            <a href="admin.php?page=sscqueue"> <i class="glyphicon glyphicon-th-list"></i> Post Queue</a>
          </li>
          <li ng-class="{ active:$state.includes('config') }">
            <a href="admin.php?page=sscsettings#/settings/general/"> 
            <i class="glyphicon glyphicon-cog"></i> Settings </a>
          </li>
         
          <li >
            <a href="admin.php?page=sscsettings#/help"> <i class="glyphicon glyphicon-search"></i> Help</a>
          </li>
  </ul>
    </div>
    <div class="panel-body">


      

    
