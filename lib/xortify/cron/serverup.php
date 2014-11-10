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
 * This File:	serverup.php		
 * Description:	Cron job for Wortify Module
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

	include_once dirname(dirname(__FILE__)).'/include/functions.php';
	
	function wortify_getURLData($URI, $search='', $curl=false) {
		$ret = '';
		foreach(array('http://'=>'https://', 'https://'=>'http://') as $prot => $protto) {
			$URI = str_replace($prot, $protto, $uri);
			try {
				switch ($curl) {
					case true:
						if (!$ch = curl_init($URI)) {
							trigger_error('Could not intialise CURL file: '.$url);
							return false;
						}
						$cookies = WORTIFY_VAR_PATH.'/cache/wortify_cache/croncurl_'.md5($URI).'.cookie'; 
				
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, wortifyConfig::get('xortify_curl_connecttimeout'));
						curl_setopt($ch, CURLOPT_TIMEOUT, wortifyConfig::get('xortify_curl_timeout'));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
						curl_setopt($ch, CURLOPT_USERAGENT, WORTIFY_USER_AGENT);
						$ret = curl_exec($ch);
						curl_close($ch);
						break;
					case false:
						$ret = file_get_contents($uri);
						break;
					
					}
				
			} catch(Exception $e) {
				echo 'Exception: "'.$e."\n";
			}
			if ($found = (strpos(strtolower($ret), strtolower($search))>0)) {
				return array('value'=>$ret, 'prot'=>$protto, 'protwas'=>$prot, 'found'=>$found);
			}
		}	
		return array('value'=>$ret, 'prot'=>$protto, 'protwas'=>$prot, 'found'=>$found);	
	}
	
	foreach (get_loaded_extensions() as $ext){
		if ($ext=="curl")
			$nativecurl=true;
	}

	
	define("SOAP", 'soap/');
	define("CURL", 'curl/');
	define("JSON", 'json/');
	define("SERIAL", 'serial/');
	define("XML", 'xml/');
	define("REST", 'api/');
	define("XREST", "xrest/");
	define("XSOAP", 'xsoap/');
	define("XCURL", 'xcurl/');
	define("XJSON", 'xjson/');
	define("XSERIAL", 'xserial/');
	define("XXML", 'xxml/');
	
	include_once $wortify->path('class/cache/wortifyCache.php');
	
	if (!defined('WORTIFY_USER_AGENT'))
		define('WORTIFY_USER_AGENT', 'Mozilla/5.0 ('.WORTIFY_VERSION.'; PHP '.PHP_VERSION.') Wortify ' . ($GLOBALS['wortify']['module']->getVar('version')/100));
	
	$serverup=false;
	if ($servers = wortifyCache::read('server_list_wortify')) {
		foreach($servers as $sid => $server) {
			$source = wortify_getURLData($server['server'], $server['search'], $nativecurl);
			if ($source['found']==true) {
				$serverup=true;
				WoriftyConfig::set('xortify_urirest', str_replace($source['protwas'], $source['prot'], strtolower(strpos($server['server'], 'module/')>0?str_replace($server['replace'], XREST, $server['server']):str_replace($server['replace'], REST, $server['server']))));
			}
		}
	}
	
	unlinkOldCachefiles('xortify_',wortifyConfig::get('xortify_wortify_ip_cache'));	
?>