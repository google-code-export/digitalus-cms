<?php
class Admin_ReportController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Site Settings') => $this->getFrontController()->getBaseUrl() . '/admin/site'
        );
    }

    public function indexAction()
    {
    }


    /**
     * render the traffic report
     *
     */
    public function trafficAction()
    {
        $breadcrumbLabel = $this->view->GetTranslation('Traffic Report');
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->getFrontController()->getBaseUrl() . '/admin/report/traffic';
        $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/admin_report_traffic';
        $log = new TrafficLog();
        $this->view->hitsThisWeek = $log->getLogByDay();
        $this->view->hitsByWeek = $log->getLogByWeek();
    }

    /**
     * render the admin access log
     *
     */
    public function adminAccessAction()
    {
        $breadcrumbLabel = $this->view->GetTranslation('Admin Access Report');
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->getFrontController()->getBaseUrl() . '/admin/report/admin-access';
        $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/admin_report_admin-access';
        $log = new TrafficLog();
        $this->view->accessLog = $log->adminAccess();
    }
}