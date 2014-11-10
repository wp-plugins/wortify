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
 * This File:	functions.php		
 * Description:	General functions for Wortify
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
if (!function_exists('checkWordLength')){
	function checkWordLength($content) {
		
		if (is_group(user_groups(), $GLOBALS['wortify']['min_words_groups'])==true) {
			wortify_load('textsanitizer');
			$Wortifyts = new WortifyTextSanitizer();
			$data = strip_tags($Wortifyts->displayTarea($content, true, true, true, true, true));
			$parts = explode(' ', $data);
			foreach($parts as $id => $part)
				if (strlen($part)<4)
					unset($parts[$id]);
			if (count($parts)<$GLOBALS['wortify']['min_words']) {
								
								
				$log_handler = wortify_getmodulehandler('log', 'wortify');
				$log = $log_handler->create();
				$log->setVars($ipdata);
				$log->setVar('provider', basename(dirname(__FILE__)));
				$log->setVar('action', 'blocked');
				$log->setVar('extra', _XOR_WORDS . ' :: [' . $_REQUEST[$field] . '] words('.count($parts).')');
				if (isset($GLOBALS['wortifyUser'])) {
					$log->setVar('email', $GLOBALS['wortifyUser']->getVar('email'));
					$log->setVar('uname', $GLOBALS['wortifyUser']->getVar('uname'));
				}
				$lid = $log_handler->insert($log, true);
								
				$module_handler = wortify_gethandler('module');
				$GLOBALS['wortifyModule'] = $module_handler->getByDirname('wortify');
				
				$wortifyOption['template_main'] = 'wortify_words_notice.html';
				include_once WORTIFY_ROOT_PATH.'/header.php';
					
				addmeta_googleanalytics(_XOR_MI_WORTIFY_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
				if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
					addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
				}
				
				$GLOBALS['wortifyTpl']->assign('xortify_pagetitle', _XOR_WORDS_PAGETITLE);
				$GLOBALS['wortifyTpl']->assign('description', _XOR_WORDS_DESCRIPTION);
				$GLOBALS['wortifyTpl']->assign('version', $GLOBALS['wortifyModule']->getVar('version')/100);
				$GLOBALS['wortifyTpl']->assign('platform', WORTIFY_VERSION);
				$GLOBALS['wortifyTpl']->assign('provider', basename(dirname(__FILE__)));
				$GLOBALS['wortifyTpl']->assign('spam', htmlspecialchars($data));
				$GLOBALS['wortifyTpl']->assign('agent', $_SERVER['HTTP_USER_AGENT']);
				$GLOBALS['wortifyTpl']->assign('words', count($parts));
				$GLOBALS['wortifyTpl']->assign('included', $GLOBALS['wortify']['min_words']);
				
				$GLOBALS['wortifyTpl']->assign('xortify_lblocks', false);
				$GLOBALS['wortifyTpl']->assign('xortify_rblocks', false);
				$GLOBALS['wortifyTpl']->assign('xortify_ccblocks', false);
				$GLOBALS['wortifyTpl']->assign('xortify_clblocks', false);
				$GLOBALS['wortifyTpl']->assign('xortify_crblocks', false);
				$GLOBALS['wortifyTpl']->assign('xortify_showlblock', false);
				$GLOBALS['wortifyTpl']->assign('xortify_showrblock', false);
				$GLOBALS['wortifyTpl']->assign('xortify_showcblock', false);
				include_once WORTIFY_ROOT_PATH.'/footer.php';
				exit(0);
			}
		}
	}
}

if (!function_exists('is_group')){
	function is_group($from, $in) {
		foreach($from as $groupid)
			if (in_array($groupid, array_keys(get_groups())))
				return true;
		return false;
	}
}

if (!function_exists('get_groups')){
	function get_groups() {
		$ret = array();
		foreach(get_option('wp_user_roles') as $key => $values)
			$ret[$key] = $value['name'];
		return $ret;
	}
}


if (!function_exists('user_groups')){
	function user_groups() {
		if (get_current_user_id()<>0)
			return get_user_by('id', get_current_user_id())->roles;
		return array('guest');
	}
}

if (!function_exists('unlinkOldCachefiles')){
	include_once WORTIFY_VAR_PATH.'/lib/cache/wortifyCache.php';
	include_once WORTIFY_VAR_PATH.'/lib/wortifyLists.php';
	
	function unlinkOldCachefiles($prefix, $howold=0) {
		$files = WortifyLists::getFileListAsArray(WORTIFY_ROOT_PATH . '/wp-content/cache/wortify/cache', $prefix);
		foreach($files as $file) {
			if (file_exists(WORTIFY_ROOT_PATH . '/wp-content/cache/wortify/cache/' . $file)) {
				$contents = file_get_contents(WORTIFY_ROOT_PATH . '/wp-content/cache/wortify/cache/' . $file);
				$content = explode('/n', $contents);
				$lock = (int)$content[0];
				if ($lock<time()-$howold) {
					unlink(WORTIFY_ROOT_PATH . '/wp-content/cache/wortify/cache/' . $file);
				}
			}
		}
	
	}
}

if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON')) include_once WORTIFY_VAR_PATH . ('/lib/xortify/include/JSON.php');
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON')) include_once WORTIFY_VAR_PATH . ('/lib/xortify/include/JSON.php');
		$json = new Services_JSON();
		return $json->decode($data);
	}
}

if (!function_exists("wortify_getIP")) {
	function wortify_getIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
			if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
				$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
			} else
				if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
					$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
				} else
					if (!empty($_SERVER['HTTP_FORWARDED'])) {
						$proxy_ip = $_SERVER['HTTP_FORWARDED'];
					} else
						if (!empty($_SERVER['HTTP_VIA'])) {
							$proxy_ip = $_SERVER['HTTP_VIA'];
						} else
							if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
								$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
							} else
								if (!empty($_SERVER['HTTP_COMING_FROM'])) {
									$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
								}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
	
		return $the_IP;
	}
}

if (!function_exists("wortify_getIPData")) {
	function wortify_getIPData($ip=false){
		$ret = array();
		if (get_current_user_id()<>0) {
			$rt = get_user_by('id', get_current_user_id());
			$ret['uid'] = get_current_user_id();
			$ret['uname'] = $rt->user_login;
			$ret['email'] = $rt->user_email;
		} else {
			$ret['uid'] = 0;
			$ret['uname'] = strtolower($_REQUEST['login_name']);
			$ret['email'] = strtolower($_REQUEST['login_name']);
		}
		$ret['agent'] = $_SERVER['HTTP_USER_AGENT'];
		if (!$ip) {
			if (wortify_getIP(true)!=$_SERVER["REMOTE_ADDR"]){ 
				$ip = (string)wortify_getIP(); 
				$ret['is_proxied'] = true;
				$proxy_ip = $_SERVER["REMOTE_ADDR"]; 
				$ret['network-addy'] = gethostbyaddr($ip); 
				$ret['long'] = ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = $ip;
					$ret['proxy-ip6'] = $proxy_ip;
				} else {
					$ret['ip4'] = $ip;
					$ret['proxy-ip4'] = $proxy_ip;
				}
			}else{ 
				$ret['is_proxied'] = false;
				$ip = (string)wortify_getIP(); 
				$ret['network-addy'] = gethostbyaddr($ip); 
				$ret['long'] = ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = $ip;
				} else {
					$ret['ip4'] = $ip;
				}
			} 
		} else {
			$ret['is_proxied'] = false;
			$ret['network-addy'] = gethostbyaddr($ip); 
			$ret['long'] = ip2long($ip);
			if (is_ipv6($ip)) {
				$ret['ip6'] = $ip;
			} else {
				$ret['ip4'] = $ip;
			}
		}
		$ret['made'] = time();				
		return $ret;
	}
}
if (!function_exists("is_ipv6")) {
	function is_ipv6($ip = "") 
	{ 
		if ($ip == "") 
			return false;
			
		if (substr_count($ip,":") > 0){ 
			return true; 
		} else { 
			return false; 
		} 
	} 
}

if (!function_exists("wortify_apimethod")) {
	function wortify_apimethod($asarray=false) {
		if ($asarray==false) {
			foreach (get_loaded_extensions() as $ext){
				if ($ext=="curl")
					return "rest_curlserialised";
			}
			if (function_exists('json_decode'))
				return 'rest_json';
			elseif (function_exists('xml_parser_create'))
				return "rest_wetxml";
			else
				return "rest_wgetserialised";
		} else {
			$ret = array(_XOR_MI_PROTOCOL_MINIMUMCLOUD => 'minimumcloud');
			foreach (get_loaded_extensions() as $ext){
				if ($ext=="curl") {
					if (function_exists('json_decode')) {
						$ret[_XOR_MI_PROTOCOL_CURL] = 'rest_curl';
					}
					$ret[_XOR_MI_PROTOCOL_CURLSERIAL] = 'rest_curlserialised';
				}
				if (function_exists('xml_parser_create')) {
					if (in_array('curl', get_loaded_extensions())) {
						$ret[_XOR_MI_PROTOCOL_CURLXML] = 'rest_curlxml';
					}
					$xmlparser=true;
				}
 			}
 			if ($xmlparser=true) {
 				$ret[_XOR_MI_PROTOCOL_WGETXML] = 'rest_wgetxml';
 			}
 			if (function_exists('json_decode')) {
				$ret[_XOR_MI_PROTOCOL_JSON] = 'rest_json';
 			}
			return $ret;
		}
	}
}

if (!function_exists("wortify_obj2array")) {
	function wortify_obj2array($objects) {
		$ret = array();
		foreach((array)$objects as $key => $value) {
			if (is_a($value, 'stdClass')) {
				$ret[$key] = wortify_obj2array($value);
			} elseif (is_array($value)) {
				$ret[$key] = wortify_obj2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}

if (!function_exists("wortify_elekey2numeric")) {
	function wortify_elekey2numeric($array, $name) {
		$ret = array();
		foreach($array as $key => $value) {
			if (is_array($value)) {
				$key = str_replace($name.'_', '', $key);
				if (is_numeric($key))
					$key = (integer)$key;
				$ret[$key] = wortify_elekey2numeric($value, $name);
			} else {
				$key = str_replace($name.'_', '', $key);
				if (is_numeric($key))
					$key = (integer)$key;
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}

if (!function_exists("wortify_xml2array")) {
	function wortify_xml2array($contents, $get_attributes=1, $priority = 'tag') { 
	    if(!$contents) return array(); 
	
	    if(!function_exists('xml_parser_create')) { 
	        return array(); 
	    } 
	
	    //Get the XML parser of PHP - PHP must have this module for the parser to work
	     $parser = xml_parser_create(''); 
	    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
	     xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
	    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
	    xml_parse_into_struct($parser, trim($contents), $xml_values); 
	    xml_parser_free($parser); 
	
	    if(!$xml_values) return;//Hmm... 
	
	    //Initializations 
	    $xml_array = array(); 
	    $parents = array(); 
	    $opened_tags = array(); 
	    $arr = array(); 
	
	    $current = &$xml_array; //Refference 
	
	    //Go through the tags. 
	    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
	     foreach($xml_values as $data) { 
	        unset($attributes,$value);//Remove existing values, or there will be trouble
	 
	        //This command will extract these variables into the foreach scope 
	        // tag(string), type(string), level(int), attributes(array). 
	        extract($data);//We could use the array by itself, but this cooler. 
	
	        $result = array(); 
	        $attributes_data = array(); 
	         
	        if(isset($value)) { 
	            if($priority == 'tag') $result = $value; 
	            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
	         } 
	
	        //Set the attributes too. 
	        if(isset($attributes) and $get_attributes) { 
	            foreach($attributes as $attr => $val) { 
	                if($priority == 'tag') $attributes_data[$attr] = $val; 
	                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
	             } 
	        } 
	
	        //See tag status and do the needed. 
	        if($type == "open") {//The starting of the tag '<tag>' 
	            $parent[$level-1] = &$current; 
	            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
	                 $current[$tag] = $result; 
	                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
	                 $repeated_tag_index[$tag.'_'.$level] = 1; 
	
	                $current = &$current[$tag]; 
	
	            } else { //There was another element with the same tag name 
	
	                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
	                     $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
	                     $repeated_tag_index[$tag.'_'.$level]++; 
	                } else {//This section will make the value an array if multiple tags with the same name appear together
	                     $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
	                     $repeated_tag_index[$tag.'_'.$level] = 2; 
	                     
	                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
	                         $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
	                        unset($current[$tag.'_attr']); 
	                    } 
	
	                } 
	                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1; 
	                $current = &$current[$tag][$last_item_index]; 
	            } 
	
	        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
	            //See if the key is already taken. 
	            if(!isset($current[$tag])) { //New Key 
	                $current[$tag] = $result; 
	                $repeated_tag_index[$tag.'_'.$level] = 1; 
	                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;
	 
	            } else { //If taken, put all things inside a list(array) 
	                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
	 
	                    // ...push the new element into that array. 
	                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
	                      
	                    if($priority == 'tag' and $get_attributes and $attributes_data) {
	                         $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
	                     } 
	                    $repeated_tag_index[$tag.'_'.$level]++; 
	
	                } else { //If it is not an array... 
	                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
	                     $repeated_tag_index[$tag.'_'.$level] = 1; 
	                    if($priority == 'tag' and $get_attributes) { 
	                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
	                              
	                            $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
	                            unset($current[$tag.'_attr']); 
	                        } 
	                         
	                        if($attributes_data) { 
	                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
	                         } 
	                    } 
	                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
	                 } 
	            } 
	
	        } elseif($type == 'close') { //End of tag '</tag>' 
	            $current = &$parent[$level-1]; 
	        } 
	    } 
	     
	    return($xml_array); 
	}
}  

if (!function_exists("wortify_toXml")) { 
	function wortify_toXml($array, $name, $standalone=false, $beginning=true, $nested) {
		
		if ($beginning) {
			if ($standalone)
				header("content-type:text/xml;charset="._CHARSET);
			$output .= '<'.'?'.'xml version="1.0" encoding="'._CHARSET.'"'.'?'.'>' . "\n";    
			$output .= '<' . $name . '>' . "\n";
			$nested = 0;
		}    
		
		if (is_array($array)) {
			foreach ($array as $key=>$value) {
				$nested++;	
				if (is_array($value)) {
					$output .= str_repeat("\t", (1 * $nested)) . '<' . (is_string($key) ? $key : $name.'_' . $key) . '>' . "\n";
					$nested++;				
					$output .= wortify_toXml($value, $name, false, false, $nested);
					$nested--;
					$output .= str_repeat("\t", (1 * $nested)) . '</' . (is_string($key) ? $key : $name.'_' . $key) . '>' . "\n";
				} else {
					if (strlen($value)>0) {
					$nested++;				
						$output .= str_repeat("\t", (1 * $nested)) . '  <' . (is_string($key) ? $key : $name.'_' . $key) . '>' . trim($value) . '</' . (is_string($key) ? $key : $name.'_' . $key) . '>' . "\n";
						$nested--;
					}
				}
				$nested--;
			}
		} elseif (strlen($array)>0) {
			$nested++; 
			$output .= str_repeat("\t", (1 * $nested)) . trim($array) ."\n";
			$nested--;
		}
			
		if ($beginning) {
			$output .= '</' . $name . '>';
			return $output;
		} else {
			return $output;
		}
	} 
}

if (!function_exists('addmeta_googleanalytics')) {
	function addmeta_googleanalytics($accountID, $hostname = 'none') {
		return false;
	}
}
?>