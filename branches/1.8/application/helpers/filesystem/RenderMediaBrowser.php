<?php
class Digitalus_View_Helper_Filesystem_RenderMediaBrowser
{


    public function renderMediaBrowser($path, $folderLink, $fileLink)
    {

        $folders = Digitalus_Filesystem_Dir::getDirectories($path);
        $files = Digitalus_Filesystem_File::getFilesByType($path, false, false, true);
        $links = null;

        if (is_array($folders) && count($folders) > 0) {
            foreach ($folders as $folder) {
                $folderPath = $path . '/' . $folder;
                $link = Digitalus_Toolbox_String::addUnderscores($folderPath);
                //remove reference to media
                $link = str_replace('media_', '', $link);
                $submenu = $this->view->RenderMediaBrowser($folderPath, $folderLink, $fileLink);
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

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}