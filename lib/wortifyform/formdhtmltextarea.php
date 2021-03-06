<?php
/**
 * Formatted textarea form
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
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          Vinod <smartvinu@gmail.com>
 * @version         $Id: formdhtmltextarea.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');


/**
 * base class
 */
include_once (dirname(__FILE__). DIRECTORY_SEPARATOR .'formtextarea.php');

/**
 *  A textarea with wortifyish formatting and smilie buttons
 *
 */
class WortifyFormDhtmlTextArea extends WortifyFormTextArea
{
    /**
     * Extended HTML editor
     *
     * <p>If an extended HTML editor is set, the renderer will be replaced by the specified editor, usually a visual or WYSIWYG editor.</p>
     *
     * <ul>Developer and user guide:
     *                         <li><ul>For run-time settings per call
     *                                 <li>To use an editor pre-configured by {@link WortifyEditor}, e.g. 'fckeditor': <code>$options['editor'] = 'fckeditor';</code></li>
     *                                 <li>To use a custom editor, e.g. 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>$options['editor'] = array('MyEditor', WORTIFY_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
     *                             </ul></li>
     *                         <li><ul>For pre-configured settings, which will force to use a editor if no specific editor is set for call
     *                                 <li><ul>Set up custom configs: in WORTIFY_VAR_PATH . '/configs/wortifyconfig.php' set a editor as default, e.g.
     *                                         <li>a pre-configured editor 'fckeditor': <code>return array('editor' => 'fckeditor');</code></li>
     *                                         <li>a custom editor 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>return array('editor' => array('MyEditor', WORTIFY_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
     *                                     </ul></li>
     *                                 <li>To disable the default editor, in WORTIFY_VAR_PATH . '/configs/wortifyconfig.php': <code>return array();</code></li>
     *                                 <li>To disable the default editor for a specific call: <code>$options['editor'] = 'dhtmltextarea';</code></li>
     *                             </ul></li>
     * </ul>
     */
    var $htmlEditor = array();

    /**
     * Hidden text
     *
     * @var string
     * @access private
     */
    var $_hiddenText;

    var $skipPreview = false;
    var $doHtml = false;
    var $js = '';

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param string $value Initial text
     * @param int $rows Number of rows
     * @param int $cols Number of columns
     * @param string $hiddentext Identifier for hidden Text
     * @param array $options Extra options
     */
    function WortifyFormDhtmlTextArea($caption, $name, $value = "", $rows = 5, $cols = 50, $hiddentext = "wortifyHiddenText", $options = array())
    {
        global $wortifyConfig;
        static $inLoop = 0;

        $inLoop ++;
        // Second loop, invalid, return directly
        if ($inLoop > 2) {
            return;
        }
        // Else, initialize
        $this->WortifyFormTextArea($caption, $name, $value, $rows, $cols);
        $this->_hiddenText = $hiddentext;

        if ($inLoop > 1) {
            return;
        }
        if (!isset($options['editor'])) {
            if (isset($wortifyConfig['editor']))
                $options['editor'] = $wortifyConfig['editor'];
        }

        if (! empty($this->htmlEditor) || ! empty($options['editor'])) {
            $options['name'] = $this->getName();
            $options['value'] = $this->getValue();
            if (! empty($options['editor'])) {
                $this->htmlEditor = is_array($options['editor']) ? $options['editor'] : array($options['editor']);
            }

            if (count($this->htmlEditor) == 1) {
                wortify_load('WortifyEditorHandler');
                $editor_handler = &WortifyEditorHandler::getInstance();
                $this->htmlEditor = $editor_handler->get($this->htmlEditor[0], $options);
                if ($inLoop > 1) {
                    $this->htmlEditor = null;
                }
            } else {
                list ($class, $path) = $this->htmlEditor;
                include_once WORTIFY_ROOT_PATH . $path;
                if (class_exists($class)) {
                    $this->htmlEditor = new $class($options);
                }
                if ($inLoop > 1) {
                    $this->htmlEditor = null;
                }
            }
        }

        $inLoop = 0;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    function render()
    {
        if ($this->htmlEditor && is_object($this->htmlEditor)) {
            if (! isset($this->htmlEditor->isEnabled) || $this->htmlEditor->isEnabled) {
                return $this->htmlEditor->render();
            }
        }
        static $js_loaded;

        wortify_loadLanguage('formdhtmltextarea');
        $ret = "";
        // actions
        $ret .= $this->codeIcon() . "<br />\n";
        // fonts
        $ret .= $this->fontArray();
        // length checker
        $ret .= "<input type='button' onclick=\"WortifyCheckLength('" . $this->getName() . "', '" . $this->configs['maxlength'] . "', '" . _WORTIFY_FORM_ALT_LENGTH . "', '" . _WORTIFY_FORM_ALT_LENGTH_MAX . "');\" value=' ? ' title='" . _WORTIFY_FORM_ALT_CHECKLENGTH . "' />";
        $ret .= "<br />\n";
        // the textarea box
        $ret .= "<textarea id='" . $this->getName() . "' name='" . $this->getName() . "' title='". $this->getTitle() . "' onselect=\"wortifySavePosition('" . $this->getName() . "');\" onclick=\"wortifySavePosition('" . $this->getName() . "');\" onkeyup=\"wortifySavePosition('" . $this->getName() . "');\" cols='" . $this->getCols() . "' rows='" . $this->getRows() . "'" . $this->getExtra() . ">" . $this->getValue() . "</textarea><br />\n";

        if (empty($this->skipPreview)) {
            if (empty($GLOBALS['xoTheme'])) {
                $this->js .= implode("", file(WORTIFY_ROOT_PATH . "/class/textsanitizer/image/image.js"));
            } else {
                $GLOBALS['xoTheme']->addScript('/class/textsanitizer/image/image.js', array('type' => 'text/javascript'));
            }
            $button = // "<br />" .
"<input " . "   id='" . $this->getName() . "_preview_button'" . "   type='button' " . "   value='" . _PREVIEW . "' " . "   onclick=\"form_instantPreview('" . WORTIFY_URL . "', '" . $this->getName() . "','" . WORTIFY_URL . "/images', " . intval($this->doHtml) . ", '" . $GLOBALS['wortifySecurity']->createToken() . "')\"" . " />";
            $ret .= "<br />" . "<div id='" . $this->getName() . "_hidden' style='display: block;'> " . "   <fieldset>" . "       <legend>" . $button . "</legend>" . "       <div id='" . $this->getName() . "_hidden_data'>" . _WORTIFY_FORM_PREVIEW_CONTENT . "</div>" . "   </fieldset>" . "</div>";
        }
        // Load javascript
        if (empty($js_loaded)) {
            $javascript = (($this->js) ? '<script language="JavaScript" type="text/javascript">' . $this->js . '</script>' : '') . '<script language="JavaScript" type="text/javascript" src="' . WORTIFY_URL . '/include/formdhtmltextarea.js"></script>';
            $ret = $javascript . $ret;
            $js_loaded = true;
        }
        return $ret;
    }

    /**
     * WortifyFormDhtmlTextArea::codeIcon()
     *
     * @return
     */
    function codeIcon()
    {
        $textarea_id = $this->getName();
        $code = "<a name='moresmiley'></a>" . "<img src='" . WORTIFY_URL . "/images/url.gif' alt='" . _WORTIFY_FORM_ALT_URL . "' title='" . _WORTIFY_FORM_ALT_URL . "' onclick='wortifyCodeUrl(\"{$textarea_id}\", \"" . htmlspecialchars(_ENTERURL, ENT_QUOTES) . "\", \"" . htmlspecialchars(_ENTERWEBTITLE, ENT_QUOTES) . "\");' onmouseover='style.cursor=\"hand\"'/>&nbsp;" . "<img src='" . WORTIFY_URL . "/images/email.gif' alt='" . _WORTIFY_FORM_ALT_EMAIL . "' title='" . _WORTIFY_FORM_ALT_EMAIL . "' onclick='wortifyCodeEmail(\"{$textarea_id}\", \"" . htmlspecialchars(_ENTEREMAIL, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;" . "<img src='" . WORTIFY_URL . "/images/imgsrc.gif' alt='" . _WORTIFY_FORM_ALT_IMG . "' title='" . _WORTIFY_FORM_ALT_IMG . "' onclick='wortifyCodeImg(\"{$textarea_id}\", \"" . htmlspecialchars(_ENTERIMGURL, ENT_QUOTES) . "\", \"" . htmlspecialchars(_ENTERIMGPOS, ENT_QUOTES) . "\", \"" . htmlspecialchars(_IMGPOSRORL, ENT_QUOTES) . "\", \"" . htmlspecialchars(_ERRORIMGPOS, ENT_QUOTES) . "\", \"" . htmlspecialchars(_WORTIFY_FORM_ALT_ENTERWIDTH, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;" . "<img src='" . WORTIFY_URL . "/images/image.gif' alt='" . _WORTIFY_FORM_ALT_IMAGE . "' title='" . _WORTIFY_FORM_ALT_IMAGE . "' onclick='openWithSelfMain(\"" . WORTIFY_URL . "/imagemanager.php?target={$textarea_id}\",\"imgmanager\",400,430);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;" . "<img src='" . WORTIFY_URL . "/images/smiley.gif' alt='" . _WORTIFY_FORM_ALT_SMILEY . "' title='" . _WORTIFY_FORM_ALT_SMILEY . "' onclick='openWithSelfMain(\"" . WORTIFY_URL . "/misc.php?action=showpopups&amp;type=smilies&amp;target={$textarea_id}\",\"smilies\",300,475);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $myts = &MyTextSanitizer::getInstance();

        $extensions = array_filter($myts->config['extensions']);
        foreach(array_keys($extensions) as $key) {
            $extension = $myts->loadExtension($key);
            @list ($encode, $js) = $extension->encode($textarea_id);
            if (empty($encode))
                continue;
            $code .= $encode;
            if (! empty($js)) {
                $this->js .= $js;
            }
        }
        $code .= "<img src='" . WORTIFY_URL . "/images/code.gif' alt='" . _WORTIFY_FORM_ALT_CODE . "' title='" . _WORTIFY_FORM_ALT_CODE . "' onclick='wortifyCodeCode(\"{$textarea_id}\", \"" . htmlspecialchars(_ENTERCODE, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;" . "<img src='" . WORTIFY_URL . "/images/quote.gif' alt='" . _WORTIFY_FORM_ALT_QUOTE . "' title='" . _WORTIFY_FORM_ALT_QUOTE . "' onclick='wortifyCodeQuote(\"{$textarea_id}\", \"" . htmlspecialchars(_ENTERQUOTE, ENT_QUOTES) . "\");' onmouseover='style.cursor=\"hand\"'/>";

        $wortifyPreload =& WortifyPreload::getInstance();
        $wortifyPreload->triggerEvent('core.class.wortifyform.formdhtmltextarea.codeicon', array(&$code));
        return $code;
    }

    /**
     * WortifyFormDhtmlTextArea::fontArray()
     *
     * @return
     */
    function fontArray()
    {
        $textarea_id = $this->getName();
        $hiddentext = $this->_hiddenText;

        $fontStr = "<script type=\"text/javascript\" language=\"JavaScript\">" . "var _editor_dialog = ''" . "+ '<select id=\'{$textarea_id}Size\' onchange=\'wortifySetElementAttribute(\"size\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'" . "+ '<option value=\'SIZE\'>" . _SIZE . "</option>'";
        foreach($GLOBALS["formtextdhtml_sizes"] as $_val => $_name) {
            $fontStr .= " + '<option value=\'{$_val}\'>{$_name}</option>'";
        }
        ;
        $fontStr .= " + '</select> '";
        $fontStr .= "+ '<select id=\'{$textarea_id}Font\' onchange=\'wortifySetElementAttribute(\"font\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'" . "+ '<option value=\'FONT\'>" . _FONT . "</option>'";
        $fontarray = ! empty($GLOBALS["formtextdhtml_fonts"]) ? $GLOBALS["formtextdhtml_fonts"] : array("Arial" , "Courier" , "Georgia" , "Helvetica" , "Impact" , "Verdana" , "Haettenschweiler");
        foreach($fontarray as $font) {
            $fontStr .= " + '<option value=\'{$font}\'>{$font}</option>'";
        }
        ;
        $fontStr .= " + '</select> '";
        $fontStr .= "+ '<select id=\'{$textarea_id}Color\' onchange=\'wortifySetElementAttribute(\"color\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'" . "+ '<option value=\'COLOR\'>" . _COLOR . "</option>';" . "var _color_array = new Array('00', '33', '66', '99', 'CC', 'FF');
            for(var i = 0; i < _color_array.length; i ++) {
                for(var j = 0; j < _color_array.length; j ++) {
                    for(var k = 0; k < _color_array.length; k ++) {
                        var _color_ele = _color_array[i] + _color_array[j] + _color_array[k];
                        _editor_dialog += '<option value=\''+_color_ele+'\' style=\'background-color:#'+_color_ele+';color:#'+_color_ele+';\'>#'+_color_ele+'</option>';
                    }
                }
            }
            _editor_dialog += '</select>';";

        $fontStr .= "document.write(_editor_dialog); </script>";

        $styleStr = "<img src='" . WORTIFY_URL . "/images/bold.gif' alt='" . _WORTIFY_FORM_ALT_BOLD . "' title='" . _WORTIFY_FORM_ALT_BOLD . "' onmouseover='style.cursor=\"hand\"' onclick='wortifyMakeBold(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $styleStr .= "<img src='" . WORTIFY_URL . "/images/italic.gif' alt='" . _WORTIFY_FORM_ALT_ITALIC . "' title='" . _WORTIFY_FORM_ALT_ITALIC . "' onmouseover='style.cursor=\"hand\"' onclick='wortifyMakeItalic(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $styleStr .= "<img src='" . WORTIFY_URL . "/images/underline.gif' alt='" . _WORTIFY_FORM_ALT_UNDERLINE . "' title='" . _WORTIFY_FORM_ALT_UNDERLINE . "' onmouseover='style.cursor=\"hand\"' onclick='wortifyMakeUnderline(\"{$hiddentext}\", \"{$textarea_id}\");'/>&nbsp;";
        $styleStr .= "<img src='" . WORTIFY_URL . "/images/linethrough.gif' alt='" . _WORTIFY_FORM_ALT_LINETHROUGH . "' title='" . _WORTIFY_FORM_ALT_LINETHROUGH . "' onmouseover='style.cursor=\"hand\"' onclick='wortifyMakeLineThrough(\"{$hiddentext}\", \"{$textarea_id}\");' /></a>&nbsp;";

        $alignStr = "<img src='" . WORTIFY_URL . "/images/alignleft.gif' alt='" . _WORTIFY_FORM_ALT_LEFT . "' title='" . _WORTIFY_FORM_ALT_LEFT . "' onmouseover='style.cursor=\"hand\"' onclick='wortifyMakeLeft(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $alignStr .= "<img src='" . WORTIFY_URL . "/images/aligncenter.gif' alt='" . _WORTIFY_FORM_ALT_CENTER . "' title='" . _WORTIFY_FORM_ALT_CENTER . "' onmouseover='style.cursor=\"hand\"' onclick='wortifyMakeCenter(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $alignStr .= "<img src='" . WORTIFY_URL . "/images/alignright.gif' alt='" . _WORTIFY_FORM_ALT_RIGHT . "' title='" . _WORTIFY_FORM_ALT_RIGHT . "' onmouseover='style.cursor=\"hand\"' onclick='wortifyMakeRight(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $fontStr = $fontStr . "<br />\n{$styleStr}&nbsp;{$alignStr}&nbsp;\n";
        return $fontStr;
    }

    /**
     * WortifyFormDhtmlTextArea::renderValidationJS()
     *
     * @return
     */
    function renderValidationJS()
    {
        if ($this->htmlEditor && is_object($this->htmlEditor) && method_exists($this->htmlEditor, 'renderValidationJS')) {
            if (! isset($this->htmlEditor->isEnabled) || $this->htmlEditor->isEnabled) {
                return $this->htmlEditor->renderValidationJS();
            }
        }
        return parent::renderValidationJS();
    }
}

?>
