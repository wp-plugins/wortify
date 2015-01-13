<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyConstants.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyConfig.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyCriteria.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyObject.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyDatabase.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyTextsanitizer.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . 'wortifyFile.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortifyCache.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'wortifyModel.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifySchema.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyUtils.php';

error_reporting(E_ERROR);
ini_set('display_errors', true);

class wortify {
	public static $printStatus = false;
	public static $wortify_wp_version = false;
	public static $newVisit = false;
	
	var $conn = NULL;

	public static function __logENV($function = '')
	{
		if (constant("WP_DEBUG"))
		{
			if (!is_dir(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'logs'))
				mkdir(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'logs', 0777, true);
			$filename = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR .  microtime(true) . "___" . $function .  '.txt';
			$f = fopen($filename, "w+");
			fwrite($f, $string = ('$_SERVER' . print_r($_SERVER, true) . "\n\n\n" .  '$_SESSION' . print_r($_SESSION, true) . "\n\n\n" .  '$_REQUEST' . print_r($_REQUEST, true) . "\n\n\n"), strlen($string));
			fclose($f);
		}
	}
	
	
	public static function __displayBan($string = '')
	{
echo "
		<style>
			#blocker
			{
				position:					absolute;
				left:						0;
				top:						0;
				z-index:					99998;
				background: 				rgba(11,22,11,0.89);
				min-height: 				101% !important;
				max-width: 					100% !important;
				width: 						100% !important;
				clear:						none;
			}
		
			#mission
			{
				z-index:					99999;
				position:					absolute;
				left:						0;
				top:						0;
				clear:						none;
				min-height: 				101% !important;
				max-width: 					100% !important;
				width: 						100% !important;
			}
		
			#banning
			{
				position:					absolute;
				top: 						89px !important;
				display:					none;
				clear:						none;
				margin-left:				auto;
				margin-right:				auto;
				width:						836px;
				min-height:					256px;
				-webkit-box-shadow: 		-4px 7px 18px rgba(254, 14, 11, 0.87);
				-moz-box-shadow:    		-4px 7px 18px rgba(254, 14, 11, 0.87);
				box-shadow:         		-4px 7px 18px rgba(254, 14, 11, 0.87);
				-webkit-border-radius: 		15px;
				-moz-border-radius: 		15px;
				border-radius: 				15px;\n";
			
			mt_srand(mt_rand(mt_rand(-microtime(true), microtime(true))));
			mt_srand(mt_rand(mt_rand(-microtime(true), microtime(true))));
			mt_srand(mt_rand(mt_rand(-microtime(true), microtime(true))));
		
			$modes = array(	'one'=>array('a'=>'center, ellipse', 'b'=>'radial, center center'),
			'two'=>array('a'=>'-45deg', 'b'=>'left top, right bottom'),
			'three'=>array('a'=>'45deg', 'b'=>'left bottom, right top'),
			'four'=>array('a'=>'top', 'b'=>'left top, left bottom'),
			'five'=>array('a'=>'left', 'b'=>'left top, right top'));
		
			$modeskeys = array('one','two','three','four','five');

			$colour = array();
			foreach(array('one', 'two', 'three', 'four') as $key) {
				$colour[$key]['red'] = mt_rand(77, 222);
				$colour[$key]['green'] = mt_rand(88, 177);
				$colour[$key]['blue'] = mt_rand(111, 243);
				if (in_array($key, array('one')))
					$colour[$key]['heat'] = mt_rand(47, 99);
					else
						$colour[$key]['heat'] = mt_rand(37, 99);
							$colour[$key]['opacity'] = (string)round(mt_rand(36, 83) / 93, 2);
			}
			shuffle($modeskeys);
			$state = $modes[$modeskeys[mt_rand(0, count($modekeys)-1)]];
echo '				background:					rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].');
				background:					-moz-linear-gradient('.$state['a'].', rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%, rgba('.$colour['two']['red'].','.$colour['two']['green'].','.$colour['two']['blue'].','.$colour['two']['opacity'].') '.$colour['two']['heat'].'%, rgba('.$colour['three']['red'].','.$colour['three']['green'].','.$colour['three']['blue'].','.$colour['three']['opacity'].') '.$colour['three']['heat'].'%, rgba('.$colour['four']['red'].','.$colour['four']['green'].','.$colour['four']['blue'].','.$colour['four']['opacity'].') '.$colour['four']['heat'].'%, rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%);
				background:					-webkit-gradient('.$state['b'].', color-stop('.$colour['one']['heat'].'%, rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].')), color-stop('.$colour['two']['heat'].'%, rgba('.$colour['two']['red'].','.$colour['two']['green'].','.$colour['two']['blue'].','.$colour['two']['opacity'].')), color-stop('.$colour['three']['heat'].'%, rgba('.$colour['three']['red'].','.$colour['three']['green'].','.$colour['three']['blue'].','.$colour['three']['opacity'].')), color-stop('.$colour['four']['heat'].'%, rgba('.$colour['four']['red'].','.$colour['four']['green'].','.$colour['four']['blue'].','.$colour['four']['opacity'].')), color-stop('.$colour['one']['heat'].'%, rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].')));
				background:					 -webkit-linear-gradient('.$state['a'].', rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%, rgba('.$colour['two']['red'].','.$colour['two']['green'].','.$colour['two']['blue'].','.$colour['two']['opacity'].') '.$colour['two']['heat'].'%, rgba('.$colour['three']['red'].','.$colour['three']['green'].','.$colour['three']['blue'].','.$colour['three']['opacity'].') '.$colour['three']['heat'].'%, rgba('.$colour['four']['red'].','.$colour['four']['green'].','.$colour['four']['blue'].','.$colour['four']['opacity'].') '.$colour['four']['heat'].'%, rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%);										background:					-o-linear-gradient('.$state['a'].', rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%, rgba('.$colour['two']['red'].','.$colour['two']['green'].','.$colour['two']['blue'].','.$colour['two']['opacity'].') '.$colour['two']['heat'].'%, rgba('.$colour['three']['red'].','.$colour['three']['green'].','.$colour['three']['blue'].','.$colour['three']['opacity'].') '.$colour['three']['heat'].'%, rgba('.$colour['four']['red'].','.$colour['four']['green'].','.$colour['four']['blue'].','.$colour['four']['opacity'].') '.$colour['four']['heat'].'%, rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%);
				background:					-ms-linear-gradient('.$state['a'].', rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%, rgba('.$colour['two']['red'].','.$colour['two']['green'].','.$colour['two']['blue'].','.$colour['two']['opacity'].') '.$colour['two']['heat'].'%, rgba('.$colour['three']['red'].','.$colour['three']['green'].','.$colour['three']['blue'].','.$colour['three']['opacity'].') '.$colour['three']['heat'].'%, rgba('.$colour['four']['red'].','.$colour['four']['green'].','.$colour['four']['blue'].','.$colour['four']['opacity'].') '.$colour['four']['heat'].'%, rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%);
				background:					linear-gradient('.$state['a'].', rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%, rgba('.$colour['two']['red'].','.$colour['two']['green'].','.$colour['two']['blue'].','.$colour['two']['opacity'].') '.$colour['two']['heat'].'%, rgba('.$colour['three']['red'].','.$colour['three']['green'].','.$colour['three']['blue'].','.$colour['three']['opacity'].') '.$colour['three']['heat'].'%, rgba('.$colour['four']['red'].','.$colour['four']['green'].','.$colour['four']['blue'].','.$colour['four']['opacity'].') '.$colour['four']['heat'].'%, rgba('.$colour['one']['red'].','.$colour['one']['green'].','.$colour['one']['blue'].','.$colour['one']['opacity'].') '.$colour['one']['heat'].'%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#ffffff\', endColorstr=\'#ffffff\', GradientType=1 );';
echo "				padding: 						13px;
				border: 						9px solid red;
				font-size:						1.478996em;
			}
		
			#banning-close
			{
				font-weight:					bold;
				margin:							5px 3px 5px 0px;
			}
		
			#banning-message
			{
				padding:						11px;
				font-weight:					bold;
			}
		
			#banning-header
			{
				text-align: 					left;
				color: 							#a5ae56;
				text-shadow: 					-2px 5px 8px rgba(9, 14, 011, 0.69);
				text-outline:					3px 2px black;
				-webkit-text-outline:			3px 2px black;
				-moz-text-outline:				3px 2px black;
				margin-left: 					10px;
				font-size: 						1.8699em;
				margin-top: 					35px;
				margin-bottom: 					35px;
				letter-spacing: 				-3px;
				font-weight:					900;
			}
		
			#banning-slogon
			{
				text-align: 					center;
				font-size: 						1.4519669em;
				margin-top: 					5px;
				margin-bottom: 					35px;
				color: 							black;
				text-shadow: 					-2px 4px 6px rgba(9, 29, 91, 0.79);
				text-outline:					2px 2px red;
				-webkit-text-outline:			2px 2px red;
				-moz-text-outline:				2px 2px red;
				padding-left: 					41px;
				padding-right: 					41px;
				line-height: 					0.985699em;
				letter-spacing: 				-2px;
				font-weight:			`		700;
			}
		
			#banning-slogon-red
			{
				color: 							red;
				text-shadow: 					2px 5px 6px rgba(91, 9, 39, 0.99);
				text-outline:					2px 2px black;
				-webkit-text-outline:			2px 2px black;
				-moz-text-outline:				2px 2px black;
				padding-left: 					7px;
				padding-right: 					7px;
				letter-spacing: 				-2px;
				font-weight:					bold;
			}
		
		</style>";
		echo '<div id="blocker"><div id="mission"><div id="banning" class="window"><div align="center" id="banning-message"><h1 id="banning-header">Xortify.com Ban or Block</h1><div id="banning-slogon">'.$string.'</div><img src="https://xortify.com/images/logo.png" style="clear:now;" width="313px" /></div></div></div></div>';
	}
	
	public static function __constructor() 
	{
		
	}
		
	public static function installPlugin()
	{
		self::__logENV(__FUNCTION__);
		self::runInstall();
		//Used by MU code below
		update_option('wortifyActivated', 1);
	}
	
	public static function uninstallPlugin()
	{
		self::__logENV(__FUNCTION__);
		//Used by MU code below
		update_option('wortifyActivated', 0);
		wp_clear_scheduled_hook('wortify_daily_cron');
		wp_clear_scheduled_hook('wortify_hourly_cron');
		
		//Remove old legacy cron job if it exists
		wp_clear_scheduled_hook('wortify_scheduled_scan');
		
		//Remove all scheduled scans.
		self::unscheduleAllBanss();
		
		$schema = new wortifySchema();
		$schema->dropAll();
	}
	
	public static function hourlyCron()
	{
		self::__logENV(__FUNCTION__);
	}
	
	public static function dailyCron()
	{
		self::__logENV(__FUNCTION__);
	}
	
	public static function runInstall()
	{
		self::__logENV(__FUNCTION__);
		update_option('wortify_version', WORTIFY_VERSION); 
		$schema = new wortifySchema();
		$schema->createAll(); //if not exists
		wortifyConfig::setDefaults(); //If not set
		wp_clear_scheduled_hook('wortify_daily_cron');
		wp_clear_scheduled_hook('wortify_hourly_cron');
		wp_schedule_event(time(), 'daily', 'wortify_daily_cron');
		wp_schedule_event(time(), 'hourly', 'wortify_hourly_cron');
		//End upgrade from 1.5.6
		global $wpdb;
	}
	
	public static function install_actions()
	{
		self::__logENV(__FUNCTION__);
		self::runInstall();
		add_action('wortify_daily_cron', 'wortify::dailyCron');
		add_action('wortify_hourly_cron', 'wortify::hourlyCron');
		add_action('init', 'wortify::initAction');
		add_action('wp_authenticate','wortify::authActionNew', 1, 2);
		add_action('admin_init', 'wortify::admin_init');
		add_action('admin_menu', 'wortify::admin_menus');
		add_action('wp_loaded', 'wortify::wpAction');
		add_action('shutdown', 'wortify::wpShutdown');
		
		add_filter( 'pre_comment_approved' , 'wortify::xortify_spam_handler' , '99', 2 );
		
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache', 0777);
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify', 0777);
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'cache'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'cache', 0777);
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'configs'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'configs', 0777);
	}
	
	public static function xortify_spam_handler( $approved , $commentdata )
	{
		self::__logENV(__FUNCTION__);
		include_once( WORTIFY_VAR_PATH . '/lib/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' );
		$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
		$apiExchange = new $func;
		
		switch ($approved)
		{
			case "spam":
				$apiExchange->training($commentdata['comment_content'], false);
				return $approved;
				break;
			default:
				$result = $apiExchange->checkForSpam($commentdata['comment_content'],$commentdata['comment_author'],$commentdata['comment_author'],$commentdata['comment_author_email'],$commentdata['comment_author_IP']);
				if (isset($result['spam']))
					switch($result['spam'])
					{
						default;
							return 'spam';
							break;
						case false:
							return 1;
							break;
					}
				return 0;
				break;
		}
	}
	
	public static function logLogin()
	{	
		self::__logENV(__FUNCTION__);
		return false;	
	}
	
	public static function getLog()
	{	
		self::__logENV(__FUNCTION__);
		return false;	
	}
	
	public static function isLockedOut()
	{	
		self::__logENV(__FUNCTION__);
		return false;	
	}
	
	public static function registrationFilter($errors, $santizedLogin, $userEmail)
	{	
		self::__logENV(__FUNCTION__);
	}
	
	public static function logoutAction()
	{	
		self::__logENV(__FUNCTION__);
	}
	
	public static function loginInitAction()
	{	
		self::__logENV(__FUNCTION__);

	}
	
	public static function authActionNew($username, &$passwd)
	{	
		self::__logENV(__FUNCTION__);
		 //As of php 5.4 we must denote passing by ref in the function definition, not the function call (as WordPress core does, which is a bug in WordPress).

	}

	
	public static function wpAction() 
	{	
		if (isset($_REQUEST["_wp_original_http_referer"]) && strpos(strtolower($_SERVER['HTTP_HOST']), strtolower($_REQUEST["_wp_original_http_referer"])))
			return false;
		
		if (strpos(strtolower($_SERVER['HTTP_HOST']), strtolower($_SERVER['HTTP_REFERER'])))
			return false;
		
		if (strpos(strtolower($_SERVER['PHP_SELF']), '/wp-admin/'))
			return false;
		
		self::__logENV(__FUNCTION__);
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protector' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'postcheck.inc.php';
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'post.loader.mainfile.php';
		
	}
	
	public static function shutdownAction() 
	{	
		self::__logENV(__FUNCTION__);
		
	}
	
	static function wpShutdown() 
	{	

		if (isset($_REQUEST["_wp_original_http_referer"]) && strpos(strtolower($_SERVER['HTTP_HOST']), strtolower($_REQUEST["_wp_original_http_referer"])))
			return false;
		
		if (strpos(strtolower($_SERVER['HTTP_HOST']), strtolower($_SERVER['HTTP_REFERER'])))
			return false;
		
		if (strpos(strtolower($_SERVER['PHP_SELF']), '/wp-admin/'))
			return false;
		
		self::__logENV(__FUNCTION__);
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'post.footer.end.php';
		
	}
	
	
	public static function initAction()
	{	
	
		if (isset($_REQUEST["_wp_original_http_referer"]) && strpos(strtolower($_SERVER['HTTP_HOST']), strtolower($_REQUEST["_wp_original_http_referer"])))
			return false;
		
		if (strpos(strtolower($_SERVER['HTTP_HOST']), strtolower($_SERVER['HTTP_REFERER'])))
			return false;
		
		if (strpos(strtolower($_SERVER['PHP_SELF']), '/wp-admin/'))
			return false;

		self::__logENV(__FUNCTION__);
		
		global $wp;
		if (!is_object($wp)) return; //Suggested fix for compatability with "Portable phpmyadmin"
		$wp->add_query_var('_wortifysf');
		$cookieName = 'wortifyvt_' . md5(site_url());
		$c = isset($_COOKIE[$cookieName]) ? isset($_COOKIE[$cookieName]) : false;
		if($c){
			self::$newVisit = false;
		} else {
			self::$newVisit = true;
		}
		@setcookie($cookieName, uniqid(), time() + 1800, '/');
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protector' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'precheck.inc.php';
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'pre.loader.mainfile.php';
	}
	
	public static function admin_init()
	{	
		self::__logENV(__FUNCTION__);
		self::setupAdminVars();
	}
	
	public static function _network_admin_menu() 
	{

		self::__logENV(__FUNCTION__);
		self::admin_menus();
	}

	public static function _user_admin_menu() 
	{	
		self::__logENV(__FUNCTION__);
		self::admin_menus();
	}

	public static function _admin_menu() 
	{	
		self::__logENV(__FUNCTION__);
		self::admin_menus();
	}
	
	public static function network_admin_menu() 
	{	
		self::__logENV(__FUNCTION__);
		self::admin_menus();
	}
	
	public static function user_admin_menu() 
	{	
		self::__logENV(__FUNCTION__);
		self::admin_menus();
	}
	
	
	private static function setupAdminVars()
	{	
		self::__logENV(__FUNCTION__);
				$updateInt = wortifyConfig::get('actUpdateInterval', 2);
		if(! preg_match('/^\d+$/', $updateInt)){
			$updateInt = 2;
		}
		$updateInt *= 1000;
		wp_localize_script('wortifyAdminjs', 'WortifyAdminVars', array(
			'ajaxURL' => admin_url('admin-ajax.php'),
			'firstNonce' => wp_create_nonce('wp-ajax'),
			'siteBaseURL' => wortifyUtils::getSiteBaseURL(),
			'debugOn' => wortifyConfig::get('debugOn', 0),
			'actUpdateInterval' => $updateInt,
			'tourClosed' => wortifyConfig::get('tourClosed', 0)
			));
			}
	
	public static function activation_warning()
	{	
		self::__logENV(__FUNCTION__);
		if (strlen(get_option('xortify_username'))==0 && strlen(get_option('xortify_password'))==0 ){
			echo '<div id="wortifyConfigWarning" class="updated fade"><p><strong>Wortify has not been signed into the Xortify Cloud!</strong> <em>Go to the sign-up options</em> and complete the form and you will be activated!</p></div>';
		}
	}
	
	public static function admin_menus()
	{	
		self::__logENV(__FUNCTION__);
		$warningAdded = false;
		if (strlen(wortifyConfig::get('xortify_username'))==0 && strlen(wortifyConfig::get('xortify_password'))==0 ){
			add_action('network_admin_notices', 'wortify::activation_warning');
			$warningAdded = true;
		}
		add_menu_page( 'Wortify', 'Wortify', 'manage_options', 'wortify-menu', 'wortify::menu_bans', site_url("/wp-content/plugins/wortify/images/wortify-logo-16x16.png") );
		add_submenu_page( 'wortify-menu', "Wortify Log", "Wortify Log", 'manage_options', 'wortify-menu-logs', 'wortify::menu_logs');
		add_submenu_page( 'wortify-menu', "Protector", "Protector", 'manage_options', 'wortify-menu-protector', 'wortify::menu_protector');
		add_submenu_page( 'wortify-menu', "Options", "Options", 'manage_options', 'wortify-menu-options', 'wortify::menu_options');
		if ($warningAdded == true ){
			add_submenu_page( 'wortify-menu', "Cloud Signup", "Cloud Signup", 'manage_options', 'wortify-menu-signup', 'wortify::menu_signup');
		}
	}
	
	public static function menu_options()
	{	
		self::__logENV(__FUNCTION__);
		require 'menu_options.php';
	}
	
	public static function menu_logs()
	{	
		self::__logENV(__FUNCTION__);
		require 'menu_log.php';
	}
	
	public static function menu_protector()
	{	
		self::__logENV(__FUNCTION__);
		require 'menu_protector.php';
	}
	
	public static function menu_signup()
	{	
		self::__logENV(__FUNCTION__);
		require 'menu_signup.php';
	}
	
	public static function menu_bans()
	{	
		self::__logENV(__FUNCTION__);
		require 'menu_bans.php';
	}
	
	public static function profileUpdateAction($userID, $newDat = false)
	{
		self::__logENV(__FUNCTION__);
		if(! $newDat){ return; }
		if(wortifyConfig::get('other_pwStrengthOnUpdate')){
			$oldDat = get_userdata($userID);
			if($newDat->user_pass != $oldDat->user_pass){
				$wortify = new wortifyBansEngine();	
				$wortify->scanUserPassword($userID);
				$wortify->emailNewIssues();
			}
		}
	}
	
	public static function preCommentApprovedFilter($approved, $cData)
	{	
		self::__logENV(__FUNCTION__);
		if( $approved == 1 && (! is_user_logged_in()) && wortifyConfig::get('other_noAnonMemberComments') ){
			$user = get_user_by('email', trim($cData['comment_author_email']));
			if($user){
				return 0; //hold for moderation if the user is not signed in but used a members email
			}
		}
		
		if(($approved == 1 || $approved == 0) && wortifyConfig::get('other_scanComments')){
			$wortify = new wortifyBansEngine();
			try {
				if($wortify->isBadComment($cData['comment_author'], $cData['comment_author_email'], $cData['comment_author_url'],  $cData['comment_author_IP'], $cData['comment_content'])){
					return 'spam';
				}
			} catch(Exception $e){
				//This will most likely be an API exception because we can't contact the API, so we ignore it and let the normal comment mechanisms run.
			}
		}
		return $approved;
	}
	
	public static function getMyHomeURL()
	{	
		self::__logENV(__FUNCTION__);
		return admin_url('admin.php?page=Wortify', 'http');
	}
	
	public static function getMyOptionsURL()
	{	
		self::__logENV(__FUNCTION__);
		return admin_url('admin.php?page=WortifySecOpt', 'http');
	}
	
	public static function wortify_hourly_cron() 
	{	
		self::__logENV(__FUNCTION__);
		include WORTIFY_VAR_PATH . '/lib/xortify/crons/serverup.php';
	}
	public static function moreCronReccurences()
	{	
		self::__logENV(__FUNCTION__);
		return array(
			'everyminute' => array('interval' => 60, 'display' => 'Once Every Minute'),
		);
	}
	
	
}
?>
