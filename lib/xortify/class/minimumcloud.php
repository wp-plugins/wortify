<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in WORTIFY Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@chronolabs.com.au
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * See /docs/license.pdf for full license.
 * 
 * Shouts:- 	Mamba (www.wortify.org), flipse (www.nlwortify.nl)
 * 				Many thanks for your additional work with version 1.01
 * 
 * Version:		3.10 Final (Stable)
 * Published:	Chronolabs
 * Download:	http://code.google.com/p/chronolabs
 * This File:	minimumcloud.php		
 * Description:	API Calls for JSON minimumcloud Routines in Wortify Cloud
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once WORTIFY_VAR_PATH . ('/lib/xortify/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once WORTIFY_VAR_PATH . ('/lib/xortify/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->decode($data);
	}
}

foreach (get_loaded_extensions() as $ext){
	if ($ext=="curl")
		$nativecurl=true;
}

if ($nativecurl==true) {
	if (!defined('WORTIFY_MINIMUMCLOUD_LIB'))
		define('WORTIFY_MINIMUMCLOUD_LIB', 'PHPCURL');
	if (!defined('WORTIFY_USER_AGENT'))
		define('WORTIFY_USER_AGENT', sprintf(_MI_XOR_USER_AGENT, _MI_XOR_NAME, _MI_XOR_VERSION, _MI_XOR_RUNTIME, _MI_XOR_MODE));
}
if (!defined('WORTIFY_MINIMUMCLOUD_LIB'))
	define('WORTIFY_MINIMUMCLOUD_LIB', 'PHPWGET');

if (!defined('WORTIFY_REST_API'))
	define('WORTIFY_REST_API', WortifyConfig::get('xortify_urirest', WORTIFY_API_URL_WORTIFY).'%s/json/?%s');

include_once(dirname(dirname(__FILE__)) . '/include/functions.php');

class MinimumcloudWortifyExchange {

	var $curl_client;
	var $minimumcloud_wortify_username = '';
	var $minimumcloud_wortify_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->MinimumcloudWortifyExchange ();
	}
	
	function MinimumcloudWortifyExchange ($url) {
		
		$this->minimumcloud_wortify_username = WortifyConfig::get('xortify_username');
		$this->minimumcloud_wortify_password = md5(WortifyConfig::get('xortify_password'));
		$this->refresh = WortifyConfig::get('xortify_records');

		if (WORTIFY_MINIMUMCLOUD_LIB=='PHPCURL') {
			if (!$ch = curl_init($url)) {
				trigger_error('Could not intialise minimumcloud file: '.WORTIFY_REST_API);
				return false;
			}
			$cookies = WORTIFY_VAR_PATH.'/cache/wortify_cache/authminimumcloud_'.md5(WORTIFY_REST_API).'.cookie'; 
	
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, WortifyConfig::get('xortify_curl_connecttimeout'));
			curl_setopt($ch, CURLOPT_TIMEOUT, WortifyConfig::get('xortify_curl_timeout'));
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_USERAGENT, WORTIFY_USER_AGENT); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$this->curl_client =& $ch;
		}			
	}

	/*
	 * Provides training document to API
	*
	* @param string $content
	* @param boolean $ham
	* @return boolean
	*/
	function training($content, $ham = false) {
		if (!empty($this->curl_client))
			switch (WORTIFY_CURLXML_LIB){
				case "PHPCURLXML":
					try {
						$data = file_get_contents(WORTIFY_JSON_API.'?training=' . json_encode(array("username"	=> 	$this->curl_wortify_username,
								"password"	=> 	$this->curl_wortify_password,
								'op' => ($ham==true?'ham':'spam'),
								'content' => $content
						)));
						$result = wortify_obj2array(json_decode($data));
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result;
	}
	
	/*
	 * Get a Spoof/Trick form from the cloud
	*
	* @param string $type
	* @return array
	*/
	function getSpoof($type = 'comment') {
		switch (WORTIFY_JSON_LIB){
			default:
			case "PHPJSON":
				try {
					wortify_load('WortifyUserUtility');
					$uu = new WortifyUserUtility();
					$data = file_get_contents(WORTIFY_JSON_API."?spoof$type=" . urlencode(json_encode(array(      "username"	=> 	$this->curl_wortify_username,
							"password"	=> 	$this->curl_wortify_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
							'ip' => $uu->getIP(true),
							'language' => $GLOBALS['wortifyConfig']['language'],
							'subject' => ''
					))));
					$result = wortify_obj2array(json_decode($data));
				}
				catch (Exception $e) { trigger_error($e); }
				break;
		}
		return $result;
	}
	
	function checkForSpam($content, $adult=false) {
		if (checkWordLength($content)==false&&is_group(user_groups(), $GLOBALS['wortify']['check_spams'])==true)
			return array('spam'=>true);
		if (is_group(user_groups(), $GLOBALS['wortify']['check_spams'])==false)
			return array('spam'=>false);
		wortify_load('WortifyUserUtility');
		try {
			$header = "Content-type: text/html; charset=utf-8";		
			$header .= "\nMIME-version: 1.0";
			$header .= "\nContent-transfer-encoding: quoted-printable";
			$header .= "\nSubject: ".$_SERVER['REQUEST_URI'];
			$header .= "\nFrom: ".(is_object($GLOBALS['wortifyUser'])?$GLOBALS['wortifyUser']->getVar('uname'):'guest')."@".$_SERVER['REMOTE_ADDR'];
			$header .= "\nTo: ".(is_object($GLOBALS['wortifyUser'])?$GLOBALS['wortifyUser']->getVar('uname'):'guest')."@".$_SERVER['HTTP_HOST'];
			$header .= "\nDate: ".date('D, d M Y H:i:s +1000');
			$header .= "\n\n";		
	
			$exe = $GLOBALS['wortify']['wortify_mc_spamc'] . ' -c --retry-sleep=2 ';
			if ($scores!=false) {
				$exe .= '-r ';
			}
			exec($exe . '< ' . $header . $content, $report, $code);
			$result = array('spam' => (intval($code)==0?false:true));
			if ($scores!=false) {
				$result['report'] = $report;
			}
		}
		catch (Exception $e) { trigger_error($e); }
		return $result;
	
	}
	
	function getServers() {
		return array();	
	}

	function sendBan($comment, $category_id = 2, $ip=false) {
		$ipData = wortify_getIPData($ip);
		if (WORTIFY_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'ban', http_build_query(array(      "username"	=> 	$this->minimumcloud_wortify_username, 
								"password"	=> 	$this->minimumcloud_wortify_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->minimumcloud_wortify_username, 
																		"comment" 	=> 		$comment
																)
												 ) ))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = wortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }		
		} else {
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->minimumcloud_wortify_username,
						"password"	=> 	$this->minimumcloud_wortify_password,
						"bans" 		=> 	array(	0 	=> 	array_merge(
								$ipData,
								array('category_id' => $category_id)
						)
						),
						"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->minimumcloud_wortify_username,
								"comment" 	=> 		$comment
						)
						)
				))));
				$result = wortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
		}
		return $result;	
	}

	private function checksfsbans_GetFromToHost($data) {
		if (WORTIFY_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				if (!$ch = curl_init($GLOBALS['wortify']['wortify_mc_sfs_api'].'?'.$data)) {
					trigger_error('Could not intialise CURL file: '.$url);
					return false;
				}
				$cookies = WORTIFY_VAR_PATH.'/cache/wortify_cache/authcurl_'.md5($GLOBALS['wortify']['wortify_mc_sfs_api']).'.cookie';
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERAGENT, WORTIFY_USER_AGENT);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, WortifyConfig::get('xortify_curl_connecttimeout'));
				curl_setopt($ch, CURLOPT_TIMEOUT, WortifyConfig::get('xortify_curl_timeout'));
				$data = curl_exec($ch);
				curl_close($ch);
			}
			catch (Exception $e) { trigger_error($e); }
		} else {
			try {
				$data = file_get_contents($GLOBALS['wortify']['wortify_mc_sfs_api'].'?'.$data);
			}
			catch (Exception $e) { trigger_error($e); }
		}
		return wortify_obj2array(json_decode($data));
	}
	
	function checkSFSBans($ipdata) {
		try {
			$result = $this->checksfsbans_GetFromToHost('f=json&username='.$ipdata['uname'].'&email='.$ipdata['email'].'&ip='.$ipdata['ip4'].$ipdata['ip6'].'&api_key='.$GLOBALS['wortify']['wortify_mc_sfs_key']);
		}		
		catch (Exception $e) { trigger_error($e); }
		return $result;	
	}

	private function checkphpbans_dolookup($apikey,$ip){
		$itman = $apikey . "." . $ip . "." . $GLOBALS['wortify']['wortify_mc_php_api'];
		$host = gethostbyname($itman);
		return ($host);
	}
	
	function checkPHPBans($ipdata) {
		$octet = array();
		try {
			$what2lookup = implode('.', array_reverse(explode('.',$ipdata['ip4'])));
			$octet = implode('.', checkphpbans_dolookup($GLOBALS['wortify']['wortify_mc_php_key'], $what2lookup));
		}
		catch (Exception $e) { trigger_error($e); }		
		return array('success'	=>	($octet[0]=='127')?true:false,
					 'octeta'	=>	$octet[1],
					 'octetb'	=>	$octet[2],
					 'octetc'	=>	$octet[3]);	
	}
	
	function getBans() {
		include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
        if (! $bans = wortifyCache::read('xortify_bans_cache')) {
				$bans = wortifyCache::read('xortify_bans_cache_backup');
				$GLOBALS['xoDoSoap'] = true;
        }
		return $bans;
	}	
	
	function retrieveBans() {
		if (WORTIFY_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->minimumcloud_wortify_username, "password"=> $this->minimumcloud_wortify_password,  "records"=> $this->refresh)	 ) ));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);				
				$result = wortify_obj2array(json_decode($data));
			}		
			catch (Exception $e) { trigger_error($e); }
		} else {
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->minimumcloud_wortify_username, "password"=> $this->minimumcloud_wortify_password,  "records"=> $this->refresh))));
				$result = wortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
		}
		return $result;
	}

	function checkBanned($ipdata) {
		if (WORTIFY_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->minimumcloud_wortify_username, "password"=> $this->minimumcloud_wortify_password,  "ipdata"=> $ipdata)	 ) ));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);				
				$result = wortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }				
		} else {
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->minimumcloud_wortify_username, "password"=> $this->minimumcloud_wortify_password,  "ipdata"=> $ipdata))));
				$result = wortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
		}		
		return $result;
	}
	
	
}

?>