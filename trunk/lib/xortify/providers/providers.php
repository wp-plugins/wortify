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


include_once( dirname(dirname(__FILE__)) . '/include/functions.php' );

class Providers 
{
	var $providers = array();
	
	function init($check) {
		
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
		
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(dirname(__FILE__) . '/'.$key.'/precheck.inc.php')) 
				include_once(dirname(__FILE__) . '/'.$key.'/precheck.inc.php');
			if (file_exists(dirname(__FILE__) . '/'.$key.'/pre.loader.php')) 
				include_once(dirname(__FILE__) . '/'.$key.'/pre.loader.php');
			
		}
		
	}
	
	private function postcheck()
	{
		
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(dirname(__FILE__) . '/'.$key.'/postcheck.inc.php'))
				include_once(dirname(__FILE__) . '/'.$key.'/postcheck.inc.php');
			if (file_exists(dirname(__FILE__) . '/'.$key.'/post.loader.php'))
				include_once(dirname(__FILE__) . '/'.$key.'/post.loader.php');
			
		}
		
	}
	
	private function headerpostcheck()
	{
		

		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(dirname(__FILE__) . '/'.$key.'/headerpostcheck.inc.php'))
				include_once(dirname(__FILE__) . '/'.$key.'/headerpostcheck.inc.php');
			if (file_exists(dirname(__FILE__) . '/'.$key.'/header.post.loader.php'))
				include_once(dirname(__FILE__) . '/'.$key.'/header.post.loader.php');
			
		}
		
	}
	
	private function footerpostcheck()
	{
		

		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(dirname(__FILE__) . '/'.$key.'/footerpostcheck.inc.php'))
				include_once(dirname(__FILE__) . '/'.$key.'/footerpostcheck.inc.php');
			if (file_exists(dirname(__FILE__) . '/'.$key.'/footer.post.loader.php'))
				include_once(dirname(__FILE__) . '/'.$key.'/footer.post.loader.php');
			
			
		}
		
	}
}

?>