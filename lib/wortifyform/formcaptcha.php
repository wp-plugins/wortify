<?php
/**
 * WORTIFY form element of CAPTCHA
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
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: formcaptcha.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formelement.php');

/**
 * Usage of WortifyFormCaptcha
 *
 * For form creation:
 * Add form element where proper: <code>$wortifyform->addElement(new WortifyFormCaptcha($caption, $name, $skipmember, $configs));</code>
 *
 * For verification:
 * <code>
 *               wortify_load("captcha");
 *               $wortifyCaptcha =& WortifyCaptcha::getInstance();
 *               if (! $wortifyCaptcha->verify() ) {
 *                   echo $wortifyCaptcha->getMessage();
 *                   ...
 *               }
 * </code>
 */

/**
 * Wortify Form Captcha
 *
 * @author 			Taiwen Jiang <phppp@users.sourceforge.net>
 * @package 		kernel
 * @subpackage 		form
 */
class WortifyFormCaptcha extends WortifyFormElement
{
    var $captchaHandler;

    /**
     *
     * @param string $caption Caption of the form element, default value is defined in captcha/language/
     * @param string $name Name for the input box
     * @param boolean $skipmember Skip CAPTCHA check for members
     */
    function WortifyFormCaptcha($caption = '', $name = 'wortifycaptcha', $skipmember = true, $configs = array())
    {
        wortify_load('WortifyCaptcha');
        $this->captchaHandler = &WortifyCaptcha::getInstance();
        $configs['name'] = $name;
        $configs['skipmember'] = $skipmember;
        $this->captchaHandler->setConfigs($configs);
        if (! $this->captchaHandler->isActive()) {
            $this->setHidden();
        } else {
            $caption = ! empty($caption) ? $caption : $this->captchaHandler->getCaption();
            $this->setCaption($caption);
            $this->setName($name);
        }
    }

    function setConfig($name, $val)
    {
        return $this->captchaHandler->setConfig($name, $val);
    }

    function render()
    {
        // if (!$this->isHidden()) {
        return $this->captchaHandler->render();
        // }
    }

    function renderValidationJS()
    {
        return $this->captchaHandler->renderValidationJS();
    }
}

?>