<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyConstants.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyConfig.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyCriteria.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyObject.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyDatabase.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'WortifyTextsanitizer.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . 'wortifyFile.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortifyCache.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'wortifyModel.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protector' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifySchema.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyUtils.php';

class wortify {
	public static $printStatus = false;
	public static $wortify_wp_version = false;
	public static $newVisit = false;
	
	var $conn = NULL;

	public static function __constructor() {
		global $wpdb;
		$GLOBALS['wortifydb'] = new wortifydb($wpdb);
	}
	
	public static function installPlugin(){
		self::runInstall();
		//Used by MU code below
		update_option('wortifyActivated', 1);
	}
	
	public static function uninstallPlugin(){
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
	
	public static function hourlyCron(){
		global $wpdb; $p = $wpdb->base_prefix;
		
	}
	
	public static function dailyCron(){
		$wortifydb = new wortifyDB();
		global $wpdb; $p = $wpdb->base_prefix;
		
	}
	
	public static function runInstall(){
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
	
	public static function install_actions(){
		$versionInOptions = get_option('wortify_version', false);
		if( (! $versionInOptions) || version_compare(WORTIFY_VERSION, $versionInOptions, '>')){
			//Either there is no version in options or the version in options is greater and we need to run the upgrade
			self::runInstall();
		}
		add_action('wortify_daily_cron', 'wortify::dailyCron');
		add_action('wortify_hourly_cron', 'wortify::hourlyCron');
		add_action('wp_loaded', 'wortify::loadedAction');
		add_action('init', 'wortify::initAction');
		add_action('shutdown', 'wortify::shutdownAction');
		add_action('wp_authenticate','wortify::authActionNew', 1, 2);
		if(is_admin()){
			add_action('admin_init', 'wortify::admin_init');
			if(is_multisite()){
				if(wortifyUtils::isAdminPageMU()){
					add_action('network_admin_menu', 'wortify::admin_menus');
				} //else don't show menu
			} else {
				add_action('admin_menu', 'wortify::admin_menus');
			}
		}
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache', 0777);
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify', 0777);
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'cache'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'cache', 0777);
		if (!is_dir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'configs'))
			mkdir(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'wortify' . DIRECTORY_SEPARATOR . 'configs', 0777);
	}
	
	public static function registrationFilter($errors, $santizedLogin, $userEmail){
		if(wortifyConfig::get('loginSec_blockAdminReg') && $santizedLogin == 'admin'){
			$errors->add('user_login_error', '<strong>ERROR</strong>: You can\'t register using that username');
		}
		return $errors;
	}
	
	public static function logoutAction(){
		$userID = get_current_user_id();
		$userDat = get_user_by('id', $userID);
		self::getLog()->logLogin('logout', 0, $userDat->user_login); 
	}
	
	public static function loginInitAction(){
		if(self::isLockedOut(wortifyUtils::getIP())){
			require('wortifyLockedOut.php');
		}
	}
	
	public static function authActionNew($username, &$passwd){ //As of php 5.4 we must denote passing by ref in the function definition, not the function call (as WordPress core does, which is a bug in WordPress).
		if(self::isLockedOut(wortifyUtils::getIP())){
			require('wortifyLockedOut.php');
		}
		if(! $username){ return; } 
		$userDat = get_user_by('login', $username);
		$_POST['wortify_userDat'] = $userDat;
		if(preg_match(self::$passwordCodePattern, $passwd, $matches)){ 
			$_POST['wortify_authFactor'] = $matches[1];
			$passwd = preg_replace('/^(.+)\s+(wortify[a-z0-9]+)$/i', '$1', $passwd);
			$_POST['pwd'] = $passwd;
		}
		if($userDat){
			require_once( ABSPATH . 'wp-includes/class-phpass.php');
			$hasher = new PasswordHash(8, TRUE);
			if(! $hasher->CheckPassword($_POST['pwd'], $userDat->user_pass)){
				self::getLog()->logLogin('loginFailValidUsername', 1, $username); 
			}
		} else {
			self::getLog()->logLogin('loginFailInvalidUsername', 1, $username); 
		}
	}
	
	public static function loadedAction() {
		@include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protector' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'precheck.inc.php';
		@include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'pre.loader.mainfile.php';
	}
	
	public static function initAction(){	
		global $wp;
		if (!is_object($wp)) return; //Suggested fix for compatability with "Portable phpmyadmin"
		$wp->add_query_var('_wortifysf');
		//add_rewrite_rule('wortifyStaticFunc/([a-zA-Z0-9]+)/?$', 'index.php?wortifyStaticFunc=' . $matches[1], 'top');
		$cookieName = 'wortifyvt_' . crc32(site_url());
		$c = isset($_COOKIE[$cookieName]) ? isset($_COOKIE[$cookieName]) : false;
		if($c){
			self::$newVisit = false;
		} else {
			self::$newVisit = true;
		}
		@setcookie($cookieName, uniqid(), time() + 1800, '/');
		@include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protector' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'postcheck.inc.php';
		@include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'post.loader.mainfile.php';
	}
	
	public static function admin_init(){
		if(! wortifyUtils::isAdmin()){ return; }
		foreach(array('activate', 'scan', 'updateAlertEmail', 'sendActivityLog', 'restoreFile', 'bulkOperation', 'deleteFile', 'removeExclusion', 'activityLogUpdate', 'ticker', 'loadIssues', 'updateIssueStatus', 'deleteIssue', 'updateAllIssues', 'reverseLookup', 'unlockOutIP', 'loadBlockRanges', 'unblockRange', 'blockIPUARange', 'whois', 'unblockIP', 'blockIP', 'permBlockIP', 'loadStaticPanel', 'saveConfig', 'clearAllBlocked', 'killBans', 'saveCountryBlocking', 'saveBansSchedule', 'tourClosed', 'startTourAgain', 'downgradeLicense', 'addTwoFactor', 'twoFacActivate', 'twoFacDel', 'loadTwoFactor') as $func){
			add_action('wp_ajax_wortify_' . $func, 'wortify::ajaxReceiver');
		}
		if(isset($_GET['page']) && preg_match('/^Wortify/', @$_GET['page']) ){
			wp_enqueue_style('wp-pointer');
			wp_enqueue_script('wp-pointer');
			wp_enqueue_style('wortify-main-style', wortifyUtils::getBaseURL() . 'css/main.css', '', WORTIFY_VERSION);
			wp_enqueue_style('wortify-colorbox-style', wortifyUtils::getBaseURL() . 'css/colorbox.css', '', WORTIFY_VERSION);
			wp_enqueue_style('wortify-dttable-style', wortifyUtils::getBaseURL() . 'css/dt_table.css', '', WORTIFY_VERSION);
			wp_enqueue_script('json2');
			wp_enqueue_script('jquery.tmpl', wortifyUtils::getBaseURL() . 'js/jquery.tmpl.min.js', array('jquery'), WORTIFY_VERSION);
			wp_enqueue_script('jquery.colorbox', wortifyUtils::getBaseURL() . 'js/jquery.colorbox-min.js', array('jquery'), WORTIFY_VERSION);
			wp_enqueue_script('jquery.dataTables', wortifyUtils::getBaseURL() . 'js/jquery.dataTables.min.js', array('jquery'), WORTIFY_VERSION);
			//wp_enqueue_script('jquery.tools', wortifyUtils::getBaseURL() . 'js/jquery.tools.min.js', array('jquery'));
			wp_enqueue_script('wortifyAdminjs', wortifyUtils::getBaseURL() . 'js/admin.js', array('jquery'), WORTIFY_VERSION);
			self::setupAdminVars();
		} else {
			wp_enqueue_style('wp-pointer');
			wp_enqueue_script('wp-pointer');
			wp_enqueue_script('wortifyAdminjs', wortifyUtils::getBaseURL() . 'js/tourTip.js', array('jquery'), WORTIFY_VERSION);
			self::setupAdminVars();
		}
	}
	
	private static function setupAdminVars(){
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
	
	public static function activation_warning(){
		if (strlen(get_option('xortify_username'))==0 && strlen(get_option('xortify_password'))==0 ){
			echo '<div id="wortifyConfigWarning" class="updated fade"><p><strong>Wortify has not been signed into the Xortify Cloud!</strong> <em>Go to the sign-up options</em> and complete the form and you will be activated!</p></div>';
		}
	}
	
	public static function admin_menus(){
		if(! wortifyUtils::isAdmin()){ return; }
		$warningAdded = false;
		
		if (strlen(get_option('xortify_username'))==0 && strlen(get_option('xortify_password'))==0 ){
			add_action('network_admin_notices', 'wortify::activation_warning');
		}
		$warningAdded = true;

		add_submenu_page("Wortify", "Bans", "Bans", "wortify_bans", "Wortify", 'wortify::menu_bans');
		add_menu_page('Wortify', 'Wortify', 'wortify_bans', 'Wortify', 'wortify::menu_bans', wortifyUtils::getBaseURL() . 'images/wortify-logo-16x16.png'); 
		add_submenu_page('Wortify', 'Wortify Log', 'Wortify Log', 'wortify_bans', 'WortifyLog', 'wortify::menu_logs');
		add_submenu_page("Wortify", "Protector", "Protector", "wortify_bans", "WortifyProtector", 'wortify::menu_protector');
		if ($warningAdded == false ){
			add_submenu_page("Wortify", "Cloud Signup", "Cloud Signup", "wortify_bans", "WortifyCloudSignup", 'wortify::menu_signup');
		}
		add_submenu_page("Wortify", "Options", "Options", "wortify_bans", "WortifySecOpt", 'wortify::menu_options');
	}
	
	public static function menu_options(){
		require 'menu_options.php';
	}
	
	public static function menu_logs(){
		require 'menu_logs.php';
	}
	
	public static function menu_protector(){
		require 'menu_protector.php';
	}
	
	public static function menu_signup(){
		require 'menu_signup.php';
	}
	
	public static function menu_bans(){
		require 'menu_bans.php';
	}
	
	public static function profileUpdateAction($userID, $newDat = false){
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
	
	public static function preCommentApprovedFilter($approved, $cData){
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
	
	public static function getMyHomeURL(){
		return admin_url('admin.php?page=Wortify', 'http');
	}
	
	public static function getMyOptionsURL(){
		return admin_url('admin.php?page=WortifySecOpt', 'http');
	}
	
	public static function wortify_hourly_cron() {
		include WORTIFY_VAR_PATH . '/lib/xortify/crons/serverup.php';
	}
	public static function moreCronReccurences(){
		return array(
			'everyminute' => array('interval' => 60, 'display' => 'Once Every Minute'),
		);
	}
	
	function __destruct() {
		@include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'post.footer.end.php';
	}
	
}
?>
