<?php
class  DSF_View_Helper_Admin_CurrentAdminUser
{

    /**
     * comments
     */
    public function CurrentAdminUser($id = 'currentUser')
    {
        $u = new Model_User();
        $user = $u->getCurrentUser();

        if ($user) {
            $xhtml = "<ul id='{$id}'>
                    <li>" . $this->view->getTranslation('Current User') . ": {$user->first_name}  {$user->last_name}</li>
                    <li>" . $this->view->getTranslation('Role') . ": {$user->role}</li>
                    <li><a href='{$this->view->getBaseUrl()}/admin/auth/logout/'>" . $this->view->getTranslation('Log Out') . "</a></li>
                </ul>";
            return $xhtml;
        } else {
            return false;
        }
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}
