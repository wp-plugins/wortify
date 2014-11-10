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
 * This File:	auth_curl.php		
 * Description:	Auth Library with CURL JSON routines for signup and API
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

 
if (!defined('WORTIFY_REST_API'))
	define('WORTIFY_REST_API', WortifyConfig::get('xortify_urirest', WORTIFY_API_URL_WORTIFY).'%s/json/?%s');

if (!defined('WORTIFY_USER_AGENT'))
		define('WORTIFY_USER_AGENT', sprintf(_MI_XOR_USER_AGENT, _MI_XOR_NAME, _MI_XOR_VERSION, _MI_XOR_RUNTIME, _MI_XOR_MODE));
	
include_once WORTIFY_ROOT_PATH . '/lib/xortify/class/auth/auth_rest_curl_provisionning.php';

class WortifyAuthRest_Curl extends WortifyAuth {
	
	var $curl_client;
	var $curl_wortify_username = '';
	var $curl_wortify_password = '';
	var $_dao;
	var $_json = '';
	/**
	 * Authentication Service constructor
	 */
	function WortifyAuthRest_Curl (&$dao) {
		if (!$ch = curl_init()) {
			trigger_error('Could not intialise CURL file: '.WORTIFY_REST_API);
			return false;
		}
		$cookies = WORTIFY_VAR_PATH.'/cache/wortify_cache/authcurl_'.md5(WORTIFY_REST_API).'.cookie'; 

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, WortifyConfig::get('xortify_curl_connecttimeout'));
		curl_setopt($ch, CURLOPT_TIMEOUT, WortifyConfig::get('xortify_curl_timeout'));
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, WORTIFY_USER_AGENT); 
		$this->curl_client =& $ch;
	}


	/**
	 *  Authenticate  user again curl directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
	function authenticate($uname, $pwd = null) {
		$authenticated = false;
		$this->WortifyAuthRest_Curl($GLOBALS['wortifyDB']);
		
		if (!$this->curl_client) {
			$this->setErrors(0, _AUTH_CURL_EXTENSION_NOT_LOAD);
			return $authenticated;
		}

				
		$rnd = rand(-100000, 100000000);
		curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'xortify_authentication', http_build_query(array("username"=> $this->curl_wortify_username, "password"=> $this->curl_wortify_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)))));
		$data = curl_exec($this->curl_client);
		$GLOBALS['error'] = curl_errno($this->curl_client) . ' - ' . curl_error($this->curl_client);
		curl_close($this->curl_client);
		$result = $this->obj2array(json_decode($data));	
		return $result["RESULT"];		
	}
	
				  
	/**
	 *  validate a user via curl
	 *
	 * @param string $uname
	 * @param string $email
	 * @param string $pass
	 * @param string $vpass
	 *
	 * @return string
	 */		
	function validate($uname, $email, $pass, $vpass){
		$this->WortifyAuthRest_Curl($GLOBALS['wortifyDB']);
		$rnd = rand(-100000, 100000000);
		curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'xortify_user_validate', http_build_query(array("username"=> $this->curl_wortify_username, "password"=> $this->curl_wortify_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		$result = $this->obj2array(json_decode($data));	
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}
	
	}

	
	/**
	 *  get the wortify site disclaimer via curl
	 *
	 * @return string
	 */			
	function network_disclaimer(){
		$this->WortifyAuthRest_Curl($GLOBALS['wortifyDB']);
		curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'xortify_network_disclaimer', http_build_query(array("username"=> $this->curl_wortify_username, "password"=> $this->curl_wortify_password))));
		$data = curl_exec($this->curl_client);
		if (curl_errno($this->curl_client)>0) $GLOBALS['error'] = curl_errno($this->curl_client) . ' - ' . curl_error($this->curl_client);
		curl_close($this->curl_client);

		$result = $this->obj2array(json_decode($data));
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}

	}
	
	/**
	 *  create a user
	 *
	 * @param bool $user_viewemail
	 * @param string $uname
	 * @param string $email
	 * @param string $url
	 * @param string $actkey
	 * @param string $pass
	 * @param integer $timezone_offset
	 * @param bool $user_mailok		 
	 * @param array $siteinfo
	 *
	 * @return array
	 */	
	function create_user($user_viewemail, $uname, $email, $url, $actkey, 
						 $pass, $timezone_offset, $user_mailok, $siteinfo){
						 
		$siteinfo = $this->check_siteinfo($siteinfo);

		$rnd = rand(-100000, 100000000);
		$this->WortifyAuthRest_Curl($GLOBALS['wortifyDB']);
		curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'xortify_create_user', http_query_build(array("username"=> $this->curl_wortify_username, "password"=> $this->curl_wortify_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd), "siteinfo" => $siteinfo))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);	
		$result = $this->obj2array(json_decode($data));		
		if ($result['ERRNUM']==1){
			return $result["RESULT"];		
		} else {
			return false;
		}
	}
	
	function obj2array($objects) {
		$ret = array();
		foreach($objects as $key => $value) {
			if (is_a($value, 'stdClass')) {
				$ret[$key] = (array)$value;
			} elseif (is_array($value)) {
				$ret[$key] = $this->obj2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
	
}
// end class


?>
