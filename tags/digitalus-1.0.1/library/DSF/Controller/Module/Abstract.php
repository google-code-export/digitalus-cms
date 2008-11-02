<?php

/**
 * this class handles the actions for a simple modular content controller
 *
 */
class DSF_Controller_Module_Abstract extends Zend_Controller_Action 
{
    /**
     * this is the full path to the module
     *
     * @var unknown_type
     */
    protected $_pathToModule = null;
    
    /**
     * the module name
     *
     * @var string
     */
    protected $_moduleName;
    
    /**
     * the controller name
     *
     * @var string
     */
    protected $_controllerName;
    
    /**
     * the class name of the model
     * this will be instantiated in the init function
     *
     * @var string
     */
    protected $_modelClass;
    
    /**
     * the model class
     *
     * @var zend_db_table
     */
    protected $_model;
    
    /**
     * the current record id
     *
     * @var int
     */
    protected $_recordId;
    
    /**
     * the record that is currently being worked on
     *
     * @var zend_db_table_row
     */
    protected $_currentRecord;
    
    /**
     * default add success message
     *
     * @var string
     */
    protected $_addMessage = "Your record was successfully added";
    
    /**
     * default add error message
     *
     * @var string
     */
    protected $_addError = "There was an error adding your record";
    
    /**
     * default edit success message
     *
     * @var string
     */
    protected $_editMessage = "Your record was successfully edited";
    
    /**
     * default edit error message
     *
     * @var string
     */
    protected $_editError = "There was an error editing your record";
    
    /**
     * default delete success message
     *
     * @var string
     */
    protected $_deleteMessage = "Your record was successfully deleted";
    
    /**
     * default delete error message
     *
     * @var string
     */
    protected $_deleteError = "There was an error deleting your record";
    
    /**
     * set the admin section and instantiate the model
     *
     */
	public function init()
	{
		$this->view->adminSection = 'module';
		$this->_pathToModule = './application/modules/' . $this->_moduleName;
		$this->view->modulePath = '/mod_' . $this->_moduleName;
		
		//include all models
		$pathToModels = $this->_pathToModule . '/models';
		$modelFiles = DSF_Filesystem_File::getFilesByType($pathToModels, 'php', $pathToModels . '/');
		if($modelFiles){
		    foreach ($modelFiles as $model){
		        require_once($model);
		    }
		    $this->_model = new $this->_modelClass;
		}
	}
	
	/**
	 * default index action
	 *
	 */
	public function indexAction(){}
	
	/**
	 * default add action
	 * triggers:
	 *     onAdd       ->  runs before record is added
	 *     afterAdd    ->  runs after the record is added
	 *
	 */
    public function addAction()
	{
	    if($this->_request->isPost())
	    {
	        $this->triggerEvent('onAdd');
	        $this->_currentRecord = $this->_model->insertFromPost();
	        $this->triggerEvent('afterAdd');
	        if($this->_currentRecord)
	        {
	            $url = '/mod_' . $this->_moduleName . '/' . $this->_controllerName . '/edit/id/' . $this->_currentRecord->id;
    		    $e = new DSF_View_Error();
    		    if(!$e->hasErrors()){
                    $m = new DSF_View_Message();
                    $m->add($this->_addMessage);  
    		    }
	        }else{
	           $url = '/mod_' . $this->_moduleName . '/index';
	        }
	    }
        $this->_redirect($url);
	}
	
	/**
	 * default edit action
	 * triggers:
	 *     onEdit      ->  runs before record is edited
	 *     afterEdit   ->  runs after the record is edited
	 *     beforeOpen  ->  runs before the record is opened for editing
	 * 
	 */
	public function editAction()
	{
		if($this->_request->isPost())
	    {
	        $this->triggerEvent('onEdit');
	        $this->_currentRecord = $this->_model->updateFromPost();
	        $this->triggerEvent('afterEdit');
			$this->_recordId = $this->_currentRecord->id;
		    $e = new DSF_View_Error();
		    if(!$e->hasErrors()){
                $m = new DSF_View_Message();
                $m->add($this->_editMessage);  
		    }
	    }else{
			$this->_recordId = $this->_request->getParam('id', 0);
	    }
	    $this->_currentRecord = $this->_model->find($this->_recordId)->current();
	    $this->triggerEvent('beforeOpen');
		$this->view->data = $this->_currentRecord;
	}
	
	/**
	 * default delete action
	 * triggers:
	 *     onDelete    ->  runs before the record is deleted
	 *     afterDelete ->  runs after the record is deleted
	 */
	public function deleteAction()
	{
		//get the id
		$this->_recordId = $this->_request->getParam('id', 0);
		$this->_currentRecord = $this->_model->find($this->_recordId)->current();
		
		//if the id is valid
		if($this->_recordId > 0)
		{
		    $this->triggerEvent('onDelete');
		    $this->_model->delete('id = ' . $this->_recordId);
		    $this->triggerEvent('afterDelete');
		    $m = new DSF_View_Message();
		    $m->add($this->_deleteMessage);
		}else{
		    $e = new DSF_View_Error();
		    $e->add($this->_deleteError);
		}
		$url = '/mod_' . $this->_moduleName . '/index';
		$this->_redirect($url);
	   
	}
	
	protected function triggerEvent($event)
	{
	    if(method_exists($this, $event)){
	        $this->$event();
	    }
	}
}