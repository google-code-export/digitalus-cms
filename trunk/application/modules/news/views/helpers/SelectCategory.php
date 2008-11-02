<?php
require('./application/modules/news/models/Category.php');
class Zend_View_Helper_SelectCategory
{	
    /**
     *
     * @param unknown_type $value
     */
	public function SelectCategory($name, $value)
	{
	    
	    $p = new NewsCategory();
	    $Categories= $p->fetchAll(null, 'title');
	    if($Categories)
	    {
	        $items = array('Select One');
	        foreach ($Categories as $cat) {
	        	$items[$cat->id] = $cat->title;
	        }
	        return $this->view->formSelect($name, $value, null, $items);
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