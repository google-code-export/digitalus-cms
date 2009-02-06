<?php

/**
 * MediaZendController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class Admin_MediaController extends Zend_Controller_Action {

    protected $_fullPathToMedia;
    protected $_pathToMedia;
    protected $_currentFolder;

    public function init()
    {
        $config = Zend_Registry::get('config');
        $this->_pathToMedia = $config->filepath->media;
        $this->_fullPathToMedia = $this->getFrontController()->getBaseUrl() . $this->_pathToMedia;
        $this->view->pathToMedia = $this->_pathToMedia;
        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Media') => $this->getFrontController()->getBaseUrl() . '/admin/media'
        );
    }
    /**
     * The default action - show the home page
     */
    public function indexAction() {
        $this->view->path = '';
    }

    public function openFolderAction()
    {
        $folder = $this->_request->getParam('folder');
        $folder = str_replace('media_', '', $folder);

        $this->view->path = $folder;
        $folder = DSF_Toolbox_String::stripHyphens($folder);

        $folder = DSF_Toolbox_String::stripLeading('_', $folder);
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

        $this->view->folderPathParts = $folderPathParts;

        $pathToFolder = $this->_fullPathToMedia . '/' . DSF_Toolbox_String::stripUnderscores($folder);
        $this->view->filesystemPath = $pathToFolder;
        $this->view->mediaPath = $folder;
        $this->view->folders = DSF_Filesystem_Dir::getDirectories($pathToFolder);
        $this->view->files = DSF_Filesystem_File::getFilesByType($pathToFolder,false,false,true);

        $this->view->breadcrumbs[$this->view->GetTranslation('Open Folder') . ': ' . $pathToFolder] = $this->getFrontController()->getBaseUrl() . '/admin/media/open-folder/folder/' . $folder;
        $this->view->toolbarLinks = array();

        $tmpPath = DSF_Toolbox_String::addUnderscores($folder);
        $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/admin_media_open-folder_folder_' . $tmpPath;
        $this->view->toolbarLinks[$this->view->GetTranslation('Delete')] = $this->getFrontController()->getBaseUrl() . '/admin/media/delete-folder/folder/' . $folder;

    }

    public function createFolderAction()
    {
        $baseFolder = DSF_Filter_Post::get('path');
        $newFolder = DSF_Filter_Post::get('folder_name');

        //dont allow access outside the media folder
        if (strpos($baseFolder, './') || strpos($newFolder, './')) {
            throw new Zend_Exception('Illegal file access attempt. Operation cancelled!');
        }

        $forwardPath = $baseFolder;
        if (!empty($newFolder)) {
            $fullPath = $this->_pathToMedia;

            $base = str_replace('media_', '', $baseFolder);

            if (!empty($base)) {
                $base = DSF_Toolbox_String::stripUnderscores($base);
                $fullPath .= '/' . $base;
            }
            $fullPath .= '/' . $newFolder;

            if (!file_exists($fullPath)) {
                $result = @mkdir($fullPath, 0777);
            } else {
                $exists = true;
            }

            if ($result || $exists) {
                $forwardPath .= '_' . $newFolder;
            }
        }

        $this->_request->setParam('folder', $forwardPath);
        $this->_forward('open-folder');
    }

    public function uploadAction()
    {
        $path = DSF_Filter_Post::get('filepath');
        $mediapath = DSF_Filter_Post::get('mediapath');
        $files = $_FILES['uploads'];
        if (is_array($files)) {
            DSF_Media::batchUpload($files, $path);
        }
        $this->_request->setParam('folder', $mediapath);

        $this->_forward('open-folder');
    }

    public function deleteFolderAction()
    {
        $folder = $this->_request->getParam('folder');
        DSF_Media::deleteFolder($folder);
        $folderPath = DSF_Toolbox_String::stripUnderscores($folder);
        $parent = DSF_Toolbox_String::getParentFromPath($folderPath);
        $cleanParent = DSF_Toolbox_String::addUnderscores($parent);
        $this->_request->setParam('folder', $cleanParent);
        $this->_forward('open-folder');
    }

    public function deleteFileAction()
    {
        $file = $this->_request->getParam('file');
        DSF_Media::deleteFile($file);
        $filePath = DSF_Toolbox_String::stripUnderscores($file);
        $parent = DSF_Toolbox_String::getParentFromPath($filePath);
        $cleanParent = DSF_Toolbox_String::addUnderscores($parent);
        $this->_request->setParam('folder', $cleanParent);
        $this->_forward('open-folder');
    }

    public function renameFolderAction()
    {
        $path = DSF_Media::renameFolder(
            DSF_Filter_Post::get('filepath'),
            DSF_Filter_Post::get('folder_name')
        );
        $path = str_replace('./', '',$path);
        $path = str_replace('../', '',$path);

        $folder = DSF_Toolbox_String::addUnderscores($path);

        $this->_request->setParam('folder', $folder);
        $this->_forward('open-folder');
    }

}
?>