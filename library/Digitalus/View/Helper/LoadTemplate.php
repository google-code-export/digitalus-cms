<?php
/**
 * LoadTemplate helper
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
 * LoadTemplate helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetBaseUrl
 */
class Digitalus_View_Helper_LoadTemplate extends Zend_View_Helper_Abstract
{
    public function loadTemplate($scope = 'public', $template = null, $page = null)
    {
        $templateConfig = Zend_Registry::get('config')->template;

        if (null == $template) {
            $template = $templateConfig->default->$scope->template;
        }

        if (null == $page) {
            $page = $templateConfig->default->$scope->page;
        }

        $pageFile = BASE_PATH . '/' . $templateConfig->pathToTemplates . '/' . $scope . '/' . $template . '/pages/' . $page . '.xml';
        $pageConfig = new Zend_Config_Xml($pageFile);

        // first load all of the style sheets
        $styleArray = $pageConfig->styles->toArray();

        if (is_array($styleArray)) {
            if (isset($styleArray['stylesheet'])) {
                if (is_array($styleArray['stylesheet'])) {
                    $templateStyles = $styleArray['stylesheet'];
                } else {
                    $templateStyles = array($styleArray['stylesheet']);
                }
            } else {
                $templateStyles = array();
            }

            if (isset($styleArray['import'])) {
                if (is_array($styleArray['import'])) {
                    $importStyles = $styleArray['import'];
                } else {
                    $importStyles = array($styleArray['import']);
                }
            } else {
                $importStyles = array();
            }

            $templatePath = $this->view->getBaseUrl() . '/' . $templateConfig->pathToTemplates . '/' . $scope . '/' . $template;
            foreach ($templateStyles as $style) {
                $this->view->headLink()->appendStylesheet($templatePath . '/styles/' . $style);
            }
            foreach ($importStyles as $style) {
                if (substr($style, 0, 4) != 'http') {
                    $style = $this->view->getBaseUrl() . '/' . $style;
                }
                $this->view->headLink()->appendStylesheet($style);
            }

            $this->view->addScriptPath(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/' . $scope . '/' . $template . '/layouts');
            $this->view->layout()->template = $this->view->render($page . '.phtml');
        }
    }
}