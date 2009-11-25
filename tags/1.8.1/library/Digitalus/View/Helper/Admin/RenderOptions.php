<?php
/**
 * RenderOptions helper
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
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
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
 * RenderOptions helper
 *
* @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetRequest
 */
class Digitalus_View_Helper_Admin_RenderOptions extends Zend_View_Helper_Abstract
{
    public $optionsPath;
    public $defaultHeadline = 'Options';

    /**
     * This helper renders the admin options.
     *
     * You can add content before the body by setting options_before placeholder
     * You can add content after the body by setting options_after placeholder
     *
     * @param unknown_type $selectedItem
     * @param unknown_type $id
     * @return unknown
     */
    public function renderOptions($id = 'Options')
    {
        $this->setOptionsPath();

        //render the column first so you can set the headline pla
        $column = $this->renderBody();
        $headline = $this->renderHeadline();

        return $headline . $column;
    }

    public function renderHeadline()
    {
        return '<h2 class="top">' . $this->view->placeholder('optionsHeadline') . '</h2>';
    }

    public function renderBody()
    {
        $xhtml = '<div class="columnBody">';

        //you can add content before the body by setting options_before placeholder
        $xhtml .= $this->view->placeholder('options_before');

        $xhtml .= $this->view->render($this->optionsPath);

        //you can add content after the body by setting options_after placeholder
        $xhtml .= $this->view->placeholder('options_after');

        $xhtml .= '</div>';
        return $xhtml;
    }

    public function setOptionsPath()
    {
        $request = $this->view->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $this->optionsPath = $controller . '/' . $action . '.options.phtml';
    }
}