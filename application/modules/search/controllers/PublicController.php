<?php
class Mod_Core_PublicController extends DSF_Controller_Module_Public
{
    /**
     * @since 0.8.7
     *
     */
    public function searchAction()
    {
        $c = new Content();
        $ns = new Zend_Session_Namespace('searchResults');
        
        if($this->_request->isPost())
        {
            
            //get the content
            //this will be much more flexible, but lets make it work first
            //default to pages
            $contentType = $this->_request->getParam('content_type', null);
            if($content === null){
                $contentType = 'page';
            }
                $where[] = "content_type = '" . $contentType . "'";
            $content = $c->fetchAll($where);
            
            //get the users array to add the author / editor fields
            $u = new User();
            $users = $u->getUserNamesArray();
            
            //set up search index
            $index = Zend_Search_Lucene::create('./application/data/indexes/content');
            
            foreach ($content as $page){
                $doc = new Zend_Search_Lucene_Document();
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('content_id', $page->id));
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('content_author', $users[$page->author_id]));
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('content_create_date', $page->create_date));
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('content_editor', $users[$page->editor_id]));
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('content_edit_date', $page->edit_date));
                $doc->addField(Zend_Search_Lucene_Field::text('content_title', $page->title));
                $doc->addField(Zend_Search_Lucene_Field::text('content_headline', $page->headline));
                $doc->addField(Zend_Search_Lucene_Field::text('content_intro', stripslashes($page->intro)));
                $doc->addField(Zend_Search_Lucene_Field::text('content', stripslashes($page->content )));
                $doc->addField(Zend_Search_Lucene_Field::text('content_additional', stripslashes($page->additional_content)));
                $doc->addField(Zend_Search_Lucene_Field::UnStored('content_tags', $page->tags));
                $index->addDocument($doc);
            }
                        
            $queryString = DSF_Filter_Post::get('query');
            $query = Zend_Search_Lucene_Search_QueryParser::parse($queryString);
            Zend_Search_Lucene::setDefaultSearchField('content');
            $hits = $index->find($query);
            $ns->hits = $hits;
            $ns->queryString = $queryString;
            $ns->query = $query;
            
            $this->view->query = $query;
            $this->view->queryString = $queryString;
            $this->view->searchResults = $hits;
        }else{
            $page = $this->_request->getParam('page', 0);
            if($page > 0){
                $this->view->currentPage = $c->find($page)->current();
            }else{
                $reload = $this->_request->getParam('reload', null);
                if($reload != null){
                    $this->view->searchResults = $ns->hits;
                    $this->view->query = $ns->query;
                    $this->view->queryString = $ns->queryString;
                }
            }
        }
        
        
    }
}