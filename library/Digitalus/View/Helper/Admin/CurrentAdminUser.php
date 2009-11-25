<?php
/**
 * CurrentAdminUser helper
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
 * CurrentAdminUser helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetBaseUrl
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class  Digitalus_View_Helper_Admin_CurrentAdminUser extends Zend_View_Helper_Abstract
{
    /**
     * comments
     */
    public function currentAdminUser($id = 'currentUser')
    {
        $u = new Model_User();
        $user = $u->getCurrentUser();

        if ($user) {
            $xhtml = '<ul id="' . $id . '">
                    <li>' . $this->view->getTranslation('Current User') . ': ' . $user->first_name . ' ' . $user->last_name . '</li>
                    <li>' . $this->view->getTranslation('Role') . ': ' . $user->role . '</li>
                    <li><a href="' . $this->view->getBaseUrl() . '/admin/auth/logout/">' . $this->view->getTranslation('Log Out') . '</a></li>
                </ul>';
            return $xhtml;
        } else {
            return false;
        }
    }
}