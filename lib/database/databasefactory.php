<?php
/**
 * Factory Class for WORTIFY Database
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The WORTIFY project http://sourceforge.net/projects/xortify/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      database
 * @version         $Id: databasefactory.php 10981 2013-02-04 19:37:48Z trabis $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 * WortifyDatabaseFactory
 *
 * @package Kernel
 * @author Kazumi Ono <onokazu@wortify.org>
 * @access public
 */
class WortifyDatabaseFactory
{
    /**
     * WortifyDatabaseFactory::WortifyDatabaseFactory()
     */
    function WortifyDatabaseFactory()
    {
    }

    /**
     * Get a reference to the only instance of database class and connects to DB
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * @static
     * @staticvar object  The only instance of database class
     * @return object Reference to the only instance of database class
     */
    static function &getDatabaseConnection($dbh = '')
    {
        static $instance;
        if (!isset($instance)) {
            if (file_exists($file = dirname(__FILE__) . DIRECTORY_SEPARATOR . DB_TYPE . 'database.php')) {
                include_once $file;

                if (!defined('DB_PROXY')) {
                    $class = 'Wortify' . ucfirst(DB_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'Wortify' . ucfirst(DB_TYPE) . 'DatabaseProxy';
                }

                $instance = new $class(NULL);
                if (!$instance->connect()) {
                    trigger_error('notrace:Unable to connect to database', E_USER_ERROR);
                }
            } else {
                trigger_error('notrace:Failed to load database of type: ' . DB_TYPE . ' in file: ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
            }
        }
        return $instance;
    }

    /**
     * Gets a reference to the only instance of database class. Currently
     * only being used within the installer.
     *
     * @static
     * @staticvar object  The only instance of database class
     * @return object Reference to the only instance of database class
     */
    static function getDatabase()
    {
        static $database;
        if (!isset($database)) {
            if (file_exists($file = dirname(__FILE__) . DIRECTORY_SEPARATOR . DB_TYPE . 'database.php')) {
                include_once $file;
                if (!defined('DB_PROXY')) {
                    $class = 'Wortify' . ucfirst(DB_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'Wortify' . ucfirst(DB_TYPE) . 'DatabaseProxy';
                }
                unset($database);
                $database = new $class();
            } else {
                trigger_error('notrace:Failed to load database of type: ' . DB_TYPE . ' in file: ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
            }
        }
        return $database;
    }
}

?>