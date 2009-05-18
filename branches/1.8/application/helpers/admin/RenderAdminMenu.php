<?php
class DSF_View_Helper_Admin_RenderAdminMenu
{
    public $sections = array(
        'index'      => 'index',
        'site'       => 'site',
        'report'     => 'site',
        'user'       => 'site',
        'page'       => 'page',
        'navigation' => 'navigation',
        'media'      => 'media',
        'design'     => 'design',
        'module'     => 'module'
    );
    public $defaultSection = 'index';
    public $moduleSection  = 'module';
    public $selectedSection;

    public $userModel;
    public $currentUser;

    public function RenderAdminMenu($selectedItem = null, $id = 'adminMenu')
    {
        $this->userModel = new Model_User();
        $this->currentUser = $this->userModel->getCurrentUser();

        $this->setSelectedSection();

        $menu = "<ul id='{$id}'>";

        if (!$this->currentUser) {
            $menu .= "<li class='med'><a href='{$this->view->getBaseUrl()}/admin/auth/login' id='loginLink' class='selected'>" . $this->view->getTranslation('Login') . "</a></li>";
        } else {
            if ($this->hasAccess('admin_index')) {
                $menu .= "<li class='small'><a href='{$this->view->getBaseUrl()}/admin' id='homeLink'" . $this->isSelected('index') . ">" . $this->view->getTranslation('Home') . "</a></li>";
            }

            if ($this->hasAccess('admin_site')) {
                $menu .= "<li class='small'><a href='{$this->view->getBaseUrl()}/admin/site' id='siteLink'" . $this->isSelected('site') . ">" . $this->view->getTranslation('Site') . "</a></li>";
            }

            if ($this->hasAccess('admin_page')) {
                $menu .= "<li class='med'><a href='{$this->view->getBaseUrl()}/admin/page' id='pageLink'" . $this->isSelected('page') . ">" . $this->view->getTranslation('Pages') . "</a></li>";
            }

            if ($this->hasAccess('admin_navigation')) {
                $menu .= "<li class='large'><a href='{$this->view->getBaseUrl()}/admin/navigation' id='navigationLink'" . $this->isSelected('navigation') . ">" . $this->view->getTranslation('Navigation') . "</a></li>";
            }

            if ($this->hasAccess('admin_media')) {
                $menu .= "<li class='med'><a href='{$this->view->getBaseUrl()}/admin/media' id='mediaLink'" . $this->isSelected('media') . ">" . $this->view->getTranslation('Media') . "</a></li>";
            }

            if ($this->hasAccess('admin_design')) {
                $menu .= "<li class='med'><a href='{$this->view->getBaseUrl()}/admin/design' id='designLink'" . $this->isSelected('design') . ">" . $this->view->getTranslation('Design') . "</a></li>";
            }

            if ($this->hasAccess('admin_module')) {
                $menu .= "<li class='med'><a href='{$this->view->getBaseUrl()}/admin/module' id='moduleLink'" . $this->isSelected('module') . ">" . $this->view->getTranslation('Modules') . "</a></li>";
            }
        }

        $menu .= '</ul>';

        return $menu;

    }

    public function isSelected($tab)
    {
        if ($tab == $this->selectedSection) {
            return " class='selected'";
        }
    }

    public function setSelectedSection()
    {
        $request = $this->view->getRequest();

        $module = $request->getModuleName();
        if (substr($module, 0, 4) == 'mod_') {
            $this->selectedSection = $this->moduleSection;
        } else {
            $controller = $request->getControllerName();
            if (isset($this->sections[$controller])) {
                $this->selectedSection = $this->sections[$controller];
            } else {
                $this->selectedSection = $this->defaultSection;
            }
        }
    }

    public function hasAccess($tab)
    {
        if ($this->currentUser) {
            if ($this->currentUser->role == Model_User::SUPERUSER_ROLE) {
                return true;
            } elseif ($this->userModel->queryPermissions($tab)) {
                return true;
            }
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