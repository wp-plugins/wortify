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
 * This File:	authfactory.php		
 * Description:	Auth Factory Library with Packages for signup and API
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

if (!class_exists('WortifyAuthFactory')) {
	class WortifyAuthFactory
	{
	
		/**
		 * Get a reference to the only instance of authentication class
		 * 
		 * if the class has not been instantiated yet, this will also take 
		 * care of that
		 * 
		 * @static
		 * @return      object  Reference to the only instance of authentication class
		 */
		function &getAuthConnection($uname, $xortify_auth_method = 'soap')
		{
					
			static $auth_instance;		
			if (!isset($auth_instance)) {
				include_once 'auth.php';
				// Verify if uname allow to bypass LDAP auth 
				$file = dirname(__FILE__) . DIRECTORY_SEPARATOR .'auth_' . $xortify_auth_method . '.php';
				include_once $file;
				$class = 'WortifyAuth' . ucfirst($xortify_auth_method);
				switch ($xortify_auth_method) {
					case 'soap';
						$dao = null;
						break;
	
				}
				$auth_instance = new $class($GLOBALS['wortifyDB'], _WORTIFY_URL);
			}
			return $auth_instance;
		}
	
	}
}
?>
