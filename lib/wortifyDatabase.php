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
 * Database Intialisation
*/
$GLOBALS['wortifyDB'] = WortifyDatabaseFactory::getDatabaseConnection($GLOBALS['wpdb']->dbh);

