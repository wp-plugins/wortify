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
 * This File:	provider.php		
 * Description:	Boot strapping class for Providers with Xoritfy Client
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

if (!defined('WORTIFY_ROOT_PATH')) die ('Restricted Access');



include_once( WORTIFY_VAR_PATH . '/lib/xortify/include/functions.php' );
include_once( WORTIFY_VAR_PATH . '/lib/xortify/include/instance.php' );
include_once( WORTIFY_VAR_PATH . '/lib/xortify/language/english/modinfo.php' );

class Providers 
{
	var $providers = array();
	
	function init($check) {
		
		defined('DS') or define('DS', DIRECTORY_SEPARATOR);
		defined('NWLINE')or define('NWLINE', "\n");
		
		global $wortify, $wortifyPreload, $wortifyLogger, $wortifyErrorHandler, $wortifySecurity, $sess_handler, $wortifyConfig;
		
		include_once WORTIFY_ROOT_PATH . DS . 'include' . DS . 'defines.php';
		include_once WORTIFY_ROOT_PATH . DS . 'include' . DS . 'version.php';
		include_once WORTIFY_ROOT_PATH . DS . 'include' . DS . 'license.php';
		require_once WORTIFY_ROOT_PATH . DS . 'class' . DS . 'cache' . DS . 'wortifyCache.php';
		require_once WORTIFY_ROOT_PATH . DS . 'class' . DS . 'wortifyload.php';
		
		$GLOBALS['wortifyCache'] = new WortifyCache();
		$GLOBALS['wortifyLoad'] = new WortifyLoad();
		
		$GLOBALS['wortifyLoad']->load('preload');
		$wortifyPreload =& WortifyPreload::getInstance();
		
		$GLOBALS['wortifyLoad']->load('wortifykernel');
		$wortify = new xos_kernel_Wortify2();
		$wortify->pathTranslation();
		$wortifyRequestUri =& $_SERVER['REQUEST_URI'];// Deprecated (use the corrected $_SERVER variable now)
		
		$GLOBALS['wortifyLoad']->load('wortifysecurity');
		$wortifySecurity = new WortifySecurity();
		$wortifySecurity->checkSuperglobals();
		
		$GLOBALS['wortifyLoad']->load('wortifylogger');
		$wortifyLogger =& WortifyLogger::getInstance();
		$wortifyErrorHandler =& WortifyLogger::getInstance();
		
		include_once $wortify->path('kernel/object.php');
		include_once $wortify->path('class/criteria.php');
		include_once $wortify->path('class/module.textsanitizer.php');
		include_once $wortify->path('include/functions.php');
		
		include_once $wortify->path('class/database/databasefactory.php');
		$GLOBALS['wortifyDB'] =& WortifyDatabaseFactory::getDatabaseConnection();
		
		/**
		 * Get wortify configs
		 * Requires functions and database loaded
		 */
		$config_handler =& wortify_gethandler('config');
		$wortifyConfig = $config_handler->getConfigsByCat(WORTIFY_CONF);
		
		/**
		 * User Sessions
		 */
		$wortifyUser = '';
		$wortifyUserIsAdmin = false;
		$member_handler =& wortify_gethandler('member');
		$sess_handler =& wortify_gethandler('session');
		if ($wortifyConfig['use_ssl']
				&& isset($_POST[$wortifyConfig['sslpost_name']])
				&& $_POST[$wortifyConfig['sslpost_name']] != ''
		) {
			session_id($_POST[$wortifyConfig['sslpost_name']]);
		} else if ($wortifyConfig['use_mysession'] && $wortifyConfig['session_name'] != '' && $wortifyConfig['session_expire'] > 0) {
			if (isset($_COOKIE[$wortifyConfig['session_name']])) {
				session_id($_COOKIE[$wortifyConfig['session_name']]);
			}
			if (function_exists('session_cache_expire')) {
				session_cache_expire($wortifyConfig['session_expire']);
			}
			@ini_set('session.gc_maxlifetime', $wortifyConfig['session_expire'] * 60);
		}
		session_set_save_handler(array(&$sess_handler, 'open'),
		array(&$sess_handler, 'close'),
		array(&$sess_handler, 'read'),
		array(&$sess_handler, 'write'),
		array(&$sess_handler, 'destroy'),
		array(&$sess_handler, 'gc'));
		if (strlen(session_id())==0)
			session_start();
		
		$module_handler = wortify_gethandler('module');
		$config_handler = wortify_gethandler('config');	
		if (!isset($GLOBALS['wortify']['module']))		
			$GLOBALS['wortify']['module'] = $module_handler->getByDirname('wortify');
		if (!isset($GLOBALS['wortify']['moduleConfig']))
			$GLOBALS['wortify']['moduleConfig'] = $config_handler->getConfigList($GLOBALS['wortify']['module']->getVar('mid'));
		
		global $wortifyConfig; 
		$wortifyConfig = $config_handler->getConfigsByCat(WORTIFY_CONF);
	}
		
	function __construct($check = 'precheck')
	{	 
				
		if (strpos($_SERVER["PHP_SELF"], '/banned.php')>0) {
			return false;
		}
		
		$this->init($check);	
		$this->providers = WortifyConfig::get('xortify_providers');
		
		$this->$check();
	}
	
	private function precheck()
	{
		
		
		if (!isset($GLOBALS['wortify']['module']))
			return false;
		if ($GLOBALS['wortify']['module']->getVar('version')<305)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/precheck.inc.php')) 
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/precheck.inc.php');
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/pre.loader.php')) 
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/pre.loader.php');
			
		}
		
	}
	
	private function postcheck()
	{
		
		if (!isset($GLOBALS['wortify']['module']))
			return false;
		if ($GLOBALS['wortify']['module']->getVar('version')<305)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/postcheck.inc.php'))
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/postcheck.inc.php');
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/post.loader.php'))
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/post.loader.php');
		}
		
	}
	
	private function headerpostcheck()
	{
		
		
		if (!isset($GLOBALS['wortify']['module']))
			return false;
		
		if ($GLOBALS['wortify']['module']->getVar('version')<305)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/headerpostcheck.inc.php'))
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/headerpostcheck.inc.php');
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/header.post.loader.php'))
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/header.post.loader.php');
			
		}
		
	}
	
	private function footerpostcheck()
	{
		
		
		if ($GLOBALS['wortify']['module']->getVar('version')<305)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/footerpostcheck.inc.php'))
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/footerpostcheck.inc.php');
			if (file_exists(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/footer.post.loader.php'))
				include_once(WORTIFY_VAR_PATH . '/lib/xortify/providers/'.$key.'/footer.post.loader.php');
			
		}
		
	}
}

?>