<?php
/**
 * TextSanitizer extension
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
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: config.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('_WORTIFY_ROOT_PATH') or die('Restricted access');

return $config = array(
    'link' => _WORTIFY_URL . "/lib/mediawiki/?title=%s",// The link to wiki module
    'charset' => "UTF-8");// Charset of wiki module

?>