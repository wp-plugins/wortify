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


include_once dirname(__FILE__) . strtolower('/wortifyform/Form.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/ThemeForm.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/SimpleForm.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormElement.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormElementTray.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormLabel.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormCheckBox.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormPassword.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormButton.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormButtonTray.php'); // To be cleaned
include_once dirname(__FILE__) . strtolower('/wortifyform/FormHidden.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormFile.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormRadio.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormRadioYN.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelect.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectGroup.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectCheckGroup.php'); // To be cleaned
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectUser.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectTheme.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectMatchOption.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectCountry.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectTimeZone.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectLang.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormSelectEditor.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormText.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormTextArea.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormTextDateSelect.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormDhtmlTextArea.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormDateTime.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormHiddenToken.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormColorPicker.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormCaptcha.php');
include_once dirname(__FILE__) . strtolower('/wortifyform/FormEditor.php');

?>