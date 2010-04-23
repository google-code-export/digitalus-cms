<?php
require_once 'Zend/Controller/Action.php';

class ErrorController extends Digitalus_Controller_Action
{
    /**
     * This action handles
     *    - Application errors
     *    - Errors in the controller chain arising from missing
     *      controller classes and/or action methods
     */
    public function errorAction()
    {
        $this->getHelper('layout')->disableLayout();
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->title = 'HTTP/1.1 404 Not Found';
                $this->view->message = $this->view->getTranslation('Sorry, we could not find the page you requested');
                break;
            default:
                // application error; display error page, but don't change
                // status code
                $this->view->title = $this->view->getTranslation('Application Error');
                $this->view->message = $errors->exception;
                break;
        }
    }

    /**
     * No auth action
     *
     * @return void
     */
    public function noAuthAction()
    {
    }
}