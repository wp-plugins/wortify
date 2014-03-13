<?php
/**
 * WordPress DB Class
 *
 * Original code from {@link http://php.justinvincent.com Justin Vincent (justin@visunet.ie)}
 *
 * @package WordPress
 * @subpackage Database
 * @since 0.71
 */

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'databasefactory.php';


/*
 * Database Type
 */
define('DB_TYPE', 'mysql');

/**
 * WordPress Database Access Abstraction Object
 *
 * It is possible to replace this class with your own
 * by setting the $wpdb global variable in wp-content/db.php
 * file to your class. The wpdb class will still be included,
 * so you can extend it or simply use your own.
 *
 * @link http://codex.wordpress.org/Function_Reference/wpdb_Class
 *
 * @package WordPress
 * @subpackage Database
 * @since 0.71
 */
class wortifydb extends wpdb {

	var $db = null;
	
	function __construct($wpdb = NULL) {
		static $connection = NULL;
		if (is_null($connection))
			$this->db = $connection =WortifyDatabaseFactory::getDatabaseConnection($wpdb->dbh());
		return $this->db; 
	}
}

