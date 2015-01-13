<?php
/*
Plugin Name: Wortify Sentry & Security
Plugin URI: https://xortify.com/
Description: Wortify Sentry & Security - Anti-virus, Firewall and real-time WordPress sentry & security Network (Open Subscription ~ Currently at no charge will remain that we hope)
Author: Simon Antony Roberts aka. Leshy Cipherhouse (MyNamesNot / Wishcraft / CEJ)
Version: 4.19.2
Author URI: http://blog.simonaroberts.com/
*/
if(defined('WP_INSTALLING') && WP_INSTALLING){
	return;
}
define('WORTIFY_VERSION', '4.0.6');
if(! defined('WORTIFY_VERSIONONLY_MODE')){
	if((int) ini_get('memory_limit') < 128){
		if(strpos(ini_get('disable_functions'), 'ini_set') === false){
			ini_set('memory_limit', '128M'); //Some hosts have ini set at as little as 32 megs. 64 is the min sane amount of memory.
		}
	}
	include_once('lib/wortifyConstants.php');
	include_once('lib/wortifyClass.php');
	register_activation_hook(WP_PLUGIN_DIR . '/xortify/wortify.php', 'wortify::installPlugin');
	register_deactivation_hook(WP_PLUGIN_DIR . '/xortify/wortify.php', 'wortify::uninstallPlugin');
	wortify::install_actions();
}
?>
