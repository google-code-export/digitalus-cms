<?php
/**
 * SelectUser helper
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
 * @version     $Id: SelectUser.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SelectUser helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Admin_SelectUser extends Zend_View_Helper_Abstract
{
    public function selectUser($name, $value = null, $attribs = null, $currentUser = 0)
    {
        $u = new Model_User();
        $users = $u->fetchAll(null, 'first_name');

        $userArray[] = $this->view->getTranslation('Select User');

        if ($users->count() > 0) {
            foreach ($users as $user) {
                if ($user->name != $currentUser) {
                   $userArray[$user->name] = $user->first_name . ' ' . $user->last_name;
                }
            }
        }
        return $this->view->formSelect($name, $value, $attribs, $userArray);
    }
}