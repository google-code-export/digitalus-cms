<?php
class Zend_View_Helper_SelectCategories
{	
    /**
     * values should be an csv list of the ids of the Categories
     *
     * @param unknown_type $values
     */
	public function SelectCategories($values)
	{
	    $values = explode(',', $values);
	    
	    $p = new NewsCategory();
	    $Categories= $p->fetchAll(null, 'title');
	    if($Categories)
	    {
	        foreach ($Categories as $category)
	        {
	            if(in_array($category->id, $values))
	            {
	                $val = 1;
	            }else{
	                $val = 0;
	            }
	            $checkBoxes[] = $this->view->formCheckbox("categories[$category->id]", $val) . ' <em>' . $category->title . "</em>";
	        }
	        if(is_array($checkBoxes))
	        {
	            return "<span class='group'>" . implode('<br />', $checkBoxes) . "</span>";
	        }
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