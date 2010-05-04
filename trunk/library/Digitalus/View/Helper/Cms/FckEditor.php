<?php
/**
 * FckEditor helper
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      Forrest Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * FckEditor helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Cms_FckEditor extends Zend_View_Helper_Abstract
{

    /**
     * i know it is not well liked to output this here, but for integration purposes it makes sense
     */
    public function fckEditor($instance = 'content', $value = 'Enter text here', $height = 600, $width = 600, $fullToolbar = true)
    {
        include('Digitalus/editor/fckeditor.php') ;
        ?>
        <script>
        function FCKeditor_OnComplete( editorInstance )
        {
        }
        </script>

        <?php
        $sBasePath = '/public/scripts/fckeditor/' ;

        $oFCKeditor = new FCKeditor($instance) ;
        $oFCKeditor->BasePath = $sBasePath ;
        $oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
        $oFCKeditor->Width  = $width ;
        $oFCKeditor->Height = $height ;
        if ($fullToolbar) {
            $oFCKeditor->ToolbarSet = 'Digitalus' ;
        } else {
            $oFCKeditor->ToolbarSet = 'Basic' ;
        }
        $oFCKeditor->Value = $this->view->getTranslation($value);

        $oFCKeditor->Create() ;

    }
}