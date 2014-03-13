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
 * This File:	pre.loader.php		
 * Description:	Wortify Pre Loader Provider for Client
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */


	include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
	if (!$servers = wortifyCache::read('server_list_wortify')) {
		include_once( WORTIFY_VAR_PATH . '/lib/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' );
		$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
		$apiExchange = new $func;
		$poll = $apiExchange->getServers();
		if (!isset($poll['success'])||$poll['success']==false) {
			wortifyCache::write('server_list_wortify', array(0=>array('server'=>'http://wortify.com/unban/?op=unban', 'replace' => 'unban/?op=unban', 'search' => 'Solve Puzzel:'), 1=>array('server'=>'http://wortify.wortify.org/unban/?op=unban', 'replace' => 'unban/?op=unban', 'search' => 'Solve Puzzel:')), (integer)WortifyConfig::get('xortify_server_cache'));
		}
	}	

?>