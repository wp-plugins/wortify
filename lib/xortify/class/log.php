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
* Download:		http://code.google.com/p/chronolabs
* This File:	log.php
* Description:	Log Object and Handler Class for Wortify
* Date:			09/09/2012 19:34 AEST
* License:		GNU3
*
*/
error_reporting(E_ERROR);

if (!defined('WORTIFY_VAR_PATH')) {
	exit();
}
/**
 * Class for Blue Room Wortify Log
 * @author Simon Roberts <simon@wortify.org>
 * @copyright copyright (c) 2009-2003 WORTIFY.org
 * @package kernel
 */
class XortifyLog extends WortifyObject
{

    function XortifyLog($id = null)
    {
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uname', XOBJ_DTYPE_TXTBOX, false, false, 64);
		$this->initVar('email', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('ip4', XOBJ_DTYPE_TXTBOX, false, false, 15);
		$this->initVar('ip6', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('proxy-ip4', XOBJ_DTYPE_TXTBOX, false, false, 15);
		$this->initVar('proxy-ip6', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('network-addy', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('provider', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('agent', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('extra', XOBJ_DTYPE_OTHER, false, false);
		$this->initVar('date', XOBJ_DTYPE_INT, null, false);
		$this->initVar('action', XOBJ_DTYPE_ENUM, 'monitored', false, false, false, array('banned', 'blocked', 'monitored'));
    }

    function toArray() {
    	$ret = parent::toArray();
    	$ret['date_datetime'] = date(_DATESTRING, $this->getVar('date'));
    	$ret['action'] = ucfirst($this->getVar('action'));
    	foreach($ret as $key => $value)
    		$ret[str_replace('-', '_', $key)] = $value;
    	return $ret;
    }
    
    function runPrePlugin($default = true) {
		
		include_once(dirname(dirname(__FILE__)) . ('/plugin/'.$this->getVar('provider').'.php'));
		
		switch ($this->getVar('action')) {
			case 'banned':
			case 'blocked':
			case 'monitored':
				$func = ucfirst($this->getVar('action')).'PreHook';
				break;
			default:
				return $default;
				break;
		}
		
		if (function_exists($func))  {
			return $func($default, $this);
		}
		return $default;
	}
    
	function runPostPlugin($lid) {
		
		include_once(dirname(dirname(__FILE__)) . ('/plugin/'.$this->getVar('provider').'.php'));
		
		switch ($this->getVar('action')) {
			case 'banned':
			case 'blocked':
			case 'monitored':
				$func = ucfirst($this->getVar('action')).'PostHook';
				break;
			default:
				return $lid;
				break;
		}
		
		if (function_exists($func))  {
			return $func($this, $lid);
		}
		return $lid;
	}
}


/**
* WORTIFY Wortify Log handler class.
* This class is responsible for providing data access mechanisms to the data source
* of WORTIFY user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class XortifyLogHandler extends WortifyPersistableObjectHandler
{
    function __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'wortify_log', 'XortifyLog', "lid", "network-addy");
    }
	
    function insert($object, $force = true) {
		$module_handler = wortify_gethandler('module');
		$config_handler = wortify_gethandler('config');
		if (!isset($GLOBALS['wortify']['module']))
			$GLOBALS['wortify']['module'] = $module_handler->getByDirname('wortify');
		if (!isset($GLOBALS['wortify']['moduleConfig']))
			$GLOBALS['wortify']['moduleConfig'] = $config_handler->getConfigList($GLOBALS['wortify']['module']->getVar('mid'));
		
		$criteria = new Criteria('`date`', time()-WortifyConfig::get('xortify_logdrops'), '<=');
		$this->deleteAll($criteria, true);
		
    	if ($object->isNew()) {
    		$object->setVar('date', time());
    	}
		$run_plugin_action=false;
    	if ($object->vars['action']['changed']==true) {	
			$run_plugin_action=true;
		}
    	if ($run_plugin_action){
    		if ($object->runPrePlugin(WortifyConfig::get('xortify_save_'.$object->getVar('action')))==true)
    			$lid = parent::insert($object, $force);
    		else 
    			return false;
    	} else 	
    		$lid = parent::insert($object, $force);		
    	if ($run_plugin_action)
    		return $object->runPostPlugin($lid);
    	else 	
    		return $lid;
    }
    
    function getCountByField($field, $value) {
    	$criteria = new Criteria('`'.$field.'`', $value);
    	$count = $this->getCount($criteria);
    	return ($count>0?$count:'0');
    }
}

?>