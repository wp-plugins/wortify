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
 * This File:	core.php		
 * Description:	Preloader Hooking Stratum for Wortify Client
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

defined('WORTIFY_ROOT_PATH') or die('Restricted access');

include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
include_once WORTIFY_ROOT_PATH.'/modules/xortify/include/functions.php';
include_once WORTIFY_ROOT_PATH.'/modules/xortify/include/instance.php';

class WortifyCorePreload extends WortifyPreloadItem
{
	
	static function eventCoreIncludeCommonStart($args)
	{
		
		//$GLOBALS['wortifyLoad'] = new WortifyLoad();
		$GLOBALS['wortifyCache'] = new WortifyCache();
		// Detect if it is an internal refereer.
		$ip = wortify_getIP();
		if (isset($_SERVER['HTTP_REFERER'])&&$result = wortifyCache::read('xortify_'.strtolower(__FUNCTION__).'_'.md5($ip))) {
			if (strtolower(WORTIFY_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(WORTIFY_URL)))&&$result['time']<microtime(true)) {
				wortifyCache::write('xortify_'.strtolower(__FUNCTION__).'_'.md5($ip), array('time'=>microtime(true)+1800), 1800);
				return false;
			}
		}
		wortifyCache::write('xortify_'.strtolower(__FUNCTION__).'_'.md5($ip), array('time'=>microtime(true)+1800), 1800);
		// Runs Security Preloader
		$result = wortifyCache::read('xortify_core_include_common_start');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			wortifyCache::write('xortify_core_include_common_start', array('time'=>microtime(true)+600), 600);
			include_once WORTIFY_ROOT_PATH . ( '/modules/xortify/include/pre.loader.mainfile.php' );
			wortifyCache::write('xortify_core_include_common_start', array('time'=>microtime(true)), -1);
		}
	}

	static function eventCoreIncludeCommonEnd($args)
	{
		wortify_loadLanguage('modinfo', 'wortify');
		$module_handler = wortify_gethandler('module');
		$config_handler = wortify_gethandler('config');		
		if (!isset($GLOBALS['wortify'])||!isset($GLOBALS['wortify']['module'])||!isset($GLOBALS['wortify']['module']))
			$GLOBALS['wortify']['module'] = $module_handler->getByDirname('wortify');
		if (!isset($GLOBALS['wortify']['moduleConfig'])&&is_object($GLOBALS['wortify']['module']))
			$GLOBALS['wortify']['moduleConfig'] = $config_handler->getConfigList($GLOBALS['wortify']['module']->getVar('mid'));
		
		$result = wortifyCache::read('xortify_cleanup_last');
		if ((isset($result['when'])?(float)$result['when']:-microtime(true))+$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache']<=microtime(true)) {
			$result = array();
			$result['when'] = microtime(true);
			$result['files'] = 0;
			$result['size'] = 0;
			foreach(WortifyCorePreload::getFileListAsArray(WORTIFY_VAR_PATH.'/caches/wortify_cache/', 'wortify') as $id => $file) {
				if (file_exists(WORTIFY_VAR_PATH.'/caches/wortify_data/'.$file)&&!empty($file)) {
					if (@filectime(WORTIFY_VAR_PATH.'/caches/wortify_data/'.$file)<time()-$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache']) {
						$result['files']++;
						$result['size'] = $result['size'] + filesize(WORTIFY_VAR_PATH.'/caches/wortify_data/'.$file);
						@unlink(WORTIFY_VAR_PATH.'/caches/wortify_data/'.$file);
					}
				}
			}
			$result['took'] = microtime(true)-$result['when'];
			wortifyCache::write('xortify_cleanup_last', $result, $GLOBALS['wortify']['moduleConfig']['wortify_ip_cache']*2);
		}
		
		if (isset($_POST)&&isset($_POST['wortify_check'])) {
			self::doSpamCheck($_POST, 'wortify_check');
		}
		
		if (isset($GLOBALS['wortify']['lid']))
			if ($GLOBALS['wortify']['lid']==0)
				unset($GLOBALS['wortify']);
				
		if (strpos($_SERVER["PHP_SELF"], '/banned.php')>0) {
			return false;
		}
		
		if ((isset($_COOKIE['wortify_lid'])&&$_COOKIE['wortify_lid']!=0)||(isset($GLOBALS['wortify']['lid'])&&$GLOBALS['wortify']['lid']!=0)&&!strpos($_SERVER["PHP_SELF"], '/banned.php')) {
			header('Location: '.WORTIFY_URL.'/banned.php');
			exit;
		} 

		// Detect if it is an internal refereer.
		if (isset($_SERVER['HTTP_REFERER'])&&(isset($GLOBALS['wortify'][__FUNCTION__]))) {
			if (strtolower(WORTIFY_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(WORTIFY_URL)))&&$GLOBALS['wortify'][__FUNCTION__]<microtime(true)) {
				$GLOBALS['wortify'][__FUNCTION__] = microtime(true)+$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache'];
				return false;
			}
		}
		$GLOBALS['wortify'][__FUNCTION__] = microtime(true)+$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache'];
		
		// Runs Security Preloader
	    $result = wortifyCache::read('xortify_core_include_common_end');
	    if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)+$GLOBALS['wortify']['moduleConfig']['fault_delay']), $GLOBALS['wortify']['moduleConfig']['fault_delay']);
			if (WortifyCorePreload::hasAPIUserPass()) {
				include_once WORTIFY_ROOT_PATH . ( '/modules/xortify/include/post.loader.mainfile.php' );
			}
			wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['wortify']['moduleConfig']['fault_delay']);
		}
		
		
	}

	static function eventCoreHeaderCacheEnd($args)
	{
		
		// Detect if it is an internal refereer.
		if (isset($_SERVER['HTTP_REFERER'])&&(isset($GLOBALS['wortify'][__FUNCTION__]))) {
			if (strtolower(WORTIFY_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(WORTIFY_URL)))&&$GLOBALS['wortify'][__FUNCTION__]<microtime(true)) {
				$GLOBALS['wortify'][__FUNCTION__] = microtime(true)+$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache'];
				return false;
			}
		}
		$GLOBALS['wortify'][__FUNCTION__] = microtime(true)+$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache'];
		// Runs Security Preloader
		include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
		$result = wortifyCache::read('xortify_core_header_cache_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			wortifyCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)+$GLOBALS['wortify']['moduleConfig']['fault_delay']), $GLOBALS['wortify']['moduleConfig']['fault_delay']);
			if (WortifyCorePreload::hasAPIUserPass()) { 		
				include_once WORTIFY_ROOT_PATH . ( '/modules/xortify/include/post.header.endcache.php' );
			}
			wortifyCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)), -1);
		}
		
	}

	static function eventCoreFooterEnd($args)
	{
			// Detect if it is an internal refereer.
		if (isset($_SERVER['HTTP_REFERER'])&&(isset($GLOBALS['wortify'][__FUNCTION__]))) {
			if (strtolower(WORTIFY_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(WORTIFY_URL)))&&$GLOBALS['wortify'][__FUNCTION__]<microtime(true)) {
				$GLOBALS['wortify'][__FUNCTION__] = microtime(true)+$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache'];
				return false;
			}
		}
		$GLOBALS['wortify'][__FUNCTION__] = microtime(true)+$GLOBALS['wortify']['moduleConfig']['wortify_ip_cache'];
		// Runs Security Preloader
		include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
		$result = wortifyCache::read('xortify_core_footer_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			wortifyCache::write('xortify_core_footer_end', array('time'=>microtime(true)+$GLOBALS['wortify']['moduleConfig']['fault_delay']), $GLOBALS['wortify']['moduleConfig']['fault_delay']);
			if (WortifyCorePreload::hasAPIUserPass()) { 		
				include_once WORTIFY_ROOT_PATH . ( '/modules/xortify/include/post.footer.end.php' );
			}
			wortifyCache::write('xortify_core_footer_end', array('time'=>microtime(true)), -1);
		}
		
	}
	
	static function eventCoreHeaderAddmeta($args)
	{
		
		/*
		if (isset($GLOBALS['wortify']['_pass'])) {
			if ($GLOBALS['wortify']['_pass'] == true) {
				include_once WORTIFY_ROOT_PATH.'/modules/xortify/include/functions.php';
				addmeta_googleanalytics(_XOR_MI_WORTIFY_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED'))>=13) { 
					addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				}	
			}
		}*/
		
		include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
		$result = wortifyCache::read('xortify_core_header_add_meta');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			wortifyCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)+$GLOBALS['wortify']['moduleConfig']['fault_delay']), $GLOBALS['wortify']['moduleConfig']['fault_delay']);
			if (WortifyCorePreload::hasAPIUserPass()) {	
				include_once WORTIFY_ROOT_PATH . ( '/modules/xortify/include/post.header.addmeta.php' );
			}
			wortifyCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)), -1);
		}
		
	}
	
	static function hasAPIUserPass()
	{
		
		
		if ($GLOBALS['wortify']['moduleConfig']['wortify_username']!=''&&$GLOBALS['wortify']['moduleConfig']['wortify_password']!='')
			return true;
		else
			return false;
	}		
	
	static function getFileListAsArray($dirname, $prefix="wortify")
	{
		
		
		$filelist = array();
		if (substr($dirname, -1) == '/') {
			$dirname = substr($dirname, 0, -1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (!preg_match("/^[\.]{1,2}$/",$file) && is_file($dirname.'/'.$file)) {
					if (!empty($prefix)&&strpos(' '.$file, $prefix)>0) {
						$filelist[$file] = $file;
					} elseif (empty($prefix)) {
						$filelist[$file] = $file;
					}
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}
		return $filelist;
	}
	
	static public function doSpamCheck( $_from = array(), $source = 'wortify_check') {
		if (isset($_from[$source])&&count($_from[$source])>0) {
			include_once( WORTIFY_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['wortify']['moduleConfig']['protocol'].'.php' );
			$func = strtoupper($GLOBALS['wortify']['moduleConfig']['protocol']).'WortifyExchange';
			$apiExchange = new $func;
			foreach ($_from[$source] as $id => $field) {
				$field = str_replace('[]', '', $field);
				if (is_array($_from[$field])) {
					foreach ($_from[$field] as $id => $data) {
						$result = $apiExchange->checkForSpam($data);
						if ($result['spam']==true) {
							$wortifycookie = unserialize($_COOKIE['wortify']);
							if (isset($wortifycookie['spams']))
								$GLOBALS['wortify']['spams'] = $wortifycookie['wortify']['spams'];
							$GLOBALS['wortify']['spams'] = $GLOBALS['wortify']['spams'] + 1;
							unset($_COOKIE['wortify']['spams']);
							setcookie('wortify', serialise(array_merge($wortifycookie,array('spams' => $GLOBALS['wortify']['spams']))), time()+3600*24*7*4*3);
														if ($GLOBALS['wortify']['spams']>=$GLOBALS['wortify']['moduleConfig']['spams_allowed']) {
									
								$results[] = $apiExchange->sendBan(array('comment'=>_XOR_SPAM . ' :: [' . $data . '] len('.strlen($data).')'), 2, wortify_getIPData());
									
								$log_handler = wortify_getmodulehandler('log', 'wortify');
								$log = $log_handler->create();
								$log->setVars(wortify_getIPData($ip));
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'banned');
								$log->setVar('extra', _XOR_SPAM . ' :: [' . $data . '] len('.strlen($data).')');
								$log->setVar('agent', $_SERVER['HTTP_USER_AGENT']);
								if (isset($GLOBALS['wortifyUser'])) {
									$log->setVar('email', $GLOBALS['wortifyUser']->getVar('email'));
									$log->setVar('uname', $GLOBALS['wortifyUser']->getVar('uname'));
								}
		
								$lid = $log_handler->insert($log, true);
								wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['wortify']['moduleConfig']['fault_delay']);
								$GLOBALS['wortify']['lid'] = $lid;
								setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
								header('Location: '.WORTIFY_URL.'/banned.php');
								exit(0);
							} else {
								$log_handler = wortify_getmodulehandler('log', 'wortify');
								$log = $log_handler->create();
								$log->setVars($ipdata);
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'blocked');
								$log->setVar('extra', _XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')');
								if (isset($GLOBALS['wortifyUser'])) {
									$log->setVar('email', $GLOBALS['wortifyUser']->getVar('email'));
									$log->setVar('uname', $GLOBALS['wortifyUser']->getVar('uname'));
								}
								$lid = $log_handler->insert($log, true);
									
		
										
								$module_handler = wortify_gethandler('module');
								$GLOBALS['wortifyModule'] = $module_handler->getByDirname('wortify');
		
								$wortifyOption['template_main'] = 'wortify_spamming_notice.html';
								include_once WORTIFY_ROOT_PATH.'/header.php';
									
								addmeta_googleanalytics(_XOR_MI_WORTIFY_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
								if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
									addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
								}
		
								$GLOBALS['wortifyTpl']->assign('xortify_pagetitle', _XOR_SPAM_PAGETITLE);
								$GLOBALS['wortifyTpl']->assign('description', _XOR_SPAM_DESCRIPTION);
								$GLOBALS['wortifyTpl']->assign('version', $GLOBALS['wortifyModule']->getVar('version')/100);
								$GLOBALS['wortifyTpl']->assign('platform', WORTIFY_VERSION);
								$GLOBALS['wortifyTpl']->assign('provider', basename(dirname(__FILE__)));
								$GLOBALS['wortifyTpl']->assign('spam', htmlspecialchars($data));
								$GLOBALS['wortifyTpl']->assign('agent', $_SERVER['HTTP_USER_AGENT']);
		
								$GLOBALS['wortifyTpl']->assign('xortify_lblocks', false);
								$GLOBALS['wortifyTpl']->assign('xortify_rblocks', false);
								$GLOBALS['wortifyTpl']->assign('xortify_ccblocks', false);
								$GLOBALS['wortifyTpl']->assign('xortify_clblocks', false);
								$GLOBALS['wortifyTpl']->assign('xortify_crblocks', false);
								$GLOBALS['wortifyTpl']->assign('xortify_showlblock', false);
								$GLOBALS['wortifyTpl']->assign('xortify_showrblock', false);
								$GLOBALS['wortifyTpl']->assign('xortify_showcblock', false);
		
								include_once WORTIFY_ROOT_PATH.'/footer.php';
							}
							exit(0);
						}
					}
				} else {
					$result = $apiExchange->checkForSpam($_from[$field], is_group(user_groups(), $GLOBALS['wortify']['allow_adult']));
					if ($result['spam']==true) {
							
						$wortifycookie = unserialize($_COOKIE['wortify']);
						if (isset($wortifycookie['spams']))
							$GLOBALS['wortify']['spams'] = $wortifycookie['wortify']['spams'];
						$GLOBALS['wortify']['spams'] = $GLOBALS['wortify']['spams'] + 1;
						unset($_COOKIE['wortify']['spams']);
						setcookie('wortify', serialise(array_merge($_COOKIE['wortify'],array('spams' => $GLOBALS['wortify']['spams']))), time()+3600*24*7*4*3);
		
												print_r($GLOBALS['wortify']);
						exit(0);
							
													
						if ($GLOBALS['wortify']['spams']>=$GLOBALS['wortify']['moduleConfig']['spams_allowed']) {
								
							$results[] = $apiExchange->sendBan(array('comment'=>_XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')'), 2, wortify_getIPData());
								
							$log_handler = wortify_getmodulehandler('log', 'wortify');
							$log = $log_handler->create();
							$log->setVars(wortify_getIPData($ip));
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'banned');
							$log->setVar('extra', _XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')');
							$log->setVar('agent', $_SERVER['HTTP_USER_AGENT']);
							if (isset($GLOBALS['wortifyUser'])) {
								$log->setVar('email', $GLOBALS['wortifyUser']->getVar('email'));
								$log->setVar('uname', $GLOBALS['wortifyUser']->getVar('uname'));
							}
		
							$lid = $log_handler->insert($log, true);
							wortifyCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['wortify']['moduleConfig']['fault_delay']);
							$GLOBALS['wortify']['lid'] = $lid;
							setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
							header('Location: '.WORTIFY_URL.'/banned.php');
							exit(0);
		
						} else {
							$log_handler = wortify_getmodulehandler('log', 'wortify');
							$log = $log_handler->create();
							$log->setVars($ipdata);
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'blocked');
							$log->setVar('extra', _XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')');
							if (isset($GLOBALS['wortifyUser'])) {
								$log->setVar('email', $GLOBALS['wortifyUser']->getVar('email'));
								$log->setVar('uname', $GLOBALS['wortifyUser']->getVar('uname'));
							}
							$lid = $log_handler->insert($log, true);
		
							$module_handler = wortify_gethandler('module');
							$GLOBALS['wortifyModule'] = $module_handler->getByDirname('wortify');
		
							$wortifyOption['template_main'] = 'wortify_spamming_notice.html';
							include_once WORTIFY_ROOT_PATH.'/header.php';
		
							addmeta_googleanalytics(_XOR_MI_WORTIFY_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
							if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
								addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
							}
		
							$GLOBALS['wortifyTpl']->assign('xortify_pagetitle', _XOR_SPAM_PAGETITLE);
							$GLOBALS['wortifyTpl']->assign('description', _XOR_SPAM_DESCRIPTION);
							$GLOBALS['wortifyTpl']->assign('version', $GLOBALS['wortifyModule']->getVar('version')/100);
							$GLOBALS['wortifyTpl']->assign('platform', WORTIFY_VERSION);
							$GLOBALS['wortifyTpl']->assign('provider', basename(dirname(__FILE__)));
							$GLOBALS['wortifyTpl']->assign('spam', htmlspecialchars($_REQUEST[$field]));
							$GLOBALS['wortifyTpl']->assign('agent', $_SERVER['HTTP_USER_AGENT']);
		
							$GLOBALS['wortifyTpl']->assign('xortify_lblocks', false);
							$GLOBALS['wortifyTpl']->assign('xortify_rblocks', false);
							$GLOBALS['wortifyTpl']->assign('xortify_ccblocks', false);
							$GLOBALS['wortifyTpl']->assign('xortify_clblocks', false);
							$GLOBALS['wortifyTpl']->assign('xortify_crblocks', false);
							$GLOBALS['wortifyTpl']->assign('xortify_showlblock', false);
							$GLOBALS['wortifyTpl']->assign('xortify_showrblock', false);
							$GLOBALS['wortifyTpl']->assign('xortify_showcblock', false);
		
							include_once WORTIFY_ROOT_PATH.'/footer.php';
						}
						exit(0);
					}
				}
			}
		}
	}

}

?>