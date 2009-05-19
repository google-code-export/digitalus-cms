<?php
/**
 * DSF CMS
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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Report Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   DSF CMS
 * @package    DSF_CMS_Controllers
 * @version    $Id:
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_ReportController extends Zend_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Site Settings') => $this->getFrontController()->getBaseUrl() . '/admin/site'
        );
    }

    /**
     * The default action
     *
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * Traffic action
     *
     * Render the traffic report
     *
     * @return void
     */
    public function trafficAction()
    {
        $breadcrumbLabel = $this->view->getTranslation('Traffic Report');
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->getFrontController()->getBaseUrl() . '/admin/report/traffic';
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/admin_report_traffic'
            . '/label/' . $this->view->getTranslation('Report') . ':' . $this->view->getTranslation('Traffic');
        $log = new Model_TrafficLog();
        $this->view->hitsThisWeek = $log->getLogByDay();
        $this->view->hitsByWeek   = $log->getLogByWeek();
    }

    /**
     * Admin access action
     *
     * Render the admin access log
     *
     * @return void
     */
    public function adminAccessAction()
    {
        $breadcrumbLabel = $this->view->getTranslation('Admin Access Report');
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->getFrontController()->getBaseUrl() . '/admin/report/admin-access';
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url'
            . '/admin_report_admin-access'
            . '/label/' . $this->view->getTranslation('Report') . ':' . $this->view->getTranslation('Access');
        $log = new Model_TrafficLog();
        $this->view->accessLog = $log->adminAccess();
    }
}