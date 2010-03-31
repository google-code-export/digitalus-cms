<?php
/**
 * SelectPage helper
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
 * SelectPage helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 * @uses        Digitalus_Form
 */
class Zend_View_Helper_SelectPage extends Zend_View_Helper_Abstract
{
    public function selectPage($name, $value = null, $attribs = null)
    {
        $mdlIndex = new Model_Page();
        $index = $mdlIndex->getIndex(0, 'name');

        $pages = array();
        $pages[0] = $this->view->getTranslation('Site Root');

        if (is_array($index)) {
            foreach ($index as $id => $page) {
                $pages[$id] = $page;
            }
        }
        $form   = new Digitalus_Form();
        $select = $form->createElement('select', $name, array(
            'multiOptions'  => $pages,
        ));
        if (is_array($attribs)) {
            $select->setAttribs($attribs);
        }
        return $select;
    }
}