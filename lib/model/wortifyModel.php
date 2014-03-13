<?php
/**
 * Object factory class.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The WORTIFY Project http://sourceforge.net/projects/xortify/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      model
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: wortifymodel.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'wortifyObject.php';
/**
 * Factory for object handlers
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @package kernel
 */
class WortifyModelFactory
{
    /**
     * static private
     */
    var $handlers = array();

    /**
     * WortifyModelFactory::__construct()
     */
    function __construct()
    {
    }

    /**
     * WortifyModelFactory::WortifyModelFactory()
     */
    function WortifyModelFactory()
    {
    }

    /**
     * Get singleton instance
     *
     * @access public
     */
    function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * Load object handler
     *
     * @access public
     * @param object $ohandler reference to {@link WortifyPersistableObjectHandler}
     * @param string $name handler name
     * @param mixed $args args
     * @return object of handler
     */
    function loadHandler($ohander, $name, $args = null)
    {
        static $handlers;
        if (!isset($handlers[$name])) {
            if (file_exists($file = dirname(__FILE__) . '/' . $name . '.php')) {
                include_once $file;
                $className = 'WortifyModel' . ucfirst($name);
                $handler = new $className();
            }
            if (!is_object($handler)) {
                trigger_error('Handler not found in file ' . __FILE__ . 'at line ' . __LINE__, E_USER_WARNING);
                return null;
            }
            $handlers[$name] = $handler;
        }
        $handlers[$name]->setHandler($ohander);
        if (!empty($args) && is_array($args) && is_a($handlers[$name], 'WortifyModelAbstract')) {
            $handlers[$name]->setVars($args);
        }
        return $handlers[$name];
    }
}

/**
 * abstract class object handler
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @package kernel
 */
class WortifyModelAbstract
{
    /**
     * holds referenced to handler object
     *
     * @var object
     * @param object $ohandler reference to {@link WortifyPersistableObjectHandler}
     * @access protected
     */
    var $handler;

    /**
     * constructor
     *
     * normally, this is called from child classes only
     *
     * @access protected
     */
    function __construct($args = null, $handler = null)
    {
        $this->setHandler($handler);
        $this->setVars($args);
    }

    /**
     * WortifyModelAbstract::WortifyObjectAbstract()
     *
     * @param mixed $args
     * @param mixed $handler
     * @return
     */
    function WortifyObjectAbstract($args = null, $handler = null)
    {
        $this->__construct($args, $handler);
    }

    /**
     * WortifyModelAbstract::setHandler()
     *
     * @param mixed $handler
     * @return
     */
    function setHandler($handler)
    {
        if (is_object($handler) && is_a($handler, 'WortifyPersistableObjectHandler')) {
            $this->handler =& $handler;
            return true;
        }
        return false;
    }

    /**
     * WortifyModelAbstract::setVars()
     *
     * @param mixed $args
     * @return
     */
    function setVars($args)
    {
        if (!empty($args) && is_array($args)) {
            foreach ($args as $key => $value) {
                $this->$key = $value;
            }
        }
        return true;
    }
}

?>