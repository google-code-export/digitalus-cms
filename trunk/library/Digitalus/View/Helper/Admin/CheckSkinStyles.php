<?php
/**
 * CheckSkinStyles helper
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
 * @version     $Id: CheckSkinStyles.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * CheckSkinStyles helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Admin_CheckSkinStyles extends Zend_View_Helper_Abstract
{
    public $partialFile = 'design/listSkin.phtml';

    /**
     *
     */
    public function checkSkinStyles($name, $values)
    {
        $config = Zend_Registry::get('config');
        $basePath = $config->design->pathToSkins;
        $xhtml = array();
        $this->view->name = $name;
        $this->view->selectedStyles = $values;

        //load the skin folders
        if (is_dir('./' . $basePath)) {
            $folders = Digitalus_Filesystem_Dir::getDirectories('./' . $basePath);
            if (count($folders) > 0) {
                foreach ($folders as $folder) {
                    $this->view->skin = $folder;
                    $styles = Digitalus_Filesystem_File::getFilesByType('./' . $basePath . '/' . $folder . '/styles', 'css');
                    if (is_array($styles)) {
                        foreach ($styles  as $style) {
                            //add each style sheet to the hash
                            // key = path / value = filename
                            $hashStyles[$style] = $style;
                        }
                        $this->view->styles = $hashStyles;
                        $xhtml[] = $this->view->render($this->partialFile);
                        unset($hashStyles);
                    }
                }
            }
        } else {
            require_once 'Digitalus/View/Exception.php';
            throw new Digitalus_View_Exception($this->view->getTranslation('Unable to locate skin folder'));
        }
        return implode(null, $xhtml);
    }
}