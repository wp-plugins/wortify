<?php
/**
 * MySQL access
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
 * @since           1.0.0
 * @author          Kazumi Ono <onokazu@wortify.org>
 * @version         $Id: mysqldatabase.php 10264 2012-11-21 04:52:11Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 *
 * @package kernel
 * @subpackage database
 * @author Kazumi Ono <onokazu@wortify.org>
 * @copyright copyright (c) 2000-2003 WORTIFY.org
 */

/**
 * base class
 */
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database.php';

/**
 * connection to a mysql database
 *
 * @abstract
 * @author Kazumi Ono <onokazu@wortify.org>
 * @copyright copyright (c) 2000-2003 WORTIFY.org
 * @package kernel
 * @subpackage database
 */
class WortifyMySQLDatabase extends WortifyDatabase
{

	var $conn = NULL;
	
	/**
	 * connect to the database
	 *
	 * @param bool $selectdb select the database now?
	 * @return bool successful?
	 */
	function __construct($dbconn)
	{
		if (!empty($dbconn)) {
			$this->conn = $dbconn;
		}
		
	}
	
		
	/**
	* connect to the database
	*
	* @param bool $selectdb select the database now?
	* @return bool successful?
	*/
	function connect($selectdb = TRUE)
	{
		if (!$this->conn) {
			if (!extension_loaded('mysqli')) {
				trigger_error('notrace:mysqli extension not loaded', E_USER_ERROR);
				return FALSE;
			}
			$this->allowWebChanges = ($_SERVER['REQUEST_METHOD'] != 'GET');
			$this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
			if (!$this->conn) {
				return FALSE;
			}
			if ($selectdb != FALSE) {
				if (!mysqli_select_db($this->conn, DB_NAME)) {
					return FALSE;
				}
			}
			if (!isset($db_charset_set) && defined('DB_CHARSET') && DB_CHARSET) {
				$this->queryF("SET NAMES '" . DB_CHARSET . "'");
			}
			$db_charset_set = 1;
			$this->queryF("SET SQL_BIG_SELECTS = 1");
		}
		return TRUE;
	}
	
    /**
     * generate an ID for a new row
     *
     * This is for compatibility only. Will always return 0, because MySQL supports
     * autoincrement for primary keys.
     *
     * @param string $sequence name of the sequence from which to get the next ID
     * @return int always 0, because mysql has support for autoincrement
     */
    function genId($sequence)
    {
        return 0; // will use auto_increment
    }

    /**
     * Get a result row as an enumerated array
     *
     * @param resource $result
     * @return array
     */
    function fetchRow($result)
    {
        return @mysqli_fetch_row($result);
    }

    /**
     * Fetch a result row as an associative array
     *
     * @return array
     */
    function fetchArray($result)
    {
        return @mysqli_fetch_assoc($result);
    }

    /**
     * Fetch a result row as an associative array
     *
     * @return array
     */
    function fetchBoth($result)
    {
        return @mysqli_fetch_array($result, mysqli_BOTH);
    }

    /**
     * WortifyMySQLDatabase::fetchObjected()
     *
     * @param mixed $result
     * @return
     */
    function fetchObject($result)
    {
        return @mysqli_fetch_object($result);
    }

    /**
     * Get the ID generated from the previous INSERT operation
     *
     * @return int
     */
    function getInsertId()
    {
        return mysqli_insert_id($this->conn);
    }

    /**
     * Get number of rows in result
     *
     * @param resource $ query result
     * @return int
     */
    function getRowsNum($result)
    {
        return @mysqli_num_rows($result);
    }

    /**
     * Get number of affected rows
     *
     * @return int
     */
    function getAffectedRows()
    {
        return mysqli_affected_rows($this->conn);
    }

    /**
     * Close MySQL connection
     */
    function close()
    {
        mysqli_close($this->conn);
    }

    /**
     * will free all memory associated with the result identifier result.
     *
     * @param resource $ query result
     * @return bool TRUE on success or FALSE on failure.
     */
    function freeRecordSet($result)
    {
        return mysqli_free_result($result);
    }

    /**
     * Returns the text of the error message from previous MySQL operation
     *
     * @return bool Returns the error text from the last MySQL function, or '' (the empty string) if no error occurred.
     */
    function error()
    {
        return @mysqli_error($this->conn);
    }

    /**
     * Returns the numerical value of the error message from previous MySQL operation
     *
     * @return int Returns the error number from the last MySQL function, or 0 (zero) if no error occurred.
     */
    function errno()
    {
        return @mysqli_errno($this->conn);
    }

    /**
     * Returns escaped string text with single quotes around it to be safely stored in database
     *
     * @param string $str unescaped string text
     * @return string escaped string text with single quotes around
     */
    function quoteString($str)
    {
        return $this->quote($str);
    }

    /**
     * Quotes a string for use in a query.
     */
    function quote($string)
    {
        return "'" . str_replace("\\\"", '"', str_replace("\\&quot;", '&quot;', mysqli_real_escape_string($string))) . "'";
    }

    /**
     * perform a query on the database
     *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     * @return resource query result or FALSE if successful
     * or TRUE if successful and no result
     */
    function queryF($sql, $limit = 0, $start = 0)
    {
    	//if (strpos($sql, 'protector')||strpos($sql, 'access'))
    	//	echo "<pre>$sql</pre>";
        if (!empty($limit)) {
            if (empty($start)) {
                $start = 0;
            }
            $sql = $sql . ' LIMIT ' . (int) $start . ', ' . (int) $limit;
        }
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    function prefix($table) {
    	return $GLOBALS['wpdb']->base_prefix . $table;
    }
    /**
     * perform a query
     *
     * This method is empty and does nothing! It should therefore only be
     * used if nothing is exactly what you want done! ;-)
     *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     * @abstract
     */
    function query($sql, $limit = 0, $start = 0)
    {
    }

    /**
     * perform queries from SQL dump file in a batch
     *
     * @param string $file file path to an SQL dump file
     * @return bool FALSE if failed reading SQL file or TRUE if the file has been read and queries executed
     */
    function queryFromFile($file)
    {
        if (FALSE !== ($fp = fopen($file, 'r'))) {
            include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sqlutility.php';
            $sql_queries = trim(fread($fp, filesize($file)));
            SqlUtility::splitMySqlFile($pieces, $sql_queries);
            foreach ($pieces as $query) {
                // [0] contains the prefixed query
                // [4] contains unprefixed table name
                $prefixed_query = SqlUtility::prefixQuery(trim($query), $this->prefix());
                if ($prefixed_query != FALSE) {
                    $this->query($prefixed_query[0]);
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Get field name
     *
     * @param resource $result query result
     * @param int $ numerical field index
     * @return string
     */
    function getFieldName($result, $offset)
    {
        return mysqli_field_name($result, $offset);
    }

    /**
     * Get field type
     *
     * @param resource $result query result
     * @param int $offset numerical field index
     * @return string
     */
    function getFieldType($result, $offset)
    {
        return mysqli_field_type($result, $offset);
    }

    /**
     * Get number of fields in result
     *
     * @param resource $result query result
     * @return int
     */
    function getFieldsNum($result)
    {
        return mysqli_num_fields($result);
    }
}

/**
 * Safe Connection to a MySQL database.
 *
 * @author Kazumi Ono <onokazu@wortify.org>
 * @copyright copyright (c) 2000-2003 WORTIFY.org
 * @package kernel
 * @subpackage database
 */
class WortifyMySQLDatabaseSafe extends WortifyMySQLDatabase
{
    /**
     * perform a query on the database
     *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     * @return resource query result or FALSE if successful
     * or TRUE if successful and no result
     */
    function query($sql, $limit = 0, $start = 0)
    {
        return $this->queryF($sql, $limit, $start);
    }
}

/**
 * Read-Only connection to a MySQL database.
 *
 * This class allows only SELECT queries to be performed through its
 * {@link query()} method for security reasons.
 *
 * @author Kazumi Ono <onokazu@wortify.org>
 * @copyright copyright (c) 2000-2003 WORTIFY.org
 * @package kernel
 * @subpackage database
 */
class WortifyMySQLDatabaseProxy extends WortifyMySQLDatabase
{
    /**
     * perform a query on the database
     *
     * this method allows only SELECT queries for safety.
     *
     * @param string $sql a valid MySQL query
     * @param int $limit number of records to return
     * @param int $start offset of first record to return
     * @return resource query result or FALSE if unsuccessful
     */
    function query($sql, $limit = 0, $start = 0)
    {
        $sql = ltrim($sql);
        if (!$this->allowWebChanges && strtolower(substr($sql, 0, 6)) != 'select') {
            trigger_error('Database updates are not allowed during processing of a GET request', E_USER_WARNING);
            return FALSE;
        }

        return $this->queryF($sql, $limit, $start);
    }
}

?>