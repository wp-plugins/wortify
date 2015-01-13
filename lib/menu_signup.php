<style>
	th, thead {background-color: #23AC5; padding : 2px; color: #0e45f6; font-size: 1.4356em; vertical-align : middle; padding: 5px;}
	.outer {border: 3px solid #c0c0c0;-webkit-box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-moz-box-shadow:    4px 4px 6px 2px rgba(95, 95, 15, 0.78);	box-shadow:         4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-webkit-border-radius: 14px;	-moz-border-radius: 14px;	border-radius: 14px;	text-shadow: 2px 2px 2px rgba(103, 87, 101, 0.82);}
	.head {background-color: #c4ea66; padding: 5px; font-weight: bold;}
	.even {background-color: #d17fe7; padding: 5px; padding: 5px;font-size: 1.2123em;}
	.odd {background-color: #a6f4e1; padding: 5px; padding: 5px; font-size: 1.2123em;}
	.foot {background-color: #c2e7a1; padding: 5px; font-weight: bold;}
	tr.even td {background-color: #d17fe7; padding: 5px; padding: 5px;font-size: 1.2123em;}
	tr.odd td {background-color: #a6f4e1; padding: 5px; padding: 5px;font-size: 1.2123em;}
	tr.foot td {background-color: #c2cdd6; padding: 5px; color:inherit; font-weight: bold;}
	tr .head { min-width: 189px; }
	.caption-marker, #caption-marker { color: rgb(250,0,0);	float: right; font-size: 134%; font-weight: bold; }
	button, input, select, radio, checkbox, options, textarea {-webkit-box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78); -moz-box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78);	box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-webkit-border-radius: 5px;	-moz-border-radius: 5px; border-radius: 5px; text-shadow: 2px 2px 1px rgba(103, 87, 101, 0.82);}
</style>
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
				
				WortifyConfig::set('xortify_username', $uname);
				WortifyConfig::set('xortify_password', $pass);
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