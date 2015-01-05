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
 * Description:	Project Honeypot Provider Post Loader
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
	
	
	include_once(WORTIFY_VAR_PATH . ('/lib/xortify/include/functions.php'));
	
	if (get_current_user_id()<>0) {
		$ret = get_user_by('id', get_current_user_id());
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['login_name'])?$_REQUEST['login_name']:'');
		$email = (isset($_REQUEST['login_name'])?$_REQUEST['login_name']:'');
	}

	include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
	
	if (!$ipdata = wortifyCache::read('xortify_php_'.constant('_WORTIFY_CACHE_SUFFIX'))) {
		$ipdata = wortify_getIPData(false);
		wortifyCache::write('xortify_php_'.constant('_WORTIFY_CACHE_SUFFIX'), $ipdata, WortifyConfig::get('xortify_ip_cache'));
	}
	
	if (isset($ipdata['ip4']))
		if ($ipdata['ip4']==$GLOBALS['wortifyConfig']['my_ip'])
			return false;
			
	if (isset($ipdata['ip6']))
		if ($ipdata['ip6']==$GLOBALS['wortifyConfig']['my_ip']) 
			return false;
	
	$checked = wortifyCache::read('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
		
  	
	if (!is_array($checked))
	{
		include_once( WORTIFY_VAR_PATH . '/lib/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' );
		$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
		ob_start();
		$apiExchange = new $func;
		$result = $apiExchange->checkPHPBans($ipdata);
		ob_end_flush();
		
		if (is_array($result)) {
			if ($result['success']==true)
				if (($result['octeta']<=WortifyConfig::get('xortify_octeta')&&$result['octetb']>WortifyConfig::get('xortify_octetb')&&$result['octetc']>=WortifyConfig::get('xortify_octetc'))) {
					$module_handler =& wortify_gethandler('module');
					$config_handler =& wortify_gethandler('config');
					if (!isset($GLOBALS['wortify']['module'])) $GLOBALS['wortify']['module'] = $module_handler->getByDirname('wortify');
					$configs = $config_handler->getConfigList($GLOBALS['wortify']['module']->mid());
					
					wortifyCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), WortifyConfig::get('xortify_ip_cache'));					
				
										
					$result = $apiExchange->sendBan(WORTIFY_BAN_PHP_TYPE."\n".
												  WORTIFY_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . WortifyConfig::get('xortify_octeta')."\n".
												  WORTIFY_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . WortifyConfig::get('xortify_octetb')."\n".
												  WORTIFY_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . WortifyConfig::get('xortify_octetc')."\n", 5, $ipdata);
												  
					$log_handler = wortify_getmodulehandler('log', 'wortify');
					$log = $log_handler->create();
					$log->setVars($ipdata);
					$log->setVar('provider', basename(dirname(__FILE__)));
					$log->setVar('action', 'banned');
					$log->setVar('extra', WORTIFY_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . WortifyConfig::get('xortify_octeta')."\n".
										  WORTIFY_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . WortifyConfig::get('xortify_octetb')."\n".
										  WORTIFY_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . WortifyConfig::get('xortify_octetc'));
					
					$GLOBALS['wortify']['lid'] = $log_handler->insert($log, true);
					wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), WortifyConfig::get('xortify_fault_delay'));
					$GLOBALS['wortify']['lid'] = $lid;
					setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
					wortifyClass::__displayBan(WORTIFY_BANNED_DESCRIPTION);
				
				}
			
		}
		unlinkOldCachefiles('xortify_',WortifyConfig::get('xortify_ip_cache'));
		$GLOBALS['wortify']['_pass'] = true;
		wortifyCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), WortifyConfig::get('xortify_seconds'));
		
	} elseif (isset($checked['success'])) {
		if ($checked['success']==true) {
			if (($checked['octeta']<=WortifyConfig::get('xortify_octeta')&&$checked['octetb']>WortifyConfig::get('xortify_octetb')&&$checked['octetc']>=WortifyConfig::get('xortify_octetc'))) {
				
								
				$log_handler = wortify_getmodulehandler('log', 'wortify');
				$log = $log_handler->create();
				$log->setVars($ipdata);
				$log->setVar('provider', basename(dirname(__FILE__)));
				$log->setVar('action', 'blocked');
				$log->setVar('extra', WORTIFY_BAN_PHP_OCTETA.' '.$checked['octeta'].' <= ' . WortifyConfig::get('xortify_octeta')."\n".
									  WORTIFY_BAN_PHP_OCTETB.' '.$checked['octetb'].' > ' . WortifyConfig::get('xortify_octetb')."\n".
									  WORTIFY_BAN_PHP_OCTETC.' '.$checked['octetc'].' >= ' . WortifyConfig::get('xortify_octetc'));
				
				$GLOBALS['wortify']['lid'] = $log_handler->insert($log, true);
				wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), WortifyConfig::get('xortify_fault_delay'));
				$GLOBALS['wortify']['lid'] = $lid;
				setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
				wortifyClass::__displayBan(WORTIFY_BANNED_DESCRIPTION);			
			}
		}
	}
	
?>