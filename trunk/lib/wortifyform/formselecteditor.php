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
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: formselecteditor.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('WORTIFY_ROOT_PATH') or die('Restricted access');

include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formelementtray.php');

/**
 * WortifyFormSelectEditor
 */
class WortifyFormSelectEditor extends WortifyFormElementTray
{
    var $allowed_editors = array();
    var $form;
    var $value;
    var $name;
    var $nohtml;

    /**
     * Constructor
     *
     * @param object $form the form calling the editor selection
     * @param string $name editor name
     * @param string $value Pre-selected text value
     * @param bool $noHtml dohtml disabled
     */
    function WortifyFormSelectEditor(&$form, $name = 'editor', $value = null, $nohtml = false, $allowed_editors = array())
    {
        $this->WortifyFormElementTray(_SELECT);
        $this->allowed_editors = $allowed_editors;
        $this->form = &$form;
        $this->name = $name;
        $this->value = $value;
        $this->nohtml = $nohtml;
    }

    /**
     * WortifyFormSelectEditor::render()
     *
     * @return
     */
    function render()
    {
        wortify_load('WortifyEditorHandler');
        $editor_handler = WortifyEditorHandler::getInstance();
        $editor_handler->allowed_editors = $this->allowed_editors;
        $option_select = new WortifyFormSelect("", $this->name, $this->value);
        $extra = 'onchange="if(this.options[this.selectedIndex].value.length > 0 ){
			window.document.forms.' . $this->form->getName() . '.submit();
			}"';
        $option_select->setExtra($extra);
        $option_select->addOptionArray($editor_handler->getList($this->nohtml));
        $this->addElement($option_select);
        return parent::render();
    }
}

?>