<?php
/**
 * LoadModule helper
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
 * LoadModule helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Zend_View_Helper_LoadModule extends Zend_View_Helper_Abstract
{
    /**
     * render a module page like news_showNewPosts
     */
    public function loadModule($module, $action, $params = array())
    {
        //validate the module
        $modules = Digitalus_Filesystem_Dir::getDirectories('./application/modules');

        // @todo: validate the action as well
        if (in_array($module, $modules)) {
            if (is_array($params)) {
                foreach ($params as $k => $v) {
                    $paramsArray[(string)$k] = (string)$v;
                }
            }
            return $this->view->action($action, 'public', 'mod_' . $module, $paramsArray);
        }
    }
}