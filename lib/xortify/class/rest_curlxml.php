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
 * This File:	curlxml.php		
 * Description:	CURL XML Routines for XML API Packages
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

foreach (get_loaded_extensions() as $ext){
	if ($ext=="curl")
		$nativecurl=true;
	if ($ext=="SimpleXML")
		$nativexml=true;
		
}

if ($nativecurl==true&&$nativexml==true) {
	define('WORTIFY_CURLXML_LIB', 'PHPCURLXML');
	if (!defined('WORTIFY_USER_AGENT'))
		define('WORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) WORTIFY/20100101 WortifyAuth/1.xx (php)');
}

if (!defined('WORTIFY_REST_API'))
	define('WORTIFY_REST_API', WortifyConfig::get('xortify_urirest', WORTIFY_API_URL_WORTIFY).'%s/xml/?%s');

include_once(dirname(dirname(__FILE__)) . '/include/functions.php');

class REST_CURLXMLWortifyExchange {

	var $curl_client;
	var $xml_wortify_username = '';
	var $xml_wortify_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->REST_CURLXMLWortifyExchange ('http://wortify.com');
	}
	
	function REST_CURLXMLWortifyExchange ($url) {
		
		$this->xml_wortify_username = WortifyConfig::get('xortify_username');
		$this->xml_wortify_password = md5(WortifyConfig::get('xortify_password'));
		$this->refresh = WortifyConfig::get('xortify_records');

		if (!$this->curl_client = curl_init($url)) {
			trigger_error('Could not intialise CURL file: '.WORTIFY_REST_API);
			return false;
		}
		$cookies = WORTIFY_VAR_PATH.'/cache/wortify_cache/authcurl_'.md5(WORTIFY_REST_API).'.cookie'; 

		curl_setopt($this->curl_client, CURLOPT_CONNECTTIMEOUT, WortifyConfig::get('xortify_curl_connecttimeout'));
		curl_setopt($this->curl_client, CURLOPT_TIMEOUT, WortifyConfig::get('xortify_curl_timeout'));
		curl_setopt($this->curl_client, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($this->curl_client, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($this->curl_client, CURLOPT_VERBOSE, false);
		curl_setopt($this->curl_client, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->curl_client, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curl_client, CURLOPT_USERAGENT, WORTIFY_USER_AGENT);
		curl_setopt($this->curl_client, CURLOPT_SSL_VERIFYPEER, false);
	
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
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'training', http_build_query(array(      "username"	=> 	$this->curl_wortify_username,
				"password"	=> 	$this->curl_wortify_password,
				'op' => ($ham==true?'ham':'spam'),
				'content' => $content
				))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = wortify_elekey2numeric(wortify_xml2array($data), 'training');
			}
			catch (Exception $e) { trigger_error($e); }			
		}
		return $result['training'];
	}
	
	/*
	 * Checks is content is spam
	 * 
	 * @param string $content
	 * @return boolean
	 */
	 function checkForSpam($content = '', $uname = '', $name = '', $email = '', $ip = '', $adult = true) {
		if (checkWordLength($content)==false)
			return array('spam'=>true);
	 	
		if (!empty($this->curl_client))
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'spamcheck', http_build_query(array(      "username"	=> 	$this->xml_wortify_username,
							"password"	=> 	$this->xml_wortify_password,
							"poll" => WORTIFY_URL.'/lib/xortify/poll/',
							'content' => $content,
							'uname' => $uname,
							'name' => $name,
							'email' => $email,
							'ip' => $ip,
							'adult' => $adult,
							'session' => session_id()
				))));
				curl_setopt($this->curl_client, CURLOPT_POST, true);
				curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'content='.$content);
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = wortify_elekey2numeric(wortify_xml2array($data), 'spamcheck');
			}
			catch (Exception $e) { trigger_error($e); }
		}
		return $result['spamcheck'];
	}


	/*
	 * Get a Spoof/Trick form from the cloud
	 * 
	 * @param string $type
	 * @return array
	 */
	 function getSpoof($type = 'comment') {
		if (!empty($this->curl_client))
		{
			try {
				wortify_load('WortifyUserUtility');
				$uu = new WortifyUserUtility();
				curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'spoof'.$type, http_build_query(array(      "username"	=> 	$this->curl_wortify_username,
				"password"	=> 	$this->curl_wortify_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
				'ip' => $uu->getIP(true),
				'language' => $GLOBALS['wortifyConfig']['language'],
				'subject' => ''
						))));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);
						$result = wortify_elekey2numeric(wortify_xml2array($data), 'spoof'.$type);
			}
			catch (Exception $e) { trigger_error($e); }	
		}
		return $result['spoof'.$type];
	}
	
	
	function getServers() {
		if (!empty($this->curl_client))
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'servers', http_build_query(array(      "username"	=> 	$this->xml_wortify_username, 
								"password"	=> 	$this->xml_wortify_password, "poll" => WORTIFY_URL.'/lib/xortify/poll/', 
								'token' => sha1(microtime(true)),
								'agent' => $_SERVER['HTTP_USER_AGENT'],
								'session' => session_id()
							))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = wortify_elekey2numeric(wortify_xml2array($data), 'servers');
			}
			catch (Exception $e) { trigger_error($e); }		
		}
		return $result['servers'];	
	}
	
	function sendBan($comment, $category_id = 2, $ip=false) {
		$ipData = wortify_getIPData($ip);
		if (!empty($this->curl_client))
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'ban', http_build_query(array(      "username"	=> 	$this->xml_wortify_username, 
								"password"	=> 	$this->xml_wortify_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->xml_wortify_username, 
																		"comment" 	=> 		$comment
																)
												 ) ))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = wortify_elekey2numeric(wortify_xml2array($data), 'ban');
			}
			catch (Exception $e) { trigger_error($e); }	
		}
		return $result['ban'];	
	}

	function checkSFSBans($ipdata) {
		if (!empty($this->curl_client))
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'checksfsbans', http_build_query(array(      "username"	=> 	$this->xml_wortify_username, 
								"password"	=> 	$this->xml_wortify_password, 
								"ipdata" 	=> 	$ipdata
							))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = wortify_elekey2numeric(wortify_xml2array($data), 'checksfsbans');
			}
			catch (Exception $e) { trigger_error($e); }		
		}
		return $result['checksfsbans'];	
	}

	function checkPHPBans($ipdata) {
		if (!empty($this->curl_client))
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'checkphpbans', http_build_query(array(      "username"	=> 	$this->xml_wortify_username, 
								"password"	=> 	$this->xml_wortify_password, 
								"ipdata" 	=> 	$ipdata
							))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = wortify_elekey2numeric(wortify_xml2array($data), 'checkphpbans');
			}
			catch (Exception $e) { trigger_error($e); }		
		}
		return $result['checkphpbans'];	
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
		if (!empty($this->curl_client))
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->xml_wortify_username, "password"=> $this->xml_wortify_password,  "records"=> $this->refresh))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);				
				$result = wortify_elekey2numeric(wortify_xml2array($data),'bans');
			}
			catch (Exception $e) { trigger_error($e); }		
		}
		return $result['bans'];
	}

	function checkBanned($ipdata) {
		if (!empty($this->curl_client))
		{
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(WORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->xml_wortify_username, "password"=> $this->xml_wortify_password,  "ipdata"=> $ipdata))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);				
				$result = wortify_elekey2numeric(wortify_xml2array($data),'banned');
			}
			
			catch (Exception $e) { trigger_error($e); }				
		}		
		return $result;
	}
	
}

?>