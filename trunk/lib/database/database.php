<?php
/**
 * Abstract base class for WORTIFY Database access classes
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
 * @version         $Id: database.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 * make sure this is only included once!
 */
if (defined('WORTIFY_C_DATABASE_INCLUDED')) {
    return;
}

define('WORTIFY_C_DATABASE_INCLUDED', 1);

/**
 * Abstract base class for Database access classes
 *
 * @abstract
 * @author Kazumi Ono <onokazu@wortify.org>
 * @package kernel
 * @subpackage database
 */
class WortifyDatabase
{


    /**
     * If statements that modify the database are selected
     *
     * @var boolean
     */
    var $allowWebChanges = false;

    /**
     * constructor
     *
     * will always fail, because this is an abstract class!
     */
    function WortifyDatabase()
    {
        // exit('Cannot instantiate this class directly');
    }

    /**
     * set the prefix for tables in the database
     *
     * @param string $value table prefix
     */
    function setPrefix($value)
    {
        $this->prefix = $value;
    }

}

?>