<?php
/**
 * Digitalus CMS
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
 * @author      LowTower - lowtower@gmx.de
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: SelectUser.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SelectGroup helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Admin_SelectGroup extends Zend_View_Helper_Abstract
{
    public function selectGroup($name, $value = null, $attribs = null, $currentGroup = null, $exclude = null, $excludeCurrent = true)
    {
        $mdlGroup = new Model_Group();
        $groups = $mdlGroup->getGroupNamesArray($exclude);

        $options[] = $this->view->getTranslation('Select Group');

        if (count($groups) > 0) {
            foreach ($groups as $group) {
                if (false == $excludeCurrent || (empty($currentGroup) || $group['name'] != $currentGroup)) {
                    if (isset($group['label']) && !empty($group['label'])) {
                        $options[$group['name']] = $group['label'];
                    } else {
                        $options[$group['name']] = $group['name'];
                    }
                }
            }
        }

        $form   = new Digitalus_Form();
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
    }
}