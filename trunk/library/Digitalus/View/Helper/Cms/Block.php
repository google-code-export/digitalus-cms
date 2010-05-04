<?php
/**
 * Block helper
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
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Block helper
 *
 * Renders a cms block
 * The blocks are formatted as:
 * module_block_action
 *
 * Note that in this case the module does not have the mod_ prefix
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Cms_Block extends Zend_View_Helper_Abstract
{
    public function block($request, $params = null)
    {
        //process the block request
        $requestArray = explode('_', $request);
        $module = $requestArray[0];
        $controller = $requestArray[1];
        $action = $requestArray[2];

        // set the block module path. note that this resolves differently than the standard modules
        $module = $module . '_blocks';

        //check to see if the module block has already been added
        $front = Zend_Controller_Front::getInstance();
        $modulePaths = $front->getControllerDirectory();
        if (!isset($modulePaths[$moduleBlock])) {
            $front->addControllerDirectory();
        }
    }
}