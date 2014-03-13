<?php
/**
 * WORTIFY listing utilities
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The WORTIFY Project http://sourceforge.net/projects/xortify/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package     kernel
 * @since       2.0.0
 * @version     $Id: wortifylists.php 10981 2013-02-04 19:37:48Z trabis $
 */


defined('WORTIFY_ROOT_PATH') or die('Restricted access');

if (!defined('WORTIFY_LISTS_INCLUDED')) {
    define('WORTIFY_LISTS_INCLUDED', 1);

    /**
     * WortifyLists
     *
     * @author John Neill <catzwolf@wortify.org>
     * @copyright copyright (c) WORTIFY.org
     * @package kernel
     * @subpackage form
     * @access public
     */
    class WortifyLists
    {
        /**
         * gets list of name of directories inside a directory
         */
        static function getDirListAsArray($dirname)
        {
            $ignored = array(
                'cvs' ,
                '_darcs');
            $list = array();
            if (substr($dirname, - 1) != '/') {
                $dirname .= '/';
            }
            if ($handle = opendir($dirname)) {
                while ($file = readdir($handle)) {
                    if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
                        continue;
                    if (is_dir($dirname . $file)) {
                        $list[$file] = $file;
                    }
                }
                closedir($handle);
                asort($list);
                reset($list);
            }
            return $list;
        }

        /**
         * gets list of all files in a directory
         */
        static function getFileListAsArray($dirname, $prefix = '')
        {
            $filelist = array();
            if (substr($dirname, - 1) == '/') {
                $dirname = substr($dirname, 0, - 1);
            }
            if (is_dir($dirname) && $handle = opendir($dirname)) {
                while (false !== ($file = readdir($handle))) {
                    if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
                        $file = $prefix . $file;
                        $filelist[$file] = $file;
                    }
                }
                closedir($handle);
                asort($filelist);
                reset($filelist);
            }
            return $filelist;
        }

        /**
         * gets list of image file names in a directory
         */
        static function getImgListAsArray($dirname, $prefix = '')
        {
            $filelist = array();
            if ($handle = opendir($dirname)) {
                while (false !== ($file = readdir($handle))) {
                    if (preg_match('/(\.gif|\.jpg|\.png)$/i', $file)) {
                        $file = $prefix . $file;
                        $filelist[$file] = $file;
                    }
                }
                closedir($handle);
                asort($filelist);
                reset($filelist);
            }
            return $filelist;
        }

        /**
         * gets list of html file names in a certain directory
         */
        static function getHtmlListAsArray($dirname, $prefix = '')
        {
            $filelist = array();
            if ($handle = opendir($dirname)) {
                while (false !== ($file = readdir($handle))) {
                    if ((preg_match('/(\.htm|\.html|\.xhtml)$/i', $file) && ! is_dir($file))) {
                        $file = $prefix . $file;
                        $filelist[$file] = $prefix . $file;
                    }
                }
                closedir($handle);
                asort($filelist);
                reset($filelist);
            }
            return $filelist;
        }

        /**
         * WortifyLists::getHtmlList()
         *
         * This Function is no longer being used by the core
         *
         * @return array
         */
        static function getHtmlList()
        {
            $html_list = array(
                'a' => '&lt;a&gt;',
                'abbr' => '&lt;abbr&gt;',
                'acronym' => '&lt;acronym&gt;',
                'address' => '&lt;address&gt;',
                'b' => '&lt;b&gt;',
                'bdo' => '&lt;bdo&gt;',
                'big' => '&lt;big&gt;',
                'blockquote' => '&lt;blockquote&gt;',
                'br' => '&lt;br&gt;',
                'caption' => '&lt;caption&gt;',
                'cite' => '&lt;cite&gt;',
                'code' => '&lt;code&gt;',
                'col' => '&lt;col&gt;',
                'colgroup' => '&lt;colgroup&gt;',
                'dd' => '&lt;dd&gt;',
                'del' => '&lt;del&gt;',
                'dfn' => '&lt;dfn&gt;',
                'div' => '&lt;div&gt;',
                'dl' => '&lt;dl&gt;',
                'dt' => '&lt;dt&gt;',
                'em' => '&lt;em&gt;',
                'font' => '&lt;font&gt;',
                'h1' => '&lt;h1&gt;',
                'h2' => '&lt;h2&gt;',
                'h3' => '&lt;h3&gt;',
                'h4' => '&lt;h4&gt;',
                'h5' => '&lt;h5&gt;',
                'h6' => '&lt;h6&gt;',
                'hr' => '&lt;hr&gt;',
                'i' => '&lt;i&gt;',
                'img' => '&lt;img&gt;',
                'ins' => '&lt;ins&gt;',
                'kbd' => '&lt;kbd&gt;',
                'li' => '&lt;li&gt;',
                'map' => '&lt;map&gt;',
                'object' => '&lt;object&gt;',
                'ol' => '&lt;ol&gt;',
                'p' => '&lt;p&gt;',
                'pre' => '&lt;pre&gt;',
                's' => '&lt;s&gt;',
                'samp' => '&lt;samp&gt;',
                'small' => '&lt;small&gt;',
                'span' => '&lt;span&gt;',
                'strike' => '&lt;strike&gt;',
                'strong' => '&lt;strong&gt;',
                'sub' => '&lt;sub&gt;',
                'sup' => '&lt;sup&gt;',
                'table' => '&lt;table&gt;',
                'tbody' => '&lt;tbody&gt;',
                'td' => '&lt;td&gt;',
                'tfoot' => '&lt;tfoot&gt;',
                'th' => '&lt;th&gt;',
                'thead' => '&lt;thead&gt;',
                'tr' => '&lt;tr&gt;',
                'tt' => '&lt;tt&gt;',
                'u' => '&lt;u&gt;',
                'ul' => '&lt;ul&gt;',
                'var' => '&lt;var&gt;');
            asort($html_list);
            reset($html_list);
            return $html_list;
        }

    }
}

?>