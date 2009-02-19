<?php
class Mod_Search_PublicController extends Zend_Controller_Action
{
    public function searchAction()
    {
        if($this->_request->isPost() && DSF_Filter_Post::has('submitSearchForm')) {
            $index = Zend_Search_Lucene::open('./application/modules/search/data/index');
            $queryString = DSF_Filter_Post::get('keywords');
            $query = Zend_Search_Lucene_Search_QueryParser::parse($queryString);
            $this->view->searchResults = $index->find($query);
            $this->view->keywords = $queryString;
        }
    }
}