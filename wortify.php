<?php
/*
Plugin Name: Wortify Security
Plugin URI: http://www.xortify.com/
Description: Wortify Security - Anti-virus, Firewall and real-time WordPress security Network
Author: Mark Maunder
Version: 4.0.3
Author URI: http://www.xortify.com/
*/
if(defined('WP_INSTALLING') && WP_INSTALLING){
	return;
}
define('WORTIFY_VERSION', '4.0.6');
if(! defined('WORTIFY_VERSIONONLY_MODE')){
	if((int) @ini_get('memory_limit') < 128){
		if(strpos(ini_get('disable_functions'), 'ini_set') === false){
			@ini_set('memory_limit', '128M'); //Some hosts have ini set at as little as 32 megs. 64 is the min sane amount of memory.
		}
	}
	require_once('lib/wortifyConstants.php');
	require_once('lib/wortifyClass.php');
	register_activation_hook(WP_PLUGIN_DIR . '/xortify/wortify.php', 'wortify::installPlugin');
	register_deactivation_hook(WP_PLUGIN_DIR . '/xortify/wortify.php', 'wortify::uninstallPlugin');
	wortify::install_actions();
}
?>
