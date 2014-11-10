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
 * This File:	post.loader.php		
 * Description:	Stop Forum Spam Post loader provider for Wortify Client
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
	
	include_once(_WORTIFY_VAR_PATH . ('/lib/xortify/include/functions.php'));
	
	include_once _WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
		
	$ipdata = wortify_getIPData(false);

	$checked = wortifyCache::read('xortify_sfs_'.constant('_WORTIFY_CACHE_SUFFIX'));
	
	
	if (!is_array($checked))
	{
		
		include_once( _WORTIFY_VAR_PATH . '/lib/xortify/class/'.wortifyConfig::get('xortify_protocol').'.php' );
		$func = strtoupper(wortifyConfig::get('xortify_protocol')).'WortifyExchange';
		ob_start();
		$apiExchange = new $func;
		
		$result = $apiExchange->checkSFSBans($ipdata);
		
		ob_end_flush();
		
		if (is_array($result)) {
			if (isset($result['success']))
				if ($result['success']==true)
					if (($result['email']['frequency']>=wortifyConfig::get('xortify_email_freq')||$result['username']['frequency']>=wortifyConfig::get('xortify_uname_freq')||$result['ip']['frequency']>=wortifyConfig::get('xortify_ip_freq')) &&
						(strtotime($result['email']['lastseen'])>time()-wortifyConfig::get('xortify_email_lastseen')||strtotime($result['username']['lastseen'])>time()-wortifyConfig::get('xortify_uname_lastseen')||strtotime($result['ip']['lastseen'])>time()-wortifyConfig::get('xortify_ip_lastseen'))) {
							
							
							wortifyCache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), wortifyConfig::get('xortify_wortify_ip_cache'));					
							
														
							$result = $apiExchange->sendBan(_WORTIFY_BAN_SFS_TYPE."\n".
														  _WORTIFY_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '.wortifyConfig::get('xortify_email_freq'). "\n".
														  _WORTIFY_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_email_lastseen')) . "\n" .
														  _WORTIFY_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '.wortifyConfig::get('xortify_uname_freq'). "\n".
														  _WORTIFY_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_uname_lastseen')) . "\n" .
														  _WORTIFY_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '.wortifyConfig::get('xortify_ip_freq'). "\n".
														  _WORTIFY_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_ip_lastseen')), 4, $ipdata);
 						    
							$log_handler = wortify_getmodulehandler('log', 'wortify');
							$log = $log_handler->create();
							$log->setVars($ipdata);
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'banned');
							$log->setVar('extra', _WORTIFY_BAN_SFS_TYPE."\n".
												  _WORTIFY_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '.wortifyConfig::get('xortify_email_freq'). "\n".
												  _WORTIFY_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_email_lastseen')) . "\n" .
												  _WORTIFY_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '.wortifyConfig::get('xortify_uname_freq'). "\n".
												  _WORTIFY_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_uname_lastseen')) . "\n" .
												  _WORTIFY_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '.wortifyConfig::get('xortify_ip_freq'). "\n".
												  _WORTIFY_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_ip_lastseen')));
							$lid = $log_handler->insert($log, true);
							wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), wortifyConfig::get('xortify_fault_delay'));
							$GLOBALS['wortify']['lid'] = $lid;
							setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
							header('Location: '._WORTIFY_URL.'/banned.php');
							exit(0);
							
						}
			unlinkOldCachefiles('xortify_',wortifyConfig::get('xortify_wortify_ip_cache'));
			wortifyCache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), wortifyConfig::get('xortify_wortify_ip_cache'));
			$GLOBALS['wortify']['_pass'] = true;
				
		}	
	} elseif (isset($checked['success'])&&$checked['success']==true) {
		if (($checked['email']['frequency']>=2||$checked['username']['frequency']>=2||$checked['ip']['frequency']>=2) &&
			(strtotime($checked['email']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['username']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['ip']['lastseen'])>time()-(60*60*24*7*4*3))) {
												
				$log_handler = wortify_getmodulehandler('log', 'wortify');
				$log = $log_handler->create();
				$log->setVars($ipdata);
				$log->setVar('provider', basename(dirname(__FILE__)));
				$log->setVar('action', 'blocked');
				$log->setVar('extra', _WORTIFY_BAN_SFS_TYPE."\n".
									  _WORTIFY_BAN_SFS_EMAIL_FREQ.': '.$checked['email']['frequency'].' >= '.wortifyConfig::get('xortify_email_freq'). "\n".
									  _WORTIFY_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_email_lastseen')) . "\n" .
									  _WORTIFY_BAN_SFS_USERNAME_FREQ.': '.$checked['username']['frequency'].' >= '.wortifyConfig::get('xortify_uname_freq'). "\n".
									  _WORTIFY_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_uname_lastseen')) . "\n" .
									  _WORTIFY_BAN_SFS_IP_FREQ.': '.$checked['ip']['frequency'].' >= '.wortifyConfig::get('xortify_ip_freq'). "\n".
									  _WORTIFY_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-wortifyConfig::get('xortify_ip_lastseen')));
				$lid = $log_handler->insert($log, true);
				wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), wortifyConfig::get('xortify_fault_delay'));
				$GLOBALS['wortify']['lid'] = $lid;
				setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
				header('Location: '._WORTIFY_URL.'/banned.php');
				exit(0);
				
				
		}
	}
	
?>