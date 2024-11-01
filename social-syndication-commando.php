<?php
/*
	Plugin Name: Social Syndication Commando
	Plugin URI: http://anthonyhayes.me/social-syndication-commando/
	Version: 1.1.0
	Description: Plugin for strategic social bookmarking
	Author: Tony Hayes
*/
			
if(!get_option("social-traffic-commando-plain_d81c_activated")){
	

add_action("admin_menu", "activation_menu_a4d5");

function activation_menu_a4d5() {	
	add_object_page( "Social Sydication Commando", "Social Syndication Commando", "activate_plugins", "social-traffic-commando-plain-activation", "activation_page", "");
}
function activation_page() {
    if (!current_user_can("manage_options")) {
        wp_die(__("You do not have sufficient permissions to access this page."));
    }
	else{
		if(isset($_POST["activationkey"]) && $_POST["activationkey"]=="Socisyndlite"){

			add_option("social-traffic-commando-plain_d81c_activated","activated");

			require_once dirname( __FILE__ ) . '/oo/SSC.php';
			require_once dirname( __FILE__ ) . '/oo/SSCDash.php';

			$ssc = new SSC( );
			register_activation_hook( __FILE__, array( $ssc, 'install' ) );
			register_deactivation_hook( __FILE__, array( $ssc, 'uninstall' ) );

			echo "<script>location.replace(\"".get_option("siteurl")."/wp-admin/plugins.php?activate=true\")</script>";
		}
		else{
		
?>
<center>
<h3 style="color:red;">
	<i>Please Subscribe to Social Syndication Commando &amp; Get Regular Updates &amp; Free SEO Training</i></h3>

<br/>
<!-- AWeber Web Form Generator 3.0 -->
<style type="text/css">
#af-form-680538938 .af-body .af-textWrap{width:98%;display:block;float:none;}
#af-form-680538938 .af-body .privacyPolicy{color:#2C4F7F;font-size:11px;font-family:Verdana, sans-serif;}
#af-form-680538938 .af-body a{color:#2C4E7F;text-decoration:underline;font-style:normal;font-weight:normal;}
#af-form-680538938 .af-body input.text, #af-form-680538938 .af-body textarea{background-color:#FFFFFF;border-color:#2C4E7F;border-width:1px;border-style:solid;color:#000000;text-decoration:none;font-style:normal;font-weight:normal;font-size:12px;font-family:Verdana, sans-serif;}
#af-form-680538938 .af-body input.text:focus, #af-form-680538938 .af-body textarea:focus{background-color:#FFFAD6;border-color:#030303;border-width:1px;border-style:solid;}
#af-form-680538938 .af-body label.previewLabel{display:block;float:none;text-align:left;width:auto;color:#000000;text-decoration:none;font-style:normal;font-weight:normal;font-size:12px;font-family:Verdana, sans-serif;}
#af-form-680538938 .af-body{padding-bottom:15px;padding-top:15px;background-repeat:no-repeat;background-position:inherit;background-image:none;color:#2C4F7F;font-size:11px;font-family:Verdana, sans-serif;}
#af-form-680538938 .af-footer{padding-bottom:15px;padding-top:15px;padding-right:15px;padding-left:15px;background-color:#2C4E7F;background-repeat:repeat-x;background-position:top;background-image:url("http://forms.aweber.com/images/forms/modern/blue/footer.png");border-width:1px;border-bottom-style:none;border-left-style:none;border-right-style:none;border-top-style:none;color:#FFFFFF;font-size:12px;font-family:Verdana, sans-serif;}
#af-form-680538938 .af-header{padding-bottom:10px;padding-top:10px;padding-right:10px;padding-left:10px;background-color:#2C4E7F;background-repeat:repeat-x;background-position:bottom;background-image:url("http://forms.aweber.com/images/forms/modern/blue/header.png");border-bottom-style:none;border-left-style:none;border-right-style:none;border-top-style:none;color:#FFFFFF;font-size:16px;font-family:Verdana, sans-serif;}
#af-form-680538938 .af-quirksMode .bodyText{padding-top:2px;padding-bottom:2px;}
#af-form-680538938 .af-quirksMode{padding-right:15px;padding-left:15px;}
#af-form-680538938 .af-standards .af-element{padding-right:15px;padding-left:15px;}
#af-form-680538938 .bodyText p{margin:1em 0;}
#af-form-680538938 .buttonContainer input.submit{background-color:#2c4e7f;background-image:url("http://forms.aweber.com/images/forms/modern/blue/button.png");color:#FFFFFF;text-decoration:none;font-style:normal;font-weight:normal;font-size:14px;font-family:Verdana, sans-serif;}
#af-form-680538938 .buttonContainer input.submit{width:auto;}
#af-form-680538938 .buttonContainer{text-align:right;}
#af-form-680538938 body,#af-form-680538938 dl,#af-form-680538938 dt,#af-form-680538938 dd,#af-form-680538938 h1,#af-form-680538938 h2,#af-form-680538938 h3,#af-form-680538938 h4,#af-form-680538938 h5,#af-form-680538938 h6,#af-form-680538938 pre,#af-form-680538938 code,#af-form-680538938 fieldset,#af-form-680538938 legend,#af-form-680538938 blockquote,#af-form-680538938 th,#af-form-680538938 td{float:none;color:inherit;position:static;margin:0;padding:0;}
#af-form-680538938 button,#af-form-680538938 input,#af-form-680538938 submit,#af-form-680538938 textarea,#af-form-680538938 select,#af-form-680538938 label,#af-form-680538938 optgroup,#af-form-680538938 option{float:none;position:static;margin:0;}
#af-form-680538938 div{margin:0;}
#af-form-680538938 fieldset{border:0;}
#af-form-680538938 form,#af-form-680538938 textarea,.af-form-wrapper,.af-form-close-button,#af-form-680538938 img{float:none;color:inherit;position:static;background-color:none;border:none;margin:0;padding:0;}
#af-form-680538938 input,#af-form-680538938 button,#af-form-680538938 textarea,#af-form-680538938 select{font-size:100%;}
#af-form-680538938 p{color:inherit;}
#af-form-680538938 select,#af-form-680538938 label,#af-form-680538938 optgroup,#af-form-680538938 option{padding:0;}
#af-form-680538938 table{border-collapse:collapse;border-spacing:0;}
#af-form-680538938 ul,#af-form-680538938 ol{list-style-image:none;list-style-position:outside;list-style-type:disc;padding-left:40px;}
#af-form-680538938,#af-form-680538938 .quirksMode{width:100%;max-width:303px;}
#af-form-680538938.af-quirksMode{overflow-x:hidden;}
#af-form-680538938{background-color:#F0F0F0;border-color:#EEEEEE;border-width:1px;border-style:solid;}
#af-form-680538938{display:none;}
#af-form-680538938{overflow:hidden;}
.af-body .af-textWrap{text-align:left;}
.af-body input.image{border:none!important;}
.af-body input.submit,.af-body input.image,.af-form .af-element input.button{float:none!important;}
.af-body input.text{width:100%;float:none;padding:2px!important;}
.af-body.af-standards input.submit{padding:4px 12px;}
.af-clear{clear:both;}
.af-element label{text-align:left;display:block;float:left;}
.af-element{padding:5px 0;}
.af-form-wrapper{text-indent:0;}
.af-form{text-align:left;margin:auto;}
.af-header,.af-footer{margin-bottom:0;margin-top:0;padding:10px;}
.af-quirksMode .af-element{padding-left:0!important;padding-right:0!important;}
.lbl-right .af-element label{text-align:right;}
body {
}#af-form-fb-680538938 .af-body .af-textWrap{width:98%;display:block;float:none;}
#af-form-fb-680538938 .af-body .privacyPolicy{color:#2C4F7F;font-size:11px;font-family:Verdana, sans-serif;}
#af-form-fb-680538938 .af-body a{color:#2C4E7F;text-decoration:underline;font-style:normal;font-weight:normal;}
#af-form-fb-680538938 .af-body input.text, #af-form-fb-680538938 .af-body textarea{background-color:#FFFFFF;border-color:#2C4E7F;border-width:1px;border-style:solid;color:#000000;text-decoration:none;font-style:normal;font-weight:normal;font-size:12px;font-family:Verdana, sans-serif;}
#af-form-fb-680538938 .af-body input.text:focus, #af-form-fb-680538938 .af-body textarea:focus{background-color:#FFFAD6;border-color:#030303;border-width:1px;border-style:solid;}
#af-form-fb-680538938 .af-body label.previewLabel{display:block;float:none;text-align:left;width:auto;color:#000000;text-decoration:none;font-style:normal;font-weight:normal;font-size:12px;font-family:Verdana, sans-serif;}
#af-form-fb-680538938 .af-body{padding-bottom:15px;padding-top:15px;background-repeat:no-repeat;background-position:inherit;background-image:none;color:#2C4F7F;font-size:11px;font-family:Verdana, sans-serif;}
#af-form-fb-680538938 .af-footer{padding-bottom:15px;padding-top:15px;padding-right:15px;padding-left:15px;background-color:#2C4E7F;background-repeat:repeat-x;background-position:top;background-image:url("http://forms.aweber.com/images/forms/modern/blue/footer.png");border-width:1px;border-bottom-style:none;border-left-style:none;border-right-style:none;border-top-style:none;color:#FFFFFF;font-size:12px;font-family:Verdana, sans-serif;}
#af-form-fb-680538938 .af-header{padding-bottom:10px;padding-top:10px;padding-right:10px;padding-left:10px;background-color:#2C4E7F;background-repeat:repeat-x;background-position:bottom;background-image:url("http://forms.aweber.com/images/forms/modern/blue/header.png");border-bottom-style:none;border-left-style:none;border-right-style:none;border-top-style:none;color:#FFFFFF;font-size:16px;font-family:Verdana, sans-serif;}
#af-form-fb-680538938 .af-quirksMode .bodyText{padding-top:2px;padding-bottom:2px;}
#af-form-fb-680538938 .af-quirksMode{padding-right:15px;padding-left:15px;}
#af-form-fb-680538938 .af-standards .af-element{padding-right:15px;padding-left:15px;}
#af-form-fb-680538938 .bodyText p{margin:1em 0;}
#af-form-fb-680538938 .buttonContainer input.submit{background-color:#2c4e7f;background-image:url("http://forms.aweber.com/images/forms/modern/blue/button.png");color:#FFFFFF;text-decoration:none;font-style:normal;font-weight:normal;font-size:14px;font-family:Verdana, sans-serif;}
#af-form-fb-680538938 .buttonContainer input.submit{width:auto;}
#af-form-fb-680538938 .buttonContainer{text-align:right;}
#af-form-fb-680538938 body,#af-form-fb-680538938 dl,#af-form-fb-680538938 dt,#af-form-fb-680538938 dd,#af-form-fb-680538938 h1,#af-form-fb-680538938 h2,#af-form-fb-680538938 h3,#af-form-fb-680538938 h4,#af-form-fb-680538938 h5,#af-form-fb-680538938 h6,#af-form-fb-680538938 pre,#af-form-fb-680538938 code,#af-form-fb-680538938 fieldset,#af-form-fb-680538938 legend,#af-form-fb-680538938 blockquote,#af-form-fb-680538938 th,#af-form-fb-680538938 td{float:none;color:inherit;position:static;margin:0;padding:0;}
#af-form-fb-680538938 button,#af-form-fb-680538938 input,#af-form-fb-680538938 submit,#af-form-fb-680538938 textarea,#af-form-fb-680538938 select,#af-form-fb-680538938 label,#af-form-fb-680538938 optgroup,#af-form-fb-680538938 option{float:none;position:static;margin:0;}
#af-form-fb-680538938 div{margin:0;}
#af-form-fb-680538938 fieldset{border:0;}
#af-form-fb-680538938 form,#af-form-fb-680538938 textarea,.af-form-fb-wrapper,.af-form-fb-close-button,#af-form-fb-680538938 img{float:none;color:inherit;position:static;background-color:none;border:none;margin:0;padding:0;}
#af-form-fb-680538938 input,#af-form-fb-680538938 button,#af-form-fb-680538938 textarea,#af-form-fb-680538938 select{font-size:100%;}
#af-form-fb-680538938 p{color:inherit;}
#af-form-fb-680538938 select,#af-form-fb-680538938 label,#af-form-fb-680538938 optgroup,#af-form-fb-680538938 option{padding:0;}
#af-form-fb-680538938 table{border-collapse:collapse;border-spacing:0;}
#af-form-fb-680538938 ul,#af-form-fb-680538938 ol{list-style-image:none;list-style-position:outside;list-style-type:disc;padding-left:40px;}
#af-form-fb-680538938,#af-form-fb-680538938 .quirksMode{width:100%;max-width:303px;}
#af-form-fb-680538938.af-quirksMode{overflow-x:hidden;}
#af-form-fb-680538938{background-color:#F0F0F0;border-color:#EEEEEE;border-width:1px;border-style:solid;}
#af-form-fb-680538938{display:none;}
#af-form-fb-680538938{overflow:hidden;}
.af-body .af-textWrap{text-align:left;}
.af-body input.image{border:none!important;}
.af-body input.submit,.af-body input.image,.af-form .af-element input.button{float:none!important;}
.af-body input.text{width:100%;float:none;padding:2px!important;}
.af-body.af-standards input.submit{padding:4px 12px;}
.af-clear{clear:both;}
.af-element label{text-align:left;display:block;float:left;}
.af-element{padding:5px 0;}
.af-form-fb-wrapper{text-indent:0;}
.af-form{text-align:left;margin:auto;}
.af-header,.af-footer{margin-bottom:0;margin-top:0;padding:10px;}
.af-quirksMode .af-element{padding-left:0!important;padding-right:0!important;}
.lbl-right .af-element label{text-align:right;}
body {
}
</style>
<iframe name="af-iframe" id="af-iframe-680538938" src="http://forms.aweber.com/form/38/680538938.htm" style="margin: -10px !important; width: 321px !important;" width="303px" height="571px" allow-transparency="true" scrolling="no" frameBorder="0"></iframe><script type="text/javascript"></script><script type="text/javascript">
    <!--
    (function() {
        var IE = /*@cc_on!@*/false;
        if (!IE) { return; }
        if (document.compatMode && document.compatMode == 'BackCompat') {
            if (document.getElementById("af-form-680538938")) {
                document.getElementById("af-form-680538938").className = 'af-form af-quirksMode';
            }
            if (document.getElementById("af-body-680538938")) {
                document.getElementById("af-body-680538938").className = "af-body inline af-quirksMode";
            }
            if (document.getElementById("af-header-680538938")) {
                document.getElementById("af-header-680538938").className = "af-header af-quirksMode";
            }
            if (document.getElementById("af-footer-680538938")) {
                document.getElementById("af-footer-680538938").className = "af-footer af-quirksMode";
            }
        }
    })();
    -->
</script>

<!-- /AWeber Web Form Generator 3.0 -->
<form method="post">
<br/>
<?php
		if(isset($_POST["activationkey"]) && $_POST["activationkey"]!="Socisyndlite"){
		 	echo "<span style=\"color:red;\">Invalid activation Key</span>";
		}
?>
	<br/>Enter The Activation key<br/>
	<input type="text" name="activationkey" />
	<input type="submit" value="Activate Plugin !"/>
<br/>
</form>
<p>
	<span style="font-size:12px;"><i>You will be redirected to the tutorial videos to use the plugin after you optin, you can also request premium installation service there as well as order some social accounts to be added to your wordpress plugin by our outsource team! :)</i></span></p>

</center>
<?php
		}
	}
}
}
else{


global $wpdb;
require_once dirname( __FILE__ ) . '/oo/SSC.php';
require_once dirname( __FILE__ ) . '/oo/SSCDash.php';


$ssc = new SSC( );
register_activation_hook( __FILE__, array( $ssc, 'install' ) );
register_deactivation_hook( __FILE__, array( $ssc, 'uninstall' ) );
?><?php } ?>
