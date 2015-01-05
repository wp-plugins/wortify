<?php

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyFormLoader.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyUtils.php';

class wortifyConfig {
	private static $table = false;
	private static $cache = array();
	private static $DB = false;
	private static $tmpFileHeader = "<?php\n/* Wortify temporary file security header */\necho \"Nothing to see here!\\n\"; exit(0);\n?>";
	private static $tmpDirCache = false;
	public static $configs = 
		array( 
			"protector" => array(
				"wortify_global_disabled" => array(
											'name'			=> 'wortify_global_disabled' ,
											'title'			=> 'WORTIFY_GLOBAL_DISBL' ,
											'description'	=> 'WORTIFY_GLOBAL_DISBLDSC' ,
											'formtype'		=> 'yesno' ,
											'valuetype'		=> 'int' ,
											'default'		=> "0" ,
											'options'		=> array()
										),
				"wortify_log_level" => array(
										'name'			=> 'wortify_log_level' ,
										'title'			=> 'WORTIFY_LOG_LEVEL' ,
										'description'	=> '' ,
										'formtype'		=> 'select' ,
										'valuetype'		=> 'int' ,
										'default'		=>  255 ,
										'options'		=> array( 'WORTIFY_LOGLEVEL0' => 0 , 'WORTIFY_LOGLEVEL15' => 15 , 'WORTIFY_LOGLEVEL63' => 63 , 'WORTIFY_LOGLEVEL255' => 255 )
									),
				"wortify_banip_time0" => array(
										'name'			=> 'wortify_banip_time0' ,
										'title'			=> 'WORTIFY_BANIP_TIME0' ,
										'description'	=> '' ,
										'formtype'		=> 'text' ,
										'valuetype'		=> 'int' ,
										'default'		=> 86400 ,
										'options'		=> array()
									),
				"wortify_reliable_ips" => array(
										'name'			=> 'wortify_reliable_ips' ,
										'title'			=> 'WORTIFY_RELIABLE_IPS' ,
										'description'	=> 'WORTIFY_RELIABLE_IPSDSC' ,
										'formtype'		=> 'textarea' ,
										'valuetype'		=> 'array' ,
										'default'		=> "^192.168.|127.0.0.1" ,
										'options'		=> array()
									),
				"wortify_session_fixed_topbit" => array(
											'name'			=> 'wortify_session_fixed_topbit' ,
											'title'			=> 'WORTIFY_HIJACK_TOPBIT' ,
											'description'	=> 'WORTIFY_HIJACK_TOPBITDSC' ,
											'formtype'		=> 'text' ,
											'valuetype'		=> 'int' ,
											'default'		=> 24 ,
											'options'		=> array()
										),
				"wortify_groups_denyipmove" => array(
											'name'			=> 'wortify_groups_denyipmove' ,
											'title'			=> 'WORTIFY_HIJACK_DENYGP' ,
											'description'	=> 'WORTIFY_HIJACK_DENYGPDSC' ,
											'formtype'		=> 'group_multi' ,
											'valuetype'		=> 'array' ,
											'default'		=> array(1) ,
											'options'		=> array()
										),
				"wortify_san_nullbyte" => array(
											'name'			=> 'wortify_san_nullbyte' ,
											'title'			=> 'WORTIFY_SAN_NULLBYTE' ,
											'description'	=> 'WORTIFY_SAN_NULLBYTEDSC' ,
											'formtype'		=> 'yesno' ,
											'valuetype'		=> 'int' ,
											'default'		=> "1" ,
											'options'		=> array()
										),
				"wortify_die_badext" => array(
												'name'			=> 'wortify_die_badext' ,
												'title'			=> 'WORTIFY_DIE_BADEXT' ,
												'description'	=> 'WORTIFY_DIE_BADEXTDSC' ,
												'formtype'		=> 'yesno' ,
												'valuetype'		=> 'int' ,
												'default'		=> "1" ,
												'options'		=> array()
											),
				"wortify_contami_action" => array(
											'name'			=> 'wortify_contami_action' ,
											'title'			=> 'WORTIFY_CONTAMI_ACTION' ,
											'description'	=> 'WORTIFY_CONTAMI_ACTIONDS' ,
											'formtype'		=> 'select' ,
											'valuetype'		=> 'int' ,
											'default'		=> 3 ,
											'options'		=> array( 'WORTIFY_OPT_NONE' => 0 , 'WORTIFY_OPT_EXIT' => 3 , 'WORTIFY_OPT_BIPTIME0' => 7 , 'WORTIFY_OPT_BIP' => 15 )
										),
				"wortify_isocom_action" => array(
												'name'			=> 'wortify_isocom_action' ,
												'title'			=> 'WORTIFY_ISOCOM_ACTION' ,
												'description'	=> 'WORTIFY_ISOCOM_ACTIONDSC' ,
												'formtype'		=> 'select' ,
												'valuetype'		=> 'int' ,
												'default'		=> 0 ,
												'options'		=> array( 'WORTIFY_OPT_NONE' => 0 , 'WORTIFY_OPT_SAN' => 1 , 'WORTIFY_OPT_EXIT' => 3 , 'WORTIFY_OPT_BIPTIME0' => 7 , 'WORTIFY_OPT_BIP' => 15 )
											),
				"wortify_id_forceintoption_value" => array(
												'name'			=> 'wortify_id_forceintoption_value' ,
												'title'			=> 'WORTIFY_ID_INTVAL' ,
												'description'	=> 'WORTIFY_ID_INTVALDSC' ,
												'formtype'		=> 'yesno' ,
												'valuetype'		=> 'int' ,
												'default'		=> "0" ,
												'options'		=> array()
											),
				"wortify_file_dotdot" => array(
											'name'			=> 'wortify_file_dotdot' ,
											'title'			=> 'WORTIFY_FILE_DOTDOT' ,
											'description'	=> 'WORTIFY_FILE_DOTDOTDSC' ,
											'formtype'		=> 'yesno' ,
											'valuetype'		=> 'int' ,
											'default'		=> "1" ,
											'options'		=> array()
										) ,
				"wortify_bf_count" => array(
											'name'			=> 'wortify_bf_count' ,
											'title'			=> 'WORTIFY_BF_COUNT' ,
											'description'	=> 'WORTIFY_BF_COUNTDSC' ,
											'formtype'		=> 'text' ,
											'valuetype'		=> 'int' ,
											'default'		=> "10" ,
											'options'		=> array()
										),
				"wortify_bwlimit_count" => array(
											'name'			=> 'wortify_bwlimit_count' ,
											'title'			=> 'WORTIFY_BWLIMIT_COUNT' ,
											'description'	=> 'WORTIFY_BWLIMIT_COUNTDSC' ,
											'formtype'		=> 'text' ,
											'valuetype'		=> 'int' ,
											'default'		=> 0 ,
											'options'		=> array()
										) ,
				"wortify_dos_skipmodules" => array(
												'name'			=> 'wortify_dos_skipmodules' ,
												'title'			=> 'WORTIFY_DOS_SKIPMODS' ,
												'description'	=> 'WORTIFY_DOS_SKIPMODSDSC' ,
												'formtype'		=> 'text' ,
												'valuetype'		=> 'text' ,
												'default'		=> "" ,
												'options'		=> array()
											),
				"wortify_dos_expire" => array(
												'name'			=> 'wortify_dos_expire' ,
												'title'			=> 'WORTIFY_DOS_EXPIRE' ,
												'description'	=> 'WORTIFY_DOS_EXPIREDSC' ,
												'formtype'		=> 'text' ,
												'valuetype'		=> 'int' ,
												'default'		=> "60" ,
												'options'		=> array()
											) ,
				"wortify_dos_f5count" => array(
													'name'			=> 'wortify_dos_f5count' ,
													'title'			=> 'WORTIFY_DOS_F5COUNT' ,
													'description'	=> 'WORTIFY_DOS_F5COUNTDSC' ,
													'formtype'		=> 'text' ,
													'valuetype'		=> 'int' ,
													'default'		=> "20" ,
													'options'		=> array()
												),
				"wortify_dos_f5action" => array(
												'name'			=> 'wortify_dos_f5action' ,
												'title'			=> 'WORTIFY_DOS_F5ACTION' ,
												'description'	=> '' ,
												'formtype'		=> 'select' ,
												'valuetype'		=> 'text' ,
												'default'		=> "exit" ,
												'options'		=> array( 'WORTIFY_DOSOPT_NONE' => 'none' , 'WORTIFY_DOSOPT_SLEEP' => 'sleep' , 'WORTIFY_DOSOPT_EXIT' => 'exit' , 'WORTIFY_DOSOPT_BIPTIME0' => 'biptime0' , 'WORTIFY_DOSOPT_BIP' => 'bip' , 'WORTIFY_DOSOPT_HTA' => 'hta' )
											) ,
				"wortify_dos_crcount" =>  array(
												'name'			=> 'wortify_dos_crcount' ,
												'title'			=> 'WORTIFY_DOS_CRCOUNT' ,
												'description'	=> 'WORTIFY_DOS_CRCOUNTDSC' ,
												'formtype'		=> 'text' ,
												'valuetype'		=> 'int' ,
												'default'		=> "40" ,
												'options'		=> array()
											),
				"wortify_dos_craction" => array(
												'name'			=> 'wortify_dos_craction' ,
												'title'			=> 'WORTIFY_DOS_CRACTION' ,
												'description'	=> '' ,
												'formtype'		=> 'select' ,
												'valuetype'		=> 'text' ,
												'default'		=> "exit" ,
												'options'		=> array( 'WORTIFY_DOSOPT_NONE' => 'none' , 'WORTIFY_DOSOPT_SLEEP' => 'sleep' , 'WORTIFY_DOSOPT_EXIT' => 'exit' , 'WORTIFY_DOSOPT_BIPTIME0' => 'biptime0' , 'WORTIFY_DOSOPT_BIP' => 'bip' , 'WORTIFY_DOSOPT_HTA' => 'hta' )
											),
				"wortify_dos_crsafe" => array(
												'name'			=> 'wortify_dos_crsafe' ,
												'title'			=> 'WORTIFY_DOS_CRSAFE' ,
												'description'	=> 'WORTIFY_DOS_CRSAFEDSC' ,
												'formtype'		=> 'text' ,
												'valuetype'		=> 'text' ,
												'default'		=> "/(bingbot|Googlebot|Yahoo! Slurp)/i" ,
												'options'		=> array()
											),
				"wortify_bip_except" => array(
												'name'			=> 'wortify_bip_except' ,
												'title'			=> 'WORTIFY_BIP_EXCEPT' ,
												'description'	=> 'WORTIFY_BIP_EXCEPTDSC' ,
												'formtype'		=> 'group_multi' ,
												'valuetype'		=> 'array' ,
												'default'		=> array(1) ,
												'options'		=> array()
											) ,
				"wortify_disable_features" => array(
												'name'			=> 'wortify_disable_features' ,
												'title'			=> 'WORTIFY_DISABLES' ,
												'description'	=> '' ,
												'formtype'		=> 'select' ,
												'valuetype'		=> 'int' ,
												'default'		=> 1 ,
												'options'		=> array('xmlrpc'=>1,'xmlrpc + 2.0.9.2 bugs'=>1025,'_NONE'=>0)
											) ,
				"wortify_enable_dblayertrap" => array(
												'name'			=> 'wortify_enable_dblayertrap' ,
												'title'			=> 'DBLAYERTRAP' ,
												'description'	=> 'DBLAYERTRAPDSC' ,
												'formtype'		=> 'yesno' ,
												'valuetype'		=> 'int' ,
												'default'		=> 1 ,
												'options'		=> array()
											),
				"wortify_dblayertrap_wo_server" =>  array(
												'name'			=> 'wortify_dblayertrap_wo_server' ,
												'title'			=> 'DBTRAPWOSRV' ,
												'description'	=> 'DBTRAPWOSRVDSC' ,
												'formtype'		=> 'yesno' ,
												'valuetype'		=> 'int' ,
												'default'		=> 0 ,
												'options'		=> array()
											) ,
				"wortify_enable_bigumbrella" => array(
												'name'			=> 'wortify_enable_bigumbrella' ,
												'title'			=> 'WORTIFY_BIGUMBRELLA' ,
												'description'	=> 'WORTIFY_BIGUMBRELLADSC' ,
												'formtype'		=> 'yesno' ,
												'valuetype'		=> 'int' ,
												'default'		=> 1 ,
												'options'		=> array()
											) ,
				"wortify_spamcount_uri4user" => array(
											'name'			=> 'wortify_spamcount_uri4user' ,
											'title'			=> 'WORTIFY_SPAMURI4U' ,
											'description'	=> 'WORTIFY_SPAMURI4UDSC' ,
											'formtype'		=> 'textbox' ,
											'valuetype'		=> 'int' ,
											'default'		=> 0 ,
											'options'		=> array()
										),
				"wortify_spamcount_uri4guest" => array(
											'name'			=> 'wortify_spamcount_uri4guest' ,
											'title'			=> 'WORTIFY_SPAMURI4G' ,
											'description'	=> 'WORTIFY_SPAMURI4GDSC' ,
											'formtype'		=> 'textbox' ,
											'valuetype'		=> 'int' ,
											'default'		=> 5 ,
											'options'		=> array()
										),
				"wortify_stopforumspam_action" =>  array(
											'name'			=> 'wortify_stopforumspam_action' ,
											'title'			=> 'WORTIFY_STOPFORUMSPAM_ACTION' ,
											'description'	=> 'WORTIFY_STOPFORUMSPAM_ACTIONDSC' ,
											'formtype'		=> 'select' ,
											'valuetype'		=> 'text' ,
											'default'		=> 'none' ,
											'options'		=> array( '_NONE' => 'none', 'WORTIFY_OPT_NONE' => 'log' , 'WORTIFY_OPT_SAN' => 'san' , 'WORTIFY_OPT_BIPTIME0' => 'biptime0' , 'WORTIFY_OPT_BIP' => 'bip')
										)
						),
			"xortify" => array(
				"xortify_protocol" => array('name' => 'xortify_protocol',
										'title' => 'WORTIFY_PROTOCOL',
										'description' => 'WORTIFY_PROTOCOL_DESC',
										'formtype' => 'select',
										'valuetype' => 'text',
										'default' => 'rest_curlserialised',
										'options' => array(WORTIFY_PROTOCOL_MINIMUMCLOUD=>'minimisedcloud', WORTIFY_PROTOCOL_WGETSERIAL=>'rest_wgetserialised', WORTIFY_PROTOCOL_WGETXML=>'rest_wgetxml', WORTIFY_PROTOCOL_CURLSERIAL=>'rest_curlserialised', WORTIFY_PROTOCOL_CURL=>'rest_curl', WORTIFY_PROTOCOL_JSON=>'rest_json', WORTIFY_PROTOCOL_CURLXML=>'rest_curlxml'),
										),
					"xortify_username" => array('name' => 'xortify_username',
							'title' => 'WORTIFY_USERNAME',
							'description' => 'WORTIFY_USERNAME_DESC',
							'formtype' => 'text',
							'valuetype' => 'text',
							'default' => ''
					),
					"xortify_password" => array('name' => 'xortify_password',
							'title' => 'WORTIFY_PASSWORD',
							'description' => 'WORTIFY_PASSWORD_DESC',
							'formtype' => 'password',
							'valuetype' => 'text',
							'default' => '',
					),
					"xortify_seconds" => array('name' => 'xortify_seconds',
							'title' => 'WORTIFY_SECONDS',
							'description' => 'WORTIFY_SECONDS_DESC',
							'formtype' => 'select',
							'valuetype' => 'int',
							'default' => 3600,
							'options' => array(WORTIFY_SECONDS_37600 => 37600, WORTIFY_SECONDS_28800 => 28800, WORTIFY_SECONDS_14400 => 14400, WORTIFY_SECONDS_7200 => 7200,
									WORTIFY_SECONDS_3600 => 3600, WORTIFY_SECONDS_1800 => 1800, WORTIFY_SECONDS_1200 => 1200, WORTIFY_SECONDS_600 => 600,
									WORTIFY_SECONDS_300 => 300, WORTIFY_SECONDS_180 => 180, WORTIFY_SECONDS_60 => 60, WORTIFY_SECONDS_30 => 30)
					),
					"xortify_ip_cache" => array('name' => 'xortify_ip_cache',
							'title' => 'WORTIFY_IPCACHE',
							'description' => 'WORTIFY_IPCACHE_DESC',
							'formtype' => 'select',
							'valuetype' => 'int',
							'default' => 86400,
							'options' => array(WORTIFY_SECONDS_29030400 => 29030400, WORTIFY_SECONDS_14515200 => 14515200, WORTIFY_SECONDS_7257600 => 7257600, WORTIFY_SECONDS_2419200 => 2419200,
									WORTIFY_SECONDS_1209600 => 1209600, WORTIFY_SECONDS_604800 => 604800, WORTIFY_SECONDS_86400 => 86400, WORTIFY_SECONDS_43200 => 43200,
									WORTIFY_SECONDS_37600 => 37600, WORTIFY_SECONDS_28800 => 28800, WORTIFY_SECONDS_14400 => 14400, WORTIFY_SECONDS_7200 => 7200,
									WORTIFY_SECONDS_3600 => 3600, WORTIFY_SECONDS_1800 => 1800, WORTIFY_SECONDS_1200 => 1200, WORTIFY_SECONDS_600 => 600,
									WORTIFY_SECONDS_300 => 300, WORTIFY_SECONDS_180 => 180, WORTIFY_SECONDS_60 => 60, WORTIFY_SECONDS_30 => 30)
					),
					"xortify_server_cache" => array('name' => 'xortify_server_cache',
							'title' => 'WORTIFY_SERVERCACHE',
							'description' => 'WORTIFY_SERVERCACHE_DESC',
							'formtype' => 'select',
							'valuetype' => 'int',
							'default' => 604800,
							'options' => array(WORTIFY_SECONDS_29030400 => 29030400, WORTIFY_SECONDS_14515200 => 14515200, WORTIFY_SECONDS_7257600 => 7257600, WORTIFY_SECONDS_2419200 => 2419200,
									WORTIFY_SECONDS_1209600 => 1209600, WORTIFY_SECONDS_604800 => 604800, WORTIFY_SECONDS_86400 => 86400, WORTIFY_SECONDS_43200 => 43200,
									WORTIFY_SECONDS_37600 => 37600, WORTIFY_SECONDS_28800 => 28800, WORTIFY_SECONDS_14400 => 14400, WORTIFY_SECONDS_7200 => 7200,
									WORTIFY_SECONDS_3600 => 3600, WORTIFY_SECONDS_1800 => 1800, WORTIFY_SECONDS_1200 => 1200, WORTIFY_SECONDS_600 => 600,
									WORTIFY_SECONDS_300 => 300, WORTIFY_SECONDS_180 => 180, WORTIFY_SECONDS_60 => 60, WORTIFY_SECONDS_30 => 30)
					),
					"xortify_records" => array('name' => 'xortify_records',
							'title' => 'WORTIFY_RECORDS',
							'description' => 'WORTIFY_RECORDS_DESC',
							'formtype' => 'select',
							'valuetype' => 'int',
							'default' => 3600,
							'options' => array(WORTIFY_RECORDS_177600 => 177600, WORTIFY_RECORDS_48800 => 48800, WORTIFY_RECORDS_24400 => 24400, WORTIFY_RECORDS_12200 => 12200,
									WORTIFY_RECORDS_3600 => 3600, WORTIFY_RECORDS_1800 => 1800, WORTIFY_RECORDS_1200 => 1200, WORTIFY_RECORDS_600 => 600,
									WORTIFY_RECORDS_300 => 300, WORTIFY_RECORDS_180 => 180, WORTIFY_RECORDS_60 => 60, WORTIFY_RECORDS_30 => 30),
								
					),
					"xortify_providers" => array('name' => 'xortify_providers',
							'title' => 'WORTIFY_PROVIDERS',
							'description' => 'WORTIFY_PROVIDERS_DESC',
							'formtype' => 'select_multi',
							'valuetype' => 'array',
							'default' => array('xortify', 'protector', 'stopforumspam.com', 'projecthoneypot.org'),
							'options' => array('(none)' => '', WORTIFY_PROVIDER_XORTIFY => 'xortify', WORTIFY_PROVIDER_PROTECTOR => 'protector', WORTIFY_PROVIDER_STOPFORUMSPAM => 'stopforumspam.com', WORTIFY_PROVIDER_PROJECTHONEYPOT => 'projecthoneypot.org')
					),					
				"xortify_urirest" => array('name' => 'xortify_urirest',
								'title' => 'WORTIFY_URIREST',
								'description' => 'WORTIFY_URIREST_DESC',
								'formtype' => 'text',
								'valuetype' => 'text',
								'default' => WORTIFY_API_URL_WORTIFY,
								),
				"xortify_save_banned" => array('name' => 'xortify_save_banned',
									'title' => 'WORTIFY_LOG_BANNED',
									'description' => 'WORTIFY_LOG_BANNED_DESC',
									'formtype' => 'yesno',
									'valuetype' => 'int',
									'default' => true,
									),
				"xortify_save_blocked" => array('name' => 'xortify_save_blocked',
									'title' => 'WORTIFY_LOG_BLOCKED',
									'description' => 'WORTIFY_LOG_BLOCKED_DESC',
									'formtype' => 'yesno',
									'valuetype' => 'int',
									'default' => true,
									),
				"xortify_save_monitored" => array('name' => 'xortify_save_monitored',
								'title' => 'WORTIFY_LOG_MONITORED',
								'description' => 'WORTIFY_LOG_MONITORED_DESC',
								'formtype' => 'yesno',
								'valuetype' => 'int',
								'default' => true
								) ,
				"xortify_octeta" => array('name' => 'xortify_octeta',
							'title' => 'WORTIFY_PHP_NUMBEROFDAYS',
							'description' => 'WORTIFY_PHP_NUMBEROFDAYS_DESC',
							'formtype' => 'select',
							'valuetype' => 'int',
							'default' => 92,
							'options' => array("1 day" => 1, "2 days" => 2, "3 days" => 3, "4 days" => 4, "5 days" => 5, "6 days" => 6, "7 days" => 7, "8 days" => 8, "9 days" => 9, "10 days" => 10, "11 day" => 11, "12 days" => 12, "13 days" => 13, "14 days" => 14, "15 days" => 15, "16 days" => 16, "17 days" => 17, "18 days" => 18, "19 days" => 19, "20 days" => 20, "21 day" => 21, "22 days" => 22, "23 days" => 23, "24 days" => 24, "25 days" => 25, "26 days" => 26, "27 days" => 27, "28 days" => 28, "29 days" => 29, "30 days" => 30, "31 day" => 31, "32 days" => 32, "33 days" => 33, "34 days" => 34, "35 days" => 35, "36 days" => 36, "37 days" => 37, "38 days" => 38, "39 days" => 39, "40 days" => 40, "41 day" => 41, "42 days" => 42, "43 days" => 43, "44 days" => 44, "45 days" => 45, "46 days" => 46, "47 days" => 47, "48 days" => 48, "49 days" => 49, "50 days" => 50, "51 day" => 51, "52 days" => 52, "53 days" => 53, "54 days" => 54, "55 days" => 55, "56 days" => 56, "57 days" => 57, "58 days" => 58, "59 days" => 59, "60 days" => 60, "61 day" => 61, "62 days" => 62, "63 days" => 63, "64 days" => 64, "65 days" => 65, "66 days" => 66, "67 days" => 67, "68 days" => 68, "69 days" => 69, "70 days" => 70, "71 day" => 71, "72 days" => 72, "73 days" => 73, "74 days" => 74, "75 days" => 75, "76 days" => 76, "77 days" => 77, "78 days" => 78, "79 days" => 79, "80 days" => 80, "81 day" => 81, "82 days" => 82, "83 days" => 83, "84 days" => 84, "85 days" => 85, "86 days" => 86, "87 days" => 87, "88 days" => 88, "89 days" => 89, "90 days" => 90, "91 day" => 91, "92 days" => 92, "93 days" => 93, "94 days" => 94, "95 days" => 95, "96 days" => 96, "97 days" => 97, "98 days" => 98, "99 days" => 99, "100 days" => 100, "101 day" => 101, "102 days" => 102, "103 days" => 103, "104 days" => 104, "105 days" => 105, "106 days" => 106, "107 days" => 107, "108 days" => 108, "109 days" => 109, "110 days" => 110, "111 day" => 111, "112 days" => 112, "113 days" => 113, "114 days" => 114, "115 days" => 115, "116 days" => 116, "117 days" => 117, "118 days" => 118, "119 days" => 119, "120 days" => 120, "121 day" => 121, "122 days" => 122, "123 days" => 123, "124 days" => 124, "125 days" => 125, "126 days" => 126, "127 days" => 127, "128 days" => 128, "129 days" => 129, "130 days" => 130, "131 day" => 131, "132 days" => 132, "133 days" => 133, "134 days" => 134, "135 days" => 135, "136 days" => 136, "137 days" => 137, "138 days" => 138, "139 days" => 139, "140 days" => 140, "141 day" => 141, "142 days" => 142, "143 days" => 143, "144 days" => 144, "145 days" => 145, "146 days" => 146, "147 days" => 147, "148 days" => 148, "149 days" => 149, "150 days" => 150, "151 day" => 151, "152 days" => 152, "153 days" => 153, "154 days" => 154, "155 days" => 155, "156 days" => 156, "157 days" => 157, "158 days" => 158, "159 days" => 159, "160 days" => 160, "161 day" => 161, "162 days" => 162, "163 days" => 163, "164 days" => 164, "165 days" => 165, "166 days" => 166, "167 days" => 167, "168 days" => 168, "169 days" => 169, "170 days" => 170, "171 day" => 171, "172 days" => 172, "173 days" => 173, "174 days" => 174, "175 days" => 175, "176 days" => 176, "177 days" => 177, "178 days" => 178, "179 days" => 179, "180 days" => 180, "181 day" => 181, "182 days" => 182, "183 days" => 183, "184 days" => 184, "185 days" => 185, "186 days" => 186, "187 days" => 187, "188 days" => 188, "189 days" => 189, "190 days" => 190, "191 day" => 191, "192 days" => 192, "193 days" => 193, "194 days" => 194, "195 days" => 195, "196 days" => 196, "197 days" => 197, "198 days" => 198, "199 days" => 199, "200 days" => 200, "201 day" => 201, "202 days" => 202, "203 days" => 203, "204 days" => 204, "205 days" => 205, "206 days" => 206, "207 days" => 207, "208 days" => 208, "209 days" => 209, "210 days" => 210, "211 day" => 211, "212 days" => 212, "213 days" => 213, "214 days" => 214, "215 days" => 215, "216 days" => 216, "217 days" => 217, "218 days" => 218, "219 days" => 219, "220 days" => 220, "221 day" => 221, "222 days" => 222, "223 days" => 223, "224 days" => 224, "225 days" => 225, "226 days" => 226, "227 days" => 227, "228 days" => 228, "229 days" => 229, "230 days" => 230, "231 day" => 231, "232 days" => 232, "233 days" => 233, "234 days" => 234, "235 days" => 235, "236 days" => 236, "237 days" => 237, "238 days" => 238, "239 days" => 239, "240 days" => 240, "241 day" => 241, "242 days" => 242, "243 days" => 243, "244 days" => 244, "245 days" => 245, "246 days" => 246, "247 days" => 247, "248 days" => 248, "249 days" => 249, "250 days" => 250, "251 day" => 251, "252 days" => 252, "253 days" => 253, "254 days" => 254, "255 days" => 255),
							),
				"xortify_octetb" => array('name' => 'xortify_octetb',
									'title' => 'WORTIFY_PHP_SEVERITYLEVEL',
									'description' => 'WORTIFY_PHP_SEVERITYLEVEL_DESC',
									'formtype' => 'select',
									'valuetype' => 'int',
									'default' => 15,
									'options' => array("1.96 percent" => 5, "2.35 percent" => 6, "2.75 percent" => 7, "3.14 percent" => 8, "3.53 percent" => 9, "3.92 percent" => 10, "4.31 percent" => 11, "4.71 percent" => 12, "5.10 percent" => 13, "5.49 percent" => 14, "5.88 percent" => 15, "6.27 percent" => 16, "6.67 percent" => 17, "7.06 percent" => 18, "7.45 percent" => 19, "7.84 percent" => 20, "8.24 percent" => 21, "8.63 percent" => 22, "9.02 percent" => 23, "9.41 percent" => 24, "9.80 percent" => 25, "10.20 percent" => 26, "10.59 percent" => 27, "10.98 percent" => 28, "11.37 percent" => 29, "11.76 percent" => 30, "12.16 percent" => 31, "12.55 percent" => 32, "12.94 percent" => 33, "13.33 percent" => 34, "13.73 percent" => 35, "14.12 percent" => 36, "14.51 percent" => 37, "14.90 percent" => 38, "15.29 percent" => 39, "15.69 percent" => 40, "16.08 percent" => 41, "16.47 percent" => 42, "16.86 percent" => 43, "17.25 percent" => 44, "17.65 percent" => 45, "18.04 percent" => 46, "18.43 percent" => 47, "18.82 percent" => 48, "19.22 percent" => 49, "19.61 percent" => 50, "20.00 percent" => 51, "20.39 percent" => 52, "20.78 percent" => 53, "21.18 percent" => 54, "21.57 percent" => 55, "21.96 percent" => 56, "22.35 percent" => 57, "22.75 percent" => 58, "23.14 percent" => 59, "23.53 percent" => 60, "23.92 percent" => 61, "24.31 percent" => 62, "24.71 percent" => 63, "25.10 percent" => 64, "25.49 percent" => 65, "25.88 percent" => 66, "26.27 percent" => 67, "26.67 percent" => 68, "27.06 percent" => 69, "27.45 percent" => 70, "27.84 percent" => 71, "28.24 percent" => 72, "28.63 percent" => 73, "29.02 percent" => 74, "29.41 percent" => 75, "29.80 percent" => 76, "30.20 percent" => 77, "30.59 percent" => 78, "30.98 percent" => 79, "31.37 percent" => 80, "31.76 percent" => 81, "32.16 percent" => 82, "32.55 percent" => 83, "32.94 percent" => 84, "33.33 percent" => 85, "33.73 percent" => 86, "34.12 percent" => 87, "34.51 percent" => 88, "34.90 percent" => 89, "35.29 percent" => 90, "35.69 percent" => 91, "36.08 percent" => 92, "36.47 percent" => 93, "36.86 percent" => 94, "37.25 percent" => 95, "37.65 percent" => 96, "38.04 percent" => 97, "38.43 percent" => 98, "38.82 percent" => 99, "39.22 percent" => 100, "39.61 percent" => 101, "40.00 percent" => 102, "40.39 percent" => 103, "40.78 percent" => 104, "41.18 percent" => 105, "41.57 percent" => 106, "41.96 percent" => 107, "42.35 percent" => 108, "42.75 percent" => 109, "43.14 percent" => 110, "43.53 percent" => 111, "43.92 percent" => 112, "44.31 percent" => 113, "44.71 percent" => 114, "45.10 percent" => 115, "45.49 percent" => 116, "45.88 percent" => 117, "46.27 percent" => 118, "46.67 percent" => 119, "47.06 percent" => 120, "47.45 percent" => 121, "47.84 percent" => 122, "48.24 percent" => 123, "48.63 percent" => 124, "49.02 percent" => 125, "49.41 percent" => 126, "49.80 percent" => 127, "50.20 percent" => 128, "50.59 percent" => 129, "50.98 percent" => 130, "51.37 percent" => 131, "51.76 percent" => 132, "52.16 percent" => 133, "52.55 percent" => 134, "52.94 percent" => 135, "53.33 percent" => 136, "53.73 percent" => 137, "54.12 percent" => 138, "54.51 percent" => 139, "54.90 percent" => 140, "55.29 percent" => 141, "55.69 percent" => 142, "56.08 percent" => 143, "56.47 percent" => 144, "56.86 percent" => 145, "57.25 percent" => 146, "57.65 percent" => 147, "58.04 percent" => 148, "58.43 percent" => 149, "58.82 percent" => 150, "59.22 percent" => 151, "59.61 percent" => 152, "60.00 percent" => 153, "60.39 percent" => 154, "60.78 percent" => 155, "61.18 percent" => 156, "61.57 percent" => 157, "61.96 percent" => 158, "62.35 percent" => 159, "62.75 percent" => 160, "63.14 percent" => 161, "63.53 percent" => 162, "63.92 percent" => 163, "64.31 percent" => 164, "64.71 percent" => 165, "65.10 percent" => 166, "65.49 percent" => 167, "65.88 percent" => 168, "66.27 percent" => 169, "66.67 percent" => 170, "67.06 percent" => 171, "67.45 percent" => 172, "67.84 percent" => 173, "68.24 percent" => 174, "68.63 percent" => 175, "69.02 percent" => 176, "69.41 percent" => 177, "69.80 percent" => 178, "70.20 percent" => 179, "70.59 percent" => 180, "70.98 percent" => 181, "71.37 percent" => 182, "71.76 percent" => 183, "72.16 percent" => 184, "72.55 percent" => 185, "72.94 percent" => 186, "73.33 percent" => 187, "73.73 percent" => 188, "74.12 percent" => 189, "74.51 percent" => 190, "74.90 percent" => 191, "75.29 percent" => 192, "75.69 percent" => 193, "76.08 percent" => 194, "76.47 percent" => 195, "76.86 percent" => 196, "77.25 percent" => 197, "77.65 percent" => 198, "78.04 percent" => 199, "78.43 percent" => 200, "78.82 percent" => 201, "79.22 percent" => 202, "79.61 percent" => 203, "80.00 percent" => 204, "80.39 percent" => 205, "80.78 percent" => 206, "81.18 percent" => 207, "81.57 percent" => 208, "81.96 percent" => 209, "82.35 percent" => 210, "82.75 percent" => 211, "83.14 percent" => 212, "83.53 percent" => 213, "83.92 percent" => 214, "84.31 percent" => 215, "84.71 percent" => 216, "85.10 percent" => 217, "85.49 percent" => 218, "85.88 percent" => 219, "86.27 percent" => 220, "86.67 percent" => 221, "87.06 percent" => 222, "87.45 percent" => 223, "87.84 percent" => 224, "88.24 percent" => 225, "88.63 percent" => 226, "89.02 percent" => 227, "89.41 percent" => 228, "89.80 percent" => 229, "90.20 percent" => 230, "90.59 percent" => 231, "90.98 percent" => 232, "91.37 percent" => 233, "91.76 percent" => 234, "92.16 percent" => 235, "92.55 percent" => 236, "92.94 percent" => 237, "93.33 percent" => 238, "93.73 percent" => 239, "94.12 percent" => 240, "94.51 percent" => 241, "94.90 percent" => 242, "95.29 percent" => 243, "95.69 percent" => 244, "96.08 percent" => 245, "96.47 percent" => 246, "96.86 percent" => 247, "97.25 percent" => 248, "97.65 percent" => 249, "98.04 percent" => 250, "98.43 percent" => 251, "98.82 percent" => 252, "99.22 percent" => 253, "99.61 percent" => 254, "100.00 percent" => 255),
									),
				"xortify_octetc" => array('name' => 'xortify_octetc',
										'title' => 'WORTIFY_PHP_SCANMODE',
										'description' => 'WORTIFY_PHP_SCANMODE_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 2,
										'options' => array(WORTIFY_PHP_SCANMODE_SUSPICIOUS => 1, WORTIFY_PHP_SCANMODE_HARVESTER => 2, WORTIFY_PHP_SCANMODE_SUSICIOUSHARVESTER => 3, 
																					 WORTIFY_PHP_SCANMODE_SPAMMER => 4, WORTIFY_PHP_SCANMODE_SUSPICIOUSSPAMMER => 5, WORTIFY_PHP_SCANMODE_HARVESTERSPAMMER => 6, 
																					 WORTIFY_PHP_SCANMODE_SUSPICIOUSHARVESTERSPAMMER => 7)
										),
				"xortify_email_freq" => array('name' => 'xortify_email_freq',
									'title' => 'WORTIFY_SFS_EMAILFREQUENCY',
									'description' => 'WORTIFY_SFS_EMAILFREQUENCY_DESC',
									'formtype' => 'select',
									'valuetype' => 'int',
									'default' => 2,
									'options' => array("Occured 1 time(s)" => 1, "Occured 2 time(s)" => 2, "Occured 3 time(s)" => 3, "Occured 4 time(s)" => 4, "Occured 5 time(s)" => 5, "Occured 6 time(s)" => 6, "Occured 7 time(s)" => 7, "Occured 8 time(s)" => 8, "Occured 9 time(s)" => 9, "Occured 10 time(s)" => 10, "Occured 11 time(s)" => 11, "Occured 12 time(s)" => 12, "Occured 13 time(s)" => 13, "Occured 14 time(s)" => 14, "Occured 15 time(s)" => 15, "Occured 16 time(s)" => 16, "Occured 17 time(s)" => 17, "Occured 18 time(s)" => 18, "Occured 19 time(s)" => 19, "Occured 20 time(s)" => 20, "Occured 21 time(s)" => 21, "Occured 22 time(s)" => 22, "Occured 23 time(s)" => 23, "Occured 24 time(s)" => 24, "Occured 25 time(s)" => 25, "Occured 26 time(s)" => 26, "Occured 27 time(s)" => 27, "Occured 28 time(s)" => 28, "Occured 29 time(s)" => 29, "Occured 30 time(s)" => 30, "Occured 31 time(s)" => 31, "Occured 32 time(s)" => 32, "Occured 33 time(s)" => 33, "Occured 34 time(s)" => 34, "Occured 35 time(s)" => 35, "Occured 36 time(s)" => 36, "Occured 37 time(s)" => 37, "Occured 38 time(s)" => 38, "Occured 39 time(s)" => 39, "Occured 40 time(s)" => 40, "Occured 41 time(s)" => 41, "Occured 42 time(s)" => 42, "Occured 43 time(s)" => 43, "Occured 44 time(s)" => 44, "Occured 45 time(s)" => 45, "Occured 46 time(s)" => 46, "Occured 47 time(s)" => 47, "Occured 48 time(s)" => 48, "Occured 49 time(s)" => 49, "Occured 50 time(s)" => 50, "Occured 51 time(s)" => 51, "Occured 52 time(s)" => 52, "Occured 53 time(s)" => 53, "Occured 54 time(s)" => 54, "Occured 55 time(s)" => 55, "Occured 56 time(s)" => 56, "Occured 57 time(s)" => 57, "Occured 58 time(s)" => 58, "Occured 59 time(s)" => 59, "Occured 60 time(s)" => 60, "Occured 61 time(s)" => 61, "Occured 62 time(s)" => 62, "Occured 63 time(s)" => 63, "Occured 64 time(s)" => 64, "Occured 65 time(s)" => 65, "Occured 66 time(s)" => 66, "Occured 67 time(s)" => 67, "Occured 68 time(s)" => 68, "Occured 69 time(s)" => 69, "Occured 70 time(s)" => 70, "Occured 71 time(s)" => 71, "Occured 72 time(s)" => 72, "Occured 73 time(s)" => 73, "Occured 74 time(s)" => 74, "Occured 75 time(s)" => 75, "Occured 76 time(s)" => 76, "Occured 77 time(s)" => 77, "Occured 78 time(s)" => 78, "Occured 79 time(s)" => 79, "Occured 80 time(s)" => 80, "Occured 81 time(s)" => 81, "Occured 82 time(s)" => 82, "Occured 83 time(s)" => 83, "Occured 84 time(s)" => 84, "Occured 85 time(s)" => 85, "Occured 86 time(s)" => 86, "Occured 87 time(s)" => 87, "Occured 88 time(s)" => 88, "Occured 89 time(s)" => 89, "Occured 90 time(s)" => 90, "Occured 91 time(s)" => 91, "Occured 92 time(s)" => 92, "Occured 93 time(s)" => 93, "Occured 94 time(s)" => 94, "Occured 95 time(s)" => 95, "Occured 96 time(s)" => 96, "Occured 97 time(s)" => 97, "Occured 98 time(s)" => 98, "Occured 99 time(s)" => 99, "Occured 100 time(s)" => 100, "Occured 101 time(s)" => 101, "Occured 102 time(s)" => 102, "Occured 103 time(s)" => 103, "Occured 104 time(s)" => 104, "Occured 105 time(s)" => 105, "Occured 106 time(s)" => 106, "Occured 107 time(s)" => 107, "Occured 108 time(s)" => 108, "Occured 109 time(s)" => 109, "Occured 110 time(s)" => 110, "Occured 111 time(s)" => 111, "Occured 112 time(s)" => 112, "Occured 113 time(s)" => 113, "Occured 114 time(s)" => 114, "Occured 115 time(s)" => 115, "Occured 116 time(s)" => 116, "Occured 117 time(s)" => 117, "Occured 118 time(s)" => 118, "Occured 119 time(s)" => 119, "Occured 120 time(s)" => 120, "Occured 121 time(s)" => 121, "Occured 122 time(s)" => 122, "Occured 123 time(s)" => 123, "Occured 124 time(s)" => 124, "Occured 125 time(s)" => 125, "Occured 126 time(s)" => 126, "Occured 127 time(s)" => 127, "Occured 128 time(s)" => 128, "Occured 129 time(s)" => 129, "Occured 130 time(s)" => 130, "Occured 131 time(s)" => 131, "Occured 132 time(s)" => 132, "Occured 133 time(s)" => 133, "Occured 134 time(s)" => 134, "Occured 135 time(s)" => 135, "Occured 136 time(s)" => 136, "Occured 137 time(s)" => 137, "Occured 138 time(s)" => 138, "Occured 139 time(s)" => 139, "Occured 140 time(s)" => 140, "Occured 141 time(s)" => 141, "Occured 142 time(s)" => 142, "Occured 143 time(s)" => 143, "Occured 144 time(s)" => 144, "Occured 145 time(s)" => 145, "Occured 146 time(s)" => 146, "Occured 147 time(s)" => 147, "Occured 148 time(s)" => 148, "Occured 149 time(s)" => 149, "Occured 150 time(s)" => 150),
									),
				"xortify_email_lastseen" => array('name' => 'xortify_email_lastseen',
									'title' => 'WORTIFY_SFS_EMAILLASTSEEN',
									'description' => 'WORTIFY_SFS_EMAILLASTSEEN_DESC',
									'formtype' => 'select',
									'valuetype' => 'int',
									'default' => 7257600,
									'options' => array(WORTIFY_SFS_LASTSEEN_24HOURS => 86400, WORTIFY_SFS_LASTSEEN_1WEEK => 604800, WORTIFY_SFS_LASTSEEN_FORTNIGHT => 1209600, 
																				 WORTIFY_SFS_LASTSEEN_1MONTH => 2419200, WORTIFY_SFS_LASTSEEN_2MONTHS => 4838400, WORTIFY_SFS_LASTSEEN_3MONTHS => 7257600, 
																				 WORTIFY_SFS_LASTSEEN_4MONTHS => 9676800, WORTIFY_SFS_LASTSEEN_5MONTHS => 12096000, WORTIFY_SFS_LASTSEEN_6MONTHS => 14515200,
																				 WORTIFY_SFS_LASTSEEN_12MONTHS => 29030400, WORTIFY_SFS_LASTSEEN_24MONTHS => 58060800),
									),
				"xortify_uoption_name_freq" => array('name' => 'xortify_uoption_name_freq',
										'title' => 'WORTIFY_SFS_UNAMEFREQUENCY',
										'description' => 'WORTIFY_SFS_UNAMEFREQUENCY_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 2,
										'options' => array("Occured 1 time(s)" => 1, "Occured 2 time(s)" => 2, "Occured 3 time(s)" => 3, "Occured 4 time(s)" => 4, "Occured 5 time(s)" => 5, "Occured 6 time(s)" => 6, "Occured 7 time(s)" => 7, "Occured 8 time(s)" => 8, "Occured 9 time(s)" => 9, "Occured 10 time(s)" => 10, "Occured 11 time(s)" => 11, "Occured 12 time(s)" => 12, "Occured 13 time(s)" => 13, "Occured 14 time(s)" => 14, "Occured 15 time(s)" => 15, "Occured 16 time(s)" => 16, "Occured 17 time(s)" => 17, "Occured 18 time(s)" => 18, "Occured 19 time(s)" => 19, "Occured 20 time(s)" => 20, "Occured 21 time(s)" => 21, "Occured 22 time(s)" => 22, "Occured 23 time(s)" => 23, "Occured 24 time(s)" => 24, "Occured 25 time(s)" => 25, "Occured 26 time(s)" => 26, "Occured 27 time(s)" => 27, "Occured 28 time(s)" => 28, "Occured 29 time(s)" => 29, "Occured 30 time(s)" => 30, "Occured 31 time(s)" => 31, "Occured 32 time(s)" => 32, "Occured 33 time(s)" => 33, "Occured 34 time(s)" => 34, "Occured 35 time(s)" => 35, "Occured 36 time(s)" => 36, "Occured 37 time(s)" => 37, "Occured 38 time(s)" => 38, "Occured 39 time(s)" => 39, "Occured 40 time(s)" => 40, "Occured 41 time(s)" => 41, "Occured 42 time(s)" => 42, "Occured 43 time(s)" => 43, "Occured 44 time(s)" => 44, "Occured 45 time(s)" => 45, "Occured 46 time(s)" => 46, "Occured 47 time(s)" => 47, "Occured 48 time(s)" => 48, "Occured 49 time(s)" => 49, "Occured 50 time(s)" => 50, "Occured 51 time(s)" => 51, "Occured 52 time(s)" => 52, "Occured 53 time(s)" => 53, "Occured 54 time(s)" => 54, "Occured 55 time(s)" => 55, "Occured 56 time(s)" => 56, "Occured 57 time(s)" => 57, "Occured 58 time(s)" => 58, "Occured 59 time(s)" => 59, "Occured 60 time(s)" => 60, "Occured 61 time(s)" => 61, "Occured 62 time(s)" => 62, "Occured 63 time(s)" => 63, "Occured 64 time(s)" => 64, "Occured 65 time(s)" => 65, "Occured 66 time(s)" => 66, "Occured 67 time(s)" => 67, "Occured 68 time(s)" => 68, "Occured 69 time(s)" => 69, "Occured 70 time(s)" => 70, "Occured 71 time(s)" => 71, "Occured 72 time(s)" => 72, "Occured 73 time(s)" => 73, "Occured 74 time(s)" => 74, "Occured 75 time(s)" => 75, "Occured 76 time(s)" => 76, "Occured 77 time(s)" => 77, "Occured 78 time(s)" => 78, "Occured 79 time(s)" => 79, "Occured 80 time(s)" => 80, "Occured 81 time(s)" => 81, "Occured 82 time(s)" => 82, "Occured 83 time(s)" => 83, "Occured 84 time(s)" => 84, "Occured 85 time(s)" => 85, "Occured 86 time(s)" => 86, "Occured 87 time(s)" => 87, "Occured 88 time(s)" => 88, "Occured 89 time(s)" => 89, "Occured 90 time(s)" => 90, "Occured 91 time(s)" => 91, "Occured 92 time(s)" => 92, "Occured 93 time(s)" => 93, "Occured 94 time(s)" => 94, "Occured 95 time(s)" => 95, "Occured 96 time(s)" => 96, "Occured 97 time(s)" => 97, "Occured 98 time(s)" => 98, "Occured 99 time(s)" => 99, "Occured 100 time(s)" => 100, "Occured 101 time(s)" => 101, "Occured 102 time(s)" => 102, "Occured 103 time(s)" => 103, "Occured 104 time(s)" => 104, "Occured 105 time(s)" => 105, "Occured 106 time(s)" => 106, "Occured 107 time(s)" => 107, "Occured 108 time(s)" => 108, "Occured 109 time(s)" => 109, "Occured 110 time(s)" => 110, "Occured 111 time(s)" => 111, "Occured 112 time(s)" => 112, "Occured 113 time(s)" => 113, "Occured 114 time(s)" => 114, "Occured 115 time(s)" => 115, "Occured 116 time(s)" => 116, "Occured 117 time(s)" => 117, "Occured 118 time(s)" => 118, "Occured 119 time(s)" => 119, "Occured 120 time(s)" => 120, "Occured 121 time(s)" => 121, "Occured 122 time(s)" => 122, "Occured 123 time(s)" => 123, "Occured 124 time(s)" => 124, "Occured 125 time(s)" => 125, "Occured 126 time(s)" => 126, "Occured 127 time(s)" => 127, "Occured 128 time(s)" => 128, "Occured 129 time(s)" => 129, "Occured 130 time(s)" => 130, "Occured 131 time(s)" => 131, "Occured 132 time(s)" => 132, "Occured 133 time(s)" => 133, "Occured 134 time(s)" => 134, "Occured 135 time(s)" => 135, "Occured 136 time(s)" => 136, "Occured 137 time(s)" => 137, "Occured 138 time(s)" => 138, "Occured 139 time(s)" => 139, "Occured 140 time(s)" => 140, "Occured 141 time(s)" => 141, "Occured 142 time(s)" => 142, "Occured 143 time(s)" => 143, "Occured 144 time(s)" => 144, "Occured 145 time(s)" => 145, "Occured 146 time(s)" => 146, "Occured 147 time(s)" => 147, "Occured 148 time(s)" => 148, "Occured 149 time(s)" => 149, "Occured 150 time(s)" => 150),
										
										),
				"xortify_uoption_name_lastseen" =>  array('name' => 'xortify_uoption_name_lastseen',
										'title' => 'WORTIFY_SFS_UNAMELASTSEEN',
										'description' => 'WORTIFY_SFS_UNAMELASTSEEN_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 7257600,
										'options' => array(WORTIFY_SFS_LASTSEEN_24HOURS => 86400, WORTIFY_SFS_LASTSEEN_1WEEK => 604800, WORTIFY_SFS_LASTSEEN_FORTNIGHT => 1209600, 
																					 WORTIFY_SFS_LASTSEEN_1MONTH => 2419200, WORTIFY_SFS_LASTSEEN_2MONTHS => 4838400, WORTIFY_SFS_LASTSEEN_3MONTHS => 7257600, 
																					 WORTIFY_SFS_LASTSEEN_4MONTHS => 9676800, WORTIFY_SFS_LASTSEEN_5MONTHS => 12096000, WORTIFY_SFS_LASTSEEN_6MONTHS => 14515200,
																					 WORTIFY_SFS_LASTSEEN_12MONTHS => 29030400, WORTIFY_SFS_LASTSEEN_24MONTHS => 58060800),
									),
				"xortify_ip_freq" => array('name' => 'xortify_ip_freq',
										'title' => 'WORTIFY_SFS_IPFREQUENCY',
										'description' => 'WORTIFY_SFS_IPFREQUENCY_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 2,
										'options' => array("Occured 1 time(s)" => 1, "Occured 2 time(s)" => 2, "Occured 3 time(s)" => 3, "Occured 4 time(s)" => 4, "Occured 5 time(s)" => 5, "Occured 6 time(s)" => 6, "Occured 7 time(s)" => 7, "Occured 8 time(s)" => 8, "Occured 9 time(s)" => 9, "Occured 10 time(s)" => 10, "Occured 11 time(s)" => 11, "Occured 12 time(s)" => 12, "Occured 13 time(s)" => 13, "Occured 14 time(s)" => 14, "Occured 15 time(s)" => 15, "Occured 16 time(s)" => 16, "Occured 17 time(s)" => 17, "Occured 18 time(s)" => 18, "Occured 19 time(s)" => 19, "Occured 20 time(s)" => 20, "Occured 21 time(s)" => 21, "Occured 22 time(s)" => 22, "Occured 23 time(s)" => 23, "Occured 24 time(s)" => 24, "Occured 25 time(s)" => 25, "Occured 26 time(s)" => 26, "Occured 27 time(s)" => 27, "Occured 28 time(s)" => 28, "Occured 29 time(s)" => 29, "Occured 30 time(s)" => 30, "Occured 31 time(s)" => 31, "Occured 32 time(s)" => 32, "Occured 33 time(s)" => 33, "Occured 34 time(s)" => 34, "Occured 35 time(s)" => 35, "Occured 36 time(s)" => 36, "Occured 37 time(s)" => 37, "Occured 38 time(s)" => 38, "Occured 39 time(s)" => 39, "Occured 40 time(s)" => 40, "Occured 41 time(s)" => 41, "Occured 42 time(s)" => 42, "Occured 43 time(s)" => 43, "Occured 44 time(s)" => 44, "Occured 45 time(s)" => 45, "Occured 46 time(s)" => 46, "Occured 47 time(s)" => 47, "Occured 48 time(s)" => 48, "Occured 49 time(s)" => 49, "Occured 50 time(s)" => 50, "Occured 51 time(s)" => 51, "Occured 52 time(s)" => 52, "Occured 53 time(s)" => 53, "Occured 54 time(s)" => 54, "Occured 55 time(s)" => 55, "Occured 56 time(s)" => 56, "Occured 57 time(s)" => 57, "Occured 58 time(s)" => 58, "Occured 59 time(s)" => 59, "Occured 60 time(s)" => 60, "Occured 61 time(s)" => 61, "Occured 62 time(s)" => 62, "Occured 63 time(s)" => 63, "Occured 64 time(s)" => 64, "Occured 65 time(s)" => 65, "Occured 66 time(s)" => 66, "Occured 67 time(s)" => 67, "Occured 68 time(s)" => 68, "Occured 69 time(s)" => 69, "Occured 70 time(s)" => 70, "Occured 71 time(s)" => 71, "Occured 72 time(s)" => 72, "Occured 73 time(s)" => 73, "Occured 74 time(s)" => 74, "Occured 75 time(s)" => 75, "Occured 76 time(s)" => 76, "Occured 77 time(s)" => 77, "Occured 78 time(s)" => 78, "Occured 79 time(s)" => 79, "Occured 80 time(s)" => 80, "Occured 81 time(s)" => 81, "Occured 82 time(s)" => 82, "Occured 83 time(s)" => 83, "Occured 84 time(s)" => 84, "Occured 85 time(s)" => 85, "Occured 86 time(s)" => 86, "Occured 87 time(s)" => 87, "Occured 88 time(s)" => 88, "Occured 89 time(s)" => 89, "Occured 90 time(s)" => 90, "Occured 91 time(s)" => 91, "Occured 92 time(s)" => 92, "Occured 93 time(s)" => 93, "Occured 94 time(s)" => 94, "Occured 95 time(s)" => 95, "Occured 96 time(s)" => 96, "Occured 97 time(s)" => 97, "Occured 98 time(s)" => 98, "Occured 99 time(s)" => 99, "Occured 100 time(s)" => 100, "Occured 101 time(s)" => 101, "Occured 102 time(s)" => 102, "Occured 103 time(s)" => 103, "Occured 104 time(s)" => 104, "Occured 105 time(s)" => 105, "Occured 106 time(s)" => 106, "Occured 107 time(s)" => 107, "Occured 108 time(s)" => 108, "Occured 109 time(s)" => 109, "Occured 110 time(s)" => 110, "Occured 111 time(s)" => 111, "Occured 112 time(s)" => 112, "Occured 113 time(s)" => 113, "Occured 114 time(s)" => 114, "Occured 115 time(s)" => 115, "Occured 116 time(s)" => 116, "Occured 117 time(s)" => 117, "Occured 118 time(s)" => 118, "Occured 119 time(s)" => 119, "Occured 120 time(s)" => 120, "Occured 121 time(s)" => 121, "Occured 122 time(s)" => 122, "Occured 123 time(s)" => 123, "Occured 124 time(s)" => 124, "Occured 125 time(s)" => 125, "Occured 126 time(s)" => 126, "Occured 127 time(s)" => 127, "Occured 128 time(s)" => 128, "Occured 129 time(s)" => 129, "Occured 130 time(s)" => 130, "Occured 131 time(s)" => 131, "Occured 132 time(s)" => 132, "Occured 133 time(s)" => 133, "Occured 134 time(s)" => 134, "Occured 135 time(s)" => 135, "Occured 136 time(s)" => 136, "Occured 137 time(s)" => 137, "Occured 138 time(s)" => 138, "Occured 139 time(s)" => 139, "Occured 140 time(s)" => 140, "Occured 141 time(s)" => 141, "Occured 142 time(s)" => 142, "Occured 143 time(s)" => 143, "Occured 144 time(s)" => 144, "Occured 145 time(s)" => 145, "Occured 146 time(s)" => 146, "Occured 147 time(s)" => 147, "Occured 148 time(s)" => 148, "Occured 149 time(s)" => 149, "Occured 150 time(s)" => 150),
										),
				"xortify_ip_lastseen" => array('name' => 'xortify_ip_lastseen',
									'title' => 'WORTIFY_SFS_IPLASTSEEN',
									'description' => 'WORTIFY_SFS_IPLASTSEEN_DESC',
									'formtype' => 'select',
									'valuetype' => 'int',
									'default' => 7257600,
									'options' => array(WORTIFY_SFS_LASTSEEN_24HOURS => 86400, WORTIFY_SFS_LASTSEEN_1WEEK => 604800, WORTIFY_SFS_LASTSEEN_FORTNIGHT => 1209600, 
																				 WORTIFY_SFS_LASTSEEN_1MONTH => 2419200, WORTIFY_SFS_LASTSEEN_2MONTHS => 4838400, WORTIFY_SFS_LASTSEEN_3MONTHS => 7257600, 
																				 WORTIFY_SFS_LASTSEEN_4MONTHS => 9676800, WORTIFY_SFS_LASTSEEN_5MONTHS => 12096000, WORTIFY_SFS_LASTSEEN_6MONTHS => 14515200,
																				 WORTIFY_SFS_LASTSEEN_12MONTHS => 29030400, WORTIFY_SFS_LASTSEEN_24MONTHS => 58060800),
									
									),
				"xortify_logdrops" => array('name' => 'xortify_logdrops',
										'title' => 'WORTIFY_LOGDROPS',
										'description' => 'WORTIFY_LOGDROPS_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 2419200,
										'options' => array(WORTIFY_SFS_LASTSEEN_24HOURS => 86400, WORTIFY_SFS_LASTSEEN_1WEEK => 604800, WORTIFY_SFS_LASTSEEN_FORTNIGHT => 1209600, 
																					 WORTIFY_SFS_LASTSEEN_1MONTH => 2419200, WORTIFY_SFS_LASTSEEN_2MONTHS => 4838400, WORTIFY_SFS_LASTSEEN_3MONTHS => 7257600, 
																					 WORTIFY_SFS_LASTSEEN_4MONTHS => 9676800, WORTIFY_SFS_LASTSEEN_5MONTHS => 12096000, WORTIFY_SFS_LASTSEEN_6MONTHS => 14515200,
																					 WORTIFY_SFS_LASTSEEN_12MONTHS => 29030400, WORTIFY_SFS_LASTSEEN_24MONTHS => 58060800),
										),
				"xortify_fault_delay" => array('name' => 'xortify_fault_delay',
											'title' => 'WORTIFY_FAULT_DELAY',
											'description' => 'WORTIFY_FAULT_DELAY_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 600
											),
				"xortify_curl_timeout" => array('name' => 'xortify_curl_timeout',
										'title' => 'WORTIFY_CURL_TIMOUT',
										'description' => 'WORTIFY_CURL_TIMOUT_DESC',
										'formtype' => 'text',
										'valuetype' => 'int',
										'default' => 40,
										
										),
				"xortify_curl_connecttimeout" =>  array('name' => 'xortify_curl_connecttimeout',
											'title' => 'WORTIFY_CURL_CONNECTTIMOUT',
											'description' => 'WORTIFY_CURL_CONNECTTIMOUT_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 20,
											),
				"xortify_users_to_check" => array('name' => 'xortify_users_to_check',
										'title' => 'WORTIFY_USERSTOCHECK',
										'description' => 'WORTIFY_USERSTOCHECK_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 180,
										'options' => array(WORTIFY_RECORDS_177600 => 177600, WORTIFY_RECORDS_48800 => 48800, WORTIFY_RECORDS_24400 => 24400, WORTIFY_RECORDS_12200 => 12200,
												WORTIFY_RECORDS_3600 => 3600, WORTIFY_RECORDS_1800 => 1800, WORTIFY_RECORDS_1200 => 1200, WORTIFY_RECORDS_600 => 600,
												WORTIFY_RECORDS_300 => 300, WORTIFY_RECORDS_180 => 180, WORTIFY_RECORDS_60 => 60, WORTIFY_RECORDS_30 => 30, WORTIFY_DISABLED => 0),
										),
				"xortify_allowed_spams" => array('name' => 'xortify_allowed_spams',
											'title' => 'WORTIFY_ALLOWEDSPAMS',
											'description' => 'WORTIFY_ALLOWEDSPAMS_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 10,
											),
				"xortify_check_spams" => array('name' => 'xortify_check_spams',
										'title' => 'WORTIFY_CHECKSPAMS',
										'description' => 'WORTIFY_CHECKSPAMS_DESC',
										'formtype' => 'group_multi',
										'valuetype' => 'array',
										'default' => array(),
										),
				"xortify_min_words" =>  array('name' => 'xortify_min_words',
											'title' => 'WORTIFY_MINIMUMWORDS',
											'description' => 'WORTIFY_MINIMUMWORDS_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 40,
											),
				"xortify_min_words_groups" =>  array('name' => 'xortify_min_words_groups',
											'title' => 'WORTIFY_MINIMUMWORDSGROUPS',
											'description' => 'WORTIFY_MINIMUMWORDSGROUPS_DESC',
											'formtype' => 'group_multi',
											'valuetype' => 'array',
											'default' => array(),
											)
			),
			"ipinfodb" => array(
				"ipinfodb_api_url" => array('name' => 'ipinfodb_api_url',
						'title' => 'WORTIFY_IPINFODB_URL',
						'description' => 'WORTIFY_IPINFODB_URL_DESC',
						'formtype' => 'text',
						'valuetype' => 'text',
						'default' => ''
				),
				"ipinfodb_api_key" => array('name' => 'ipinfodb_api_key',
						'title' => 'WORTIFY_IPINFODB_KEY',
						'description' => 'WORTIFY_IPINFODB_KEY_DESC',
						'formtype' => 'text',
						'valuetype' => 'text',
						'default' => ''
				),
			),
			"alerts" => array(
				"alertEmails" => array('name' => 'alertEmails',
						'title' => 'WORTIFY_ALERT_EMAILS',
						'description' => 'WORTIFY_ALERT_EMAILS_DESC',
						'formtype' => 'textarea',
						'valuetype' => 'text',
						'default' => ''
				),
				"alertOn_warnings" => array('name' => 'alertOn_warnings',
						'title' => 'WORTIFY_ALERT_ON_WARNINGS',
						'description' => 'WORTIFY_ALERT_ON_WARNINGS_DESC',
						'formtype' => 'yesno',
						'valuetype' => 'integer',
						'default' => true
				),
				"alertOn_critical" => array('name' => 'alertOn_critical',
						'title' => 'WORTIFY_ALERT_ON_CRITICAL',
						'description' => 'WORTIFY_ALERT_ON_CRITICAL_DESC',
						'formtype' => 'yesno',
						'valuetype' => 'integer',
						'default' => true
				)
		)
	);
	
	function __construct() {
		self::$table = $GLOBALS['wpdb']->base_prefix . 'options';
	}
	
	public static function formElement($option_name, $formtype, $options = array(), $option_valueue = '', $valuetype = 'text') {	
		switch ($formtype) {
		
			case 'textarea':
				$myts =& WortifyTextSanitizer::getInstance();
				if ($valuetype == 'array') {
					// this is exceptional.. only when option_valueue type is arrayneed a smarter way for this
					$ele = ($option_valueue != '') ? new WortifyFormTextArea($title, $option_name, $myts->htmlspecialchars(implode('|', $option_valueue)), 5, 50) : new WortifyFormTextArea($title, $option_name, '', 5, 50);
				} else {
					$ele = new WortifyFormTextArea($title, $option_name, $myts->htmlspecialchars($option_valueue), 5, 50);
				}
				break;
		
			case 'select':
				$ele = new WortifyFormSelect($title, $option_name, $option_valueue);
				foreach($options as $key => $option_valueue) {
					$optoption_value = defined($option_valueue) ? constant($option_valueue) : $option_valueue;
					$optkey = defined($key) ? constant($key) : $key;
					$ele->addOption($optoption_value, $optkey);
				}
				break;
		
			case 'select_multi':
				$ele = new WortifyFormSelect($title, $option_name, $option_valueue, 5, true);
				foreach($options as $key => $option_valueue) {
					$optoption_value = defined($option_valueue) ? constant($option_valueue) : $option_valueue;
					$optkey = defined($key) ? constant($key) : $key;
					$ele->addOption($optoption_value, $optkey);
				}
				break;
		
			case 'yesno':
				$ele = new WortifyFormRadioYN($title, $option_name, $option_valueue, _YES, _NO);
				break;
		
			case 'timezone':
				$ele = new WortifyFormSelectTimezone($title, $option_name, $option_valueue);
				break;
		
			case 'group':
				$ele = new WortifyFormSelectGroup($title, $option_name, false, $option_valueue, 1, false);
				break;
		
			case 'group_multi':
				$ele = new WortifyFormSelectGroup($title, $option_name, true, $option_valueue, 5, true);
				break;
		
				// RMV-NOTIFY - added 'user' and 'user_multi'
			case 'user':
				$ele = new WortifyFormSelectUser($title, $option_name, false, $option_valueue, 1, false);
				break;
		
			case 'user_multi':
				$ele = new WortifyFormSelectUser($title, $option_name, false, $option_valueue, 5, true);
				break;
				
			case 'password':
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormPassword($title, $option_name, 50, 255, $myts->htmlspecialchars($option_valueue));
				break;
		
			case 'color':
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormColorPicker($title, $option_name, $myts->htmlspecialchars($option_valueue));
				break;
		
			case 'hidden':
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormHidden( $option_name, $myts->htmlspecialchars( $option_valueue ) );
				break;
		
			case 'textbox':
			default:
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormText($title, $option_name, 50, 255, $myts->htmlspecialchars($option_valueue));
				break;
		
		}
		if (is_object($ele))
			return $ele->render();
		return '';
	}
	
	public static function setDefaults(){
		foreach(self::$configs as $mode => $values) {
			foreach($values as $key => $option_value){
				if(self::get($key) === false){
					self::set($key, $option_value['default']);
				}
			}
		}
		self::set('encKey', substr(sha1(microtime(true)),0 ,16) );
		if(self::get('maxMem', false) === false ){
			self::set('maxMem', WORTIFY_MAX_MEMORY);
		}
	}
	public static function parseOptions(){
		$ret = array();
		foreach(self::$configs as $mode => $values) {
			foreach($values as $key => $option_value){
				$ret[$key] = isset($_REQUEST[$key]) ? $_POST[$key] : $option_value['default'];
			}
		}
		return $ret;
	}
	public static function setArray($arr){
		foreach($arr as $key => $option_value){
			self::set($key, $option_value);
		}
	}
	public static function clearCache(){
		self::$cache = array();
	}
	public static function getHTML($key){
		return htmlspecialchars(self::get($key));
	}
	public static function valuetype($key){
		if (isset(self::$configs['protector'][$key]))
			return self::$configs['protector'][$key]['valuetype'];
		elseif (isset(self::$configs['xortify'][$key]))
			return self::$configs['xortify'][$key]['valuetype'];
		return 'string';
	}
	public static function set($key, $option_value){
		switch (self::valuetype($key)) {
		case "array":
			$option_value = json_encode($option_value);
		}
		self::getDB()->queryF(sprintf("delete from " . self::table() . " WHERE option_name = '%s'", $key));
		self::getDB()->queryF(sprintf("insert into " . self::table() . " (option_name, option_value) values ('%s', '%s')", $key, $option_value));
		self::$cache[$key] = $option_value;
	}
	static $_non_cache_vars = array('xortify_username', 'xortify_password');
	public static function get($key, $default = false){
		if ($default == false) {
			if (isset(self::$configs['protector'][$key]))
				$default = self::$configs['protector'][$key]['default'];
			elseif (isset(self::$configs['xortify'][$key]))
				$default = self::$configs['xortify'][$key]['default'];
		}
		if(! isset(self::$cache[$key]) || in_array($key, self::$_non_cache_vars)){ 
			list($option_value) = self::getDB()->fetchRow(self::getDB()->query($sql = sprintf("select `option_value` from `" . self::table() . "` where `option_name`='%s'", $key)));
			switch (self::valuetype($key)) {
				case "array":
					$option_value = json_decode($option_value);
			}
			if(isset($option_value)){
				self::$cache[$key] = $option_value;
			} else {
				self::$cache[$key] = $default;
			}
		}
		if (empty(self::$cache[$key])) {
			if (isset(self::$configs['protector'][$key]))
				self::$cache[$key] = self::$configs['protector'][$key]['default'];
			elseif (isset(self::$configs['xortify'][$key]))
				self::$cache[$key] = self::$configs['xortify'][$key]['default'];
		}
		return self::$cache[$key];
	}
	
	public static function get_ser($key, $default, $canUseDisk = false){ //When using disk, reading a option_valueue deletes it.
		//If we can use disk, check if there are any values stored on disk first and read them instead of the DB if there are values
		if($canUseDisk){
			$fileoption_name = 'xortify_tmpfile_' . $key . '.php';
			$dir = self::getTempDir();
			if($dir){
				$obj = false;
				$foundFiles = false;
				$fullFile = $dir . $fileoption_name;
				if(file_exists($fullFile)){
					xortify::status(4, 'info', "Loading serialized data from file $fullFile");
					$obj = unserialize(substr(file_get_contents($fullFile), strlen(self::$tmpFileHeader))); //Strip off security header and unserialize
					if(! $obj){
						xortify::status(2, 'error', "Could not unserialize file $fullFile");
					}
					self::deleteOldTempFile($fullFile);
				}
				if($obj){ //If we managed to deserialize something, clean ALL tmp dirs of this file and return obj
					return $obj;
				}
			}
		}

		list($res) = self::getDB()->fetchRow(self::getDB()->query("select option_value from " . self::table() . " where option_name = '%s'", $key));
		self::getDB()->flush(); //clear cache
		if($res){
			return unserialize($res);
		}
		return $default;
	}
	public static function set_ser($key, $option_value, $canUseDisk = false){
		//We serialize some very big values so this is memory efficient. We don't make any copies of $option_value and don't use ON DUPLICATE KEY UPDATE
		// because we would have to concatenate $option_value twice into the query which could also exceed max packet for the mysql server
		$serialized = serialize($option_value);
		$option_value = '';
		$tempFileoption_name = 'xortify_tmpfile_' . $key . '.php';
		if((strlen($serialized) * 1.1) > self::getDB()->getMaxAllowedPacketBytes()){ //If it's greater than max_allowed_packet + 10% for escaping and SQL
			if($canUseDisk){
				$dir = self::getTempDir();
				$potentialDirs = self::getPotentialTempDirs();
				if($dir){
					$fh = false;
					$fullFile = $dir . $tempFileoption_name;
					self::deleteOldTempFile($fullFile);
					$fh = fopen($fullFile, 'w');
					if($fh){ 
						xortify::status(4, 'info', "Serialized data for $key is " . strlen($serialized) . " bytes and is greater than max_allowed packet so writing it to disk file: " . $fullFile);
					} else {
						xortify::status(1, 'error', "Your database doesn't allow big packets so we have to use files to store temporary data and Wortify can't find a place to write them. Either ask your admin to increase max_allowed_packet on your MySQL database, or make one of the following directories writable by your web server: " . implode(', ', $potentialDirs));
						return false;
					}
					fwrite($fh, self::$tmpFileHeader);
					fwrite($fh, $serialized);
					fclose($fh);
					return true;
				} else {
					xortify::status(1, 'error', "Your database doesn't allow big packets so we have to use files to store temporary data and Wortify can't find a place to write them. Either ask your admin to increase max_allowed_packet on your MySQL database, or make one of the following directories writable by your web server: " . implode(', ', $potentialDirs));
					return false;
				}
					
			} else {
				xortify::status(1, 'error', "Wortify tried to save a variable with option_name '$key' and your database max_allowed_packet is set to be too small. This particular variable can't be saved to disk. Please ask your administrator to increase max_allowed_packet. Thanks.");
				return false;
			}
		} else {
			//Delete temp files on disk or else the DB will be written to but get_ser will see files on disk and read them instead
			$tempDir = self::getTempDir();
			if($tempDir){
				self::deleteOldTempFile($tempDir . $tempFileoption_name);
			}
			list($exists) = self::getDB()->fetchRow(self::getDB()->query(sprintf("select option_name from " . self::table() . " where option_name='%s'", $key)));
			if($exists){
				self::getDB()->queryF(sprintf("update " . self::table() . " set option_value=%s where option_name=%s", $serialized, $key));
			} else {
				self::getDB()->queryF(sprintf("insert IGNORE into " . self::table() . " (option_name, option_value) values (%s, %s)", $key, $serialized));
			}
		}
		self::getDB()->flush();
		return true;
	}
	private static function deleteOldTempFile($fileoption_name){
		if(file_exists($fileoption_name)){
			unlink($fileoption_name);
		}
	}
	private static function getTempDir(){
		if(! self::$tmpDirCache){
			$dirs = self::getPotentialTempDirs();
			$finalDir = 'notmp';
			xortifyUtils::errorsOff();
			foreach($dirs as $dir){
				$dir = rtrim($dir, '/') . '/';
				$fh = fopen($dir . 'xortifytmptest.txt', 'w');
				if(! $fh){ continue; }
				$bytes = fwrite($fh, 'test');
				if($bytes != 4){ @fclose($fh); continue; }
				@fclose($fh);
				if(! @unlink($dir . 'xortifytmptest.txt')){ continue; }
				$finalDir = $dir;
				break;
			}
			xortifyUtils::errorsOn();
			self::$tmpDirCache = $finalDir;
		}
		if(self::$tmpDirCache == 'notmp'){
			return false;
		} else {
			return self::$tmpDirCache;
		}
	}
	private static function getPotentialTempDirs() {
		return array(WORTIFY_ROOT_PATH . '/wp-content/cache/xortify/', sys_get_temp_dir(), ABSPATH . 'wp-content/uploads/');
	}
	public static function f($key){
		echo esc_attr(self::get($key));
	}
	public static function cb($key){
		if(self::get($key)){
			echo ' checked ';
		}
	}
	public static function sel($key, $option_value, $isDefault = false){
		if((! self::get($key)) && $isDefault){ echo ' selected '; }
		if(self::get($key) == $option_value){ echo ' selected '; }
	}
	public static function getArray(){
		$ret = array();
		$q = self::getDB()->query("select option_name, option_value from " . self::table());
		foreach(self::getDB()->fetchArray($q) as $row){
			self::$cache[$row['option_name']] = $row['option_value'];
		}
		return self::$cache;
	}
	private static function getDB(){
		if(! self::$DB){ 
			self::$DB = WortifyDatabaseFactory::getDatabaseConnection($GLOBALS['wpdb']->dhr);
		}
		return self::$DB;
	}
	private static function table(){
		if(! self::$table){
			global $xortifydb;
			self::$table = $GLOBALS['wpdb']->base_prefix . 'options';
		}
		return self::$table;
	}
	public static function haveAlertEmails(){
		$emails = self::getAlertEmails();
		return sizeof($emails) > 0 ? true : false;
	}
	public static function getAlertEmails(){
		$dat = explode(',', self::get('alertEmails'));
		$emails = array();
		foreach($dat as $email){
			if(preg_match('/\@/', $email)){
				$emails[] = trim($email);
			}
		}
		return $emails;
	}
	public static function getAlertLevel(){
		if(self::get('alertOn_warnings')){
			return 2;
		} else if(self::get('alertOn_critical')){
			return 1;
		} else {
			return 0;
		}
	}
}
?>
