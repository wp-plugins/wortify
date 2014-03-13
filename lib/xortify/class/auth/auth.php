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
 * This File:	auth.php		
 * Description:	Wortify Authentication Library modeled from Wortify Auth.
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'authfactory.php';


class WortifyAuth {

	var	$_dao;

	var	$_errors;
	/**
	 * Authentication Service constructor
	 */
	function WortifyAuth (&$dao){
		$this->_dao = $dao;
	}

	/**
	 * @abstract need to be write in the dervied class
	 */	
	function authenticate() {
		$authenticated = false;
				
		return $authenticated;
	}		
	
	/**
	 * add an error 
	 * 
	 * @param string $value error to add
	 * @access public
	 */
	function setErrors($err_no, $err_str)
	{
		$this->_errors[$err_no] = trim($err_str);
	}

	/**
	 * return the errors for this object as an array
	 * 
	 * @return array an array of errors
	 * @access public
	 */
	function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * return the errors for this object as html
	 * 
	 * @return string html listing the errors
	 * @access public
	 */
	function getHtmlErrors()
	{
		global $wortifyConfig;
		$ret = '<br>';
		if ( $wortifyConfig['debug_mode'] == 1 || $wortifyConfig['debug_mode'] == 2 ) 
		{	       
			if (!empty($this->_errors)) {
				foreach ($this->_errors as $errno => $errstr) {            	
					$ret .=  $errstr . '<br/>';
				}
			} else {
				$ret .= _NONE.'<br />';
			}        
			$ret .= sprintf(_AUTH_MSG_AUTH_METHOD, $this->auth_method);
		}
		else {
			$ret .= _US_INCORRECTLOGIN;
		}
		return $ret;
	}	

	/**
	 * checks for variables include in siteinfo package in the auth library
	 * 
	 * @param array $siteinfo 
	 *
	 * @return array $siteinfo
	 * @access public
	 */
	function check_siteinfo($siteinfo){

		global $wortifyConfig;
		if (!isset($siteinfo)||empty($siteinfo)||!is_array($siteinfo)){
			$siteinfo = array();
			$siteinfo['sitename'] = $wortifyConfig['sitename'];
			$siteinfo['adminmail'] = $wortifyConfig['adminmail'];
			$siteinfo['systemkey'] = WORTIFY_LICENSE_KEY;
			$siteinfo['wortify_url'] = WORTIFY_URL;
		}
		
		if (!isset($siteinfo['sitename'])||empty($siteinfo['sitename']))
			$siteinfo['sitename'] = $wortifyConfig['sitename'];

		if (!isset($siteinfo['adminmail'])||empty($siteinfo['adminmail']))
			$siteinfo['adminmail'] = $wortifyConfig['adminmail'];
		
		if (!isset($siteinfo['wortify_url'])||empty($siteinfo['wortify_url']))
			$siteinfo['wortify_url'] = WORTIFY_URL;

		if (!isset($siteinfo['systemkey'])||empty($siteinfo['systemkey']))
			$siteinfo['systemkey'] = $wortifyConfig['systemkey'];
		
		return $siteinfo;	
	}
}


?>
