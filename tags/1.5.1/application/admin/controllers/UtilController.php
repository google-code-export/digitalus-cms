<?php

/**
 * Util
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class Admin_Util extends Zend_Controller_Action {

    public function renderPartialAction()
    {
        $partial = $this->_request->getParam('partial');
        if ($partial != null) {
            $this->view->partialKey = DSF_Toolbox_String::stripUnderscores($partial);
            $data = new stdClass();
            $data->get = $this->_request->getParams();
            $data->post = $_POST;
            $this->view->data = $data;
        } else {
            throw new Zend_Exception('Invalid placeholder passed');
        }
    }

}
?>