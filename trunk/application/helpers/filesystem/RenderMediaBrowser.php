<?php
class DSF_View_Helper_Filesystem_RenderMediaBrowser
{	
	public function RenderMediaBrowser($path, $folderLink, $fileLink)
	{
		$folders = DSF_Filesystem_Dir::getDirectories('./' . $path);
		$files = DSF_Filesystem_File::getFilesByType('./' . $path,false,false,true);
		
		if(is_array($folders) && count($folders) > 0) {
    		foreach ($folders as $folder)
    		{	
    		    $folderPath = $path . '/' . $folder;
    		    $link = DSF_Toolbox_String::addUnderscores($folderPath);
    		    $submenu = $this->view->RenderMediaBrowser($folderPath, $folderLink, $fileLink);
    			$links[] ="<li class='menuItem'><a href='/{$folderLink}/{$link}' class='folder' id='folder-{$link}'>{$folder}</a>" . $submenu . '</li>';
    		}
		}
		
		if(is_array($files) && count($files) > 0) {
    		foreach ($files as $file) {
    		    if(substr($file,0,1) != '.') {
    		        $filePath = $path . '/' . $file;
    			    $links[] ="<li class='menuItem'><a href='{$fileLink}/{$filePath}' class='file' id='file-{$file}'>{$file}</a></li>";
    		    }
		    }
		}
		
		if(is_array($links))
		{
			$filetree = "<ul id='fileTree'>" . implode(null, $links) . "</ul>";
			return  $filetree;
		}
	}
	
    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}