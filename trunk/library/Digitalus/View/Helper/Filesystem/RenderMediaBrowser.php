<?php
/**
 * RenderMediaBrowser helper
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
 * @version     $Id: RenderMediaBrowser.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderMediaBrowser helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper  Digitalus_View_Helper_GetIconByFiletype
 * @uses        viewHelper  Digitalus_View_Helper_RenderMediaBrowser
 */
class Digitalus_View_Helper_Filesystem_RenderMediaBrowser extends Zend_View_Helper_Abstract
{
    public function renderMediaBrowser($path, $folderLink, $fileLink)
    {
        $folders = Digitalus_Filesystem_Dir::getDirectories($path);
        $files   = Digitalus_Filesystem_File::getFilesByType($path, false, false, true);
        $links   = null;

        if (is_array($folders) && count($folders) > 0) {
            foreach ($folders as $folder) {
                $folderPath = $path . '/' . $folder;
                $link = Digitalus_Toolbox_String::addUnderscores($folderPath);
                //remove reference to media
                $link    = str_replace('media_', '', $link);
                $submenu = $this->view->renderMediaBrowser($folderPath, $folderLink, $fileLink);
                $links[] = '<li class="menuItem">' . $this->view->link($folder, '/' . $folderLink . '/' . $link, 'folder.png') . $submenu . '</li>';
            }
        }

        if (is_array($files) && count($files) > 0) {
            foreach ($files as $file) {
                if (substr($file,0,1) != '.') {
                    $filePath = $path . '/' . $file;
                    $links[] = '<li class="menuItem">' .
                    $this->view->link($file , $fileLink . '/' . $filePath, $this->view->getIconByFiletype($file, false)) . '</li>';
                }
            }
        }

        if (is_array($links)) {
            $filetree = '<ul id="fileTree" class="treeview">' . implode(null, $links) . '</ul>';
            return  $filetree;
        }
        return null;
    }
}