<?php
class DSF_View_Helper_Admin_SelectUser
{
    public function SelectUser($name, $value = null, $attribs = null, $currentUser = 0)
    {
        $u = new Model_User();
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
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}