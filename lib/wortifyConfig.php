<?php

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyFormLoader.php';


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
				"wortify_id_forceintval" => array(
												'name'			=> 'wortify_id_forceintval' ,
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
			"wortify" => array(
				"wortify_username" => array('name' => 'wortify_username',
									'title' => 'WORTIFY_USERNAME',
									'description' => 'WORTIFY_USERNAME_DESC',
									'formtype' => 'text',
									'valuetype' => 'text',
									'default' => ''
									),
				"wortify_password" => array('name' => 'wortify_password',
											'title' => 'WORTIFY_PASSWORD',
											'description' => 'WORTIFY_PASSWORD_DESC',
											'formtype' => 'password',
											'valuetype' => 'text',
											'default' => '',
											),
				"wortify_seconds" => array('name' => 'wortify_seconds',
											'title' => 'WORTIFY_SECONDS',
											'description' => 'WORTIFY_SECONDS_DESC',
											'formtype' => 'select',
											'valuetype' => 'int',
											'default' => 3600,
											'options' => array(WORTIFY_SECONDS_37600 => 37600, WORTIFY_SECONDS_28800 => 28800, WORTIFY_SECONDS_14400 => 14400, WORTIFY_SECONDS_7200 => 7200,
		  														WORTIFY_SECONDS_3600 => 3600, WORTIFY_SECONDS_1800 => 1800, WORTIFY_SECONDS_1200 => 1200, WORTIFY_SECONDS_600 => 600,
																WORTIFY_SECONDS_300 => 300, WORTIFY_SECONDS_180 => 180, WORTIFY_SECONDS_60 => 60, WORTIFY_SECONDS_30 => 30)											
											),
				"wortify_ip_cache" => array('name' => 'wortify_ip_cache',
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
				"wortify_server_cache" => array('name' => 'wortify_server_cache',
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
				"wortify_records" => array('name' => 'wortify_records',
										'title' => 'WORTIFY_RECORDS',
										'description' => 'WORTIFY_RECORDS_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 3600,
										'options' => array(WORTIFY_RECORDS_177600 => 177600, WORTIFY_RECORDS_48800 => 48800, WORTIFY_RECORDS_24400 => 24400, WORTIFY_RECORDS_12200 => 12200,
																					WORTIFY_RECORDS_3600 => 3600, WORTIFY_RECORDS_1800 => 1800, WORTIFY_RECORDS_1200 => 1200, WORTIFY_RECORDS_600 => 600,
																					WORTIFY_RECORDS_300 => 300, WORTIFY_RECORDS_180 => 180, WORTIFY_RECORDS_60 => 60, WORTIFY_RECORDS_30 => 30),
																					
										),
				"wortify_providers" => array('name' => 'wortify_providers',
											'title' => 'WORTIFY_PROVIDERS',
											'description' => 'WORTIFY_PROVIDERS_DESC',
											'formtype' => 'select_multi',
											'valuetype' => 'array',
											'default' => array('wortify', 'protector', 'stopforumspam.com', 'projecthoneypot.org'),
											'options' => array('(none)' => '', WORTIFY_PROVIDER_WORTIFY => 'wortify', WORTIFY_PROVIDER_PROTECTOR => 'protector', WORTIFY_PROVIDER_STOPFORUMSPAM => 'stopforumspam.com', WORTIFY_PROVIDER_PROJECTHONEYPOT => 'projecthoneypot.org')
											),
				"wortify_protocol" => array('name' => 'wortify_protocol',
										'title' => 'WORTIFY_PROTOCOL',
										'description' => 'WORTIFY_PROTOCOL_DESC',
										'formtype' => 'select',
										'valuetype' => 'text',
										'default' => WORTITY_DEFAULT_API_METHOD,
										'options' => WORTITY_OPTIONS_API_METHOD,
										),
				"wortify_urirest" => array('name' => 'wortify_urirest',
								'title' => 'WORTIFY_URIREST',
								'description' => 'WORTIFY_URIREST_DESC',
								'formtype' => 'text',
								'valuetype' => 'text',
								'default' => WORTIFY_API_URL_WORTIFY,
								),
				"wortify_save_banned" => array('name' => 'wortify_save_banned',
									'title' => 'WORTIFY_LOG_BANNED',
									'description' => 'WORTIFY_LOG_BANNED_DESC',
									'formtype' => 'yesno',
									'valuetype' => 'int',
									'default' => true,
									),
				"wortify_save_blocked" => array('name' => 'wortify_save_blocked',
									'title' => 'WORTIFY_LOG_BLOCKED',
									'description' => 'WORTIFY_LOG_BLOCKED_DESC',
									'formtype' => 'yesno',
									'valuetype' => 'int',
									'default' => true,
									),
				"wortify_save_monitored" => array('name' => 'wortify_save_monitored',
								'title' => 'WORTIFY_LOG_MONITORED',
								'description' => 'WORTIFY_LOG_MONITORED_DESC',
								'formtype' => 'yesno',
								'valuetype' => 'int',
								'default' => true
								) ,
				"wortify_octeta" => array('name' => 'wortify_octeta',
							'title' => 'WORTIFY_PHP_NUMBEROFDAYS',
							'description' => 'WORTIFY_PHP_NUMBEROFDAYS_DESC',
							'formtype' => 'select',
							'valuetype' => 'int',
							'default' => 92,
							'options' => WORTIFY_OPTIONS_PHP_DAYS,
							),
				"wortify_octetb" => array('name' => 'wortify_octetb',
									'title' => 'WORTIFY_PHP_SEVERITYLEVEL',
									'description' => 'WORTIFY_PHP_SEVERITYLEVEL_DESC',
									'formtype' => 'select',
									'valuetype' => 'int',
									'default' => 15,
									'options' => WORTIFY_OPTIONS_PHP_SEVERITY,
									),
				"wortify_octetc" => array('name' => 'wortify_octetc',
										'title' => 'WORTIFY_PHP_SCANMODE',
										'description' => 'WORTIFY_PHP_SCANMODE_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 2,
										'options' => array(WORTIFY_PHP_SCANMODE_SUSPICIOUS => 1, WORTIFY_PHP_SCANMODE_HARVESTER => 2, WORTIFY_PHP_SCANMODE_SUSICIOUSHARVESTER => 3, 
																					 WORTIFY_PHP_SCANMODE_SPAMMER => 4, WORTIFY_PHP_SCANMODE_SUSPICIOUSSPAMMER => 5, WORTIFY_PHP_SCANMODE_HARVESTERSPAMMER => 6, 
																					 WORTIFY_PHP_SCANMODE_SUSPICIOUSHARVESTERSPAMMER => 7)
										),
				"wortify_email_freq" => array('name' => 'wortify_email_freq',
									'title' => 'WORTIFY_SFS_EMAILFREQUENCY',
									'description' => 'WORTIFY_SFS_EMAILFREQUENCY_DESC',
									'formtype' => 'select',
									'valuetype' => 'int',
									'default' => 2,
									'options' => WORTIFY_OPTIONS_SFS_EMAIILFREQ,
									),
				"wortify_email_lastseen" => array('name' => 'wortify_email_lastseen',
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
				"wortify_uname_freq" => array('name' => 'wortify_uname_freq',
										'title' => 'WORTIFY_SFS_UNAMEFREQUENCY',
										'description' => 'WORTIFY_SFS_UNAMEFREQUENCY_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 2,
										'options' => WORTIFY_OPTIONS_SFS_UNAMEFREQ,
										
										),
				"wortify_uname_lastseen" =>  array('name' => 'wortify_uname_lastseen',
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
				"wortify_ip_freq" => array('name' => 'wortify_ip_freq',
										'title' => 'WORTIFY_SFS_IPFREQUENCY',
										'description' => 'WORTIFY_SFS_IPFREQUENCY_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 2,
										'options' => WORTIFY_OPTIONS_SFS_IPFREQ,
										),
				"wortify_ip_lastseen" => array('name' => 'wortify_ip_lastseen',
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
				"wortify_logdrops" => array('name' => 'wortify_logdrops',
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
				"wortify_fault_delay" => array('name' => 'wortify_fault_delay',
											'title' => 'WORTIFY_FAULT_DELAY',
											'description' => 'WORTIFY_FAULT_DELAY_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 600
											),
				"wortify_curl_timeout" => array('name' => 'wortify_curl_timeout',
										'title' => 'WORTIFY_CURL_TIMOUT',
										'description' => 'WORTIFY_CURL_TIMOUT_DESC',
										'formtype' => 'text',
										'valuetype' => 'int',
										'default' => 40,
										
										),
				"wortify_curl_connecttimeout" =>  array('name' => 'wortify_curl_connecttimeout',
											'title' => 'WORTIFY_CURL_CONNECTTIMOUT',
											'description' => 'WORTIFY_CURL_CONNECTTIMOUT_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 20,
											),
				"wortify_users_to_check" => array('name' => 'wortify_users_to_check',
										'title' => 'WORTIFY_USERSTOCHECK',
										'description' => 'WORTIFY_USERSTOCHECK_DESC',
										'formtype' => 'select',
										'valuetype' => 'int',
										'default' => 180,
										'options' => array(WORTIFY_RECORDS_177600 => 177600, WORTIFY_RECORDS_48800 => 48800, WORTIFY_RECORDS_24400 => 24400, WORTIFY_RECORDS_12200 => 12200,
												WORTIFY_RECORDS_3600 => 3600, WORTIFY_RECORDS_1800 => 1800, WORTIFY_RECORDS_1200 => 1200, WORTIFY_RECORDS_600 => 600,
												WORTIFY_RECORDS_300 => 300, WORTIFY_RECORDS_180 => 180, WORTIFY_RECORDS_60 => 60, WORTIFY_RECORDS_30 => 30, WORTIFY_DISABLED => 0),
										),
				"wortify_allowed_spams" => array('name' => 'wortify_allowed_spams',
											'title' => 'WORTIFY_ALLOWEDSPAMS',
											'description' => 'WORTIFY_ALLOWEDSPAMS_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 10,
											),
				"wortify_check_spams" => array('name' => 'wortify_check_spams',
										'title' => 'WORTIFY_CHECKSPAMS',
										'description' => 'WORTIFY_CHECKSPAMS_DESC',
										'formtype' => 'group_multi',
										'valuetype' => 'array',
										'default' => array(),
										),
				"wortify_min_words" =>  array('name' => 'wortify_min_words',
											'title' => 'WORTIFY_MINIMUMWORDS',
											'description' => 'WORTIFY_MINIMUMWORDS_DESC',
											'formtype' => 'text',
											'valuetype' => 'int',
											'default' => 40,
											),
				"wortify_min_words_groups" =>  array('name' => 'wortify_min_words_groups',
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
	
	public static function formElement($name, $formtype, $options = array(), $value) {	
		switch ($formtype) {
		
			case 'textarea':
				$myts =& WortifyTextSanitizer::getInstance();
				if ($config[$i]->getVar('conf_valuetype') == 'array') {
					// this is exceptional.. only when value type is arrayneed a smarter way for this
					$ele = ($config[$i]->getVar('conf_value') != '') ? new WortifyFormTextArea($title, $name, $myts->htmlspecialchars(implode('|', $value)), 5, 50) : new WortifyFormTextArea($title, $name, '', 5, 50);
				} else {
					$ele = new WortifyFormTextArea($title, $name, $myts->htmlspecialchars($value), 5, 50);
				}
				break;
		
			case 'select':
				$ele = new WortifyFormSelect($title, $name, $value);
				$opcount = count($options);
				for ($j = 0; $j < $opcount; $j++) {
					$optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
					$optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
					$ele->addOption($optval, $optkey);
				}
				break;
		
			case 'select_multi':
				$ele = new WortifyFormSelect($title, $name, $value, 5, true);
				$opcount = count($options);
				for ($j = 0; $j < $opcount; $j++) {
					$optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
					$optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
					$ele->addOption($optval, $optkey);
				}
				break;
		
			case 'yesno':
				$ele = new WortifyFormRadioYN($title, $name, $value, _YES, _NO);
				break;
		
			case 'timezone':
				$ele = new WortifyFormSelectTimezone($title, $name, $value);
				break;
		
			case 'group':
				$ele = new WortifyFormSelectGroup($title, $name, false, $value, 1, false);
				break;
		
			case 'group_multi':
				$ele = new WortifyFormSelectGroup($title, $name, true, $value, 5, true);
				break;
		
				// RMV-NOTIFY - added 'user' and 'user_multi'
			case 'user':
				$ele = new WortifyFormSelectUser($title, $name, false, $value, 1, false);
				break;
		
			case 'user_multi':
				$ele = new WortifyFormSelectUser($title, $name, false, $value, 5, true);
				break;
				
			case 'password':
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormPassword($title, $name, 50, 255, $myts->htmlspecialchars($value));
				break;
		
			case 'color':
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormColorPicker($title, $name, $myts->htmlspecialchars($value));
				break;
		
			case 'hidden':
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormHidden( $name, $myts->htmlspecialchars( $value ) );
				break;
		
			case 'textbox':
			default:
				$myts =& WortifyTextSanitizer::getInstance();
				$ele = new WortifyFormText($title, $name, 50, 255, $myts->htmlspecialchars($value));
				break;
		
		}
		if (is_object($ele))
			return $ele->render();
		return '';
	}
	
	public static function setDefaults(){
		foreach(self::$configs as $mode => $values) {
			foreach($values as $key => $val){
				if(self::get($key) === false){
					self::set($key, $val['default']);
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
			foreach($values as $key => $val){
				$ret[$key] = isset($_REQUEST[$key]) ? $_POST[$key] : $val['default'];
			}
		}
		return $ret;
	}
	public static function setArray($arr){
		foreach($arr as $key => $val){
			self::set($key, $val);
		}
	}
	public static function clearCache(){
		self::$cache = array();
	}
	public static function getHTML($key){
		return htmlspecialchars(self::get($key));
	}
	public static function set($key, $val){
		if(is_array($val)){
			$msg = "wortifyConfig::set() got an array as second param with key: $key and value: " . var_export($val, true);
			wortify::status(1, 'error', $msg);
			return;
		}
		self::getDB()->queryWrite("insert into " . self::table() . " (name, val) values ('%s', '%s') ON DUPLICATE KEY UPDATE val='%s'", $key, $val, $val);
		self::$cache[$key] = $val;
	}
	public static function get($key, $default = false){
		if(! isset(self::$cache[$key])){ 
			$val = self::getDB()->querySingle("select val from " . self::table() . " where name='%s'", $key);
			if(isset($val)){
				self::$cache[$key] = $val;
			} else {
				self::$cache[$key] = $default;
			}
		}
		return self::$cache[$key];
	}
	public static function get_ser($key, $default, $canUseDisk = false){ //When using disk, reading a value deletes it.
		//If we can use disk, check if there are any values stored on disk first and read them instead of the DB if there are values
		if($canUseDisk){
			$filename = 'wortify_tmpfile_' . $key . '.php';
			$dir = self::getTempDir();
			if($dir){
				$obj = false;
				$foundFiles = false;
				$fullFile = $dir . $filename;
				if(file_exists($fullFile)){
					wortify::status(4, 'info', "Loading serialized data from file $fullFile");
					$obj = unserialize(substr(file_get_contents($fullFile), strlen(self::$tmpFileHeader))); //Strip off security header and unserialize
					if(! $obj){
						wortify::status(2, 'error', "Could not unserialize file $fullFile");
					}
					self::deleteOldTempFile($fullFile);
				}
				if($obj){ //If we managed to deserialize something, clean ALL tmp dirs of this file and return obj
					return $obj;
				}
			}
		}

		$res = self::getDB()->querySingle("select val from " . self::table() . " where name=%s", $key);
		self::getDB()->flush(); //clear cache
		if($res){
			return unserialize($res);
		}
		return $default;
	}
	public static function set_ser($key, $val, $canUseDisk = false){
		//We serialize some very big values so this is memory efficient. We don't make any copies of $val and don't use ON DUPLICATE KEY UPDATE
		// because we would have to concatenate $val twice into the query which could also exceed max packet for the mysql server
		$serialized = serialize($val);
		$val = '';
		$tempFilename = 'wortify_tmpfile_' . $key . '.php';
		if((strlen($serialized) * 1.1) > self::getDB()->getMaxAllowedPacketBytes()){ //If it's greater than max_allowed_packet + 10% for escaping and SQL
			if($canUseDisk){
				$dir = self::getTempDir();
				$potentialDirs = self::getPotentialTempDirs();
				if($dir){
					$fh = false;
					$fullFile = $dir . $tempFilename;
					self::deleteOldTempFile($fullFile);
					$fh = fopen($fullFile, 'w');
					if($fh){ 
						wortify::status(4, 'info', "Serialized data for $key is " . strlen($serialized) . " bytes and is greater than max_allowed packet so writing it to disk file: " . $fullFile);
					} else {
						wortify::status(1, 'error', "Your database doesn't allow big packets so we have to use files to store temporary data and Wortify can't find a place to write them. Either ask your admin to increase max_allowed_packet on your MySQL database, or make one of the following directories writable by your web server: " . implode(', ', $potentialDirs));
						return false;
					}
					fwrite($fh, self::$tmpFileHeader);
					fwrite($fh, $serialized);
					fclose($fh);
					return true;
				} else {
					wortify::status(1, 'error', "Your database doesn't allow big packets so we have to use files to store temporary data and Wortify can't find a place to write them. Either ask your admin to increase max_allowed_packet on your MySQL database, or make one of the following directories writable by your web server: " . implode(', ', $potentialDirs));
					return false;
				}
					
			} else {
				wortify::status(1, 'error', "Wortify tried to save a variable with name '$key' and your database max_allowed_packet is set to be too small. This particular variable can't be saved to disk. Please ask your administrator to increase max_allowed_packet. Thanks.");
				return false;
			}
		} else {
			//Delete temp files on disk or else the DB will be written to but get_ser will see files on disk and read them instead
			$tempDir = self::getTempDir();
			if($tempDir){
				self::deleteOldTempFile($tempDir . $tempFilename);
			}
			$exists = self::getDB()->querySingle("select name from " . self::table() . " where name='%s'", $key);
			if($exists){
				self::getDB()->queryWrite("update " . self::table() . " set val=%s where name=%s", $serialized, $key);
			} else {
				self::getDB()->queryWrite("insert IGNORE into " . self::table() . " (name, val) values (%s, %s)", $key, $serialized);
			}
		}
		self::getDB()->flush();
		return true;
	}
	private static function deleteOldTempFile($filename){
		if(file_exists($filename)){
			unlink($filename);
		}
	}
	private static function getTempDir(){
		if(! self::$tmpDirCache){
			$dirs = self::getPotentialTempDirs();
			$finalDir = 'notmp';
			wortifyUtils::errorsOff();
			foreach($dirs as $dir){
				$dir = rtrim($dir, '/') . '/';
				$fh = @fopen($dir . 'wortifytmptest.txt', 'w');
				if(! $fh){ continue; }
				$bytes = @fwrite($fh, 'test');
				if($bytes != 4){ @fclose($fh); continue; }
				@fclose($fh);
				if(! @unlink($dir . 'wortifytmptest.txt')){ continue; }
				$finalDir = $dir;
				break;
			}
			wortifyUtils::errorsOn();
			self::$tmpDirCache = $finalDir;
		}
		if(self::$tmpDirCache == 'notmp'){
			return false;
		} else {
			return self::$tmpDirCache;
		}
	}
	private static function getPotentialTempDirs() {
		return array(WORTIFY_ROOT_PATH . '/wp-content/cache/wortify/', sys_get_temp_dir(), ABSPATH . 'wp-content/uploads/');
	}
	public static function f($key){
		echo esc_attr(self::get($key));
	}
	public static function cb($key){
		if(self::get($key)){
			echo ' checked ';
		}
	}
	public static function sel($key, $val, $isDefault = false){
		if((! self::get($key)) && $isDefault){ echo ' selected '; }
		if(self::get($key) == $val){ echo ' selected '; }
	}
	public static function getArray(){
		$ret = array();
		$q = self::getDB()->querySelect("select name, val from " . self::table());
		foreach($q as $row){
			self::$cache[$row['name']] = $row['val'];
		}
		return self::$cache;
	}
	private static function getDB(){
		if(! self::$DB){ 
			self::$DB = new wortifydb();
		}
		return self::$DB;
	}
	private static function table(){
		if(! self::$table){
			global $wortifydb;
			self::$table = DB_PREFIX . 'options';
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
