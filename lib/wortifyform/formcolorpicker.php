<?php
/**
 * WortifyFormColorPicker component class file
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
 * @author          Zoullou <webmaster@zoullou.org>
 * @version         $Id: formcolorpicker.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 * Color Selection Field
 *
 * @author 		Zoullou <webmaster@zoullou.org>
 * @author 		John Neill <catzwolf@wortify.org>
 * @copyright   The WORTIFY Project http://sourceforge.net/projects/xortify/ 
 * @version 	$Id: formcolorpicker.php 8066 2011-11-06 05:09:33Z beckmi $
 * @package 	Kernel
 * @access 		public
 */
class WortifyFormColorPicker extends WortifyFormText
{
    /**
     * WortifyFormColorPicker::WortifyFormColorPicker()
     *
     * @param mixed $caption
     * @param mixed $name
     * @param string $value
     */
    function WortifyFormColorPicker($caption, $name, $value = '#FFFFFF')
    {
        $this->WortifyFormText($caption, $name, 9, 7, $value);
    }
    
    /**
     * WortifyFormColorPicker::render()
     *
     * @return
     */
    function render()
    {
        if (isset($GLOBALS['xoTheme'])) {
            $GLOBALS['xoTheme']->addScript('include/color-picker.js');
        } else {
            echo '<script type="text/javascript" src="' . WORTIFY_URL . '/include/color-picker.js"></script>';
        }
        $this->setExtra(' style="background-color:' . $this->getValue() . ';"');
        return parent::render()  . "<input type='reset' value=' ... ' onclick=\"return TCP.popup('" . WORTIFY_URL . "/include/',document.getElementById('" . $this->getName() . "'));\">" ;
    }
    
    /**
     * Returns custom validation Javascript
     *
     * @return string Element validation Javascript
     */
    function renderValidationJS()
    {
        $eltname = $this->getName();
        $eltcaption = $this->getCaption();
        $eltmsg = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);
        
        return "if ( !(new RegExp(\"^#[0-9a-fA-F]{6}\",\"i\").test(myform.{$eltname}.value)) ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
    }
}

?>