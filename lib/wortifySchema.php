<?php
require_once('wortifyDB.php');
class wortifySchema {
	private $tables = array(
"wortify_access" => "(
  `ip` varchar(255) NOT NULL DEFAULT '0.0.0.0',
  `request_uri` varchar(255) NOT NULL DEFAULT '',
  `malicious_actions` varchar(255) NOT NULL DEFAULT '',
  `expire` int(11) NOT NULL DEFAULT '0',
  KEY `ip` (`ip`),
  KEY `request_uri` (`request_uri`),
  KEY `malicious_actions` (`malicious_actions`),
  KEY `expire` (`expire`)
) ENGINE=INNODB DEFAULT CHARSET=utf8",
"wortify_protector_log" => "(
`lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
`uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
`ip` varchar(255) NOT NULL DEFAULT '0.0.0.0',
`type` varchar(255) NOT NULL DEFAULT '',
`agent` varchar(255) NOT NULL DEFAULT '',
`description` text,
`extra` text,
`timestamp` datetime DEFAULT NULL,
PRIMARY KEY (`lid`),
KEY `uid` (`uid`),
KEY `ip` (`ip`),
KEY `type` (`type`),
KEY `timestamp` (`timestamp`)
) ENGINE=INNODB DEFAULT CHARSET=utf8",
"wortify_wortify_log" => "(
`lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
`uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
`uname` varchar(64) DEFAULT NULL,
`email` varchar(255) DEFAULT NULL,
`ip4` varchar(15) NOT NULL DEFAULT '0.0.0.0',
`ip6` varchar(128) NOT NULL DEFAULT '0:0:0:0:0:0',
`proxy-ip4` varchar(64) NOT NULL DEFAULT '0.0.0.0',
`proxy-ip6` varchar(128) NOT NULL DEFAULT '0:0:0:0:0:0',
`network-addy` varchar(255) NOT NULL DEFAULT '',
`provider` varchar(128) NOT NULL DEFAULT '',
`agent` varchar(255) NOT NULL DEFAULT '',
`extra` text,
`date` int(12) NOT NULL DEFAULT '0',
`action` enum('banned','blocked','monitored','polled') NOT NULL DEFAULT 'monitored',
PRIMARY KEY (`lid`),
KEY `uid` (`uid`),
KEY `ip` (`ip4`,`ip6`(16),`proxy-ip4`,`proxy-ip6`(16)),
KEY `provider` (`provider`(15)),
KEY `date` (`date`),
KEY `action` (`action`)
) ENGINE=INNODB DEFAULT CHARSET=utf8"
);
	private $db = false;
	private $prefix = DB_PREFIX;
	public function __construct($dbhost = false, $dbuser = false, $dbpassword = false, $dbname = false){
		/*
		if($dbhost){ //for testing
			$this->db = new wortifyDB(false, $dbhost, $dbuser, $dbpassword, $dbname);
			$this->prefix = 'wp_';
		} else {
		*/
		global $wpdb;
		$this->db = new wortifyDB();
		$this->prefix = $wpdb->base_prefix;
	}
	public function dropAll(){
		foreach($this->tables as $table => $def){
			$this->db->queryWrite("drop table if exists " . $this->prefix . $table);
		}
	}
	public function createAll(){
		foreach($this->tables as $table => $def){
			$this->db->queryWrite("create table IF NOT EXISTS " . $this->prefix . $table . " " . $def);
		}
	}
	public function create($table){
		$this->db->queryWrite("create table IF NOT EXISTS " . $this->prefix . $table . " " . $this->tables[$table]);
	}
	public function drop($table){
		$this->db->queryWrite("drop table if exists " . $this->prefix . $table);
	}
}
?>
