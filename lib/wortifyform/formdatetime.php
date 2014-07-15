<?php
/**
 * WORTIFY form element of datetime
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
 * @version         $Id: formdatetime.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 * Date and time selection field
 *
 * @author 		Kazumi Ono <onokazu@wortify.org>
 * @package 	kernel
 * @subpackage 	form
 * @access 		public
 */
class WortifyFormDateTime extends WortifyFormElementTray
{
    /**
     * WortifyFormDateTime::WortifyFormDateTime()
     *
     * @param mixed $caption
     * @param mixed $name
     * @param integer $size
     * @param integer $value
     * @param mixed $showtime
     */
    function WortifyFormDateTime($caption, $name, $size = 15, $value = 0, $showtime = true)
    {
        $this->WortifyFormElementTray($caption, '&nbsp;');
        $value = intval($value);
        $value = ($value > 0) ? $value : time();
        $datetime = getDate($value);
        $this->addElement(new WortifyFormTextDateSelect('', $name . '[date]', $size, $value, $showtime));
        $timearray = array();
        for ($i = 0; $i < 24; $i ++) {
            for ($j = 0; $j < 60; $j = $j + 10) {
                $key = ($i * 3600) + ($j * 60);
                $timearray[$key] = ($j != 0) ? $i . ':' . $j : $i . ':0' . $j;
            }
        }
        ksort($timearray);

        $timeselect = new WortifyFormSelect('', $name . '[time]', $datetime['hours'] * 3600 + 600 * ceil($datetime['minutes'] / 10));
        $timeselect->addOptionArray($timearray);
        $this->addElement($timeselect);
    }
}

?>