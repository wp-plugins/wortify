<?php
/**
 * WORTIFY form element of button
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
 * @version         $Id: formbutton.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die("WORTIFY root path not defined");

include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formelement.php');

/**
 *
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@wortify.org>
 * @copyright	copyright (c) 2000-2003 WORTIFY.org
 */
/**
 * A button
 *
 * @author	Kazumi Ono	<onokazu@wortify.org>
 * @copyright	copyright (c) 2000-2003 WORTIFY.org
 *
 * @package     kernel
 * @subpackage  form
 */
class WortifyFormButton extends WortifyFormElement
{

	/**
     * Value
	 * @var	string
	 * @access	private
	 */
	var $_value;

	/**
     * Type of the button. This could be either "button", "submit", or "reset"
	 * @var	string
	 * @access	private
	 */
	var $_type;

	/**
	 * Constructor
     *
	 * @param	string  $caption    Caption
     * @param	string  $name
     * @param	string  $value
     * @param	string  $type       Type of the button. Potential values: "button", "submit", or "reset"
	 */
	function WortifyFormButton($caption, $name, $value = "", $type = "button")
	{
		$this->setCaption($caption);
		$this->setName($name);
		$this->_type = $type;
		$this->setValue($value);
	}

	/**
	 * Get the initial value
	 *
	 * @param	bool    $encode To sanitizer the text?
     * @return	string
	 */
	function getValue($encode = false)
	{
		return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
	}

	/**
	 * Set the initial value
	 *
     * @return	string
	 */
	function setValue($value)
	{
		$this->_value = $value;
	}

	/**
	 * Get the type
	 *
     * @return	string
	 */
	function getType()
	{
		return in_array( strtolower($this->_type), array("button", "submit", "reset") ) ? $this->_type : "button";
	}

	/**
	 * prepare HTML for output
	 *
     * @return	string
	 */
	function render()
	{
		return "<input type='" . $this->getType() . "' class='formButton' name='" . $this->getName() . "'  id='" . $this->getName() . "' value='" . $this->getValue() . "' title='" . $this->getValue() . "'" . $this->getExtra() . " />";
	}
}
?>