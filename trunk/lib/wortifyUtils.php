<?php
include_once('wortifyConfig.php');
class wortifyUtils {
	private static $privateAddrs = array(
		array('0.0.0.0/8',0,16777215),
		array('10.0.0.0/8',167772160,184549375),
		array('100.64.0.0/10',1681915904,1686110207),
		array('127.0.0.0/8',2130706432,2147483647),
		array('169.254.0.0/16',2851995648,2852061183),
		array('172.16.0.0/12',2886729728,2887778303),
		array('192.0.0.0/29',3221225472,3221225479),
		array('192.0.2.0/24',3221225984,3221226239),
		array('192.88.99.0/24',3227017984,3227018239),
		array('192.168.0.0/16',3232235520,3232301055),
		array('198.18.0.0/15',3323068416,3323199487),
		array('198.51.100.0/24',3325256704,3325256959),
		array('203.0.113.0/24',3405803776,3405804031),
		array('224.0.0.0/4',3758096384,4026531839),
		array('240.0.0.0/4',4026531840,4294967295),
		array('255.255.255.255/32',4294967295,4294967295)
	);
	private static $isWindows = false;
	public static $scanLockFH = false;
	private static $lastErrorReporting = false;
	private static $lastDisplayErrors = false;
	public static function makeTimeAgo($secs, $noSeconds = false) {
		if($secs < 1){
			return "a moment";
		}
		$months = floor($secs / (86400 * 30));
		$days = floor($secs / 86400);
		$hours = floor($secs / 3600);
		$minutes = floor($secs / 60);
		if($months) {
			$days -= $months * 30;
			return self::pluralize($months, 'month', $days, 'day');
		} else if($days) {
			$hours -= $days * 24;
			return self::pluralize($days, 'day', $hours, 'hour');
		} else if($hours) {
			$minutes -= $hours * 60;
			return self::pluralize($hours, 'hour', $minutes, 'min');
		} else if($minutes) {
			$secs -= $minutes * 60;
			return self::pluralize($minutes, 'min');
		} else {
			if($noSeconds){
				return "less than a minute";
			} else {
				return floor($secs) . " secs";
			}
		}
	}
	public static function pluralize($m1, $t1, $m2 = false, $t2 = false) {
		if($m1 != 1) {
			$t1 = $t1 . 's';
		}
		if($m2 != 1) {
			$t2 = $t2 . 's';
		}
		if($m1 && $m2){
			return "$m1 $t1 $m2 $t2";
		} else {
			return "$m1 $t1";
		}
	}
	public static function formatBytes($bytes, $precision = 2) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 

		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 

		// Uncomment one of the following alternatives
		$bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow)); 

		return round($bytes, $precision) . ' ' . $units[$pow]; 
	} 
	public static function getBaseURL(){
		return plugins_url() . '/xortify/';
	}
	public static function getPluginBaseDir(){
		return WP_CONTENT_DIR . '/plugins/';
		//return ABSPATH . 'wp-content/plugins/';
	}
	public static function getIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
			if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
				$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
			} else
				if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
					$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
				} else
					if (!empty($_SERVER['HTTP_FORWARDED'])) {
						$proxy_ip = $_SERVER['HTTP_FORWARDED'];
					} else
						if (!empty($_SERVER['HTTP_VIA'])) {
							$proxy_ip = $_SERVER['HTTP_VIA'];
						} else
							if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
								$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
							} else
								if (!empty($_SERVER['HTTP_COMING_FROM'])) {
									$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
								}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
	
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
	
		return $the_IP;
	}
	public static function isValidIP($IP){
		if(preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/', $IP, $m)){
			if(
				$m[0] >= 0 && $m[0] <= 255 &&
				$m[1] >= 0 && $m[1] <= 255 &&
				$m[2] >= 0 && $m[2] <= 255 &&
				$m[3] >= 0 && $m[3] <= 255
			){
				return true;
			}
		}
		return false;
	}
	public static function getRequestedURL(){
		if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']){
			$host = $_SERVER['HTTP_HOST'];
		} else {
			$host = $_SERVER['SERVER_NAME'];
		}
		$prefix = 'http';
		if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ){
			$prefix = 'https';
		}
		return $prefix . '://' . $host . $_SERVER['REQUEST_URI'];
	}

	public static function editUserLink($userID){
		return get_admin_url() . 'user-edit.php?user_id=' . $userID;
	}
	public static function tmpl($file, $data){
		extract($data);
		ob_start();
		include $file;
		return ob_get_contents() . (ob_end_clean() ? "" : "");
	}
	public static function bigRandomHex(){
		return dechex(rand(0, 2147483647)) . dechex(rand(0, 2147483647)) . dechex(rand(0, 2147483647));
	}
	public static function encrypt($str){
		$key = wfConfig::get('encKey');
		if(! $key){
			wortify::status(1, 'error', "Wordfence error: No encryption key found!");
			return false;
		}
		$db = new wfDB();
		return $db->querySingle("select HEX(AES_ENCRYPT('%s', '%s')) as val", $str, $key);
	}
	public static function decrypt($str){
		$key = wfConfig::get('encKey');
		if(! $key){
			wortify::status(1, 'error', "Wordfence error: No encryption key found!");
			return false;
		}
		$db = new wfDB();
		return $db->querySingle("select AES_DECRYPT(UNHEX('%s'), '%s') as val", $str, $key);
	}
	public static function lcmem(){
		$trace=debug_backtrace(); 
		$caller=array_shift($trace); 
		$c2 = array_shift($trace);
		$mem = memory_get_usage(true);
		error_log("$mem at " . $caller['file'] . " line " . $caller['line']);
	}
	public static function logCaller(){
		$trace=debug_backtrace(); 
		$caller=array_shift($trace); 
		$c2 = array_shift($trace);
		error_log("Caller for " . $caller['file'] . " line " . $caller['line'] . " is " . $c2['file'] . ' line ' . $c2['line']);
	}
	public static function getWPVersion(){
		if(wortify::$wortify_wp_version){
			return wortify::$wortify_wp_version;
		} else {
			global $wp_version;
			return $wp_version;
		}
	}
	public static function isAdminPageMU(){
		if(preg_match('/^[\/a-zA-Z0-9\-\_\s\+\~\!\^\.]*\/wp-admin\/network\//', $_SERVER['REQUEST_URI'])){ 
			return true; 
		}
		return false;
	}
	public static function getSiteBaseURL(){
		return rtrim(site_url(), '/') . '/';
	}
	public static function longestLine($data){
		$lines = preg_split('/[\r\n]+/', $data);
		$max = 0;
		foreach($lines as $line){
			$len = strlen($line);
			if($len > $max){
				$max = $len;
			}
		}
		return $max;
	}
	public static function longestNospace($data){
		$lines = preg_split('/[\r\n\s\t]+/', $data);
		$max = 0;
		foreach($lines as $line){
			$len = strlen($line);
			if($len > $max){
				$max = $len;
			}
		}
		return $max;
	}
	public static function requestMaxMemory(){
		if(wfConfig::get('maxMem', false) && (int) wfConfig::get('maxMem') > 0){
			$maxMem = (int) wfConfig::get('maxMem');
		} else {
			$maxMem = 256;
		}
		if( function_exists('memory_get_usage') && ( (int) @ini_get('memory_limit') < $maxMem ) ){
			self::iniSet('memory_limit', $maxMem . 'M');
		}
	}
	public static function isAdmin(){
		if(is_multisite()){
			if(current_user_can('manage_network')){
				return true;
			}
		} else {
			if(current_user_can('manage_options')){
				return true;
			}
		}
		return false;
	}
	public static function isWindows(){
		if(! self::$isWindows){
			if(preg_match('/^win/i', PHP_OS)){
				self::$isWindows = 'yes';
			} else {
				self::$isWindows = 'no';
			}
		}
		return self::$isWindows == 'yes' ? true : false;
	}
	public static function errorsOff(){
		self::$lastErrorReporting = ini_get('error_reporting');
		@error_reporting(E_ERROR);
		self::$lastDisplayErrors = ini_get('display_errors');
		self::iniSet('display_errors', 0);
		if(class_exists('wfScan')){ wfScan::$errorHandlingOn = false; }
	}
	public static function errorsOn(){
		@error_reporting(self::$lastErrorReporting);
		self::iniSet('display_errors', self::$lastDisplayErrors);
		if(class_exists('wfScan')){ wfScan::$errorHandlingOn = true; }
	}
	public static function localHumanDate(){
		return date('l jS \of F Y \a\t h:i:s A', time() + (3600 * WortifyConfig::get('gmt_offset')));
	}
	public static function funcEnabled($func){
		if(! function_exists($func)){ return false; }
		$disabled = explode(',', ini_get('disable_functions'));
		foreach($disabled as $f){
			if($func == $f){ return false; }
		}
		return true;
	}
	public static function iniSet($key, $val){
		if(self::funcEnabled('ini_set')){
			@ini_set($key, $val);
		}
	}
	public static function doNotCache(){
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); //In the past
		define('DONOTCACHEPAGE', true);
		define('DONOTCACHEDB', true);
		define('DONOTCDN', true);
		define('DONOTCACHEOBJECT', true);

	}
}


?>
