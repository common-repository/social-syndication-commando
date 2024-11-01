<div class="container-fluid clearfix" style="padding-right:10px; background: #f4f4f4; margin-top:10px">
	<div class="panel panel-primary main-container clearfix"  style="background-color: #fcfcfc ">
		<div class="panel-heading main-heading"> 
			<h1 class="main-heading-title"> 
				<img ng-src="{{ $global.urls.icon }}">
				<span ng-bind="$global.app.pluginName"></span>
			</h1>
			<ng-include src="$global.urls.template('shared.nav')"></ng-include>
		</div>
		<div class="panel-body stc-main-body" style="position:relative">
			<div class="page-header">
			  <h1>
			  	<i class="{{ $global.routes.current().icon() }}"></i>
			  	<span ng-bind="$global.routes.current().title()"> </span>
			  	<small ng-bind="$global.routes.current().breadCrumb()"></small>
			  	<ng-include src="$global.urls.template('shared.sub-nav')"></ng-include>
			  </h1>
			</div>
			<ng-include src="$global.urls.template('shared.alerts')"></ng-include>
			<div ui-view></div>	
			
		</div>
	</div>
</div>

<div class="row">
  <div class="col-xs-6 col-md-3">
    <a target="_blank" href="http://anthonyhayes.me/social-account-commando/" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/sac.png"; ?>" alt="...">
    </a>
    <a target="_blank" href="http://anthonyhayes.me/social-account-commando/">Social account creator</a>
  </div>
  <div class="col-xs-6 col-md-3">
    <a target="_blank" href="http://anthonyhayes.me/products/web-2-0-commando/" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/web.png"; ?>" alt="">
    </a>
    <a target="_blank" href="http://anthonyhayes.me/products/web-2-0-commando/">Web 2.0 Commando</a>
  </div>
  <div class="col-xs-6 col-md-3">
    <a target="_blank" href="http://anthonyhayes.me/marverickseoweeklygiveaways" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/mav.png"; ?>" alt="">
    </a>
    <a target="_blank" href="http://anthonyhayes.me/marverickseoweeklygiveaways">Maverick SEO Podcast</a>
  </div>
  <div class="col-xs-6 col-md-3">
    <a target="_blank" href="http://massmediaseo.com" class="thumbnail">
      <img src="<?php echo "{$this->ssc->plugin_url}views/images/mas-media.png"; ?>" alt="">
    </a>
    <a target="_blank" href="http://massmediaseo.com">Massmedia seo press release</a>
  </div>
</div>


