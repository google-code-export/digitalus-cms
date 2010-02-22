<?php
/**
 * RenderSidebar helper
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
 * @author      Forrest Lyman
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderSidebar helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetRequest
 */
class Digitalus_View_Helper_Admin_RenderSidebar extends Zend_View_Helper_Abstract
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
    public $moduleSection = 'module';
    public $selectedSection;
    public $sidebarPath;
    public $defaultHeadline = 'Home';

    /**
     * this helper renders the admin sidebar.
     *
     * you can override the header by setting: view->language->sidebar_headline
     *
     * you can add content before the body by setting sidebar_before placeholder
     * you can add content after the body by setting sidebar_after placeholder
     *
     * @param unknown_type $selectedItem
     * @param unknown_type $id
     * @return unknown
     */
    public function renderSidebar($selectedItem = null, $id = 'Sidebar')
    {
        $this->setSidebarPath();

       //load the content first so you can set the headline in the sidebar
        $xmlContent = $this->renderBody();
        $xmlHeadline = $this->renderHeadline();

        return $xmlHeadline . $xmlContent;
    }

    public function renderHeadline()
    {
        $strHeadline = $this->view->placeholder('sidebarHeadline');
        if (empty($strHeadline)) {
            $strHeadline = $this->defaultHeadline;
        }
        return "<h2 class='top'>" . $strHeadline . "</h2>";
    }

    public function renderBody()
    {
        $xhtml = '<div class="columnBody">';

        //you can add content before the body by setting sidebar_before placeholder
        $xhtml .= $this->view->placeholder('sidebar_before');

        $xhtml .= $this->view->render($this->sidebarPath);

        //you can add content after the body by setting sidebar_after placeholder
        $xhtml .= $this->view->placeholder('sidebar_after');

        $xhtml .= "</div>";
        return $xhtml;
    }

    public function setSidebarPath()
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
        $this->sidebarPath = $this->selectedSection . '/sidebar.phtml';
    }
}