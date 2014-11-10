<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *  Wortify Form Class Elements
 *
 * @copyright       The WORTIFY Project http://sourceforge.net/projects/xortify/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.3.0
 * @author          John Neill <catzwolf@wortify.org>
 * @version         $Id: formselectcheckgroup.php 9320 2012-04-14 16:32:46Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formcheckbox.php');
/**
 * Wortify Form Select Check Groups
 *
 * @author 		John Neill <catzwolf@wortify.org>
 * @copyright   The WORTIFY Project http://sourceforge.net/projects/xortify/
 * @package 	kernel
 * @subpackage 	form
 * @access 		public
 */
class WortifyFormSelectCheckGroup extends WortifyFormCheckBox
{
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param bool $include_anon Include user "anonymous"?
     * @param mixed $value Pre-selected value (or array of them).
     * @param int $size Number or rows. "1" makes a drop-down-list.
     * @param bool $multiple Allow multiple selections?
     */
    function WortifyFormSelectCheckGroup($caption, $name, $value = null, $size = 1, $multiple = false)
    {
        $member_handler = &wortify_gethandler('member');
        $this->userGroups = $member_handler->getGroupList();
        $this->WortifyFormCheckBox($caption, $name, $value, '', true);
        $this->columns = 3;
        foreach($this->userGroups as $group_id => $group_name) {
            $this->addOption($group_id, $group_name);
        }
    }
}

?>
