<?php
require_once APPLICATION_PATH . '/modules/search/forms/Search.php';

class Mod_Search_PublicController extends Zend_Controller_Action
{
    public function searchAction()
    {
        $searchForm = new Search_Form();

        if ($this->_request->isPost() && $searchForm->isValid($_POST) && Digitalus_Filter_Post::has('submitSearchForm')) {
            $index = Zend_Search_Lucene::open(APPLICATION_PATH . '/modules/search/data/index');
            $queryString = Digitalus_Filter_Post::get('keywords');
            $query = Zend_Search_Lucene_Search_QueryParser::parse($queryString);
            $this->view->searchResults = $index->find($query);

            if (!empty($queryString)) {
                $keywordsElement = $searchForm->getElement('keywords');
                $keywordsElement->setValue($queryString);
            }
        }
        $this->view->form = $searchForm;
    }
}