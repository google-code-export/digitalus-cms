<?php
class Mod_Search_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->getFrontController()->getBaseUrl() . '/admin/module',
           $this->view->getTranslation('Search') => $this->getFrontController()->getBaseUrl() . '/mod_search'
        );
        $this->view->toolbarLinks[$this->view->getTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/mod_search'
            . '/label/' . $this->view->getTranslation('Module') . ':' . $this->view->getTranslation('Search');
    }

    public function indexAction()
    {
        // Check whether index directory is writeable
        $indexPath = APPLICATION_PATH . '/modules/search/data/index';
        if (!file_exists($indexPath) || !is_writeable($indexPath)) {
            $this->view->errorMessage = 'For the search module to work properly, the index directory must be writeable! Please check the permissions of this directory:';
            $this->view->indexPath = $indexPath;
        }
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
        echo '<p><strong>' . $this->view->getTranslation('The search index was rebuilt successfully!') . '</strong></p><br />';
    }

}