<?php
class DSF_View_Helper_Content_RenderBlock
{
	public function RenderBlock($module, $block, $params = null){
	    $front = zend_controller_front::getInstance();
	    $controllers = $front->getControllerDirectory();
	    if(isset($controllers['mod_' . $module])) {
	        //create a new view instance (that shares the other views params)
	        $view = clone ($this->view);

	        $path = str_replace('controllers', 'blocks', $controllers['mod_' . $module]) . '/' . $block;
	        
	        
	        //require the controller class
	        require_once($path . '/controller.php');
	        
	        //create an instance
	        $className = ucwords($module) . '_Block_' . ucwords($block);

	        $block = new $className($view, $params);
	        
	        //render the view
	        $view->addScriptPath($path);
	        return $view->render('view.phtml');
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