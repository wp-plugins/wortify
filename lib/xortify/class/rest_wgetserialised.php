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
 * This File:	wgetserialised.php		
 * Description:	wGET Serialised API Package Routines for Wortify Cloud
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
if (!defined('WORTIFY_REST_API'))
	define('WORTIFY_REST_API', WortifyConfig::get('xortify_urirest', WORTIFY_API_URL_WORTIFY).'%s/serial/?%s');

include_once(dirname(dirname(__FILE__)) . '/include/functions.php');

define('WORTIFY_SERIAL_LIB', 'PHPSERIAL');

class REST_WGETSERIALISEDWortifyExchange {

	var $serial_client;
	var $serial_wortify_username = '';
	var $serial_wortify_password = '';
	var $refresh = 600;
	var $serial = '';
	
	function __construct()
	{
		$this->REST_WGETSERIALISEDWortifyExchange ();
	}
	
	function REST_WGETSERIALISEDWortifyExchange () {
		
		$this->serial_wortify_username = WortifyConfig::get('xortify_username');
		$this->serial_wortify_password = md5(WortifyConfig::get('xortify_password'));
		$this->refresh = WortifyConfig::get('xortify_records');
			
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
			switch (WORTIFY_SERIAL_LIB){
				case "PHPSERIAL":
					try {
						curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'training', http_build_query(array(      "username"	=> 	$this->curl_wortify_username,
							"password"	=> 	$this->curl_wortify_password,
							'op' => ($ham==true?'ham':'spam'),
							'content' => $content
						))));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);
						$result = (unserialize($data));
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result;
	}
	
	/*
	 * Checks is content is spam
	 * 
	 * @param string $content
	 * @return boolean
	 */
	 function checkForSpam($content) {
		if (checkWordLength($content)==false&&is_group(user_groups(), $GLOBALS['wortify']['check_spams'])==true)
			return array('spam'=>true);
		if (is_group(user_groups(), $GLOBALS['wortify']['check_spams'])==false)
			return array('spam'=>false);
		switch (WORTIFY_SERIAL_LIB){
			default:
			case "PHPSERIAL":
				try {
					$data = file_get_contents(sprintf(WORTIFY_REST_API, 'spamcheck', http_build_query(array(      "username"	=> 	$this->serial_wortify_username,
							"password"	=> 	$this->serial_wortify_password, "poll" => WORTIFY_URL.'/lib/xortify/poll/',
							'content' => $content,
							'uname' => (is_object($GLOBALS['wortifyUser'])?$GLOBALS['wortifyUser']->getVar('uname'):''),
							'name' => (is_object($GLOBALS['wortifyUser'])?$GLOBALS['wortifyUser']->getVar('name'):''),
							'email' => (is_object($GLOBALS['wortifyUser'])?$GLOBALS['wortifyUser']->getVar('email'):''),
							'ip' => (class_exists('WortifyUserUtility')?WortifyUserUtility::getIP(true):$_SERVER['REMOTE_ADDR']),
							'session' => session_id()
					))));
					$result = (unserialize($data));
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
		switch (WORTIFY_SERIAL_LIB){
			default:
			case "PHPSERIAL":
				try {
					wortify_load('WortifyUserUtility');
					$uu = new WortifyUserUtility();
					$data = file_get_contents( sprintf(WORTIFY_REST_API, 'spoof'.$type, http_build_query(array(      "username"	=> 	$this->curl_wortify_username,
							"password"	=> 	$this->curl_wortify_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
							'ip' => $uu->getIP(true),
							'language' => $GLOBALS['wortifyConfig']['language'],
							'subject' => ''
					))));
					$result = (unserialize($data));
				}
				catch (Exception $e) { trigger_error($e); }
				break;
		}
		return $result;
	}
	
	
	function getServers() {
		switch (WORTIFY_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->serial_wortify_username, 
								"password"	=> 	$this->serial_wortify_password, "poll" => WORTIFY_URL.'/lib/xortify/poll/', 
								'token' => $GLOBALS['wortifySecurity']->createToken(3600, 'poll_token'),
								'agent' => $_SERVER['HTTP_USER_AGENT'],
								'session' => session_id()
						))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
							
			break;
		}			
		return $result;	
	}
	
	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = wortify_getIPData($ip);
		
		switch (WORTIFY_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->serial_wortify_username, 
								"password"	=> 	$this->serial_wortify_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->serial_wortify_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
							
			break;
		}			
		return $result;	
	}

	function checkSFSBans($ipdata) {
		
		switch (WORTIFY_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'checksfsbans', http_build_query( 
						array(  "username"	=> 	$this->serial_wortify_username, 
								"password"	=> 	$this->serial_wortify_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			
			break;
		}			
		return $result;	
	}

	function checkPHPBans($ipdata) {
		
		switch (WORTIFY_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'checkphpbans', http_build_query( 
						array(  "username"	=> 	$this->serial_wortify_username, 
								"password"	=> 	$this->serial_wortify_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result;	
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
		switch (WORTIFY_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->serial_wortify_username, "password"=> $this->serial_wortify_password,  "records"=> $this->refresh))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}

	function checkBanned($ipdata) {
		switch (WORTIFY_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(WORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->serial_wortify_username, "password"=> $this->serial_wortify_password,  "ipdata"=> $ipdata))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>