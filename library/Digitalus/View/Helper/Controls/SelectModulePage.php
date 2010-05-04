<?php
/**
 * SelectModulePage helper
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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SelectModulePage helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Controls_SelectModulePage extends Zend_View_Helper_Abstract
{
    public function selectModulePage($name, $module, $value, $attribs = null)
    {
        $pages = Digitalus_Filesystem_File::getFilesByType(APPLICATION_PATH . '/modules/' . $module . '/views/scripts/public', 'phtml');
        if (is_array($pages)) {
            $options[] = $this->view->getTranslation('Select One');
            foreach ($pages as $page) {
                $page = Digitalus_Toolbox_Regex::stripFileExtension($page);
                $options[$page] = $page;
            }
            $form = new Digitalus_Form();
            $select = $form->createElement('select', $name, array(
                'multiOptions'  => $options,
            ));
            if (is_array($value)) {
                $select->setValue($value);
            }
            if (is_array($attribs)) {
                $select->setAttribs($attribs);
            }
            return $select;
        } else {
            return $this->view->getTranslation('There are no pages in this module');
        }
    }
}