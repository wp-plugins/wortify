<?php

function protector_prepare()
{
	error_reporting(E_ERROR);
	
	// Protector class
	require_once dirname(dirname(__FILE__)).'/class/protector.php' ;
	
	// Protector object
	$protector =& Protector::getInstance() ;
	$conf = $protector->getConf() ;
	
	// bandwidth limitation
	if( WortifyConfig::get('wortify_bwlimit_count') >= 10 ) {
		$bwexpire = $protector->get_bwlimit() ;
		if( $bwexpire > time() ) {
			header( 'HTTP/1.0 503 Service unavailable' ) ;
			$protector->call_filter( 'precommon_bwlimit' , 'This website is very busy now. Please try later.' ) ;
		}
	}
	
	// bad_ips
	$bad_ips = $protector->get_bad_ips( true ) ;
	$bad_ip_match = $protector->ip_match( $bad_ips ) ;
	if( $bad_ip_match ) {
		$protector->call_filter( 'precommon_badip' , 'You are registered as BAD_IP by Protector.' ) ;
	}
	
	// global enabled or disabled
	$global_disabled = WortifyConfig::get('wortify_global_disabled');
	if( ! empty( $global_disabled ) ) return true ;
	
	// reliable ips
	$reliable_ips = unserialize( WortifyConfig::get('wortify_reliable_ips') ) ;
	if( ! is_array( $reliable_ips ) ) {
		// for the environment of (buggy core version && magic_quotes_gpc)
		$reliable_ips = unserialize( stripslashes( WortifyConfig::get('wortify_reliable_ips') ) ) ;
		if( ! is_array( $reliable_ips ) ) $reliable_ips = array() ;
	}
	$is_reliable = false ;
	foreach( $reliable_ips as $reliable_ip ) {
		if( ! empty( $reliable_ip ) && preg_match( '/'.$reliable_ip.'/' , $_SERVER['REMOTE_ADDR'] ) ) {
			$is_reliable = true ;
		}
	}
	
	// "DB Layer Trapper"
	$force_override = strstr( $_SERVER['REQUEST_URI'] , 'protector/admin/index.php?page=advisory' ) ? true : false ;
	// $force_override = true ;
	$dblayertrap = WortifyConfig::get('wortify_enable_dblayertrap');
	if( $force_override || ! empty( $dblayertrap ) ) {
		@define('PROTECTOR_ENABLED_ANTI_SQL_INJECTION' , 1 ) ;
		$protector->dblayertrap_init( $force_override ) ;
	}
	
	// "Big Umbrella" subset version
	$bigumbrella = WortifyConfig::get('wortify_enable_bigumbrella');
	if( ! empty( $bigumbrella ) ) {
		@define('PROTECTOR_ENABLED_ANTI_XSS' , 1 ) ;
		$protector->bigumbrella_init() ;
	}
	
	// force intval variables whose name is *id
	$forceintval = WortifyConfig::get('wortify_id_forceintval');
	if( ! empty( $forceintval ) ) $protector->intval_allrequestsendid() ;
	
	// eliminate '..' from requests looks like file specifications
	$dotdot = WortifyConfig::get('wortify_file_dotdot');
	if( ! $is_reliable && ! empty( $dotdot ) ) $protector->eliminate_dotdot() ;
	
	// Check uploaded files
	$badext = WortifyConfig::get('wortify_die_badext');
	if( ! $is_reliable && ! empty( $_FILES ) && ! empty( $badext ) && ! defined( 'PROTECTOR_SKIP_FILESCHECKER' ) && ! $protector->check_uploaded_files() ) {
		$protector->output_log( $protector->last_error_type ) ;
		$protector->purge() ;
	}
	
	// Variables contamination
	if( ! $protector->check_contami_systemglobals() ) {
		if( WortifyConfig::get('wortify_contami_action') & 4 ) {
			if( WortifyConfig::get('wortify_contami_action') & 8 ) {
				$protector->_should_be_banned = true ;
			} else {
				$protector->_should_be_banned_time0 = true ;
			}
			$_GET = $_POST = array() ;
		}

		$protector->output_log( $protector->last_error_type ) ;
		if( WortifyConfig::get('wortify_contami_action') & 2 ) $protector->purge() ;
	}
	
	// prepare for DoS
	//if( ! $protector->check_dos_attack_prepare() ) {
	//	$protector->output_log( $protector->last_error_type , 0 , true ) ;
	//}
	$disabled = WortifyConfig::get('wortify_disable_features');
	if( ! empty( $disabled ) ) $protector->disable_features() ;
	
}

?>