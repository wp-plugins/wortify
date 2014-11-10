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
 * @version         $Id: formselectlang.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('_WORTIFY_ROOT_PATH') or die('Restricted access');

include_once (dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR .'wortifyLists.php');
include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formselect.php');

/**
 * A select field with available languages
 */
class WortifyFormSelectLang extends WortifyFormSelect
{
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param mixed $value Pre-selected value (or array of them).
     * 							Legal is any name of a _WORTIFY_ROOT_PATH."/language/" subdirectory.
     * @param int $size Number of rows. "1" makes a drop-down-list.
     */
    function WortifyFormSelectLang($caption, $name, $value = null, $size = 1)
    {
        $this->WortifyFormSelect($caption, $name, $value, $size);
        $this->addOptionArray(WortifyLists::getLangList());
    }
}

?>