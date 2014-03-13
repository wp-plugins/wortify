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
 * TextSanitizer extension
 *
 * @copyright       The WORTIFY Project http://sourceforge.net/projects/xortify/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: wiki.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

class WortifytsWiki extends WortifyTextSanitizerExtension
{
    function encode($textarea_id)
    {
        $config = parent::loadConfig(dirname(__FILE__));
        $code = "<img src='{$this->image_path}/wiki.gif' alt='" . _WORTIFY_FORM_ALTWIKI . "' onclick='wortifyCodeWiki(\"{$textarea_id}\",\"" . htmlspecialchars(_WORTIFY_FORM_ENTERWIKITERM, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOH
            function wortifyCodeWiki(id, enterWikiPhrase){
                if (enterWikiPhrase == null) {
                    enterWikiPhrase = "Enter the word to be linked to Wiki:";
                }
                var selection = wortifyGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                }else {
                    var text = prompt(enterWikiPhrase, "");
                }
                var domobj = wortifyGetElementById(id);
                if ( text != null && text != "" ) {
                    var result = "[[" + text + "]]";
                    wortifyInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
        return array(
            $code ,
            $javascript);
    }

    function load(&$ts)
    {
        $ts->patterns[] = "/\[\[([^\]]*)\]\]/esU";
        $ts->replacements[] = __CLASS__ . "::decode( '\\1' )";
    }

    function decode($text)
    {
        $config = parent::loadConfig(dirname(__FILE__));
        if (empty($text) || empty($config['link'])) {
            return $text;
        }
        $charset = !empty($config['charset']) ? $config['charset'] : "UTF-8";
        wortify_load('WortifyLocal');
        $ret = "<a href='" . sprintf($config['link'], urlencode(WortifyLocal::convert_encoding($text, $charset))) . "' rel='external' title=''>{$text}</a>";
        return $ret;
    }
}
?>