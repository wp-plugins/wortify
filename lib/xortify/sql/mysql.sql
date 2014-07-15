CREATE TABLE `;

CREATE TABLE `xortify_servers` (
  `sid` mediumint(32) unsigned NOT NULL AUTO_INCREMENT,
  `server` varchar(255) NOT NULL DEFAULT '',
  `replace` varchar(255) NOT NULL DEFAULT '',
  `search` varchar(64) NOT NULL DEFAULT '',
  `online` tinyint(1) DEFAULT '0',
  `polled` int(12) NOT NULL DEFAULT '0',
  `user` varchar(64) NOT NULL DEFAULT '',
  `pass` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`sid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `xortify_emails` (
  `eid` mediumint(32) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `uid` int(13) NOT NULL DEFAULT '0',
  `count` int(26) NOT NULL DEFAULT '1',
  `encounter` int(13) NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `xortify_emails_links` (
  `elid` mediumint(64) unsigned NOT NULL AUTO_INCREMENT,
  `eid` mediumint(32) unsigned NOT NULL DEFAULT '0',
  `uid` int(13) NOT NULL DEFAULT '0',
  `ip` varchar(128) NOT NULL DEFAULT '127.0.0.1',
PRIMARY KEY (`elid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;