<?php
/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Page Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id: PageController.php Tue Dec 25 19:38:20 EST 2007 19:38:20 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_PageController extends Zend_Controller_Action
{
    const META_ACTION = '/admin/page/update-meta-data';
    const PROPERTY_ACTION = '/admin/page/properties';
    const RELATED_ACTION = '/admin/page/related-data';

    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Pages') => $this->getFrontController()->getBaseUrl() . '/admin/page'
        );
    }

    /**
     * The default action
     *
     * Render the admin page interface
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->createPageOptions = $this->_getCreateOptions();
    }

    /**
     * New action
     *
     * @return void
     */
    public function newAction()
    {
        $name = Digitalus_Filter_Post::get('page_name');
        $contentType = Digitalus_Filter_Post::get('contentType');
        $parentId = Digitalus_Filter_Post::int('parent_id');
        $this->_setCreateOptions($parentId, $contentType);

        $page = new Model_Page();
        $newPage = $page->createPage($name, $parentId, $contentType);

        if ($newPage) {
            $url = 'admin/page/edit/id/' . $newPage->id;
        } else {
            $url = 'admin/page';
            $e = new Digitalus_View_Error();
            $e->add(
                $this->view->getTranslation('Sorry, there was an error adding your page')
            );
        }
        $this->_redirect($url);

    }

    /**
     * Edit action
     *
     * @return void
     */
    public function editAction()
    {

        $page = new Model_Page();
        //load the current page
        if ($this->_request->isPost()) {
            $pageId = Digitalus_Filter_Post::int('page_id');
            $version = Digitalus_Filter_Post::get('version');
        } else {
            $pageId = $this->_request->getParam('id',0);
            $version = $this->_request->getParam('version', $page->getDefaultVersion());
        }

        $currentPage = $page->open($pageId, $version);

        $template = $page->getTemplate($pageId);
        $templateLoader = new Digitalus_Content_Template_Loader();
        $pageTemplate = $templateLoader->load($template);
        $form = $this->getContentForm($pageTemplate);

        if (!is_object($currentPage)) {
            $url = 'admin/page';
            $e = new Digitalus_View_Error();
            $e->add(
                $this->view->getTranslation('Sorry, there was an error opening your page')
            );
            $this->_redirect($url);
        }

        //process the form if this is a post back
        if ($this->_request->isPost()) {
            //load the content form
            $values = $form->getValues();
            if (is_array($values)) {
                $currentPage = $page->edit($values);
            } else {
                $form->getErrors();
            }
        }

        if ($currentPage->content) {
            $data = $currentPage->content;
        } else {
            $data = array();
        }

        $this->view->currentVersion = $version;

        $data['page_id'] = $pageId;
        $data['name'] = $currentPage->page->name;
        $data['version'] = $version;

        $this->view->pageId = $pageId;

        //main content form
        $this->view->form = $form;
        $this->view->form->setValues($data);
        $this->view->page = $currentPage;

        //meta data form
        $mdlMeta = new Model_MetaData();
        $metaData = $mdlMeta->asArray($pageId);
        $metaData['page_id'] = $pageId;
        $this->view->metaForm = $this->getMetaForm($metaData);

        //properties
        $mdlProperties = new Model_Properties();
        $this->view->properties = $mdlProperties->asArray($pageId);

        //related pages
        $this->view->relatedPages = $page->getRelatedPages($pageId);

        $this->view->design = $page->getDesign($pageId);

        if (isset($currentPage->page->label) && !empty($currentPage->page->label)) {
            $label = $currentPage->page->label;
        } else {
            $label = $currentPage->page->name;
        }
        $this->view->breadcrumbs[$this->view->getTranslation('Open') . ': ' . $label] = $this->getFrontController()->getBaseUrl() . '/admin/page/edit/id/' . $pageId;
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/admin_page_edit_id_' . $pageId
            . '/label/' . $this->view->getTranslation('Page') . ':' . $currentPage->page->name;
        $this->view->toolbarLinks['Delete'] = $this->getFrontController()->getBaseUrl() . '/admin/page/delete/id/' . $pageId;

    }

    /**
     * Update design action
     *
     * @return void
     */
    public function updateDesignAction()
    {
        $id = $this->_request->getParam('id');
        $design = $this->_request->getParam('design');
        $mdlPage = new Model_Page();
        $mdlPage->setDesign($id, $design);
        $this->_forward('edit');
    }

    /**
     * Update meta data action
     *
     * @return void
     */
    public function updateMetaDataAction()
    {
        $mdlMetaData = new Model_MetaData();

        if ($this->_request->isPost()) {
            $form = $this->getMetaForm($_POST);
            $data = $form->getValues();
            $id = $data['page_id'];
            if ($id > 0) {
                $mdlMetaData->set($form->getValues(), $id);
            }
        }

        $this->_redirect('admin/page/edit/id/' . $id);
    }

    /**
     * Make homepage action
     *
     * @return void
     */
    public function makeHomePageAction()
    {
        $id = $this->_request->getParam('id');
        $mdlPage = new Model_Page();
        $mdlPage->makeHomePage($id);
        $this->_redirect('admin/page/edit/id/' . $id);
    }

    /**
     * Update properties action
     *
     * @return void
     */
    public function updatePropertiesAction()
    {
        $mdlProperties = new Model_Properties();
        if ($this->_request->isPost()) {
            $pageId = Digitalus_Filter_Post::int('page_id');
            $keys = Digitalus_Filter_Post::raw('key');
            $values = Digitalus_Filter_Post::raw('value');
            if (is_array($keys)) {
                for ($i = 0; $i <= (count($keys) - 1); $i++) {
                    $k = $keys[$i];
                    $data[$k] = $values[$i];
                }
                if (is_array($data)) {
                    $mdlProperties->set($data, $pageId);
                }
            }
        }
        $this->_redirect('admin/page/edit/id/' . $pageId);
    }

    /**
     * Related content action
     *
     * @return void
     */
    public function relatedContentAction()
    {
        $pageId = Digitalus_Filter_Post::int('page_id');
        foreach ($_POST as $k => $v) {
            if (substr($k, 0, 5) == 'file_' && $v == 1) {
                $relatedFiles[] = str_replace('file_', '',$k);
            }
        }
        if (is_array($relatedFiles)) {
            $page = new Model_Page();
            $page->setRelatedPages($pageId, $relatedFiles);
        }
        $this->_redirect('admin/page/edit/id/' . $pageId);
    }

    /**
     * Move action
     *
     * @return void
     */
    public function moveAction()
    {
        $mdlPage = new Model_Page();
        $id = $this->_request->getParam('id');
        $parentId = $this->_request->getParam('parent');
        $mdlPage->movePage($id, $parentId);
        $this->_redirect('admin/page/edit/id/' . $id);
    }

    /**
     * Delete action
     *
     * @return void
     */
    public function deleteAction()
    {
        $id = $this->_request->getParam('id', 0);
        if ($id > 0) {
            $page = new Model_Page();
            $page->deletePageById($id);
        }
        $this->_redirect('admin/page');
    }

    /**
     * Select content template action
     *
     * @return void
     */
    public function selectContentTemplateAction()
    {
        $parentId = $this->_request->getParam('parent_id');
        $this->view->createPageOptions = $this->_getCreateOptions();
        $page = new Model_Page();
        $contentType = $page->getContentTemplate($parentId);
        $templateLoader = new Digitalus_Content_Template_Loader();
        $template = $templateLoader->load($contentType);
        $this->view->allowedTemplates = $template->getAllowedChildTemplates();
    }

    /**
     * Create page form action
     *
     * @return void
     */
    public function createPageFormAction()
    {

    }

    /**
     * Get content form action
     *
     * @param  string $template
     * @return Zend_Form
     */
    public function getContentForm($template)
    {
        return $template->getForm();
    }

    /**
     * Get meta action
     *
     * @param  string $data
     * @return Zend_Form
     */
    public function getMetaForm($data)
    {
        $form = new Zend_Form();
        $form->setAction($this->getFrontController()->getBaseUrl() . self::META_ACTION )
             ->setMethod('post');

        $pageId = $form->createElement('hidden', 'page_id');
        $pageId->addFilter('int');

        $pageTitle = $form->createElement('text', 'page_title');
        $pageTitle->setLabel($this->view->getTranslation('Page Title') . ':')
                  ->addFilter('stripTags')
                  ->setAttrib('class', 'med');

        $filename = $form->createElement('text', 'filename');
        $filename->setLabel($this->view->getTranslation('Filename') . ':')
                 ->addFilter('stripTags')
                 ->setAttrib('class', 'med');

        $metaDescription = $form->createElement('textarea', 'meta_description');
        $metaDescription->setLabel($this->view->getTranslation('Meta Description') . ':')
                        ->addFilter('stripTags')
                        ->setAttrib('class', 'med_short');


        $metaKeywords = $form->createElement('textarea', 'keywords');
        $metaKeywords->setLabel($this->view->getTranslation('Meta Keywords') . ':')
                     ->addFilter('stripTags')
                     ->setAttrib('class', 'med_short');


        $searchTags = $form->createElement('textarea', 'search_tags');
        $searchTags->setLabel($this->view->getTranslation('Search Tags') . ':')
                   ->addFilter('stripTags')
                   ->setAttrib('class', 'med_short');

        // Add elements to form:
        $form->addElement($pageTitle)
             ->addElement($filename)
             ->addElement($metaDescription)
             ->addElement($metaKeywords)
             ->addElement($searchTags)
             ->addElement('submit', 'update', array('label' => $this->view->getTranslation('Update Meta Data')))
             ->addElement($pageId);

        //set data
        if (is_array($data)) {
            $form->populate($data);
        }

        return $form;
    }

    /**
     * Set create options action
     *
     * @param  id     $parentId
     * @param  string $contentType
     * @return void
     */
    protected function _setCreateOptions($parentId, $contentType)
    {
        $session = $this->_getCreateOptions();
        $session->contentType = $contentType;
        $session->parentId = intval($parentId);
    }

    /**
     * Get create options action
     *
     * @return Zend_Session_Namespace
     */
    protected function _getCreateOptions()
    {
        return new Zend_Session_Namespace('createPageOptions');
    }

}