<?php
/**
 * Digitalus CMS
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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Media Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_MediaController extends Zend_Controller_Action
{
    /**
     * @var string
     */
    protected $_fullPathToMedia;

    /**
     * @var string
     */
    protected $_pathToMedia;

    /**
     * @var string
     */
    protected $_currentFolder;

    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $config = Zend_Registry::get('config');
        $this->_pathToMedia = $this->getFrontController()->getBaseUrl() . $config->filepath->media;
        $this->_fullPathToMedia = Digitalus_Toolbox_String::stripLeading('/', $this->getFrontController()->getBaseUrl() . '/' . $this->_pathToMedia);
        $this->view->pathToMedia = $this->_pathToMedia;
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Media') => $this->getFrontController()->getBaseUrl() . '/admin/media'
        );
    }

    /**
     * The default action - show the home page
     *
     * @return void
     */
    public function indexAction() {
        $this->view->path = '';
    }

    /**
     * Open Folder Action
     *
     * @return void
     */
    public function openFolderAction()
    {
        $folder = $this->_request->getParam('folder');
        $folder = str_replace('media_', '', $folder);

        $this->view->path = $folder;
        $folder = Digitalus_Toolbox_String::stripHyphens($folder);

        $folder = Digitalus_Toolbox_String::stripLeading('_', $folder);
        $folderArray = explode('_', $folder);

        if (is_array($folderArray)) {
            foreach ($folderArray as $pathPart) {
                if (!empty($pathPart)) {
                    $fullPathParts[] = $pathPart;
                    $fullPath = implode('_', $fullPathParts);
                    $folderPathParts[$fullPath] = $pathPart;
                }
            }
        }
        if (isset($folderPathParts) && !empty($folderPathParts)) {
            $this->view->folderPathParts = $folderPathParts;
        }

        $pathToFolder = $this->_fullPathToMedia . '/' . Digitalus_Toolbox_String::stripUnderscores($folder);
        $this->view->filesystemPath = $pathToFolder;
        $this->view->mediaPath = $folder;
        $this->view->folders = Digitalus_Filesystem_Dir::getDirectories($pathToFolder);
        $this->view->files = Digitalus_Filesystem_File::getFilesByType($pathToFolder, false, false, true);

        $this->view->breadcrumbs[$this->view->getTranslation('Open Folder') . ': ' . $pathToFolder] = $this->getFrontController()->getBaseUrl() . '/admin/media/open-folder/folder/' . $folder;
        $this->view->toolbarLinks = array();

        $tmpPath = Digitalus_Toolbox_String::addUnderscores($folder);
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/admin_media_open-folder_folder_' . $tmpPath
            . '/label/' . $this->view->getTranslation('Media') . ':' . $pathToFolder;
        $this->view->toolbarLinks['Delete'] = $this->getFrontController()->getBaseUrl() . '/admin/media/delete-folder/folder/' . $folder;

    }

    /**
     * Create Folder Action
     *
     * @throws Zend_Exception
     * @return void
     */
    public function createFolderAction()
    {
        $baseFolder = Digitalus_Filter_Post::get('path');
        $newFolder  = Digitalus_Filter_Post::get('folder_name');

        //dont allow access outside the media folder
        if (strpos($baseFolder, './') || strpos($newFolder, './')) {
            throw new Zend_Exception('Illegal file access attempt. Operation cancelled!');
        }

        $forwardPath = $baseFolder;
        if (!empty($newFolder)) {
            $fullPath = $this->_pathToMedia;

            $base = str_replace('media_', '', $baseFolder);

            if (!empty($base)) {
                $base = Digitalus_Toolbox_String::stripUnderscores($base);
                $fullPath .= '/' . $base;
            }
            $fullPath .= '/' . $newFolder;

            if (!file_exists($fullPath)) {
                $result = @mkdir($fullPath, 0777);
            } else {
                $exists = true;
            }

            if (isset($result) || isset($exists)) {
                $forwardPath .= '_' . $newFolder;
            }
        }

        $this->_request->setParam('folder', $forwardPath);
        $this->_forward('open-folder');
    }

    /**
     * Upload Action
     *
     * @return void
     */
    public function uploadAction()
    {
        $path = Digitalus_Filter_Post::get('filepath');
        $mediapath = Digitalus_Filter_Post::get('mediapath');
        $files = $_FILES['uploads'];
        if (is_array($files)) {
            $result = Digitalus_Media::batchUpload($files, $path);
        }
        $this->_request->setParam('folder', $mediapath);

        $this->_forward('open-folder');
    }

    /**
     * Delete Folder Action
     *
     * @return void
     */
    public function deleteFolderAction()
    {
        $folder = $this->_request->getParam('folder');
        Digitalus_Media::deleteFolder($folder);
        $folderPath = Digitalus_Toolbox_String::stripUnderscores($folder);
        $parent = Digitalus_Toolbox_String::getParentFromPath($folderPath);
        $cleanParent = Digitalus_Toolbox_String::addUnderscores($parent);
        $this->_request->setParam('folder', $cleanParent);
        $this->_forward('open-folder');
    }

    /**
     * Delete File Action
     *
     * @return void
     */
    public function deleteFileAction()
    {
        $file = $this->_request->getParam('file');
        Digitalus_Media::deleteFile($file);
        $filePath = Digitalus_Toolbox_String::stripUnderscores($file);
        $parent = Digitalus_Toolbox_String::getParentFromPath($filePath);
        $cleanParent = Digitalus_Toolbox_String::addUnderscores($parent);
        $this->_request->setParam('folder', $cleanParent);
        $this->_forward('open-folder');
    }

    /**
     * Rename Folder Action
     *
     * @return void
     */
    public function renameFolderAction()
    {
        $path = Digitalus_Media::renameFolder(
            Digitalus_Filter_Post::get('filepath'),
            Digitalus_Filter_Post::get('folder_name')
        );
        $path = str_replace('./', '', $path);
        $path = str_replace('../', '', $path);

        $folder = Digitalus_Toolbox_String::addUnderscores($path);

        $this->_request->setParam('folder', $folder);
        $this->_forward('open-folder');
    }
}
?>