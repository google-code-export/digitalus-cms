<?php
/**
 * LoadDefaultDesign helper
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
 * LoadDefaultDesign helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Interface_LoadDefaultDesign extends Zend_View_Helper_Abstract
{
    /**
     *
     */
    public function loadDefaultDesign()
    {
        $mdlDesign = new Model_Design();
        $design = $mdlDesign->getDefaultDesign();
        $mdlDesign->setDesign($design->id);

        //todo: this is duplicated in the builder

        //the design model returns the stylesheets organized by skin
        $skins = $mdlDesign->getStylesheets();
        if (is_array($skins)) {
            foreach ($skins as $skin => $styles) {
                if (is_array($styles)) {
                    foreach ($styles as $style) {
                        $this->view->headLink()->appendStylesheet('/skins/' . $skin . '/styles/' . $style);
                    }
                }
            }
        }

        $this->view->layout = $mdlDesign->getLayout();
    }
}