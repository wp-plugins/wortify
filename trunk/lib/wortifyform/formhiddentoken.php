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
 * @version         $Id: formhiddentoken.php 8066 2011-11-06 05:09:33Z beckmi $
 */
 
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 * A hidden token field
 */
class WortifyFormHiddenToken extends WortifyFormHidden
{
    /**
     * Constructor
     *
     * @param string $name "name" attribute
     */
    function WortifyFormHiddenToken($name = 'WORTIFY_TOKEN', $timeout = 0)
    {
        $this->WortifyFormHidden($name . '_REQUEST', $GLOBALS['wortifySecurity']->createToken($timeout, $name));
    }
}

?>