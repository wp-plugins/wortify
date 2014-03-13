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
 * This File:	footer.post.loader.php		
 * Description:	Wortify Footer Post Loader provider for client
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
	
	if (isset($GLOBALS['xoDoSoap']))
	{
		
	include_once( WORTIFY_VAR_PATH . '/lib/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' );
		$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
		$apiExchange = new $func;
		$result = $apiExchange->retrieveBans();
				
		if (is_array($result)) {
		
	
			include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
		
			unlinkOldCachefiles('xortify_',WortifyConfig::get('xortify_ip_cache'));
			wortifyCache::delete('xortify_bans_cache');
			wortifyCache::delete('xortify_bans_cache_backup');			
			wortifyCache::write('xortify_bans_cache', $result, WortifyConfig::get('xortify_seconds'));
			wortifyCache::write('xortify_bans_cache_backup', $result, (WortifyConfig::get('xortify_seconds') * 1.45));			
		}		
	}
	
?>