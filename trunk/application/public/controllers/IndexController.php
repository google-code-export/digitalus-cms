<?php
/**
 * the public index controller's sole mission in life is to render content pages
 *
 */
class IndexController extends Digitalus_Controller_Action
{
    public $page;

    public function init()
    {
        // create the new page object
        $this->page = Digitalus_Builder::loadPage(null, 'initialize.xml');

        // load the data
        Digitalus_Builder::loadPage(null, 'load_data.xml', $this->page);
    }

    public function indexAction()
    {
        // load the view
        Digitalus_Builder::loadPage(null, 'load_view.xml', $this->page, $this->view);

        // render the page
        $this->view->page           = $this->page;
        $this->view->layout()->page = $this->page->getParam('xhtml');
    }

    public function loginAction()
    {
        $form = new Admin_Form_Login();
        $form->setAction($this->baseUrl . '/public/index/login');

        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $uri      = Digitalus_Filter_Post::get('uri');
            $username = Digitalus_Filter_Post::get('adminUsername');
            $password = Digitalus_Filter_Post::get('adminPassword');

            $auth = new Digitalus_Auth($username, $password);
            $result = $auth->authenticate();
            if (!$result) {
                $e = new Digitalus_View_Error();
                $e->add($this->view->getTranslation('The username or password you entered was not correct.'));
            } else {
                $uri = Digitalus_Toolbox_Page::getHomePageName();
                $this->_redirect($uri);
            }
        }

# ---------------------------------------------------------------------------- #

        $this->page->content = array(
            'label'    => 'Auth',
            'headline' => $this->view->getTranslation('Authorisation required'),
            'content'  => $this->view->partial('partials/login.phtml', array('form' => $form)),
        );
        $this->page->defaultContent = $this->page->content;

        // load the view
        Digitalus_Builder::loadPage(null, 'load_view.xml', $this->page, $this->view);

        // render the page
        $this->view->page           = $this->page;
        $this->view->layout()->page = $this->page->getParam('xhtml');

        $this->renderScript('index/index.phtml');
    }
}