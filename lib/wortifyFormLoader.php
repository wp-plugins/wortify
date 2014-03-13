<?php
/**
 * XOOPS form class loader
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id: xoopsformloader.php 8066 2011-11-06 05:09:33Z beckmi $
 */


include_once dirname(__FILE__) . strtolower('/wortifyform/Form');
include_once dirname(__FILE__) . strtolower('/wortifyform/ThemeForm');
include_once dirname(__FILE__) . strtolower('/wortifyform/SimpleForm');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormElement');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormElementTray');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormLabel');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormCheckBox');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormPassword');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormButton');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormButtonTray'); // To be cleaned
include_once dirname(__FILE__) . strtolower('/wortifyform/FormHidden');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormFile');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormRadio');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormRadioYN');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelect');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectGroup');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectCheckGroup'); // To be cleaned
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectUser');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectTheme');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectMatchOption');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectCountry');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectTimeZone');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectLang');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectEditor');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormText');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormTextArea');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormTextDateSelect');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormDhtmlTextArea');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormDateTime');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormHiddenToken');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormColorPicker');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormCaptcha');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormEditor');

?>