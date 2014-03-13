<?php
/**
 * user select with page navigation
 *
 * limit: Only work with javascript enabled
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
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: formselectuser.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formelementtray.php');
include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formselect.php');

/**
 * User Select field
 */
class WortifyFormSelectUser extends WortifyFormElementTray
{
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param mixed $value Pre-selected value (or array of them).
     *                                                 For an item with massive members, such as "Registered Users", "$value" should be used to store selected temporary users only instead of all members of that item
     * @param bool $include_anon Include user "anonymous"?
     * @param int $size Number or rows. "1" makes a drop-down-list.
     * @param bool $multiple Allow multiple selections?
     */
    function WortifyFormSelectUser($caption, $name, $include_anon = false, $value = null, $size = 1, $multiple = false)
    {
        $limit = 200;
        $select_element = new WortifyFormSelect('', $name, $value, $size, $multiple);
        if ($include_anon) {
            $select_element->addOption(0, $GLOBALS['wortifyConfig']['anonymous']);
        }
        $member_handler =& wortify_gethandler('member');
        $user_count = $member_handler->getUserCount();
        $value = is_array($value) ? $value : (empty($value) ? array() : array($value));
        if ($user_count > $limit && count($value) > 0) {
            $criteria = new CriteriaCompo(new Criteria('uid', '(' . implode(',', $value) . ')', 'IN'));
        } else {
            $criteria = new CriteriaCompo();
            $criteria->setLimit($limit);
        }
        $criteria->setSort('uname');
        $criteria->setOrder('ASC');
        $users = $member_handler->getUserList($criteria);
        $select_element->addOptionArray($users);
        if ($user_count <= $limit) {
            $this->WortifyFormElementTray($caption, "", $name);
            $this->addElement($select_element);
            return;
        }

        wortify_loadLanguage('findusers');
        $js_addusers = "<script type='text/javascript'>
            function addusers(opts){
                var num = opts.substring(0, opts.indexOf(':'));
                opts = opts.substring(opts.indexOf(':')+1, opts.length);
                var sel = wortifyGetElementById('" . $name . "');
                var arr = new Array(num);
                for (var n=0; n < num; n++) {
                    var nm = opts.substring(0, opts.indexOf(':'));
                    opts = opts.substring(opts.indexOf(':')+1, opts.length);
                    var val = opts.substring(0, opts.indexOf(':'));
                    opts = opts.substring(opts.indexOf(':')+1, opts.length);
                    var txt = opts.substring(0, nm - val.length);
                    opts = opts.substring(nm - val.length, opts.length);
                    var added = false;
                    for (var k = 0; k < sel.options.length; k++) {
                        if(sel.options[k].value == val){
                            added = true;
                            break;
                        }
                    }
                    if (added == false) {
                        sel.options[k] = new Option(txt, val);
                        sel.options[k].selected = true;
                    }
                }
                return true;
            }
            </script>";
        $token = $GLOBALS['wortifySecurity']->createToken();
        $action_tray = new WortifyFormElementTray("", " | ");
        $action_tray->addElement(new WortifyFormLabel('', '<a href="#" onclick="var sel = wortifyGetElementById(\'' . $name . '\');for (var i = sel.options.length-1; i >= 0; i--) {if (!sel.options[i].selected) {sel.options[i] = null;}}; return false;">' . _MA_USER_REMOVE . "</a>"));
        $action_tray->addElement(new WortifyFormLabel('', '<a href="#" onclick="openWithSelfMain(\'' . WORTIFY_URL . '/include/findusers.php?target=' . $name . '&amp;multiple=' . $multiple . '&amp;token=' . $token . '\', \'userselect\', 800, 600, null); return false;" >' . _MA_USER_MORE . "</a>" . $js_addusers));
        $this->WortifyFormElementTray($caption, '<br /><br />', $name);
        $this->addElement($select_element);
        $this->addElement($action_tray);
    }
}

?>