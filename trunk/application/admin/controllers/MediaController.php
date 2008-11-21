<?php

/**
 * MediaZendController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class Admin_MediaController extends Zend_Controller_Action {
    
    public function init()
    {
        $config = Zend_Registry::get('config');
        $this->pathToMedia = $config->filepath->media;
        $this->view->pathToMedia = $this->pathToMedia;
    }
    /**
     * The default action - show the home page
     */
    public function indexAction() {
        // TODO Auto-generated MediaZendController::indexAction() default action
    }
    
    public function openFolderAction()
    {
        $folder = $this->_request->getParam('folder');
		$this->view->basePath = $folder;
        $folder = DSF_Toolbox_String::stripHyphens($folder);
        
        $folderArray = explode('_', $folder);
        
        if(is_array($folderArray)) {
            foreach ($folderArray as $pathPart) {
                $fullPathParts[] = $pathPart;
                $fullPath = implode('_', $fullPathParts);
                $folderPathParts[$fullPath] = $pathPart;
            }
        }
        
        $this->view->folderPathParts = $folderPathParts;
        
        $pathToFolder = DSF_Toolbox_String::stripUnderscores($folder);
		$this->view->folders = DSF_Filesystem_Dir::getDirectories($pathToFolder);
		$this->view->files = DSF_Filesystem_File::getFilesByType($pathToFolder,false,false,true);
    }
    
    public function createFolderAction()
    {
        $baseFolder = DSF_Filter_Post::get('path');
        $newFolder = DSF_Filter_Post::get('folder_name');
        $forwardPath = $baseFolder;
        if(!empty($newFolder)) {
            $base = str_replace('media_', '', $baseFolder);
            $base = DSF_Toolbox_String::stripUnderscores($base);
            $fullPath = $this->pathToMedia . '/' . $base . '/' . $newFolder;
            $result = mkdir($fullPath, 0777);
            if($result) {
                $forwardPath .= '_' . $newFolder;
            }
        }
        $this->_request->setParam('folder', $forwardPath);
        $this->_forward('open-folder');
    }
    
    public function uploadAction()
    {
        $path = DSF_Filter_Post::get('path');
        $files = $_FILES['uploads'];
        if(is_array($files)) {
            DSF_Media::batchUpload($files, $path);
        }
        $this->_request->setParam('folder', $path);
        $this->_forward('open-folder');
    }
    
    public function deleteFolderAction()
    {
        
    }
    
    public function deleteFileAction()
    {
        
    }
    
    public function renameFolderAction()
    {
        $result = DSF_Media::renameFolder(
            DSF_Filter_Post::get('path'),
            DSF_Filter_Post::get('folder_name')
        );
        $this->_request->setParam('folder', $result);
        $this->_forward('open-folder');
    }

}
?>

