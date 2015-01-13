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
* Download:		http://code.google.com/p/chronolabs
* This File:	post.loader.php
* Description:	Wortify Post Loader provider for client
* Date:			09/09/2012 19:34 AEST
* License:		GNU3
*
*/
	
	$checkfields = array('uname', 'email', 'ip4', 'ip6', 'network-addy', 'login_name');
	
	include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
	
	include_once( WORTIFY_VAR_PATH . '/lib/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' );
	$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
	$apiExchange = new $func;
	$bans = $apiExchange->getBans();
	
	if (get_current_user_id()<>0) {
		$ret = get_user_by('id', get_current_user_id());
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['login_name'])?$_REQUEST['login_name']:'');
		$email = (isset($_REQUEST['login_name'])?$_REQUEST['login_name']:'');
	}
	
	if (!$ipdata = wortifyCache::read('xortify_php_'.constant('_WORTIFY_CACHE_SUFFIX'))) {
		$ipdata = wortify_getIPData(false);
		wortifyCache::write('xortify_php_'.constant('_WORTIFY_CACHE_SUFFIX'), $ipdata, WortifyConfig::get('xortify_ip_cache'));
	}
	
	if (is_array($bans['data'])&&count($bans['data'])>0) {
		foreach ($bans['data'] as $id => $ban) {
			foreach($ipdata as $key => $ip) {
				if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) {
					if (in_array($key, $checkfields)) {
						if ($ban[$key] == $ip) {
														
							$log_handler = wortify_getmodulehandler('log', 'wortify');
							$log = $log_handler->create();
							$log->setVars($ipdata);
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'blocked');
							$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
												  _XOR_BAN_XORT_MATCH.' ('.$key.') '.$ban[$key].' == '.$ip.'<br/>'.
												  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
							
							$lid = $log_handler->insert($log, true);
							wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), WortifyConfig::get('xortify_fault_delay'));
							$GLOBALS['wortify']['lid'] = $lid;
							setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
							wortifyClass::__displayBan(WORTIFY_BANNED_DESCRIPTION);
						}
					}
				}
			}
		}
		unlinkOldCachefiles('xortify_',WortifyConfig::get('xortify_ip_cache'));
		$GLOBALS['wortify']['_pass'] = true;
	}
	
	if (!$checked = wortifyCache::read('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy'])))
	{
		$checked = $apiExchange->checkBanned($ipdata);
		wortifyCache::write('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($checked, array('ipdata' => $ipdata)), WortifyConfig::get('xortify_ip_cache'));
	}
	
	if (isset($checked['count'])) {
		if ($checked['count']>0) {
			foreach ($checked['bans'] as $id => $ban)
				foreach($ipdata as $key => $ip)
					if (in_array($key, $checkfields))
						if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) 
							if ($ban[$key] == $ip) {
																
								$log_handler = wortify_getmodulehandler('log', 'wortify');
								$log = $log_handler->create();
								$log->setVars($ipdata);
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'blocked');
								$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
													  _XOR_BAN_XORT_MATCH.' '.$ban[$key].' == '.$ip.'<br/>'.
													  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
								
								include_once WORTIFY_ROOT_PATH."/include/common.php";
						
								$lid = $log_handler->insert($log, true);
								wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), WortifyConfig::get('xortify_fault_delay'));
								$GLOBALS['wortify']['lid'] = $lid;
								setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
								header('Location: '.WORTIFY_URL.'/banned.php');
								exit(0);
							
							}		
		}
		unlinkOldCachefiles('xortify_',WortifyConfig::get('xortify_ip_cache'));
		$GLOBALS['wortify']['_pass'] = true;
	}
	
?>