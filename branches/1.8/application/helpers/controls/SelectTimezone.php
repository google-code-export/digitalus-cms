<?php
/**
 * SelectTimezone helper
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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */

/**
 * @see Zend_View_Interface
 */
require_once 'Zend/View/Interface.php';

/**
 * SelectTimezone helper
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 * @uses       viewHelper DSF_View_Helper_Controls
 */
class DSF_View_Helper_Controls_SelectTimezone
{
    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */

    public function selectTimezone($name, $value, $attr = null)
    {
        $data = DSF_Validate_Timezone::getValidTimezones(null, true);

        return $this->view->formSelect($name, $value, $attr, $data);
    }

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
