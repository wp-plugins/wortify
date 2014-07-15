<link rel='stylesheet' href='<?php echo plugins_url( '/css/style.css', __FILE__ ); ?>' type='text/css' media='all' />
<?php
	error_reporting(0);
	global $error;
	require_once dirname(__FILE__) . '/xortify/include/forms.xortify.php';
	require_once dirname(__FILE__) . '/xortify/class/auth/authfactory.php';

	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"signup";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"";
	
	switch($op) {
	case "signup":	
	
		switch ($fct)
		{
		case "save":	

			$wortifyAuth =& WortifyAuthFactory::getAuthConnection(false, WortifyConfig::get('xortify_protocol'));
			$myts =& wortifyTextSanitizer::getInstance();
			$uname = isset($_POST['uname']) ? $myts->stripSlashesGPC(trim($_POST['uname'])) : '';
			$email = isset($_POST['email']) ? $myts->stripSlashesGPC(trim($_POST['email'])) : '';
			$url = isset($_POST['url']) ? $myts->stripSlashesGPC(trim($_POST['url'])) : '';
			$pass = isset($_POST['pass']) ? $myts->stripSlashesGPC(trim($_POST['pass'])) : '';
			$vpass = isset($_POST['vpass']) ? $myts->stripSlashesGPC(trim($_POST['vpass'])) : '';
			$agree = (isset($_POST['agree']) && intval($_POST['agree'])) ? 1 : 0;
			
			if ($agree != 1) {
				$stop .= _US_UNEEDAGREE . '<br />';
			}
			
			$validate = $wortifyAuth->validate($uname, $email, $pass, $vpass);
			
			if ($validate!=false)
				$stop .= "User details didn't validate with Wortify.com<br/>$validate";
					
			if ($stop!='') {
				$wortifyAuth =& WortifyAuthFactory::getAuthConnection(false, WortifyConfig::get('xortify_protocol'));
				$disclaimer = $wortifyAuth->network_disclaimer();
				if (strlen(trim($disclaimer))==0)
				{
					$disclaimer = WORTIFY_ADMIN_NONETWORKCOMM_DISCLAIMER;
				}
				if ($disclaimer != WORTIFY_ADMIN_NONETWORKCOMM_DISCLAIMER) {
?><h2><?php echo WORTIFY_ADMIN_SIGNUP_XORTIFY_H2; ?></h2>
<p><?php echo WORTIFY_ADMIN_SIGNUP_XORTIFY_P; ?></p><?php
echo "<p align='center' style='font-size: 15px; color: #FF0000;'>$stop</p>"; 
					echo WortifySignupForm($disclaimer);
				} else {
?>
<h2><?php echo WORTIFY_ADMIN_ERROR_OCCURED; ?></h2>
<p><?php echo $GLOBALS['error']; ?></p>
<br/><br/>
<h3><?php echo WORTIFY_ADMIN_ERROR_URL; ?></h3>
<pre><?php echo WortifyConfig::get('xortify_urirest'); ?></pre>
<br/><br/>
<h3><?php echo WORTIFY_ADMIN_ERROR_PROTOCOL; ?></h3>
<pre><?php echo WortifyConfig::get('xortify_protocol'); ?></pre>
<?php 
									}
					
			} else {
				$wortifyAuth->create_user(	$_REQUEST['viewemail'], $uname, $email, $url, $actkey, 
											$pass, $_REQUEST['timezone'], $_REQUEST['mailok'], $wortifyAuth->check_siteinfo(array()));
				
				@WortifyConfig::set('xortify_username', $uname);
				@WortifyConfig::set('xortify_password', $pass);
?>
<meta http-equiv="Refresh" content="0; url=<?php echo site_url(); ?>" />
<meta http-equiv="refresh" content="0; url=<?php echo site_url(); ?>" />
<h1 style="text-align: center;"><?php echo sprintf(WORTIFY_ADMIN_USER_CREATED_H1, $uname); ?></h1>
<?php 
				exit(0);
			}
			break;
		default:	
		case "signup":
			$wortifyAuth =& WortifyAuthFactory::getAuthConnection(false, WortifyConfig::get('xortify_protocol'));
			$disclaimer = $wortifyAuth->network_disclaimer();
			if (strlen(trim($disclaimer))==0)
			{
				$disclaimer = WORTIFY_ADMIN_NONETWORKCOMM_DISCLAIMER;
			}
			if ($disclaimer != WORTIFY_ADMIN_NONETWORKCOMM_DISCLAIMER) {
?><h2><?php echo WORTIFY_ADMIN_SIGNUP_XORTIFY_H2; ?></h2>
<p><?php echo WORTIFY_ADMIN_SIGNUP_XORTIFY_P; ?></p><?php 
				echo WortifySignupForm($disclaimer);
			} else {
?>
<h2><?php echo WORTIFY_ADMIN_ERROR_OCCURED; ?></h2>
<p><?php echo $GLOBALS['error']; ?></p>
<br/><br/>
<h3><?php echo WORTIFY_ADMIN_ERROR_URL; ?></h3>
<pre><?php echo WortifyConfig::get('xortify_urirest'); ?></pre>
<br/><br/>
<h3><?php echo WORTIFY_ADMIN_ERROR_PROTOCOL; ?></h3>
<pre><?php echo WortifyConfig::get('xortify_protocol'); ?></pre>
<?php 
			}
			break;
		}
		break;
	}
?>