<?php
class Digitalus_Menu
{
    public    $view;
    public    $pages = array();
    protected $_parentId;
    protected $_identity;

    /**
     * this function sets up then loads the menu
     *
     * @todo this needs to be cached
     *
     * @param int $parentId
     * @param int $levels
     */
    public function __construct($parentId = 0)
    {
        $this->setView();
        $this->_identity = Digitalus_Auth::getIdentity();
        $this->_parentId = $parentId;
        $this->_load();
    }

    /**
     * this function loads the current menu and is run automatically by the constructor
     *
     */
    protected function _load()
    {
        $mdlMenu  = new Model_Menu();
        $children = $mdlMenu->getChildren($this->_parentId);
        if ($children != null && $children->count() > 0) {
            foreach ($children as $child) {
                $this->pages[] = new Digitalus_Menu_Item(null, $child);
            }
            $container = new Zend_Navigation($this->pages);
            // set container, acl and role for view helper
            $acl = new Digitalus_Acl();
            $this->view->navigation($container);
            $this->view->navigation()->setAcl($acl);
            $this->view->navigation()->setRole($this->_identity->role);
            // write Zend_Navigation into registry
            Zend_Registry::set('Zend_Navigation', $container);
        }
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView(Zend_View $view = null)
    {
        if ($view == null) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            if (null === $viewRenderer->view) {
                $viewRenderer->initView();
            }
            $this->view = $viewRenderer->view;
        } else {
            $this->view = $view;
        }
    }
}