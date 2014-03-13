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
 * This File:	header.post.loader.php		
 * Description:	Header post loader provider for protector
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
	
	if (class_exists('Protector')) {
		
		include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
		
		$bad_ips = Protector::get_bad_ips(false);
		$cache_bad_ips = $GLOBALS['wortifyCache']->read('wortify_bans_protector');
		if (empty($cache_bad_ips))
			$cache_bad_ips = array();
	
		require_once( WORTIFY_VAR_PATH . '/lib/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' ); 	
		$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
		$apiExchange = new $func;
		
		if (is_array($cache_bad_ips)) {
			foreach($bad_ips as $id => $ip) {
				if (!in_array($ip, $cache_bad_ips)) { 
					 
						$sql = 'SELECT `timestamp`, `type`, `agent`, `description` FROM '.DB_PREFIX . ('wortify_protector_log').' WHERE ip = "'.$ip.'" ORDER BY `timestamp`';
						$result = $GLOBALS['wortifyDB']->queryF($sql);
						$comment = '';
						while($row = $GLOBALS['wortifyDB']->fetchArray($result)) {
							$comment .= (strlen($comment)>0?"\n":'').$row['timestamp']. ' - ' . $row['type'] . ' - ' . $row['agent'] . ' - ' . $row['description'];
							$agent[] = $row['agent'];
						} 
						$results[] = $apiExchange->sendBan($comment, 1, $ip);
						
						$log_handler = wortify_getmodulehandler('log', 'wortify');
						$log = $log_handler->create();
						$log->setVars(wortify_getIPData($ip));
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', $comment);
						$log->setVar('agent', implode("\n", array_unique($agent)));
						$log->setVar('email', '');
						$log->setVar('uname', '');
						$log_handler->insert($log, true);
						
					
				}
			}
		}		
		unlinkOldCachefiles('wortify_',WortifyConfig::get('xortify_ip_cache'));
		$GLOBALS['wortifyCache']->delete('wortify_bans_protector');
		$GLOBALS['wortifyCache']->write('wortify_bans_protector', $bad_ips);			
	}
?>