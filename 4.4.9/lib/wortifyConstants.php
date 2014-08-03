<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyFunctions.php';
define('DS', DIRECTORY_SEPARATOR);
define('NWLINE', "\n");

// Set constants
define('WORTIFY_VAR_PATH', dirname(dirname(__FILE__)));
define('WORTIFY_ROOT_PATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
define('WORTIFY_MAX_MEMORY', '256');
define('WORTIFY_URL', site_url() . '/wp-content/plugins/wortify/');
define('WORTIFY_API_URL_WORTIFY', 'https://xortify.com/api/');
define('WORTIFY_API_URL_IPINFODB', 'https://lookups.labs.coop/');
define('WORTIFY_API_URL_PLACES', 'https://places.labs.coop/');
define('WORTIFY_API_URL_WHOIS', 'https://whois.labs.coop/');
define('DB_TYPE', 'mysql');
define('WORTIFY_VERSION', '4.01');

// Protector Admin
// index.php
define("WORTIFT_AM_TH_DATETIME","Time");
define("WORTIFT_AM_TH_USER","User");
define("WORTIFT_AM_TH_IP","IP");
define("WORTIFT_AM_TH_AGENT","AGENT");
define("WORTIFT_AM_TH_TYPE","Type");
define("WORTIFT_AM_TH_DESCRIPTION","Description");

define("WORTIFT_AM_TH_BADIPS" , 'Bad IPs<br /><br /><span style="font-weight:normal;">Write each IP a line<br />blank means all IPs are allowed</span>');

define("WORTIFT_AM_TH_GROUP1IPS" , 'Allowed IPs for Group=1<br /><br /><span style="font-weight:normal;">Write each IP a line.<br />192.168. means 192.168.*<br />blank means all IPs are allowed</span>');

define("WORTIFT_AM_LABEL_COMPACTLOG" , "Compact log");
define("WORTIFT_AM_BUTTON_COMPACTLOG" , "Compact it!");
define("WORTIFT_AM_JS_COMPACTLOGCONFIRM" , "Duplicated (IP,Type) records will be removed");
define("WORTIFT_AM_LABEL_REMOVEALL" , "Remove all records");
define("WORTIFT_AM_BUTTON_REMOVEALL" , "Remove all!");
define("WORTIFT_AM_JS_REMOVEALLCONFIRM" , "All logs are removed absolutely. Are you really OK?");
define("WORTIFT_AM_LABEL_REMOVE" , "Remove the records checked:");
define("WORTIFT_AM_BUTTON_REMOVE" , "Remove!");
define("WORTIFT_AM_JS_REMOVECONFIRM" , "Remove OK?");
define("WORTIFT_AM_MSG_IPFILESUPDATED" , "Files for IPs have been updated");
define("WORTIFT_AM_MSG_BADIPSCANTOPEN" , "The file for bad IP cannot be opened");
define("WORTIFT_AM_MSG_GROUP1IPSCANTOPEN" , "The file for allowing group=1 cannot be opened");
define("WORTIFT_AM_MSG_REMOVED" , "Records are removed");
define("WORTIFT_AM_FMT_CONFIGSNOTWRITABLE" , "Turn the configs directory writable: %s");


// prefix_manager.php
define("WORTIFT_AM_H3_PREFIXMAN" , "Prefix Manager");
define("WORTIFT_AM_MSG_DBUPDATED" , "Database Updated Successfully!");
define("WORTIFT_AM_CONFIRM_DELETE" , "All data will be dropped. OK?");
define("WORTIFT_AM_TXT_HOWTOCHANGEDB" , "If you want to change prefix,<br /> edit %s/mainfile.php manually.<br /><br />define('XOOPS_DB_PREFIX', '<b>%s</b>');");


// advisory.php
define("WORTIFT_AM_ADV_NOTSECURE","Not secure");

define("WORTIFT_AM_ADV_TRUSTPATHPUBLIC","If you can see an image -NG- or the link returns normal page, your XOOPS_TRUST_PATH is not placed properly. The best place for XOOPS_TRUST_PATH is outside of DocumentRoot. If you cannot do that, you have to put .htaccess (DENY FROM ALL) just under XOOPS_TRUST_PATH as the second best way.");
define("WORTIFT_AM_ADV_TRUSTPATHPUBLICLINK","Check that PHP files inside TRUST_PATH are set to read-only (it must be 404,403 or 500 error)");
define("WORTIFT_AM_ADV_REGISTERGLOBALS","If 'ON', this setting invites a variety of injecting attacks. If you can, set 'register_globals off' in php.ini, or if not possible, create or edit .htaccess in your XOOPS directory:");
define("WORTIFT_AM_ADV_ALLOWURLFOPEN","If 'ON', this setting allows attackers to execute arbitrary scripts on remote servers.<br />Only administrator can change this option.<br />If you are an admin, edit php.ini or httpd.conf.<br /><b>Sample of httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />Else, claim it to your administrators.");
define("WORTIFT_AM_ADV_USETRANSSID","If 'ON', your Session ID will be displayed in anchor tags etc.<br />To prevent session hi-jacking, add a line into .htaccess in XOOPS_ROOT_PATH.<br /><b>php_flag session.use_trans_sid off</b>");
define("WORTIFT_AM_ADV_DBPREFIX","This setting invites 'SQL Injections'.<br />Don't forget turning 'Force sanitizing *' ON in this module's preferences.");
define("WORTIFT_AM_ADV_LINK_TO_PREFIXMAN","Go to prefix manager");
define("WORTIFT_AM_ADV_MAINUNPATCHED","You should edit your mainfile.php like written in README.");
define("WORTIFT_AM_ADV_DBFACTORYPATCHED","Your databasefactory is ready for DBLayer Trapping anti-SQL-Injection");
define("WORTIFT_AM_ADV_DBFACTORYUNPATCHED","Your databasefactory is not ready for DBLayer Trapping anti-SQL-Injection. Some patches are required.");

define("WORTIFT_AM_ADV_SUBTITLECHECK","Check if Protector works well");
define("WORTIFT_AM_ADV_CHECKCONTAMI","Contamination");
define("WORTIFT_AM_ADV_CHECKISOCOM","Isolated Comments");

//XOOPS 2.5.4
define("WORTIFT_AM_ADV_REGISTERGLOBALS2","and place in it the line below:");

//Signup form
define('WORTIFY_ADMIN_USER_CREATED_H1', 'The User "%s" ~ was successfully registered on the service node!');
define('WORTIFY_ADMIN_NONETWORKCOMM_DISCLAIMER', 'Network did not provide Disclaimer for signing up!');
define('WORTIFY_ADMIN_SIGNUP_XORTIFY_H2', 'Sign-up to the Xortify.com cloud for full services!');
define('WORTIFY_ADMIN_SIGNUP_XORTIFY_P', 'This form will allow you to sign-up to the Xortify cloud for full services from this software!');
define('WORTIFY_ADMIN_ERROR_OCCURED', 'API Error Occurred');
define('WORTIFY_ADMIN_ERROR_URL', 'API URL Reference');
define('WORTIFY_ADMIN_ERROR_PROTOCOL', 'API Protocol Selected Currently');

define('_SUBMIT', 'Submit & Save');
define('_GO', 'Go...');
define('_YES', 'Yes');
define('_NO', 'No');
define('_NONE', 'None');
define('WORTIFY_AM_NOCACHEMSG', '<div style="font-size: 1.99em; color:red; margin-top:45px;"><center>Absolutely no ban\'s current hosted on Cloud Service!</center></div>');
define('WORTIFY_MI_PHP_DAYS', '%s day(s)');
define('WORTIFY_MI_PHP_SEVERITY', '%s percent');
define('WORTIFY_ADMIN_IPTYPE_IP4', 'IPv4');
define('WORTIFY_ADMIN_IPTYPE_IP6', 'IPv6');
define('WORTIFY_ADMIN_IPTYPE_EMPTY', 'No IP');
define('WORTIFY_ADMIN_IPTYPE', 'IP Type');
define('WORTIFY_ADMIN_IPADDRESS', 'IP Address');
define('WORTIFY_ADMIN_NETADDRESS', 'Network Address');
define('WORTIFY_ADMIN_PROXYIP', 'Proxy IP');
define('WORTIFY_ADMIN_MACADDRESS', 'Mac Address');
define('WORTIFY_ADMIN_LONG', 'Network Pointer');
define('WORTIFY_ADMIN_BANS', 'Current Network Bans');
define('WORTIFY_ADMIN_NOCACHEMSG', '<center><font size="+2">EMPTY BAN CACHE!! NOTHING TO DISPLAY!!</font></center>');
define('WORTIFY_ADMIN_LOG_H1', 'Wortify Usage Log');
define('WORTIFY_ADMIN_LOG_P', 'This is the actions that have occured on your system in the last period up unto the log drop date!');
define('WORTIFY_ADMIN_TH_ACTION', 'Action');
define('WORTIFY_ADMIN_TH_PROVIDER', 'Provider');
define('WORTIFY_ADMIN_TH_DATE', 'Date');
define('WORTIFY_ADMIN_TH_UNAME', 'Username');
define('WORTIFY_ADMIN_TH_EMAIL', 'Email');
define('WORTIFY_ADMIN_TH_IP4', 'Ip4');
define('WORTIFY_ADMIN_TH_IP6', 'IP6');
define('WORTIFY_ADMIN_TH_PROXY_IP4', 'Proxy IP4');
define('WORTIFY_ADMIN_TH_PROXY_IP6', 'Proxy IP6');
define('WORTIFY_ADMIN_TH_NETWORK_ADDY', 'Netbios Address');
define('WORTIFY_ADMIN_TH_AGENT', 'User Agent');
define('WORTIFY_ADMIN_THEREARE_WHENCLEANED', 'Wortify File Cache Cleaned Last: %s');
define('WORTIFY_ADMIN_THEREARE_CLEANINGTOOK', 'Wortify File Cache Cleaning Took: %s seconds');
define('WORTIFY_ADMIN_THEREARE_FILESDELETED', 'Wortify Cache Files Deleted: %s');
define('WORTIFY_ADMIN_THEREARE_BYTESSAVED', 'Wortify Cache Cleaning Saved: %s bytes');
define('WORTIFY_ADMIN_CATEGORY', 'Ban Category');
define('WORTIFY_ADMIN_TYPE', 'Ban Type');
define('WORTIFY_ADMIN_BANID', 'Ban Id:');

//Ban Notices
define('WORTIFY_BAN_PHP_TYPE','Project Honey Pot Ban - Details:');
define('WORTIFY_BAN_PHP_OCTETA','Number of Days');
define('WORTIFY_BAN_PHP_OCTETB','Severity');
define('WORTIFY_BAN_PHP_OCTETC','Scan Mode');
define('WORTIFY_BAN_SPIDER_TYPE','Spider Block - $_POST Variables are:');
define('WORTIFY_BAN_SFS_TYPE','Stop Forum Spam Ban - Details:');
define('WORTIFY_BAN_SFS_EMAIL_FREQ','Email Frequency');
define('WORTIFY_BAN_SFS_EMAIL_LASTSEEN','Email Last Seen');
define('WORTIFY_BAN_SFS_USERNAME_FREQ','Usename Frequency');
define('WORTIFY_BAN_SFS_USERNAME_LASTSEEN','Username Last Seen');
define('WORTIFY_BAN_SFS_IP_FREQ','IP Frequency');
define('WORTIFY_BAN_SFS_IP_LASTSEEN','IP Last Seen');
define('WORTIFY_BANWORTIFYT_KEY', 'Field');
define('WORTIFY_BANWORTIFYT_MATCH', 'Match');
define('WORTIFY_BANWORTIFYT_LENGTH', 'Length');
define('WORTIFY_WORDS', 'Minimum Word Criteria');
define('WORTIFY_SPAM', 'Spam Heuristics');
define('WORTIFY_WORDS_PAGETITLE', 'Minimum Word Criteria not met!');
define('WORTIFY_SPAM_PAGETITLE', 'Spam Heuristics Flagged!');
define('WORTIFY_WORDS_DESCRIPTION', '<p align="center"><img align="middle" src="'.XOOPS_URL.'/modules/xortify/images/accessdenied.png"></p><p align="center" style="font-size:18px">You have been blocked from our site by Xortify, this is because you didn\'t meet the minimum word includements for a submissions; you cannot be banned for this you will have to press the back button and try again!</p>');
define('WORTIFY_SPAM_DESCRIPTION', '<p align="center"><img align="middle" src="'.XOOPS_URL.'/modules/xortify/images/accessdenied.png"></p><p align="center" style="font-size:18px">You have been blocked for submitted spam by our spam heuristic checking system Xortify, this is possibly done by one of our third parties like Stop Forum Spam or Project Honeypot, you will have to check the provider of the ban!</p><div style="clear:both; height: 25px;">&nbsp;</div><p align="center" style="font-size:14px">You can occur an IP Bans in a certain number of these; that is a ban that is based on the provider "Xortify" are with your IP Can be removed by going to <a href="http://wortify.com/modules/unban/">XORTIFY DOT COM</a><br/><br/>This ban is not permenant and will drop in 3 months!</p>');

// Sets Language Constants for Configuration
define('WORTIFY_GLOBAL_DISBL','Temporary disabled');
define('WORTIFY_GLOBAL_DISBLDSC','All protections are disabled in temporary.<br />Don\'t forget turn this off after shooting the trouble');
define('WORTIFY_RELIABLE_IPS','Reliable IPs');
define('WORTIFY_RELIABLE_IPSDSC','set IPs you can rely separated with | . ^ matches the head of string, $ matches the tail of string.');
define('WORTIFY_LOG_LEVEL','Logging level');
define('WORTIFY_LOG_LEVELDSC','');
define('WORTIFY_BANIP_TIME0','Banned IP suspension time (sec)');
define('WORTIFY_LOGLEVEL0','none');
define('WORTIFY_LOGLEVEL15','Quiet');
define('WORTIFY_LOGLEVEL63','quiet');
define('WORTIFY_LOGLEVEL255','full');
define('WORTIFY_HIJACK_TOPBIT','Protected IP bits for the session');
define('WORTIFY_HIJACK_TOPBITDSC','Anti Session Hi-Jacking:<br />Default 32(bit). (All bits are protected)<br />When your IP is not stable, set the IP range by number of the bits.<br />(eg) If your IP can move in the range of 192.168.0.0-192.168.0.255, set 24(bit) here');
define('WORTIFY_HIJACK_DENYGP','Groups disallowed IP moving in a session');
define('WORTIFY_HIJACK_DENYGPDSC','Anti Session Hi-Jacking:<br />Select groups which is disallowed to move their IP in a session.<br />(I recommend to turn Administrator on.)');
define('WORTIFY_SAN_NULLBYTE','Sanitizing null-bytes');
define('WORTIFY_SAN_NULLBYTEDSC','The terminating character "\\0" is often used in malicious attacks.<br />a null-byte will be changed to a space.<br />(highly recommended as On)');
define('WORTIFY_DIE_NULLBYTE','Exit if null bytes are found');
define('WORTIFY_DIE_NULLBYTEDSC','The terminating character "\\0" is often used in malicious attacks.<br />(highly recommended as On)');
define('WORTIFY_DIE_BADEXT','Exit if bad files are uploaded');
define('WORTIFY_DIE_BADEXTDSC','If someone tries to upload files which have bad extensions like .php , this module exits your WORTIFY.<br />If you often attach php files into B-Wiki or PukiWikiMod, turn this off.');
define('WORTIFY_CONTAMI_ACTION','Action if a contamination is found');
define('WORTIFY_CONTAMI_ACTIONDS','Select the action when someone tries to contaminate system global variables into your WORTIFY.<br />(recommended option is blank screen)');
define('WORTIFY_ISOCOM_ACTION','Action if an isolated comment-in is found');
define('WORTIFY_ISOCOM_ACTIONDSC','Anti SQL Injection:<br />Select the action when an isolated "/*" is found.<br />"Sanitizing" means adding another "*/" in tail.<br />(recommended option is Sanitizing)');
define('WORTIFY_UNION_ACTION','Action if a UNION is found');
define('WORTIFY_UNION_ACTIONDSC','Anti SQL Injection:<br />Select the action when some syntax like UNION of SQL.<br />"Sanitizing" means changing "union" to "uni-on".<br />(recommended option is Sanitizing)');
define('WORTIFY_ID_INTVAL','Force intval to variables like id');
define('WORTIFY_ID_INTVALDSC','All requests named "*id" will be treated as integer.<br />This option protects you from some kind of XSS and SQL Injections.<br />Though I recommend to turn this option on, it can cause problems with some modules.');
define('WORTIFY_FILE_DOTDOT','Protection from Directory Traversals');
define('WORTIFY_FILE_DOTDOTDSC','It eliminates ".." from all requests looks like Directory Traversals');
define('WORTIFY_BF_COUNT','Anti Brute Force');
define('WORTIFY_BF_COUNTDSC','Set count you allow guest try to login within 10 minutes. If someone fails to login more than this number, her/his IP will be banned.');
define('WORTIFY_BWLIMIT_COUNT','Bandwidth limitation');
define('WORTIFY_BWLIMIT_COUNTDSC','Specify the max access to mainfile.php during watching time. This value should be 0 for normal environments which have enough CPU bandwidth. The number fewer than 10 will be ignored.');
define('WORTIFY_DOS_SKIPMODS','Modules out of DoS/Crawler checker');
define('WORTIFY_DOS_SKIPMODSDSC','set the dirnames of the modules separated with |. This option will be useful with chatting module etc.');
define('WORTIFY_DOS_EXPIRE','Watch time for high loadings (sec)');
define('WORTIFY_DOS_EXPIREDSC','This value specifies the watch time for high-frequent reloading (F5 attack) and high loading crawlers.');
define('WORTIFY_DOS_F5COUNT','Bad counts for F5 Attack');
define('WORTIFY_DOS_F5COUNTDSC','Preventing from DoS attacks.<br />This value specifies the reloading counts to be considered as a malicious attack.');
define('WORTIFY_DOS_F5ACTION','Action against F5 Attack');
define('WORTIFY_DOS_CRCOUNT','Bad counts for Crawlers');
define('WORTIFY_DOS_CRCOUNTDSC','Preventing from high loading crawlers.<br />This value specifies the access counts to be considered as a bad-manner crawler.');
define('WORTIFY_DOS_CRACTION','Action against high loading Crawlers');
define('WORTIFY_DOS_CRSAFE','Welcomed User-Agent');
define('WORTIFY_DOS_CRSAFEDSC','A perl regex pattern for User-Agent.<br />If it matches, the crawler is never considered as a high loading crawler.<br />eg) /(bingbot|Googlebot|Yahoo! Slurp)/i');
define('WORTIFY_OPT_NONE','None (only logging)');
define('WORTIFY_OPT_SAN','Sanitizing');
define('WORTIFY_OPT_EXIT','Blank Screen');
define('WORTIFY_OPT_BIP','Ban the IP (No limit)');
define('WORTIFY_OPT_BIPTIME0','Ban the IP (moratorium)');
define('WORTIFY_DOSOPT_NONE','None (only logging)');
define('WORTIFY_DOSOPT_SLEEP','Sleep');
define('WORTIFY_DOSOPT_EXIT','Blank Screen');
define('WORTIFY_DOSOPT_BIP','Ban the IP (No limit)');
define('WORTIFY_DOSOPT_BIPTIME0','Ban the IP (moratorium)');
define('WORTIFY_DOSOPT_HTA','DENY by .htaccess(Experimental)');
define('WORTIFY_BIP_EXCEPT','Groups never registered as Bad IP');
define('WORTIFY_BIP_EXCEPTDSC','A user who belongs to the group specified here will never be banned.<br />(I recommend to turn Administrator on.)');
define('WORTIFY_DISABLES','Disable dangerous features in WORTIFY');
define('DBLAYERTRAP','Enable DB Layer trapping anti-SQL-Injection');
define('DBLAYERTRAPDSC','Almost SQL Injection attacks will be canceled by this feature. This feature is included a support from databasefactory. You can check it on Security Advisory page. This setting must be on. Never turn it off casually.');
define('DBTRAPWOSRV','Never checking _SERVER for anti-SQL-Injection');
define('DBTRAPWOSRVDSC','Some servers always enable DB Layer trapping. It causes wrong detections as SQL Injection attack. If you got such errors, turn this option on. You should know this option weakens the security of DB Layer trapping anti-SQL-Injection.');
define('WORTIFY_BIGUMBRELLA','enable anti-XSS (BigUmbrella)');
define('WORTIFY_BIGUMBRELLADSC','This protects you from almost attacks via XSS vulnerabilities. But it is not 100%');
define('WORTIFY_SPAMURI4U','anti-SPAM: URLs for normal users');
define('WORTIFY_SPAMURI4UDSC','If this number of URLs are found in POST data from users other than admin, the POST is considered as SPAM. 0 means disabling this feature.');
define('WORTIFY_SPAMURI4G','anti-SPAM: URLs for guests');
define('WORTIFY_SPAMURI4GDSC','If this number of URLs are found in POST data from guests, the POST is considered as SPAM. 0 means disabling this feature.');
define('WORTIFY_STOPFORUMSPAM_ACTION','Stop Forum Spam');
define('WORTIFY_STOPFORUMSPAM_ACTIONDSC','Checks POST data against spammers registered on www.stopforumspam.com database. Requires php CURL lib.');
define('WORTIFY_USERNAME', 'Wortify Cloud Username');
define('WORTIFY_USERNAME_DESC', 'You can get one of these by going to the menu <a href="admin.php?page=wortify-menu-signup">Sign-up</a>');
define('WORTIFY_PASSWORD', 'Wortify Cloud Password');
define('WORTIFY_PASSWORD_DESC', 'You assign one of these by going to the menu <a href="admin.php?page=wortify-menu-signup">Sign-up</a>');
define('WORTIFY_SECONDS', 'Seconds to base Cache List on!');
define('WORTIFY_SECONDS_DESC', 'Period of time for ban list to be invocated!');
define('WORTIFY_PROVIDERS', 'Provider Plug-ins');
define('WORTIFY_PROVIDERS_DESC', 'Provider\'s that are supported by Wortify');
define('WORTIFY_FRM_TITLE', 'Sign-up to Wortify.com - Fortify your WORTIFY');
define('WORTIFY_FRM_UNAME', 'Your Wortify Username');
define('WORTIFY_FRM_EMAIL', 'Your Email Address');
define('WORTIFY_FRM_PASS', 'Your Password');
define('WORTIFY_FRM_VPASS', 'Validate Your Password');
define('WORTIFY_FRM_URL', 'Your Primary URL');
define('WORTIFY_FRM_VIEWEMAIL', 'View Your Email');
define('WORTIFY_FRM_TIMEZONE', 'Your Timezone');
define('WORTIFY_FRM_DISCLAIMER', 'Wortify Disclaimer');
define('WORTIFY_FRM_DISCLAIMER_AGREE', 'Agree to Wortify Disclaimer');
define('WORTIFY_FRM_PUZZEL', 'Solve Puzzel');
define('WORTIFY_FRM_REGISTER', 'Register with Wortify.com');
define('WORTIFY_USERCREATED_PLEASEACTIVATE', 'Check Your Email for Activation Email, Click on the activation link to activate module!');
define('WORTIFY_FRM_NOSOAP_DISCLAIMER', '<strong>There is no API Communication with Wortify.com check you have PHP SOAP installed as an extension and you have no firewalls preventing normal SOAP Protocol Communication. You will not be able to continue until this message has been replaced with the Wortify Disclaimer.<br/><br/>The Submit button below will appear when this is working, wortify is currently <strong>Offline</strong>');
define('WORTIFY_RECORDS', 'Records to Retrieve!');
define('WORTIFY_RECORDS_DESC', 'Number of total records to retrieve!');
define('WORTIFY_SECONDS_37600', '5 Hours');
define('WORTIFY_SECONDS_28800', '4 Hours');
define('WORTIFY_SECONDS_14400', '3 Hours');
define('WORTIFY_SECONDS_7200', '2 Hours');
define('WORTIFY_SECONDS_3600', '1 Hour');
define('WORTIFY_SECONDS_1800', '30 minutes');
define('WORTIFY_SECONDS_1200', '20 minutes');
define('WORTIFY_SECONDS_600', '10 minutes');
define('WORTIFY_SECONDS_300', '5 Minutes');
define('WORTIFY_SECONDS_180', '3 Minutes');
define('WORTIFY_SECONDS_60', '1 Minute');
define('WORTIFY_SECONDS_30', '30 Seconds');
define('WORTIFY_RECORDS_177600', '177600 Records');
define('WORTIFY_RECORDS_48800', '48800 Records');
define('WORTIFY_RECORDS_24400', '24400 Records');
define('WORTIFY_RECORDS_12200', '12200 Records');
define('WORTIFY_RECORDS_3600', '3600 Records');
define('WORTIFY_RECORDS_1800', '1800 Records');
define('WORTIFY_RECORDS_1200', '1200 Records');
define('WORTIFY_RECORDS_600', '600 Records');
define('WORTIFY_RECORDS_300', '300 Records');
define('WORTIFY_RECORDS_180', '180 Records');
define('WORTIFY_RECORDS_60', '60 Records');
define('WORTIFY_RECORDS_30', '30 Records');
define('WORTIFY_PROVIDER_XORTIFY', 'Wortify Module');
define('WORTIFY_PROVIDER_PROTECTOR', 'Wortify Protector Module');
define('WORTIFY_PROVIDER_STOPFORUMSPAM', 'Stop Forum Spam');
define('WORTIFY_PROVIDER_PROJECTHONEYPOT', 'Project Honeypot');
define('WORTIFY_PROTOCOL', 'Cloud Communication Protocol');
define('WORTIFY_PROTOCOL_DESC', 'This is the protocol that is used in the communication with the wortify cloud!');
define('WORTIFY_PHP_DAYS', '%s day(s)');
define('WORTIFY_PHP_NUMBEROFDAYS', '<a href="http://www.projecthoneypot.org">Project Honeypot</a> - Last Number of Days Seen');
define('WORTIFY_PHP_NUMBEROFDAYS_DESC', 'This is the number of days upto that the IP has been seen and flagged by project honeypot');
define('WORTIFY_PHP_SEVERITY', '%s percent');
define('WORTIFY_PHP_SEVERITYLEVEL', '<a href="http://www.projecthoneypot.org">Project Honeypot</a> - Severity Level of IP');
define('WORTIFY_PHP_SEVERITYLEVEL_DESC', 'This is the severity level on the honeypot that the IP has been seen and flagged by project honeypot');
define('WORTIFY_PHP_SCANMODE', '<a href="http://www.projecthoneypot.org">Project Honeypot</a> - What will be banned');
define('WORTIFY_PHP_SCANMODE_DESC', 'This list is in a hierachy which will ban from what you select and down in the list from severity.');
define('WORTIFY_PHP_SCANMODE_SUSPICIOUS', 'Suspicious IP');
define('WORTIFY_PHP_SCANMODE_HARVESTER', 'Harvester IP');
define('WORTIFY_PHP_SCANMODE_SUSICIOUSHARVESTER', 'Suspicious + Harvester IP');
define('WORTIFY_PHP_SCANMODE_SPAMMER', 'Comment Spammer');
define('WORTIFY_PHP_SCANMODE_SUSPICIOUSSPAMMER', 'Suspicious + Comment Spammer');
define('WORTIFY_PHP_SCANMODE_HARVESTERSPAMMER', 'Harvester + Comment Spammer');
define('WORTIFY_PHP_SCANMODE_SUSPICIOUSHARVESTERSPAMMER', 'Suspicious + Harvester + Comment Spammer');
define('WORTIFY_SFS_EMAIILFREQ','Occured %s time(s)');
define('WORTIFY_SFS_EMAILFREQUENCY','<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - Number of time email address has been reported');
define('WORTIFY_SFS_EMAILFREQUENCY_DESC','This is the number of time the email has been reported as spam and will ban the IP and details when it occurs at this level.');
define('WORTIFY_SFS_UNAMEFREQ','Occured %s time(s)');
define('WORTIFY_SFS_UNAMEFREQUENCY','<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - Number of time username has been reported');
define('WORTIFY_SFS_UNAMEFREQUENCY_DESC','This is the number of time the username has been reported as spam and will ban the IP and details when it occurs at this level.');
define('WORTIFY_SFS_IPFREQ','Occured %s time(s)');
define('WORTIFY_SFS_IPFREQUENCY','<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - Number of time IP address has been reported');
define('WORTIFY_SFS_IPFREQUENCY_DESC','This is the number of time the IP Address has been reported as spam and will ban the IP and details when it occurs at this level.');
define('WORTIFY_SFS_EMAILLASTSEEN', '<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - How long ago the email address was seen');
define('WORTIFY_SFS_EMAILLASTSEEN_DESC', 'This is the period of time allocated to wence the email was last seen, if it occured after this period it will not be banned!');
define('WORTIFY_SFS_UNAMELASTSEEN', '<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - How long ago the username was seen');
define('WORTIFY_SFS_UNAMELASTSEEN_DESC', 'This is the period of time allocated to wence the username was last seen, if it occured after this period it will not be banned!');
define('WORTIFY_SFS_IPLASTSEEN', '<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - How long ago the IP address was seen');
define('WORTIFY_SFS_IPLASTSEEN_DESC', 'This is the period of time allocated to wence the IP address was last seen, if it occured after this period it will not be banned!');
define('WORTIFY_SFS_LASTSEEN_24HOURS', '24 Hours');
define('WORTIFY_SFS_LASTSEEN_1WEEK', '1 Week');
define('WORTIFY_SFS_LASTSEEN_FORTNIGHT', 'A Fortnight');
define('WORTIFY_SFS_LASTSEEN_1MONTH', '1 Month');
define('WORTIFY_SFS_LASTSEEN_2MONTHS', '2 Months');
define('WORTIFY_SFS_LASTSEEN_3MONTHS', '3 Months');
define('WORTIFY_SFS_LASTSEEN_4MONTHS', '4 Months');
define('WORTIFY_SFS_LASTSEEN_5MONTHS', '5 Months');
define('WORTIFY_SFS_LASTSEEN_6MONTHS', '6 Months');
define('WORTIFY_SFS_LASTSEEN_12MONTHS', '1 Year');
define('WORTIFY_SFS_LASTSEEN_24MONTHS', '2 Years');
define('WORTIFY_SFS_LASTSEEN_36MONTHS', '3 Years');
define('WORTIFY_LOG_BANNED', 'Log Banned Action');
define('WORTIFY_LOG_BANNED_DESC', 'When Wortify Bans something on the cloud, then log it!');
define('WORTIFY_LOG_BLOCKED', 'Log Blocked Action');
define('WORTIFY_LOG_BLOCKED_DESC', 'When Wortify Blocks something as detected on the cloud, then log it!');
define('WORTIFY_LOG_MONITORED', 'Log Monitored Action');
define('WORTIFY_LOG_MONITORED_DESC', 'When Wortify Monitor\'s something/someone as detected on the cloud, then log it!');
define('WORTIFY_LOGDROPS', 'Log Deletes Itself After');
define('WORTIFY_LOGDROPS_DESC', 'This is how long the log stays on your site for after a record reaches this age it is deleted!');
define('WORTIFY_IPCACHE', 'IP Cache Time');
define('WORTIFY_IPCACHE_DESC', 'This is the amount of time an IP Address and information on it is cached!');
define('WORTIFY_SECONDS_29030400', '1 Year');
define('WORTIFY_SECONDS_14515200', '6 Months');
define('WORTIFY_SECONDS_7257600', '3 Months');
define('WORTIFY_SECONDS_2419200', '1 Month');
define('WORTIFY_SECONDS_1209600', '1 Fortnight');
define('WORTIFY_SECONDS_604800', '1 Week');
define('WORTIFY_SECONDS_86400', '24 Hours');
define('WORTIFY_SECONDS_43200', '12 Hours');
define('WORTIFY_FAULT_DELAY', 'Number of second to delay function on fault!');
define('WORTIFY_FAULT_DELAY_DESC', 'If a fault occurs in the preloader, wortify will delay this function before calling it again this many seconds (Default 10 minutes)');
define('WORTIFY_CURL_TIMOUT', 'Total amount of seconds a CURL Waits for a Response');
define('WORTIFY_CURL_TIMOUT_DESC', 'This is the total amount of seconds a CURL waits for a response after resolving the DNS.');
define('WORTIFY_CURL_CONNECTTIMOUT', 'Total amount of seconds a CURL Waits for DNS to resolve');
define('WORTIFY_CURL_CONNECTTIMOUT_DESC', 'This is the total amount of seconds a CURL waits for a DNS Lookup to resolve.');
define('WORTIFY_NAME', 'Wortify');
define('WORTIFY_VERSION', '4.16');
define('WORTIFY_MODE_CLIENT', 'Client');
define('WORTIFY_MODE_SERVER', 'Server');
define('WORTIFY_USER_AGENT', '%s/%s %s Sector Network Security (%s)');
define('WORTIFY_RUNTIME', 'PHP ' . PHP_VERSION. ' ['.PHP_VERSION_ID . '], WORTIFY '.WORTIFY_VERSION);
define('WORTIFY_URIREST', 'REST API Cloud Base URL');
define('WORTIFY_URIREST_DESC', 'This is the URL for cURL or wGET for REST API Communication <i>(With Trailing Slash)</i>');
define('WORTIFY_PROTOCOL_CURLSERIAL', 'cURL Serilisation Protocol (REST)');
define('WORTIFY_PROTOCOL_WGETSERIAL', 'wGET Serilisation Protocol (REST)');
define('WORTIFY_PROTOCOL_CURLXML', 'cURL XML Exchange Protocol (REST)');
define('WORTIFY_PROTOCOL_WGETXML', 'wGET XML Exchange Protocol (REST)');
define('WORTIFY_PROTOCOL_CURL', 'CURL Protocol (REST)');
define('WORTIFY_PROTOCOL_JSON', 'JSON Protocol (REST)');
define('WORTIFY_BOUNCE', 'Server Bounce Relay Delay');
define('WORTIFY_BOUNCE_DESC', 'This is the relay delay for the software when it is in Server Mode only! <i>(Does not apply to Client Edition!)</i>');
define('WORTIFY_DISABLED', 'Disabled!');
define('WORTIFY_USERSTOCHECK', 'Number of Users to Crawl at IP Cache Time?');
define('WORTIFY_USERSTOCHECK_DESC', 'This is the number of Users to Crawl at IP Cache Time; you can disable checking your User Base by selecting \'Disabled!\'');
define('WORTIFY_ALLOWEDSPAMS', 'Spam attempts allowed before ban.');
define('WORTIFY_ALLOWEDSPAMS_DESC', 'This is the number of times a user is blocked about spam before they are banned!');
define('WORTIFY_CHECKSPAMS', 'Groups checked for SPAM');
define('WORTIFY_CHECKSPAMS_DESC', 'This is the groups for user is blocked & checked for spam before they are banned at the number of attempts above!');
define('WORTIFY_MINIMUMWORDS', 'Major Text Box Minimum number of allowed words.');
define('WORTIFY_MINIMUMWORDS_DESC', 'This is the number of words allowed for a major text box by the selected groups!');
define('WORTIFY_MINIMUMWORDSGROUPS', 'Major Text Box Minimum number of allowed words.');
define('WORTIFY_MINIMUMWORDSGROUPS_DESC', 'This is the number of words allowed for a major text box by the selected groups!');
define('WORTIFY_ALLOWEDADULT', 'Allow Adult Keywords and Key Phrases.');
define('WORTIFY_ALLOWEDADULT_DESC', 'This is the groups to enable adult keywords for any selected group!');
define('WORTIFY_PROTOCOL_MINIMUMCLOUD', 'Mimised Use of Cloud (REST)');
define('WORTIFY_MC_REQUIRED', '&nbsp;<font style="color:red;">(Required for <strong>'.WORTIFY_PROTOCOL_MINIMUMCLOUD.'</strong>)</font>');
define('WORTIFY_MC_SPAMC', 'Command for SpamAssassin\'s <i>SPAMC</i>');
define('WORTIFY_MC_SPAMC_DESC', 'Must include full path and executable filename for SpamAssassin\'s SPAMC Executable!'.WORTIFY_MC_REQUIRED);
define('WORTIFY_MC_SFS_API', 'Stop Forum Spam\'s API URI');
define('WORTIFY_MC_SFS_API_DESC', 'Full URI for <a href="http://stopforumspam.com">Stop Forum Spams</a> API!'.WORTIFY_MC_REQUIRED);
define('WORTIFY_MC_SFS_KEY', 'Stop Forum\'s Spam API Key.');
define('WORTIFY_MC_SFS_KEY_DESC', 'Full API KEy for <a href="http://stopforumspam.com">Stop Forum Spams</a> API!'.WORTIFY_MC_REQUIRED);
define('WORTIFY_MC_PHP_API', 'Project Honeypots End URI for BL API.');
define('WORTIFY_MC_PHP_API_DESC', 'Full Domain Extraction for BL API on <a href="http://projecthoneypot.org">Project Honeypots</a>!'.WORTIFY_MC_REQUIRED);
define('WORTIFY_MC_PHP_KEY', 'Project Honeypots BL API Key.');
define('WORTIFY_MC_PHP_KEY_DESC', 'BL API Key on <a href="http://projecthoneypot.org">Project Honeypots</a>!'.WORTIFY_MC_REQUIRED);
define('WORTIFY_SPOOF_COMMENT', 'Comments Sentry');
define('WORTIFY_SPOOF_REGISTRATION', 'Registration Sentry');
define('WORTIFY_SPOOF_THREAD', 'Thread Sentry');
define('WORTIFY_SPOOF_COMMENT_DESC', 'Displays a fake comment now form for the purposes of capturing spam and banning the submitter!');
define('WORTIFY_SPOOF_REGISTRATION_DESC', 'Displays a fake registrationw form for the purposes of capturing spam and banning the submitter!');
define('WORTIFY_SPOOF_THREAD_DESC', 'Displays a fake thread form for the purposes of capturing spam and banning the submitter!');

define('WORTIFY_IPINFODB_URL', 'URL for ipinfodb.com API');
define('WORTIFY_IPINFODB_URL_DESC', 'This is the URL for the IP Information Databases API. <font style="color: red;">(Only included for minimal mode)</font>');
define('WORTIFY_IPINFODB_KEY', 'Key for ipinfodb.com');
define('WORTIFY_IPINFODB_KEY_DESC', 'This is the API Key provided by ipinfodb.com. <font style="color: red;">(Only included for minimal mode)</font>');
define('WORTIFY_ALERT_EMAILS', 'Emails to be alerted on bans and blocks!');
define('WORTIFY_ALERT_EMAILS_DESC', 'Seperated by a pipe symbol ("|") these are the emails that are sent IP Security Alerts!');
define('WORTIFY_ALERT_ON_WARNINGS', 'Send an alert on warnings!');
define('WORTIFY_ALERT_ON_WARNINGS_DESC', 'Enable this for alerts on spam warnings!');
define('WORTIFY_ALERT_ON_CRITICAL', 'Send alter on critical spam ban!');
define('WORTIFY_ALERT_ON_CRITICAL_DESC', 'Enable this for alerts on critical spam warnings!');
define('WORTIFY_SERVERCACHE', 'Server list is kept for this time before refreshing!');
define('WORTIFY_SERVERCACHE_DESC', 'The length a server list is kept for.');

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wortifyDatabase.php';

?>