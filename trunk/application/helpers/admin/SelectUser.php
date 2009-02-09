<?php
class DSF_View_Helper_Admin_SelectUser
{
    public function SelectUser($name, $value = null, $attribs = null, $currentUser = 0)
    {
        $u = new User();
        $users = $u->fetchAll(null, 'first_name');

        $userArray[] = $this->view->GetTranslation('Select User');

        if ($users->count() > 0) {
            foreach ($users as $user) {
                if ($user->id != $currentUser) {
                   $userArray[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
            }
        }
        return $this->view->formSelect($name, $value, $attribs, $userArray);
    }

    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}