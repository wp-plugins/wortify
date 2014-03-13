<?php
	global $error;
	
	include_once dirname(__FILE__) . '/xortify/include/forms.wortify.php';
	include_once dirname(__FILE__) . '/xortify/class/auth/authfactory.php';

	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"signup";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"";
	
	switch($op) {
	case "signup":	
	
		switch ($fct)
		{
		case "save":	

			$wortifyAuth =& WortifyAuthFactory::getAuthConnection(false, WortifyConfig::get('xortify_protocol'));
			$myts =& MyTextSanitizer::getInstance();
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
					WortifySignupForm($disclaimer);
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
				
				@WortifyConfig::set('xortify_username', $pass);
				@WortifyConfig::set('xortify_password', $pass);
				header('Location: ' . site_url());
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
				WortifySignupForm($disclaimer);
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