<?php
class DSF_View_Helper_Filesystem_RenderMediaBrowser
{	
    public $defaultIcon = 'page.png';
    public $icons = array();
    
	public function RenderMediaBrowser($path, $folderLink, $fileLink)
	{
	    $config = Zend_Registry::get('config');
	    $this->icons = $config->filetypes;    
	    
		$folders = DSF_Filesystem_Dir::getDirectories('./' . $path);
		$files = DSF_Filesystem_File::getFilesByType('./' . $path,false,false,true);
		
		if(is_array($folders) && count($folders) > 0) {
    		foreach ($folders as $folder)
    		{	
    		    $folderPath = $path . '/' . $folder;
    		    $link = DSF_Toolbox_String::addUnderscores($folderPath);
    		    $submenu = $this->view->RenderMediaBrowser($folderPath, $folderLink, $fileLink);
    		    $links[] = "<li class='menuItem'>" . $this->view->link($folder, '/' . $folderLink . '/' . $link, 'folder.png') . $submenu . '</li>';
    		}
		}
		
		if(is_array($files) && count($files) > 0) {
    		foreach ($files as $file) {
    		    if(substr($file,0,1) != '.') {
    		        $filetype = DSF_Media_Filetype::load($file);
    		        $filePath = $path . '/' . $file;
    			    $links[] ="<li class='menuItem'>" . 
    			    $this->view->link($file , '/' . $fileLink . '/' . $filePath, $this->getIconByType($filetype->key)) . "</li>";
    		    }
		    }
		}
		
		if(is_array($links))
		{
			$filetree = "<ul id='fileTree' class='treeview'>" . implode(null, $links) . "</ul>";
			return  $filetree;
		}
		return null;
	}
	
	public function getIconByType($type)
	{
	    if(isset($this->icons->$type)) {
	        $filetype = $this->icons->$type;
	        return $filetype->icon;
	    }else{
	        return $this->defaultIcon;
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