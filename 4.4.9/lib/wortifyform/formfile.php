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
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.wortify.org/
 * @version         $Id: formfile.php 8066 2011-11-06 05:09:33Z beckmi $
 */
 
defined('WORTIFY_ROOT_PATH') or die('Restricted access');

/**
 * A file upload field
 */
class WortifyFormFile extends WortifyFormElement
{
    /**
     * Maximum size for an uploaded file
     *
     * @var int
     * @access private
     */
    var $_maxFileSize;
    
    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param int $maxfilesize Maximum size for an uploaded file
     */
    function WortifyFormFile($caption, $name, $maxfilesize)
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_maxFileSize = intval($maxfilesize);
    }
    
    /**
     * Get the maximum filesize
     *
     * @return int
     */
    function getMaxFileSize()
    {
        return $this->_maxFileSize;
    }
    
    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    function render()
    {
        return '<input type="hidden" name="MAX_FILE_SIZE" value="' . $this->getMaxFileSize() . '" /><input type="file" name="' . $this->getName() . '" id="' . $this->getName() . '" title="' . $this->getTitle() . '" ' .$this->getExtra() . ' /><input type="hidden" name="wortify_upload_file[]" id="wortify_upload_file[]" value="' . $this->getName() . '" />';		
    }
}

?>