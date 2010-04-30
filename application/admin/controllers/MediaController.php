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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */

/**
 * @see Digitalus_Controller_Action
 */
require_once 'Digitalus/Controller/Action.php';

/**
 * Admin Media Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */
class Admin_MediaController extends Digitalus_Controller_Action
{
    /**
     * @var string
     */
    protected $_mediaFolder;

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
        parent::init();

        $config = Zend_Registry::get('config');
        $this->_pathToMedia      = APPLICATION_PATH . '/../' . $config->filepath->media;
        $this->_mediaFolder      = $config->filepath->media;
        $this->view->mediaFolder = $config->filepath->media;
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Media') => $this->baseUrl . '/admin/media'
        );
    }

    /**
     * The default action - show the home page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_forward('open-folder');
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

        $data = array();
        $data['path'] = $folder;

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
            $data['folderPathParts'] = $folderPathParts;
            $data['label']           = array_pop($folderPathParts);
        }

        $pathToFolder        = Digitalus_Toolbox_String::stripUnderscores($folder);
        $data['filepath']    = $pathToFolder;
        $data['mediapath']   = $folder;
        $data['folders']     = Digitalus_Filesystem_Dir::getDirectories($this->_pathToMedia . '/' . $pathToFolder);
        $data['files']       = Digitalus_Filesystem_File::getFilesByType($this->_pathToMedia . '/' . $pathToFolder, false, false, true);
        $data['mediaFolder'] = $this->view->mediaFolder;

        $form = new Admin_Form_Media(null, $data);
        $form->setDecorators(array(
            'FormElements',
            'Form',
            array('FormErrors', array('placement' => 'prepend'))
        ));

        if ($this->_request->isPost() && Digitalus_Filter_Post::has('form_instance')) {
            $path          = Digitalus_Filter_Post::get('path');
            $filePath      = Digitalus_Filter_Post::get('filepath');
            $mediaPath     = Digitalus_Filter_Post::get('mediapath');
            $folderName    = Digitalus_Filter_Post::get('folder_name');
            $newFolderName = Digitalus_Filter_Post::get('new_folder_name');

            // indicator if it is a return of one of the other actions
            if (false == $this->_request->getParam('return')) {
                // createFolderAction
                if ($form->isValidPartial(array('path' => $path, 'folder_name' => $folderName))
                    && isset($_POST['createFolderSubmit']) && !empty($_POST['createFolderSubmit']))
                {
                    $this->_request->setParam('path',        $path);
                    $this->_request->setParam('folder_name', $folderName);
                    $this->_forward('create-folder');
                // renameFolderAction
                } else if ($form->isValidPartial(array('filepath' => $filePath, 'new_folder_name' => $newFolderName))
                    && isset($_POST['renameFolderSubmit']) && !empty($_POST['renameFolderSubmit']))
                {
                    $this->_request->setParam('filepath',    $filePath);
                    $this->_request->setParam('new_folder_name', $newFolderName);
                    $this->_forward('rename-folder');
                // uploadAction
                } else if ($form->isValidPartial(array('filepath' => $filePath, 'mediapath' => $mediaPath))
                    && isset($_POST['uploadSubmit']) && !empty($_POST['uploadSubmit']))
                {
                    $this->_request->setParam('filepath',  $filePath);
                    $this->_request->setParam('mediapath', $mediaPath);
                    $this->_forward('upload');
                }
            }
        }
        $this->view->form = $form;

        $tmpPath = Digitalus_Toolbox_String::addUnderscores($folder);
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark'
            . '/url/admin_media_open-folder_folder_' . $tmpPath
            . '/label/' . $this->view->getTranslation('Media') . ':' . $pathToFolder;
        $this->view->toolbarLinks['Delete'] = $this->baseUrl . '/admin/media/delete-folder/folder/' . $folder;
        $this->view->breadcrumbs[$this->view->getTranslation('Open Folder') . ': ' . Digitalus_Toolbox_String::stripUnderscores($folder)] = $this->baseUrl . '/admin/media/open-folder/folder/' . $folder;
    }

    /**
     * Create Folder Action
     *
     * @throws Digitalus_Exception
     * @return void
     */
    public function createFolderAction()
    {
        $baseFolder = $this->_request->getParam('path');
        $newFolder  = $this->_request->getParam('folder_name');

        //dont allow access outside the media folder
        if (strpos($baseFolder, './') || strpos($newFolder, './')) {
            throw new Digitalus_Exception($this->view->getTranslation('Illegal file access attempt. Operation cancelled!'));
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
        $this->_request->setParam('return', true);
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
        $filePath  = $this->_request->getParam('filepath');
        $mediaPath = $this->_request->getParam('mediapath');
        $files     = $_FILES['uploads'];
        if (is_array($files)) {
#            $result = Digitalus_Media::batchUpload($files, $filePath);
            $result = Digitalus_Media::upload($files, $filePath);
        }
        $this->_request->setParam('return', true);
        $this->_request->setParam('folder', $mediaPath);
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
        $folderPath  = Digitalus_Toolbox_String::stripUnderscores($folder);
        $parent      = Digitalus_Toolbox_String::getParentFromPath($folderPath);
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
        $filePath    = Digitalus_Toolbox_String::stripUnderscores($file);
        $parent      = Digitalus_Toolbox_String::getParentFromPath($filePath);
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
        $filepath   = $this->_request->getParam('filepath');
        $folderName = $this->_request->getParam('new_folder_name');

        Digitalus_Media::renameFolder($filepath, $folderName);

        $folder = Digitalus_Toolbox_String::addUnderscores(Digitalus_Toolbox_String::getParentFromPath($filepath) . '/' . $folderName);

        $this->_request->setParam('return', true);
        $this->_request->setParam('folder', $folder);
        $this->_forward('open-folder');
    }
}