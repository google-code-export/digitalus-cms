<?php
/**
 * SelectModule helper
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
 * SelectModule helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Controls_SelectModule extends Zend_View_Helper_Abstract
{
    public function selectModule($name, $value, $attribs = array())
    {
        $options = array();
        $modules = Digitalus_Filesystem_Dir::getDirectories(APPLICATION_PATH . '/modules');
        if (is_array($modules)) {
            $options[] = $this->view->getTranslation('Select a module');
            $options = array_merge($options, $this->_getModuleForms());

            $attribs['multiple'] = false;
            $form = new Digitalus_Form();
            $select = $form->createElement('select', $name, array(
                'multiOptions'  => $options,
                'value'         => $value,
            ));
            if (is_array($attribs)) {
                $select->setAttribs($attribs);
            }
            return $select;
        } else {
            return $this->view->getTranslation('There are no modules currently installed');
        }
    }

    protected function _getModuleForms()
    {
        $moduleForms = array();
        $modules = Digitalus_Filesystem_Dir::getDirectories(APPLICATION_PATH . '/modules');
        if (is_array($modules)) {
            foreach ($modules as $module) {
                $pages = Digitalus_Filesystem_File::getFilesByType(APPLICATION_PATH . '/modules/' . $module . '/views/scripts/public', 'phtml');
                if (is_array($pages)) {
                    foreach ($pages as $page) {
                        if (strpos($page, '.form.')) {
                            $page = Digitalus_Toolbox_Regex::stripFileExtension($page);
                            $page = str_replace('.form', '', $page);
                            $moduleForms[$module . '_' . $page] = $this->view->getTranslation($module) . ' -> ' . $page;
                        }
                    }
                }
            }
            return $moduleForms;
        }
        return false;
    }
}