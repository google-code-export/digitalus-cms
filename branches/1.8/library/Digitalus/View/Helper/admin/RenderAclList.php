<?php


class Digitalus_View_Helper_Admin_RenderAclList
{
    public function RenderAclList($usersPermissions = array(), $id = 'aclList')
    {
        $this->permissions = $usersPermissions;

        $acl = new Digitalus_Acl();
        $resources = $acl->getResourceList();

        $xhtml = "<ul id='{$id}'>";

        foreach ($resources as $module => $resources){
            if (!is_array($resources)) {
                $key = $module;
                $xhtml .= '<li class="module">' . $this->view->formCheckbox("acl_resources[{$key}]", $this->hasPermision($key, $usersPermissions)) . $module;
            } else {
                $xhtml .= '<li class="module">' . $module;
                $xhtml .= '<ul>';

                foreach ($resources as $controller => $actions) {


                    if (!is_array($actions)) {
                        $key = $module . '_' . $controller;
                        $xhtml .= '<li class="controller">' . $this->view->formCheckbox("acl_resources[{$key}]", $this->hasPermision($key, $usersPermissions)) . $controller;
                    } else {
                        $xhtml .= '<li class="controller">' . $controller;
                        $xhtml .= '<ul>';

                        foreach ($actions as $action) {
                            $key = $module . '_' . $controller . '_' . $action;
                            $xhtml .= "<li class='action'>" . $this->view->formCheckbox("acl_resources[{$key}]", $this->hasPermision($key, $usersPermissions)) . $action . '</li>';
                        }

                        $xhtml .= '</ul>';
                    }
                    $xhtml .= '</li>'; //end of controller
                }

               $xhtml .= '</ul>';
            }
            $xhtml .= '</li>'; //end of module
        }

        $xhtml .= '</ul>';

        return $xhtml;

    }

    public function hasPermision($key, $userPermissions)
    {
        if (is_array($userPermissions) && isset($userPermissions[$key])) {
            $result = $userPermissions[$key];
            return intval($result);
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