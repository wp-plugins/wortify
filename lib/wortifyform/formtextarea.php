<?php
/**
 * WORTIFY form element
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
 * @subpackage      form
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.wortify.org/
 * @version         $Id: formtextarea.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('WORTIFY_ROOT_PATH') or die('Restricted access');

include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formelement.php');

/**
 * A textarea
 */
class WortifyFormTextArea extends WortifyFormElement
{
    /**
     * number of columns
     *
     * @var int
     * @access private
     */
    var $_cols;

    /**
     * number of rows
     *
     * @var int
     * @access private
     */
    var $_rows;

    /**
     * initial content
     *
     * @var string
     * @access private
     */
    var $_value;

    /**
     * Constuctor
     *
     * @param string $caption caption
     * @param string $name name
     * @param string $value initial content
     * @param int $rows number of rows
     * @param int $cols number of columns
     */
    function WortifyFormTextArea($caption, $name, $value = "", $rows = 5, $cols = 50)
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_rows = intval($rows);
        $this->_cols = intval($cols);
        $this->setValue($value);
    }

    /**
     * get number of rows
     *
     * @return int
     */
    function getRows()
    {
        return $this->_rows;
    }

    /**
     * Get number of columns
     *
     * @return int
     */
    function getCols()
    {
        return $this->_cols;
    }

    /**
     * Get initial content
     *
     * @param bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compat
     * @return string
     */
    function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->_value) : $this->_value;
    }

    /**
     * Set initial content
     *
     * @param  $value string
     */
    function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * prepare HTML for output
     *
     * @return sting HTML
     */
    function render()
    {
        return "<textarea name='" . $this->getName() . "' id='" . $this->getName() . "'  title='" . $this->getTitle() . "' rows='" . $this->getRows() . "' cols='" . $this->getCols() . "'" . $this->getExtra() . ">" . $this->getValue() . "</textarea>";
    }
}

?>