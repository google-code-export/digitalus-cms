<?php
class Mod_Search_IndexController extends Zend_Controller_Action
{


    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Modules') => $this->getFrontController()->getBaseUrl() . '/admin/module',
           $this->view->GetTranslation('Search') => $this->getFrontController()->getBaseUrl() . '/mod_search'
        );
        $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/mod_search'
            . '/label/' . $this->view->GetTranslation('Module') . ':' . $this->view->GetTranslation('Search');
    }

    public function indexAction()
    {

    }

    public function rebuildAction()
    {
        //this can take a lot of time
        set_time_limit(0);
        $properties = DSF_Module_Property::load('mod_search');
        //create the index
        $index = Zend_Search_Lucene::create($properties->pathToIndex);

        $adapters = $properties->adapters;
        foreach ($adapters as $adapter) {
            require_once $adapter->filepath;
            $className = $adapter->classname;
            $adapterObj = new $className();
            $pages = $adapterObj->getPages();
            if (is_array($pages) && count($pages) > 0) {
                foreach ($pages as $page) {
                    $index->addDocument($page->asLuceneDocument());
                }
            }
        }
        $index->optimize();
        $this->_forward('index');
    }

}