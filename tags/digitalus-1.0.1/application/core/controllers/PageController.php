<?php 

/**
 * DSF CMS
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
 * @category   DSF CMS
 * @package    DSF_CMS_Controllers
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: PageController.php Tue Dec 25 19:38:20 EST 2007 19:38:20 forrest lyman $
 */

class Core_PageController extends Zend_Controller_Action
{
	
	function init()
	{
		//set the admin section
		$this->view->adminSection = 'page';
	}
	
	/**
	 * render the admin page interface
	 *
	 */
	function indexAction()
	{
	    $addMore = $this->_request->getParam('continue');
	    if($addMore == 'true'){
	        $this->view->add_more = 1;
	    }
	    $this->view->pageParentId = $this->_request->getParam('parent');
        $this->view->formAction = "/admin/page/new"; 
	}
	
	/**
	 * open a page to edit it
	 * 
	 * @param int id
	 *
	 */
	function openAction()
	{
	    $mdl = new Page();
	    $id = $this->_request->getParam("id", 0);
   		$page = $mdl->find($id)->current();
	    $parent = implode('/', $mdl->getParents($id));
        
	    $msg = new DSF_View_Message();
	    $msg->add("You are editing: <strong>" . $page->title . "</strong> (" . $parent . "/" . $page->title . ")");
	    
	    $this->view->Page = $page;
	    
	    //load the page properties
	    $prop = new Properties();
	    $this->view->properties = $prop->load($id);
        $this->view->submitText = "Update";
        $this->view->fullPageForm = "/admin/page/edit";
	}
	
	/**
	 * advanced options for page editing
	 * this is set up so it is easy to separate common admin functions (editing content)
	 * and more advanced ones like adding modules, managing menus, etc
	 *
	 */
	function advancedOptionsAction()
	{
	    $mdl = new Page();
	    $id = $this->_request->getParam("id", 0);
   		$page = $mdl->find($id)->current();
	    $parent = implode('/', $mdl->getParents($id));
        
	    $msg = new DSF_View_Message();
	    $msg->add("You are editing: <strong>" . $page->title . "</strong> (" . $parent . "/" . $page->title . ")");
	    
	    $this->view->Page = $page;
	    
	    //load the page properties
	    $this->view->properties = new Properties($id);
	}
	
	/**
	 * add a new page, then open it for editing
	 *
	 */
	function newAction()
	{
	    if (strtolower($_SERVER["REQUEST_METHOD"]) == "post") {
    		$m = new Page();
            $curr = $m->insertFromPost();
            $e = new DSF_View_Error();

            // @todo: validate this and reload the post if it fails
            if($e->hasErrors()){
                $this->view->menuItem = $this->view->ReloadViewData('Page');
                $url = '/admin/page';
            }elseif(DSF_Filter_Post::int('add_more') == 1){
                $url = '/admin/page/index/continue/true/parent/' . DSF_Filter_Post::int('parent_id');
            }else{
                $url = "/admin/page/open/id/" . $curr->id;
            }
           	$this->_redirect($url);
	    }
	}
	
	/**
	 * edit an existing page
	 * 
	 * @param int id
	 *
	 */
	function editAction()
	{
		$m = new Page();
        $item = $m->updateFromPost();
        
        //upload the image if it exists
        if(DSF_Resource::isUploaded('image')){
            $img = new DSF_Resource_Image();
            $img->upload('image', 'page_' . $item->id,true, true, 230);
            $item->filepath = $img->thumbPath;
            $item->save();
        }elseif (DSF_Filter_Post::get('removeImage')){
            $item->filepath = '';
            $item->save(); 
        }
        
        //if a module is set set then update the record
        if(DSF_Filter_Post::get('moduleName') !== 0)
        {
        	//$m->addModule($item->id, DSF_Filter_Post::get('moduleName'), DSF_Filter_Post::get('moduleAction'), DSF_Filter_Post::raw('moduleParams'));
        }
        
		$done = DSF_Filter_Post::get('done');
        
        $m = new DSF_View_Message();
        $m->add("Page successfully updated");
		if($done == 1)
		{
			$url = "/admin/page";	
		}else{
        	$url = "/admin/page/open/id/" . $item->id;
		}
      $this->_redirect($url);

    }
    
    function ajaxEditorAction()
    {
        //load the common helpers
        DSF_View_RegisterHelpers::register($this->view);
        
        $page = new Content();
        if($this->_request->isPost()){
            //get the id and block from post
            $id = DSF_Filter_Post::get('id');
            $block = DSF_Filter_Post::get('block');
            $content = DSF_Filter_Post::raw('content');
            $return = DSF_Filter_Post::get('return');
            
            $currentPage = $page->find($id)->current();
            $currentPage->$block = $content;
            $currentPage->save();
            
            //redirect back to page
            $url = DSF_Toolbox_String::stripUnderscores($return);
            $this->_redirect($url);
        }
        
        //get the id and block from uri
        $id = $this->_request->getParam('id');
        $block = $this->_request->getParam('block');
        $return = $this->_request->getParam('return');
        
        $currentPage = $page->find($id)->current();

        $this->view->content = $currentPage->$block;
        $this->view->id = $id;
        $this->view->block = $block;
        $this->view->return = $return;
    }

    /**
     * delete a page
     *
     */
	function deleteAction()
	{
	   $id = $this->_request->getParam('id');
	   $c = new Page();
	   $c->delete("id = " . intval($id));
	   
	   $m = new DSF_View_Message();
	    $m->add("Page deleted");
	   $this->_redirect('/admin/page');
    }
    
    /* advanced options */
    
    /**
     * update the page menu link and label
     *
     */
    function updateMenuLinkAction()
    {
        $menu = new Menu();
        $id = DSF_Filter_Post::int('id');
        $label = DSF_Filter_Post::get('menu');
        $show = DSF_Filter_Post::int('show_on_menu');
        $result = $menu->updateMenuLink($id, $label, $show);
        if($result)
        {
            $m = new DSF_View_Message();
            $m->add('Your menu was succefully updated');
        }else{
            $e = new DSF_View_Error();
            $e->add("Sorry, there was an error updating your menu");
        }
        $url = '/admin/page/advanced-options/id/' . $id;
        $this->_redirect($url);
        
    }
    /**
     * update the page layout and template
     *
     */
    function updateDesignAction()
    {
        $page = new Page();
        $id = DSF_Filter_Post::int('id');
        $template = DSF_Filter_Post::get('template_path');
        $layout = DSF_Filter_Post::get('layout_path');
        $userStyles = DSF_Filter_Post::get('user_styles');
        $result = $page->updateDesign($id, $template, $layout, $userStyles);
        
        if($result)
        {
            $m = new DSF_View_Message();
            $m->add("Your page's design was succefully updated");
        }else{
            $e = new DSF_View_Error();
            $e->add("Sorry, there was an error updating your page's design");
        }
        $url = '/admin/page/advanced-options/id/' . $id;
        $this->_redirect($url);
        
    }
    
    /**
     * move the page to a new section
     *
     */
    function movePageAction()
    {
        $page = new Page();
        $id = DSF_Filter_Post::int('id');
        $parentId = DSF_Filter_Post::int('parent_id');
        $result = $page->move($id, $parentId);
        if($result)
        {
            $m = new DSF_View_Message();
            $m->add('Your page was moved successfully');
        }else{
            $e = new DSF_View_Error();
            $e->add("Sorry, there was an error moving your page");
        }
        $url = '/admin/page/advanced-options/id/' . $id;
        $this->_redirect($url);
    }
    
    /**
     * add a module to the page
     *
     */
    function addModuleAction()
    {
        $p = new Page();
        $id = DSF_Filter_Post::int('id');
        $moduleName = DSF_Filter_Post::get('moduleName');
        $moduleAction = DSF_Filter_Post::get('moduleAction');
        $params = DSF_Filter_Post::raw('param');
        $p->addModule($id, $moduleName, $moduleAction, $params);
        
        //@todo: delete

        $prop = new Properties();
        $properties = $prop->load($id);

        $url = '/admin/page/advanced-options/id/' . $id;
        $this->_redirect($url);
        
    }

}