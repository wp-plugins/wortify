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

	public static function __constructor() {

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
	}
	
	public static function dailyCron(){
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
	
	public static function wpAction() {
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protector' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'postcheck.inc.php';
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'post.loader.mainfile.php';
		
	}
	
	public static function shutdownAction() {
		
	}
	
	static function wpShutdown() {
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'post.footer.end.php';
		
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
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protector' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'precheck.inc.php';
		
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'pre.loader.mainfile.php';
		
	}
	
	public static function admin_init(){
		self::setupAdminVars();
	}
	
	public static function _network_admin_menu() {
		self::admin_menus();
	}

	public static function _user_admin_menu() {
		self::admin_menus();
	}

	public static function _admin_menu() {
		self::admin_menus();
	}
	
	public static function network_admin_menu() {
		self::admin_menus();
	}
	
	public static function user_admin_menu() {
		self::admin_menus();
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
		$warningAdded = false;
		
		if (strlen(wortifyConfig::get('xortify_username'))==0 && strlen(wortifyConfig::get('xortify_password'))==0 ){
			add_action('network_admin_notices', 'wortify::activation_warning');
			$warningAdded = true;
		}
		

		add_menu_page( 'Wortify', 'Wortify', 'manage_options', 'wortify-menu', 'wortify::menu_bans', site_url("/wp-content/plugins/wortify/images/wortify-logo-16x16.png") );
		add_submenu_page( 'wortify-menu', "Bans", "Bans", 'manage_options', 'wortify-menu-bans', 'wortify::menu_bans');
		add_submenu_page( 'wortify-menu', "Wortify Log", "Wortify Log", 'manage_options', 'wortify-menu-logs', 'wortify::menu_logs');
		add_submenu_page( 'wortify-menu', "Protector", "Protector", 'manage_options', 'wortify-menu-protector', 'wortify::menu_protector');
		add_submenu_page( 'wortify-menu', "Options", "Options", 'manage_options', 'wortify-menu-options', 'wortify::menu_options');
		if ($warningAdded == true ){
			add_submenu_page( 'wortify-menu', "Cloud Signup", "Cloud Signup", 'manage_options', 'wortify-menu-signup', 'wortify::menu_signup');
		}
	}
	
	public static function menu_options(){
		require 'menu_options.php';
	}
	
	public static function menu_logs(){
		require 'menu_log.php';
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
	
	
}
?>
