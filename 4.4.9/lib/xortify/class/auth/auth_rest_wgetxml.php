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
 * This File:	auth_wgetxml.php		
 * Description:	Auth Library with wGET XML Packages for signup and API
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
 
if (!defined('XORTIFY_REST_API'))
	define('XORTIFY_REST_API', WortifyConfig::get('xortify_urirest', WORTIFY_API_URL_XORTIFY).'%s/xml/?%s');
include_once WORTIFY_ROOT_PATH . '/lib/xortify/class/auth/auth_rest_wgetxml_provisionning.php';
include_once WORTIFY_ROOT_PATH . '/lib/xortify/include/functions.php';

class WortifyAuthRest_Wgetxml extends WortifyAuth {
	
	var $xml_wortify_username = '';
	var $xml_wortify_password = '';
	var $_dao;
	/**
	 * Authentication Service constructor
	 */
	function WortifyAuthRest_Wgetxml (&$dao) {
	
	}


	/**
	 *  Authenticate  user again json directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
	function authenticate($uname, $pwd = null) {
		$authenticated = false;
		$rnd = rand(-100000, 100000000);		
		$data = file_get_contents(sprintf(XORTIFY_REST_API, 'xortify_authentication', http_build_query(array("username"=> $this->xml_wortify_username, "password"=> $this->xml_wortify_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)))));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xortify_authentication');
		return $result['xortify_authentication']["RESULT"];		
	}
	
				  
	/**
	 *  validate a user via json
	 *
	 * @param string $uname
	 * @param string $email
	 * @param string $pass
	 * @param string $vpass
	 *
	 * @return string
	 */		
	function validate($uname, $email, $pass, $vpass){
		$rnd = rand(-100000, 100000000);	
		$data = file_get_contents(sprintf(XORTIFY_REST_API,'xortify_user_validate',http_build_query(array("username"=> $this->xml_wortify_username, "password"=> $this->xml_wortify_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)), 'xortify_user_validate')));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xortify_user_validate');
		if ($result['xortify_user_validate']['ERRNUM']==1){
			return $result['xortify_user_validate']["RESULT"];
		} else {
			return false;
		}
	
	}

    function reduce_string($str)
    {
        $str = preg_replace(array(

                // eliminate single line comments in '/ ...' form
                '#^\s*//(.+)$#m',

                // eliminate multi-line comments in '/* ... */' form, at start of string
                '#^\s*/\*(.+)\*/#Us',

                // eliminate multi-line comments in '/* ... */' form, at end of string
                '#/\*(.+)\*/\s*$#Us'

            ), '', $str);

        // eliminate extraneous space
        return trim($str);
    }
    
	/**
	 *  get the wortify site disclaimer via json
	 *
	 * @return string
	 */			
	function network_disclaimer(){

		$data = file_get_contents(sprintf(XORTIFY_REST_API,'xortify_network_disclaimer',http_build_query(array("username"=> $this->xml_wortify_username, "password"=> $this->xml_wortify_password))));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xortify_network_disclaimer');

		if ($result['xortify_network_disclaimer']['ERRNUM']==1){
			return $result['xortify_network_disclaimer']["RESULT"];
		} else {
			return false;
		}

	}
	
	/**
	 *  create a user
	 *
	 * @param bool $user_viewemail
	 * @param string $uname
	 * @param string $email
	 * @param string $url
	 * @param string $actkey
	 * @param string $pass
	 * @param integer $timezone_offset
	 * @param bool $user_mailok		 
	 * @param array $siteinfo
	 *
	 * @return array
	 */	
	function create_user($user_viewemail, $uname, $email, $url, $actkey, 
						 $pass, $timezone_offset, $user_mailok, $siteinfo){
		$siteinfo = $this->check_siteinfo($siteinfo);
		$rnd = rand(-100000, 100000000);
		$data = file_get_contents(sprintf(XORTIFY_REST_API,'xortify_create_user',http_build_query(array("username"=> $this->xml_wortify_username, "password"=> $this->xml_wortify_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd), "siteinfo" => $siteinfo))));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xortify_create_user');
		if ($result['xortify_create_user']['ERRNUM']==1){
			return $result['xortify_create_user']["RESULT"];
		} else {
			return false;
		}
	}
		
}
// end class


?>
