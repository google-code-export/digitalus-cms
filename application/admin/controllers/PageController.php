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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: PageController.php Tue Dec 25 19:38:20 EST 2007 19:38:20 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */

/**
 * @see Digitalus_Controller_Action
 */
require_once 'Digitalus/Controller/Action.php';

/**
 * Admin Page Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 * @uses        Model_Page
 * @uses        Model_MetaData
 * @uses        Model_Properties
 */
class Admin_PageController extends Digitalus_Controller_Action
{
    const META_ACTION     = '/admin/page/update-meta-data';
    const PROPERTY_ACTION = '/admin/page/properties';
    const RELATED_ACTION  = '/admin/page/related-data';
    const PUBLISH_ACTION  = '/admin/page/publish';

    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Pages') => $this->baseUrl . '/admin/page'
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
        $this->_forward('new');
    }

    /**
     * New action
     *
     * @return void
     */
    public function newAction()
    {
        $pageForm = new Admin_Form_Page();
        $pageForm->setAction($this->baseUrl . '/admin/page/new');
        $pageForm->setAttrib('class', $pageForm->getAttrib('class') . ' columnar');

        $elmPageName = $pageForm->getElement('page_name');
        $elmPageName->addValidators(array(
            array('PagenameExistsNot', true),
        ));

        $createPageGroup = $pageForm->getDisplayGroup('createPageGroup');
        $createPageGroup->setLegend($this->view->getTranslation('Create Page'));

        if (!$this->view->isAllowed('admin', 'page', 'publish')) {
            $pageForm->getElement('publish_pages')->setAttrib('disabled', 'disabled');
        }

        if ($this->_request->isPost() && $pageForm->isValid($_POST)) {
            $this->_setCreateOptions($pageForm->getValue('parent_id'),
                                     $pageForm->getElement('continue_adding_pages')->isChecked(),
                                     $pageForm->getValue('content_template'),
                                     $pageForm->getElement('show_on_menu')->isChecked(),
                                     $pageForm->getElement('publish_pages')->isChecked());

            $page = new Model_Page();
            $newPage = $page->createPage($pageForm->getValue('page_name'),
                                         $pageForm->getValue('parent_id'),
                                         $pageForm->getValue('content_template'),
                                         $pageForm->getElement('show_on_menu')->isChecked(),
                                         $pageForm->getElement('publish_pages')->isChecked());

            if ($newPage) {
                if ($pageForm->getElement('continue_adding_pages')->isChecked()) {
                    $url = 'admin/page/new';
                } else {
                    $url = 'admin/page/edit/id/' . $newPage->id;
                }
            } else {
                $url = 'admin/page';
                $e = new Digitalus_View_Error();
                $e->add(
                    $this->view->getTranslation('Sorry, there was an error adding your page')
                );
            }
            $formValues = $this->_getCreateOptions();
            $this->_redirect($url);
        } else {
            $formValues = $this->_getCreateOptions();
            $pageForm->getElement('parent_id')->setValue($formValues->parent_id);
            $pageForm->getElement('continue_adding_pages')->setValue($formValues->continue);
            $pageForm->getElement('publish_pages')->setValue($formValues->publish_pages);
            $pageForm->getElement('show_on_menu')->setValue($formValues->show_on_menu);
            $pageForm->getElement('content_template')->setValue($formValues->content_template);
        }
        $this->view->form = $pageForm;
    }

    /**
     * Edit action
     *
     * @return void
     */
    public function editAction()
    {
        $page = new Model_Page();
        $pageId   = $this->_request->getParam('id', 0);
        $language = $this->_request->getParam('language', $page->getDefaultLanguage());
        $currentPage = $page->open($pageId, $language);

        // load the template and form
        $template = $page->getTemplate($pageId);

        // @todo: refactor this into some sort of helper function
        $templateParts       = explode('_', $template);
        $currentTemplate     = $templateParts[0];
        $currentTemplatePage = $templateParts[1];
        $templatePath        = BASE_PATH . '/templates/public/' . $currentTemplate;
        $templateConfig      = new Zend_Config_Xml($templatePath . '/pages/' . $currentTemplatePage . '.xml');
        $pageTemplate        = new Digitalus_Interface_Template();

        $form = $pageTemplate->getForm($templatePath . '/layouts/' . $templateConfig->layout);
        $form->setAction($this->baseUrl . '/admin/page/edit')
             ->setDecorators(array(
                'FormElements',
                'Form',
                array('FormErrors', array('placement' => 'prepend'))
            ));

        $elmPageName = $form->getElement('name');
        $elmPageName->addValidators(array(
            array('PagenameExistsNot', true, array('exclude' => $currentPage->page->name)),
        ));

        if (!is_object($currentPage)) {
            $url = 'admin/page';
            $e = new Digitalus_View_Error();
            $e->add(
                $this->view->getTranslation('Sorry, there was an error opening your page')
            );
            $this->_redirect($url);
        }

        //process the form if this is a post back
        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $values = $form->getValues();
            unset($values['submit']);
            unset($values['form_instance']);
            $currentPage = $page->edit($values);
        } else {
            if ($currentPage->content) {
                $data = $currentPage->content;
            } else {
                $data = array();
            }
            $data['id']       = $pageId;
            $data['name']     = $currentPage->page->name;
            $data['language'] = $language;
            $form->populate($data);
        }

        $this->view->currentVersion = $language;
        $this->view->pageId = $pageId;

        //main content form
        $this->view->form = $form;
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
//        $this->view->relatedPages = $page->getRelatedPages($pageId);

        $label = $currentPage->page->name;
        if (isset($currentPage->page->label) && !empty($currentPage->page->label)) {
            $label = $currentPage->page->label;
        }
        $this->view->breadcrumbs[$this->view->getTranslation('Open') . ': ' . $label] = $this->baseUrl . '/admin/page/edit/id/' . $pageId;
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark'
            . '/url/admin_page_edit_id_' . $pageId
            . '/label/' . $this->view->getTranslation('Page') . ':' . $currentPage->page->name;
        $this->view->toolbarLinks['Delete'] = $this->baseUrl . '/admin/page/delete/id/' . $pageId;
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

    public function updateTemplateAction()
    {
        $id = $this->_request->getParam('id');
        $content_template = $this->_request->getParam('content_template');
        $mdlPage = new Model_Page();
        $mdlPage->setContentTemplate($id, $content_template);
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
                    $cleanKey = str_replace(' ', '_', $k);
                    $data[$cleanKey] = $values[$i];
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
                $relatedFiles[] = str_replace('file_', '', $k);
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
     * Publish page action
     *
     * @return void
     */
    public function publishAction()
    {
        if ($this->_request->isPost()) {
            $action = Digitalus_Filter_Post::text('publish');
            $id = $this->_request->getParam('id');

            $mdlPage = new Model_Page();
            $mdlPage->publishPage($id, $action);
            $this->_redirect('admin/page/edit/id/' . $id);
        }
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
        $form = new Admin_Form_PageMetaData();
        $form->setAction($this->baseUrl . self::META_ACTION )
             ->setMethod('post');

        //set data
        if (is_array($data)) {
            $form->populate($data);
        }
        return $form;
    }

    /**
     * Get publish form
     *
     * @param  string $data
     * @return Zend_Form
     */
    public function getForm($data)
    {
        $form = new Zend_Form();
        $form->setAction($this->baseUrl . self::PUBLISH_ACTION )
             ->setMethod('post');

        $pageId = $form->createElement('hidden', 'page_id');
        $pageId->addFilter('int');

        $publishRadio = $form->createElement('radio', 'publish');
        $publishRadio->setLabel($this->view->getTranslation('Publish or Archive Page') . ':')
                     ->setAttrib('class', 'med');

        // Add elements to form:
        $form->addElement($publishRadio)
             ->addElement('submit', 'update', array('label' => $this->view->getTranslation('Publish or Archive')))
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
    protected function _setCreateOptions($parentId, $continue = false, $contentTemplate = false, $showOnMenu = false, $publishPages = false)
    {
        $session = $this->_getCreateOptions();
        $session->continue = $continue;
        $session->parent_id = $parentId;
        $session->content_template = $contentTemplate;
        $session->show_on_menu = $showOnMenu;
        $session->publish_pages = $publishPages;
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