<?php
/**
* wortify_getmodulehandler()
*
* @param mixed $name
* @param mixed $module_dir
* @param mixed $optional
* @return
*/
function &wortify_getmodulehandler($name = null, $module_dir = null, $optional = false)
{
	static $handlers = array();
	// if $module_dir is not specified
	$module_dir = trim($module_dir);
	$name = (!isset($name)) ? $module_dir : trim($name);
	if (!isset($handlers[$module_dir][$name])) {
		if (file_exists($hnd_file = dirname(__FILE__) . "/{$module_dir}/class/{$name}.php")) {
			include_once $hnd_file;
		}
		$class = ucfirst(strtolower($module_dir)) . ucfirst($name) . 'Handler';
		if (class_exists($class)) {
			$handlers[$module_dir][$name] = new $class($GLOBALS['wortifyDB']);
		}
	
	}
	if (!isset($handlers[$module_dir][$name])) {
		trigger_error('Handler does not exist<br />Module: ' . $module_dir . '<br />Name: ' . $name,
		$optional ? E_USER_WARNING : E_USER_ERROR);
	}
	if (isset($handlers[$module_dir][$name])) {
		return $handlers[$module_dir][$name];
	}
	$inst = false;
	return $inst;
}

?>
