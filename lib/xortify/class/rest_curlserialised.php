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
 * This File:	curlserialised.php		
 * Description:	CURL API Routines for Serialisation Packages
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

foreach (get_loaded_extensions() as $ext){
	if ($ext=="curl")
		$nativecurl=true;
}

if ($nativecurl==true) {
	if (!defined('_WORTIFY_CURLSERIAL_LIB'))
		define('_WORTIFY_CURLSERIAL_LIB', 'PHPCURLSERIAL');
	if (!defined('_WORTIFY_USER_AGENT'))
		define('_WORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) WORTIFY/20100101 WortifyAuth/1.xx (php)');
}

if (!defined('_WORTIFY_REST_API'))
	define('_WORTIFY_REST_API', WortifyConfig::get('xortify_urirest', _WORTIFY_API_URL_WORTIFY).'%s/serial/?%s');

include_once(dirname(dirname(__FILE__)) . '/include/functions.php');

class REST_CURLSERIALISEDWortifyExchange {

	var $curl_client;
	var $serial_wortify_username = '';
	var $serial_wortify_password = '';
	var $refresh = 600;
	var $json = '';
	
	function __construct()
	{
		
		$this->serial_wortify_username = WortifyConfig::get('xortify_username');
		$this->serial_wortify_password = md5(WortifyConfig::get('xortify_password'));
		$this->refresh = WortifyConfig::get('xortify_records');
		
	}
	
	private function cURL ($url='', $post = '') {


		if (!$ch = curl_init($url)) {
			die('Could not intialise CURLSERIAL file: '.$url);
			return false;
		}
		$cookies = _WORTIFY_VAR_PATH.'/cache/wortify_cache/authcurl_'.md5($url).'.cookie'; 

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, WortifyConfig::get('xortify_curl_connecttimeout'));
		curl_setopt($ch, CURLOPT_TIMEOUT, WortifyConfig::get('xortify_curl_timeout'));
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, !empty($post));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_USERAGENT, _WORTIFY_USER_AGENT);

		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
	}

	/*
	 * Provides training document to API
	 * 
	 * @param string $content
	 * @param boolean $ham
	 * @return boolean
	 */
	 function training($content, $ham = false) {
		$result = array();
		try {
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'training', http_build_query(array(      "username"	=> 	$this->serial_wortify_username,
			"password"	=> 	$this->serial_wortify_password,
			'op' => ($ham==true?'ham':'spam'),
			'content' => $content
			))));
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); };
		return $result;
	}
	
	/*
	 * Get a Spoof/Trick form from the cloud
	*
	* @param string $type
	* @return array
	*/
	function getSpoof($type = 'comment') {
		$result = array();
		try {
			wortify_load('WortifyUserUtility');
			$uu = new WortifyUserUtility();
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'spoof'.$type, http_build_query(array(      "username"	=> 	$this->serial_wortify_username,
			"password"	=> 	$this->serial_wortify_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
			'ip' => $uu->getIP(true),
			'language' => $GLOBALS['wortifyConfig']['language'],
			'subject' => ''
					))));
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); }
		return $result;
	}
	
	/*
	 * Checks is content is spam
	 * 
	 * @param string $content
	 * @return boolean
	 */
	function checkForSpam($content = '', $uname = '', $name = '', $email = '', $ip = '', $adult = true) 
	{
		if (checkWordLength($content)==false)
			return array('spam'=>true);
		try {
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'spamcheck', http_build_query(array(      "username"	=> 	$this->serial_wortify_username,
						"password"	=> 	$this->serial_wortify_password, 
						"poll" => _WORTIFY_URL.'/lib/xortify/poll/',
						'content' => $content,
						'uname' => $uname,
						'name' => $name,
						'email' => $email,
						'ip' => $ip,
						'adult' => $adult,
						'session' => session_id()
			))), 'content='.$content);
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); 	}
		return $result;
	}
	
	function getServers() 
	{
		$result = array();
		try {
			curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'servers', http_build_query(array(      "username"	=> 	$this->serial_wortify_username, 
							"password"	=> 	$this->serial_wortify_password, "poll" => _WORTIFY_URL.'/lib/xortify/poll/', 
							'token' => sha1(microtime(true)),
							'agent' => $_SERVER['HTTP_USER_AGENT'],
							'session' => session_id()
						))));
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); }				
		return $result;	
	}
	

	function sendBan($comment, $category_id = 2, $ip=false) 
	{
		$ipData = wortify_getIPData($ip);
		$result = array();	
		try {
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'ban', http_build_query(array(      "username"	=> 	$this->serial_wortify_username, 
							"password"	=> 	$this->serial_wortify_password, 
							"bans" 		=> 	array(	0 	=> 	array_merge(
																		$ipData, 
																		array('category_id' => $category_id)
																		)
											),
							"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->serial_wortify_username, 
																	"comment" 	=> 		$comment
															)
											 ) ))));
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); }	
		return $result;	
	}

	function checkSFSBans($ipdata) 
	{
		$result = array();		
		try {
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'checksfsbans', http_build_query(array(      "username"	=> 	$this->serial_wortify_username, 
							"password"	=> 	$this->serial_wortify_password, 
							"ipdata" 	=> 	$ipdata
						))));
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); }				
		return $result;	
	}

	function checkPHPBans($ipdata) 
	{
		$result = array();
		try {
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'checkphpbans', http_build_query(array(      "username"	=> 	$this->serial_wortify_username, 
							"password"	=> 	$this->serial_wortify_password, 
							"ipdata" 	=> 	$ipdata
						))));
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); }		
		return $result;	
	}
	
	function getBans() 
	{
		include_once _WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
        if (! $bans = wortifyCache::read('xortify_bans_cache')) {
			$bans = wortifyCache::read('xortify_bans_cache_backup');
			$GLOBALS['xoDoSoap'] = true;
        }
		return $bans;
	}	
	
	function retrieveBans() 
	{
		$result = array();
		try {
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->serial_wortify_username, "password"=> $this->serial_wortify_password,  "records"=> $this->refresh))	 ) );
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); }		
		return $result;
	}

	function checkBanned($ipdata) 
	{
		$result = array();
		try {
			$data = $this->cURL(sprintf(_WORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->serial_wortify_username, "password"=> $this->serial_wortify_password,  "ipdata"=> $ipdata)	 ) ));		
			$result = (unserialize($data));
		}
		catch (Exception $e) { trigger_error($e); }				
		return $result;
	}
	
}

?>