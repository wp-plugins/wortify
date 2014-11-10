<?php
/**
 * WORTIFY Form Class Elements
 *
 * @copyright       The WORTIFY Project http://sourceforge.net/projects/xortify/ 
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.4.0
 * @author          John Neill <catzwolf@wortify.org>
 * @version         $Id: formbuttontray.php 8066 2011-11-06 05:09:33Z beckmi $
 * 
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 * WortifyFormButtonTray
 *
 * @author 		John Neill <catzwolf@wortify.org>
 * @package 	kernel
 * @subpackage 	form
 * @access 		public
 */
class WortifyFormButtonTray extends WortifyFormElement {
	/**
	 * Value
	 *
	 * @var string
	 * @access private
	 */
	var $_value;

	/**
	 * Type of the button. This could be either "button", "submit", or "reset"
	 *
	 * @var string
	 * @access private
	 */
	var $_type;

	/**
	 * WortifyFormButtonTray::WortifyFormButtonTray()
	 *
	 * @param mixed $name
	 * @param string $value
	 * @param string $type
	 * @param string $onclick
	 */
	function WortifyFormButtonTray( $name, $value = '', $type = '', $onclick = '', $showDelete = false ) {
		$this->setName( $name );
		$this->setValue( $value );
		$this->_type = ( !empty( $type ) ) ? $type : 'submit';
		$this->_showDelete = $showDelete;
		if ( $onclick ) {
			$this->setExtra( $onclick );
		} else {
			$this->setExtra( '' );
		}
	}

	/**
	 * WortifyFormButtonTray::getValue()
	 *
	 * @return
	 */
	function getValue() {
		return $this->_value;
	}

	/**
	 * WortifyFormButtonTray::setValue()
	 *
	 * @param mixed $value
	 * @return
	 */
	function setValue( $value ) {
		$this->_value = $value;
	}

	/**
	 * WortifyFormButtonTray::getType()
	 *
	 * @return
	 */
	function getType() {
		return $this->_type;
	}

	/**
	 * WortifyFormButtonTray::render()
	 *
	 * @return
	 */
	function render() {
		// onclick="this.form.elements.op.value=\'delfile\';
		$ret = '';
		if ( $this->_showDelete ) {
			$ret .= '<input type="submit" class="formbutton" name="delete" id="delete" value="' . _DELETE . '" onclick="this.form.elements.op.value=\'delete\'">&nbsp;';
		}
		$ret .= '<input type="button" value="' . _CANCEL . '" onClick="history.go(-1);return true;" />&nbsp;<input type="reset" class="formbutton"  name="reset"  id="reset" value="' . _RESET . '" />&nbsp;<input type="' . $this->getType() . '" class="formbutton"  name="' . $this->getName() . '"  id="' . $this->getName() . '" value="' . $this->getValue() . '"' . $this->getExtra() . '  />';
		return $ret;
	}
}

?>